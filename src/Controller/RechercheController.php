<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Recherche;
use App\Core\View;

/**
 * La recherche du site (brief §9, lot G9).
 *
 * Une page et une adresse — `/recherche?q=…` — plutôt qu'un panneau surgissant :
 * une recherche se partage, se met en favori, et se retrouve dans l'historique.
 * C'est aussi ce qui permet à la page 404 d'y renvoyer avec un terme
 * pré-rempli, ce qui compte quand des adresses sont imprimées (décision 3).
 *
 * Lecture seule, aucune session.
 */
final class RechercheController
{
    public static function page(): void
    {
        $terme = trim((string) ($_GET['q'] ?? ''));

        // Une recherche trop courte n'est pas une erreur : c'est la page
        // d'accueil de la recherche, avec son champ vide.
        $resultats = Recherche::partout($terme);

        View::render('pages/recherche', [
            'page'      => '',
            'terme'     => $terme,
            'resultats' => $resultats,
            'total'     => Recherche::compter($resultats),
            // `noindex` : une page de résultats n'a rien à faire dans un index.
            // Elle change à chaque contenu ajouté, elle duplique ce que les
            // pages disent déjà, et Google la traite comme du remplissage.
            'robots'    => 'noindex, follow',
        ]);
    }
}
