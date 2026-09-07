<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Slug;
use App\Core\Validator;
use App\Model\Periode;

/**
 * Les périodes de la biographie (brief §3, lot G10).
 *
 * Même patron que les notices d'archives et les sujets d'Héritage : plusieurs
 * images par fiche, la liaison dans une table à part, et la provenance exigée
 * pour publier.
 *
 * Deux règles lui sont propres, et toutes deux tiennent à ce qu'une période
 * est : **des bornes**. Elles sont exigées à la publication, et deux périodes
 * publiées ne peuvent pas se recouvrir.
 */
final class PeriodeController extends CrudController
{
    /**
     * Bornes acceptées pour une année de période.
     *
     * Larges à dessein : la biographie porte sur 1920-1998, mais une période
     * de contexte peut commencer avant la naissance — « la Côte d'Ivoire
     * coloniale » — et l'écran n'a pas à en décider.
     */
    private const ANNEE_MIN = 1800;
    private const ANNEE_MAX = 2100;

    protected static function modele(): string
    {
        return Periode::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'periodes',
            'chemin'         => '/periodes',
            'singulier'      => 'La période',
            'pluriel'        => 'Biographie',
            'titre_creation' => 'Nouvelle période',
            'titre_edition'  => 'Modifier la période',
            'feminin'        => true,
            'gabarit'        => 'periodes',
            'media'          => true,
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 3, 200)
          ->longueur('sous_titre', 'Sous-titre', 0, 300)
          ->longueur('source', 'Source', 0, 300)
          ->parmi('statut', 'Statut', array_keys(Periode::STATUTS));

        foreach (['debut' => 'Année de début', 'fin' => 'Année de fin'] as $champ => $libelle) {
            if ($v->valeur($champ) !== '') {
                $v->entier($champ, $libelle, self::ANNEE_MIN, self::ANNEE_MAX);
            }
        }

        static::verifierBornes($v, $id);

        /*
         * Le sourçage, comme partout ailleurs sur ce site — et c'est ici qu'il
         * compte le plus : la biographie est le seul endroit où l'on écrit la
         * vie d'une personne réelle en continu, et une phrase non sourcée y
         * passe pour un fait établi (CDC §6).
         */
        if ($v->valeur('statut') === 'publie' && $v->valeur('source') === '') {
            $v->erreur(
                'source',
                'Une période publiée doit être sourcée (CDC §6). Laissez-la en brouillon '
                . "tant que la référence n'est pas établie."
            );
        }

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'titre'      => $v->valeur('titre'),
            'slug'       => Slug::unique(Periode::table(), $v->valeur('titre'), $id),
            'sous_titre' => static::ouNull($v->valeur('sous_titre')),
            'debut'      => $v->valeur('debut') === '' ? null : (int) $v->valeur('debut'),
            'fin'        => $v->valeur('fin')   === '' ? null : (int) $v->valeur('fin'),
            'recit'      => static::ouNull($v->valeur('recit')),
            'source'     => static::ouNull($v->valeur('source')),
            'statut'     => $v->valeur('statut', 'brouillon'),
        ];
    }

    /**
     * Ce qui empêche de publier une période depuis la liste.
     *
     * Le bouton de publication n'écrit qu'une colonne et ne passe pas par
     * `valider()` : sans ce contrôle, il mettrait en ligne ce que la fiche
     * refuse d'enregistrer. Les mêmes trois règles, dans le même ordre.
     *
     * @param array<string,mixed> $ligne
     */
    protected static function refusDePublier(array $ligne): ?string
    {
        $debut = (int) ($ligne['debut'] ?? 0);
        $fin   = (int) ($ligne['fin'] ?? 0);

        if ($debut === 0 || $fin === 0) {
            return sprintf(
                '« %s » ne peut pas être publiée sans ses deux bornes : c\'est par elles '
                . "qu'elle retrouve les repères de la frise et les pièces du fonds. "
                . 'Ouvrez la fiche pour les renseigner.',
                (string) ($ligne['titre'] ?? '—')
            );
        }

        if (trim((string) ($ligne['source'] ?? '')) === '') {
            return sprintf(
                '« %s » ne peut pas être publiée sans source (CDC §6).',
                (string) ($ligne['titre'] ?? '—')
            );
        }

        $voisines = Periode::chevauchantes($debut, $fin, (int) $ligne['id']);

        if ($voisines !== []) {
            return sprintf(
                '« %s » (%s) recouvre « %s » (%s). Deux périodes publiées ne peuvent pas se '
                . 'chevaucher : une année appartient à un seul récit.',
                (string) ($ligne['titre'] ?? '—'),
                Periode::annees($ligne),
                (string) $voisines[0]['titre'],
                Periode::annees($voisines[0])
            );
        }

        return null;
    }

    /** Les images de la période, écrites après la ligne — voir `CrudController`. */
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

        Periode::poserImages($id, $ids);
    }

    /**
     * Ce qu'une période doit à ses dates.
     *
     * Trois contrôles, dans l'ordre où ils cessent d'avoir un sens : les
     * bornes doivent être dans le bon ordre, présentes pour publier, et ne
     * recouvrir aucune autre période publiée.
     *
     * **Le chevauchement est vérifié à la publication seulement.** Un
     * brouillon en cours de découpage passe forcément par des états
     * incohérents — on déplace une borne, puis l'autre — et refuser
     * l'enregistrement intermédiaire obligerait à tenir le découpage entier
     * dans sa tête plutôt que dans l'écran.
     */
    private static function verifierBornes(Validator $v, ?int $id): void
    {
        $debut = $v->valeur('debut');
        $fin   = $v->valeur('fin');
        $publie = $v->valeur('statut') === 'publie';

        if ($debut !== '' && $fin !== ''
            && filter_var($debut, FILTER_VALIDATE_INT) !== false
            && filter_var($fin, FILTER_VALIDATE_INT) !== false
            && (int) $fin < (int) $debut) {
            $v->erreur('fin', "L'année de fin ne peut pas précéder celle de début.");

            return;
        }

        if (!$publie) {
            return;
        }

        // Une période publiée sans bornes ne porterait ni frise ni fonds : elle
        // dirait qu'elle est une période sans en être une.
        foreach (['debut' => 'de début', 'fin' => 'de fin'] as $champ => $quoi) {
            if ($v->valeur($champ) === '') {
                $v->erreur($champ, sprintf(
                    "Une période publiée doit porter ses deux bornes : c'est par elles qu'elle "
                    . "retrouve les repères de la frise et les pièces du fonds. Renseignez l'année %s "
                    . 'ou laissez la période en brouillon.',
                    $quoi
                ));
            }
        }

        if ($v->erreurDe('debut') !== null || $v->erreurDe('fin') !== null) {
            return;
        }

        /*
         * Deux périodes publiées ne peuvent pas se recouvrir : une année
         * appartient à un récit et à un seul, sans quoi le même repère
         * paraîtrait sous deux onglets de la frise et la même pièce d'archive
         * sous deux périodes. Le découpage `p1`-`p4` que ce lot remplace se
         * chevauchait d'un an — 1980 fermait l'un et ouvrait l'autre.
         */
        $voisines = Periode::chevauchantes((int) $v->valeur('debut'), (int) $v->valeur('fin'), $id);

        if ($voisines !== []) {
            $premiere = $voisines[0];

            $v->erreur('debut', sprintf(
                'Ces années recouvrent « %s » (%s). Deux périodes publiées ne peuvent pas se '
                . 'chevaucher : une année appartient à un seul récit, et les repères comme les '
                . 'pièces du fonds s\'y rattachent par leur date.',
                (string) $premiere['titre'],
                Periode::annees($premiere)
            ));
        }
    }
}
