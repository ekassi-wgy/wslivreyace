<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Repères chronologiques de la biographie (CDC §4.4).
 *
 * `annee` est une chaîne et non un entier : une date d'archive est souvent
 * imprécise — « v. 1945 », « 1959-1960 ». `tri` porte l'année numérique qui
 * sert au classement, et c'est pour cela que les deux coexistent.
 */
final class Repere extends Modele
{
    protected const TABLE = 'repere';

    protected const ASSIGNABLES = [
        'annee', 'tri', 'periode', 'titre', 'notice', 'source', 'statut',
        'en_avant',
    ];

    /** Ordre chronologique : une frise ne se lit pas à l'envers. */
    protected const ORDRE = 'tri ASC, id ASC';

    /**
     * Les bornes reprennent exactement les filtres de la frise publique
     * (templates/pages/biographie.php). Les faire diverger casserait le
     * filtrage sans que rien ne le signale.
     */
    public const PERIODES = [
        'p1' => '1920 — 1944',
        'p2' => '1945 — 1958',
        'p3' => '1959 — 1980',
        'p4' => '1980 — 1998',
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /**
     * Ce qu'une page publique a le droit de voir. Le statut fait foi, comme
     * partout ailleurs : un repère en brouillon n'existe pas pour la frise.
     */
    private const PUBLIQUE = "statut = 'publie'";

    /**
     * Les repères publiés, dans l'ordre chronologique.
     *
     * **Cette méthode manquait, et c'est tout le sujet du lot G0.** La table et
     * son écran d'administration existent depuis le lot C, mais les deux frises
     * publiques — accueil et biographie — portaient leurs dates en dur dans le
     * gabarit. Ce qu'un éditeur saisissait n'arrivait donc nulle part. Voir
     * README §9.
     *
     * @param string|null $periode clé de PERIODES ; null = toutes
     * @return array<int,array<string,mixed>>
     */
    public static function listerPubliees(?string $periode = null, ?int $limite = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE;
        $params = [];

        // Comparée à la liste blanche : une valeur inconnue ne part pas en
        // requête, même liée.
        if ($periode !== null && isset(self::PERIODES[$periode])) {
            $sql .= ' AND periode = ?';
            $params[] = $periode;
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
     * Les périodes qui portent au moins un repère publié, dans l'ordre de
     * PERIODES.
     *
     * Sert les filtres de la frise : un onglet « 1945 — 1958 » qui donne sur
     * une frise vide est un lien mort. Même règle que les catégories
     * d'actualités et d'archives — seuls paraissent les filtres qui mènent
     * quelque part.
     *
     * @return array<string,int> clé de période => nombre de repères
     */
    public static function periodesPubliees(): array
    {
        $brut = [];

        foreach (Database::all(
            'SELECT periode, COUNT(*) AS n FROM ' . self::TABLE
            . ' WHERE ' . self::PUBLIQUE . ' GROUP BY periode'
        ) as $l) {
            $brut[(string) $l['periode']] = (int) $l['n'];
        }

        $n = [];

        foreach (array_keys(self::PERIODES) as $cle) {
            if (($brut[$cle] ?? 0) > 0) {
                $n[$cle] = $brut[$cle];
            }
        }

        return $n;
    }

    /**
     * Les repères mis en avant, pour l'accueil.
     *
     * L'accueil n'a de place que pour quelques jalons, et **c'est l'éditeur
     * qui choisit lesquels** — une case sur la fiche du repère, pas une règle
     * devinée par le code. Prendre les premiers de la frise remonterait les
     * notices encore à documenter ; prendre les mieux datés supposerait qu'une
     * date établie fasse un jalon marquant, ce qui n'est pas la même chose.
     *
     * La mise en avant seule ne publie rien : un repère en brouillon coché
     * n'apparaît nulle part, ici comme sur la biographie.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function listerEnAvant(?int $limite = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE
             . ' WHERE ' . self::PUBLIQUE . ' AND en_avant = 1'
             . ' ORDER BY ' . self::ORDRE;

        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return Database::all($sql);
    }

    /** Libellé d'affichage d'une période ; la clé brute si elle est inconnue. */
    public static function periode(?string $cle): string
    {
        return self::PERIODES[(string) $cle] ?? (string) $cle;
    }
}
