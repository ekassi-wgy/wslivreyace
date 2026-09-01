<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Actualités et revue de presse (CDC §4.7).
 */
final class Actualite extends Modele
{
    protected const TABLE = 'actualite';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'categorie', 'chapo', 'contenu',
        'image', 'source', 'source_url', 'statut', 'publie_le',
    ];

    /**
     * Les plus récentes d'abord. `publie_le` peut être nulle sur un brouillon :
     * sans le repli sur `cree_le`, tous les brouillons se rangeraient en bloc à
     * une extrémité de la liste au lieu de rester près de leur date de saisie.
     */
    protected const ORDRE = 'COALESCE(publie_le, DATE(cree_le)) DESC, id DESC';

    public const CATEGORIES = [
        'parution'  => 'Parution',
        'dedicace'  => 'Dédicace',
        'presse'    => 'Presse',
        'hommage'   => 'Hommage',
        'evenement' => 'Événement',
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];
}
