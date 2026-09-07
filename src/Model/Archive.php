<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Notices d'archives — la bibliothèque numérique (brief §4, lot G4).
 *
 * **Une notice n'est pas un fichier.** C'est ce qui se catalogue, se date, se
 * situe, se crédite, se cite et se partage ; les fichiers qu'elle porte sont
 * dans `media`, reliés par `archive_media`. Un discours réunit ainsi son
 * contexte, sa vidéo, sa transcription et son document sous une seule adresse.
 * Voir `sql/011_archive.sql` pour le raisonnement complet.
 *
 * `Media` reste le magasin de fichiers, avec son téléversement contrôlé et ses
 * tailles dérivées : elle n'est pas touchée par ce lot.
 */
final class Archive extends Modele
{
    protected const TABLE = 'archive';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'categorie', 'description', 'lieu', 'personnes',
        'mots_cles', 'date_texte', 'annee', 'source', 'credit',
        'contexte', 'transcription', 'video_url', 'statut',
    ];

    /** Back-office : les dernières saisies d'abord. */
    protected const ORDRE = 'cree_le DESC, id DESC';

    /**
     * Les six catégories du brief.
     *
     * **Les clés sont les segments d'adresse** — `discours` donne
     * `/archives/discours/…`. Elles ne changeront plus : la décision 3 dit
     * qu'une adresse imprimée sur un QR code ou dans le livre ne bouge jamais.
     */
    public const CATEGORIES = [
        'photographies'   => 'Photographies',
        'videos'          => 'Vidéos',
        'discours'        => 'Discours',
        'documents'       => 'Documents',
        'presse'          => 'Presse',
        'correspondances' => 'Correspondances',
    ];

    /** Pictogramme de chaque catégorie, repris du brief. */
    public const SIGNES = [
        'photographies'   => '📷',
        'videos'          => '🎥',
        'discours'        => '🎙️',
        'documents'       => '📄',
        'presse'          => '📰',
        'correspondances' => '✉️',
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /** Le statut fait foi : une notice en brouillon n'existe pas pour le site. */
    private const PUBLIQUE = "statut = 'publie'";

    /**
     * Ordre du fonds : la plus ancienne d'abord.
     *
     * Une bibliothèque d'archives se parcourt dans le sens de l'histoire, à
     * l'inverse d'une liste d'actualités. Les pièces non datées ferment la
     * marche plutôt que de s'intercaler n'importe où — `annee` est nullable et
     * le restera, une date d'archive manque souvent.
     */
    private const ORDRE_PUBLIC = 'annee IS NULL, annee ASC, titre ASC, id ASC';

    /**
     * Le fonds public, filtré et cherché.
     *
     * Les trois filtres du brief §4 — catégorie, année, mot-clé — sont réunis
     * ici plutôt qu'en trois méthodes : ils se combinent, et trois méthodes
     * auraient obligé à en écrire une quatrième pour chaque paire.
     *
     * @param string|null $categorie clé de CATEGORIES ; null = toutes
     * @param int|null    $annee     millésime exact
     * @param string      $recherche titre, description, lieu, personnes, mots-clés
     * @return array<int,array<string,mixed>>
     */
    public static function chercher(
        ?string $categorie = null,
        ?int $annee = null,
        string $recherche = '',
        ?int $limite = null
    ): array {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE;
        $params = [];

        // Comparée à la liste blanche : une valeur inconnue ne part pas en
        // requête, même liée.
        if ($categorie !== null && isset(self::CATEGORIES[$categorie])) {
            $sql .= ' AND categorie = ?';
            $params[] = $categorie;
        }

        if ($annee !== null) {
            $sql .= ' AND annee = ?';
            $params[] = $annee;
        }

        $recherche = trim($recherche);

        if ($recherche !== '') {
            /*
             * `LIKE` et non `MATCH ... AGAINST` : un index plein texte MySQL
             * ignore les mots de moins de quatre lettres et ceux qu'il tient
             * pour trop fréquents. Sur un fonds où l'on cherche « PDCI », un
             * lieu ou un nom propre, c'est exactement ce qu'il ne faut pas.
             *
             * Le coût d'un `LIKE '%…%'` — pas d'index — est sans objet à
             * l'échelle de quelques milliers de notices. Il se reposera le
             * jour où le fonds en comptera cent mille, pas avant.
             */
            $motif = '%' . str_replace(['%', '_'], ['\%', '\_'], $recherche) . '%';
            $sql .= ' AND (titre LIKE ? OR description LIKE ? OR lieu LIKE ?'
                  . ' OR personnes LIKE ? OR mots_cles LIKE ? OR transcription LIKE ?)';
            $params = [...$params, $motif, $motif, $motif, $motif, $motif, $motif];
        }

        $sql .= ' ORDER BY ' . self::ORDRE_PUBLIC;

        // Entier casté, jamais un paramètre lié : MySQL refuse un placeholder
        // dans LIMIT quand les requêtes préparées ne sont pas émulées.
        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return self::traduireToutes(Database::all($sql, $params));
    }

    /**
     * Une notice par son slug, à condition qu'elle soit publiée.
     *
     * La catégorie est vérifiée en plus du slug : l'adresse porte les deux, et
     * `/archives/discours/mon-slug` ne doit pas répondre pour une notice
     * rangée dans « documents ». Sans ce contrôle, la même pièce aurait deux
     * adresses valides et les moteurs y verraient un doublon.
     *
     * @return array<string,mixed>|null
     */
    public static function parSlug(string $categorie, string $slug): ?array
    {
        return self::traduire(Database::one(
            'SELECT * FROM ' . self::TABLE
            . ' WHERE slug = ? AND categorie = ? AND ' . self::PUBLIQUE,
            [$slug, $categorie]
        ));
    }

    /**
     * Nombre de notices publiées par catégorie, dans l'ordre de CATEGORIES.
     *
     * Seules paraissent les catégories qui mènent quelque part — même règle
     * que les actualités et la médiathèque.
     *
     * @return array<string,int>
     */
    public static function comptesParCategorie(): array
    {
        $brut = [];

        foreach (Database::all(
            'SELECT categorie, COUNT(*) AS n FROM ' . self::TABLE
            . ' WHERE ' . self::PUBLIQUE . ' GROUP BY categorie'
        ) as $l) {
            $brut[(string) $l['categorie']] = (int) $l['n'];
        }

        $n = [];

        foreach (array_keys(self::CATEGORIES) as $cle) {
            if (($brut[$cle] ?? 0) > 0) {
                $n[$cle] = $brut[$cle];
            }
        }

        return $n;
    }

    /**
     * Les millésimes représentés dans le fonds publié, du plus ancien au plus
     * récent. Sert le filtre par année du brief §4.
     *
     * @param string|null $categorie restreint le décompte à une catégorie
     * @return array<int,int> année => nombre de notices
     */
    public static function annees(?string $categorie = null): array
    {
        $sql = 'SELECT annee, COUNT(*) AS n FROM ' . self::TABLE
             . ' WHERE ' . self::PUBLIQUE . ' AND annee IS NOT NULL';
        $params = [];

        if ($categorie !== null && isset(self::CATEGORIES[$categorie])) {
            $sql .= ' AND categorie = ?';
            $params[] = $categorie;
        }

        $annees = [];

        foreach (Database::all($sql . ' GROUP BY annee ORDER BY annee ASC', $params) as $l) {
            $annees[(int) $l['annee']] = (int) $l['n'];
        }

        return $annees;
    }

    // -- Les fichiers portés par une notice ---------------------------------

    /**
     * Les fichiers d'une notice, dans l'ordre posé au back-office.
     *
     * @return array<int,array<string,mixed>> lignes de `media`
     */
    public static function fichiers(int $archiveId): array
    {
        return Database::all(
            'SELECT m.* FROM media m'
            . ' JOIN archive_media am ON am.media_id = m.id'
            . ' WHERE am.archive_id = ?'
            . ' ORDER BY am.ordre ASC, m.id ASC',
            [$archiveId]
        );
    }

    /**
     * Le premier fichier d'une notice — sa vignette et son image de partage.
     *
     * @return array<string,mixed>|null
     */
    public static function couverture(int $archiveId): ?array
    {
        return Database::one(
            'SELECT m.* FROM media m'
            . ' JOIN archive_media am ON am.media_id = m.id'
            . ' WHERE am.archive_id = ?'
            . ' ORDER BY am.ordre ASC, m.id ASC LIMIT 1',
            [$archiveId]
        );
    }

    /**
     * Les couvertures d'un lot de notices, en une seule requête.
     *
     * Une planche de soixante notices appellerait autrement soixante fois
     * `couverture()`.
     *
     * @param array<int,array<string,mixed>> $notices
     * @return array<int,array<string,mixed>> archive_id => ligne de `media`
     */
    public static function couvertures(array $notices): array
    {
        $ids = [];

        foreach ($notices as $n) {
            if (isset($n['id'])) {
                $ids[] = (int) $n['id'];
            }
        }

        if ($ids === []) {
            return [];
        }

        $marqueurs = implode(', ', array_fill(0, count($ids), '?'));

        /*
         * Le premier fichier de chaque notice, en une passe. La sous-requête
         * choisit le rang minimal par notice ; `m.id` la départage quand deux
         * fichiers partagent le même ordre — sans quoi la couverture pourrait
         * changer d'un affichage à l'autre.
         */
        $lignes = Database::all(
            'SELECT am.archive_id, m.* FROM archive_media am'
            . ' JOIN media m ON m.id = am.media_id'
            . ' JOIN ('
            . '   SELECT archive_id, MIN(ordre) AS rang FROM archive_media'
            . '   WHERE archive_id IN (' . $marqueurs . ') GROUP BY archive_id'
            . ' ) tete ON tete.archive_id = am.archive_id AND tete.rang = am.ordre'
            . ' ORDER BY am.archive_id, m.id ASC',
            $ids
        );

        $couvertures = [];

        foreach ($lignes as $l) {
            $couvertures[(int) $l['archive_id']] ??= $l;
        }

        return $couvertures;
    }

    /**
     * Remplace les fichiers d'une notice par la liste donnée.
     *
     * Table de liaison effacée puis réécrite : c'est plus simple qu'un calcul
     * de différence, et l'ordre soumis fait autorité — deux fichiers échangés
     * dans le formulaire doivent l'être en base.
     *
     * @param array<int,int> $mediaIds dans l'ordre d'affichage voulu
     */
    public static function poserFichiers(int $archiveId, array $mediaIds): void
    {
        $pdo = Database::pdo();

        $pdo->prepare('DELETE FROM archive_media WHERE archive_id = ?')->execute([$archiveId]);

        if ($mediaIds === []) {
            return;
        }

        $st = $pdo->prepare(
            'INSERT INTO archive_media (archive_id, media_id, ordre) VALUES (?, ?, ?)'
        );

        $ordre = 0;

        // `array_unique` : un même fichier deux fois violerait la clé primaire
        // de la table de liaison, et n'aurait de toute façon aucun sens.
        foreach (array_unique($mediaIds) as $mediaId) {
            $st->execute([$archiveId, (int) $mediaId, $ordre++]);
        }
    }

    /** Identifiants des fichiers d'une notice, pour réafficher le formulaire. */
    public static function idsFichiers(int $archiveId): array
    {
        return array_map(
            static fn(array $l): int => (int) $l['media_id'],
            Database::all(
                'SELECT media_id FROM archive_media WHERE archive_id = ? ORDER BY ordre ASC',
                [$archiveId]
            )
        );
    }

    // -- Libellés ------------------------------------------------------------

    /** Libellé d'une catégorie ; la clé brute si elle est inconnue. */
    public static function categorie(?string $cle): string
    {
        return self::CATEGORIES[(string) $cle] ?? (string) $cle;
    }

    /** Pictogramme d'une catégorie ; chaîne vide si elle est inconnue. */
    public static function signe(?string $cle): string
    {
        return self::SIGNES[(string) $cle] ?? '';
    }

    /**
     * L'adresse publique d'une notice.
     *
     * **Écrite ici et nulle part ailleurs.** La décision 3 du brief impose que
     * ces adresses ne bougent jamais : les composer dans les gabarits aurait
     * garanti qu'une forme diverge un jour, et qu'une adresse imprimée cesse
     * de répondre.
     *
     * @param array<string,mixed> $notice
     */
    public static function chemin(array $notice): string
    {
        return '/archives/' . (string) $notice['categorie'] . '/' . (string) $notice['slug'];
    }

    /**
     * La date d'une notice, telle qu'elle s'affiche.
     *
     * `date_texte` d'abord — c'est la formulation de l'archiviste, « vers
     * 1965 » — l'année seule à défaut. Vide quand rien n'est su, ce qui est
     * fréquent et ne doit pas produire un tiret orphelin.
     *
     * @param array<string,mixed> $notice
     */
    public static function date(array $notice): string
    {
        $texte = trim((string) ($notice['date_texte'] ?? ''));

        if ($texte !== '') {
            return $texte;
        }

        $annee = (int) ($notice['annee'] ?? 0);

        return $annee > 0 ? (string) $annee : '';
    }

    /**
     * Les mots-clés d'une notice, découpés et nettoyés.
     *
     * @param array<string,mixed> $notice
     * @return array<int,string>
     */
    public static function motsCles(array $notice): array
    {
        $bruts = explode(',', (string) ($notice['mots_cles'] ?? ''));
        $mots = [];

        foreach ($bruts as $mot) {
            $mot = trim($mot);
            if ($mot !== '') {
                $mots[] = $mot;
            }
        }

        return $mots;
    }

    /**
     * L'identifiant d'une vidéo YouTube, extrait de son URL.
     *
     * La vidéo n'est pas hébergée ici (décision 2) : l'éditeur colle l'adresse
     * qu'il a sous la main, et les trois formes courantes se ressemblent assez
     * peu pour qu'on ne lui demande pas de les distinguer.
     *
     * Rend `null` sur tout le reste — un lien Vimeo, une faute de frappe —
     * plutôt que de fabriquer une intégration qui afficherait un cadre noir.
     */
    public static function videoYoutube(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        $motifs = [
            '#youtube\.com/watch\?(?:.*&)?v=([A-Za-z0-9_-]{11})#',
            '#youtu\.be/([A-Za-z0-9_-]{11})#',
            '#youtube\.com/embed/([A-Za-z0-9_-]{11})#',
        ];

        foreach ($motifs as $motif) {
            if (preg_match($motif, $url, $m) === 1) {
                return $m[1];
            }
        }

        return null;
    }
}
