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

    public function get(string $pattern, callable $action): void
    {
        $this->routes['GET'][$pattern] = $action;
    }

    public function post(string $pattern, callable $action): void
    {
        $this->routes['POST'][$pattern] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = '/' . trim(parse_url($uri, PHP_URL_PATH) ?? '/', '/');

        foreach ($this->routes[$method] ?? [] as $pattern => $action) {
            $regex = '#^' . preg_replace('#\{([a-z_]+)\}#', '(?P<$1>[a-z0-9\-]+)', $pattern) . '$#i';
            if (preg_match($regex, $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                $action($params);
                return;
            }
        }

        http_response_code(404);
        View::render('pages/404', ['titre' => 'Page introuvable'], 404);
    }
}
