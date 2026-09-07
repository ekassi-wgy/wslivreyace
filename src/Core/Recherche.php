<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Actualite;
use App\Model\Archive;
use App\Model\Evenement;
use App\Model\Heritage;

/**
 * Recherche transversale (brief §9, lot G9).
 *
 * Chaque rubrique avait déjà la sienne : le fonds d'archives depuis G4, les
 * discours depuis G6. Ce qui manquait, c'est **la question qu'on pose quand on
 * ne sait pas où chercher** — « Jacqueville » se trouve dans une notice
 * d'archive, dans un lieu de mémoire et peut-être dans une actualité, et le
 * visiteur n'a pas à deviner laquelle des trois rubriques ouvrir.
 *
 * **Une requête par entité, pas une union SQL.** Les cinq tables n'ont ni les
 * mêmes colonnes, ni les mêmes conditions de publication — un événement annulé
 * reste visible, une actualité sans date ne l'est pas. Une `UNION` aurait
 * demandé de recopier ces règles ici, où elles auraient divergé de leurs
 * modèles au premier changement. Cinq requêtes indexées coûtent moins cher
 * qu'une règle de publication fausse.
 *
 * Les résultats sont normalisés : quelle que soit leur provenance, ils portent
 * un type, un titre, un chemin, une date et un extrait.
 */
final class Recherche
{
    /**
     * Ce que la recherche parcourt, dans l'ordre où les résultats paraissent.
     *
     * L'ordre n'est pas indifférent : sur un fonds patrimonial, une pièce
     * d'archive répond mieux à une recherche libre qu'un communiqué de presse.
     */
    public const TYPES = [
        'archives'    => ['libelle' => 'Archives',   'signe' => '🗂️'],
        'heritage'    => ['libelle' => 'Héritage',   'signe' => '🏛️'],
        'actualites'  => ['libelle' => 'Actualités', 'signe' => '📰'],
        'evenements'  => ['libelle' => 'Événements', 'signe' => '📅'],
    ];

    /** Longueur minimale d'une recherche. */
    public const MINIMUM = 2;

    /**
     * Cherche dans tout le site.
     *
     * @return array<string,array<int,array{titre:string,chemin:string,extrait:string,date:string}>>
     */
    public static function partout(string $terme, int $parType = 8): array
    {
        $terme = trim($terme);

        if (mb_strlen($terme) < self::MINIMUM) {
            return [];
        }

        $resultats = [
            'archives'   => self::archives($terme, $parType),
            'heritage'   => self::heritage($terme, $parType),
            'actualites' => self::actualites($terme, $parType),
            'evenements' => self::evenements($terme, $parType),
        ];

        // Les types sans résultat ne paraissent pas : un intertitre suivi de
        // vide fait croire qu'on a mal cherché.
        return array_filter($resultats, static fn(array $lot): bool => $lot !== []);
    }

    /** Nombre total de résultats, tous types confondus. */
    public static function compter(array $resultats): int
    {
        return array_sum(array_map('count', $resultats));
    }

    /**
     * Le motif `LIKE` d'un terme, ses caractères spéciaux neutralisés.
     *
     * `%` et `_` sont des jokers SQL : sans échappement, chercher « 100% »
     * rendrait tout le fonds.
     */
    private static function motif(string $terme): string
    {
        return '%' . str_replace(['%', '_'], ['\%', '\_'], $terme) . '%';
    }

    /**
     * Les notices d'archives.
     *
     * Passe par le modèle et non par une requête écrite ici : `Archive` sait
     * ce qui est publié, et sa recherche couvre déjà la transcription des
     * discours — c'est-à-dire le contenu le plus riche du site.
     */
    private static function archives(string $terme, int $limite): array
    {
        $lignes = [];

        foreach (Archive::chercher(null, null, $terme, $limite) as $a) {
            $lignes[] = [
                'titre'   => (string) $a['titre'],
                'chemin'  => Archive::chemin($a),
                'extrait' => self::extrait($terme, [
                    (string) ($a['description'] ?? ''),
                    (string) ($a['transcription'] ?? ''),
                    (string) ($a['personnes'] ?? ''),
                ]),
                'date'    => Archive::date($a),
                'contexte' => Archive::categorie((string) $a['categorie']),
            ];
        }

        return $lignes;
    }

    private static function heritage(string $terme, int $limite): array
    {
        $motif = self::motif($terme);

        $lignes = [];

        foreach (Database::all(
            "SELECT * FROM heritage WHERE statut = 'publie'"
            . ' AND (titre LIKE ? OR sous_titre LIKE ? OR description LIKE ? OR lieu LIKE ?)'
            . ' ORDER BY rubrique, ordre LIMIT ' . max(1, $limite),
            [$motif, $motif, $motif, $motif]
        ) as $h) {
            $lignes[] = [
                'titre'    => (string) $h['titre'],
                'chemin'   => Heritage::chemin($h),
                'extrait'  => self::extrait($terme, [
                    (string) ($h['sous_titre'] ?? ''),
                    (string) ($h['description'] ?? ''),
                ]),
                'date'     => Heritage::date($h),
                'contexte' => Heritage::rubrique((string) $h['rubrique']),
            ];
        }

        return $lignes;
    }

    private static function actualites(string $terme, int $limite): array
    {
        $motif = self::motif($terme);

        $lignes = [];

        foreach (Database::all(
            "SELECT * FROM actualite WHERE statut = 'publie' AND publie_le IS NOT NULL"
            . ' AND (titre LIKE ? OR chapo LIKE ? OR contenu LIKE ? OR source LIKE ?)'
            . ' ORDER BY publie_le DESC LIMIT ' . max(1, $limite),
            [$motif, $motif, $motif, $motif]
        ) as $a) {
            $lignes[] = [
                'titre'    => (string) $a['titre'],
                'chemin'   => '/actualites/' . (string) $a['slug'],
                'extrait'  => self::extrait($terme, [
                    (string) ($a['chapo'] ?? ''),
                    (string) ($a['contenu'] ?? ''),
                ]),
                'date'     => DateFr::longue((string) $a['publie_le']),
                'contexte' => Actualite::categorie((string) $a['categorie']),
            ];
        }

        return $lignes;
    }

    private static function evenements(string $terme, int $limite): array
    {
        $motif = self::motif($terme);

        $lignes = [];

        // Publiés **et annulés** : un événement annulé garde sa page, qui dit
        // qu'il est annulé. Voir `App\Model\Evenement`.
        foreach (Database::all(
            "SELECT * FROM evenement WHERE statut IN ('publie', 'annule')"
            . ' AND (titre LIKE ? OR description LIKE ? OR lieu LIKE ? OR ville LIKE ?)'
            . ' ORDER BY debut_le DESC LIMIT ' . max(1, $limite),
            [$motif, $motif, $motif, $motif]
        ) as $e) {
            $lignes[] = [
                'titre'    => (string) $e['titre'],
                'chemin'   => '/evenements/' . (string) $e['slug'],
                'extrait'  => self::extrait($terme, [(string) ($e['description'] ?? '')]),
                'date'     => DateFr::longue((string) $e['debut_le']),
                'contexte' => trim((string) ($e['ville'] ?? '')),
            ];
        }

        return $lignes;
    }

    /**
     * Un extrait de texte **autour du terme trouvé**.
     *
     * Rendre les 200 premiers signes d'une transcription de discours ne dirait
     * rien : le mot cherché est peut-être à la page cinq. L'extrait s'ouvre
     * donc là où le terme apparaît, avec ce qui l'entoure — c'est ce qui permet
     * de juger d'un résultat sans l'ouvrir.
     *
     * @param array<int,string> $sources dans l'ordre de préférence
     */
    private static function extrait(string $terme, array $sources, int $largeur = 180): string
    {
        foreach ($sources as $texte) {
            $texte = trim(preg_replace('/\s+/', ' ', $texte) ?? '');

            if ($texte === '') {
                continue;
            }

            $position = mb_stripos($texte, $terme);

            if ($position === false) {
                continue;
            }

            // Un peu avant le terme, pour qu'il ne soit pas collé au bord.
            $debut = max(0, $position - 60);
            $bout  = mb_substr($texte, $debut, $largeur);

            return ($debut > 0 ? '… ' : '') . $bout . (mb_strlen($texte) > $debut + $largeur ? ' …' : '');
        }

        // Le terme n'est dans aucun corps de texte : il vient du titre, du
        // lieu ou d'un mot-clé. Le premier texte disponible fait l'affaire.
        foreach ($sources as $texte) {
            $texte = trim(preg_replace('/\s+/', ' ', $texte) ?? '');

            if ($texte !== '') {
                return mb_strimwidth($texte, 0, $largeur, ' …');
            }
        }

        return '';
    }

    /**
     * Met en évidence le terme dans un texte **déjà échappé**.
     *
     * L'ordre compte : on échappe, puis on insère le balisage. L'inverse
     * ferait échapper le `<mark>` qu'on vient de poser, et il s'afficherait en
     * clair.
     */
    public static function surligner(string $texte, string $terme): string
    {
        $echappe = View::e($texte);
        $terme   = trim($terme);

        if ($terme === '') {
            return $echappe;
        }

        return (string) preg_replace(
            '/' . preg_quote(View::e($terme), '/') . '/iu',
            '<mark>$0</mark>',
            $echappe
        );
    }
}
