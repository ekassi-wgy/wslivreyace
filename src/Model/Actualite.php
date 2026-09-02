<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Actualités et revue de presse (CDC §4.7).
 */
final class Actualite extends Modele
{
    protected const TABLE = 'actualite';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'categorie', 'chapo', 'contenu',
        'image', 'source', 'source_url', 'statut', 'publie_le',
    ];

    /**
     * Les plus récentes d'abord. `publie_le` peut être nulle sur un brouillon :
     * sans le repli sur `cree_le`, tous les brouillons se rangeraient en bloc à
     * une extrémité de la liste au lieu de rester près de leur date de saisie.
     */
    protected const ORDRE = 'COALESCE(publie_le, DATE(cree_le)) DESC, id DESC';

    public const CATEGORIES = [
        'parution'  => 'Parution',
        'dedicace'  => 'Dédicace',
        'presse'    => 'Presse',
        'hommage'   => 'Hommage',
        'evenement' => 'Événement',
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /**
     * Ce qu'une page publique a le droit de voir.
     *
     * Le statut fait foi, et lui seul : une entrée en brouillon n'existe pas
     * pour le site, y compris quand son adresse est connue — un slug se devine.
     *
     * La date nulle est écartée en plus du statut. L'écran de publication la
     * refuse déjà (voir `Admin\ActualiteController::valider`), mais une ligne saisie
     * avant cette règle, ou modifiée en SQL, passerait au travers : sans date,
     * l'entrée se rangerait n'importe où dans un classement qui est justement
     * chronologique.
     */
    private const PUBLIQUE = "statut = 'publie' AND publie_le IS NOT NULL";

    /** Classement des pages publiques : la date d'abord, l'entrée récente ensuite. */
    private const ORDRE_PUBLIC = 'publie_le DESC, id DESC';

    /**
     * Colonnes servies aux listes. Le corps du texte n'y est pas : c'est un
     * MEDIUMTEXT, et une liste de trente entrées n'en affiche pas une ligne.
     * Le chapô suffit à l'aperçu ; le corps se lit sur la fiche.
     */
    private const COLONNES_LISTE = 'id, titre, slug, categorie, chapo, image, source, source_url, publie_le';

    /**
     * Les actualités publiées, pour le site.
     *
     * Une date à venir ne masque pas l'entrée : ce qui met en ligne est le
     * statut, pas le calendrier. Le site n'a pas de publication différée, et
     * en simuler une ici surprendrait l'éditeur qui a daté son article de
     * demain — il le verrait disparaître sans qu'aucun écran ne le lui dise.
     *
     * @param string|null $categorie clé de CATEGORIES ; null = toutes
     * @return array<int,array<string,mixed>>
     */
    public static function listerPubliees(?string $categorie = null, ?int $limite = null): array
    {
        $sql = 'SELECT ' . self::COLONNES_LISTE . ' FROM ' . self::TABLE
             . ' WHERE ' . self::PUBLIQUE;
        $params = [];

        // Comparée à la liste blanche : une valeur inconnue ne part pas en
        // requête, même liée.
        if ($categorie !== null && isset(self::CATEGORIES[$categorie])) {
            $sql .= ' AND categorie = ?';
            $params[] = $categorie;
        }

        $sql .= ' ORDER BY ' . self::ORDRE_PUBLIC;

        // Entier casté, jamais un paramètre lié : MySQL refuse un placeholder
        // dans LIMIT quand les requêtes préparées ne sont pas émulées.
        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return Database::all($sql, $params);
    }

    /**
     * Une actualité par son slug, à condition qu'elle soit publiée.
     *
     * Rend `null` dans tous les autres cas — inconnue comme en brouillon — et
     * c'est voulu : le contrôleur répond 404 sans distinguer les deux. Dire
     * « cette page existe mais n'est pas publiée » renseignerait sur le
     * contenu du back-office.
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

    /**
     * Nombre d'entrées publiées par catégorie.
     *
     * Sert à n'afficher que les filtres qui mènent quelque part : un onglet
     * « Hommage » qui donne sur une liste vide est un lien mort.
     *
     * @return array<string,int> clés de CATEGORIES effectivement représentées
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

        // L'ordre est celui de CATEGORIES, pas celui que rend MySQL : les
        // filtres doivent garder la même suite d'une page à l'autre.
        $n = [];

        foreach (array_keys(self::CATEGORIES) as $cle) {
            if (($brut[$cle] ?? 0) > 0) {
                $n[$cle] = $brut[$cle];
            }
        }

        return $n;
    }

    /**
     * Les autres actualités publiées, pour le pied d'une fiche.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function autresQue(int $id, int $limite = 3): array
    {
        return Database::all(
            'SELECT ' . self::COLONNES_LISTE . ' FROM ' . self::TABLE
            . ' WHERE ' . self::PUBLIQUE . ' AND id <> ?'
            . ' ORDER BY ' . self::ORDRE_PUBLIC . ' LIMIT ' . max(1, $limite),
            [$id]
        );
    }

    /** Libellé d'affichage d'une catégorie ; la clé brute si elle est inconnue. */
    public static function categorie(?string $cle): string
    {
        return self::CATEGORIES[(string) $cle] ?? (string) $cle;
    }
}
