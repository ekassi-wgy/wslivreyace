<?php
declare(strict_types=1);

/**
 * The site's own interface strings, in English (lot G11).
 *
 * **This file carries only what the site says about itself** — button labels,
 * section headings, form messages, alternative texts and `aria-label`s. It
 * carries no editorial content: a news item, an archive record, a period of
 * the biography are entered by the editor and translated from the back office,
 * through `App\Core\Traduction`.
 *
 * **A missing key falls back to French** rather than disappearing, so an
 * incomplete English page stays readable. That is what will allow English to
 * open section by section instead of waiting for everything at once.
 *
 * The French catalogue in `fr.php` is authoritative: a key that exists here and
 * not there is dead weight, and `Lexique::existe()` is what tells you so.
 *
 * @return array<string,string>
 */

return [

    // --- Used throughout -----------------------------------------------------
    'commun.lire_suite'        => 'Read more',
    'commun.en_savoir_plus'    => 'Find out more',
    'commun.voir_tout'         => 'View all',
    'commun.retour'            => 'Back',
    'commun.fermer'            => 'Close',
    'commun.suivant'           => 'Next',
    'commun.precedent'         => 'Previous',
    'commun.champ_obligatoire' => 'Required field',
    'commun.chargement'        => 'Loading…',

    // --- Main navigation ------------------------------------------------------
    'nav.accueil'         => 'Home',
    'nav.livre'           => 'The book',
    'nav.biographie'      => 'Biography',
    'nav.archives'        => 'Archives',
    'nav.heritage'        => 'Legacy',
    'nav.actualites'      => 'News',
    'nav.commander'       => 'Order',
    'nav.logo_aria'       => 'Philippe Grégoire Yacé — home',
    'nav.recherche_aria'  => 'Search the site',
    'nav.principale_aria' => 'Main navigation',
    'nav.ouvrir_menu'     => 'Open menu',
    'nav.langue_aria'     => 'Choose language',

    // --- Footer ---------------------------------------------------------------
    'pied.ouvrage'          => 'The book',
    'pied.livre'            => 'The book',
    'pied.auteur'           => 'The author',
    'pied.extraits'         => 'Extracts',
    'pied.commander'        => 'Order',
    'pied.personnage'       => 'The man',
    'pied.biographie'       => 'Biography',
    'pied.reperes'          => 'Milestones',
    'pied.heritage'         => 'Legacy',
    'pied.archives'         => 'Archives',
    'pied.site'             => 'The site',
    'pied.actualites'       => 'News',
    'pied.revue_presse'     => 'Press review',
    'pied.evenements'       => 'Events',
    'pied.temoignages'      => 'Tributes',
    'pied.contact'          => 'Contact',
    'pied.droits'           => 'All rights reserved. Publishing body to be confirmed.',
    'pied.mentions'         => 'Legal notice',
    'pied.confidentialite'  => 'Privacy',

    // --- Breadcrumb ------------------------------------------------------------
    'fil.aria' => 'Breadcrumb',

    // --- Page not found ----------------------------------------------------------
    'e404.titre_page'  => 'Page not found — Philippe Grégoire Yacé',
    'e404.description' => 'The page you asked for does not exist.',
    'e404.kicker'      => 'Page not found',
    'e404.titre'       => 'This page does not exist.',
    'e404.lead'        => 'The link may be old, mistyped, or the page may have moved. Search for what you were looking for:',
    'e404.label'       => 'Search the site',
    'e404.placeholder' => 'A name, a place, a year…',
    'e404.chercher'    => 'Search',
    'e404.retour'      => 'Back to home',
    'e404.archives'    => 'Browse the archives',

    // --- A legacy entry ----------------------------------------------------------
    'heritage_sujet.source'  => 'Source:',
    'heritage_sujet.adresse' => 'Address of this page',

    // --- Layout ------------------------------------------------------------------
    'layout.titre_defaut'  => 'Philippe Grégoire Yacé — Une destinée',
    'layout.aller_contenu' => 'Skip to content',

    // --- Author page -------------------------------------------------------------
    'auteur.titre_page'               => ':nom — author of “:livre”',
    'auteur.description'              => ':nom, :qualite, author of “:livre”.',
    'auteur.description_sans_qualite' => ':nom, author of “:livre”.',
    'auteur.fil_livre'                => 'The book',
    'auteur.kicker'                   => 'The author',
    'auteur.notice_vide'              => 'Biographical note to be completed.',

    // --- A news item -------------------------------------------------------------
    'actualite.titre_page' => ':titre — Philippe Grégoire Yacé: une destinée',
    'actualite.toutes'     => 'All news',
    'actualite.a_lire'     => 'Also worth reading',

    // --- Home --------------------------------------------------------------------
    'accueil.titre_page'            => 'Philippe Grégoire Yacé — Une destinée (1920-1998)',
    'accueil.description'           => 'The biography of Philippe Grégoire Yacé, a central figure in the building of the Ivorian state.',
    'accueil.hero.diapos_aria'      => 'Slides',
    'accueil.hero.1_lead'           => 'The biography of Philippe Grégoire Yacé — a life that follows Côte d\'Ivoire\'s own, from the eve of independence to the closing years of the century.',
    'accueil.hero.1_cta'            => 'Discover the book',
    'accueil.hero.1_lien'           => 'Browse the milestones',
    'accueil.hero.2_lead'           => 'Seventy-eight years spanning the birth of a nation. <em>Text to be completed by the editor.</em>',
    'accueil.hero.2_cta'            => 'The man',
    'accueil.hero.3_titre'          => 'The book',
    'accueil.hero.3_lead'           => 'A bound volume, richly documented and illustrated with previously unpublished archives. <em>Description to be completed by the editor.</em>',
    'accueil.hero.3_cta'            => 'Order',
    'accueil.hero.3_lien'           => 'Where to buy',
    'accueil.preface.kicker'        => 'Foreword',
    'accueil.preface.lien'          => 'Read the foreword',
    'accueil.homme.kicker'          => 'The man',
    'accueil.homme.titre'           => 'A public life bound up<br>with the building of a state.',
    'accueil.homme.p1'              => '<em>Introductory text to be written by the editor.</em> This paragraph stands in for the opening summary: a few lines setting out the stature of the man and the angle the book takes.',
    'accueil.homme.p2'              => 'President of the National Assembly of Côte d\'Ivoire from 1959 to 1980, secretary-general of the PDCI-RDA, then president of the Economic and Social Council — for four decades Philippe Grégoire Yacé held a central place in the country\'s institutional life.',
    'accueil.homme.lien'            => 'Read the full biography',
    'accueil.ouvrage.kicker'        => 'The book',
    'accueil.ouvrage.titre'         => 'Une destinée',
    'accueil.ouvrage.lead'          => '<em>Back-cover text to be supplied.</em> A few lines will do: what the book is about, how it was written, what it brings that is new.',
    'accueil.ouvrage.auteur'        => 'Author',
    'accueil.ouvrage.editeur'       => 'Publisher',
    'accueil.ouvrage.parution'      => 'Publication',
    'accueil.ouvrage.format'        => 'Format',
    'accueil.ouvrage.pages'         => 'Pages',
    'accueil.ouvrage.isbn'          => 'ISBN',
    'accueil.ouvrage.a_renseigner'  => 'To be confirmed',
    'accueil.ouvrage.format_valeur' => 'Hardback, 240 × 310 mm',
    'accueil.ouvrage.cta'           => 'Order the book',
    'accueil.ouvrage.alt'           => 'Book cover — placeholder image',
    'accueil.reperes.kicker'        => 'Milestones',
    'accueil.reperes.compte_1'      => 'One date',
    'accueil.reperes.compte_2'      => 'Two dates',
    'accueil.reperes.compte_3'      => 'Three dates',
    'accueil.reperes.compte_4'      => 'Four dates',
    'accueil.reperes.compte_autre'  => 'Dates',
    'accueil.reperes.titre_suite'   => ',<br>an Ivorian century.',
    'accueil.reperes.lien'          => 'See the full timeline',
    'accueil.extrait.kicker'        => 'Extract',
    'accueil.extrait.texte'         => 'Space reserved for an extract from the book, to be chosen by the editor.',
    'accueil.extrait.source'        => 'Une destinée — chapter to be confirmed',
    'accueil.galerie.kicker'        => 'Gallery',
    'accueil.galerie.titre'         => 'Archives.',
    'accueil.galerie.lien'          => 'All archives',
    'accueil.galerie.vide'          => '<em>Archives will be published here.</em> Photographs, official documents and press cuttings: each item will appear with its caption and credit.',
    'accueil.temoignages.kicker'    => 'Tributes',
    'accueil.temoignages.titre'     => 'What they say.',
    'accueil.temoignages.deposer'   => 'Leave a tribute',
    'accueil.temoignages.vide'      => '<em>The first tributes will appear here.</em> Did you know Philippe Grégoire Yacé, closely or from afar? Your recollection belongs here.',
    'accueil.temoignages.defaut'    => 'Tribute',
    'accueil.temoignages.tous'      => 'Read all tributes',
    'accueil.actualites.kicker'     => 'News',
    'accueil.actualites.titre'      => 'Around the book.',
    'accueil.actualites.lien'       => 'All news',
    'accueil.actualites.vide'       => '<em>News will appear here.</em> Publications, signings and events around the book: nothing has been published yet.',
    'accueil.commander.kicker'      => 'Getting the book',
    'accueil.commander.titre'       => 'In bookshops<br>and online.',
    'accueil.commander.cta'         => 'Order online',
    'accueil.commander.adresse'     => 'Shop and address to be confirmed',

];
