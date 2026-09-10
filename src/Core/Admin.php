<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Repères du back-office : préfixe d'URL et arborescence du menu.
 *
 * Le préfixe est déduit de l'emplacement réel du contrôleur frontal plutôt
 * qu'écrit en dur. Renommer le dossier `cmsadmin`, ou déplacer le site dans un
 * sous-répertoire d'hébergement, ne demande alors aucune retouche.
 */
final class Admin
{
    private static ?string $base = null;

    /** Préfixe d'URL de l'admin, sans barre finale. Ex. : "/cmsadmin". */
    public static function base(): string
    {
        if (self::$base === null) {
            $dir = dirname($_SERVER['SCRIPT_NAME'] ?? '/cmsadmin/index.php');
            self::$base = rtrim(str_replace('\\', '/', $dir), '/');
        }
        return self::$base;
    }

    /** URL absolue d'une page de l'admin. `url('/actualites')`. */
    public static function url(string $chemin = '/'): string
    {
        return self::base() . '/' . ltrim($chemin, '/');
    }

    /** URL d'un asset du thème. `asset('css/style.css')`. */
    public static function asset(string $chemin): string
    {
        return self::base() . '/assets/' . ltrim($chemin, '/');
    }

    /**
     * Arborescence du menu latéral.
     *
     * Une seule source : le gabarit s'en sert pour dessiner la barre, et la
     * clé `actif` passée à la vue s'y compare pour marquer l'entrée courante.
     * Les entrées non encore construites portent `bientot` — elles restent
     * visibles, désactivées : le commanditaire voit la forme finale du
     * back-office dès le premier lot.
     */
    public static function menu(): array
    {
        return [
            [
                'cle'   => 'tableau-de-bord',
                'titre' => 'Tableau de bord',
                'icone' => 'mdi-view-dashboard-outline',
                'url'   => self::url('/'),
            ],
            ['rubrique' => 'Contenus'],
            [
                'cle'    => 'actualites',
                'titre'  => 'Actualités',
                'icone'  => 'mdi-newspaper-variant-outline',
                'url'    => self::url('/actualites'),
            ],
            [
                'cle'    => 'evenements',
                'titre'  => 'Événements',
                'icone'  => 'mdi-calendar-star',
                'url'    => self::url('/evenements'),
            ],
            /*
             * La biographie précède les repères, et l'ordre a un sens depuis
             * le lot G10 : la période est le récit, le repère est le jalon
             * qu'elle traverse. C'est aussi l'ordre dans lequel un éditeur
             * travaille — on découpe, puis on date.
             */
            [
                'cle'    => 'periodes',
                'titre'  => 'Biographie',
                'icone'  => 'mdi-book-open-page-variant-outline',
                'url'    => self::url('/periodes'),
            ],
            [
                'cle'    => 'reperes',
                'titre'  => 'Repères',
                'icone'  => 'mdi-timeline-text-outline',
                'url'    => self::url('/reperes'),
            ],
            [
                'cle'    => 'archives',
                'titre'  => 'Archives',
                'icone'  => 'mdi-archive-outline',
                'url'    => self::url('/archives'),
            ],
            [
                'cle'    => 'heritage',
                'titre'  => 'Héritage',
                'icone'  => 'mdi-bank-outline',
                'url'    => self::url('/heritage'),
            ],
            /*
             * Les points de vente (lot G12). Dans « Contenus » et non dans
             * « Administration », où vivent les zones de livraison : une
             * librairie qui tient le livre en rayon est une information de
             * communication, pas une décision commerciale. Celui qui rédige
             * l'actualité d'une dédicace est celui qui sait où l'on achète.
             */
            /*
             * Les citations (lot G14). Placées avec les contenus et non avec
             * les réglages : choisir la phrase qui ouvre l'accueil est un acte
             * éditorial, au même titre qu'écrire une actualité.
             */
            [
                'cle'    => 'citations',
                'titre'  => 'Citations',
                'icone'  => 'mdi-format-quote-close',
                'url'    => self::url('/citations'),
            ],
            [
                'cle'    => 'points-de-vente',
                'titre'  => 'Points de vente',
                'icone'  => 'mdi-store-outline',
                'url'    => self::url('/points-de-vente'),
            ],
            [
                'cle'    => 'medias',
                'titre'  => 'Médiathèque',
                'icone'  => 'mdi-image-multiple-outline',
                'url'    => self::url('/medias'),
            ],
            [
                'cle'    => 'traductions',
                'titre'  => 'Traductions',
                'icone'  => 'mdi-translate',
                'url'    => self::url('/traductions'),
            ],
            ['rubrique' => 'Modération'],
            [
                'cle'    => 'temoignages',
                'titre'  => 'Témoignages',
                'icone'  => 'mdi-comment-check-outline',
                'url'    => self::url('/temoignages'),
            ],
            [
                'cle'    => 'contributions',
                'titre'  => 'Contributions',
                'icone'  => 'mdi-inbox-arrow-down-outline',
                'url'    => self::url('/contributions'),
            ],
            [
                'cle'    => 'messages',
                'titre'  => 'Messages',
                'icone'  => 'mdi-email-outline',
                'url'    => self::url('/messages'),
            ],
            ['rubrique' => 'Administration'],
            [
                'cle'    => 'zones',
                'titre'  => 'Zones de livraison',
                'icone'  => 'mdi-truck-delivery-outline',
                'url'    => self::url('/zones'),
                // Comme les commandes : un tarif de livraison est une décision
                // commerciale, pas une décision éditoriale (lot G3).
                'role'   => 'admin',
            ],
            [
                'cle'    => 'commandes',
                'titre'  => 'Commandes',
                'icone'  => 'mdi-package-variant-closed',
                'url'    => self::url('/commandes'),
                'role'   => 'admin',
            ],
            [
                'cle'    => 'parametres',
                'titre'  => 'Paramètres',
                'icone'  => 'mdi-tune-variant',
                'url'    => self::url('/parametres'),
            ],
            [
                'cle'    => 'comptes',
                'titre'  => 'Comptes',
                'icone'  => 'mdi-account-key-outline',
                'url'    => self::url('/comptes'),
                'role'   => 'admin',
            ],
            /*
             * Le manuel de l'éditeur (lot G15). En fin de rubrique parce qu'on
             * ne le consulte pas tous les jours, mais dans le menu parce que
             * celui qui en a besoin est devant cet écran — le chercher dans un
             * dossier de fichiers supposait de savoir qu'il existe.
             *
             * **Sans `role`** : c'est l'éditeur qui en a le plus l'usage, et
             * le réserver aux administrateurs le retirerait précisément à son
             * destinataire.
             */
            [
                'cle'    => 'manuel',
                'titre'  => "Manuel de l'éditeur",
                'icone'  => 'mdi-book-open-page-variant-outline',
                'url'    => self::url('/manuel'),
            ],
        ];
    }

    /**
     * Le menu tel qu'un compte donné a le droit de le voir.
     *
     * Une entrée réservée aux administrateurs est retirée pour les autres, et
     * non affichée grisée : un verrou dit « pas encore construit », ce qui
     * serait faux ici. Les rubriques qui se retrouveraient vides tombent avec
     * leurs entrées — un intertitre sans rien dessous fait croire à un bogue.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function menuPour(bool $estAdmin): array
    {
        $entrees = array_values(array_filter(
            self::menu(),
            static fn(array $e): bool => $estAdmin || ($e['role'] ?? '') !== 'admin'
        ));

        $garde = [];
        foreach ($entrees as $i => $entree) {
            $suivante = $entrees[$i + 1] ?? null;

            // Une rubrique n'est gardée que si une entrée la suit.
            if (isset($entree['rubrique']) && ($suivante === null || isset($suivante['rubrique']))) {
                continue;
            }

            $garde[] = $entree;
        }

        return $garde;
    }
}
