<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Routeur minimal. Les motifs acceptent des segments nommés {slug}, traduits
 * en groupes de capture ; les valeurs sont passées à l'action.
 */
final class Router
{
    /** @var array<string,array<string,callable>> */
    private array $routes = ['GET' => [], 'POST' => []];

    /** Repli quand aucun motif ne correspond. */
    private $introuvable = null;

    public function get(string $pattern, callable $action): void
    {
        $this->routes['GET'][self::normalise($pattern)] = $action;
    }

    public function post(string $pattern, callable $action): void
    {
        $this->routes['POST'][self::normalise($pattern)] = $action;
    }

    /**
     * Forme canonique d'un chemin : une barre de tête, aucune barre finale.
     *
     * Appliquée aux motifs autant qu'à l'URL reçue. Ne la faire que d'un côté
     * revenait à ce que `/cmsadmin/` déclaré ne réponde jamais, l'URL entrante
     * arrivant dépouillée de sa barre finale.
     */
    public static function normalise(string $chemin): string
    {
        return '/' . trim($chemin, '/');
    }

    /**
     * Page servie en 404. Le back-office a la sienne : sans ce point
     * d'accroche, une URL fautive sous /cmsadmin/ répondait avec l'en-tête et
     * le pied du site public.
     */
    public function introuvable(callable $action): void
    {
        $this->introuvable = $action;
    }

    /**
     * Le motif d'une route, compilé en expression régulière.
     *
     * **Les segments littéraux sont échappés**, et cela compte depuis que
     * `/sitemap.xml` existe : sans `preg_quote`, son point vaut « n'importe
     * quel caractère » et `/sitemapaxml` répondrait la même chose. Rien de
     * grave ici — une adresse fantaisiste de plus — mais un motif de route qui
     * ne dit pas ce qu'il a l'air de dire finit par surprendre ailleurs.
     *
     * L'échappement ne peut pas s'appliquer au motif entier : `preg_quote`
     * neutraliserait aussi les accolades des segments nommés. Le motif est donc
     * découpé sur ces accolades, et chaque morceau traité selon sa nature.
     */
    private static function motif(string $pattern): string
    {
        $morceaux = preg_split('#(\{[a-z_]+\})#', $pattern, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [];
        $regex = '';

        foreach ($morceaux as $morceau) {
            /*
             * Le tiret bas est accepté depuis le lot G3. Les slugs du site n'en
             * portent pas — `App\Core\Slug` translittère en `[a-z0-9-]` — mais
             * l'écran de traduction adresse ses rubriques par **nom de table**,
             * et les tables en portent : `zone_livraison`. Sans lui, la route
             * ne correspondait pas et l'écran répondait 404.
             *
             * Élargir ne relâche aucune garde : un segment fantaisiste atteint
             * le contrôleur au lieu du repli du routeur, et le contrôleur rend
             * la même 404 après n'avoir rien trouvé.
             */
            $regex .= preg_match('#^\{([a-z_]+)\}$#', $morceau, $m) === 1
                ? '(?P<' . $m[1] . '>[a-z0-9_\-]+)'
                : preg_quote($morceau, '#');
        }

        return '#^' . $regex . '$#i';
    }

    /**
     * Détache le préfixe de langue du chemin reçu, et le déclare.
     *
     * **Un seul endroit décide de la langue de la requête** (lot G1). Les
     * routes sont déclarées une fois, sans préfixe : `/le-livre` répond aussi
     * bien à `/le-livre` qu'à `/en/le-livre`, et c'est `Langue` qui dit laquelle
     * des deux a été demandée. Déclarer chaque route deux fois aurait garanti
     * qu'une des deux séries finisse par manquer une adresse.
     *
     * Un préfixe déclaré mais fermé — `/en/` tant que rien n'est traduit — rend
     * le chemin inchangé : aucune route ne correspondra à `/en/le-livre`, et la
     * 404 tombera d'elle-même. C'est la réponse juste : la page n'existe pas
     * encore.
     */
    private static function langue(string $path): string
    {
        if (preg_match('#^/([a-z]{2})(/.*)?$#', $path, $m) !== 1) {
            Langue::poserCheminNu($path);

            return $path;
        }

        [, $code, $reste] = $m + [2 => ''];

        if ($code === Langue::DEFAUT || !Langue::fixer($code)) {
            Langue::poserCheminNu($path);

            return $path;
        }

        $nu = self::normalise($reste === '' ? '/' : $reste);
        Langue::poserCheminNu($nu);

        return $nu;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = self::langue(self::normalise(parse_url($uri, PHP_URL_PATH) ?? '/'));

        foreach ($this->routes[$method] ?? [] as $pattern => $action) {
            if (preg_match(self::motif($pattern), $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                $action($params);
                return;
            }
        }

        if ($this->introuvable !== null) {
            ($this->introuvable)();
            return;
        }

        View::render('pages/404', ['titre' => 'Page introuvable'], 404);
    }
}
