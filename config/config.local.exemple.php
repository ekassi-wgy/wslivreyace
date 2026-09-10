<?php
/**
 * Modèle de `config/config.local.php` — à copier, pas à modifier ici.
 *
 *     cp config/config.local.exemple.php config/config.local.php
 *
 * Ce fichier-ci est versionné et sert de mémoire de ce qu'il faut renseigner.
 * Sa copie, elle, est ignorée par git et **ne s'envoie jamais par FTP** : elle
 * se crée une fois sur chaque machine — poste de développement et serveur — et
 * n'en bouge plus. C'est ce qui permet d'envoyer `config.php` les yeux fermés
 * à chaque mise en ligne.
 *
 * Il ne retourne qu'un tableau **partiel** : ce qui n'y figure pas garde la
 * valeur de `config.php`. Ne pas y recopier les clés `contact`, `paiement` ou
 * `reseaux` — elles viennent du dépôt, et une copie posée ici recouvrirait
 * silencieusement toute mise à jour. Le piège s'est déjà refermé une fois sur
 * les coordonnées de la page Contact.
 *
 * Les valeurs ci-dessous sont celles de MAMP : c'est le cas du poste de
 * développement. Sur le serveur, les quatre premières viennent de l'hébergeur,
 * `debug` passe à false et `url` porte le domaine réel.
 */

return [
    'db' => [
        'host' => '127.0.0.1',
        // MAMP écoute sur 8889 ; un hébergement mutualisé, en général sur 3306.
        // Absent, le socle retombe sur 3306.
        'port' => 8889,
        'name' => 'livreyace_sbd',
        'user' => 'root',
        'pass' => 'root',
    ],

    'app' => [
        /*
         * `true` sur le poste de développement uniquement. Sur le serveur, le
         * laisser à false : l'affichage des erreurs révèle les chemins, l'hôte
         * et le nom de la base à n'importe quel visiteur.
         */
        'debug' => true,

        /*
         * Adresse publique, sans barre finale. **Vide en développement**,
         * renseignée sur le serveur : `https://www.philippeyace.ci`.
         *
         * Sans elle, `canonical` et `og:image` retombent sur l'en-tête `Host`
         * de la requête — que le client choisit. C'est le seul réglage dont
         * l'absence ne se voit pas : les pages s'affichent normalement, seules
         * les adresses canoniques sont fausses. Le contrôle se fait en lisant
         * la source d'une page en ligne.
         */
        'url'   => '',
    ],
];
