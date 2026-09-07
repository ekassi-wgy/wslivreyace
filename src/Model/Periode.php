<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Les périodes de la biographie (brief §3, lot G10).
 *
 * La page Biographie portait cinq chapitres écrits en dur dans le gabarit :
 * sans date, sans adresse, et donc sans rien à quoi rattacher le reste du
 * site. Le brief en demande douze, chacune à son adresse, illustrées et
 * reliées au fonds.
 *
 * **Une période est définie par ses bornes, et c'est ce qui la distingue d'un
 * chapitre.** Un chapitre porte un titre et du texte ; une période porte deux
 * années, et ces deux années suffisent à savoir quels repères de la frise la
 * traversent et quelles pièces d'archives ont été produites pendant qu'elle
 * durait. Rien de ce rattachement n'est saisi : il se déduit, et il suit donc
 * toute correction de découpage sans qu'on ait à repasser sur les repères ni
 * sur les notices.
 *
 * Les bornes sont **incluses des deux côtés et ne se chevauchent pas** : une
 * année appartient à une période et à une seule. Le découpage `p1`-`p4` que ce
 * lot remplace se chevauchait d'un an — 1980 fermait la troisième période et
 * ouvrait la quatrième — et une même pièce serait remontée sous deux récits.
 */
final class Periode extends Modele
{
    protected const TABLE = 'periode';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'sous_titre', 'debut', 'fin', 'recit', 'source', 'statut',
    ];

    /**
     * Ordre chronologique, les périodes non datées en fin de liste.
     *
     * Une biographie ne se lit pas à l'envers, et une période en cours de
     * rédaction n'a pas encore ses bornes : la reléguer en fin de liste vaut
     * mieux que la faire remonter en tête, où `NULL` la placerait.
     */
    protected const ORDRE = 'debut IS NULL, debut ASC, fin ASC, id ASC';

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /**
     * Ce qu'une page publique a le droit de voir.
     *
     * Les bornes s'ajoutent au statut, et ce n'est pas une redondance avec la
     * validation : une période dont on effacerait les dates en base
     * n'apparaîtrait plus sur la frise ni sur l'index, mais garderait son
     * adresse. Mieux vaut qu'elle disparaisse des deux — une période sans
     * dates ne peut ni se situer, ni porter de repères, ni ouvrir sur le fonds.
     */
    private const PUBLIQUE = "statut = 'publie' AND debut IS NOT NULL AND fin IS NOT NULL";

    /**
     * Les périodes publiées, de la plus ancienne à la plus récente.
     *
     * C'est **la** méthode de lecture publique, et elle est appelée une fois
     * par page : douze lignes tiennent en mémoire, et les voisines d'une
     * période comme le rattachement des repères s'en déduisent sans requête de
     * plus.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function listerPubliees(): array
    {
        return self::traduireToutes(Database::all(
            'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE
            . ' ORDER BY ' . self::ORDRE
        ));
    }

    /**
     * La période qui contient une année, prise dans une liste déjà chargée.
     *
     * Statique et sans requête, à dessein : la frise appelle cette méthode une
     * fois par repère affiché, et une requête par ligne de frise serait le
     * genre de coût qu'on ne voit pas venir.
     *
     * @param array<int,array<string,mixed>> $periodes rendues par listerPubliees()
     * @return array<string,mixed>|null
     */
    public static function contenant(array $periodes, ?int $annee): ?array
    {
        if ($annee === null) {
            return null;
        }

        foreach ($periodes as $p) {
            if ($annee >= (int) $p['debut'] && $annee <= (int) $p['fin']) {
                return $p;
            }
        }

        return null;
    }

    /**
     * Les périodes publiées qui chevauchent un intervalle, hors l'une d'elles.
     *
     * Sert la validation : deux périodes publiées ne peuvent pas se recouvrir,
     * sans quoi une année appartiendrait à deux récits. Le test est celui de
     * l'intersection de deux intervalles fermés — `debut <= fin_autre` et
     * `fin >= debut_autre` — et non une comparaison de bornes deux à deux, qui
     * laisse toujours passer le cas de l'englobement.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function chevauchantes(int $debut, int $fin, ?int $exclure = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE
             . ' AND debut <= ? AND fin >= ?';
        $params = [$fin, $debut];

        if ($exclure !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $exclure;
        }

        return Database::all($sql . ' ORDER BY ' . self::ORDRE, $params);
    }

    // -- Illustrations -------------------------------------------------------

    /**
     * Les images d'une période, dans l'ordre posé au back-office.
     *
     * Images seules : le récit d'une période n'a pas de document à
     * télécharger. Un décret, une lettre, un enregistrement sont des pièces du
     * fonds — ils ont leur notice, leur date et leur crédit, et la période les
     * montre par ce rattachement plutôt qu'en les portant elle-même.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function images(int $periodeId): array
    {
        return Database::all(
            'SELECT m.* FROM media m'
            . ' JOIN periode_media pm ON pm.media_id = m.id'
            . " WHERE pm.periode_id = ? AND m.famille = 'image'"
            . ' ORDER BY pm.ordre ASC, m.id ASC',
            [$periodeId]
        );
    }

    /**
     * Les couvertures d'un lot de périodes, en une seule requête.
     *
     * @param array<int,array<string,mixed>> $periodes
     * @return array<int,array<string,mixed>> periode_id => ligne de `media`
     */
    public static function couvertures(array $periodes): array
    {
        $ids = [];

        foreach ($periodes as $p) {
            if (isset($p['id'])) {
                $ids[] = (int) $p['id'];
            }
        }

        if ($ids === []) {
            return [];
        }

        $marqueurs = implode(', ', array_fill(0, count($ids), '?'));

        $lignes = Database::all(
            'SELECT pm.periode_id, m.* FROM periode_media pm'
            . ' JOIN media m ON m.id = pm.media_id'
            . ' JOIN ('
            . '   SELECT pm2.periode_id, MIN(pm2.ordre) AS rang'
            . '     FROM periode_media pm2 JOIN media m2 ON m2.id = pm2.media_id'
            . "    WHERE m2.famille = 'image' AND pm2.periode_id IN (" . $marqueurs . ')'
            . '    GROUP BY pm2.periode_id'
            . ' ) tete ON tete.periode_id = pm.periode_id AND tete.rang = pm.ordre'
            . " WHERE m.famille = 'image'"
            . ' ORDER BY pm.periode_id, m.id ASC',
            $ids
        );

        $couvertures = [];

        foreach ($lignes as $l) {
            $couvertures[(int) $l['periode_id']] ??= $l;
        }

        return $couvertures;
    }

    /**
     * Remplace les images d'une période par la liste donnée.
     *
     * @param array<int,int> $mediaIds dans l'ordre d'affichage voulu
     */
    public static function poserImages(int $periodeId, array $mediaIds): void
    {
        $pdo = Database::pdo();

        $pdo->prepare('DELETE FROM periode_media WHERE periode_id = ?')->execute([$periodeId]);

        if ($mediaIds === []) {
            return;
        }

        $st = $pdo->prepare(
            'INSERT INTO periode_media (periode_id, media_id, ordre) VALUES (?, ?, ?)'
        );

        $ordre = 0;

        foreach (array_unique($mediaIds) as $mediaId) {
            $st->execute([$periodeId, (int) $mediaId, $ordre++]);
        }
    }

    /**
     * Identifiants des images d'une période, pour réafficher le formulaire.
     *
     * @return array<int,int>
     */
    public static function idsImages(int $periodeId): array
    {
        return array_map(
            static fn(array $l): int => (int) $l['media_id'],
            Database::all(
                'SELECT media_id FROM periode_media WHERE periode_id = ? ORDER BY ordre ASC',
                [$periodeId]
            )
        );
    }

    // -- Libellés ------------------------------------------------------------

    /**
     * L'adresse publique d'une période.
     *
     * Écrite ici et nulle part ailleurs, comme celle d'une notice d'archive ou
     * d'un sujet d'Héritage : la décision 3 impose qu'une adresse publiée ne
     * bouge plus, et une forme composée dans les gabarits finit toujours par
     * diverger d'un gabarit à l'autre.
     *
     * @param array<string,mixed> $periode
     */
    public static function chemin(array $periode): string
    {
        return '/biographie/' . (string) $periode['slug'];
    }

    /**
     * Les bornes d'une période, telles qu'elles s'affichent.
     *
     * Une période d'un an ne s'écrit pas « 1959 — 1959 ». Le tiret demi-cadratin
     * entouré d'espaces est la forme qu'employaient les filtres de la frise
     * depuis le premier gabarit ; elle est reprise telle quelle.
     *
     * @param array<string,mixed> $periode
     */
    public static function annees(array $periode): string
    {
        $debut = (int) ($periode['debut'] ?? 0);
        $fin   = (int) ($periode['fin'] ?? 0);

        if ($debut === 0 && $fin === 0) {
            return '';
        }

        if ($debut === 0 || $fin === 0 || $debut === $fin) {
            return (string) ($debut !== 0 ? $debut : $fin);
        }

        return $debut . ' — ' . $fin;
    }
}
