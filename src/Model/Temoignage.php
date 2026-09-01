<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Témoignages du public, avec modération (CDC §4.8).
 *
 * Rien ne s'affiche sans passage explicite en `publie` par un modérateur : il
 * s'agit de propos sur une personne réelle, déposés par un formulaire ouvert à
 * tout le monde. Le défaut du schéma est `en_attente`, et c'est délibéré.
 *
 * `auteur_email` et `ip_soumission` ne sortent jamais en public : la première
 * sert à recontacter le signataire, la seconde à repérer un abus. Aucune vue
 * publique ne les lit.
 */
final class Temoignage extends Modele
{
    protected const TABLE = 'temoignage';

    /**
     * Un modérateur ne saisit pas un témoignage, il le corrige à la marge.
     * Le statut et l'horodatage de modération passent par des méthodes
     * dédiées, qui posent aussi qui a décidé.
     */
    protected const ASSIGNABLES = ['auteur_nom', 'auteur_fonction', 'contenu'];

    /** La file de modération se lit du plus ancien au plus récent. */
    protected const ORDRE = 'soumis_le DESC, id DESC';

    public const STATUTS = [
        'en_attente' => 'En attente',
        'publie'     => 'Publié',
        'refuse'     => 'Refusé',
    ];

    /**
     * @param string|null $statut null = tous
     * @return array<int,array<string,mixed>>
     */
    public static function listerPar(?string $statut = null): array
    {
        $sql = 'SELECT t.*, u.nom AS moderateur_nom
                  FROM temoignage t
                  LEFT JOIN utilisateur u ON u.id = t.modere_par';
        $params = [];

        if ($statut !== null) {
            $sql .= ' WHERE t.statut = ?';
            $params[] = $statut;
        }

        // Les entrées en attente remontent en tête quelle que soit leur date :
        // c'est la seule pile sur laquelle le modérateur a quelque chose à faire.
        $sql .= " ORDER BY t.statut = 'en_attente' DESC, t.soumis_le DESC, t.id DESC";

        return Database::all($sql, $params);
    }

    /** @return array<string,int> nombre par statut, plus 'tous' */
    public static function compteurs(): array
    {
        $n = ['en_attente' => 0, 'publie' => 0, 'refuse' => 0, 'tous' => 0];

        foreach (Database::all('SELECT statut, COUNT(*) AS n FROM temoignage GROUP BY statut') as $l) {
            $n[$l['statut']] = (int) $l['n'];
            $n['tous'] += (int) $l['n'];
        }

        return $n;
    }

    /**
     * Enregistre une décision de modération.
     *
     * Qui a décidé et quand sont conservés : sur des propos publiés au nom
     * d'un tiers, la question « qui a validé ceci ? » doit avoir une réponse.
     */
    public static function moderer(int $id, string $statut, int $moderateurId): void
    {
        if (!isset(self::STATUTS[$statut])) {
            throw new \InvalidArgumentException("Statut inconnu : $statut");
        }

        Database::pdo()->prepare(
            'UPDATE temoignage
                SET statut = ?, modere_le = NOW(), modere_par = ?
              WHERE id = ?'
        )->execute([$statut, $moderateurId, $id]);
    }
}
