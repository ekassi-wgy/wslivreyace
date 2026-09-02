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

    /** Forme machine, pour l'attribut `datetime` d'un `<time>`. */
    public static function iso(?string $date): string
    {
        $d = self::lire($date);

        return $d === null ? '' : $d->format('Y-m-d');
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
