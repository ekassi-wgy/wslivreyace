<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Rendu des gabarits. Le gabarit de page est capturé dans un tampon puis
 * injecté dans la mise en page — d'où un seul en-tête, une seule navigation
 * et un seul pied pour tout le site.
 */
final class View
{
    public static function render(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);

        $base = dirname(__DIR__, 2) . '/templates/';
        $file = $base . $template . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("Gabarit introuvable : $template");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        $contenu = ob_get_clean();

        require $base . 'layout.php';
    }

    /** Échappement HTML. Toute donnée issue de la base passe par ici. */
    public static function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
