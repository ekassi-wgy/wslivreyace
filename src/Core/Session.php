<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Session, ouverte avec des réglages explicites.
 *
 * Les valeurs par défaut de PHP ne conviennent pas : `use_strict_mode` est à 0,
 * ce qui laisse un attaquant imposer un identifiant de session de son choix
 * (fixation), et le cookie sort sans `HttpOnly` ni `SameSite`.
 *
 * Le back-office et le site public ouvrent deux sessions distinctes — noms et
 * chemins de cookie séparés. Le cookie d'administration n'est donc jamais
 * envoyé avec une requête vers une page publique : ni au visiteur anonyme, ni
 * à un script tiers embarqué côté public.
 */
final class Session
{
    /** Inactivité au-delà de laquelle la session est abandonnée. */
    private const INACTIVITE = 7200;      // 2 h

    /** Durée de vie absolue, quelle que soit l'activité. */
    private const DUREE_MAX = 43200;      // 12 h

    private static bool $ouverte = false;

    /**
     * Ouvre la session. `$nom` et `$chemin` isolent les périmètres :
     * ('pgyadmin', '/cmsadmin') pour le back-office, ('pgy', '/') pour le site.
     */
    public static function demarrer(string $nom, string $chemin = '/'): void
    {
        if (self::$ouverte || session_status() === PHP_SESSION_ACTIVE) {
            self::$ouverte = true;
            return;
        }

        // Refuse un identifiant de session qui n'a pas été émis par le serveur.
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');

        session_name($nom);
        session_set_cookie_params([
            'lifetime' => 0,               // cookie de session : fermé avec le navigateur
            'path'     => $chemin === '' ? '/' : $chemin,
            'httponly' => true,            // hors de portée de tout JavaScript
            'samesite' => 'Lax',           // première barrière contre le CSRF
            'secure'   => self::enHttps(),
        ]);

        session_start();
        self::$ouverte = true;
        self::verifierAge();
    }

    /**
     * Abandonne la session trop vieille ou trop longtemps inactive.
     *
     * Deux bornes plutôt qu'une : l'inactivité protège le poste laissé ouvert,
     * la durée absolue borne une session qu'un onglet maintiendrait en vie
     * indéfiniment.
     */
    private static function verifierAge(): void
    {
        $maintenant = time();
        $ouverte    = $_SESSION['_ouverte_le'] ?? null;
        $vue        = $_SESSION['_vue_le'] ?? null;

        $expiree = ($ouverte !== null && $maintenant - $ouverte > self::DUREE_MAX)
                || ($vue !== null && $maintenant - $vue > self::INACTIVITE);

        if ($expiree) {
            // Vider et faire tourner l'identifiant, plutôt que détruire puis
            // rouvrir : la seconde façon émettait deux Set-Cookie dans la même
            // réponse — la suppression, puis la nouvelle session — et le
            // drapeau ci-dessous finissait dans une session dont le client ne
            // recevait jamais le cookie. Le message d'expiration ne s'affichait
            // donc jamais. Ici, une seule session, un seul cookie.
            $_SESSION = [];
            session_regenerate_id(true);
            $_SESSION['_expiree'] = true;
            $ouverte = null;
        }

        $_SESSION['_ouverte_le'] ??= $maintenant;
        $_SESSION['_vue_le'] = $maintenant;
    }

    /**
     * Change l'identifiant de session en conservant son contenu.
     *
     * À appeler à chaque changement de niveau de privilège — connexion en
     * particulier : sans cela, un identifiant capté avant la connexion reste
     * valable après, et devient une session authentifiée.
     */
    public static function regenerer(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function get(string $cle, mixed $defaut = null): mixed
    {
        return $_SESSION[$cle] ?? $defaut;
    }

    public static function set(string $cle, mixed $valeur): void
    {
        $_SESSION[$cle] = $valeur;
    }

    public static function oublier(string $cle): void
    {
        unset($_SESSION[$cle]);
    }

    /** Message affiché une seule fois, au prochain rendu. */
    public static function message(string $type, string $texte): void
    {
        $_SESSION['_messages'][] = ['type' => $type, 'texte' => $texte];
    }

    /** @return array<int,array{type:string,texte:string}> */
    public static function messages(): array
    {
        $m = $_SESSION['_messages'] ?? [];
        unset($_SESSION['_messages']);
        return $m;
    }

    public static function detruire(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => $p['path'],
                'domain'   => $p['domain'],
                'secure'   => $p['secure'],
                'httponly' => $p['httponly'],
                'samesite' => $p['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
        self::$ouverte = false;
    }

    private static function enHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }
}
