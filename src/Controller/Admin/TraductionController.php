<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Csrf;
use App\Core\Database;
use App\Core\Langue;
use App\Core\Session;
use App\Core\Traduction;
use App\Core\View;
use App\Model\Actualite;
use App\Model\Archive;
use App\Model\Citation;
use App\Model\Evenement;
use App\Model\Heritage;
use App\Model\Parametre;
use App\Model\Periode;
use App\Model\PointDeVente;
use App\Model\Repere;
use App\Model\Zone;

/**
 * L'écran de traduction des contenus (lot G11).
 *
 * `Traduction::poser()` portait depuis le lot G1 le commentaire « écrit par le
 * back-office (lot G11) » et n'était appelée nulle part : remplir la table
 * demandait d'écrire du SQL à la main. C'est ce que cet écran répare.
 *
 * **Un écran à part, et non un panneau greffé sur chaque fiche.** Trois
 * raisons, dans cet ordre :
 *
 * 1. **Traduire n'est pas éditer.** Ce sont deux gestes, souvent deux
 *    personnes, et parfois deux moments séparés de plusieurs semaines. Un
 *    traducteur n'a pas à traverser un formulaire de saisie — avec son
 *    sélecteur d'images et son bouton de publication — pour faire son travail.
 * 2. **Le français doit se lire en regard.** Traduire à l'aveugle, en
 *    retrouvant l'original ailleurs, est le meilleur moyen de traduire à côté.
 *    Cet écran met la source et la cible côte à côte, champ par champ.
 * 3. **Rien ne se publie ici.** Une traduction posée ne change pas le statut
 *    de la ligne, ne touche pas au français, et n'a aucun effet tant que
 *    l'anglais est fermé. C'est un écran qu'on peut confier sans risque.
 *
 * **Il paraît avant que l'anglais ne soit ouvert**, et c'est tout l'intérêt :
 * la matière se verse pendant que `/en/` répond encore 404. Il s'appuie donc
 * sur `Langue::LANGUES` — les langues *déclarées* — et non sur `ouvertes()`.
 */
final class TraductionController
{
    /**
     * Ce qui se traduit, et sous quel libellé.
     *
     * **Déclaré ici et non déduit des colonnes de la table.** Une entité porte
     * des champs qui ne se traduisent pas — un slug, une année, un crédit
     * photo, une URL de vidéo — et proposer de traduire un slug est une
     * invitation à casser une adresse. La liste est donc explicite, et le jour
     * où une colonne s'ajoute, elle ne devient traduisible que si on l'écrit.
     *
     * `zone` dit la hauteur du champ de saisie : un titre tient sur une ligne,
     * un récit non.
     *
     * @var array<string,array{
     *   modele: class-string, titre: string, ordre: string,
     *   champs: array<string,array{libelle:string,zone:bool}>
     * }>
     */
    private const ENTITES = [
        'periode' => [
            'modele' => Periode::class,
            'titre'  => 'Biographie — périodes',
            'ordre'  => 'debut IS NULL, debut ASC, id ASC',
            'champs' => [
                'titre'      => ['libelle' => 'Titre',       'zone' => false],
                'sous_titre' => ['libelle' => 'Sous-titre',  'zone' => false],
                'recit'      => ['libelle' => 'Récit',       'zone' => true],
            ],
        ],
        /*
         * Les citations (lot G14). `source` se traduit autant que `texte` :
         * « Une destinée, chapitre IV » se dit « chapter IV » en anglais, et
         * « Assemblée nationale, 7 août 1960 » demande le nom anglais de
         * l'institution.
         */
        'citation' => [
            'modele' => Citation::class,
            'titre'  => 'Citations',
            'ordre'  => 'emplacement ASC, id DESC',
            'champs' => [
                'texte'  => ['libelle' => 'Texte',  'zone' => true],
                'source' => ['libelle' => 'Source', 'zone' => false],
            ],
        ],
        'repere' => [
            'modele' => Repere::class,
            'titre'  => 'Repères de la frise',
            'ordre'  => 'tri ASC, id ASC',
            'champs' => [
                'annee'  => ['libelle' => 'Date affichée', 'zone' => false],
                'titre'  => ['libelle' => 'Titre',         'zone' => false],
                'notice' => ['libelle' => 'Notice',        'zone' => true],
            ],
        ],
        'archive' => [
            'modele' => Archive::class,
            'titre'  => 'Archives',
            'ordre'  => 'annee IS NULL, annee ASC, id ASC',
            'champs' => [
                'titre'         => ['libelle' => 'Titre',              'zone' => false],
                'date_texte'    => ['libelle' => 'Date affichée',      'zone' => false],
                'lieu'          => ['libelle' => 'Lieu',               'zone' => false],
                'description'   => ['libelle' => 'Description',        'zone' => true],
                'contexte'      => ['libelle' => 'Contexte historique','zone' => true],
                'transcription' => ['libelle' => 'Transcription',      'zone' => true],
            ],
        ],
        'heritage' => [
            'modele' => Heritage::class,
            'titre'  => 'Héritage',
            'ordre'  => 'ordre ASC, id ASC',
            'champs' => [
                'titre'       => ['libelle' => 'Titre',         'zone' => false],
                'sous_titre'  => ['libelle' => 'Sous-titre',    'zone' => false],
                'date_texte'  => ['libelle' => 'Date affichée', 'zone' => false],
                'lieu'        => ['libelle' => 'Lieu',          'zone' => false],
                'description' => ['libelle' => 'Description',   'zone' => true],
            ],
        ],
        'actualite' => [
            'modele' => Actualite::class,
            'titre'  => 'Actualités',
            'ordre'  => 'publie_le DESC, id DESC',
            'champs' => [
                'titre'   => ['libelle' => 'Titre',   'zone' => false],
                'chapo'   => ['libelle' => 'Chapô',   'zone' => true],
                'contenu' => ['libelle' => 'Contenu', 'zone' => true],
            ],
        ],
        /*
         * Les zones de livraison (lot G3). Seul le nom se traduit : le code
         * ISO, le tarif et le rang ne sont pas de la langue. « Abidjan » ne se
         * traduit pas non plus, mais « Reste du monde » si — et c'est
         * l'éditeur qui sait lesquelles valent la peine.
         */
        'zone_livraison' => [
            'modele' => Zone::class,
            'titre'  => 'Zones de livraison',
            'ordre'  => 'niveau ASC, ordre ASC, nom ASC',
            'champs' => [
                'nom' => ['libelle' => 'Nom', 'zone' => false],
            ],
        ],
        'evenement' => [
            'modele' => Evenement::class,
            'titre'  => 'Événements',
            'ordre'  => 'debut_le DESC, id DESC',
            'champs' => [
                'titre'       => ['libelle' => 'Titre',       'zone' => false],
                'lieu'        => ['libelle' => 'Lieu',        'zone' => false],
                'description' => ['libelle' => 'Description', 'zone' => true],
            ],
        ],
        /*
         * Les points de vente (lot G12). Ni l'enseigne, ni le téléphone, ni le
         * site : une enseigne est un nom propre, et les deux autres ne sont pas
         * de la langue. La ville pour « Londres » contre « London » ; l'adresse
         * parce qu'elle porte souvent un repère plutôt qu'un numéro — « face à
         * la cathédrale » se traduit, le nom de la rue non. À l'éditeur de voir
         * lesquelles valent la peine, comme pour les zones de livraison.
         */
        'point_de_vente' => [
            'modele' => PointDeVente::class,
            'titre'  => 'Points de vente',
            'ordre'  => 'ordre ASC, ville ASC, id ASC',
            'champs' => [
                'ville'   => ['libelle' => 'Ville',   'zone' => false],
                'adresse' => ['libelle' => 'Adresse', 'zone' => false],
            ],
        ],
    ];

    /**
     * Les contenus de `parametre`, qui n'ont pas d'identifiant.
     *
     * **`parametre` a `cle` pour clé primaire et pas d'`id`** — or `Traduction`
     * s'indexe sur un entier. Le trou était réel : `preface_texte`,
     * `preface_extrait` et `auteur_bio` sont de la prose, pas des réglages, et
     * seraient restés français sur une page anglaise.
     *
     * Il se comble **sans migration** : la table `traduction` accepte
     * `ligne_id = 0`, aucune ligne de `parametre` n'ayant d'identifiant qui
     * puisse entrer en collision, et sa clé unique porte déjà sur le quadruplet
     * `(entite, ligne_id, langue, champ)`. Le nom du paramètre tient donc lieu
     * de `champ`, ce qu'il est déjà.
     *
     * Seuls les textes suivis : un ISBN, un prix ou une date de parution ne se
     * traduisent pas, et les proposer aurait fait un écran de bruit.
     *
     * **Deux fiches depuis le lot G16**, et non une seule qui aurait grossi :
     * le livre et son auteur d'un côté, les textes des pages de l'autre. Ce ne
     * sont ni les mêmes écrans de saisie, ni forcément le même traducteur, et
     * un contexte historique glissé sous « Le livre et son auteur » ne se
     * trouverait pas. Toutes deux écrivent sous `entite = 'parametre'` et
     * `ligne_id = 0` — c'est là que `Parametre` les lit ; la clé de la fiche ne
     * sert qu'à l'adresse et au choix des champs.
     *
     * `lignes`, facultatif, règle la hauteur d'un champ `zone` : un titre en
     * deux lignes n'a pas à ouvrir la boîte d'un récit.
     *
     * @var array<string,array{
     *   titre: string, resume: string,
     *   champs: array<string,array{libelle:string,zone:bool,lignes?:int}>
     * }>
     */
    private const FICHES_PARAMETRES = [
        'livre' => [
            'titre'  => 'Le livre et son auteur',
            'resume' => "Titre, préface, biographie de l'auteur",
            'champs' => [
                'livre_titre'     => ['libelle' => "Titre de l'ouvrage",       'zone' => false],
                'livre_format'    => ['libelle' => 'Format',                   'zone' => false],
                'preface_qualite' => ['libelle' => 'Qualité du préfacier',     'zone' => false],
                'preface_extrait' => ['libelle' => 'Extrait de la préface',    'zone' => true],
                'preface_texte'   => ['libelle' => 'Texte de la préface',      'zone' => true],
                'auteur_qualite'  => ['libelle' => "Qualité de l'auteur",      'zone' => false],
                'auteur_bio'      => ['libelle' => "Biographie de l'auteur",   'zone' => true],
            ],
        ],
        /*
         * Les textes des pages (lot G16). Un titre de contexte laissé vide en
         * français n'a rien à traduire ici : la page prend alors celui du
         * lexique, qui existe déjà dans les deux langues — voir
         * `templates/pages/biographie.php`. Même règle pour le diaporama et la
         * section « L'homme » de l'accueil (lot G17) : un champ vide y garde son
         * texte du lexique.
         * Ses images ne se traduisent pas, elles ne figurent donc pas ici.
         */
        'textes' => [
            'titre'  => 'Textes des pages',
            'resume' => "Accueil, présentation du livre, contexte de la biographie",
            'champs' => [
                'accueil_hero_1_titre'    => ['libelle' => 'Diapositive 1 — titre',    'zone' => true, 'lignes' => 2],
                'accueil_hero_1_accroche' => ['libelle' => 'Diapositive 1 — accroche', 'zone' => true, 'lignes' => 3],
                'accueil_hero_1_bouton'   => ['libelle' => 'Diapositive 1 — bouton',   'zone' => false],
                'accueil_hero_1_lien'     => ['libelle' => 'Diapositive 1 — lien',     'zone' => false],
                'accueil_hero_2_titre'    => ['libelle' => 'Diapositive 2 — titre',    'zone' => true, 'lignes' => 2],
                'accueil_hero_2_accroche' => ['libelle' => 'Diapositive 2 — accroche', 'zone' => true, 'lignes' => 3],
                'accueil_hero_2_bouton'   => ['libelle' => 'Diapositive 2 — bouton',   'zone' => false],
                'accueil_hero_3_titre'    => ['libelle' => 'Diapositive 3 — titre',    'zone' => true, 'lignes' => 2],
                'accueil_hero_3_accroche' => ['libelle' => 'Diapositive 3 — accroche', 'zone' => true, 'lignes' => 3],
                'accueil_hero_3_bouton'   => ['libelle' => 'Diapositive 3 — bouton',   'zone' => false],
                'accueil_hero_3_lien'     => ['libelle' => 'Diapositive 3 — lien',     'zone' => false],
                'accueil_homme_titre'       => ['libelle' => 'Accueil, « L\'homme » — titre', 'zone' => true, 'lignes' => 2],
                'accueil_homme_texte'       => ['libelle' => 'Accueil, « L\'homme » — texte', 'zone' => true],
                'livre_presentation_quatrieme' => ['libelle' => 'Le livre — quatrième de couverture', 'zone' => true, 'lignes' => 4],
                'livre_presentation_resume'    => ['libelle' => 'Le livre — résumé long', 'zone' => true],
                'biographie_contexte_titre' => ['libelle' => 'Biographie — titre du contexte', 'zone' => true, 'lignes' => 2],
                'biographie_contexte_texte' => ['libelle' => 'Biographie — texte du contexte', 'zone' => true],
            ],
        ],
    ];

    // -- Écrans ------------------------------------------------------------

    /** L'index : une entité par ligne, et où en est la traduction. */
    public static function index(): void
    {
        $langues = self::languesCibles();

        $rubriques = [];

        foreach (self::ENTITES as $entite => $def) {
            $modele = $def['modele'];
            $lignes = Database::all(
                'SELECT id FROM ' . $modele::table() . ' ORDER BY ' . $def['ordre']
            );

            $rubriques[] = [
                'entite'    => $entite,
                'titre'     => $def['titre'],
                'total'     => count($lignes),
                'champs'    => count($def['champs']),
                'traduites' => self::comptees($entite, $langues),
            ];
        }

        View::admin('traductions/index', [
            'titre'     => 'Traductions',
            'actif'     => 'traductions',
            'langues'   => $langues,
            'rubriques' => $rubriques,
            'parametres'=> self::rubriquesParametres($langues),
        ]);
    }

    /** La liste des lignes d'une entité, avec l'état de chacune. */
    public static function entite(array $params): void
    {
        $entite = (string) $params['entite'];

        /*
         * `parametre` n'a pas de lignes : ses fiches — le livre, les textes
         * des pages — sont listées directement sur l'index, qui renvoie vers
         * `/traductions/parametre/{fiche}`. Cette adresse-ci n'a donc rien à
         * montrer qui ne soit déjà là-bas ; elle y ramène plutôt que de
         * répondre 404 à un ancien favori.
         */
        if ($entite === 'parametre') {
            header('Location: ' . \App\Core\Admin::url('/traductions'), true, 302);
            exit;
        }

        $def = self::ENTITES[$entite] ?? null;

        if ($def === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'traductions'], 404);
            exit;
        }

        $modele  = $def['modele'];
        $langues = self::languesCibles();
        $colonne = array_key_first($def['champs']);

        $lignes = Database::all(
            'SELECT id, ' . $colonne . ' AS intitule FROM ' . $modele::table()
            . ' ORDER BY ' . $def['ordre']
        );

        $faites = self::parLigne($entite, $langues);

        View::admin('traductions/entite', [
            'titre'   => 'Traductions — ' . $def['titre'],
            'actif'   => 'traductions',
            'entite'  => $entite,
            'def'     => $def,
            'lignes'  => $lignes,
            'faites'  => $faites,
            'langues' => $langues,
        ]);
    }

    /** La fiche d'une ligne : le français à gauche, la cible à droite. */
    public static function fiche(array $params): void
    {
        $entite = (string) $params['entite'];

        if ($entite === 'parametre') {
            self::ficheParametres((string) $params['id']);
        }

        $def = self::ENTITES[$entite] ?? null;
        $id  = (int) $params['id'];

        if ($def === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'traductions'], 404);
            exit;
        }

        $modele = $def['modele'];
        $source = Database::one('SELECT * FROM ' . $modele::table() . ' WHERE id = ?', [$id]);

        if ($source === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'traductions'], 404);
            exit;
        }

        View::admin('traductions/fiche', [
            'titre'    => 'Traduire — ' . $def['titre'],
            'actif'    => 'traductions',
            'entite'   => $entite,
            'def'      => $def,
            'id'       => $id,
            'source'   => $source,
            'cibles'   => self::posees($entite, $id),
            'langues'  => self::languesCibles(),
            'retour'   => '/traductions/' . $entite,
        ]);
    }

    /**
     * Une fiche de paramètres : même écran, source prise ailleurs.
     *
     * `id` porte ici la clé de la fiche — `livre`, `textes` — et non un
     * identifiant de ligne : le gabarit ne s'en sert que pour l'adresse du
     * formulaire, et `enregistrer()` la retraduit en `Traduction::SANS_ID`.
     */
    private static function ficheParametres(string $cle): never
    {
        $fiche = self::FICHES_PARAMETRES[$cle] ?? null;

        if ($fiche === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'traductions'], 404);
            exit;
        }

        $reglages = Parametre::toutes();
        $source   = [];

        foreach (array_keys($fiche['champs']) as $champ) {
            $source[$champ] = (string) ($reglages[$champ] ?? '');
        }

        View::admin('traductions/fiche', [
            'titre'   => 'Traduire — ' . $fiche['titre'],
            'actif'   => 'traductions',
            'entite'  => 'parametre',
            'def'     => ['titre' => $fiche['titre'], 'champs' => $fiche['champs']],
            'id'      => $cle,
            'source'  => $source,
            'cibles'  => self::posees('parametre', Traduction::SANS_ID),
            'langues' => self::languesCibles(),
            'retour'  => '/traductions',
        ]);

        exit;
    }

    /** Enregistrement. Une seule ligne, une seule langue à la fois. */
    public static function enregistrer(array $params): void
    {
        Csrf::exiger();

        $entite = (string) $params['entite'];

        /*
         * Les fiches de paramètres ont une clé et non un identifiant : toutes
         * écrivent sous `Traduction::SANS_ID`, et la clé ne choisit que les
         * champs acceptés. Un champ d'une autre fiche glissé dans le formulaire
         * est donc ignoré, comme n'importe quel champ inconnu.
         */
        if ($entite === 'parametre') {
            $id     = Traduction::SANS_ID;
            $champs = self::FICHES_PARAMETRES[(string) $params['id']]['champs'] ?? null;
        } else {
            $id     = (int) $params['id'];
            $champs = self::ENTITES[$entite]['champs'] ?? null;
        }

        if ($champs === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'traductions'], 404);
            exit;
        }

        $saisies = $_POST['traduction'] ?? [];
        $posees  = 0;

        foreach (self::languesCibles() as $code => $infos) {
            foreach (array_keys($champs) as $champ) {
                if (!isset($saisies[$code]) || !array_key_exists($champ, $saisies[$code])) {
                    continue;
                }

                /*
                 * Une valeur vide **efface** la traduction plutôt que
                 * d'enregistrer une chaîne vide : c'est `Traduction::poser()`
                 * qui s'en charge, et c'est ce qui fait qu'un champ vidé
                 * retombe sur le français au lieu d'afficher un blanc.
                 */
                Traduction::poser($entite, $id, $code, $champ, (string) $saisies[$code][$champ]);
                $posees++;
            }
        }

        Session::message('succes', $posees === 0
            ? 'Aucune modification.'
            : 'Traductions enregistrées.');

        $retour = $entite === 'parametre'
            ? '/traductions'
            : '/traductions/' . $entite . '/' . $id;

        header('Location: ' . \App\Core\Admin::url($retour), true, 303);
        exit;
    }

    // -- Rouages -----------------------------------------------------------

    /**
     * Les langues à traduire : toutes les langues **déclarées** sauf celle qui
     * fait foi.
     *
     * Déclarées et non ouvertes : c'est ce qui permet de verser la matière
     * pendant que `/en/` répond encore 404, ce qui est l'ordre normal des
     * choses — on n'ouvre pas une version qu'on n'a pas.
     *
     * @return array<string,array{nom:string,locale:string,active:bool}>
     */
    public static function languesCibles(): array
    {
        $langues = Langue::LANGUES;
        unset($langues[Langue::DEFAUT]);

        return $langues;
    }

    /**
     * Combien de champs sont traduits pour une entité, par langue.
     *
     * `$champs` restreint le compte à certaines colonnes : les deux fiches de
     * paramètres écrivent sous la même entité, et chacune ne doit compter que
     * les siennes.
     *
     * @param array<string,mixed> $langues
     * @param array<int,string>|null $champs
     * @return array<string,int>
     */
    private static function comptees(string $entite, array $langues, ?array $champs = null): array
    {
        $comptes = [];
        $filtre  = '';

        if ($champs !== null) {
            $filtre = $champs === []
                ? ' AND 0'
                : ' AND champ IN (' . implode(', ', array_fill(0, count($champs), '?')) . ')';
        }

        foreach (array_keys($langues) as $code) {
            $ligne = Database::one(
                'SELECT COUNT(*) AS n FROM traduction WHERE entite = ? AND langue = ?' . $filtre,
                [$entite, $code, ...($champs ?? [])]
            );
            $comptes[$code] = (int) ($ligne['n'] ?? 0);
        }

        return $comptes;
    }

    /**
     * Les fiches de paramètres, telles que l'index les liste.
     *
     * @param array<string,mixed> $langues
     * @return array<int,array{cle:string,titre:string,resume:string,traduites:array<string,int>}>
     */
    private static function rubriquesParametres(array $langues): array
    {
        $rubriques = [];

        foreach (self::FICHES_PARAMETRES as $cle => $fiche) {
            $rubriques[] = [
                'cle'       => $cle,
                'titre'     => $fiche['titre'],
                'resume'    => $fiche['resume'],
                'traduites' => self::comptees('parametre', $langues, array_keys($fiche['champs'])),
            ];
        }

        return $rubriques;
    }

    /**
     * Le compte de champs traduits, ligne par ligne.
     *
     * @param array<string,mixed> $langues
     * @return array<int,array<string,int>> id => [langue => compte]
     */
    private static function parLigne(string $entite, array $langues): array
    {
        $faites = [];

        foreach (Database::all(
            'SELECT ligne_id, langue, COUNT(*) AS n FROM traduction'
            . ' WHERE entite = ? GROUP BY ligne_id, langue',
            [$entite]
        ) as $l) {
            $faites[(int) $l['ligne_id']][(string) $l['langue']] = (int) $l['n'];
        }

        return $faites;
    }

    /**
     * Ce qui est déjà posé pour une ligne.
     *
     * Publique depuis le lot G16 : l'écran « Textes des pages » s'en sert pour
     * dire, sous chaque section, ce que la page anglaise affichera.
     *
     * @return array<string,array<string,string>> langue => [champ => valeur]
     */
    public static function posees(string $entite, int $id): array
    {
        $cibles = [];

        foreach (Database::all(
            'SELECT langue, champ, valeur FROM traduction WHERE entite = ? AND ligne_id = ?',
            [$entite, $id]
        ) as $l) {
            $cibles[(string) $l['langue']][(string) $l['champ']] = (string) $l['valeur'];
        }

        return $cibles;
    }
}
