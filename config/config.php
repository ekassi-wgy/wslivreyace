<?php
/**
 * Configuration commune à toutes les machines.
 *
 * **Ce fichier ne porte aucun identifiant, et c'est délibéré.** Il voyage à
 * chaque envoi — il contient les coordonnées publiques, les points de vente,
 * la chaîne WhatsApp — et tout ce qu'on y laisserait d'un environnement
 * écraserait celui du serveur à la première mise en ligne. Il fallait alors
 * retaper les identifiants en ligne, le site en erreur pendant ce temps.
 *
 * Ce qui dépend de la machine — base de données, affichage des erreurs,
 * adresse publique — vit dans `config/config.local.php`, ignoré par git et
 * jamais envoyé. Il existe sur le poste de développement comme sur le
 * serveur, chacun avec ses valeurs. Modèle : `config.local.exemple.php`.
 *
 * `array_replace_recursive` le fusionne par-dessus, clé par clé — voir la
 * note en fin de fichier, cette finesse a déjà piégé un envoi.
 */

$config = [
    /*
     * Base de données — vide ici, renseignée par `config.local.php`.
     *
     * `null` plutôt qu'une valeur de repli : une configuration absente doit
     * s'arrêter net. L'ancienne version retombait sur MAMP, ce qui veut dire
     * qu'un serveur mal configuré tentait silencieusement de joindre
     * `127.0.0.1:8889` en `root`/`root` — et rendait une demi-page.
     * `src/bootstrap.php` vérifie maintenant ces valeurs avant toute chose.
     */
    'db' => [
        'host'    => null,
        'port'    => null,
        'name'    => null,
        'user'    => null,
        'pass'    => null,
        // Le jeu de caractères n'est pas un secret et ne change pas d'une
        // machine à l'autre : lui seul reste ici.
        'charset' => 'utf8mb4',
    ],
    'app' => [
        /*
         * **`false` par défaut, et c'est le point important.**
         *
         * L'affichage des erreurs montre les chemins absolus du serveur,
         * l'hôte et le nom de la base, et la requête fautive. C'est donc
         * `true` qui est l'exception, posé dans le `config.local.php` du
         * poste de développement — jamais l'inverse. Une configuration
         * oubliée ne peut plus allumer les traces sur un site public.
         */
        'debug'   => false,
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
    /*
     * Réseaux publics.
     *
     * Ici pour la même raison que les coordonnées : une adresse de chaîne est
     * une constante d'organisation, pas du contenu éditorial. Elle s'ouvre une
     * fois et ne bouge plus ; lui bâtir un écran d'administration serait faire
     * un formulaire pour une ligne qu'on ne rouvrira pas.
     *
     * **Une valeur vide n'affiche rien** — ni le bloc du pied, ni le bandeau
     * des actualités. C'est ainsi qu'on ferme un canal : on vide la ligne, on
     * ne touche à aucun gabarit. Même principe que `tel_lien` ci-dessus.
     */
    'reseaux' => [
        /*
         * Chaîne WhatsApp — diffusion seule. Les abonnés ne se voient pas
         * entre eux et leur numéro n'est pas exposé : c'est ce que dit le
         * bandeau, et c'est ce qui lève l'hésitation à s'abonner.
         */
        'whatsapp_chaine' => 'https://whatsapp.com/channel/0029VbDYE1j2Jl8F3ZEnci3h',
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
