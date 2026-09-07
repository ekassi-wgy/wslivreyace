<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Les contenus traduits, posés par-dessus les lignes lues en base (lot G1).
 *
 * **En français, cette classe ne fait rien et n'interroge rien.** C'est sa
 * propriété la plus importante : tant que le site est monolingue — et il l'est
 * au lancement — le coût du bilinguisme est nul. Pas une jointure, pas une
 * requête, pas une ligne de plus dans le plan d'exécution.
 *
 * Le principe : les modèles continuent de rendre ce qu'ils rendaient, et la
 * traduction est **appliquée par-dessus** au moment de l'affichage. Un champ
 * non traduit garde donc sa valeur française plutôt que de disparaître : une
 * page anglaise incomplète reste lisible, ce qui vaut mieux qu'une page à
 * trous. C'est aussi ce qui permettra d'ouvrir l'anglais progressivement,
 * rubrique par rubrique, au lieu d'attendre que tout soit traduit.
 *
 * @see Langue pour le choix de la langue de la requête
 */
final class Traduction
{
    private const TABLE = 'traduction';

    /**
     * Traductions déjà lues pendant cette requête, par entité et par langue.
     *
     * Une page d'accueil affiche des actualités, des témoignages, des repères
     * et des archives : sans ce cache, chaque bloc relirait la table. Il vit le
     * temps d'une requête et pas davantage.
     *
     * @var array<string,array<int,array<string,string>>>
     */
    private static array $cache = [];

    /**
     * Applique les traductions à une ligne.
     *
     * @param string $entite nom de la table d'origine, ex. « actualite »
     * @param array<string,mixed> $ligne telle que le modèle l'a rendue
     * @return array<string,mixed>
     */
    public static function ligne(string $entite, array $ligne): array
    {
        if (Langue::estDefaut() || !isset($ligne['id'])) {
            return $ligne;
        }

        return self::appliquer($ligne, self::pour($entite, [(int) $ligne['id']]));
    }

    /**
     * Applique les traductions à une liste de lignes, en une seule requête.
     *
     * C'est la méthode à préférer : traduire une liste ligne à ligne
     * produirait autant de requêtes que d'entrées affichées.
     *
     * @param array<int,array<string,mixed>> $lignes
     * @return array<int,array<string,mixed>>
     */
    public static function lignes(string $entite, array $lignes): array
    {
        if (Langue::estDefaut() || $lignes === []) {
            return $lignes;
        }

        $ids = [];

        foreach ($lignes as $l) {
            if (isset($l['id'])) {
                $ids[] = (int) $l['id'];
            }
        }

        if ($ids === []) {
            return $lignes;
        }

        $traductions = self::pour($entite, $ids);

        foreach ($lignes as $i => $l) {
            $lignes[$i] = self::appliquer($l, $traductions);
        }

        return $lignes;
    }

    /**
     * Enregistre la traduction d'un champ. Écrit par le back-office (lot G11).
     *
     * Une valeur vide **efface** la traduction plutôt que d'enregistrer une
     * chaîne vide : sans cela, vider un champ dans l'écran de traduction
     * afficherait un blanc en anglais au lieu de retomber sur le français.
     */
    public static function poser(string $entite, int $ligneId, string $langue, string $champ, ?string $valeur): void
    {
        $valeur = $valeur === null ? '' : trim($valeur);

        if ($valeur === '') {
            Database::pdo()->prepare(
                'DELETE FROM ' . self::TABLE
                . ' WHERE entite = ? AND ligne_id = ? AND langue = ? AND champ = ?'
            )->execute([$entite, $ligneId, $langue, $champ]);

            return;
        }

        Database::pdo()->prepare(
            'INSERT INTO ' . self::TABLE . ' (entite, ligne_id, langue, champ, valeur)'
            . ' VALUES (?, ?, ?, ?, ?)'
            . ' ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)'
        )->execute([$entite, $ligneId, $langue, $champ, $valeur]);
    }

    /**
     * Efface les traductions d'une ligne supprimée.
     *
     * **À appeler à chaque suppression.** La table ne porte aucune clé
     * étrangère — elle viserait une table différente à chaque ligne, ce
     * qu'InnoDB ne sait pas faire — donc rien ne fait le ménage à notre place.
     * Sans cet appel, un identifiant réattribué par `AUTO_INCREMENT` ferait
     * ressortir la traduction d'un contenu effacé sous un contenu nouveau.
     */
    public static function oublier(string $entite, int $ligneId): void
    {
        Database::pdo()
            ->prepare('DELETE FROM ' . self::TABLE . ' WHERE entite = ? AND ligne_id = ?')
            ->execute([$entite, $ligneId]);
    }

    /**
     * Les traductions d'un jeu de lignes, dans la langue en cours.
     *
     * @param array<int,int> $ids
     * @return array<int,array<string,string>> id => [champ => valeur]
     */
    private static function pour(string $entite, array $ids): array
    {
        $langue = Langue::code();
        $cle    = $entite . '|' . $langue;
        $cache  = self::$cache[$cle] ?? [];

        // Seuls les identifiants encore inconnus partent en requête.
        $manquants = array_values(array_diff(array_unique($ids), array_keys($cache)));

        if ($manquants !== []) {
            // Autant de marqueurs que d'identifiants : ils sont entiers et
            // liés, jamais concaténés dans la requête.
            $marqueurs = implode(', ', array_fill(0, count($manquants), '?'));

            $lues = Database::all(
                'SELECT ligne_id, champ, valeur FROM ' . self::TABLE
                . ' WHERE entite = ? AND langue = ? AND ligne_id IN (' . $marqueurs . ')',
                [$entite, $langue, ...$manquants]
            );

            // Les identifiants sans traduction sont mémorisés vides : sans
            // cela, ils repartiraient en requête à chaque bloc de la page.
            foreach ($manquants as $id) {
                $cache[$id] = [];
            }

            foreach ($lues as $l) {
                $cache[(int) $l['ligne_id']][(string) $l['champ']] = (string) $l['valeur'];
            }

            self::$cache[$cle] = $cache;
        }

        return $cache;
    }

    /**
     * Recouvre les champs traduits d'une ligne.
     *
     * Seuls les champs **déjà présents** sont remplacés : une traduction
     * portant un nom de colonne disparu n'ajoute rien au tableau, et ne peut
     * donc pas faire apparaître une clé qu'un gabarit ne connaît pas.
     *
     * @param array<string,mixed> $ligne
     * @param array<int,array<string,string>> $traductions
     * @return array<string,mixed>
     */
    private static function appliquer(array $ligne, array $traductions): array
    {
        foreach ($traductions[(int) $ligne['id']] ?? [] as $champ => $valeur) {
            if (array_key_exists($champ, $ligne) && $valeur !== '') {
                $ligne[$champ] = $valeur;
            }
        }

        return $ligne;
    }
}
