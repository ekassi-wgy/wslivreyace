<?php
declare(strict_types=1);

namespace App\Core;

/**
 * La langue de la page en cours (lot G1, README §9).
 *
 * **Ce lot pose la structure, il ne traduit rien.** L'objectif est qu'ouvrir
 * la version anglaise, le jour où la communication internationale démarrera,
 * ne demande pas de rouvrir le routeur, les modèles et les dix-huit gabarits.
 * Il n'y a donc, volontairement, aucun contenu anglais dans ce dépôt.
 *
 * Trois choix qui se prennent une fois :
 *
 * 1. **Le préfixe d'adresse** — `/en/le-livre` et non `en.philippeyace.ci` ni
 *    `?lang=en`. C'est la forme que Google recommande, elle ne demande rien au
 *    DNS ni au certificat, et elle laisse la version française à la racine :
 *    aucune adresse existante ne bouge, ce qui compte quand certaines seront
 *    imprimées (décision 3 du brief).
 * 2. **Le français n'a pas de préfixe.** `/le-livre` reste `/le-livre`. Un
 *    `/fr/` ajouté après coup aurait rendu caduque chaque adresse déjà
 *    partagée, chaque QR code, chaque lien de la page Facebook.
 * 3. **La langue vient de l'adresse, jamais de l'en-tête du navigateur.**
 *    Rediriger un visiteur d'après `Accept-Language` donne deux contenus à une
 *    même adresse : le moteur en indexe un et l'utilisateur en voit l'autre.
 *
 * @see Traduction pour la lecture des contenus traduits
 */
final class Langue
{
    /**
     * Les langues du site, par code ISO 639-1.
     *
     * `active` dit si la langue est ouverte au public. **L'anglais est déclaré
     * mais fermé** : ses adresses répondent 404 tant que rien n'est traduit.
     * Servir des pages françaises sous `/en/` apprendrait aux moteurs que le
     * site ment sur son contenu, et c'est long à défaire.
     *
     * Ouvrir l'anglais tiendra en un mot : `'active' => true`.
     *
     * @var array<string,array{nom:string,locale:string,active:bool}>
     */
    public const LANGUES = [
        'fr' => ['nom' => 'Français', 'locale' => 'fr_FR', 'active' => true],
        'en' => ['nom' => 'English',  'locale' => 'en_GB', 'active' => false],
    ];

    /**
     * La langue servie sans préfixe, et le repli de tout ce qui n'est pas
     * traduit. Elle ne peut pas être fermée — le site n'aurait plus de contenu.
     */
    public const DEFAUT = 'fr';

    private static ?string $courante = null;

    /** Code de la langue en cours. `Langue::code()` vaut « fr » ou « en ». */
    public static function code(): string
    {
        return self::$courante ?? self::DEFAUT;
    }

    /** Vrai quand la page est servie dans la langue par défaut. */
    public static function estDefaut(): bool
    {
        return self::code() === self::DEFAUT;
    }

    /**
     * Fixe la langue de la requête. Appelé par le routeur, et par lui seul.
     *
     * Une langue inconnue ou fermée est refusée plutôt que ramenée au
     * français : c'est le routeur qui décide alors de la 404, et il doit
     * pouvoir la distinguer d'un chemin fautif.
     */
    public static function fixer(string $code): bool
    {
        if (!self::ouverte($code)) {
            return false;
        }

        self::$courante = $code;

        return true;
    }

    /** Une langue déclarée **et** ouverte au public. */
    public static function ouverte(string $code): bool
    {
        return (self::LANGUES[$code]['active'] ?? false) === true;
    }

    /**
     * Les langues ouvertes, dans l'ordre de déclaration.
     *
     * @return array<string,array{nom:string,locale:string,active:bool}>
     */
    public static function ouvertes(): array
    {
        return array_filter(self::LANGUES, static fn(array $l): bool => $l['active']);
    }

    /** Le site est-il réellement multilingue aujourd'hui ? */
    public static function multilingue(): bool
    {
        return count(self::ouvertes()) > 1;
    }

    /** Étiquette de langue pour `<html lang>` et `hreflang`. */
    public static function etiquette(?string $code = null): string
    {
        return $code ?? self::code();
    }

    /** Locale complète, pour `og:locale`. Ex. : « fr_FR ». */
    public static function locale(?string $code = null): string
    {
        $code ??= self::code();

        return self::LANGUES[$code]['locale'] ?? 'fr_FR';
    }

    /**
     * Le préfixe d'adresse d'une langue, barre de tête comprise, vide pour le
     * français. `prefixe('en')` vaut « /en ».
     */
    public static function prefixe(?string $code = null): string
    {
        $code ??= self::code();

        return $code === self::DEFAUT ? '' : '/' . $code;
    }

    /**
     * Un chemin interne, préfixé pour la langue demandée.
     *
     * `chemin('/le-livre', 'en')` rend « /en/le-livre » ; en français, le
     * chemin ressort inchangé. C'est le point de passage unique : un gabarit
     * qui écrit `href="/le-livre"` en dur restera français une fois l'anglais
     * ouvert.
     */
    public static function chemin(string $chemin, ?string $code = null): string
    {
        $chemin = '/' . ltrim($chemin, '/');
        $prefixe = self::prefixe($code);

        // La racine ne devient pas « /en/ » mais « /en » : une barre finale
        // ferait deux adresses pour une même page.
        if ($chemin === '/') {
            return $prefixe === '' ? '/' : $prefixe;
        }

        return $prefixe . $chemin;
    }

    /**
     * Le chemin de la requête en cours, dépouillé de son préfixe de langue.
     *
     * Sert à composer les liens `hreflang` : la même page, dans chaque langue
     * ouverte. Posé par le routeur en même temps que la langue.
     */
    private static string $cheminNu = '/';

    public static function poserCheminNu(string $chemin): void
    {
        self::$cheminNu = $chemin;
    }

    public static function cheminNu(): string
    {
        return self::$cheminNu;
    }

    /**
     * Les autres versions de la page en cours, pour `hreflang`.
     *
     * Vide tant qu'une seule langue est ouverte : annoncer une alternative qui
     * répond 404 est pire que ne rien annoncer.
     *
     * @return array<string,string> code de langue => URL absolue
     */
    public static function alternatives(): array
    {
        if (!self::multilingue()) {
            return [];
        }

        $urls = [];

        foreach (array_keys(self::ouvertes()) as $code) {
            $urls[$code] = Site::base() . self::chemin(self::cheminNu(), $code);
        }

        return $urls;
    }
}
