<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Slug;
use App\Core\Validator;
use App\Model\Archive;
use App\Model\Heritage;

/**
 * Héritage (brief §6, lot G7).
 *
 * Même patron que les notices d'archives : la rubrique porte plusieurs images,
 * la liaison vit dans une table à part, et la provenance est exigée pour
 * publier.
 */
final class HeritageController extends CrudController
{
    protected static function modele(): string
    {
        return Heritage::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'heritage',
            'chemin'         => '/heritage',
            'singulier'      => 'Le sujet',
            'pluriel'        => 'Héritage',
            'titre_creation' => 'Nouveau sujet',
            'titre_edition'  => 'Modifier le sujet',
            'feminin'        => false,
            'gabarit'        => 'heritage',
            'media'          => true,
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 3, 200)
          ->parmi('rubrique', 'Rubrique', array_keys(Heritage::RUBRIQUES))
          ->requis('rubrique', 'Rubrique')
          ->longueur('sous_titre', 'Sous-titre', 0, 300)
          ->longueur('lieu', 'Lieu', 0, 200)
          ->longueur('date_texte', 'Date affichée', 0, 60)
          ->longueur('source', 'Source', 0, 300)
          ->longueur('credit', 'Crédit', 0, 200)
          ->longueur('video_url', 'Lien de la vidéo', 0, 500)
          ->parmi('statut', 'Statut', array_keys(Heritage::STATUTS));

        if ($v->valeur('annee') !== '') {
            $v->entier('annee', 'Année', 1900, 2100);
        }

        if ($v->valeur('ordre') !== '') {
            $v->entier('ordre', 'Ordre', 0, 999);
        }

        /*
         * Le sourçage, comme partout ailleurs sur ce site. Il compte
         * particulièrement ici : une liste de décorations se recopie de proche
         * en proche avec ses erreurs, et publier la nôtre sans référence
         * reviendrait à ajouter une source de plus au malentendu.
         */
        if ($v->valeur('statut') === 'publie' && $v->valeur('source') === '') {
            $v->erreur(
                'source',
                'Un sujet publié doit être sourcé (CDC §6). Laissez-le en brouillon '
                . "tant que la référence n'est pas établie."
            );
        }

        $v->url('video_url', 'Lien de la vidéo');

        if ($v->valeur('video_url') !== ''
            && Archive::videoYoutube($v->valeur('video_url')) === null) {
            $v->erreur(
                'video_url',
                'Seules les adresses YouTube sont reconnues (youtube.com/watch, youtu.be). '
                . "Les vidéos ne sont pas hébergées sur le site."
            );
        }

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'titre'       => $v->valeur('titre'),
            'slug'        => Slug::unique(Heritage::table(), $v->valeur('titre'), $id),
            'rubrique'    => $v->valeur('rubrique', 'lieux'),
            'sous_titre'  => static::ouNull($v->valeur('sous_titre')),
            'description' => static::ouNull($v->valeur('description')),
            'lieu'        => static::ouNull($v->valeur('lieu')),
            'date_texte'  => static::ouNull($v->valeur('date_texte')),
            'annee'       => $v->valeur('annee') === '' ? null : (int) $v->valeur('annee'),
            'source'      => static::ouNull($v->valeur('source')),
            'credit'      => static::ouNull($v->valeur('credit')),
            'video_url'   => static::ouNull($v->valeur('video_url')),
            'ordre'       => $v->valeur('ordre') === '' ? 0 : (int) $v->valeur('ordre'),
            'statut'      => $v->valeur('statut', 'brouillon'),
        ];
    }

    /** Les images du sujet, écrites après la ligne — voir `CrudController`. */
    protected static function apresEcriture(int $id, Validator $v): void
    {
        $bruts = $_POST['fichiers'] ?? [];

        if (!is_array($bruts)) {
            $bruts = [];
        }

        $ids = [];

        foreach ($bruts as $brut) {
            $n = filter_var($brut, FILTER_VALIDATE_INT);

            if ($n !== false && $n > 0) {
                $ids[] = $n;
            }
        }

        Heritage::poserImages($id, $ids);
    }
}
