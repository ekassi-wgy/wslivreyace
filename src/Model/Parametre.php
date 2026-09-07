<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

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
        ],
        'livre_isbn' => [
            'libelle' => 'ISBN',
            'type'    => 'isbn',
            'aide'    => 'ISBN-13, avec ou sans tirets.',
            'exemple' => '978-2-1234-5678-9',
        ],
        'livre_prix' => [
            'libelle' => 'Prix',
            'type'    => 'texte',
            'aide'    => 'Devise comprise : le site dessert plusieurs zones.',
            'exemple' => '25 000 F CFA',
        ],
        'livre_format' => [
            'libelle' => 'Format',
            'type'    => 'texte',
            'aide'    => '',
            'exemple' => 'Relié, 240 × 310 mm',
        ],
    ];

    /** @return array<string,string|null> toutes les valeurs, indexées par clé */
    public static function toutes(): array
    {
        $valeurs = [];

        foreach (Database::all('SELECT cle, valeur FROM parametre') as $l) {
            $valeurs[$l['cle']] = $l['valeur'];
        }

        return $valeurs;
    }

    public static function lire(string $cle, ?string $defaut = null): ?string
    {
        $l = Database::one('SELECT valeur FROM parametre WHERE cle = ?', [$cle]);
        return $l['valeur'] ?? $defaut;
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
