<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Les citations du site public (lot G14).
 *
 * Trois blocs tiraient leur texte du lexique — `accueil.extrait.*`,
 * `livre.extrait.*`, `biographie.citations.*` — et aucun n'avait jamais reçu
 * de contenu : les trois affichaient en ligne leur propre consigne de
 * remplissage. Ils sont désormais ici.
 *
 * **Deux régimes, et il en faut deux.** `statut` dit si la citation est prête ;
 * `en_avant` dit laquelle des citations prêtes occupe son emplacement. Un
 * emplacement ne montre qu'une citation à la fois — voir `affichee()` — et la
 * mise en avant est exclusive — voir `activer()`.
 */
final class Citation extends Modele
{
    protected const TABLE = 'citation';

    protected const ASSIGNABLES = [
        'emplacement', 'texte', 'source', 'statut', 'en_avant',
    ];

    /**
     * Les emplacements d'abord, la plus récente ensuite.
     *
     * Grouper par emplacement est ce que l'éditeur vient chercher : il ouvre
     * la liste pour voir ce qui paraît où, pas pour lire un journal des
     * saisies. Le rang secondaire est décroissant — la dernière écrite en
     * premier, c'est celle sur laquelle on revient.
     */
    protected const ORDRE = 'emplacement ASC, en_avant DESC, id DESC';

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /**
     * Les trois emplacements, et le bloc de page que chacun désigne.
     *
     * Cette liste est la source de vérité : le menu déroulant de la fiche, les
     * libellés de la liste et la validation la lisent tous.
     */
    public const EMPLACEMENTS = [
        'accueil'    => 'Accueil — bandeau « Extrait »',
        'livre'      => 'Le livre — bandeau « Extrait »',
        'biographie' => 'Biographie — bandeau « Citations »',
    ];

    /**
     * Ce que la source désigne, selon l'emplacement.
     *
     * Les deux « Extrait » citent l'ouvrage : leur source est un chapitre.
     * Celui de la biographie cite Yacé : sa source est un lieu et une date.
     * La fiche s'en sert pour poser le bon libellé sous le champ.
     */
    public const AIDES_SOURCE = [
        'accueil'    => 'Le chapitre ou la page — « Une destinée, chapitre IV ».',
        'livre'      => 'Le chapitre ou la page — « Une destinée, chapitre IV ».',
        'biographie' => 'Où et quand le propos a été tenu — « Assemblée nationale, 7 août 1960 ».',
    ];

    /** Ce qu'une page publique a le droit de voir. */
    private const PUBLIQUE = "statut = 'publie' AND en_avant = 1";

    /**
     * La citation affichée à un emplacement, ou `null`.
     *
     * `null` n'est pas une anomalie : c'est l'état d'un emplacement que
     * l'éditeur n'a pas encore rempli, et le gabarit fait alors disparaître sa
     * section entière. Un bandeau vide serait pire qu'un bandeau absent.
     *
     * `LIMIT 1` malgré l'exclusivité tenue par `activer()` : une base touchée
     * à la main — un import, une reprise — pourrait porter deux mises en avant
     * pour un même emplacement, et la page publique doit rendre quelque chose
     * de déterminé plutôt que ce que MySQL voudra bien sortir.
     *
     * @return array<string,mixed>|null
     */
    public static function affichee(string $emplacement): ?array
    {
        $ligne = Database::one(
            'SELECT * FROM ' . self::TABLE
            . ' WHERE emplacement = :e AND ' . self::PUBLIQUE
            . ' ORDER BY id DESC LIMIT 1',
            ['e' => $emplacement]
        );

        return self::traduire($ligne);
    }

    /**
     * Met une citation en avant, et retire la mise en avant des autres du même
     * emplacement.
     *
     * **C'est ce qui fait de la case à cocher un bouton radio.** Sans cette
     * exclusivité, l'éditeur coche deux citations pour un même bandeau et se
     * demande laquelle le site affiche — la réponse dépendrait alors de l'ordre
     * dans lequel MySQL rend les lignes, ce qu'aucune garantie ne couvre.
     *
     * Les deux écritures ne sont pas dans une transaction : entre elles, le
     * pire état possible est un emplacement sans citation mise en avant
     * pendant quelques millisecondes — la section disparaît d'un rendu, ce qui
     * est déjà son état normal quand l'éditeur n'a rien choisi. L'ordre
     * inverse, lui, laisserait deux citations en avant, qui est l'état qu'on
     * cherche précisément à empêcher.
     */
    public static function activer(int $id, string $emplacement): void
    {
        Database::pdo()->prepare(
            'UPDATE ' . self::TABLE . ' SET en_avant = 0 WHERE emplacement = :e AND id <> :id'
        )->execute(['e' => $emplacement, 'id' => $id]);

        Database::pdo()->prepare(
            'UPDATE ' . self::TABLE . ' SET en_avant = 1 WHERE id = :id'
        )->execute(['id' => $id]);
    }

    /**
     * Les emplacements qui n'affichent rien, avec la raison.
     *
     * Sert l'avertissement en tête de la liste du back-office : un bandeau muet
     * ne se voit pas depuis le back-office, seulement en parcourant le site.
     *
     * @return array<string,string> emplacement => raison
     */
    public static function emplacementsMuets(): array
    {
        $muets = [];

        foreach (array_keys(self::EMPLACEMENTS) as $emplacement) {
            if (self::affichee($emplacement) !== null) {
                continue;
            }

            $compte = Database::one(
                'SELECT'
                . " SUM(statut = 'publie') publiees,"
                . ' SUM(en_avant = 1) cochees'
                . ' FROM ' . self::TABLE . ' WHERE emplacement = :e',
                ['e' => $emplacement]
            );

            $publiees = (int) ($compte['publiees'] ?? 0);
            $cochees  = (int) ($compte['cochees'] ?? 0);

            /*
             * Trois causes, et l'éditeur n'agit pas de la même façon selon
             * laquelle. La troisième est celle qui déroute : la case est
             * cochée, l'éditeur croit avoir agi, et le site ne montre rien
             * parce que le statut n'a pas suivi.
             */
            if ($cochees > 0) {
                $muets[$emplacement] = 'la citation mise en avant n\'est pas publiée';
            } elseif ($publiees > 0) {
                $muets[$emplacement] = 'aucune citation mise en avant';
            } else {
                $muets[$emplacement] = 'aucune citation publiée';
            }
        }

        return $muets;
    }
}
