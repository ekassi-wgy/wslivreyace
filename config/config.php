<?php
/**
 * Configuration. Les valeurs ci-dessous sont celles de MAMP en local.
 * Pour surcharger sans toucher au dépôt (identifiants de production, par
 * exemple), créer config/config.local.php qui retourne un tableau partiel ;
 * il est ignoré par git.
 */

$config = [
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 8889,
        'name'    => 'livreyace_sbd',
        'user'    => 'root',
        'pass'    => 'root',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        // À passer à false en production : conditionne l'affichage des erreurs.
        'debug'   => true,
        'nom'     => 'Philippe Grégoire Yacé — Une destinée',
        'medias'  => __DIR__ . '/../medias',
    ],
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_replace_recursive($config, require $local);
}

return $config;
