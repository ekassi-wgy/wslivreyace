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
        'annee', 'tri', 'titre', 'notice', 'source', 'statut', 'en_avant',
    ];

    /** Ordre chronologique : une frise ne se lit pas à l'envers. */
    protected const ORDRE = 'tri ASC, id ASC';

    /*
     * **La période d'un repère ne se saisit plus** (lot G10). Quatre valeurs
     * `p1`-`p4` vivaient ici, choisies dans un menu déroulant sur la fiche du
     * repère, et l'écran vérifiait que l'année de classement tombait bien dans
     * la période retenue — deux saisies pour une seule information.
     *
     * Les périodes sont désormais en base (`App\Model\Periode`), datées et au
     * nombre que le découpage éditorial exige. Celle d'un repère se déduit de
     * son année : voir `Periode::contenant()`. Un ENUM figé à quatre valeurs
     * aurait divergé du premier découpage revu.
     */

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
     * @return array<int,array<string,mixed>>
     */
    public static function listerPubliees(?int $limite = null): array
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE
             . ' ORDER BY ' . self::ORDRE;

        // Entier casté, jamais un paramètre lié : MySQL refuse un placeholder
        // dans LIMIT quand les requêtes préparées ne sont pas émulées.
        if ($limite !== null) {
            $sql .= ' LIMIT ' . max(1, $limite);
        }

        return self::traduireToutes(Database::all($sql));
    }

    /**
     * Les repères publiés dont l'année de classement tombe dans un intervalle.
     *
     * C'est par là qu'une période de la biographie retrouve les jalons qui la
     * traversent (lot G10). Le filtre porte sur `tri` et non sur `annee` :
     * `annee` est une chaîne d'affichage — « v. 1945 », « — » — et ne se
     * compare pas.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function entreAnnees(int $debut, int $fin): array
    {
        return self::traduireToutes(Database::all(
            'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE
            . ' AND tri BETWEEN ? AND ? ORDER BY ' . self::ORDRE,
            [$debut, $fin]
        ));
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

        return self::traduireToutes(Database::all($sql));
    }
}
