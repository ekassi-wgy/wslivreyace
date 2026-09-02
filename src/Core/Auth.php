<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Utilisateur;

/**
 * État d'authentification du back-office.
 *
 * La session ne retient que l'identifiant du compte ; le nom, le rôle et
 * l'état actif sont relus en base à chaque requête. Un compte désactivé, ou
 * dont le rôle est retiré, perd donc ses droits immédiatement — pas à la
 * prochaine reconnexion.
 */
final class Auth
{
    private const CLE = '_utilisateur_id';

    /** @var array<string,mixed>|null|false false = pas encore cherché */
    private static array|null|false $cache = false;

    /** @return array<string,mixed>|null */
    public static function utilisateur(): ?array
    {
        if (self::$cache !== false) {
            return self::$cache;
        }

        $id = Session::get(self::CLE);
        if (!is_int($id)) {
            return self::$cache = null;
        }

        $compte = Utilisateur::parId($id);

        // Compte supprimé ou désactivé entre deux requêtes : la session ne doit
        // pas lui survivre.
        if ($compte === null || (int) $compte['actif'] !== 1) {
            self::deconnecter();
            return self::$cache = null;
        }

        return self::$cache = $compte;
    }

    public static function estConnecte(): bool
    {
        return self::utilisateur() !== null;
    }

    public static function estAdmin(): bool
    {
        return (self::utilisateur()['role'] ?? null) === 'admin';
    }

    /** Ouvre la session authentifiée. $compte vient de Utilisateur::authentifier(). */
    public static function connecter(array $compte): void
    {
        // Nouvel identifiant de session ET nouveau jeton CSRF : ce qui a été
        // émis avant l'authentification ne doit pas rester valable après.
        Session::regenerer();
        Csrf::renouveler();

        Session::set(self::CLE, (int) $compte['id']);
        Session::set('_ouverte_le', time());
        self::$cache = false;
    }

    public static function deconnecter(): void
    {
        Session::detruire();
        self::$cache = null;
    }

    /**
     * Garde du back-office, posée avant le routage.
     *
     * Le principe est celui de la liste blanche : tout chemin qui n'est pas
     * énuméré exige une session. Une route ajoutée plus tard et oubliée est
     * donc protégée par défaut — l'inverse, une liste de routes à protéger,
     * fait qu'un oubli ouvre une page.
     *
     * Elle s'exécute avant le `dispatch` et non entre deux déclarations de
     * routes : celles-ci sont toutes enregistrées avant d'être appariées, une
     * garde intercalée s'exécuterait à l'enregistrement et bloquerait aussi le
     * formulaire de connexion.
     *
     * @param array<int,string> $ouvertes chemins accessibles sans session
     */
    public static function garder(array $ouvertes): void
    {
        $chemin = Router::normalise(self::cheminCourant());

        foreach ($ouvertes as $ouverte) {
            if (Router::normalise($ouverte) === $chemin) {
                return;
            }
        }

        self::exigerConnexion();
    }

    /**
     * Interrompt la requête si personne n'est connecté.
     *
     * En GET, l'adresse demandée est mémorisée pour y revenir après connexion.
     * En POST elle ne l'est pas : rejouer une écriture après un détour par le
     * formulaire de connexion serait une surprise, et le corps de la requête
     * est perdu de toute façon.
     */
    public static function exigerConnexion(): void
    {
        if (self::estConnecte()) {
            return;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            Session::set('_apres_connexion', self::cheminCourant());
        }

        header('Location: ' . Admin::url('/connexion'), true, 302);
        exit;
    }

    /**
     * Interrompt la requête si le compte connecté n'est pas administrateur.
     *
     * Deux écrans en dépendent : les comptes — un éditeur qui se donne le rôle
     * admin n'aurait plus de rôle du tout — et les commandes, qui portent des
     * noms, des adresses et des numéros de téléphone de clients. Le principe
     * est celui du moindre accès : l'éditeur travaille sur les contenus, pas
     * sur les personnes.
     *
     * Posée dans l'action et non dans la garde de route : celle-ci s'exécute
     * avant le routage et ne sait pas encore quel écran est demandé.
     */
    public static function exigerAdmin(): void
    {
        if (self::estAdmin()) {
            return;
        }

        View::admin('403', ['titre' => 'Accès refusé', 'actif' => ''], 403);
        exit;
    }

    /** Chemin où revenir après connexion, vérifié puis consommé. */
    public static function destinationApresConnexion(): string
    {
        $cible = Session::get('_apres_connexion');
        Session::oublier('_apres_connexion');

        // Seul un chemin interne au back-office est accepté. Sans ce filtre,
        // une valeur déposée en session ferait de la page de connexion un
        // tremplin vers un site tiers (redirection ouverte).
        if (is_string($cible)
            && str_starts_with($cible, Admin::base() . '/')
            && !str_starts_with($cible, '//')
        ) {
            return $cible;
        }

        return Admin::url('/');
    }

    private static function cheminCourant(): string
    {
        $chemin = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        return is_string($chemin) && $chemin !== '' ? $chemin : Admin::url('/');
    }
}
