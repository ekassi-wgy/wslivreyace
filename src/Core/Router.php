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
    private static function normalise(string $chemin): string
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

    public function dispatch(string $method, string $uri): void
    {
        $path = self::normalise(parse_url($uri, PHP_URL_PATH) ?? '/');

        foreach ($this->routes[$method] ?? [] as $pattern => $action) {
            $regex = '#^' . preg_replace('#\{([a-z_]+)\}#', '(?P<$1>[a-z0-9\-]+)', $pattern) . '$#i';
            if (preg_match($regex, $path, $m)) {
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
