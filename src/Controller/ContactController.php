<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Config;
use App\Core\Csrf;
use App\Core\Debit;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Message;

/**
 * Contact et mentions légales (CDC §4.11 et §4.12).
 *
 * Le second formulaire ouvert du site, et il ne réinvente rien : la plomberie
 * posée avec les témoignages — session ouverte route par route, jeton CSRF,
 * plafond de débit, champ leurre, délai minimal, saisie renvoyée en cas
 * d'échec — se reprend telle quelle. Le barème `contact` était même déjà
 * déclaré dans `Debit::BAREMES`, avant qu'aucun formulaire ne l'utilise.
 *
 * **Ce qui change tient au dépôt.** Un témoignage part en modération pour être
 * publié ; un message part dans une boîte de réception pour recevoir une
 * réponse. Il n'est jamais affiché nulle part.
 *
 * Les mentions légales, elles, ne lisent rien et n'écrivent rien : elles sont
 * ici parce qu'elles partagent les coordonnées de la page Contact, et qu'un
 * seul endroit doit les porter.
 */
final class ContactController
{
    /** Clé du barème de débit, déclarée dans App\Core\Debit. */
    private const ACTION = 'contact';

    /**
     * Délai minimal entre l'affichage du formulaire et son envoi.
     *
     * Même valeur que pour les témoignages, et pour la même raison : un robot
     * poste en moins d'une seconde, un humain qui écrit met bien davantage.
     */
    private const DELAI_MINIMAL = 3;

    /** Nom du champ leurre. Anodin à dessein : un robot le remplira. */
    private const LEURRE = 'site_web';

    public static function page(): void
    {
        Session::demarrer('pgy', '/');
        Session::set('_contact_ouvert_le', time());

        self::afficher([], []);
    }

    public static function envoyer(): void
    {
        Session::demarrer('pgy', '/');
        Csrf::exiger();

        // Le plafond d'abord : refuser tôt évite d'écrire quoi que ce soit.
        if (!Debit::autorise(self::ACTION)) {
            self::afficher($_POST, ['_global' => Debit::refus(self::ACTION)], 429);
        }

        // Toute soumission compte, valide ou non — sinon le plafond se
        // contournerait en envoyant des formulaires fautifs.
        Debit::enregistrer(self::ACTION);

        $v = self::valider($_POST);

        if (!$v->estValide()) {
            self::afficher($_POST, $v->erreurs(), 422);
        }

        Message::deposer([
            'nom'     => $v->valeur('nom'),
            'email'   => $v->valeur('email'),
            'sujet'   => $v->valeur('sujet', 'autre'),
            'contenu' => $v->valeur('contenu'),
        ]);

        Session::oublier('_contact_ouvert_le');

        Session::message('succes', sprintf(
            'Merci, %s. Votre message est bien arrivé et sera lu. '
            . 'La réponse partira vers %s.',
            $v->valeur('nom'),
            $v->valeur('email')
        ));

        // Redirection après écriture : sans elle, un rafraîchissement de page
        // renverrait le même message.
        header('Location: /contact#message', true, 303);
        exit;
    }

    /** Mentions légales. Aucune donnée, aucune session, aucun cookie. */
    public static function mentions(): void
    {
        View::render('pages/mentions-legales', [
            'page'    => 'mentions',
            'contact' => Config::get('contact'),
        ]);
    }

    // -- Rouages -----------------------------------------------------------

    /** @param array<string,mixed> $post */
    private static function valider(array $post): Validator
    {
        $v = new Validator($post);

        $v->requis('nom', 'Votre nom')->longueur('nom', 'Votre nom', 2, 160)
          ->requis('email', 'Votre adresse électronique')
          ->courriel('email', 'Votre adresse électronique')
          ->longueur('email', 'Votre adresse électronique', 0, 180)
          ->parmi('sujet', 'Motif', array_keys(Message::SUJETS))
          ->requis('contenu', 'Votre message')
          ->longueur('contenu', 'Votre message', 20, 5000);

        // Piège à robots : champ masqué et retiré aux lecteurs d'écran.
        if (trim((string) ($post[self::LEURRE] ?? '')) !== '') {
            $v->erreur('_global', 'Envoi refusé : un champ réservé au filtrage a été rempli. '
                . 'Si vous voyez ce message par erreur, laissez le champ « site web » vide.');
        }

        // Délai minimal. Le refus est dit, jamais silencieux : quelqu'un qui a
        // écrit dix lignes doit pouvoir les renvoyer.
        $ouvert = Session::get('_contact_ouvert_le');

        if (is_int($ouvert) && time() - $ouvert < self::DELAI_MINIMAL) {
            $v->erreur('_global', 'Envoi trop rapide pour être relu. Reprenez votre texte '
                . 'et renvoyez-le : il est resté dans le champ.');
        }

        return $v;
    }

    /**
     * Rend la page : les coordonnées, puis le formulaire.
     *
     * En cas d'erreur la saisie repart avec la page — la règle posée avec les
     * témoignages : on ne fait pas réécrire quelqu'un parce qu'il a oublié un
     * champ.
     *
     * @param array<string,mixed>  $valeurs
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $valeurs, array $erreurs, int $statut = 200): void
    {
        // Le formulaire est réaffiché : le délai minimal repart d'ici, sans
        // quoi une correction rapide serait prise pour un envoi automatisé.
        if ($erreurs !== []) {
            Session::set('_contact_ouvert_le', time());
        }

        View::render('pages/contact', [
            'page'    => 'contact',
            'contact' => Config::get('contact'),
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
            'leurre'  => self::LEURRE,
        ], $statut);

        if ($statut !== 200) {
            exit;
        }
    }
}
