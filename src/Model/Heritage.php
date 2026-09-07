<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Héritage — ce qui perpétue la mémoire de Yacé aujourd'hui (brief §6, lot G7).
 *
 * Une table et non une page, et c'est ce qui débloque la rubrique : le cahier
 * des charges d'origine la prévoyait en page unique, et c'est pour cela qu'elle
 * est restée non écrite pendant tout le projet — une page de texte attend que
 * *tout* son texte existe. Les dix sujets du brief n'arriveront pas ensemble.
 *
 * Distincte d'`Archive`, délibérément : une notice d'archive est une **pièce**
 * du fonds, datée et cataloguée ; un lieu de mémoire existe aujourd'hui, se
 * visite, et sa photographie n'est qu'une illustration. Les mêler aurait fait
 * remonter le pont de Marcory dans les résultats du fonds documentaire.
 */
final class Heritage extends Modele
{
    protected const TABLE = 'heritage';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'rubrique', 'sous_titre', 'description',
        'lieu', 'date_texte', 'annee', 'source', 'credit',
        'video_url', 'ordre', 'statut',
    ];

    /** Back-office : par rubrique, puis dans l'ordre voulu par l'éditeur. */
    protected const ORDRE = 'rubrique ASC, ordre ASC, id ASC';

    /**
     * Les cinq rubriques, dans l'ordre où elles paraissent.
     *
     * Elles reprennent le §6 du brief en le regroupant : les quatre lieux
     * nommés — pont, boulevard, buste, Jacqueville — forment une rubrique, pas
     * quatre. « Témoignages » n'y est pas : la rubrique existe déjà et vit à
     * son adresse, l'index d'Héritage y renvoie.
     */
    public const RUBRIQUES = [
        'lieux'        => 'Lieux de mémoire',
        'hommages'     => 'Hommages et commémorations',
        'decorations'  => 'Décorations et distinctions',
        'publications' => 'Livres et publications',
        'culture'      => 'Musique et culture',
    ];

    /** Une phrase par rubrique, affichée sur l'index. */
    public const CHAPOS = [
        'lieux'        => "Un pont, un boulevard, un buste : la ville porte son nom.",
        'hommages'     => 'Ce que les institutions et les proches ont voulu retenir.',
        'decorations'  => 'Les distinctions reçues, au pays et au-dehors.',
        'publications' => "Ce qui a été écrit sur lui, et ce qu'il a écrit.",
        'culture'      => "La chanson, l'image, la scène.",
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    private const PUBLIQUE = "statut = 'publie'";

    /**
     * Les sujets publiés, par rubrique.
     *
     * **Seules paraissent les rubriques qui portent quelque chose.** C'est ce
     * qui permet d'ouvrir Héritage avant que les dix sujets ne soient réunis :
     * la page montre ce qui existe et se tait sur le reste, au lieu d'afficher
     * cinq intertitres suivis de vide.
     *
     * @return array<string,array<int,array<string,mixed>>>
     */
    public static function parRubrique(): array
    {
        $groupes = [];

        foreach (self::traduireToutes(Database::all(
            'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE
            . ' ORDER BY ' . self::ORDRE
        )) as $ligne) {
            $groupes[(string) $ligne['rubrique']][] = $ligne;
        }

        // L'ordre est celui de RUBRIQUES, pas celui que rend MySQL.
        $ordonnees = [];

        foreach (array_keys(self::RUBRIQUES) as $cle) {
            if (isset($groupes[$cle])) {
                $ordonnees[$cle] = $groupes[$cle];
            }
        }

        return $ordonnees;
    }

    /**
     * Un sujet par son slug, à condition qu'il soit publié.
     *
     * @return array<string,mixed>|null
     */
    public static function parSlug(string $slug): ?array
    {
        return self::traduire(Database::one(
            'SELECT * FROM ' . self::TABLE . ' WHERE slug = ? AND ' . self::PUBLIQUE,
            [$slug]
        ));
    }

    /**
     * Les autres sujets de la même rubrique, pour le pied d'une page.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function voisins(int $id, string $rubrique, int $limite = 4): array
    {
        return self::traduireToutes(Database::all(
            'SELECT * FROM ' . self::TABLE
            . ' WHERE ' . self::PUBLIQUE . ' AND rubrique = ? AND id <> ?'
            . ' ORDER BY ' . self::ORDRE . ' LIMIT ' . max(1, $limite),
            [$rubrique, $id]
        ));
    }

    // -- Illustrations -------------------------------------------------------

    /**
     * Les images d'un sujet, dans l'ordre posé au back-office.
     *
     * Images seules : un sujet d'héritage n'a pas de document à télécharger —
     * si un décret de décoration doit être publié, il a sa place dans le fonds
     * d'archives, où il sera catalogué et daté.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function images(int $heritageId): array
    {
        return Database::all(
            'SELECT m.* FROM media m'
            . ' JOIN heritage_media hm ON hm.media_id = m.id'
            . " WHERE hm.heritage_id = ? AND m.famille = 'image'"
            . ' ORDER BY hm.ordre ASC, m.id ASC',
            [$heritageId]
        );
    }

    /**
     * Les couvertures d'un lot de sujets, en une seule requête.
     *
     * @param array<int,array<string,mixed>> $sujets
     * @return array<int,array<string,mixed>> heritage_id => ligne de `media`
     */
    public static function couvertures(array $sujets): array
    {
        $ids = [];

        foreach ($sujets as $s) {
            if (isset($s['id'])) {
                $ids[] = (int) $s['id'];
            }
        }

        if ($ids === []) {
            return [];
        }

        $marqueurs = implode(', ', array_fill(0, count($ids), '?'));

        $lignes = Database::all(
            'SELECT hm.heritage_id, m.* FROM heritage_media hm'
            . ' JOIN media m ON m.id = hm.media_id'
            . ' JOIN ('
            . '   SELECT hm2.heritage_id, MIN(hm2.ordre) AS rang'
            . '     FROM heritage_media hm2 JOIN media m2 ON m2.id = hm2.media_id'
            . "    WHERE m2.famille = 'image' AND hm2.heritage_id IN (" . $marqueurs . ')'
            . '    GROUP BY hm2.heritage_id'
            . ' ) tete ON tete.heritage_id = hm.heritage_id AND tete.rang = hm.ordre'
            . " WHERE m.famille = 'image'"
            . ' ORDER BY hm.heritage_id, m.id ASC',
            $ids
        );

        $couvertures = [];

        foreach ($lignes as $l) {
            $couvertures[(int) $l['heritage_id']] ??= $l;
        }

        return $couvertures;
    }

    /**
     * Remplace les images d'un sujet par la liste donnée.
     *
     * @param array<int,int> $mediaIds dans l'ordre d'affichage voulu
     */
    public static function poserImages(int $heritageId, array $mediaIds): void
    {
        $pdo = Database::pdo();

        $pdo->prepare('DELETE FROM heritage_media WHERE heritage_id = ?')->execute([$heritageId]);

        if ($mediaIds === []) {
            return;
        }

        $st = $pdo->prepare(
            'INSERT INTO heritage_media (heritage_id, media_id, ordre) VALUES (?, ?, ?)'
        );

        $ordre = 0;

        foreach (array_unique($mediaIds) as $mediaId) {
            $st->execute([$heritageId, (int) $mediaId, $ordre++]);
        }
    }

    /** Identifiants des images d'un sujet, pour réafficher le formulaire. */
    public static function idsImages(int $heritageId): array
    {
        return array_map(
            static fn(array $l): int => (int) $l['media_id'],
            Database::all(
                'SELECT media_id FROM heritage_media WHERE heritage_id = ? ORDER BY ordre ASC',
                [$heritageId]
            )
        );
    }

    // -- Libellés ------------------------------------------------------------

    /** Libellé d'une rubrique ; la clé brute si elle est inconnue. */
    public static function rubrique(?string $cle): string
    {
        return self::RUBRIQUES[(string) $cle] ?? (string) $cle;
    }

    /**
     * L'adresse publique d'un sujet.
     *
     * **Plate et écrite ici seule.** Ces adresses sont destinées à l'impression
     * — plaque, QR code, dossier de presse — et la décision 3 impose qu'elles
     * ne bougent jamais. Les composer dans les gabarits aurait garanti qu'une
     * forme diverge un jour.
     *
     * @param array<string,mixed> $sujet
     */
    public static function chemin(array $sujet): string
    {
        return '/heritage/' . (string) $sujet['slug'];
    }

    /**
     * La date d'un sujet, telle qu'elle s'affiche.
     *
     * @param array<string,mixed> $sujet
     */
    public static function date(array $sujet): string
    {
        $texte = trim((string) ($sujet['date_texte'] ?? ''));

        if ($texte !== '') {
            return $texte;
        }

        $annee = (int) ($sujet['annee'] ?? 0);

        return $annee > 0 ? (string) $annee : '';
    }
}
