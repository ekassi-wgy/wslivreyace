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
 *
 * **La période ne se choisit plus** (lot G10). Un menu déroulant l'imposait,
 * et l'écran vérifiait ensuite que l'année de classement y tombait bien —
 * deux saisies pour une seule information, et une erreur à corriger quand le
 * découpage changeait. L'année suffit : la période qui la contient se déduit
 * (`App\Model\Periode::contenant()`), et la liste des repères l'affiche sans
 * que personne ait à l'entretenir.
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

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'annee'   => $v->valeur('annee'),
            'tri'     => (int) $v->valeur('tri'),
            'titre'   => $v->valeur('titre'),
            'notice'  => static::ouNull($v->valeur('notice')),
            'source'  => static::ouNull($v->valeur('source')),
            'statut'  => $v->valeur('statut', 'brouillon'),
            /*
             * Une case décochée ne poste rien. La valeur est donc **écrite
             * dans les deux cas** — 1 ou 0, jamais omise : ne rien écrire
             * quand elle est absente laisserait l'ancienne valeur en base, et
             * décocher n'aurait aucun effet.
             */
            'en_avant' => $v->valeur('en_avant') === '1' ? 1 : 0,
        ];
    }
}
