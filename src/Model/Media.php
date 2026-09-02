<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;
use App\Core\Televersement;

/**
 * Photothèque et archives (CDC §4.6).
 *
 * Une ligne par fichier déposé. La colonne `fichier` porte un chemin relatif à
 * `medias/` — « 2026/09/portrait-a1b2c3d4.jpg » — et rien d'autre : ni URL
 * absolue, qui casserait au changement de domaine, ni chemin serveur, qui
 * casserait au déploiement.
 *
 * Le `credit` n'est pas décoratif. Une archive publiée sans mention de sa
 * source expose l'éditeur : le cahier des charges (§6) l'exige, et l'écran de
 * publication le refuse quand il manque.
 */
final class Media extends Modele
{
    protected const TABLE = 'media';

    /**
     * `fichier`, `largeur`, `hauteur` et `octets` sont écrits par le
     * téléversement, pas par un formulaire — mais ils passent par `creer()`,
     * donc ils figurent ici. L'écran de modification, lui, ne soumet aucun de
     * ces quatre champs.
     */
    protected const ASSIGNABLES = [
        'fichier', 'titre', 'legende', 'credit', 'date_prise',
        'categorie', 'largeur', 'hauteur', 'octets', 'ordre', 'statut',
    ];

    /**
     * L'ordre manuel d'abord — c'est celui de la galerie publique — puis les
     * derniers arrivés. Un dépôt récent remonte donc en tête tant que personne
     * ne lui a donné de rang.
     */
    protected const ORDRE = 'ordre ASC, cree_le DESC, id DESC';

    /** Chemin public du dossier des médias. */
    public const BASE = '/medias/';

    public const CATEGORIES = [
        'portrait' => 'Portrait',
        'officiel' => 'Officiel',
        'prive'    => 'Privé',
        'document' => 'Document',
        'presse'   => 'Presse',
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /**
     * @param string|null $categorie null = toutes
     * @return array<int,array<string,mixed>>
     */
    public static function listerPar(?string $categorie = null): array
    {
        $sql = 'SELECT * FROM media';
        $params = [];

        if ($categorie !== null) {
            $sql .= ' WHERE categorie = ?';
            $params[] = $categorie;
        }

        return Database::all($sql . ' ORDER BY ' . self::ORDRE, $params);
    }

    /** @return array<string,int> nombre par catégorie, plus 'tous' et 'publie' */
    public static function compteurs(): array
    {
        $n = array_fill_keys(array_keys(self::CATEGORIES), 0) + ['tous' => 0, 'publie' => 0];

        foreach (Database::all('SELECT categorie, statut, COUNT(*) AS n FROM media GROUP BY categorie, statut') as $l) {
            $n[$l['categorie']] = ($n[$l['categorie']] ?? 0) + (int) $l['n'];
            $n['tous'] += (int) $l['n'];
            if ($l['statut'] === 'publie') {
                $n['publie'] += (int) $l['n'];
            }
        }

        return $n;
    }

    /** @return array<string,mixed>|null */
    public static function parFichier(string $fichier): ?array
    {
        return Database::one('SELECT * FROM media WHERE fichier = ?', [$fichier]);
    }

    /**
     * Détache un fichier des fiches qui l'affichent.
     *
     * Appelé à la suppression : sans cela, l'actualité garderait le chemin
     * d'une image effacée et la page publique servirait une image cassée. La
     * colonne `image` n'est pas une clé étrangère — elle porte un chemin, pas
     * un identifiant — donc aucune contrainte ne le ferait à notre place.
     *
     * @return int nombre de fiches détachées
     */
    public static function detacher(string $fichier): int
    {
        $n = 0;

        foreach (['actualite', 'evenement'] as $table) {
            $st = Database::pdo()->prepare("UPDATE $table SET image = NULL WHERE image = ?");
            $st->execute([$fichier]);
            $n += $st->rowCount();
        }

        return $n;
    }

    /** Fiches qui affichent ce fichier, pour l'avertissement de suppression. */
    public static function usages(string $fichier): int
    {
        $n = 0;

        foreach (['actualite', 'evenement'] as $table) {
            $n += (int) (Database::one(
                "SELECT COUNT(*) AS n FROM $table WHERE image = ?",
                [$fichier]
            )['n'] ?? 0);
        }

        return $n;
    }

    /** URL publique du fichier d'origine. */
    public static function url(string $fichier): string
    {
        return self::BASE . $fichier;
    }

    /**
     * URL de la vignette, avec repli sur l'original.
     *
     * La vignette peut manquer — GD absente au moment du dépôt, fichier effacé
     * à la main. Servir l'original pèse plus lourd mais affiche quelque chose,
     * ce qui vaut mieux qu'un cadre vide dans la médiathèque.
     */
    public static function urlVignette(string $fichier): string
    {
        $vignette = Televersement::relatifVignette($fichier);

        return Televersement::chemin($vignette) !== null
            ? self::BASE . $vignette
            : self::BASE . $fichier;
    }

    /**
     * Texte de remplacement d'une image.
     *
     * La légende d'abord, le titre ensuite : c'est la légende qui décrit ce
     * qu'on voit. Jamais vide — une image d'archive sans alternative textuelle
     * est muette pour un lecteur d'écran (CDC §5).
     *
     * @param array<string,mixed> $media
     */
    public static function alternative(array $media): string
    {
        foreach (['legende', 'titre'] as $champ) {
            $v = trim((string) ($media[$champ] ?? ''));
            if ($v !== '') {
                return $v;
            }
        }

        return 'Archive photographique';
    }
}
