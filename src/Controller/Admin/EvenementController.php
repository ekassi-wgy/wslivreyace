<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Slug;
use App\Core\Validator;
use App\Model\Evenement;

/**
 * Événements : dédicaces, colloques, hommages (CDC §4.10).
 */
final class EvenementController extends CrudController
{
    protected static function modele(): string
    {
        return Evenement::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'evenements',
            'chemin'         => '/evenements',
            'singulier'      => "L'événement",
            'pluriel'        => 'Événements',
            'titre_creation' => 'Nouvel événement',
            'titre_edition'  => "Modifier l'événement",
            'feminin'        => false,
            'gabarit'        => 'evenements',
            'media'          => true,
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 3, 200)
          ->longueur('lieu', 'Lieu', 0, 200)
          ->longueur('ville', 'Ville', 0, 120)
          ->requis('debut_le', 'Date et heure de début')
          ->parmi('statut', 'Statut', array_keys(Evenement::STATUTS))
          ->url('inscription_url', "Lien d'inscription")
          ->longueur('inscription_url', "Lien d'inscription", 0, 500);

        $debut = static::horodatage($v->valeur('debut_le'));
        $fin   = static::horodatage($v->valeur('fin_le'));

        if ($v->valeur('debut_le') !== '' && $debut === null) {
            $v->erreur('debut_le', 'La date de début est mal formée.');
        }
        if ($v->valeur('fin_le') !== '' && $fin === null) {
            $v->erreur('fin_le', 'La date de fin est mal formée.');
        }

        // Une fin antérieure au début passerait silencieusement en base et
        // donnerait une durée négative sur la page publique.
        if ($debut !== null && $fin !== null && $fin < $debut) {
            $v->erreur('fin_le', 'La fin ne peut pas précéder le début.');
        }

        // Un événement en ligne sans lieu n'est pas une annonce utilisable.
        if ($v->valeur('statut') === 'publie' && $v->valeur('ville') === '') {
            $v->erreur('ville', 'Un événement publié doit indiquer sa ville.');
        }

        static::validerImage($v);

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'titre'           => $v->valeur('titre'),
            'slug'            => Slug::unique(Evenement::table(), $v->valeur('titre'), $id),
            'description'     => static::ouNull($v->valeur('description')),
            'image'           => static::ouNull($v->valeur('image')),
            'lieu'            => static::ouNull($v->valeur('lieu')),
            'ville'           => static::ouNull($v->valeur('ville')),
            'debut_le'        => static::horodatage($v->valeur('debut_le')),
            'fin_le'          => static::horodatage($v->valeur('fin_le')),
            'inscription_url' => static::ouNull($v->valeur('inscription_url')),
            'statut'          => $v->valeur('statut', 'brouillon'),
        ];
    }

    /**
     * `<input type="datetime-local">` rend « 2026-03-14T18:30 » ; MySQL attend
     * « 2026-03-14 18:30:00 ». La conversion est faite ici, une fois, plutôt
     * que dans le gabarit.
     */
    private static function horodatage(string $valeur): ?string
    {
        if ($valeur === '') {
            return null;
        }

        foreach (['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', 'Y-m-d H:i'] as $format) {
            $d = \DateTimeImmutable::createFromFormat($format, $valeur);
            if ($d !== false) {
                return $d->format('Y-m-d H:i:s');
            }
        }

        return null;
    }
}
