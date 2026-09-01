<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Rendu des gabarits. Le gabarit de page est capturé dans un tampon puis
 * injecté dans la mise en page — d'où un seul en-tête, une seule navigation
 * et un seul pied pour tout le site.
 *
 * Deux mises en page coexistent : `layout` pour le site public, `admin/layout`
 * pour le back-office. Le choix est explicite à l'appel, jamais déduit du
 * chemin du gabarit.
 */
final class View
{
    public static function render(
        string $template,
        array $data = [],
        int $status = 200,
        string $layout = 'layout'
    ): void {
        http_response_code($status);

        $base = dirname(__DIR__, 2) . '/templates/';
        $file = $base . $template . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("Gabarit introuvable : $template");
        }

        $fichierLayout = $base . $layout . '.php';
        if (!is_file($fichierLayout)) {
            throw new \RuntimeException("Mise en page introuvable : $layout");
        }

        // EXTR_SKIP : les variables de travail ci-dessus ne peuvent pas être
        // écrasées par une clé de $data qui porterait le même nom.
        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        $contenu = ob_get_clean();

        require $fichierLayout;
    }

    /** Rendu d'une page du back-office. */
    public static function admin(string $template, array $data = [], int $status = 200): void
    {
        self::render('admin/pages/' . $template, $data, $status, 'admin/layout');
    }

    /** Échappement HTML. Toute donnée issue de la base passe par ici. */
    public static function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
