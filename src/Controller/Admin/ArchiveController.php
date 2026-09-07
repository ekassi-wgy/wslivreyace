<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Slug;
use App\Core\Validator;
use App\Model\Archive;

/**
 * Notices d'archives (brief §4, lot G4).
 *
 * Suit le patron des écrans de contenu, avec une différence : une notice porte
 * **plusieurs** fichiers. Le sélecteur d'illustration des actualités choisit
 * une image ; celui-ci en choisit une liste ordonnée, et le lien vit dans
 * `archive_media` et non dans une colonne.
 *
 * Comme les repères, la validation porte sur le fond : une archive publiée
 * sans mention de sa provenance expose l'éditeur (CDC §6, brief §4).
 */
final class ArchiveController extends CrudController
{
    protected static function modele(): string
    {
        return Archive::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'archives',
            'chemin'         => '/archives',
            'singulier'      => "L'archive",
            'pluriel'        => 'Archives',
            'titre_creation' => 'Nouvelle archive',
            'titre_edition'  => "Modifier l'archive",
            'feminin'        => true,
            'gabarit'        => 'archives',
            // Ouvre la planche de la médiathèque avec le formulaire : c'est
            // là qu'on choisit les fichiers de la notice.
            'media'          => true,
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 3, 200)
          ->parmi('categorie', 'Catégorie', array_keys(Archive::CATEGORIES))
          ->requis('categorie', 'Catégorie')
          ->longueur('lieu', 'Lieu', 0, 200)
          ->longueur('personnes', 'Personnes présentes', 0, 500)
          ->longueur('mots_cles', 'Mots-clés', 0, 500)
          ->longueur('date_texte', 'Date affichée', 0, 60)
          ->longueur('source', 'Source', 0, 300)
          ->longueur('credit', 'Crédit', 0, 200)
          ->longueur('video_url', 'Lien de la vidéo', 0, 500)
          ->parmi('statut', 'Statut', array_keys(Archive::STATUTS));

        // L'année est facultative — une archive est souvent mal datée — mais
        // si elle est donnée, elle doit être plausible : c'est elle qui filtre
        // et qui classe.
        if ($v->valeur('annee') !== '') {
            $v->entier('annee', 'Année', 1900, 2100);
        }

        /*
         * La règle du projet, et non une convenance : une pièce publiée sans
         * provenance expose l'éditeur. Le fonds recevra des documents prêtés
         * par des familles et des organes de presse — savoir de qui vient
         * quoi n'est pas une formalité.
         */
        if ($v->valeur('statut') === 'publie'
            && $v->valeur('credit') === ''
            && $v->valeur('source') === '') {
            $v->erreur(
                'credit',
                'Une archive publiée doit porter son crédit ou sa source (CDC §6). '
                . "Laissez-la en brouillon tant que la provenance n'est pas établie."
            );
        }

        $v->url('video_url', 'Lien de la vidéo');

        // Une adresse de vidéo qu'on ne sait pas intégrer afficherait un cadre
        // vide sur la page publique : autant le dire à la saisie.
        if ($v->valeur('video_url') !== ''
            && Archive::videoYoutube($v->valeur('video_url')) === null) {
            $v->erreur(
                'video_url',
                "Seules les adresses YouTube sont reconnues (youtube.com/watch, youtu.be). "
                . 'Les vidéos ne sont pas hébergées sur le site.'
            );
        }

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'titre'         => $v->valeur('titre'),
            'slug'          => Slug::unique(Archive::table(), $v->valeur('titre'), $id),
            'categorie'     => $v->valeur('categorie', 'photographies'),
            'description'   => static::ouNull($v->valeur('description')),
            'lieu'          => static::ouNull($v->valeur('lieu')),
            'personnes'     => static::ouNull($v->valeur('personnes')),
            'mots_cles'     => static::ouNull($v->valeur('mots_cles')),
            'date_texte'    => static::ouNull($v->valeur('date_texte')),
            'annee'         => $v->valeur('annee') === '' ? null : (int) $v->valeur('annee'),
            'source'        => static::ouNull($v->valeur('source')),
            'credit'        => static::ouNull($v->valeur('credit')),
            'contexte'      => static::ouNull($v->valeur('contexte')),
            'transcription' => static::ouNull($v->valeur('transcription')),
            'video_url'     => static::ouNull($v->valeur('video_url')),
            'statut'        => $v->valeur('statut', 'brouillon'),
        ];
    }

    /**
     * Les fichiers de la notice, écrits après la ligne elle-même.
     *
     * `CrudController` ne connaît que des colonnes ; la liaison vit dans une
     * seconde table, d'où ce point d'accroche. Il est appelé à la création
     * comme à la modification — à la création, l'identifiant n'existe qu'ici.
     */
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

        // Les identifiants inconnus sont écartés par la clé étrangère de
        // `archive_media` ; les filtrer ici évite l'erreur SQL plutôt que de
        // la laisser remonter à l'éditeur.
        Archive::poserFichiers($id, $ids);
    }
}
