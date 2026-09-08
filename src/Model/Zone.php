<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Les zones de livraison, et ce que coûte d'y porter un exemplaire (lot G3).
 *
 * **Une table qui se référence elle-même**, à trois niveaux — pays, ville,
 * commune. Ils ont la même forme : un nom, un parent, un tarif. Trois tables
 * auraient triplé les écrans et les jointures, et un quatrième niveau — un
 * quartier d'Abidjan, un arrondissement de Paris — en aurait demandé une
 * quatrième.
 *
 * **Les frais s'héritent, et c'est ce qui rend le système tenable à la main.**
 * `frais` est nullable, et null veut dire « ceux du parent ». On pose « Côte
 * d'Ivoire = 2 000 », puis seulement les exceptions : « Abidjan = 1 500 »,
 * « Cocody = 1 000 ». Sans héritage, il faudrait un tarif pour chacune des
 * treize communes du district, et pour chaque commune ajoutée ensuite.
 *
 * **Une zone sans tarif résoluble n'est pas livrable**, et le tunnel ne la
 * propose pas : mieux vaut ne pas offrir la livraison quelque part que de la
 * facturer zéro franc.
 *
 * L'arbre est lu **une fois par requête** et gardé en mémoire. Un formulaire de
 * commande le parcourt plusieurs fois — les racines, les enfants, la résolution
 * du tarif, la chaîne lisible — et chaque parcours serait sinon une requête.
 */
final class Zone extends Modele
{
    protected const TABLE = 'zone_livraison';

    protected const ASSIGNABLES = ['parent_id', 'niveau', 'nom', 'code', 'frais', 'actif', 'ordre'];

    protected const ORDRE = 'ordre ASC, nom ASC, id ASC';

    /** Les trois étages, du plus large au plus fin. */
    public const NIVEAUX = [
        'pays'    => 'Pays',
        'ville'   => 'Ville',
        'commune' => 'Commune',
    ];

    /**
     * Le titre de l'écran de création, par niveau.
     *
     * **Écrits en toutes lettres et non composés.** « Nouveau pays » contre
     * « Nouvelle ville » : le genre ne se dérive de rien, et `'Nouvelle ' .
     * strtolower($niveau)` donne « Nouvelle pays ». `CrudController` a la même
     * note depuis le lot C, pour la même raison.
     */
    public const TITRES_CREATION = [
        'pays'    => 'Nouveau pays',
        'ville'   => 'Nouvelle ville',
        'commune' => 'Nouvelle commune',
    ];

    /** Le niveau qu'un enfant doit avoir, selon celui de son parent. */
    public const SOUS_NIVEAU = [
        'pays'  => 'ville',
        'ville' => 'commune',
    ];

    /**
     * L'arbre entier, indexé par identifiant, lu une fois par requête.
     *
     * @var array<int,array<string,mixed>>|null
     */
    private static ?array $cache = null;

    /**
     * Toutes les zones, indexées par identifiant.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function toutes(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $zones = [];

        foreach (Database::all('SELECT * FROM ' . self::TABLE . ' ORDER BY ' . self::ORDRE) as $z) {
            $zones[(int) $z['id']] = $z;
        }

        return self::$cache = self::traduireToutes($zones);
    }

    /**
     * Vide le cache de l'arbre. À appeler après toute écriture, sinon la page
     * qui suit l'enregistrement montre l'état d'avant.
     *
     * Nommée ainsi et non `oublier()` : `Modele::supprimer()` appelle
     * `Traduction::oublier()`, et deux méthodes du même nom pour deux oublis
     * différents se confondraient à la relecture.
     */
    public static function oublierCache(): void
    {
        self::$cache = null;
    }

    /**
     * Les enfants directs d'une zone, ou les racines si `$parent` est null.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function enfants(?int $parent, bool $actifsSeulement = true): array
    {
        $enfants = [];

        foreach (self::toutes() as $z) {
            $pere = $z['parent_id'] === null ? null : (int) $z['parent_id'];

            if ($pere !== $parent) {
                continue;
            }

            if ($actifsSeulement && (int) $z['actif'] !== 1) {
                continue;
            }

            $enfants[] = $z;
        }

        return $enfants;
    }

    /**
     * Les frais d'une zone, hérités du premier ancêtre qui en porte.
     *
     * Null quand personne n'en porte sur toute la remontée : la zone n'est pas
     * livrable, et c'est à l'appelant d'en tirer les conséquences plutôt qu'au
     * modèle de rendre zéro.
     */
    public static function frais(int $id): ?int
    {
        foreach (self::remontee($id) as $z) {
            if ($z['frais'] !== null) {
                return (int) $z['frais'];
            }
        }

        return null;
    }

    /**
     * De quelle zone le tarif vient réellement.
     *
     * Sert à l'écran d'administration, qui doit pouvoir dire « hérité de Côte
     * d'Ivoire » plutôt que d'afficher une case vide sans explication.
     */
    public static function origineFrais(int $id): ?array
    {
        foreach (self::remontee($id) as $z) {
            if ($z['frais'] !== null) {
                return $z;
            }
        }

        return null;
    }

    /**
     * La zone et ses ancêtres, du plus fin au plus large.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function remontee(int $id): array
    {
        $zones  = self::toutes();
        $chemin = [];
        $vu     = [];

        while (isset($zones[$id]) && !isset($vu[$id])) {
            $vu[$id]  = true;   // garde-fou : une boucle de parents ne doit pas figer la page
            $chemin[] = $zones[$id];
            $parent   = $zones[$id]['parent_id'];

            if ($parent === null) {
                break;
            }

            $id = (int) $parent;
        }

        return $chemin;
    }

    /**
     * La chaîne lisible d'une zone : « Côte d'Ivoire · Abidjan · Cocody ».
     *
     * **Recopiée dans la commande au moment de l'enregistrer**, en texte : une
     * zone peut être renommée ou supprimée, et une commande ancienne doit
     * rester lisible.
     */
    public static function chaine(int $id): string
    {
        $noms = array_map(
            static fn(array $z): string => (string) $z['nom'],
            array_reverse(self::remontee($id))
        );

        return implode(' · ', $noms);
    }

    /**
     * Peut-on livrer dans cette zone ?
     *
     * Trois conditions, et il les faut toutes : la zone existe, elle et tous
     * ses ancêtres sont actifs — décocher un pays ferme ses villes sans qu'on
     * ait à les décocher une à une —, et un tarif se résout quelque part sur
     * la remontée.
     */
    public static function livrable(int $id): bool
    {
        $remontee = self::remontee($id);

        if ($remontee === []) {
            return false;
        }

        foreach ($remontee as $z) {
            if ((int) $z['actif'] !== 1) {
                return false;
            }
        }

        return self::frais($id) !== null;
    }

    /**
     * Les zones qu'un formulaire public peut proposer, en arbre.
     *
     * Une branche entière disparaît si elle ne mène à aucune zone livrable :
     * proposer un pays pour découvrir qu'aucune de ses villes n'est desservie
     * fait remplir un formulaire pour rien.
     *
     * @return array<int,array{zone:array<string,mixed>,enfants:array<int,mixed>}>
     */
    public static function offertes(?int $parent = null): array
    {
        $offre = [];

        foreach (self::enfants($parent) as $z) {
            $id      = (int) $z['id'];
            $enfants = self::offertes($id);

            /*
             * Une zone se propose si elle est elle-même livrable, ou si elle
             * mène à quelque chose qui l'est. Un pays dont seule une commune
             * porte un tarif reste donc atteignable.
             */
            if ($enfants === [] && !self::livrable($id)) {
                continue;
            }

            $offre[] = ['zone' => $z, 'enfants' => $enfants];
        }

        return $offre;
    }

    /**
     * Les zones livrables, groupées par pays, prêtes pour un menu déroulant.
     *
     * **Un seul menu déroulant et non trois en cascade.** Trois menus liés
     * exigent du JavaScript pour se remplir l'un l'autre, et sans lui le
     * visiteur peut composer un triplet incohérent — une commune d'Abidjan
     * sous la France. Un menu unique porte le chemin complet et son tarif, se
     * lit d'un coup d'œil, et fonctionne sans une ligne de script. À quinze
     * zones c'est le bon outil ; la question se reposerait à cinq cents.
     *
     * Le libellé montre **le tarif à côté de chaque zone** : c'est
     * l'information que le client cherche à ce moment précis, et la lui faire
     * découvrir après avoir choisi est une mauvaise surprise.
     *
     * @return array<int,array{pays:string,options:array<int,array{id:int,libelle:string,frais:int}>}>
     */
    public static function optionsPubliques(): array
    {
        $groupes = [];

        foreach (self::offertes() as $racine) {
            $options = [];
            self::aplatir($racine, '', $options);

            if ($options !== []) {
                $groupes[] = ['pays' => (string) $racine['zone']['nom'], 'options' => $options];
            }
        }

        return $groupes;
    }

    /**
     * Aplatit une branche en options, le chemin sous le pays servant de
     * libellé — « Abidjan · Cocody ». Le pays lui-même porte son propre nom.
     *
     * @param array{zone:array<string,mixed>,enfants:array<int,mixed>} $noeud
     * @param array<int,array{id:int,libelle:string,frais:int}>        $options
     */
    private static function aplatir(array $noeud, string $prefixe, array &$options): void
    {
        $z   = $noeud['zone'];
        $id  = (int) $z['id'];
        $nom = (string) $z['nom'];

        $chemin = $prefixe === '' ? $nom : $prefixe . ' · ' . $nom;

        if (self::livrable($id)) {
            /*
             * Une zone qui porte elle-même des zones livrables désigne « tout
             * le reste » : choisir « Côte d'Ivoire » quand Abidjan figure
             * juste en dessous, c'est dire « ailleurs dans le pays ». Le
             * libellé le dit, sans quoi les deux lignes se ressemblent et le
             * client prend la première.
             */
            $reste = match (true) {
                $noeud['enfants'] === []  => '',
                (string) $z['niveau'] === 'pays'  => ' (autre ville)',
                (string) $z['niveau'] === 'ville' => ' (autre commune)',
                default                            => '',
            };

            $options[] = [
                'id'      => $id,
                'libelle' => $chemin . $reste,
                'frais'   => (int) self::frais($id),
            ];
        }

        foreach ($noeud['enfants'] as $enfant) {
            // Le pays ne se répète pas dans le chemin de ses enfants : il est
            // déjà le titre du groupe, et « Côte d'Ivoire · Abidjan · Cocody »
            // dans une liste intitulée « Côte d'Ivoire » dit deux fois la même
            // chose.
            self::aplatir($enfant, $prefixe === '' && $z['parent_id'] === null ? '' : $chemin, $options);
        }
    }

    /** Y a-t-il au moins une zone livrable ? Sinon le tunnel n'offre que le retrait. */
    public static function livraisonPossible(): bool
    {
        return self::offertes() !== [];
    }

    /**
     * Une zone a-t-elle des enfants ? L'écran d'administration refuse de
     * supprimer un parent — la contrainte le refuserait de toute façon, mais
     * une erreur SQL brute n'explique rien à l'éditeur.
     */
    public static function aDesEnfants(int $id): bool
    {
        return self::enfants($id, false) !== [];
    }

    /** Le libellé d'un niveau ; la clé brute si elle est inconnue. */
    public static function niveau(?string $cle): string
    {
        return self::NIVEAUX[(string) $cle] ?? (string) $cle;
    }
}
