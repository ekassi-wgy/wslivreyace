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
use App\Controller\ContactController;
use App\Controller\EvenementController;
use App\Controller\TemoignageController;
use App\Core\Router;
use App\Core\View;

require __DIR__ . '/src/bootstrap.php';

$router = new Router();

$router->get('/',            fn() => View::render('pages/accueil',    ['page' => 'accueil']));
$router->get('/le-livre',    fn() => View::render('pages/livre',      ['page' => 'livre']));
$router->get('/biographie',  fn() => View::render('pages/biographie', ['page' => 'bio']));

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
$router->get('/archives',           [ArchiveController::class,  'galerie']);
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

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
