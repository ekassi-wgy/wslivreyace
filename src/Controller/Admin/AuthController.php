<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\TentativeConnexion;
use App\Model\Utilisateur;

/**
 * Connexion et déconnexion du back-office.
 */
final class AuthController
{
    public static function formulaire(): void
    {
        if (Auth::estConnecte()) {
            self::rediriger(Admin::url('/'));
        }

        // Un seul message : la session a expiré pendant que l'onglet dormait.
        if (Session::get('_expiree')) {
            Session::oublier('_expiree');
            Session::message('info', 'Votre session a expiré. Reconnectez-vous.');
        }

        self::afficher();
    }

    public static function connexion(): void
    {
        Csrf::exiger();

        if (Auth::estConnecte()) {
            self::rediriger(Admin::url('/'));
        }

        $v = new Validator($_POST);
        $v->requis('email', 'Adresse électronique')
          ->courriel('email', 'Adresse électronique')
          ->requis('mot_de_passe', 'Mot de passe');

        $email = $v->valeur('email');

        if (!$v->estValide()) {
            self::afficher($v->erreurs(), $email);
        }

        TentativeConnexion::purger();

        if (TentativeConnexion::bloque($email)) {
            $minutes = TentativeConnexion::attenteMinutes($email);
            self::afficher(
                ['email' => "Trop d'essais infructueux. Réessayez dans $minutes minutes."],
                $email
            );
        }

        $compte = Utilisateur::authentifier($email, (string) ($_POST['mot_de_passe'] ?? ''));
        TentativeConnexion::enregistrer($email, $compte !== null);

        if ($compte === null) {
            // Un seul message pour les trois causes possibles — adresse
            // inconnue, mot de passe faux, compte désactivé. Les distinguer
            // reviendrait à confirmer l'existence d'un compte.
            self::afficher(
                ['mot_de_passe' => 'Adresse ou mot de passe incorrect.'],
                $email
            );
        }

        Auth::connecter($compte);
        Session::message('succes', 'Bonjour ' . $compte['nom'] . '.');

        self::rediriger(Auth::destinationApresConnexion());
    }

    public static function deconnexion(): void
    {
        // La déconnexion passe par POST : en GET, une simple balise <img> sur
        // un site tiers suffirait à déconnecter l'éditeur au passage.
        Csrf::exiger();
        Auth::deconnecter();

        // La session vient d'être détruite : il en faut une neuve pour porter
        // le message d'au revoir jusqu'à la page de connexion.
        Session::demarrer('pgyadmin', Admin::base());
        Session::message('info', 'Vous êtes déconnecté.');

        self::rediriger(Admin::url('/connexion'));
    }

    /**
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $erreurs = [], string $email = ''): never
    {
        View::render('admin/pages/connexion', [
            'titre'   => 'Connexion',
            'erreurs' => $erreurs,
            'email'   => $email,
        ], $erreurs === [] ? 200 : 422, 'admin/layout-nu');
        exit;
    }

    private static function rediriger(string $url): never
    {
        header('Location: ' . $url, true, 302);
        exit;
    }
}
