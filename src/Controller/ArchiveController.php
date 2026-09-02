<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\Media;

/**
 * Galerie d'archives (CDC §4.6).
 *
 * La page publique de la médiathèque : ce que le back-office a déposé,
 * légendé, crédité et publié. Lecture seule, aucune session — comme les
 * actualités.
 *
 * **Rien n'est écrit en dur.** L'ordre est celui que l'éditeur a posé dans la
 * planche du back-office, les catégories sont les siennes, et une image passe
 * en public par le seul fait d'être publiée. Il n'y a pas de second réglage
 * « galerie » : un deuxième endroit où décider l'ordre finirait par contredire
 * le premier.
 */
final class ArchiveController
{
    /**
     * La galerie, éventuellement filtrée par catégorie.
     *
     * Même mécanique que les actualités : le filtre est un paramètre de
     * requête, le canonical le laisse tomber, et une catégorie inconnue rend
     * la planche entière plutôt qu'une 404.
     */
    public static function galerie(): void
    {
        $demande   = trim((string) ($_GET['categorie'] ?? ''));
        $categorie = isset(Media::CATEGORIES[$demande]) ? $demande : null;

        View::render('pages/archives', [
            'page'      => 'archives',
            'images'    => Media::listerPubliees($categorie),
            'categorie' => $categorie,
            'comptes'   => Media::comptesPublies(),
        ]);
    }
}
