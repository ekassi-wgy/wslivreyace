<?php
declare(strict_types=1);

/**
 * Contrôleur frontal du site public. Toute requête qui ne vise pas un fichier
 * réel arrive ici (voir .htaccess).
 *
 * Le back-office a le sien, cmsadmin/index.php ; les deux partagent le même
 * amorçage.
 */

use App\Controller\ActualiteController;
use App\Controller\ArchiveController;
use App\Controller\CommandeController;
use App\Controller\BiographieController;
use App\Controller\ContactController;
use App\Controller\ContributionController;
use App\Controller\EvenementController;
use App\Controller\HeritageController;
use App\Controller\LivreController;
use App\Controller\RechercheController;
use App\Controller\SeoController;
use App\Controller\TemoignageController;
use App\Core\Router;
use App\Core\View;

require __DIR__ . '/src/bootstrap.php';

$router = new Router();

$router->get('/',            fn() => View::render('pages/accueil',    ['page' => 'accueil']));
/*
 * L'ouvrage et son auteur (lot G2). L'auteur a sa page propre et non une
 * ancre : le brief le demande, et une ancre ne se partage pas — ni sur un
 * plateau, ni dans un dossier de presse.
 */
$router->get('/le-livre',    [LivreController::class, 'livre']);
$router->get('/auteur',      [LivreController::class, 'auteur']);

/*
 * Biographie (brief §3, lot G10). Une adresse par période, comme il y en a une
 * par notice d'archive et par sujet d'Héritage : le récit d'une époque se cite
 * seul, dans un dossier de presse comme dans une note de bas de page.
 *
 * L'ordre de déclaration importe : `/biographie` avant `/biographie/{slug}`
 * serait sans effet dans un sens comme dans l'autre — les deux motifs n'ont pas
 * le même nombre de segments — mais la lisibilité veut qu'on aille de l'index
 * à la pièce.
 */
$router->get('/biographie',         [BiographieController::class, 'page']);
$router->get('/biographie/{slug}',  [BiographieController::class, 'periode']);

/**
 * Actualités (CDC §4.7). La revue de presse a son adresse propre : ce n'est
 * pas un filtre de la liste mais une autre lecture de la même matière — voir
 * le contrôleur. Aucune de ces trois routes n'ouvre de session : elles ne
 * font que lire.
 */
$router->get('/actualites',        [ActualiteController::class, 'liste']);
$router->get('/revue-de-presse',   [ActualiteController::class, 'presse']);
$router->get('/actualites/{slug}', [ActualiteController::class, 'detail']);

/**
 * Archives et événements (CDC §4.6 et §4.10). La galerie lit la médiathèque
 * telle que le back-office l'a rangée ; l'agenda partage la même page entre ce
 * qui vient et ce qui a eu lieu. Lecture seule, aucune session.
 */
/*
 * Archives (brief §4, lot G4). Trois adresses, et la troisième est celle qui
 * compte : une par pièce, partageable seule — c'est ce que demande le §9.
 * L'ordre de déclaration importe, le routeur retenant le premier motif qui
 * correspond : `{categorie}` seul avant `{categorie}/{slug}` serait sans
 * effet, mais l'inverse — un motif à deux segments avant celui à un — ne
 * poserait aucun problème. La lisibilité tranche : du général au particulier.
 */
/*
 * « Contribuez aux archives » (brief §5, lot G8). Adresse courte et à part :
 * `/archives/contribuer` serait entré en concurrence avec `{categorie}`, et
 * c'est de toute façon un lien qu'on donne de vive voix.
 *
 * Le seul formulaire du site qui reçoit des fichiers d'un inconnu — voir le
 * contrôleur, et `App\Core\Quarantaine` pour où ils atterrissent.
 */
$router->get('/contribuer',   [ContributionController::class, 'page']);
$router->post('/contribuer',  [ContributionController::class, 'envoyer']);

$router->get('/archives',                       [ArchiveController::class, 'index']);
$router->get('/archives/{categorie}',           [ArchiveController::class, 'categorie']);
$router->get('/archives/{categorie}/{slug}',    [ArchiveController::class, 'notice']);
/*
 * Héritage (brief §6, lot G7). Les adresses sont plates — `/heritage/{slug}`
 * et non `/heritage/{rubrique}/{slug}` : elles finiront sur une plaque ou un
 * QR code, et chaque segment compte. La rubrique reste un regroupement
 * d'affichage, pas un niveau d'adresse (décision 3).
 */
$router->get('/heritage',         [HeritageController::class, 'index']);
$router->get('/heritage/{slug}',  [HeritageController::class, 'sujet']);

$router->get('/evenements',         [EvenementController::class, 'liste']);
$router->get('/evenements/{slug}',  [EvenementController::class, 'detail']);

/**
 * Contact et mentions légales (CDC §4.11 et §4.12). Le second formulaire
 * ouvert du site : même plomberie que les témoignages, session comprise — elle
 * n'est ouverte que par les deux routes de `/contact`. Les mentions, elles, ne
 * lisent rien et ne posent aucun cookie.
 */
$router->get('/contact',           [ContactController::class, 'page']);
$router->post('/contact',          [ContactController::class, 'envoyer']);
$router->get('/mentions-legales',  [ContactController::class, 'mentions']);

/**
 * Témoignages : la première page publique adossée aux données, et le premier
 * écrit ouvert à tout le monde. La session n'est ouverte que par ces deux
 * routes — voir le contrôleur.
 */
$router->get('/temoignages',  [TemoignageController::class, 'page']);
$router->post('/temoignages', [TemoignageController::class, 'deposer']);

/**
 * Plan du site et consignes aux robots (brief §9, README §9).
 *
 * Servis par le routeur et non posés en fichiers : le plan doit lister les
 * actualités et les événements publiés, et un fichier statique se périmerait
 * dès la première publication sans que personne ne le sache. Voir le
 * contrôleur. Lecture seule, aucune session.
 */
/*
 * Recherche transversale (brief §9, lot G9). Une adresse et non un panneau
 * surgissant : une recherche se partage, se met en favori, et la page 404 peut
 * y renvoyer avec un terme pré-rempli — ce qui compte quand des adresses sont
 * imprimées (décision 3).
 */
$router->get('/recherche', [RechercheController::class, 'page']);

/*
 * Commander (brief §2, lot G3). **Paiement à la livraison** : aucune passerelle
 * n'est appelée, et `App\Core\Paiement` continue de n'être que décrite.
 *
 * La page reste servie quand la boutique est fermée — elle explique alors
 * pourquoi et renvoie vers l'ouvrage. Un bouton « Commander » menant à une 404
 * ferait croire à une panne.
 *
 * `/commander/confirmation` avant rien d'autre : la référence voyage en
 * session et non dans l'adresse, une confirmation portant la référence en URL
 * étant partageable — et montrant le nom, le téléphone et l'adresse du client
 * à qui aurait le lien.
 */
$router->get('/commander',              [CommandeController::class, 'page']);
$router->post('/commander',             [CommandeController::class, 'envoyer']);
$router->get('/commander/confirmation', [CommandeController::class, 'confirmation']);

$router->get('/sitemap.xml', [SeoController::class, 'sitemap']);
$router->get('/robots.txt',  [SeoController::class, 'robots']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
