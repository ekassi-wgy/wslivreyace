<?php
declare(strict_types=1);

/**
 * Amorçage commun au site public et au back-office : chargement automatique
 * des classes et régime d'erreurs.
 *
 * Les deux contrôleurs frontaux (index.php à la racine, cmsadmin/index.php)
 * passent par ici. Dupliquer ces vingt lignes garantissait qu'elles finiraient
 * par diverger — typiquement sur l'affichage des erreurs, qui n'a rien à faire
 * en production.
 */

require __DIR__ . '/Core/Config.php';

spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $path = __DIR__ . '/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

error_reporting(E_ALL);

if (App\Core\Config::debug()) {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}
