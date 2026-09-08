<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Dates écrites pour être lues, dans la langue de la page.
 *
 * **Cette classe s'appelait `DateLisible` jusqu'au lot G11**, et le nom était juste
 * tant qu'elle n'écrivait qu'en français. Elle écrit aujourd'hui dans les deux
 * langues, et « 12 septembre 2026 » servi sous `/en/` était le genre de détail
 * qui trahit une traduction faite à moitié.
 *
 * Table de douze entrées par langue plutôt que `IntlDateFormatter`,
 * `setlocale` ou `strftime`, et pour la même raison qui fait que `Slug`
 * translittère à la main : ces trois-là dépendent de la machine. `ext-intl`
 * n'est pas garantie sur un hébergement mutualisé, `setlocale` exige que la
 * locale soit installée sur le système — elle ne l'est pas sur un conteneur
 * minimal — et `strftime` est dépréciée depuis PHP 8.1. Une date affichée dans
 * la mauvaise langue, ou en anglais parce qu'une locale manquait, se remarque
 * tout de suite sur un site éditorial.
 *
 * **Les noms de mois restent ici et non au lexique**, contrairement au reste
 * des textes du site. Ils ne se lisent jamais seuls : ils arrivent toujours
 * pris dans une règle typographique — l'ordinal du premier en français,
 * l'absence de point abréviatif en anglais, la place du quantième — et séparer
 * la table de la règle qui la gouverne aurait fait deux endroits à corriger
 * pour une même correction. Voir `Lexique` pour ce qui se traduit vraiment.
 *
 * Les entrées viennent de MySQL, en `AAAA-MM-JJ` ou `AAAA-MM-JJ HH:MM:SS`.
 * Une valeur vide ou illisible rend une chaîne vide : c'est à l'appelant de
 * décider ce qu'il affiche à la place, jamais à un formateur d'inventer.
 */
final class DateLisible
{
    /**
     * Les abréviations françaises ne sont pas des troncatures à trois lettres :
     * mars, mai, juin et août s'écrivent en entier — ils sont déjà courts, et
     * « aoû. » ne s'écrit pas —, les autres prennent un point abréviatif.
     * L'anglais britannique, lui, abrège sans point depuis longtemps.
     *
     * @var array<string,array<int,string>>
     */
    private const MOIS_COURTS = [
        'fr' => [
            1 => 'janv.', 2 => 'févr.', 3 => 'mars',  4 => 'avr.',
            5 => 'mai',   6 => 'juin',  7 => 'juil.', 8 => 'août',
            9 => 'sept.', 10 => 'oct.', 11 => 'nov.', 12 => 'déc.',
        ],
        'en' => [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar',  4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul',  8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec',
        ],
    ];

    /**
     * Les mois prennent la majuscule en anglais et la minuscule en français :
     * c'est une règle d'orthographe, pas un choix de présentation.
     *
     * @var array<string,array<int,string>>
     */
    private const MOIS = [
        'fr' => [
            1 => 'janvier',   2 => 'février',  3 => 'mars',      4 => 'avril',
            5 => 'mai',       6 => 'juin',     7 => 'juillet',   8 => 'août',
            9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre',
        ],
        'en' => [
            1 => 'January',   2 => 'February', 3 => 'March',     4 => 'April',
            5 => 'May',       6 => 'June',     7 => 'July',      8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        ],
    ];

    /** La langue de la table à employer : celle de la page, français à défaut. */
    private static function langue(): string
    {
        $code = Langue::code();

        return isset(self::MOIS[$code]) ? $code : Langue::DEFAUT;
    }

    /**
     * « 12 mars 2026 », et « 1er mars 2026 » au premier du mois.
     * En anglais : « 12 March 2026 », sans ordinal.
     *
     * L'ordinal du premier n'est pas une coquetterie : « 1 mars » ne s'écrit
     * pas en français, et c'est le genre de détail qui trahit un gabarit.
     * L'anglais britannique ne le demande pas dans une date complète.
     */
    public static function longue(?string $date): string
    {
        return self::composer($date, true);
    }

    /**
     * Comme `longue()`, mais sans balise : pour un attribut, un titre de
     * document ou une description, où un `<sup>` s'afficherait tel quel.
     */
    public static function longueTexte(?string $date): string
    {
        return self::composer($date, false);
    }

    /** Le corps commun de `longue()` et `longueTexte()`. */
    private static function composer(?string $date, bool $balise): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        $mois = self::MOIS[self::langue()][(int) $d->format('n')];

        return sprintf('%s %s %s', self::jour($d, $balise), $mois, $d->format('Y'));
    }

    /**
     * « 18 h 30 », et « 18 h » à l'heure juste. En anglais : « 6.30 pm »,
     * « 6 pm » à l'heure juste.
     *
     * L'usage français sépare l'heure des minutes par la lettre h entourée
     * d'espaces, et non par un deux-points : « 18h30 » est de l'anglais mal
     * traduit. Les espaces sont insécables — une heure coupée en fin de ligne
     * se relit deux fois. L'usage britannique compte en douze heures, sépare
     * par un point, et colle l'indicateur après une espace insécable pour la
     * même raison.
     */
    public static function heure(?string $date): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        $minutes = (int) $d->format('i');

        if (self::langue() === 'en') {
            $indicateur = (int) $d->format('G') < 12 ? 'am' : 'pm';

            return $minutes === 0
                ? $d->format('g') . "\u{A0}" . $indicateur
                : $d->format('g') . '.' . $d->format('i') . "\u{A0}" . $indicateur;
        }

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
     * L'anglais ne redouble pas la préposition — « 14–16 March 2026 » plutôt
     * que « from … to … » — et emploie le tiret demi-cadratin, qui est sa
     * ponctuation d'intervalle.
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

        $anglais    = self::langue() === 'en';
        $f          = self::lire($fin);
        $heureDebut = self::heure($debut);
        $aMinuit    = $d->format('H:i') === '00:00';

        if ($f === null || $f->format('Y-m-d H:i') === $d->format('Y-m-d H:i')) {
            if ($aMinuit) {
                return self::longue($debut);
            }

            return self::longue($debut) . ($anglais ? ' at ' : ' à ') . $heureDebut;
        }

        // Même jour : une seule date, deux heures.
        if ($d->format('Y-m-d') === $f->format('Y-m-d')) {
            if ($aMinuit) {
                return self::longue($debut);
            }

            return $anglais
                ? self::longue($debut) . ', ' . $heureDebut . ' to ' . self::heure($fin)
                : self::longue($debut) . ', de ' . $heureDebut . ' à ' . self::heure($fin);
        }

        $memeMois  = $d->format('Y-m') === $f->format('Y-m');
        $memeAnnee = $d->format('Y') === $f->format('Y');
        $mois      = self::MOIS[self::langue()][(int) $d->format('n')];

        $depuis = self::jour($d, true)
            . ($memeMois ? '' : ' ' . $mois)
            . ($memeAnnee ? '' : ' ' . $d->format('Y'));

        if (!$anglais) {
            return 'du ' . $depuis . ' au ' . self::longue($fin);
        }

        // Le tiret d'intervalle se colle entre deux bornes d'un seul mot —
        // « 14–16 March 2026 » — et prend ses espaces dès que l'une des deux
        // en contient : « 28 February – 3 March 2026 ». Coller le tiret là
        // rendrait « February–3 » illisible.
        $tiret = str_contains($depuis, ' ') ? " \u{2013} " : "\u{2013}";

        return $depuis . $tiret . self::longue($fin);
    }

    /**
     * Le quantième seul. « 1er » en français, et rien de tel en anglais où le
     * quantième d'une date complète s'écrit en chiffres nus.
     */
    private static function jour(\DateTimeImmutable $d, bool $balise): string
    {
        $jour = (int) $d->format('j');

        if (self::langue() === 'en' || $jour !== 1) {
            return (string) $jour;
        }

        return $balise ? '1<sup>er</sup>' : '1er';
    }

    /** Forme machine, pour l'attribut `datetime` d'un `<time>`. */
    public static function iso(?string $date): string
    {
        $d = self::lire($date);

        return $d === null ? '' : $d->format('Y-m-d');
    }

    /** Le mois abrégé, pour un cartouche d'agenda : « janv. », « déc. », « Jan ». */
    public static function moisCourt(?string $date): string
    {
        $d = self::lire($date);

        if ($d === null) {
            return '';
        }

        return self::MOIS_COURTS[self::langue()][(int) $d->format('n')];
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
