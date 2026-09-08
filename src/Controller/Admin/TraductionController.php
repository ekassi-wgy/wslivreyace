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
use App\Model\Evenement;
use App\Model\Heritage;
use App\Model\Parametre;
use App\Model\Periode;
use App\Model\Repere;

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
     * @var array<string,array{libelle:string,zone:bool}>
     */
    private const PARAMETRES = [
        'livre_titre'     => ['libelle' => "Titre de l'ouvrage",       'zone' => false],
        'livre_format'    => ['libelle' => 'Format',                   'zone' => false],
        'preface_qualite' => ['libelle' => 'Qualité du préfacier',     'zone' => false],
        'preface_extrait' => ['libelle' => 'Extrait de la préface',    'zone' => true],
        'preface_texte'   => ['libelle' => 'Texte de la préface',      'zone' => true],
        'auteur_qualite'  => ['libelle' => "Qualité de l'auteur",      'zone' => false],
        'auteur_bio'      => ['libelle' => "Biographie de l'auteur",   'zone' => true],
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
            'parametres'=> [
                'total'     => count(self::PARAMETRES),
                'traduites' => self::comptees('parametre', $langues),
            ],
        ]);
    }

    /** La liste des lignes d'une entité, avec l'état de chacune. */
    public static function entite(array $params): void
    {
        $entite = (string) $params['entite'];

        /*
         * `parametre` n'a pas de lignes : le livre et son auteur, c'est une
         * fiche unique. L'index y renvoie donc par la même adresse que les
         * autres rubriques, et on saute l'étape de la liste plutôt que de
         * montrer un tableau d'une seule ligne.
         */
        if ($entite === 'parametre') {
            self::ficheParametres();
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
            self::ficheParametres();
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

    /** La fiche des paramètres : même écran, source prise ailleurs. */
    private static function ficheParametres(): never
    {
        $reglages = Parametre::toutes();
        $source   = [];

        foreach (array_keys(self::PARAMETRES) as $cle) {
            $source[$cle] = (string) ($reglages[$cle] ?? '');
        }

        View::admin('traductions/fiche', [
            'titre'   => 'Traduire — le livre et son auteur',
            'actif'   => 'traductions',
            'entite'  => 'parametre',
            'def'     => ['titre' => 'Le livre et son auteur', 'champs' => self::PARAMETRES],
            'id'      => Traduction::SANS_ID,
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
        $id     = (int) $params['id'];

        $champs = $entite === 'parametre'
            ? self::PARAMETRES
            : (self::ENTITES[$entite]['champs'] ?? null);

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
     * @param array<string,mixed> $langues
     * @return array<string,int>
     */
    private static function comptees(string $entite, array $langues): array
    {
        $comptes = [];

        foreach (array_keys($langues) as $code) {
            $ligne = Database::one(
                'SELECT COUNT(*) AS n FROM traduction WHERE entite = ? AND langue = ?',
                [$entite, $code]
            );
            $comptes[$code] = (int) ($ligne['n'] ?? 0);
        }

        return $comptes;
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
     * @return array<string,array<string,string>> langue => [champ => valeur]
     */
    private static function posees(string $entite, int $id): array
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
