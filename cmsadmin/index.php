<?php
declare(strict_types=1);

/**
 * Contrôleur frontal du back-office.
 *
 * Toute URL sous /cmsadmin/ qui ne désigne pas un fichier réel arrive ici
 * (voir le .htaccess du dossier). Le socle — configuration, autoload, routeur,
 * gabarits — est celui du site public : rien n'est réécrit pour l'admin.
 */

use App\Controller\Admin\AuthController;
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
 * Page témoin du lot A : elle sert à vérifier la mise en page sur un contenu
 * quelconque. Elle disparaîtra quand les vraies pages existeront.
 */
$router->get($base . '/exemple', static fn() => View::admin('exemple', [
    'titre' => 'Page d\'exemple',
    'actif' => '',
]));

$router->introuvable(static function (): void {
    // Une adresse fautive derrière la garde reste une 404 ; l'anonyme, lui, a
    // déjà été renvoyé vers la connexion par la garde.
    View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
