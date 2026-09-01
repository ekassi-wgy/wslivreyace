<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Compteur glissant des tentatives de connexion.
 *
 * Deux plafonds distincts, parce qu'ils couvrent deux attaques différentes :
 * par adresse, on freine l'essai de mots de passe sur un compte visé ; par IP,
 * on freine le balayage d'un dictionnaire d'adresses depuis un même poste.
 *
 * Le blocage est temporaire et jamais définitif : verrouiller un compte pour
 * de bon offrirait à un tiers le moyen d'interdire l'accès à l'éditeur
 * légitime en se contentant d'échouer assez souvent.
 */
final class TentativeConnexion
{
    /** Fenêtre d'observation, en minutes. */
    private const FENETRE = 15;

    /** Échecs tolérés sur une même adresse avant blocage. */
    private const PLAFOND_IDENTIFIANT = 5;

    /** Échecs tolérés depuis une même IP, toutes adresses confondues. */
    private const PLAFOND_IP = 15;

    public static function enregistrer(string $identifiant, bool $reussie): void
    {
        Database::pdo()->prepare(
            'INSERT INTO tentative_connexion (identifiant, ip, reussie)
             VALUES (?, ?, ?)'
        )->execute([
            mb_substr(mb_strtolower(trim($identifiant)), 0, 180),
            self::ipBinaire(),
            $reussie ? 1 : 0,
        ]);
    }

    /**
     * Le prochain essai doit-il être refusé sans même être examiné ?
     *
     * Seuls les échecs comptent, et seulement ceux postérieurs à la dernière
     * réussite : une connexion réussie remet le compteur à zéro, sinon
     * l'éditeur qui s'est trompé quatre fois avant d'entrer resterait à un
     * essai du blocage pendant le quart d'heure suivant.
     */
    public static function bloque(string $identifiant): bool
    {
        return self::echecs('identifiant = ?', [mb_strtolower(trim($identifiant))]) >= self::PLAFOND_IDENTIFIANT
            || self::echecs('ip = ?', [self::ipBinaire()]) >= self::PLAFOND_IP;
    }

    /** Minutes restantes avant que le plus ancien échec ne sorte de la fenêtre. */
    public static function attenteMinutes(string $identifiant): int
    {
        $ligne = Database::one(
            'SELECT TIMESTAMPDIFF(MINUTE, MIN(tentee_le), NOW()) AS ecoule
               FROM tentative_connexion
              WHERE reussie = 0
                AND tentee_le > NOW() - INTERVAL ? MINUTE
                AND (identifiant = ? OR ip = ?)',
            [self::FENETRE, mb_strtolower(trim($identifiant)), self::ipBinaire()]
        );

        $ecoule = (int) ($ligne['ecoule'] ?? 0);
        return max(1, self::FENETRE - $ecoule);
    }

    /** @param array<int,mixed> $params */
    private static function echecs(string $condition, array $params): int
    {
        $ligne = Database::one(
            "SELECT COUNT(*) AS n
               FROM tentative_connexion
              WHERE reussie = 0
                AND $condition
                AND tentee_le > NOW() - INTERVAL " . self::FENETRE . " MINUTE
                AND tentee_le > COALESCE((
                      SELECT MAX(t2.tentee_le)
                        FROM tentative_connexion t2
                       WHERE t2.reussie = 1 AND t2.$condition
                    ), '1000-01-01')",
            [...$params, ...$params]
        );

        return (int) ($ligne['n'] ?? 0);
    }

    /**
     * Purge les lignes sorties de la fenêtre.
     *
     * Appelée à chaque tentative plutôt que par une tâche planifiée : la table
     * ne dépassera jamais quelques dizaines de lignes, et le projet n'a pas de
     * planificateur à sa disposition sur l'hébergement visé.
     */
    public static function purger(): void
    {
        Database::pdo()->exec(
            'DELETE FROM tentative_connexion
              WHERE tentee_le < NOW() - INTERVAL 1 DAY'
        );
    }

    /**
     * IP de l'appelant, en binaire.
     *
     * `REMOTE_ADDR` seul, jamais `X-Forwarded-For` : cet en-tête est fourni par
     * le client et se falsifie, ce qui permettrait de repartir de zéro à chaque
     * essai. Derrière un répartiteur de charge, il faudra le lire — mais
     * seulement après avoir vérifié que la requête vient bien de lui.
     */
    private static function ipBinaire(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        return inet_pton($ip) ?: inet_pton('0.0.0.0');
    }
}
