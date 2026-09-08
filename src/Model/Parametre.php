<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;
use App\Core\Langue;
use App\Core\Traduction;

/**
 * Contenus éditables sans toucher au code (CDC §4.2).
 *
 * Table clé/valeur, sans identifiant auto-incrémenté : elle n'hérite donc pas
 * de `Modele`, qui suppose une colonne `id`. La clé primaire est `cle`.
 *
 * Sert aujourd'hui à la fiche technique de l'ouvrage — les huit valeurs que
 * l'éditeur doit fournir avant mise en ligne. Le tableau ci-dessous est la
 * source de vérité : il décrit ce que le formulaire affiche, dans quel ordre,
 * et comment chaque valeur se valide.
 */
final class Parametre
{
    /**
     * Préface et auteur (lot G2).
     *
     * **La mise en avant de la préface est un réglage, pas un choix de
     * gabarit.** Le brief dit : « si la préface du Président de la République
     * se confirme, nous prévoirons une mise en avant spécifique ». Coder l'une
     * des deux formes aurait obligé à rouvrir la page le jour de la
     * confirmation — et remonter une préface, ce n'est pas déplacer un bloc,
     * c'est refaire la hiérarchie de la page.
     *
     * L'éditeur coche donc la case le jour venu : le bloc remonte en tête de
     * « Le livre » et un bandeau paraît sur l'accueil. Même principe que la
     * mise en avant des repères (lot G0) — un choix éditorial appartient à
     * l'éditeur, pas au code.
     *
     * `type` vaut `texte` (une ligne, 200 signes), `long` (plusieurs
     * paragraphes) ou `case` (oui/non).
     *
     * @var array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public const AUTOUR_LIVRE = [
        'preface_auteur' => [
            'libelle' => 'Nom du préfacier',
            'type'    => 'texte',
            'aide'    => 'Tel qu\'il doit être cité, sans abréviation.',
            'exemple' => 'Prénom NOM',
        ],
        'preface_qualite' => [
            'libelle' => 'Qualité du préfacier',
            'type'    => 'texte',
            'aide'    => 'Sa fonction, telle qu\'elle accompagnera la signature.',
            'exemple' => 'Président de la République de Côte d\'Ivoire',
        ],
        'preface_extrait' => [
            'libelle' => 'Extrait mis en exergue',
            'type'    => 'long',
            'aide'    => 'Une ou deux phrases, celles qui portent. Servent au bandeau de l\'accueil.',
            'exemple' => '',
        ],
        'preface_texte' => [
            'libelle' => 'Texte de la préface',
            'type'    => 'long',
            'aide'    => 'Le texte publié. Une ligne vide sépare deux paragraphes.',
            'exemple' => '',
        ],
        'preface_avant' => [
            'libelle' => 'Mettre la préface en avant',
            'type'    => 'case',
            'aide'    => 'À cocher quand la préface est confirmée : elle remonte en tête de la page du livre et un bandeau paraît sur l\'accueil.',
            'exemple' => '',
        ],
        'auteur_nom' => [
            'libelle' => 'Nom de l\'auteur',
            'type'    => 'texte',
            'aide'    => 'L\'auteur de l\'ouvrage. Sa page est publiée dès que ce nom est renseigné.',
            'exemple' => 'Prénom NOM',
        ],
        'auteur_qualite' => [
            'libelle' => 'Qualité de l\'auteur',
            'type'    => 'texte',
            'aide'    => 'Une ligne : profession, titre, rattachement.',
            'exemple' => 'Historien',
        ],
        'auteur_bio' => [
            'libelle' => 'Biographie de l\'auteur',
            'type'    => 'long',
            'aide'    => 'Sa page propre s\'en nourrit. Une ligne vide sépare deux paragraphes.',
            'exemple' => '',
        ],
    ];

    /**
     * @var array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public const FICHE_LIVRE = [
        'livre_titre' => [
            'libelle' => "Titre de l'ouvrage",
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Une destinée',
        ],
        'livre_auteur' => [
            'libelle' => 'Auteur',
            'type'    => 'texte',
            'aide'    => "L'auteur du livre, à ne pas confondre avec son sujet.",
            'exemple' => 'Prénom NOM',
        ],
        'livre_editeur' => [
            'libelle' => 'Éditeur',
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Nom de la maison d\'édition',
        ],
        'livre_parution' => [
            'libelle' => 'Date de parution',
            'type'    => 'texte',
            'aide'    => 'Texte libre : le mois suffit si le jour n\'est pas arrêté.',
            'exemple' => 'Mars 2026',
        ],
        'livre_pages' => [
            'libelle' => 'Nombre de pages',
            'type'    => 'entier',
            'aide'    => '',
            'exemple' => '320',
            'min'     => 1,
            'max'     => 10000,
        ],
        'livre_isbn' => [
            'libelle' => 'ISBN',
            'type'    => 'isbn',
            'aide'    => 'ISBN-13, avec ou sans tirets.',
            'exemple' => '978-2-1234-5678-9',
        ],
        'livre_prix' => [
            'libelle' => 'Prix en francs CFA',
            'type'    => 'entier',
            'aide'    => "Le nombre seul, sans espaces ni devise : le site l'affiche « 25 000 F CFA » "
                       . "et s'en sert pour calculer les commandes. Sans prix, la boutique reste fermée.",
            'exemple' => '25000',
            // Un livre à plus de dix millions de francs est une faute de
            // frappe, pas un tarif ; le plancher écarte le zéro, qui rendrait
            // l'ouvrage gratuit sans que personne ne s'en aperçoive.
            'min'     => 1,
            'max'     => 10000000,
        ],
        'livre_format' => [
            'libelle' => 'Format',
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Relié, 240 × 310 mm',
        ],
    ];

    /**
     * La boutique et la livraison (lot G3).
     *
     * **Un groupe à part et non des champs ajoutés à la fiche technique** : la
     * fiche décrit l'ouvrage, ceci décide s'il se vend. Les mêler ferait
     * qu'ouvrir la boutique passerait pour une correction de fiche, et
     * `ficheRemplie()` — qui compte ce qui reste à fournir avant mise en
     * ligne — se mettrait à réclamer un point de retrait.
     *
     * @var array<string,array{libelle:string,type:string,aide:string,exemple:string}>
     */
    public const BOUTIQUE = [
        'boutique_ouverte' => [
            'libelle' => 'Ouvrir les commandes',
            'type'    => 'case',
            'aide'    => "À cocher le jour où l'ouvrage peut être remis. Décochée, la page "
                       . 'Commander reste en ligne et annonce que les commandes ouvriront à '
                       . "la parution — plutôt qu'un bouton qui ne fait rien. Un prix est "
                       . 'exigé en plus de la case : sans lui la boutique reste fermée.',
            'exemple' => '',
        ],
        'retrait_lieu' => [
            'libelle' => 'Point de retrait',
            'type'    => 'long',
            'aide'    => "L'adresse où l'on vient chercher un exemplaire, avec ses horaires. "
                       . "Vide, le retrait n'est pas proposé du tout : offrir « retrait sur "
                       . 'place » sans dire où est une promesse creuse, et le client s\'en '
                       . 'aperçoit après avoir commandé.',
            'exemple' => '',
        ],
        'commande_message' => [
            'libelle' => 'Message affiché après commande',
            'type'    => 'long',
            'aide'    => 'Ce que le client lit sur la page de confirmation, sous sa référence. '
                       . 'Le délai de rappel, le mode de paiement accepté à la remise, un numéro '
                       . "à joindre. Vide, le site s'en tient à sa formule standard.",
            'exemple' => '',
        ],
    ];

    /** @return array<string,string|null> toutes les valeurs, indexées par clé */
    public static function toutes(): array
    {
        $valeurs = [];

        foreach (Database::all('SELECT cle, valeur FROM parametre') as $l) {
            $valeurs[$l['cle']] = $l['valeur'];
        }

        return self::traduites($valeurs);
    }

    public static function lire(string $cle, ?string $defaut = null): ?string
    {
        $l = Database::one('SELECT valeur FROM parametre WHERE cle = ?', [$cle]);

        return self::traduites([$cle => $l['valeur'] ?? $defaut])[$cle];
    }

    /**
     * Recouvre les valeurs traduites, en anglais comme ailleurs (lot G11).
     *
     * **`parametre` ne passe pas par `Modele` et n'a pas d'identifiant** : sa
     * clé primaire est `cle`, une chaîne, quand `Traduction` s'indexe sur un
     * entier. Le trou était réel — `preface_texte`, `preface_extrait` et
     * `auteur_bio` sont de la prose, pas des réglages, et seraient restés
     * français sur une page anglaise.
     *
     * Il se comble **sans migration** : la table `traduction` accepte
     * `ligne_id = 0`, aucune ligne de `parametre` n'ayant d'identifiant qui
     * puisse entrer en collision, et sa clé unique porte déjà sur le
     * quadruplet `(entite, ligne_id, langue, champ)`. Le nom du paramètre tient
     * lieu de `champ`, ce qu'il est déjà.
     *
     * En français la méthode ne fait rien et n'interroge rien, comme le reste
     * de `Traduction` : le coût du bilinguisme reste nul tant que le site est
     * monolingue.
     *
     * @param array<string,string|null> $valeurs
     * @return array<string,string|null>
     */
    private static function traduites(array $valeurs): array
    {
        if (Langue::estDefaut() || $valeurs === []) {
            return $valeurs;
        }

        /*
         * `Traduction::ligne()` attend une ligne portant un `id` : on lui en
         * fabrique une, avec l'identifiant conventionnel des paramètres. Elle
         * ne recouvre que les clés déjà présentes, donc rien n'apparaît qui ne
         * soit déjà attendu par l'appelant.
         */
        $ligne = Traduction::ligne('parametre', ['id' => Traduction::SANS_ID] + $valeurs);

        unset($ligne['id']);

        return $ligne;
    }

    /**
     * Écrit une valeur.
     *
     * `ON DUPLICATE KEY UPDATE` plutôt qu'un SELECT suivi d'un INSERT ou d'un
     * UPDATE : une seule requête, et pas de fenêtre entre les deux où une
     * autre écriture s'intercalerait.
     */
    public static function ecrire(string $cle, ?string $valeur, ?string $libelle = null): void
    {
        Database::pdo()->prepare(
            'INSERT INTO parametre (cle, valeur, libelle) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)'
        )->execute([$cle, $valeur, $libelle]);
    }

    /** Combien des huit valeurs de la fiche technique sont renseignées. */
    public static function ficheRemplie(): int
    {
        $valeurs = self::toutes();
        $n = 0;

        foreach (array_keys(self::FICHE_LIVRE) as $cle) {
            if (trim((string) ($valeurs[$cle] ?? '')) !== '') {
                $n++;
            }
        }

        return $n;
    }
}
