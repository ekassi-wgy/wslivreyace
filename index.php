<?php
declare(strict_types=1);

/**
 * Contrôleur frontal. Toute requête qui ne vise pas un fichier réel arrive ici
 * (voir .htaccess).
 */

use App\Core\Config;
use App\Core\Router;
use App\Core\View;

require __DIR__ . '/src/Core/Config.php';

// Chargement automatique des classes App\ depuis src/.
spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $path = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

if (Config::debug()) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

$router = new Router();

$router->get('/',            fn() => View::render('pages/accueil',    ['page' => 'accueil']));
$router->get('/le-livre',    fn() => View::render('pages/livre',      ['page' => 'livre']));
$router->get('/biographie',  fn() => View::render('pages/biographie', ['page' => 'bio']));

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
