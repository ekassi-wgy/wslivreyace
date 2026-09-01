<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Adresse publique du site.
 *
 * `canonical` et `og:image` exigent des URL absolues : un chemin relatif y est
 * ignoré par les moteurs et par les aperçus de partage.
 *
 * La valeur vient de la configuration, jamais de la requête. Déduire l'hôte de
 * `HTTP_HOST` marcherait la plupart du temps — mais cet en-tête est fourni par
 * le client. Un `Host:` forgé ferait pointer le canonical d'une page vers un
 * domaine tiers, et c'est précisément ce qu'un canonical est censé empêcher.
 *
 * Le repli sur la requête n'existe que pour le poste de développement, où la
 * configuration n'a pas de raison de porter une adresse.
 */
final class Site
{
    private static ?string $base = null;

    /** Racine absolue, sans barre finale. Ex. : « https://philippeyace.ci ». */
    public static function base(): string
    {
        if (self::$base !== null) {
            return self::$base;
        }

        $configure = trim((string) (Config::get('app')['url'] ?? ''));

        if ($configure !== '') {
            return self::$base = rtrim($configure, '/');
        }

        // Repli de développement. En production, `app.url` doit être posée :
        // sans elle, l'adresse dépend d'un en-tête que le client choisit.
        $schema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
               || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
            ? 'https' : 'http';

        $hote = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return self::$base = $schema . '://' . $hote;
    }

    /** URL absolue d'un chemin interne. `url('/le-livre')`. */
    public static function url(string $chemin = '/'): string
    {
        return self::base() . '/' . ltrim($chemin, '/');
    }

    /**
     * URL canonique de la page en cours.
     *
     * Le chemin seul, sans chaîne de requête : deux adresses qui ne diffèrent
     * que par un paramètre de suivi désignent la même page, et c'est tout
     * l'objet d'un canonical de le dire.
     */
    public static function canonique(): string
    {
        $chemin = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $chemin = is_string($chemin) ? $chemin : '/';

        // La racine garde sa barre ; les autres pages n'en portent pas.
        $chemin = $chemin === '/' ? '/' : '/' . trim($chemin, '/');

        return self::base() . $chemin;
    }
}
