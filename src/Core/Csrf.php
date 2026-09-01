<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Jeton anti-CSRF.
 *
 * Un jeton par session, pas un par formulaire : deux onglets ouverts sur deux
 * formulaires différents doivent pouvoir être soumis dans n'importe quel
 * ordre. Un jeton par formulaire invaliderait le plus ancien, et l'éditeur
 * perdrait sa saisie sans comprendre pourquoi.
 *
 * Deuxième barrière, pas la première : le cookie de session sort en
 * `SameSite=Lax` (voir Session), ce qui bloque déjà la soumission POST
 * inter-site. Le jeton couvre les navigateurs qui ignoreraient l'attribut.
 */
final class Csrf
{
    private const CLE = '_csrf';

    /** Jeton de la session courante, créé au premier appel. */
    public static function jeton(): string
    {
        $jeton = Session::get(self::CLE);

        if (!is_string($jeton) || $jeton === '') {
            $jeton = bin2hex(random_bytes(32));
            Session::set(self::CLE, $jeton);
        }

        return $jeton;
    }

    /** Champ caché à poser dans chaque formulaire. */
    public static function champ(): string
    {
        return '<input type="hidden" name="_csrf" value="' . View::e(self::jeton()) . '">';
    }

    /**
     * Le jeton reçu correspond-il à celui de la session ?
     *
     * `hash_equals` et non `===` : la comparaison de chaînes ordinaire
     * s'interrompt au premier octet différent, ce qui laisse mesurer le jeton
     * octet par octet.
     */
    public static function valide(?string $recu): bool
    {
        $attendu = Session::get(self::CLE);

        return is_string($attendu)
            && $attendu !== ''
            && is_string($recu)
            && hash_equals($attendu, $recu);
    }

    /** Vérifie le jeton du POST courant, ou interrompt la requête en 419. */
    public static function exiger(): void
    {
        if (self::valide($_POST['_csrf'] ?? null)) {
            return;
        }

        // 419 n'est pas normalisé mais s'est imposé pour « jeton expiré » ;
        // il se distingue d'un 403 dû aux droits, ce qui aide au diagnostic.
        http_response_code(419);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="fr"><meta charset="utf-8">'
           . '<title>Session expirée</title>'
           . '<p>Votre session a expiré ou le formulaire a été soumis depuis un '
           . 'autre site. Revenez en arrière et réessayez.</p>';
        exit;
    }

    /**
     * Renouvelle le jeton. Appelé à la connexion, en même temps que la
     * régénération de l'identifiant de session : le jeton émis avant
     * l'authentification ne doit pas survivre à celle-ci.
     */
    public static function renouveler(): void
    {
        Session::oublier(self::CLE);
    }
}
