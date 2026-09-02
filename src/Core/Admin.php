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
            [
                'cle'    => 'reperes',
                'titre'  => 'Repères',
                'icone'  => 'mdi-timeline-text-outline',
                'url'    => self::url('/reperes'),
            ],
            [
                'cle'    => 'medias',
                'titre'  => 'Médiathèque',
                'icone'  => 'mdi-image-multiple-outline',
                'url'    => self::url('/medias'),
            ],
            ['rubrique' => 'Modération'],
            [
                'cle'    => 'temoignages',
                'titre'  => 'Témoignages',
                'icone'  => 'mdi-comment-check-outline',
                'url'    => self::url('/temoignages'),
            ],
            ['rubrique' => 'Administration'],
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
