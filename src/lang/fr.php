<?php
declare(strict_types=1);

/**
 * Les textes de l'interface, en français (lot G11).
 *
 * **Ce catalogue fait foi.** Une clé absente d'ici est un défaut de
 * développement : `Lexique::t()` rend alors la clé elle-même, ce qui se voit à
 * l'écran et se corrige. Une clé absente de `en.php` retombe sur celle-ci.
 *
 * **Ce qui vit ici, et ce qui n'y vit pas.** Ici : ce que le site dit de
 * lui-même — libellés de boutons, intitulés de sections, messages de
 * formulaire, textes de remplacement et `aria-label`. Pas ici : ce que
 * l'éditeur saisit, qui se traduit depuis le back-office et passe par
 * `App\Core\Traduction`. Un texte qu'un éditeur doit pouvoir changer sans
 * livraison de code n'a rien à faire dans ce fichier.
 *
 * **Les clés se lisent de gauche à droite, du général au particulier** :
 * `commun.` pour ce qui sert partout, puis une racine par page. Une clé n'est
 * jamais réutilisée d'une page à l'autre pour économiser une ligne : deux
 * pages qui disent « Voir tout » aujourd'hui peuvent devoir le dire
 * différemment en anglais, et les avoir fondues obligerait alors à les
 * séparer dans les gabarits.
 *
 * Les valeurs à insérer se nomment `:quelque_chose` et sont échappées par
 * `Lexique`.
 *
 * @return array<string,string>
 */

return [

    // --- Ce qui sert partout ------------------------------------------------
    'commun.lire_suite'        => 'Lire la suite',
    'commun.en_savoir_plus'    => 'En savoir plus',
    'commun.voir_tout'         => 'Tout voir',
    'commun.retour'            => 'Retour',
    'commun.fermer'            => 'Fermer',
    'commun.suivant'           => 'Suivant',
    'commun.precedent'         => 'Précédent',
    'commun.champ_obligatoire' => 'Champ obligatoire',
    'commun.chargement'        => 'Chargement…',

    // --- Navigation principale ----------------------------------------------
    'nav.accueil'      => 'Accueil',
    'nav.livre'        => 'Le livre',
    'nav.biographie'   => 'Biographie',
    'nav.archives'     => 'Archives',
    'nav.heritage'     => 'Héritage',
    'nav.actualites'   => 'Actualités',
    'nav.commander'    => 'Commander',
    'nav.logo_aria'    => 'Philippe Grégoire Yacé — accueil',
    'nav.recherche_aria' => 'Rechercher dans le site',
    'nav.principale_aria' => 'Navigation principale',
    'nav.ouvrir_menu'  => 'Ouvrir le menu',
    'nav.langue_aria'  => 'Choisir la langue',

    // --- Pied de page --------------------------------------------------------
    'pied.ouvrage'          => "L'ouvrage",
    'pied.livre'            => 'Le livre',
    'pied.auteur'           => "L'auteur",
    'pied.extraits'         => 'Extraits',
    'pied.commander'        => 'Commander',
    'pied.personnage'       => 'Le personnage',
    'pied.biographie'       => 'Biographie',
    'pied.reperes'          => 'Repères',
    'pied.heritage'         => 'Héritage',
    'pied.archives'         => 'Archives',
    'pied.site'             => 'Le site',
    'pied.actualites'       => 'Actualités',
    'pied.revue_presse'     => 'Revue de presse',
    'pied.evenements'       => 'Événements',
    'pied.temoignages'      => 'Témoignages',
    'pied.contact'          => 'Contact',
    'pied.droits'           => 'Tous droits réservés. Structure porteuse à renseigner.',
    'pied.mentions'         => 'Mentions légales',
    'pied.confidentialite'  => 'Confidentialité',

    // --- Fil d'Ariane ---------------------------------------------------------
    'fil.aria' => "Fil d'Ariane",

    // --- Page introuvable --------------------------------------------------------
    'e404.titre_page'  => 'Page introuvable — Philippe Grégoire Yacé',
    'e404.description' => 'La page demandée n\'existe pas.',
    'e404.kicker'      => 'Page introuvable',
    'e404.titre'       => 'Cette page n\'existe pas.',
    'e404.lead'        => 'Le lien est peut-être ancien, mal recopié, ou la page a été déplacée. Cherchez ce que vous vouliez trouver :',
    'e404.label'       => 'Rechercher dans le site',
    'e404.placeholder' => 'Un nom, un lieu, une année…',
    'e404.chercher'    => 'Chercher',
    'e404.retour'      => 'Retour à l\'accueil',
    'e404.archives'    => 'Parcourir les archives',

    // --- Un sujet d'Héritage -----------------------------------------------------
    'heritage_sujet.source'  => 'Source :',
    'heritage_sujet.adresse' => 'Adresse de cette page',

    // --- Mise en page ------------------------------------------------------------
    'layout.titre_defaut'  => 'Philippe Grégoire Yacé — Une destinée',
    'layout.aller_contenu' => 'Aller au contenu',

    // --- Page de l'auteur --------------------------------------------------------
    'auteur.titre_page'               => ':nom — auteur de « :livre »',
    'auteur.description'              => ':nom, :qualite, auteur de « :livre ».',
    'auteur.description_sans_qualite' => ':nom, auteur de « :livre ».',
    'auteur.fil_livre'                => 'Le livre',
    'auteur.kicker'                   => 'L\'auteur',
    'auteur.notice_vide'              => 'Notice biographique à compléter.',

    // --- Une actualité -----------------------------------------------------------
    'actualite.titre_page' => ':titre — Philippe Grégoire Yacé : une destinée',
    'actualite.toutes'     => 'Toutes les actualités',
    'actualite.a_lire'     => 'À lire également',

    // --- Accueil -----------------------------------------------------------------
    'accueil.titre_page'            => 'Philippe Grégoire Yacé — Une destinée (1920-1998)',
    'accueil.description'           => 'La biographie de Philippe Grégoire Yacé, figure de la construction de l\'État ivoirien.',
    'accueil.hero.diapos_aria'      => 'Diapositives',
    'accueil.hero.1_lead'           => 'La biographie de Philippe Grégoire Yacé — un parcours qui épouse celui de la Côte d\'Ivoire, de la veille de l\'indépendance aux dernières années du siècle.',
    'accueil.hero.1_cta'            => 'Découvrir l\'ouvrage',
    'accueil.hero.1_lien'           => 'Parcourir les repères',
    'accueil.hero.2_lead'           => 'Soixante-dix-huit années traversées par la naissance d\'une nation. <em>Texte à compléter par l\'éditeur.</em>',
    'accueil.hero.2_cta'            => 'L\'homme',
    'accueil.hero.3_titre'          => 'L\'ouvrage',
    'accueil.hero.3_lead'           => 'Un volume relié, richement documenté et illustré d\'archives inédites. <em>Descriptif à compléter par l\'éditeur.</em>',
    'accueil.hero.3_cta'            => 'Commander',
    'accueil.hero.3_lien'           => 'Points de vente',
    'accueil.preface.kicker'        => 'Préface',
    'accueil.preface.lien'          => 'Lire la préface',
    'accueil.homme.kicker'          => 'L\'homme',
    'accueil.homme.titre'           => 'Une vie publique adossée<br>à la construction d\'un État.',
    'accueil.homme.p1'              => '<em>Texte de présentation à rédiger par l\'éditeur.</em> Ce paragraphe tient la place du chapeau introductif : il pose en quelques lignes la stature du personnage et l\'angle retenu par l\'ouvrage.',
    'accueil.homme.p2'              => 'Président de l\'Assemblée nationale de Côte d\'Ivoire de 1959 à 1980, secrétaire général du PDCI-RDA, puis président du Conseil économique et social — Philippe Grégoire Yacé occupe pendant quatre décennies une position centrale dans la vie institutionnelle du pays.',
    'accueil.homme.lien'            => 'Lire la biographie complète',
    'accueil.ouvrage.kicker'        => 'L\'ouvrage',
    'accueil.ouvrage.titre'         => 'Une destinée',
    'accueil.ouvrage.lead'          => '<em>Quatrième de couverture à fournir.</em> Quelques lignes suffisent : l\'objet du livre, sa méthode, ce qu\'il apporte de neuf.',
    'accueil.ouvrage.auteur'        => 'Auteur',
    'accueil.ouvrage.editeur'       => 'Éditeur',
    'accueil.ouvrage.parution'      => 'Parution',
    'accueil.ouvrage.format'        => 'Format',
    'accueil.ouvrage.pages'         => 'Pages',
    'accueil.ouvrage.isbn'          => 'ISBN',
    'accueil.ouvrage.a_renseigner'  => 'À renseigner',
    'accueil.ouvrage.format_valeur' => 'Relié, 240 × 310 mm',
    'accueil.ouvrage.cta'           => 'Commander l\'ouvrage',
    'accueil.ouvrage.alt'           => 'Couverture de l\'ouvrage — visuel provisoire',
    'accueil.reperes.kicker'        => 'Repères',
    'accueil.reperes.compte_1'      => 'Une date',
    'accueil.reperes.compte_2'      => 'Deux dates',
    'accueil.reperes.compte_3'      => 'Trois dates',
    'accueil.reperes.compte_4'      => 'Quatre dates',
    'accueil.reperes.compte_autre'  => 'Des dates',
    'accueil.reperes.titre_suite'   => ',<br>un siècle ivoirien.',
    'accueil.reperes.lien'          => 'Voir la chronologie complète',
    'accueil.extrait.kicker'        => 'Extrait',
    'accueil.extrait.texte'         => 'Emplacement réservé à un extrait de l\'ouvrage, à choisir par l\'éditeur.',
    'accueil.extrait.source'        => 'Une destinée — chapitre à préciser',
    'accueil.galerie.kicker'        => 'Galerie',
    'accueil.galerie.titre'         => 'Archives.',
    'accueil.galerie.lien'          => 'Toutes les archives',
    'accueil.galerie.vide'          => '<em>Les archives seront publiées ici.</em> Photographies, documents officiels et coupures : chaque pièce paraîtra avec sa légende et son crédit.',
    'accueil.temoignages.kicker'    => 'Témoignages',
    'accueil.temoignages.titre'     => 'Ce qu\'ils en disent.',
    'accueil.temoignages.deposer'   => 'Déposer un témoignage',
    'accueil.temoignages.vide'      => '<em>Les premiers témoignages seront affichés ici.</em> Vous avez connu Philippe Grégoire Yacé, de près ou de loin ? Votre souvenir a sa place.',
    'accueil.temoignages.defaut'    => 'Témoignage',
    'accueil.temoignages.tous'      => 'Lire tous les témoignages',
    'accueil.actualites.kicker'     => 'Actualités',
    'accueil.actualites.titre'      => 'Autour de l\'ouvrage.',
    'accueil.actualites.lien'       => 'Toutes les actualités',
    'accueil.actualites.vide'       => '<em>Les actualités paraîtront ici.</em> Parutions, dédicaces et rendez-vous autour de l\'ouvrage : rien n\'est encore publié.',
    'accueil.commander.kicker'      => 'Se procurer l\'ouvrage',
    'accueil.commander.titre'       => 'En librairie<br>et en ligne.',
    'accueil.commander.cta'         => 'Commander en ligne',
    'accueil.commander.adresse'     => 'Enseigne et adresse à renseigner',

];
