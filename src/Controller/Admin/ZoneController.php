<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Boutique;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Zone;

/**
 * Les zones de livraison et leurs tarifs (lot G3).
 *
 * **Il n'étend pas `CrudController`, et c'est délibéré.** Le CRUD partagé
 * suppose une liste plate, un champ `titre` et une colonne `statut` ; une zone
 * a un arbre, un parent, un niveau qui découle du parent et une colonne
 * `actif`. Le plier aurait demandé plus de dérogations que d'écrire les six
 * écrans, et chaque dérogation aurait pesé sur les six entités qui s'en
 * servent déjà.
 *
 * **Le niveau n'est jamais saisi.** Il découle du parent : sous un pays on
 * crée une ville, sous une ville une commune. Un menu déroulant aurait permis
 * de ranger une commune sous un pays, et l'arbre aurait cessé de vouloir dire
 * quelque chose.
 */
final class ZoneController
{
    /*
     * **Écran réservé aux administrateurs.** Un tarif de livraison est une
     * décision commerciale : il s'ajoute à ce que le client paie, et le
     * modifier revient à changer un prix. Masquer l'entrée dans la barre
     * latérale ne suffirait pas — elle cache, elle ne protège pas ; chaque
     * point d'entrée l'exige donc lui-même, comme le fait l'écran des
     * commandes depuis le lot E2.
     */

    /** L'arbre entier, tarifs résolus, y compris les zones décochées. */
    public static function liste(): void
    {
        Auth::exigerAdmin();

        View::admin('zones/liste', [
            'titre'  => 'Zones de livraison',
            'actif'  => 'zones',
            'arbre'  => self::arbre(null),
            'ouvert' => Boutique::ouverte(),
        ]);
    }

    public static function formulaireCreation(): void
    {
        Auth::exigerAdmin();
        $parent = self::parentDemande();

        self::formulaire(null, [
            'parent_id' => $parent === null ? '' : (string) $parent['id'],
            'actif'     => '1',
        ], [], $parent);
    }

    public static function formulaireEdition(array $params): void
    {
        Auth::exigerAdmin();
        $zone   = self::exigerZone((int) $params['id']);
        $parent = $zone['parent_id'] === null ? null : Zone::trouver((int) $zone['parent_id']);

        self::formulaire($zone, $zone, [], $parent);
    }

    public static function enregistrer(): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();
        self::ecrire(null);
    }

    public static function mettreAJour(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();
        self::exigerZone((int) $params['id']);
        self::ecrire((int) $params['id']);
    }

    /**
     * Ouvre ou ferme une zone.
     *
     * Fermer un pays ferme tout ce qu'il porte, sans qu'on ait à décocher ses
     * villes une à une : `Zone::livrable()` remonte la chaîne et refuse dès
     * qu'un ancêtre est décoché. C'est le geste qu'on veut quand un
     * transporteur cesse de desservir un pays.
     */
    public static function basculer(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $zone = self::exigerZone((int) $params['id']);
        $neuf = (int) $zone['actif'] === 1 ? 0 : 1;

        Zone::modifier((int) $zone['id'], ['actif' => $neuf]);
        Zone::oublierCache();

        Session::message('succes', sprintf(
            '« %s » est %s.',
            $zone['nom'],
            $neuf === 1 ? 'de nouveau desservie' : 'fermée à la livraison'
        ));

        self::rediriger();
    }

    /**
     * Supprime une zone.
     *
     * **Refusée si elle porte des enfants.** La contrainte `RESTRICT` de la
     * table le refuserait de toute façon, mais une erreur SQL brute
     * n'expliquerait rien : supprimer la Côte d'Ivoire emporterait Abidjan et
     * ses treize communes, donc les tarifs qu'on venait d'y poser.
     *
     * Les commandes anciennes ne s'y opposent pas : `commande.zone_id` passe à
     * null et `zone_libelle` garde la chaîne lisible.
     */
    public static function supprimer(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $zone = self::exigerZone((int) $params['id']);

        if (Zone::aDesEnfants((int) $zone['id'])) {
            Session::message('erreur', sprintf(
                '« %s » porte des zones filles : supprimez-les d’abord, ou décochez-la '
                . 'pour cesser de la desservir sans rien perdre.',
                $zone['nom']
            ));

            self::rediriger();
        }

        Zone::supprimer((int) $zone['id']);
        Zone::oublierCache();

        Session::message('succes', sprintf('« %s » a été supprimée.', $zone['nom']));
        self::rediriger();
    }

    // -- Rouages -----------------------------------------------------------

    private static function ecrire(?int $id): void
    {
        $v      = self::valider($_POST, $id);
        $parent = self::parentValide($_POST['parent_id'] ?? '');

        if (!$v->estValide()) {
            self::formulaire($id === null ? null : ['id' => $id], $_POST, $v->erreurs(), $parent);
        }

        /*
         * Le niveau se déduit du parent, il ne se saisit pas : sous un pays on
         * crée une ville, sous une ville une commune, et sans parent un pays.
         */
        $niveau = $parent === null
            ? 'pays'
            : (Zone::SOUS_NIVEAU[(string) $parent['niveau']] ?? 'commune');

        $frais = trim((string) $v->valeur('frais'));

        $donnees = [
            'parent_id' => $parent === null ? null : (int) $parent['id'],
            'niveau'    => $niveau,
            'nom'       => $v->valeur('nom'),
            // Le code ISO n'a de sens que sur un pays ; ailleurs il resterait
            // une colonne vide qu'on finirait par remplir de n'importe quoi.
            'code'      => $niveau === 'pays' && $v->valeur('code') !== ''
                ? strtoupper($v->valeur('code'))
                : null,
            // Vide veut dire « hérite du parent », et non « gratuit » : c'est
            // toute la mécanique des tarifs, et le formulaire le dit.
            'frais'     => $frais === '' ? null : (int) $frais,
            'actif'     => $v->valeur('actif') === '1' ? 1 : 0,
            'ordre'     => (int) ($v->valeur('ordre') === '' ? 0 : $v->valeur('ordre')),
        ];

        if ($id === null) {
            $id = Zone::creer($donnees);
            $message = 'a été ajoutée.';
        } else {
            Zone::modifier($id, $donnees);
            $message = 'a été enregistrée.';
        }

        Zone::oublierCache();

        Session::message('succes', sprintf('La zone « %s » %s', $donnees['nom'], $message));
        self::rediriger();
    }

    /** @param array<string,mixed> $post */
    private static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('nom', 'Nom de la zone')
          ->longueur('nom', 'Nom de la zone', 2, 120)
          ->longueur('code', 'Code pays', 0, 40);

        if (trim((string) ($post['frais'] ?? '')) !== '') {
            // Zéro est permis : une livraison offerte est un tarif, pas une
            // absence de tarif — et c'est ce qui la distingue d'un héritage.
            $v->entier('frais', 'Frais de livraison', 0, 10000000);
        }

        if (trim((string) ($post['ordre'] ?? '')) !== '') {
            $v->entier('ordre', 'Rang', 0, 999);
        }

        $parent = self::parentValide($post['parent_id'] ?? '');

        /*
         * Une zone ne peut pas être son propre parent, ni descendre d'une de
         * ses filles : la remontée boucle, et l'écran se fige. `Zone::remontee`
         * a son garde-fou, mais mieux vaut refuser la saisie que la rattraper.
         */
        if ($id !== null && $parent !== null) {
            foreach (Zone::remontee((int) $parent['id']) as $ancetre) {
                if ((int) $ancetre['id'] === $id) {
                    $v->erreur('parent_id', 'Une zone ne peut pas être rattachée à elle-même '
                        . 'ni à l’une de ses zones filles.');
                    break;
                }
            }
        }

        // Trois niveaux, pas quatre : une commune ne porte pas de sous-zone.
        if ($parent !== null && !isset(Zone::SOUS_NIVEAU[(string) $parent['niveau']])) {
            $v->erreur('parent_id', sprintf(
                '« %s » est une commune : elle ne peut pas porter de zone fille.',
                $parent['nom']
            ));
        }

        return $v;
    }

    /**
     * @param array<string,mixed>|null $zone
     * @param array<string,mixed>      $valeurs
     * @param array<string,string>     $erreurs
     * @param array<string,mixed>|null $parent
     */
    private static function formulaire(?array $zone, array $valeurs, array $erreurs, ?array $parent): never
    {
        $edition = $zone !== null;
        $niveau  = $parent === null ? 'pays' : (Zone::SOUS_NIVEAU[(string) $parent['niveau']] ?? 'commune');

        View::admin('zones/formulaire', [
            'titre'   => $edition ? 'Modifier la zone' : (Zone::TITRES_CREATION[$niveau] ?? 'Nouvelle zone'),
            'actif'   => 'zones',
            'edition' => $edition,
            'zone'    => $zone,
            'parent'  => $parent,
            'niveau'  => $niveau,
            'herite'  => $parent === null ? null : Zone::frais((int) $parent['id']),
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
        ], $erreurs === [] ? 200 : 422);

        exit;
    }

    /**
     * L'arbre, prêt à afficher : chaque nœud porte son tarif résolu et d'où
     * il vient.
     *
     * @return array<int,array<string,mixed>>
     */
    private static function arbre(?int $parent): array
    {
        $noeuds = [];

        foreach (Zone::enfants($parent, false) as $z) {
            $id      = (int) $z['id'];
            $origine = Zone::origineFrais($id);

            $noeuds[] = [
                'zone'     => $z,
                'frais'    => Zone::frais($id),
                'origine'  => $origine,
                'propre'   => $z['frais'] !== null,
                'livrable' => Zone::livrable($id),
                'enfants'  => self::arbre($id),
            ];
        }

        return $noeuds;
    }

    /** @return array<string,mixed>|null */
    private static function parentDemande(): ?array
    {
        return self::parentValide($_GET['parent'] ?? '');
    }

    /** @return array<string,mixed>|null */
    private static function parentValide(mixed $brut): ?array
    {
        $id = (int) $brut;

        return $id > 0 ? Zone::trouver($id) : null;
    }

    /** @return array<string,mixed> */
    private static function exigerZone(int $id): array
    {
        $zone = Zone::trouver($id);

        if ($zone === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'zones'], 404);
            exit;
        }

        return $zone;
    }

    private static function rediriger(): never
    {
        header('Location: ' . Admin::url('/zones'), true, 303);
        exit;
    }
}
