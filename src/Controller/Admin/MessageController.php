<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\View;
use App\Model\Message;

/**
 * Boîte de réception du formulaire de contact (CDC §4.11).
 *
 * **C'est le seul écran que le lot F4 ajoute au back-office**, et il n'était
 * pas prévu : le back-office avait été déclaré entier. La raison tient en une
 * ligne — les messages sont écrits en base plutôt qu'envoyés par courriel
 * (voir `App\Model\Message`), et une boîte que personne ne peut ouvrir ne sert
 * à rien.
 *
 * Il ne dérive pas de `CrudController`, comme la file de modération et pour la
 * même raison : on ne crée pas un message, on en reçoit. Il ne se corrige pas
 * non plus — ce sont les mots de quelqu'un d'autre, et contrairement à un
 * témoignage ils ne seront jamais publiés, donc il n'y a rien à retoucher. Les
 * deux seuls verbes sont « traité » et « supprimer ».
 */
final class MessageController
{
    private const CHEMIN = '/messages';

    public static function liste(array $params = []): void
    {
        // Le filtre vient de la barre d'adresse : on ne garde qu'une valeur
        // connue, sinon la vue afficherait un onglet actif qui n'existe pas.
        $filtre = $_GET['statut'] ?? 'tous';
        if (!isset(Message::STATUTS[$filtre])) {
            $filtre = 'tous';
        }

        View::admin('messages/liste', [
            'titre'     => 'Messages',
            'actif'     => 'messages',
            'filtre'    => $filtre,
            'lignes'    => Message::listerPar($filtre === 'tous' ? null : $filtre),
            'compteurs' => Message::compteurs(),
        ]);
    }

    /** Bascule entre « nouveau » et « traité ». */
    public static function marquer(array $params): void
    {
        Csrf::exiger();

        $id     = (int) $params['id'];
        $statut = (string) ($params['statut'] ?? '');

        if (Message::trouver($id) === null || !isset(Message::STATUTS[$statut])) {
            self::retour($params);
        }

        Message::marquer($id, $statut, (int) (Auth::utilisateur()['id'] ?? 0));

        Session::message('succes', $statut === 'traite'
            ? 'Message marqué comme traité.'
            : 'Message remis dans les nouveaux.');

        self::retour($params);
    }

    /**
     * Suppression définitive.
     *
     * Il n'y a pas de corbeille, et c'est assumé : un message de contact n'a
     * pas vocation à être conservé une fois la réponse envoyée — c'est aussi ce
     * que disent les mentions légales au visiteur.
     */
    public static function supprimer(array $params): void
    {
        Csrf::exiger();

        Message::supprimer((int) $params['id']);
        Session::message('succes', 'Message supprimé.');

        self::retour($params);
    }

    /** Retour à la liste, en conservant le filtre d'où l'action est partie. */
    private static function retour(array $params): never
    {
        $filtre = (string) ($_POST['retour'] ?? '');
        $suffixe = isset(Message::STATUTS[$filtre]) ? '?statut=' . $filtre : '';

        header('Location: ' . Admin::url(self::CHEMIN) . $suffixe, true, 303);
        exit;
    }
}
