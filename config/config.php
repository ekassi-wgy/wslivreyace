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
        // Adresse publique du site, sans barre finale. Sert aux URL absolues
        // — canonical, og:image — qui ne peuvent pas être relatives.
        // En production : 'url' => 'https://philippeyace.ci' dans
        // config/config.local.php. Laissée vide ici, le socle retombe sur
        // l'hôte de la requête, ce qui convient au poste de développement mais
        // pas à un serveur public : HTTP_HOST est fourni par le client.
        'url'     => '',
        'nom'     => 'Philippe Grégoire Yacé — Une destinée',
        'medias'  => __DIR__ . '/../medias',
    ],
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_replace_recursive($config, require $local);
}

return $config;
