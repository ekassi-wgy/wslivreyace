<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Les textes de l'interface, sortis des gabarits (lot G11).
 *
 * **Deux choses se traduisent sur ce site, et elles n'ont pas le même régime.**
 * Les *contenus* — une notice d'archive, un récit de période, une actualité —
 * sont saisis par l'éditeur et traduits depuis le back-office : ils passent par
 * `Traduction`, qui les recouvre au moment de l'affichage. Les *textes
 * d'interface* — « Lire la suite », « Retour aux archives », « Ouvrir le
 * menu » — n'appartiennent à personne d'autre qu'au site lui-même : ils vivent
 * ici, dans le dépôt, versionnés avec le code qui les affiche.
 *
 * Les mélanger aurait été une faute des deux côtés : demander à un éditeur de
 * traduire « Fermer » depuis un écran d'administration, ou livrer une mise à
 * jour de code pour corriger une notice.
 *
 * **Le catalogue français fait foi.** Une clé absente du français est un défaut
 * de développement, et `t()` rend alors la clé elle-même — visible à l'écran,
 * donc corrigée. Une clé absente de l'anglais retombe sur le français : une
 * page anglaise incomplète reste lisible, exactement comme pour les contenus.
 *
 * @see Traduction pour les contenus saisis au back-office
 * @see Langue pour le choix de la langue de la requête
 */
final class Lexique
{
    /**
     * Catalogues déjà chargés, par code de langue.
     *
     * Le fichier n'est lu qu'une fois par requête, et seulement si une clé est
     * demandée : en français comme en anglais, une page qui n'appelle pas `t()`
     * ne lit aucun fichier.
     *
     * @var array<string,array<string,string>>
     */
    private static array $catalogues = [];

    /**
     * Un texte d'interface, échappé et prêt à écrire dans la page.
     *
     * **Il échappe**, et c'est délibéré : ces chaînes finissent presque toutes
     * dans du HTML — texte de bouton, `aria-label`, `title` — et un helper qui
     * n'échappe pas oblige à écrire `View::e(t(...))` quatre cents fois, ce
     * qu'on finit par oublier une fois. Pour le cas rare d'un texte portant du
     * balisage, voir `brut()`.
     *
     * Les valeurs à insérer se nomment `:quelque_chose` dans le catalogue et
     * sont échappées elles aussi :
     *
     *     t('archives.compte', ['nombre' => $n])
     *
     * @param array<string,string|int> $valeurs
     */
    public static function t(string $cle, array $valeurs = []): string
    {
        return self::remplacer(View::e(self::lire($cle)), $valeurs, true);
    }

    /**
     * Un texte d'interface **non échappé**, pour les rares chaînes qui portent
     * du balisage — une mise en exergue au milieu d'une phrase, dont le
     * découpage en trois morceaux rendrait la traduction impossible.
     *
     * Le texte vient du dépôt, jamais d'un utilisateur ; les valeurs insérées,
     * elles, restent échappées.
     *
     * @param array<string,string|int> $valeurs
     */
    public static function brut(string $cle, array $valeurs = []): string
    {
        return self::remplacer(self::lire($cle), $valeurs, true);
    }

    /**
     * Un texte d'interface **entièrement nu**, pour un contexte qui échappera
     * plus loin : le titre de page et la description de partage, que
     * `templates/layout.php` écrit lui-même dans des attributs et échappe donc
     * une fois. Passé par `t()`, « l'État » y ressortait en
     * `l&amp;#039;État` — échappé deux fois.
     *
     * Les valeurs insérées ne sont pas échappées non plus, pour la même
     * raison : elles subiront le même traitement en aval. **Ne pas l'employer
     * dans un gabarit qui écrit directement dans la page** — c'est ce que
     * `t()` et `brut()` sont là pour faire.
     *
     * @param array<string,string|int> $valeurs
     */
    public static function nu(string $cle, array $valeurs = []): string
    {
        return self::remplacer(self::lire($cle), $valeurs, false);
    }

    /** La clé existe-t-elle au catalogue français ? Sert aux essais. */
    public static function existe(string $cle): bool
    {
        return isset(self::catalogue(Langue::DEFAUT)[$cle]);
    }

    /**
     * Le texte brut d'une clé, langue courante puis repli français, puis la
     * clé elle-même.
     */
    private static function lire(string $cle): string
    {
        $code = Langue::code();

        if ($code !== Langue::DEFAUT) {
            $valeur = self::catalogue($code)[$cle] ?? '';

            if ($valeur !== '') {
                return $valeur;
            }
        }

        return self::catalogue(Langue::DEFAUT)[$cle] ?? $cle;
    }

    /**
     * @param array<string,string|int> $valeurs
     */
    private static function remplacer(string $texte, array $valeurs, bool $echapper): string
    {
        if ($valeurs === []) {
            return $texte;
        }

        $de = $vers = [];

        foreach ($valeurs as $nom => $valeur) {
            $de[]   = ':' . $nom;
            $vers[] = $echapper ? View::e((string) $valeur) : (string) $valeur;
        }

        return str_replace($de, $vers, $texte);
    }

    /**
     * Charge le catalogue d'une langue. Un fichier absent donne un catalogue
     * vide plutôt qu'une erreur : c'est ce qui permet d'ajouter une langue au
     * `Langue::LANGUES` avant d'avoir écrit son fichier.
     *
     * @return array<string,string>
     */
    private static function catalogue(string $code): array
    {
        if (isset(self::$catalogues[$code])) {
            return self::$catalogues[$code];
        }

        $fichier = dirname(__DIR__) . '/lang/' . $code . '.php';
        $lu      = is_file($fichier) ? require $fichier : [];

        return self::$catalogues[$code] = is_array($lu) ? $lu : [];
    }
}
