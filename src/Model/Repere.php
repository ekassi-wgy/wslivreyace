<?php
declare(strict_types=1);

namespace App\Model;

/**
 * Repères chronologiques de la biographie (CDC §4.4).
 *
 * `annee` est une chaîne et non un entier : une date d'archive est souvent
 * imprécise — « v. 1945 », « 1959-1960 ». `tri` porte l'année numérique qui
 * sert au classement, et c'est pour cela que les deux coexistent.
 */
final class Repere extends Modele
{
    protected const TABLE = 'repere';

    protected const ASSIGNABLES = [
        'annee', 'tri', 'periode', 'titre', 'notice', 'source', 'statut',
    ];

    /** Ordre chronologique : une frise ne se lit pas à l'envers. */
    protected const ORDRE = 'tri ASC, id ASC';

    /**
     * Les bornes reprennent exactement les filtres de la frise publique
     * (templates/pages/biographie.php). Les faire diverger casserait le
     * filtrage sans que rien ne le signale.
     */
    public const PERIODES = [
        'p1' => '1920 — 1944',
        'p2' => '1945 — 1958',
        'p3' => '1959 — 1980',
        'p4' => '1980 — 1998',
    ];

    public const STATUTS = [
        'brouillon' => 'Brouillon',
        'publie'    => 'Publié',
    ];
}
