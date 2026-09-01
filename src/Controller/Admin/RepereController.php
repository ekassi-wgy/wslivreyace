<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Validator;
use App\Model\Repere;

/**
 * Repères chronologiques de la biographie (CDC §4.4).
 *
 * C'est le seul écran du lot où la validation porte sur le fond et pas
 * seulement sur la forme : un repère publié doit être sourcé. Yacé est une
 * figure historique réelle, et le §6 du cahier des charges exige que tout fait
 * biographique soit rattaché à une référence.
 */
final class RepereController extends CrudController
{
    protected static function modele(): string
    {
        return Repere::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'reperes',
            'chemin'         => '/reperes',
            'singulier'      => 'Le repère',
            'pluriel'        => 'Repères',
            'titre_creation' => 'Nouveau repère',
            'titre_edition'  => 'Modifier le repère',
            'feminin'        => false,
            'gabarit'        => 'reperes',
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 3, 200)
          ->requis('annee', 'Année affichée')->longueur('annee', 'Année affichée', 1, 20)
          ->requis('tri', 'Année de classement')
          ->entier('tri', 'Année de classement', 1900, 2100)
          ->parmi('periode', 'Période', array_keys(Repere::PERIODES))
          ->requis('periode', 'Période')
          ->longueur('source', 'Source', 0, 300)
          ->parmi('statut', 'Statut', array_keys(Repere::STATUTS));

        // La règle du projet, pas une convenance technique : rien n'est
        // attribué à Yacé sans référence vérifiable.
        if ($v->valeur('statut') === 'publie' && $v->valeur('source') === '') {
            $v->erreur(
                'source',
                'Un repère publié doit être sourcé (CDC §6). Laissez-le en brouillon '
                . "tant que la référence n'est pas établie."
            );
        }

        // L'année de classement doit rester dans la période choisie, sinon la
        // frise publique affiche l'entrée sous un filtre où elle ne va pas.
        static::verifierPeriode($v);

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'annee'   => $v->valeur('annee'),
            'tri'     => (int) $v->valeur('tri'),
            'periode' => $v->valeur('periode'),
            'titre'   => $v->valeur('titre'),
            'notice'  => static::ouNull($v->valeur('notice')),
            'source'  => static::ouNull($v->valeur('source')),
            'statut'  => $v->valeur('statut', 'brouillon'),
        ];
    }

    /**
     * Bornes reprises des filtres de la frise publique
     * (templates/pages/biographie.php). Les périodes se chevauchent d'un an sur
     * p3/p4 — 1980 appartient aux deux — c'est le découpage retenu côté public,
     * on ne le corrige pas ici sans décision éditoriale.
     */
    private const BORNES = [
        'p1' => [1920, 1944],
        'p2' => [1945, 1958],
        'p3' => [1959, 1980],
        'p4' => [1980, 1998],
    ];

    private static function verifierPeriode(Validator $v): void
    {
        $periode = $v->valeur('periode');
        $tri     = $v->valeur('tri');

        if (!isset(self::BORNES[$periode]) || filter_var($tri, FILTER_VALIDATE_INT) === false) {
            return;
        }

        [$min, $max] = self::BORNES[$periode];
        $annee = (int) $tri;

        if ($annee < $min || $annee > $max) {
            $v->erreur('tri', sprintf(
                'L\'année %d sort de la période choisie (%s). Corrigez l\'une ou l\'autre.',
                $annee,
                Repere::PERIODES[$periode]
            ));
        }
    }
}
