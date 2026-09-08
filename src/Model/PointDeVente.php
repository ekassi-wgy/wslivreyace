<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Les points de vente de l'ouvrage (CDC §4.1 et §4.2, lot G12).
 *
 * Trois villes vivaient en dur dans `templates/pages/accueil.php` et
 * `templates/pages/livre.php`, sous une ligne « Enseigne et adresse à
 * renseigner » que rien ne permettait de renseigner. Elles sont désormais ici.
 *
 * `ville` tient lieu de titre : c'est le grand caractère de la maquette, et le
 * libellé sous lequel la fiche paraît dans tous les messages du back-office —
 * voir la clé `libelle` de `PointDeVenteController::config()`.
 */
final class PointDeVente extends Modele
{
    protected const TABLE = 'point_de_vente';

    protected const ASSIGNABLES = [
        'ville', 'enseigne', 'adresse', 'telephone', 'url', 'ordre', 'statut',
    ];

    /**
     * Le rang d'abord, la ville ensuite.
     *
     * Le classement alphabétique en second n'est pas décoratif : sans lui,
     * deux points de vente laissés au même rang — le cas dès qu'on en ajoute
     * un sans toucher au rang — sortiraient dans l'ordre où MySQL les rend,
     * qui n'est garanti par rien.
     */
    protected const ORDRE = 'ordre ASC, ville ASC, id ASC';

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];

    /** Ce qu'une page publique a le droit de voir. */
    private const PUBLIQUE = "statut = 'publie'";

    /**
     * Les points de vente publiés, dans l'ordre voulu par l'éditeur.
     *
     * Traduits au passage : une ville se dit parfois autrement en anglais, et
     * une adresse porte souvent un repère qui se traduit — voir
     * `App\Controller\Admin\TraductionController::ENTITES`.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function listerPublies(): array
    {
        return self::traduireToutes(Database::all(
            'SELECT * FROM ' . self::TABLE . ' WHERE ' . self::PUBLIQUE
            . ' ORDER BY ' . self::ORDRE
        ));
    }

    /**
     * Le numéro de téléphone réduit à ce qu'un `tel:` accepte.
     *
     * Le champ est saisi tel qu'il doit se lire — « +225 27 22 44 55 66 » —
     * et c'est bien ce qui doit s'afficher. Un lien d'appel, lui, ne veut ni
     * espaces ni points : les deux formes viennent donc de la même saisie,
     * plutôt que d'un second champ que personne ne tiendrait à jour.
     */
    public static function appel(string $telephone): string
    {
        return (string) preg_replace('/[^0-9+]/', '', $telephone);
    }
}
