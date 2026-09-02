<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Événements : dédicaces, colloques, hommages (CDC §4.10).
 */
final class Evenement extends Modele
{
    protected const TABLE = 'evenement';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'description', 'lieu', 'ville',
        'debut_le', 'fin_le', 'image', 'inscription_url', 'statut',
    ];

    /** À venir d'abord : c'est ce qu'un éditeur vient corriger en priorité. */
    protected const ORDRE = 'debut_le DESC, id DESC';

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
        'annule'    => 'Annulé',
    ];

    /**
     * Ce qu'une page publique a le droit de voir : le publié **et l'annulé**.
     *
     * Un événement annulé n'est pas un brouillon. Il a été annoncé, des gens
     * l'ont peut-être noté dans leur agenda ; le retirer en silence les
     * laisserait se déplacer. Il reste donc affiché, et l'agenda dit qu'il est
     * annulé.
     */
    private const PUBLIQUE = "statut IN ('publie', 'annule')";

    /**
     * Fin réelle d'un événement, pour le partage entre à venir et passés.
     *
     * `fin_le` est facultative : sans elle, c'est le début qui fait foi. Une
     * dédicace d'un après-midi saisie sans heure de fin ne doit pas basculer
     * dans les archives à l'instant où elle commence — d'où le jour entier
     * accordé au repli, et non l'horodatage nu.
     */
    private const FIN = 'COALESCE(fin_le, DATE_ADD(DATE(debut_le), INTERVAL 1 DAY))';

    /**
     * Les événements à venir, du plus proche au plus lointain.
     *
     * L'ordre s'inverse par rapport à celui du back-office, et c'est le sens
     * même de la page : un agenda répond à « qu'est-ce qui arrive ensuite ? »,
     * une liste d'administration à « qu'ai-je saisi en dernier ? ».
     *
     * **La comparaison au présent se fait en SQL**, avec `NOW()`. PHP et MySQL
     * peuvent ne pas porter le même fuseau ; prendre l'heure des deux côtés
     * ferait dépendre le classement de leur écart. Une seule horloge tranche.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function aVenir(?int $limite = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE
             . ' WHERE ' . self::PUBLIQUE . ' AND ' . self::FIN . ' >= NOW()'
             . ' ORDER BY debut_le ASC, id ASC';

        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return Database::all($sql);
    }

    /**
     * Les événements passés, du plus récent au plus ancien.
     *
     * Les annulés en sont exclus : un rendez-vous qui n'a pas eu lieu n'a rien
     * à archiver, et le laisser dans la liste des passés le ferait lire comme
     * un événement qui s'est tenu.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function passes(?int $limite = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE
             . " WHERE statut = 'publie' AND " . self::FIN . ' < NOW()'
             . ' ORDER BY debut_le DESC, id DESC';

        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return Database::all($sql);
    }

    /**
     * Un événement par son slug, publié ou annulé.
     *
     * @return array<string,mixed>|null
     */
    public static function parSlug(string $slug): ?array
    {
        return Database::one(
            'SELECT * FROM ' . self::TABLE . ' WHERE slug = ? AND ' . self::PUBLIQUE,
            [$slug]
        );
    }

    /** L'événement est-il passé ? Lu sur la ligne, sans requête. */
    public static function estPasse(array $evenement): bool
    {
        $fin = trim((string) ($evenement['fin_le'] ?? ''));

        if ($fin === '') {
            // Même règle que `FIN` : le jour entier est accordé.
            $debut = trim((string) ($evenement['debut_le'] ?? ''));
            $fin = $debut === '' ? '' : substr($debut, 0, 10) . ' 23:59:59';
        }

        return $fin !== '' && strtotime($fin) < time();
    }
}
