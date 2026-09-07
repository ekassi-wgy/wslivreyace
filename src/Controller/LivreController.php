<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Model\Actualite;
use App\Model\Evenement;
use App\Model\Parametre;

/**
 * L'ouvrage et son auteur (CDC §4.2 et §4.3, brief §2, lot G2).
 *
 * La page du livre était rendue par une fermeture sans données : elle lit
 * désormais la préface, l'auteur, la revue de presse et les événements de
 * lancement, que le brief demande d'y rattacher.
 */
final class LivreController
{
    public static function livre(): void
    {
        $p = Parametre::toutes();

        View::render('pages/livre', [
            'page'      => 'livre',
            'reglages'  => $p,
            'preface'   => self::preface($p),
            // Le brief rattache à la page du livre la revue de presse et les
            // événements de lancement, qui vivaient chacun de leur côté.
            'presse'    => Actualite::listerPubliees('presse', 3),
            'aVenir'    => Evenement::aVenir(3),
        ]);
    }

    /**
     * La page de l'auteur.
     *
     * **404 tant que le nom n'est pas renseigné**, et non une page vide : une
     * fiche d'auteur sans auteur n'est pas une page, et une adresse qui répond
     * avec un gabarit creux se fait indexer telle quelle.
     */
    public static function auteur(): void
    {
        $nom = trim((string) Parametre::lire('auteur_nom', ''));

        if ($nom === '') {
            View::render('pages/404', ['titre' => 'Page introuvable', 'page' => 'livre'], 404);
            return;
        }

        View::render('pages/auteur', [
            'page'       => 'livre',
            'nom'        => $nom,
            'qualite'    => (string) Parametre::lire('auteur_qualite', ''),
            'bio'        => (string) Parametre::lire('auteur_bio', ''),
            'livreTitre' => (string) Parametre::lire('livre_titre', 'Une destinée'),
        ]);
    }

    /**
     * L'état de la préface, tel que le back-office l'a réglé.
     *
     * `avant` décide de la place du bloc dans la page — et de la présence d'un
     * bandeau sur l'accueil. C'est un réglage et non un choix de gabarit :
     * voir `Parametre::AUTOUR_LIVRE` pour le raisonnement.
     *
     * @param array<string,mixed> $p
     * @return array{presente:bool,avant:bool,auteur:string,qualite:string,extrait:string,texte:string}
     */
    public static function preface(array $p): array
    {
        $auteur  = trim((string) ($p['preface_auteur'] ?? ''));
        $extrait = trim((string) ($p['preface_extrait'] ?? ''));
        $texte   = trim((string) ($p['preface_texte'] ?? ''));

        return [
            // Une signature seule suffit à annoncer la préface, même avant que
            // le texte n'arrive : c'est justement ce qu'on voudra afficher dès
            // que la préface présidentielle sera confirmée.
            'presente' => $auteur !== '' || $texte !== '',
            'avant'    => ($p['preface_avant'] ?? '') === '1' && $auteur !== '',
            'auteur'   => $auteur,
            'qualite'  => trim((string) ($p['preface_qualite'] ?? '')),
            'extrait'  => $extrait,
            'texte'    => $texte,
        ];
    }
}
