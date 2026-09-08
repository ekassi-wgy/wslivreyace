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
        // En production : 'url' => 'https://www.philippeyace.ci' dans
        // config/config.local.php — le domaine est arrêté, voir la section
        // `contact` ci-dessous. Laissée vide ici, le socle retombe sur l'hôte
        // de la requête, ce qui convient au poste de développement mais pas à
        // un serveur public : HTTP_HOST est fourni par le client. Écrire le
        // domaine réel ici ferait pointer les canonical du poste de
        // développement vers la production.
        'url'     => '',
        'nom'     => 'Philippe Grégoire Yacé — Une destinée',
        'medias'  => __DIR__ . '/../medias',
        /*
         * Fichiers envoyés par le public et non encore relus (lot G8).
         *
         * **Ce dossier ne doit jamais être servi.** Il porte son propre
         * `.htaccess` en `Require all denied`, et le contrôleur frontal
         * l'exclut de sa réécriture. Un hébergement qui le permet gagnerait à
         * le placer hors de la racine web ; ici, il est refusé sur place.
         */
        'quarantaine' => __DIR__ . '/../quarantaine',
    ],
    /*
     * Coordonnées publiques de la structure éditrice.
     *
     * Ici et pas dans un gabarit : elles paraissent à trois endroits — la page
     * Contact, les mentions légales et le pied de page — et une adresse
     * recopiée trois fois finit par diverger. Ici et pas en base non plus :
     * ce sont des constantes d'organisation, pas du contenu éditorial qui
     * change au fil des semaines ; les mettre dans `parametre` demanderait un
     * écran d'administration pour une valeur qu'on touche tous les cinq ans.
     *
     * Une valeur vide n'est pas affichée : la page se referme proprement sur
     * ce qui manque plutôt que de montrer un libellé sans contenu.
     */
    'contact' => [
        /*
         * Deux adresses et non une : la boîte postale reçoit le courrier, la
         * seconde dit où se trouve la structure. Les confondre sur une seule
         * ligne ferait écrire un numéro de rue sur une enveloppe destinée à
         * une boîte — la page Contact les empile, les mentions légales les
         * séparent d'un tiret.
         */
        'boite_postale' => "15 BP 10125 Abidjan 15, République de Côte d'Ivoire",
        'adresse'   => '7186, Boulevard Philippe Grégoire Yacé',
        'ville'     => "00216 Marcory, District d'Abidjan",
        'pays'      => "Côte d'Ivoire",
        'email'     => 'contact@philippeyace.ci',
        /*
         * Forme lisible pour l'œil, forme internationale pour le lien `tel:`.
         *
         * **`tel_lien` vide veut dire « ce numéro ne se compose pas »**, et
         * c'est le cas aujourd'hui : le numéro n'est pas arrêté, le masque le
         * dit. La page Contact affiche alors le masque en texte simple au lieu
         * d'un lien — un lien `tel:` sur des astérisques ouvrirait le
         * composeur du téléphone sur un numéro impossible.
         *
         * Le jour où le numéro est connu, les deux lignes se remplissent
         * ensemble et le lien revient de lui-même.
         */
        'telephone' => '+225 ** ** ** ** **',
        'tel_lien'  => '',
        'site'      => 'https://www.philippeyace.ci',
    ],

    // Passerelle de paiement retenue : celle du site de référence. Elle peut
    // changer — c'est pourquoi l'hôte est ici et non dans le code. Voir
    // App\Core\Paiement, qui porte les points d'entrée de chaque mode.
    'paiement' => [
        'passerelle' => 'carte.abidjan.net',
        'nom'        => 'Carte Abidjan.net',
        'base'       => 'https://carte.abidjan.net',
    ],
];

$local = __DIR__ . '/config.local.php';
if (is_file($local)) {
    $config = array_replace_recursive($config, require $local);
}

return $config;
