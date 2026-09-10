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

/*
 * Les fonctions globales `t()` et `t_brut()` (lot G11). Chargées ici plutôt
 * qu'appelées à la demande : elles servent dans presque tous les gabarits, et
 * un `require` oublié ne se verrait qu'à l'exécution de la page concernée.
 */
require __DIR__ . '/helpers.php';

/*
 * Garde-fou de configuration, avant toute autre chose.
 *
 * `config/config.php` ne porte plus d'identifiants de base : il voyage à
 * chaque envoi et écraserait ceux du serveur. Ils viennent de
 * `config/config.local.php`, propre à la machine et jamais envoyé.
 *
 * S'il manque, rien de ce qui suit ne peut aboutir. Mieux vaut le dire ici
 * qu'au milieu du rendu : sans cette garde, la page part, s'interrompt sur
 * l'exception de connexion, et le visiteur reçoit une demi-page — ou une
 * trace d'erreur si `debug` avait été laissé à true.
 *
 * `pass` n'est pas contrôlé : un mot de passe vide est une configuration
 * valide. `port` non plus, il retombe sur 3306 dans App\Core\Database.
 */
$dbConf = App\Core\Config::get('db');

if (($dbConf['host'] ?? null) === null
    || ($dbConf['name'] ?? null) === null
    || ($dbConf['user'] ?? null) === null) {

    $manque = 'Configuration absente : creez config/config.local.php a partir '
            . 'de config/config.local.exemple.php.';

    /* En ligne de commande — bin/compte.php passe aussi par ici — une page
       HTML n'a pas de sens : le message va sur la sortie d'erreur. */
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, $manque . "\n");
        exit(1);
    }

    error_log($manque);
    http_response_code(503);
    /* Une heure : assez pour couvrir une intervention, assez court pour que
       les moteurs repassent le jour meme. */
    header('Retry-After: 3600');
    header('Content-Type: text/html; charset=UTF-8');
    require __DIR__ . '/../templates/maintenance.php';
    exit;
}

error_reporting(E_ALL);

if (App\Core\Config::debug()) {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}
