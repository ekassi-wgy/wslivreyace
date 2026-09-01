<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Événements : dédicaces, colloques, hommages (CDC §4.10).
 */
final class Evenement extends Modele
{
    protected const TABLE = 'evenement';

    protected const ASSIGNABLES = [
        'titre', 'slug', 'description', 'lieu', 'ville',
        'debut_le', 'fin_le', 'image', 'inscription_url', 'statut',
    ];

    /** À venir d'abord : c'est ce qu'un éditeur vient corriger en priorité. */
    protected const ORDRE = 'debut_le DESC, id DESC';

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
        'annule'    => 'Annulé',
    ];
}
