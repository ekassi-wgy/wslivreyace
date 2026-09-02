<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Dates écrites en français.
 *
 * Table de douze entrées plutôt que `IntlDateFormatter`, `setlocale` ou
 * `strftime`, et pour la même raison qui fait que `Slug` translittère à la
 * main : ces trois-là dépendent de la machine. `ext-intl` n'est pas garantie
 * sur un hébergement mutualisé, `setlocale` exige que la locale `fr_FR` soit
 * installée sur le système — elle ne l'est pas sur un conteneur minimal — et
 * `strftime` est dépréciée depuis PHP 8.1. Une date affichée dans la mauvaise
 * langue, ou en anglais parce qu'une locale manquait, se remarque tout de
 * suite sur un site éditorial.
 *
 * Les entrées viennent de MySQL, en `AAAA-MM-JJ` ou `AAAA-MM-JJ HH:MM:SS`.
 * Une valeur vide ou illisible rend une chaîne vide : c'est à l'appelant de
 * décider ce qu'il affiche à la place, jamais à un formateur d'inventer.
 */
final class DateFr
{
    private const MOIS_COURTS = [
        1 => 'janv.', 2 => 'févr.', 3 => 'mars',  4 => 'avr.',
        5 => 'mai',   6 => 'juin',  7 => 'juil.', 8 => 'août',
        9 => 'sept.', 10 => 'oct.', 11 => 'nov.', 12 => 'déc.',
    ];

    private const MOIS = [
        1 => 'janvier',  2 => 'février', 3 => 'mars',      4 => 'avril',
        5 => 'mai',      6 => 'juin',    7 => 'juillet',   8 => 'août',
        9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
    ];

    /**
     * « 12 mars 2026 », et « 1er mars 2026 » au premier du mois.
     *
     * L'ordinal du premier n'est pas une coquetterie : « 1 mars » ne s'écrit
     * pas en français, et c'est le genre de détail qui trahit un gabarit.
     */
    public static function longue(?string $date): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        $jour = (int) $d->format('j');

        return sprintf(
            '%s %s %s',
            $jour === 1 ? '1<sup>er</sup>' : (string) $jour,
            self::MOIS[(int) $d->format('n')],
            $d->format('Y')
        );
    }

    /**
     * Comme `longue()`, mais sans balise : pour un attribut, un titre de
     * document ou une description, où un `<sup>` s'afficherait tel quel.
     */
    public static function longueTexte(?string $date): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        $jour = (int) $d->format('j');

        return sprintf(
            '%s %s %s',
            $jour === 1 ? '1er' : (string) $jour,
            self::MOIS[(int) $d->format('n')],
            $d->format('Y')
        );
    }

    /**
     * « 18 h 30 », et « 18 h » à l'heure juste.
     *
     * L'usage français sépare l'heure des minutes par la lettre h entourée
     * d'espaces, et non par un deux-points : « 18h30 » est de l'anglais mal
     * traduit. Les espaces sont insécables — une heure coupée en fin de ligne
     * se relit deux fois.
     */
    public static function heure(?string $date): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        $minutes = (int) $d->format('i');

        return $minutes === 0
            ? $d->format('G') . "\u{A0}h"
            : $d->format('G') . "\u{A0}h\u{A0}" . $d->format('i');
    }

    /**
     * Intervalle lisible entre deux horodatages.
     *
     * Un agenda n'écrit pas « du 14 mars 2026 au 14 mars 2026 » : la langue a
     * une forme pour chaque cas, et les employer est ce qui sépare un agenda
     * d'un tableau de base de données. Cinq cas, du plus fréquent au plus rare :
     *
     *   - sans fin ................. « 14 mars 2026 à 18 h 30 »
     *   - même jour ................ « 14 mars 2026, de 18 h 30 à 21 h »
     *   - même mois ................ « du 14 au 16 mars 2026 »
     *   - même année ............... « du 28 février au 3 mars 2026 »
     *   - à cheval sur deux ans .... « du 28 décembre 2025 au 3 janvier 2026 »
     *
     * **Minuit vaut « heure non précisée » et ne s'affiche pas.** Le champ de
     * saisie impose une heure ; celui qui n'en connaît pas encore laisse
     * 00:00, et « à 0 h » serait une information fausse plutôt qu'absente.
     */
    public static function intervalle(?string $debut, ?string $fin): string
    {
        $d = self::lire($debut);

        if ($d === null) {
            return '';
        }

        $f = self::lire($fin);
        $heureDebut = self::heure($debut);
        $aMinuit    = $d->format('H:i') === '00:00';

        if ($f === null || $f->format('Y-m-d H:i') === $d->format('Y-m-d H:i')) {
            return self::longue($debut) . ($aMinuit ? '' : ' à ' . $heureDebut);
        }

        // Même jour : une seule date, deux heures.
        if ($d->format('Y-m-d') === $f->format('Y-m-d')) {
            return $aMinuit
                ? self::longue($debut)
                : self::longue($debut) . ', de ' . $heureDebut . ' à ' . self::heure($fin);
        }

        $memeMois  = $d->format('Y-m') === $f->format('Y-m');
        $memeAnnee = $d->format('Y') === $f->format('Y');

        $depuis = self::jour($d)
            . ($memeMois ? '' : ' ' . self::MOIS[(int) $d->format('n')])
            . ($memeAnnee ? '' : ' ' . $d->format('Y'));

        return 'du ' . $depuis . ' au ' . self::longue($fin);
    }

    /** Le quantième seul, « 1er » compris. */
    private static function jour(\DateTimeImmutable $d): string
    {
        $jour = (int) $d->format('j');

        return $jour === 1 ? '1<sup>er</sup>' : (string) $jour;
    }

    /** Forme machine, pour l'attribut `datetime` d'un `<time>`. */
    public static function iso(?string $date): string
    {
        $d = self::lire($date);

        return $d === null ? '' : $d->format('Y-m-d');
    }

    /**
     * Le mois abrégé, pour un cartouche d'agenda : « janv. », « déc. ».
     *
     * Les abréviations françaises ne sont pas des troncatures à trois lettres :
     * mars, mai, juin et août s'écrivent en entier — ils sont déjà courts, et
     * « aoû. » ne s'écrit pas —, les autres prennent un point abréviatif.
     */
    public static function moisCourt(?string $date): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        return self::MOIS_COURTS[(int) $d->format('n')];
    }

    /** Forme machine avec l'heure : `datetime` d'un événement horodaté. */
    public static function isoHeure(?string $date): string
    {
        $d = self::lire($date);

        return $d === null ? '' : $d->format('Y-m-d\\TH:i');
    }

    /** L'année seule, pour les regroupements. */
    public static function annee(?string $date): string
    {
        $d = self::lire($date);

        return $d === null ? '' : $d->format('Y');
    }

    /**
     * Lecture tolérante : la colonne peut être nulle sur un brouillon, et une
     * valeur fautive ne doit pas casser une page publique.
     */
    private static function lire(?string $date): ?\DateTimeImmutable
    {
        $date = trim((string) $date);

        if ($date === '' || str_starts_with($date, '0000-00-00')) {
            return null;
        }

        try {
            return new \DateTimeImmutable($date);
        } catch (\Exception) {
            return null;
        }
    }
}
