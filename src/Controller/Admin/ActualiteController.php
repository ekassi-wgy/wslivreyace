<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Slug;
use App\Core\Validator;
use App\Model\Actualite;

/**
 * Actualités et revue de presse (CDC §4.7).
 */
final class ActualiteController extends CrudController
{
    protected static function modele(): string
    {
        return Actualite::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'actualites',
            'chemin'         => '/actualites',
            'singulier'      => "L'actualité",
            'pluriel'        => 'Actualités',
            'titre_creation' => 'Nouvelle actualité',
            'titre_edition'  => "Modifier l'actualité",
            'feminin'        => true,
            'gabarit'        => 'actualites',
            'media'          => true,
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 3, 200)
          ->parmi('categorie', 'Catégorie', array_keys(Actualite::CATEGORIES))
          ->longueur('chapo', 'Chapô', 0, 400)
          ->longueur('source', 'Source', 0, 200)
          ->longueur('source_url', 'Lien vers la source', 0, 500)
          ->parmi('statut', 'Statut', array_keys(Actualite::STATUTS))
          ->date('publie_le', 'Date de publication');

        // Un article de presse sans référence à son organe n'est pas
        // vérifiable : le cahier des charges (§6) exige le sourçage.
        if ($v->valeur('categorie') === 'presse' && $v->valeur('source') === '') {
            $v->erreur('source', 'La source est obligatoire pour un article de presse.');
        }

        // Rien ne part en ligne sans date : la page publique classe par
        // `publie_le`, une entrée sans date s'y rangerait n'importe où.
        if ($v->valeur('statut') === 'publie' && $v->valeur('publie_le') === '') {
            $v->erreur('publie_le', 'Une actualité publiée doit porter une date.');
        }

        $v->url('source_url', 'Lien vers la source');
        static::validerImage($v);

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'titre'      => $v->valeur('titre'),
            'slug'       => Slug::unique(Actualite::table(), $v->valeur('titre'), $id),
            'categorie'  => $v->valeur('categorie', 'parution'),
            'chapo'      => static::ouNull($v->valeur('chapo')),
            'image'      => static::ouNull($v->valeur('image')),
            'contenu'    => static::ouNull($v->valeur('contenu')),
            'source'     => static::ouNull($v->valeur('source')),
            'source_url' => static::ouNull($v->valeur('source_url')),
            'statut'     => $v->valeur('statut', 'brouillon'),
            'publie_le'  => static::ouNull($v->valeur('publie_le')),
        ];
    }
}
