<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Base commune aux tables du back-office.
 *
 * Les trois entités du lot C — actualités, événements, repères — partagent
 * exactement les mêmes opérations. Les écrire trois fois garantissait qu'une
 * correction n'en toucherait qu'une.
 *
 * Une règle tient tout : `$assignables` est une liste blanche de colonnes.
 * Aucune clé venue du formulaire n'entre dans une requête sans y figurer, et
 * aucune valeur ne circule autrement que par un paramètre lié. Sans cette
 * liste, un champ `role` glissé dans un POST irait se ranger en base.
 */
abstract class Modele
{
    /** Nom de la table. Écrit ici, jamais reçu. */
    protected const TABLE = '';

    /** @var array<int,string> colonnes qu'un formulaire a le droit de remplir */
    protected const ASSIGNABLES = [];

    /** Ordre par défaut des listes du back-office. */
    protected const ORDRE = 'id DESC';

    /** Nom de la table, pour ce qui en a besoin au-dehors (unicité de slug). */
    public static function table(): string
    {
        return static::TABLE;
    }

    /** @return array<int,array<string,mixed>> */
    public static function lister(): array
    {
        return Database::all('SELECT * FROM ' . static::TABLE . ' ORDER BY ' . static::ORDRE);
    }

    /** @return array<string,mixed>|null */
    public static function trouver(int $id): ?array
    {
        return Database::one('SELECT * FROM ' . static::TABLE . ' WHERE id = ?', [$id]);
    }

    /** @param array<string,mixed> $donnees */
    public static function creer(array $donnees): int
    {
        $donnees = self::filtrer($donnees);

        if ($donnees === []) {
            throw new \InvalidArgumentException('Aucune colonne assignable fournie.');
        }

        $colonnes = array_keys($donnees);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::TABLE,
            implode(', ', $colonnes),
            implode(', ', array_fill(0, count($colonnes), '?'))
        );

        $pdo = Database::pdo();
        $pdo->prepare($sql)->execute(array_values($donnees));

        return (int) $pdo->lastInsertId();
    }

    /** @param array<string,mixed> $donnees */
    public static function modifier(int $id, array $donnees): void
    {
        $donnees = self::filtrer($donnees);

        if ($donnees === []) {
            return;
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE id = ?',
            static::TABLE,
            implode(', ', array_map(static fn($c) => "$c = ?", array_keys($donnees)))
        );

        Database::pdo()->prepare($sql)->execute([...array_values($donnees), $id]);
    }

    public static function supprimer(int $id): void
    {
        Database::pdo()
            ->prepare('DELETE FROM ' . static::TABLE . ' WHERE id = ?')
            ->execute([$id]);
    }

    public static function compter(?string $statut = null): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . static::TABLE;
        $params = [];

        if ($statut !== null) {
            $sql .= ' WHERE statut = ?';
            $params[] = $statut;
        }

        $st = Database::pdo()->prepare($sql);
        $st->execute($params);

        return (int) $st->fetchColumn();
    }

    /**
     * Ne conserve que les colonnes déclarées assignables.
     *
     * @param array<string,mixed> $donnees
     * @return array<string,mixed>
     */
    private static function filtrer(array $donnees): array
    {
        return array_intersect_key($donnees, array_flip(static::ASSIGNABLES));
    }
}
