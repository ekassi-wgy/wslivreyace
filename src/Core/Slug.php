<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Fabrication des identifiants d'URL.
 *
 * Le site est en français : les titres portent des accents, des apostrophes et
 * des ligatures. « Séance de dédicace à Abidjan » doit donner
 * `seance-de-dedicace-a-abidjan`, pas `s-ance-de-d-dicace-abidjan`.
 */
final class Slug
{
    /**
     * Translittération des caractères que le français emploie couramment.
     *
     * `iconv('UTF-8', 'ASCII//TRANSLIT')` dépend de la locale du serveur : sur
     * une machine mal configurée il rend « ? » ou « 'e » au lieu de « e ». Une
     * table explicite donne le même résultat partout.
     */
    private const REMPLACEMENTS = [
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', 'ã' => 'a', 'å' => 'a',
        'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
        'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ñ' => 'n',
        'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o', 'õ' => 'o', 'ø' => 'o',
        'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
        'ý' => 'y', 'ÿ' => 'y',
        'æ' => 'ae', 'œ' => 'oe', 'ß' => 'ss',
        // L'apostrophe typographique autant que la droite : « l'auteur ».
        "'" => ' ', '’' => ' ', '‘' => ' ',
        '–' => '-', '—' => '-', '…' => ' ',
    ];

    public static function depuis(string $texte): string
    {
        $s = mb_strtolower(trim($texte), 'UTF-8');
        $s = strtr($s, self::REMPLACEMENTS);

        // Tout ce qui n'est ni lettre ASCII ni chiffre devient un tiret.
        $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';
        $s = trim($s, '-');

        // Un titre entièrement non latin donnerait une chaîne vide ; la clé
        // doit rester utilisable, on retombe sur une valeur neutre.
        return $s === '' ? 'sans-titre' : mb_substr($s, 0, 190);
    }

    /**
     * Décline le slug jusqu'à ce qu'il soit libre dans `$table`.
     *
     * `$exclure` est l'identifiant de la ligne en cours de modification : sans
     * lui, rééditer une fiche sans changer son titre transformerait son slug en
     * `titre-2` à chaque enregistrement, cassant les URL déjà partagées.
     */
    public static function unique(string $table, string $base, ?int $exclure = null): string
    {
        // $table ne vient jamais d'une saisie : elle est écrite dans le modèle.
        if (!preg_match('/^[a-z_]+$/', $table)) {
            throw new \InvalidArgumentException("Nom de table invalide : $table");
        }

        $slug = self::depuis($base);
        $essai = $slug;
        $n = 1;

        while (self::pris($table, $essai, $exclure)) {
            $essai = $slug . '-' . (++$n);
        }

        return $essai;
    }

    private static function pris(string $table, string $slug, ?int $exclure): bool
    {
        $sql = "SELECT id FROM $table WHERE slug = ?";
        $params = [$slug];

        if ($exclure !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $exclure;
        }

        return Database::one($sql, $params) !== null;
    }
}
