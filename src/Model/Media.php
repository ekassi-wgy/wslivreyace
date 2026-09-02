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

    /**
     * Ce qu'une page publique a le droit de voir.
     *
     * Le statut fait foi. Une archive en brouillon n'existe pas pour la
     * galerie, y compris quand on connaît l'adresse de son fichier — mais
     * `medias/` est un dossier servi par Apache : le fichier lui-même reste
     * téléchargeable par qui devine son nom. C'est assumé et ce n'est pas un
     * secret d'État : le nom porte huit caractères aléatoires, et rien
     * d'autre qu'une image n'y est déposé.
     */
    private const PUBLIQUE = "statut = 'publie'";

    /**
     * Les archives publiées, pour la galerie (CDC §4.6).
     *
     * L'ordre manuel d'abord — c'est celui que l'éditeur a posé — puis les
     * dernières arrivées. Le même que la médiathèque : une planche réordonnée
     * au back-office se retrouve telle quelle en public, sans second réglage.
     *
     * @param string|null $categorie clé de CATEGORIES ; null = toutes
     * @return array<int,array<string,mixed>>
     */
    public static function listerPubliees(?string $categorie = null, ?int $limite = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE;
        $params = [];

        if ($categorie !== null && isset(self::CATEGORIES[$categorie])) {
            $sql .= ' AND categorie = ?';
            $params[] = $categorie;
        }

        $sql .= ' ORDER BY ' . self::ORDRE;

        // Entier casté, jamais un paramètre lié : MySQL refuse un placeholder
        // dans LIMIT quand les requêtes préparées ne sont pas émulées.
        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return Database::all($sql, $params);
    }

    /**
     * Nombre d'archives publiées par catégorie, dans l'ordre de CATEGORIES.
     *
     * Sert les filtres de la galerie : seules paraissent les catégories qui
     * mènent quelque part.
     *
     * @return array<string,int>
     */
    public static function comptesPublies(): array
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

    /** Libellé d'affichage d'une catégorie ; la clé brute si elle est inconnue. */
    public static function categorie(?string $cle): string
    {
        return self::CATEGORIES[(string) $cle] ?? (string) $cle;
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
     * URL de la taille moyenne (1600 px), avec repli sur l'original.
     *
     * C'est ce que sert la visionneuse. Le repli n'est pas un cas d'erreur :
     * une image déjà plus petite que 1600 px n'a pas de dérivée moyenne, elle
     * est servie telle quelle — voir `Televersement::DERIVEES`.
     */
    public static function urlMoyen(string $fichier): string
    {
        $moyen = Televersement::relatifMoyen($fichier);

        return Televersement::chemin($moyen) !== null
            ? self::BASE . $moyen
            : self::BASE . $fichier;
    }

    /**
     * `srcset` d'une tuile de galerie : la vignette, puis la taille moyenne.
     *
     * Les deux largeurs sont **calculées et non devinées** — une tuile carrée
     * et une tuile panoramique n'ont pas la même largeur pour un même côté
     * maximal, et un `w` faux ferait choisir au navigateur le mauvais fichier.
     * La hauteur étant la dimension bornée sur un portrait, on repart des
     * proportions réelles de l'image.
     *
     * Rend une chaîne vide quand il n'y a rien à proposer : l'appelant s'en
     * tient alors à `src`.
     *
     * @param array<string,mixed> $media
     */
    public static function srcset(array $media): string
    {
        $fichier = (string) ($media['fichier'] ?? '');
        $largeur = (int) ($media['largeur'] ?? 0);
        $hauteur = (int) ($media['hauteur'] ?? 0);

        if ($fichier === '' || $largeur < 1 || $hauteur < 1) {
            return '';
        }

        $sources = [];
        $moyenPresent = false;

        foreach (Televersement::DERIVEES as $suffixe => $cote) {
            $relatif = Televersement::relatifDerivee($fichier, $suffixe);

            if (Televersement::chemin($relatif) === null) {
                continue;
            }

            $moyenPresent = $moyenPresent || $suffixe === 'moyen';

            $ratio = min($cote / $largeur, $cote / $hauteur, 1);
            $sources[(int) round($largeur * $ratio)] = self::BASE . $relatif;
        }

        /*
         * **L'original n'est proposé que s'il n'a pas de taille moyenne**, et
         * c'est une garantie et non une approximation : la dérivée moyenne
         * n'est justement pas fabriquée quand l'image tient déjà dans 1600 px
         * (voir `Televersement::fabriquerDerivees`). Son absence dit donc que
         * l'original est léger, et qu'on peut le servir.
         *
         * L'inverse — laisser l'original en dernier cran d'un `srcset` —
         * enverrait un scan de plusieurs mégaoctets à un grand écran pour
         * afficher une tuile. Une page de galerie en compte des dizaines.
         */
        if (!$moyenPresent) {
            $sources[$largeur] = self::BASE . $fichier;
        }

        if (count($sources) < 2) {
            return '';
        }

        ksort($sources);

        $rendu = [];

        foreach ($sources as $w => $url) {
            $rendu[] = $url . ' ' . $w . 'w';
        }

        return implode(', ', $rendu);
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
