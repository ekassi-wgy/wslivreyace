<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Boutique;
use App\Core\Database;
use App\Core\Langue;
use App\Core\Lexique;
use App\Core\Traduction;

/**
 * Contenus éditables sans toucher au code (CDC §4.2).
 *
 * Table clé/valeur, sans identifiant auto-incrémenté : elle n'hérite donc pas
 * de `Modele`, qui suppose une colonne `id`. La clé primaire est `cle`.
 *
 * Sert aujourd'hui à la fiche technique de l'ouvrage — les huit valeurs que
 * l'éditeur doit fournir avant mise en ligne. Le tableau ci-dessous est la
 * source de vérité : il décrit ce que le formulaire affiche, dans quel ordre,
 * et comment chaque valeur se valide.
 */
final class Parametre
{
    /**
     * Préface et auteur (lot G2).
     *
     * **La mise en avant de la préface est un réglage, pas un choix de
     * gabarit.** Le brief dit : « si la préface du Président de la République
     * se confirme, nous prévoirons une mise en avant spécifique ». Coder l'une
     * des deux formes aurait obligé à rouvrir la page le jour de la
     * confirmation — et remonter une préface, ce n'est pas déplacer un bloc,
     * c'est refaire la hiérarchie de la page.
     *
     * L'éditeur coche donc la case le jour venu : le bloc remonte en tête de
     * « Le livre » et un bandeau paraît sur l'accueil. Même principe que la
     * mise en avant des repères (lot G0) — un choix éditorial appartient à
     * l'éditeur, pas au code.
     *
     * `type` vaut `texte` (une ligne, 200 signes), `long` (plusieurs
     * paragraphes) ou `case` (oui/non).
     *
     * @var array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public const AUTOUR_LIVRE = [
        'preface_auteur' => [
            'libelle' => 'Nom du préfacier',
            'type'    => 'texte',
            'aide'    => 'Tel qu\'il doit être cité, sans abréviation.',
            'exemple' => 'Prénom NOM',
        ],
        'preface_qualite' => [
            'libelle' => 'Qualité du préfacier',
            'type'    => 'texte',
            'aide'    => 'Sa fonction, telle qu\'elle accompagnera la signature.',
            'exemple' => 'Président de la République de Côte d\'Ivoire',
        ],
        'preface_extrait' => [
            'libelle' => 'Extrait mis en exergue',
            'type'    => 'long',
            'aide'    => 'Une ou deux phrases, celles qui portent. Servent au bandeau de l\'accueil.',
            'exemple' => '',
        ],
        'preface_texte' => [
            'libelle' => 'Texte de la préface',
            'type'    => 'long',
            'aide'    => 'Le texte publié. Une ligne vide sépare deux paragraphes.',
            'exemple' => '',
        ],
        'preface_avant' => [
            'libelle' => 'Mettre la préface en avant',
            'type'    => 'case',
            'aide'    => 'À cocher quand la préface est confirmée : elle remonte en tête de la page du livre et un bandeau paraît sur l\'accueil.',
            'exemple' => '',
        ],
        'auteur_nom' => [
            'libelle' => 'Nom de l\'auteur',
            'type'    => 'texte',
            'aide'    => 'L\'auteur de l\'ouvrage. Sa page est publiée dès que ce nom est renseigné.',
            'exemple' => 'Prénom NOM',
        ],
        'auteur_qualite' => [
            'libelle' => 'Qualité de l\'auteur',
            'type'    => 'texte',
            'aide'    => 'Une ligne : profession, titre, rattachement.',
            'exemple' => 'Historien',
        ],
        'auteur_bio' => [
            'libelle' => 'Biographie de l\'auteur',
            'type'    => 'long',
            'aide'    => 'Sa page propre s\'en nourrit. Une ligne vide sépare deux paragraphes.',
            'exemple' => '',
        ],
    ];

    /**
     * @var array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public const FICHE_LIVRE = [
        'livre_titre' => [
            'libelle' => "Titre de l'ouvrage",
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Une destinée',
        ],
        'livre_auteur' => [
            'libelle' => 'Auteur',
            'type'    => 'texte',
            'aide'    => "L'auteur du livre, à ne pas confondre avec son sujet.",
            'exemple' => 'Prénom NOM',
        ],
        'livre_editeur' => [
            'libelle' => 'Éditeur',
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Nom de la maison d\'édition',
        ],
        'livre_parution' => [
            'libelle' => 'Date de parution',
            'type'    => 'texte',
            'aide'    => 'Texte libre : le mois suffit si le jour n\'est pas arrêté.',
            'exemple' => 'Mars 2026',
        ],
        'livre_pages' => [
            'libelle' => 'Nombre de pages',
            'type'    => 'entier',
            'aide'    => '',
            'exemple' => '320',
            'min'     => 1,
            'max'     => 10000,
        ],
        'livre_isbn' => [
            'libelle' => 'ISBN',
            'type'    => 'isbn',
            'aide'    => 'ISBN-13, avec ou sans tirets.',
            'exemple' => '978-2-1234-5678-9',
        ],
        'livre_prix' => [
            'libelle' => 'Prix en francs CFA',
            'type'    => 'entier',
            'aide'    => "Le nombre seul, sans espaces ni devise : le site l'affiche « 25 000 F CFA » "
                       . "et s'en sert pour calculer les commandes. Sans prix, la boutique reste fermée.",
            'exemple' => '25000',
            // Un livre à plus de dix millions de francs est une faute de
            // frappe, pas un tarif ; le plancher écarte le zéro, qui rendrait
            // l'ouvrage gratuit sans que personne ne s'en aperçoive.
            'min'     => 1,
            'max'     => 10000000,
        ],
        'livre_format' => [
            'libelle' => 'Format',
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Relié, 240 × 310 mm',
        ],
    ];

    /**
     * La boutique et la livraison (lot G3).
     *
     * **Un groupe à part et non des champs ajoutés à la fiche technique** : la
     * fiche décrit l'ouvrage, ceci décide s'il se vend. Les mêler ferait
     * qu'ouvrir la boutique passerait pour une correction de fiche, et
     * `ficheRemplie()` — qui compte ce qui reste à fournir avant mise en
     * ligne — se mettrait à réclamer un point de retrait.
     *
     * @var array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public const BOUTIQUE = [
        'boutique_ouverte' => [
            'libelle' => 'Ouvrir les commandes',
            'type'    => 'case',
            'aide'    => "À cocher le jour où l'ouvrage peut être remis. Décochée, la page "
                       . 'Commander reste en ligne et annonce que les commandes ouvriront à '
                       . "la parution — plutôt qu'un bouton qui ne fait rien. Un prix est "
                       . 'exigé en plus de la case : sans lui la boutique reste fermée.',
            'exemple' => '',
        ],
        'retrait_lieu' => [
            'libelle' => 'Point de retrait',
            'type'    => 'long',
            'aide'    => "L'adresse où l'on vient chercher un exemplaire, avec ses horaires. "
                       . "Vide, le retrait n'est pas proposé du tout : offrir « retrait sur "
                       . 'place » sans dire où est une promesse creuse, et le client s\'en '
                       . 'aperçoit après avoir commandé.',
            'exemple' => '',
        ],
        'commande_message' => [
            'libelle' => 'Message affiché après commande',
            'type'    => 'long',
            'aide'    => 'Ce que le client lit sur la page de confirmation, sous sa référence. '
                       . 'Le délai de rappel, le mode de paiement accepté à la remise, un numéro '
                       . "à joindre. Vide, le site s'en tient à sa formule standard.",
            'exemple' => '',
        ],
    ];

    /**
     * Les textes des pages publiques (lot G16).
     *
     * **Du texte de page, pas du contenu catalogué.** Le contexte historique de
     * la biographie n'a ni date, ni statut, ni seconde version en réserve : il
     * y en a un, et il est à sa place. C'est ce qui le range ici plutôt que
     * dans `citation`, dont la table garde un fonds où l'éditeur puise — voir
     * `sql/018_citation.sql`, « pourquoi une table et non trois paramètres ».
     * Le raisonnement vaut à l'envers.
     *
     * Il vivait dans `src/lang/fr.php`, avec sa consigne de rédaction affichée
     * en ligne — « Texte à rédiger. Situer le personnage… » — et ne pouvait
     * changer qu'avec une livraison de code.
     *
     * **Rangés par section**, et non à plat comme les groupes du livre : l'écran
     * « Textes des pages » en fait une carte chacune, et les autres textes
     * encore écrits dans le lexique — quatrième de couverture — viendront s'y
     * ajouter comme sections. Le diaporama de l'accueil l'a fait le premier.
     *
     * `type` vaut `titre` (court, un retour à la ligne y est permis et se
     * retrouve sur la page), `long` (une ligne vide sépare deux paragraphes),
     * `court` (un seul paragraphe), `ligne` (une ligne), `sommaire` (une entrée
     * par ligne, voir `sommaire()`), `image` (un chemin de la médiathèque, comme
     * la colonne `image` d'une actualité ; `attente` dit qu'un cadre d'attente
     * la remplace quand elle manque) ou `document` (un PDF de la médiathèque).
     *
     * **Deux sortes de sections.** Une section ordinaire disparaît quand son
     * texte `long` est vide. Une section `toujours` — les diapositives du
     * diaporama de l'accueil — ne disparaît jamais : chaque champ vide y garde
     * son texte par défaut, la clé de lexique `defaut`, déjà traduite. Un
     * diaporama amputé d'une diapositive casserait sa navigation, et il
     * existait avant l'écran : rien ne doit changer tant qu'on n'y touche pas.
     * `max` borne la longueur d'un champ ; 200 signes à défaut.
     *
     * **Bilingue par l'écran des traductions**, comme les autres paramètres :
     * `traduction` avec `ligne_id = 0`. Les clés traduisibles y sont déclarées
     * — voir `TraductionController::FICHES_PARAMETRES`.
     *
     * @var array<string,array{
     *   titre: string, page: string, chemin: string, aide: string,
     *   toujours?: bool,
     *   champs: array<string,array{libelle:string,type:string,aide:string,exemple:string,max?:int,defaut?:string}>
     * }>
     */
    public const TEXTES_PAGES = [
        'accueil_hero_1' => [
            'titre'    => 'Accueil — diapositive 1 du diaporama',
            'page'     => 'Accueil',
            'chemin'   => '/',
            'aide'     => 'Le bouton mène à la page du livre.',
            'toujours' => true,
            'champs'   => [
                'accueil_hero_1_titre' => [
                    'libelle' => 'Titre',
                    'type'    => 'titre',
                    'aide'    => 'En très grands caractères : deux ou trois mots. Chaque retour à la '
                               . 'ligne fait une ligne du titre.',
                    'exemple' => "Une\ndestinée",
                    'max'     => 60,
                    'defaut'  => 'accueil.hero.1_titre',
                ],
                'accueil_hero_1_accroche' => [
                    'libelle' => 'Accroche',
                    'type'    => 'court',
                    'aide'    => 'Une ou deux phrases sous le titre.',
                    'exemple' => '',
                    'max'     => 300,
                    'defaut'  => 'accueil.hero.1_lead',
                ],
                'accueil_hero_1_bouton' => [
                    'libelle' => 'Texte du bouton',
                    'type'    => 'ligne',
                    'aide'    => 'Deux ou trois mots. La destination du bouton ne change pas.',
                    'exemple' => '',
                    'max'     => 40,
                    'defaut'  => 'accueil.hero.1_cta',
                ],
                'accueil_hero_1_lien' => [
                    'libelle' => 'Texte du lien',
                    'type'    => 'ligne',
                    'aide'    => 'Le lien descend vers les repères de la frise ; il ne paraît que si des repères sont mis en avant.',
                    'exemple' => '',
                    'max'     => 40,
                    'defaut'  => 'accueil.hero.1_lien',
                ],
                'accueil_hero_1_image' => [
                    'libelle' => 'Image',
                    'type'    => 'image',
                    'aide'    => 'Une photographie en hauteur, 2000 × 2600 px, <strong>sujet dans la '
                               . 'moitié droite</strong> : la gauche passe sous le texte. Sans image, '
                               . 'le cadre d\'attente reste affiché.',
                    'exemple' => '',
'exemple' => '',
                    'attente' => true,
                ],
            ],
        ],
        'accueil_hero_2' => [
            'titre'    => 'Accueil — diapositive 2 du diaporama',
            'page'     => 'Accueil',
            'chemin'   => '/',
            'aide'     => 'Le bouton mène à la biographie.',
            'toujours' => true,
            'champs'   => [
                'accueil_hero_2_titre' => [
                    'libelle' => 'Titre',
                    'type'    => 'titre',
                    'aide'    => 'En très grands caractères : deux ou trois mots. Chaque retour à la '
                               . 'ligne fait une ligne du titre.',
                    'exemple' => "1920\n1998",
                    'max'     => 60,
                    'defaut'  => 'accueil.hero.2_titre',
                ],
                'accueil_hero_2_accroche' => [
                    'libelle' => 'Accroche',
                    'type'    => 'court',
                    'aide'    => 'Une ou deux phrases sous le titre.',
                    'exemple' => '',
                    'max'     => 300,
                    'defaut'  => 'accueil.hero.2_lead',
                ],
                'accueil_hero_2_bouton' => [
                    'libelle' => 'Texte du bouton',
                    'type'    => 'ligne',
                    'aide'    => 'Deux ou trois mots. La destination du bouton ne change pas.',
                    'exemple' => '',
                    'max'     => 40,
                    'defaut'  => 'accueil.hero.2_cta',
                ],
                'accueil_hero_2_image' => [
                    'libelle' => 'Image',
                    'type'    => 'image',
                    'aide'    => 'Une photographie en hauteur, 2000 × 2600 px, <strong>sujet dans la '
                               . 'moitié droite</strong> : la gauche passe sous le texte. Sans image, '
                               . 'le cadre d\'attente reste affiché.',
                    'exemple' => '',
'exemple' => '',
                    'attente' => true,
                ],
            ],
        ],
        'accueil_hero_3' => [
            'titre'    => 'Accueil — diapositive 3 du diaporama',
            'page'     => 'Accueil',
            'chemin'   => '/',
            'aide'     => 'Le bouton mène à la page Commander.',
            'toujours' => true,
            'champs'   => [
                'accueil_hero_3_titre' => [
                    'libelle' => 'Titre',
                    'type'    => 'titre',
                    'aide'    => 'En très grands caractères : deux ou trois mots. Chaque retour à la '
                               . 'ligne fait une ligne du titre.',
                    'exemple' => 'L\'ouvrage',
                    'max'     => 60,
                    'defaut'  => 'accueil.hero.3_titre',
                ],
                'accueil_hero_3_accroche' => [
                    'libelle' => 'Accroche',
                    'type'    => 'court',
                    'aide'    => 'Une ou deux phrases sous le titre.',
                    'exemple' => '',
                    'max'     => 300,
                    'defaut'  => 'accueil.hero.3_lead',
                ],
                'accueil_hero_3_bouton' => [
                    'libelle' => 'Texte du bouton',
                    'type'    => 'ligne',
                    'aide'    => 'Deux ou trois mots. La destination du bouton ne change pas.',
                    'exemple' => '',
                    'max'     => 40,
                    'defaut'  => 'accueil.hero.3_cta',
                ],
                'accueil_hero_3_lien' => [
                    'libelle' => 'Texte du lien',
                    'type'    => 'ligne',
                    'aide'    => 'Le lien mène aux points de vente, sur la page du livre.',
                    'exemple' => '',
                    'max'     => 40,
                    'defaut'  => 'accueil.hero.3_lien',
                ],
                'accueil_hero_3_image' => [
                    'libelle' => 'Image',
                    'type'    => 'image',
                    'aide'    => 'Une photographie en hauteur, 2000 × 2600 px, <strong>sujet dans la '
                               . 'moitié droite</strong> : la gauche passe sous le texte. Sans image, '
                               . 'le cadre d\'attente reste affiché.',
                    'exemple' => '',
'exemple' => '',
                    'attente' => true,
                ],
            ],
        ],
        'accueil_homme' => [
            'titre'    => 'Accueil — section « L\'homme »',
            'page'     => 'Accueil',
            'chemin'   => '/',
            'aide'     => 'La première section sous le diaporama : la stature du personnage et '
                        . 'l\'angle retenu par l\'ouvrage, en quelques lignes. Le lien « Lire la '
                        . 'biographie complète » la suit toujours.',
            'toujours' => true,
            'champs'   => [
                'accueil_homme_titre' => [
                    'libelle' => 'Titre',
                    'type'    => 'titre',
                    'aide'    => 'Un retour à la ligne se retrouve sur la page.',
                    'exemple' => '',
                    'max'     => 120,
                    'defaut'  => 'accueil.homme.titre',
                ],
                'accueil_homme_texte' => [
                    'libelle' => 'Texte',
                    'type'    => 'long',
                    'aide'    => 'Deux ou trois courts paragraphes. Une ligne vide sépare deux '
                               . 'paragraphes.',
                    'exemple' => '',
                    'defaut'  => 'accueil.homme.texte',
                ],
            ],
        ],
        'livre_presentation' => [
            'titre'    => 'Le livre — couverture et présentation',
            'page'     => 'Le livre',
            'chemin'   => '/le-livre',
            'aide'     => 'La couverture et la quatrième paraissent sur l\'accueil et sur la page du '
                        . 'livre ; le résumé long, sur la page du livre seulement. Le titre, l\'auteur, '
                        . 'l\'éditeur et le reste de la fiche technique se saisissent à l\'écran '
                        . '« Paramètres ».',
            'toujours' => true,
            'champs'   => [
                'livre_presentation_accroche' => [
                    'libelle' => 'Sous-titre et accroche',
                    'type'    => 'court',
                    'aide'    => 'Une ou deux phrases sous le titre, en tête de la page du livre. '
                               . '<strong>Vide, la ligne n\'apparaît pas.</strong>',
                    'exemple' => '',
                    'max'     => 300,
                ],
                'livre_presentation_couverture' => [
                    'libelle' => 'Couverture',
                    'type'    => 'image',
                    'aide'    => 'La couverture seule, sans décor autour : 1200 × 1550 px. Sans image, '
                               . 'le cadre d\'attente reste affiché.',
                    'exemple' => '',
'exemple' => '',
                    'attente' => true,
                ],
                'livre_presentation_quatrieme' => [
                    'libelle' => 'Quatrième de couverture',
                    'type'    => 'court',
                    'aide'    => 'Quelques lignes sous le titre, sur l\'accueil : l\'objet du livre, sa '
                               . 'méthode, ce qu\'il apporte de neuf. <strong>Vide, le paragraphe '
                               . 'n\'apparaît pas.</strong>',
                    'exemple' => '',
                    'max'     => 600,
                ],
                'livre_presentation_resume' => [
                    'libelle' => 'Résumé long',
                    'type'    => 'long',
                    'aide'    => 'Sur la page du livre : trois à cinq paragraphes, l\'objet du livre, la '
                               . 'période couverte, les sources. Une ligne vide sépare deux paragraphes. '
                               . '<strong>Vide, le résumé n\'apparaît pas.</strong>',
                    'exemple' => '',
                ],
                'livre_presentation_editeur' => [
                    'libelle' => 'Mot de l\'éditeur',
                    'type'    => 'long',
                    'aide'    => 'Sous le résumé, sur la page du livre. Une ligne vide sépare deux '
                               . 'paragraphes. <strong>Vide, il n\'apparaît pas.</strong>',
                    'exemple' => '',
                ],
            ],
        ],
        'livre_contenu' => [
            'titre'    => 'Le livre — sommaire, feuilletage, auteur',
            'page'     => 'Le livre',
            'chemin'   => '/le-livre',
            'aide'     => 'Trois sections de la page du livre. Chacune disparaît tant qu\'elle n\'a rien '
                        . 'à montrer, et les numéros des sections suivantes se recalent. Le nom et la '
                        . 'biographie de l\'auteur se saisissent à l\'écran « Paramètres ».',
            'toujours' => true,
            'champs'   => [
                'livre_contenu_sommaire' => [
                    'libelle' => 'Sommaire',
                    'type'    => 'sommaire',
                    'aide'    => 'Une ligne par partie. La page, facultative, après une barre verticale : '
                               . '« Les années de formation | 13 ». <strong>Vide, la section Sommaire '
                               . 'n\'apparaît pas.</strong>',
                    'exemple' => '',
                ],
                'livre_contenu_extrait_1' => [
                    'libelle' => 'Feuilletage — première double page',
                    'type'    => 'image',
                    'aide'    => 'Une double page photographiée à plat, 1500 × 1000 px. <strong>Sans '
                               . 'aucune double page, la section Feuilletage n\'apparaît pas.</strong>',
                    'exemple' => '',
                ],
                'livre_contenu_extrait_2' => [
                    'libelle' => 'Feuilletage — seconde double page',
                    'type'    => 'image',
                    'aide'    => 'Facultative.',
                    'exemple' => '',
                ],
                'livre_contenu_extrait_pdf' => [
                    'libelle' => 'Feuilletage — extrait en PDF',
                    'type'    => 'document',
                    'aide'    => 'Un PDF de la médiathèque, proposé en téléchargement. <strong>Vide, le '
                               . 'lien n\'apparaît pas.</strong>',
                    'exemple' => '',
                ],
                'livre_contenu_portrait' => [
                    'libelle' => 'Portrait de l\'auteur',
                    'type'    => 'image',
                    'aide'    => 'Un portrait vertical, 1000 × 1250 px. Sans portrait, la section de '
                               . 'l\'auteur paraît sans image ; <strong>sans nom d\'auteur</strong> '
                               . '(écran « Paramètres »), elle n\'apparaît pas.',
                    'exemple' => '',
                ],
            ],
        ],
        'biographie_contexte' => [
            'titre'  => 'Biographie — section « Contexte »',
            'page'   => 'Biographie',
            'chemin' => '/biographie',
            'aide'   => 'La première section de la page, sous le portrait : situer l\'homme dans '
                      . 'son époque avant d\'entrer dans le parcours.',
            'champs' => [
                'biographie_contexte_titre' => [
                    'libelle' => 'Titre de la section',
                    'type'    => 'titre',
                    'aide'    => 'Facultatif. Vide, la page garde son titre actuel, « Une trajectoire '
                               . 'et un pays qui naît. » — déjà traduit en anglais. Un retour à la '
                               . 'ligne se retrouve sur la page.',
                    'exemple' => "Une trajectoire\net un pays qui naît.",
                ],
                'biographie_contexte_texte' => [
                    'libelle' => 'Texte',
                    'type'    => 'long',
                    'aide'    => 'La Côte d\'Ivoire avant et après l\'indépendance, le rôle aux côtés '
                               . 'de Félix Houphouët-Boigny, la place de l\'Assemblée nationale. '
                               . 'Une ligne vide sépare deux paragraphes. <strong>Vide, la section '
                               . 'n\'apparaît pas sur le site.</strong>',
                    'exemple' => '',
                ],
            ],
        ],
    ];

    /**
     * Les champs de toutes les sections de `TEXTES_PAGES`, à plat.
     *
     * @return array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public static function champsTextes(): array
    {
        $champs = [];

        foreach (self::TEXTES_PAGES as $section) {
            $champs += $section['champs'];
        }

        return $champs;
    }

    /** @return array<string,string|null> toutes les valeurs, indexées par clé */
    public static function toutes(): array
    {
        $valeurs = [];

        foreach (Database::all('SELECT cle, valeur FROM parametre') as $l) {
            $valeurs[$l['cle']] = $l['valeur'];
        }

        return self::traduites($valeurs);
    }

    public static function lire(string $cle, ?string $defaut = null): ?string
    {
        $l = Database::one('SELECT valeur FROM parametre WHERE cle = ?', [$cle]);

        return self::traduites([$cle => $l['valeur'] ?? $defaut])[$cle];
    }

    /**
     * Un texte de page dans la langue courante, **à condition que le français
     * existe** (lot G16).
     *
     * `lire()` pose la traduction par-dessus la valeur française, même quand
     * celle-ci est vide. Pour un réglage, c'est sans conséquence ; pour une
     * section de page, c'est une contradiction : l'éditeur vide le texte
     * français, l'écran lui dit que la section a disparu, et elle reste en
     * ligne en anglais parce qu'une traduction ancienne traîne en base.
     *
     * **Le français fait foi, ici comme dans le lexique** : vide en français,
     * rien dans aucune langue. La traduction reste en base, et reparaît le jour
     * où le français est rédigé de nouveau.
     */
    public static function texte(string $cle): string
    {
        $l = Database::one('SELECT valeur FROM parametre WHERE cle = ?', [$cle]);
        $francais = trim((string) ($l['valeur'] ?? ''));

        if ($francais === '') {
            return '';
        }

        return trim((string) self::traduites([$cle => $francais])[$cle]);
    }

    /**
     * Les textes d'une section `toujours`, indexés par nom court (lot G17).
     *
     * `accueil_homme_titre` y devient `titre`. Chaque valeur est celle de
     * l'écran si elle est saisie — traduite dans la langue courante —, son
     * texte par défaut du lexique sinon, **nu dans les deux cas** : le gabarit
     * échappe tout de la même façon. Les images n'y figurent pas.
     *
     * @return array<string,string>
     */
    public static function section(string $section): array
    {
        $prefixe = $section . '_';
        $textes  = [];

        foreach (self::TEXTES_PAGES[$section]['champs'] ?? [] as $cle => $champ) {
            if ($champ['type'] === 'image' || $champ['type'] === 'document') {
                continue;
            }

            $valeur = self::texte($cle);
            $textes[substr($cle, strlen($prefixe))] = $valeur !== ''
                ? $valeur
                : Lexique::nu((string) ($champ['defaut'] ?? ''));
        }

        return $textes;
    }

    /**
     * Une diapositive du diaporama de l'accueil, prête à afficher.
     *
     * Chaque texte est celui de l'écran « Textes des pages » s'il est saisi,
     * son texte par défaut du lexique sinon — **nu dans les deux cas** : le
     * gabarit échappe tout de la même façon, qu'il vienne du dépôt ou du
     * back-office. Le titre est rendu ligne par ligne, chacune ayant son
     * masque animé.
     *
     * L'image est la ligne de `media`, ou `null` : le gabarit garde alors son
     * cadre d'attente. Une image effacée de la médiathèque, ou qui n'en est
     * plus une, retombe sur ce cadre plutôt que sur une `<img>` cassée.
     *
     * @return array{titre:list<string>,accroche:string,bouton:string,lien:string,image:?array<string,mixed>}
     */
    public static function diapositive(int $n): array
    {
        $diapo = self::section('accueil_hero_' . $n)
               + ['titre' => '', 'accroche' => '', 'bouton' => '', 'lien' => '', 'image' => null];

        foreach (self::TEXTES_PAGES['accueil_hero_' . $n]['champs'] ?? [] as $cle => $champ) {
            if ($champ['type'] === 'image') {
                $diapo['image'] = self::image($cle);
            }
        }

        $diapo['titre'] = array_values(array_filter(
            array_map('trim', explode("\n", (string) $diapo['titre'])),
            static fn(string $ligne): bool => $ligne !== ''
        ));

        return $diapo;
    }

    /**
     * L'image d'un champ `image`, prête à afficher (lot G17).
     *
     * La ligne de `media`, traduite — la légende sert de texte de
     * remplacement —, ou `null` : champ vide, fichier effacé de la
     * médiathèque, ou qui n'est pas une image. Le gabarit garde alors son cadre
     * d'attente plutôt qu'une `<img>` cassée.
     *
     * @return array<string,mixed>|null
     */
    public static function image(string $cle): ?array
    {
        $fichier = trim((string) self::lire($cle));

        if ($fichier === '') {
            return null;
        }

        $media = Media::parFichier($fichier);

        if ($media === null || !Media::aVignette($media)) {
            return null;
        }

        return Traduction::ligne('media', $media);
    }

    /**
     * Le PDF d'un champ `document`, ou `null` — champ vide, fichier effacé, ou
     * qui n'est pas un document (lot G17).
     *
     * @return array<string,mixed>|null
     */
    public static function document(string $cle): ?array
    {
        $fichier = trim((string) self::lire($cle));
        $media   = $fichier === '' ? null : Media::parFichier($fichier);

        return $media !== null && Media::est($media, 'document')
            ? Traduction::ligne('media', $media)
            : null;
    }

    /**
     * Le sommaire de l'ouvrage, une entrée par ligne saisie (lot G17).
     *
     * « Intitulé | page » : la page est facultative, et seule la dernière barre
     * la sépare — un intitulé peut en contenir une. Les lignes vides sont
     * ignorées.
     *
     * @return list<array{titre:string,page:string}>
     */
    public static function sommaire(): array
    {
        $entrees = [];

        foreach (explode("\n", self::texte('livre_contenu_sommaire')) as $ligne) {
            $ligne = trim($ligne);

            if ($ligne === '') {
                continue;
            }

            $barre = mb_strrpos($ligne, '|');
            $titre = $barre === false ? $ligne : trim(mb_substr($ligne, 0, $barre));
            $page  = $barre === false ? '' : trim(mb_substr($ligne, $barre + 1));

            if ($titre !== '') {
                $entrees[] = ['titre' => $titre, 'page' => $page];
            }
        }

        return $entrees;
    }

    /**
     * La fiche technique de l'ouvrage, telle que l'accueil et la page du livre
     * la montrent (lot G17).
     *
     * Les valeurs de l'écran « Paramètres », dans l'ordre demandé, **les vides
     * écartés** : une ligne « À renseigner » publiée n'apprend rien au lecteur,
     * et c'est ce que les deux pages affichaient en dur, sans jamais lire ce
     * qui était saisi. Le prix est écrit comme on le lit, « 25 000 F CFA » ;
     * les textes sont traduits, **le français décidant** — voir `texte()`.
     *
     * @param list<string> $cles clés de FICHE_LIVRE
     * @return array<string,string> clé => valeur affichable
     */
    public static function fiche(array $cles): array
    {
        $fiche = [];

        foreach ($cles as $cle) {
            $valeur = $cle === 'livre_prix' ? Boutique::prixLisible() : self::texte($cle);

            if ($valeur !== '') {
                $fiche[$cle] = $valeur;
            }
        }

        return $fiche;
    }

    /**
     * Recouvre les valeurs traduites, en anglais comme ailleurs (lot G11).
     *
     * **`parametre` ne passe pas par `Modele` et n'a pas d'identifiant** : sa
     * clé primaire est `cle`, une chaîne, quand `Traduction` s'indexe sur un
     * entier. Le trou était réel — `preface_texte`, `preface_extrait` et
     * `auteur_bio` sont de la prose, pas des réglages, et seraient restés
     * français sur une page anglaise.
     *
     * Il se comble **sans migration** : la table `traduction` accepte
     * `ligne_id = 0`, aucune ligne de `parametre` n'ayant d'identifiant qui
     * puisse entrer en collision, et sa clé unique porte déjà sur le
     * quadruplet `(entite, ligne_id, langue, champ)`. Le nom du paramètre tient
     * lieu de `champ`, ce qu'il est déjà.
     *
     * En français la méthode ne fait rien et n'interroge rien, comme le reste
     * de `Traduction` : le coût du bilinguisme reste nul tant que le site est
     * monolingue.
     *
     * @param array<string,string|null> $valeurs
     * @return array<string,string|null>
     */
    private static function traduites(array $valeurs): array
    {
        if (Langue::estDefaut() || $valeurs === []) {
            return $valeurs;
        }

        /*
         * `Traduction::ligne()` attend une ligne portant un `id` : on lui en
         * fabrique une, avec l'identifiant conventionnel des paramètres. Elle
         * ne recouvre que les clés déjà présentes, donc rien n'apparaît qui ne
         * soit déjà attendu par l'appelant.
         */
        $ligne = Traduction::ligne('parametre', ['id' => Traduction::SANS_ID] + $valeurs);

        unset($ligne['id']);

        return $ligne;
    }

    /**
     * Écrit une valeur.
     *
     * `ON DUPLICATE KEY UPDATE` plutôt qu'un SELECT suivi d'un INSERT ou d'un
     * UPDATE : une seule requête, et pas de fenêtre entre les deux où une
     * autre écriture s'intercalerait.
     */
    public static function ecrire(string $cle, ?string $valeur, ?string $libelle = null): void
    {
        Database::pdo()->prepare(
            'INSERT INTO parametre (cle, valeur, libelle) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)'
        )->execute([$cle, $valeur, $libelle]);
    }

    /** Combien des huit valeurs de la fiche technique sont renseignées. */
    public static function ficheRemplie(): int
    {
        $valeurs = self::toutes();
        $n = 0;

        foreach (array_keys(self::FICHE_LIVRE) as $cle) {
            if (trim((string) ($valeurs[$cle] ?? '')) !== '') {
                $n++;
            }
        }

        return $n;
    }
}
