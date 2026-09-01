<?php
declare(strict_types=1);

/**
 * Contrôleur frontal du back-office.
 *
 * Toute URL sous /cmsadmin/ qui ne désigne pas un fichier réel arrive ici
 * (voir le .htaccess du dossier). Le socle — configuration, autoload, routeur,
 * gabarits — est celui du site public : rien n'est réécrit pour l'admin.
 *
 * Lot A : ossature seule. Aucune route n'est encore protégée parce qu'aucune
 * ne lit ni n'écrit de donnée. L'authentification arrive au lot B, et c'est
 * elle qui posera la garde en tête de ce fichier.
 */

use App\Core\Admin;
use App\Core\Router;
use App\Core\View;

require dirname(__DIR__) . '/src/bootstrap.php';

$router = new Router();
$base   = Admin::base();

$router->get($base . '/', static fn() => View::admin('tableau-de-bord', [
    'titre'  => 'Tableau de bord',
    'actif'  => 'tableau-de-bord',
]));

/**
 * Page témoin du lot A : elle sert à vérifier la mise en page sur un contenu
 * quelconque. Elle disparaîtra quand les vraies pages existeront.
 */
$router->get($base . '/exemple', static fn() => View::admin('exemple', [
    'titre' => 'Page d\'exemple',
    'actif' => '',
]));

$router->introuvable(static fn() => View::admin('404', [
    'titre' => 'Page introuvable',
    'actif' => '',
], 404));

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
