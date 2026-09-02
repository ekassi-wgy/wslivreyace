<?php
declare(strict_types=1);

/**
 * Contrôleur frontal du back-office.
 *
 * Toute URL sous /cmsadmin/ qui ne désigne pas un fichier réel arrive ici
 * (voir le .htaccess du dossier). Le socle — configuration, autoload, routeur,
 * gabarits — est celui du site public : rien n'est réécrit pour l'admin.
 */

use App\Controller\Admin\ActualiteController;
use App\Controller\Admin\AuthController;
use App\Controller\Admin\EvenementController;
use App\Controller\Admin\MediaController;
use App\Controller\Admin\ParametreController;
use App\Controller\Admin\RepereController;
use App\Controller\Admin\TemoignageController;
use App\Core\Admin;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Router;
use App\Core\View;

require dirname(__DIR__) . '/src/bootstrap.php';

// Session propre au back-office : nom et chemin de cookie distincts de ceux du
// site public, pour que le cookie d'administration ne parte jamais avec une
// requête vers une page publique.
Session::demarrer('pgyadmin', Admin::base());

$base = Admin::base();

/**
 * Garde, posée une fois pour tout le back-office et par liste blanche : ces
 * deux chemins sont accessibles sans session, tout le reste exige la
 * connexion. Une route ajoutée plus tard et oubliée est donc protégée par
 * défaut — c'est le sens de la liste blanche, un oubli ferme au lieu d'ouvrir.
 */
Auth::garder([
    $base . '/connexion',
    $base . '/deconnexion',
]);

$router = new Router();

$router->get($base . '/connexion',    [AuthController::class, 'formulaire']);
$router->post($base . '/connexion',   [AuthController::class, 'connexion']);
$router->post($base . '/deconnexion', [AuthController::class, 'deconnexion']);

$router->get($base . '/', static fn() => View::admin('tableau-de-bord', [
    'titre' => 'Tableau de bord',
    'actif' => 'tableau-de-bord',
]));

/**
 * Écrans de contenu — un jeu de six routes par entité.
 *
 * `/nouvelle` et `/nouveau` sont déclarés AVANT `/{id}` : le routeur retient le
 * premier motif qui correspond, et `{id}` accepte aussi bien des lettres que
 * des chiffres. Déclaré après, `/actualites/nouvelle` serait compris comme une
 * fiche d'identifiant « nouvelle ».
 */
$crud = static function (string $chemin, string $controleur, string $creation) use ($router, $base): void {
    $router->get($base . $chemin,                     [$controleur, 'liste']);
    $router->get($base . $chemin . '/' . $creation,   [$controleur, 'formulaireCreation']);
    $router->post($base . $chemin,                    [$controleur, 'enregistrer']);
    $router->get($base . $chemin . '/{id}',           [$controleur, 'formulaireEdition']);
    $router->post($base . $chemin . '/{id}',          [$controleur, 'mettreAJour']);
    $router->post($base . $chemin . '/{id}/statut',   [$controleur, 'basculerStatut']);
    $router->post($base . $chemin . '/{id}/supprimer',[$controleur, 'supprimer']);
};

$crud('/actualites', ActualiteController::class, 'nouvelle');
$crud('/evenements', EvenementController::class, 'nouveau');
$crud('/reperes',    RepereController::class,    'nouveau');

/**
 * Modération des témoignages. Pas le même jeu de routes que les contenus : on
 * ne crée pas un témoignage depuis l'admin, on décide de celui qu'on reçoit.
 * `{decision}` vaut publier, refuser ou reprendre.
 */
$router->get($base . '/temoignages',                       [TemoignageController::class, 'liste']);
$router->get($base . '/temoignages/{id}',                  [TemoignageController::class, 'formulaire']);
$router->post($base . '/temoignages/{id}',                 [TemoignageController::class, 'mettreAJour']);
$router->post($base . '/temoignages/{id}/supprimer',       [TemoignageController::class, 'supprimer']);
$router->post($base . '/temoignages/{id}/{decision}',      [TemoignageController::class, 'moderer']);

/**
 * Médiathèque. Encore un jeu de routes à part, et pour la même raison que la
 * modération : la création n'est pas un formulaire mais un dépôt de fichiers,
 * d'où l'absence de route `/nouveau`. Le POST sur `/medias` reçoit le lot.
 */
$router->get($base . '/medias',                 [MediaController::class, 'liste']);
$router->post($base . '/medias',                [MediaController::class, 'televerser']);
$router->get($base . '/medias/{id}',            [MediaController::class, 'formulaire']);
$router->post($base . '/medias/{id}',           [MediaController::class, 'mettreAJour']);
$router->post($base . '/medias/{id}/statut',    [MediaController::class, 'basculerStatut']);
$router->post($base . '/medias/{id}/supprimer', [MediaController::class, 'supprimer']);

$router->get($base . '/parametres',  [ParametreController::class, 'formulaire']);
$router->post($base . '/parametres', [ParametreController::class, 'enregistrer']);

$router->introuvable(static function (): void {
    // Une adresse fautive derrière la garde reste une 404 ; l'anonyme, lui, a
    // déjà été renvoyé vers la connexion par la garde.
    View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
