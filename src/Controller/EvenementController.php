<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\Evenement;
use App\Model\Media;

/**
 * Agenda des événements (CDC §4.10).
 *
 * Deux temps sur une seule page : ce qui vient, puis ce qui a eu lieu. Dans
 * cet ordre, et sans pagination — un agenda se lit par le haut, et le passé
 * n'est là que pour montrer qu'il s'en tient.
 *
 * **Un événement annulé reste affiché**, marqué comme tel, tant qu'il n'est
 * pas passé : il a été annoncé, quelqu'un l'a peut-être noté. Le retirer en
 * silence laisserait cette personne se déplacer. Une fois la date franchie, il
 * disparaît — un rendez-vous qui n'a pas eu lieu n'a rien à archiver.
 */
final class EvenementController
{
    public static function liste(): void
    {
        View::render('pages/evenements', [
            'page'    => 'evenements',
            'aVenir'  => Evenement::aVenir(),
            'passes'  => Evenement::passes(),
        ]);
    }

    /** Un événement, par son slug. */
    public static function detail(array $params): void
    {
        $evenement = Evenement::parSlug((string) ($params['slug'] ?? ''));

        // Inconnu ou en brouillon : même réponse, sans distinguer les deux.
        if ($evenement === null) {
            View::render('pages/404', [], 404);
            exit;
        }

        $fichier = trim((string) ($evenement['image'] ?? ''));

        View::render('pages/evenement', [
            'page'      => 'evenements',
            'evenement' => $evenement,
            // La colonne `image` porte un chemin, pas une clé étrangère : le
            // fichier peut avoir quitté la médiathèque, la page s'affiche
            // alors sans illustration.
            'media'     => $fichier === '' ? null : Media::parFichier($fichier),
            'passe'     => Evenement::estPasse($evenement),
        ]);
    }
}
