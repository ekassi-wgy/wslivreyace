<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Connexion PDO unique, ouverte à la première utilisation.
 *
 * Les options sont explicites plutôt que laissées par défaut : sans
 * ERRMODE_EXCEPTION une requête fautive échoue en silence, et sans
 * EMULATE_PREPARES à false les requêtes préparées restent émulées côté PHP,
 * ce qui affaiblit la protection contre l'injection.
 */
final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $c = Config::get('db');
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            // Le port est le seul reglage qui a un defaut raisonnable : la
            // plupart des hebergements ecoutent sur 3306, MAMP sur 8889. Les
            // trois autres n'en ont aucun — bootstrap.php les exige.
            $c['host'], (int) ($c['port'] ?? 3306), $c['name'], $c['charset']
        );

        try {
            self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ]);
        } catch (PDOException $e) {
            // Le message natif contient les identifiants : ne jamais le propager.
            throw new RuntimeException('Connexion à la base impossible.', 0, $e);
        }

        return self::$pdo;
    }

    /** @return array<int,array<string,mixed>> */
    public static function all(string $sql, array $params = []): array
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public static function one(string $sql, array $params = []): ?array
    {
        $st = self::pdo()->prepare($sql);
        $st->execute($params);
        $row = $st->fetch();
        return $row === false ? null : $row;
    }
}
