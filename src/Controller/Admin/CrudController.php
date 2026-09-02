<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Televersement;
use App\Core\Validator;
use App\Core\View;
use App\Model\Media;

/**
 * Déroulé commun aux écrans de contenu : lister, créer, modifier, supprimer,
 * publier.
 *
 * Les trois entités du lot C suivent la même mécanique et ne diffèrent que par
 * leurs champs. Ce qui varie est déclaré par les sous-classes ; ce qui ne varie
 * pas — vérification du jeton, existence de la ligne, messages, redirections —
 * vit ici, écrit une fois.
 *
 * Le français impose une contrainte que l'anglais n'a pas : « enregistrée »
 * pour une actualité, « enregistré » pour un événement. Le genre fait donc
 * partie de la configuration, sinon un message sur deux serait fautif.
 */
abstract class CrudController
{
    /** Classe de modèle, sous-classe de App\Model\Modele. */
    abstract protected static function modele(): string;

    /**
     * `titre_creation` et `titre_edition` sont donnés en toutes lettres plutôt
     * que composés : « Nouvelle actualité » contre « Nouvel événement »
     * contre « Nouveau repère » — trois accords que rien ne dérive d'un genre.
     *
     * `media` ouvre le sélecteur d'illustration sur la fiche : la planche de
     * la médiathèque est alors chargée avec le formulaire. Les repères n'en
     * portent pas — une frise chronologique est du texte.
     *
     * @return array{
     *   cle: string, chemin: string, singulier: string, pluriel: string,
     *   titre_creation: string, titre_edition: string,
     *   feminin: bool, gabarit: string, media?: bool
     * }
     */
    abstract protected static function config(): array;

    /** Règles propres à l'entité. */
    abstract protected static function valider(array $post, ?int $id): Validator;

    /**
     * Colonnes à écrire, dérivées des champs validés.
     *
     * @return array<string,mixed>
     */
    abstract protected static function donnees(Validator $v, ?int $id): array;

    // -- Écrans ------------------------------------------------------------

    public static function liste(): void
    {
        $c = static::config();
        $modele = static::modele();

        View::admin($c['gabarit'] . '/liste', [
            'titre'  => $c['pluriel'],
            'actif'  => $c['cle'],
            'lignes' => $modele::lister(),
            'config' => $c,
            // DataTables n'est chargé que sur les listes : c'est le seul écran
            // qui s'en sert, et 470 Ko sur un formulaire seraient gratuits.
            'styles'  => [Admin::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')],
            'scripts' => [
                Admin::asset('vendors/datatables.net/jquery.dataTables.js'),
                Admin::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js'),
                Admin::asset('js/listes.js'),
            ],
        ]);
    }

    public static function formulaireCreation(): void
    {
        static::formulaire(null, [], []);
    }

    public static function formulaireEdition(array $params): void
    {
        $ligne = static::exigerLigne((int) $params['id']);
        static::formulaire($ligne, $ligne, []);
    }

    public static function enregistrer(): void
    {
        Csrf::exiger();
        static::ecrire(null);
    }

    public static function mettreAJour(array $params): void
    {
        Csrf::exiger();
        static::exigerLigne((int) $params['id']);
        static::ecrire((int) $params['id']);
    }

    public static function supprimer(array $params): void
    {
        Csrf::exiger();

        $c = static::config();
        $ligne = static::exigerLigne((int) $params['id']);
        $modele = static::modele();

        $modele::supprimer((int) $params['id']);

        Session::message('succes', sprintf(
            '%s « %s » a été supprimé%s.',
            $c['singulier'],
            $ligne['titre'] ?? '—',
            $c['feminin'] ? 'e' : ''
        ));

        static::rediriger($c['chemin']);
    }

    /**
     * Bascule brouillon / publié depuis la liste.
     *
     * C'est l'action la plus fréquente d'un éditeur : la faire passer par le
     * formulaire complet obligerait à rouvrir, modifier, enregistrer.
     */
    public static function basculerStatut(array $params): void
    {
        Csrf::exiger();

        $c = static::config();
        $ligne = static::exigerLigne((int) $params['id']);
        $modele = static::modele();

        $nouveau = ($ligne['statut'] ?? 'brouillon') === 'publie' ? 'brouillon' : 'publie';
        $modele::modifier((int) $params['id'], ['statut' => $nouveau]);

        // Tournure verbale plutôt qu'un participe passé : « passe en ligne »
        // s'accorde tout seul, là où « publiée / publié » demanderait le genre.
        Session::message('succes', sprintf(
            '« %s » %s.',
            $ligne['titre'] ?? '—',
            $nouveau === 'publie' ? 'passe en ligne' : 'repasse en brouillon'
        ));

        static::rediriger($c['chemin']);
    }

    // -- Rouages -----------------------------------------------------------

    private static function ecrire(?int $id): void
    {
        $c = static::config();
        $modele = static::modele();
        $v = static::valider($_POST, $id);

        if (!$v->estValide()) {
            // La saisie est renvoyée telle quelle avec ses erreurs : refaire
            // remplir un formulaire parce qu'un champ manquait est le meilleur
            // moyen de faire renoncer un éditeur.
            static::formulaire($id === null ? null : ['id' => $id], $_POST, $v->erreurs());
        }

        $donnees = static::donnees($v, $id);

        $e = $c['feminin'] ? 'e' : '';   // accord du participe passé

        if ($id === null) {
            $id = $modele::creer($donnees);
            $message = "a été créé$e.";
        } else {
            $modele::modifier($id, $donnees);
            $message = "a été enregistré$e.";
        }

        Session::message('succes', sprintf(
            '%s « %s » %s',
            $c['singulier'],
            $v->valeur('titre'),
            $message
        ));

        static::rediriger($c['chemin']);
    }

    /**
     * @param array<string,mixed>|null $ligne  la ligne en base, null en création
     * @param array<string,mixed>      $valeurs valeurs à afficher dans les champs
     * @param array<string,string>     $erreurs
     */
    private static function formulaire(?array $ligne, array $valeurs, array $erreurs): never
    {
        $c = static::config();
        $edition = $ligne !== null;

        $avecMedia = !empty($c['media']);

        View::admin($c['gabarit'] . '/formulaire', [
            'titre'   => $edition ? $c['titre_edition'] : $c['titre_creation'],
            'actif'   => $c['cle'],
            'edition' => $edition,
            'ligne'   => $ligne,
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
            'config'  => $c,
            // La planche entière, vignettes comprises : le sélecteur montre
            // les images, un menu déroulant de noms de fichiers ne dirait rien.
            'medias'  => $avecMedia ? Media::listerPar() : [],
            'scripts' => $avecMedia ? [Admin::asset('js/medias.js')] : [],
        ], $erreurs === [] ? 200 : 422);

        exit;
    }

    /**
     * @return array<string,mixed>
     */
    private static function exigerLigne(int $id): array
    {
        $modele = static::modele();
        $ligne = $modele::trouver($id);

        if ($ligne === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        return $ligne;
    }

    /**
     * Chaîne vide -> NULL.
     *
     * Un champ facultatif laissé vide doit valoir NULL en base et non ''. Les
     * deux se ressemblent en PHP mais pas en SQL : `WHERE source IS NULL` ne
     * trouve pas une chaîne vide, et `COALESCE` ne la remplace pas.
     */
    protected static function ouNull(string $valeur): ?string
    {
        return $valeur === '' ? null : $valeur;
    }

    /**
     * L'image choisie existe-t-elle toujours ?
     *
     * Le champ porte un chemin de fichier, soumis par un contrôle caché : il
     * se réécrit aussi bien qu'un autre. La forme est vérifiée, puis la
     * présence en base — sans quoi une fiche pourrait afficher n'importe quel
     * chemin, ou pointer sur une image supprimée entre l'ouverture du
     * formulaire et son enregistrement.
     */
    protected static function validerImage(Validator $v, string $champ = 'image'): void
    {
        $chemin = $v->valeur($champ);

        if ($chemin === '') {
            return;
        }

        if (!Televersement::formeValide($chemin) || Media::parFichier($chemin) === null) {
            $v->erreur($champ, "L'image choisie n'est plus dans la médiathèque. Choisissez-en une autre.");
        }
    }

    private static function rediriger(string $chemin): never
    {
        header('Location: ' . Admin::url($chemin), true, 302);
        exit;
    }
}
