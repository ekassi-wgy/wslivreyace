<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Validator;
use App\Model\PointDeVente;

/**
 * Les points de vente de l'ouvrage (lot G12).
 *
 * **La fiche n'a pas de titre, elle a une ville.** C'est le seul écran du
 * back-office dans ce cas, et c'est ce que déclare la clé `libelle` : elle dit
 * au déroulé commun quelle colonne identifie la ligne dans ses messages et ses
 * confirmations de suppression. Sans elle, supprimer une fiche annoncerait
 * « Le point de vente « — » a été supprimé ».
 *
 * **Ouvert aux éditeurs**, contrairement aux zones de livraison et aux
 * commandes : une librairie qui vend le livre est une information de
 * communication, pas une décision commerciale. Celui qui rédige l'actualité
 * d'une dédicace est celui qui sait où le livre est en rayon.
 */
final class PointDeVenteController extends CrudController
{
    protected static function modele(): string
    {
        return PointDeVente::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'points-de-vente',
            'chemin'         => '/points-de-vente',
            'singulier'      => 'Le point de vente',
            'pluriel'        => 'Points de vente',
            'titre_creation' => 'Nouveau point de vente',
            'titre_edition'  => 'Modifier le point de vente',
            'feminin'        => false,
            'gabarit'        => 'points-de-vente',
            'libelle'        => 'ville',
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('ville', 'Ville')->longueur('ville', 'Ville', 2, 120)
          ->longueur('enseigne', 'Enseigne', 0, 200)
          ->longueur('adresse', 'Adresse', 0, 300)
          ->longueur('telephone', 'Téléphone', 0, 60)
          ->longueur('url', 'Site', 0, 300)
          // Le schéma est vérifié, et restreint à http/https : cette valeur
          // finit dans un `href` de la page publique. Voir `Validator::url()`.
          ->url('url', 'Site')
          ->entier('ordre', 'Ordre', 0, 999)
          ->parmi('statut', 'Statut', array_keys(PointDeVente::STATUTS));

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'ville'     => $v->valeur('ville'),
            'enseigne'  => static::ouNull($v->valeur('enseigne')),
            'adresse'   => static::ouNull($v->valeur('adresse')),
            'telephone' => static::ouNull($v->valeur('telephone')),
            'url'       => static::ouNull($v->valeur('url')),
            'ordre'     => (int) $v->valeur('ordre', '0'),
            'statut'    => $v->valeur('statut', 'brouillon'),
        ];
    }
}
