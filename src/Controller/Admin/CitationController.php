<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Validator;
use App\Model\Citation;

/**
 * Les citations du site public (lot G14).
 *
 * **La fiche n'a pas de titre, elle a un texte.** C'est ce que déclare la clé
 * `libelle` : elle dit au déroulé commun quelle colonne identifie la ligne dans
 * ses messages et ses confirmations de suppression. Le texte d'une citation
 * pouvant courir sur un paragraphe, la liste et les messages le tronquent —
 * voir `templates/admin/pages/citations/liste.php`.
 *
 * **Ouvert aux éditeurs.** Choisir la phrase qui ouvre l'accueil est un acte
 * éditorial, pas un réglage : c'est exactement le métier de celui qui rédige.
 */
final class CitationController extends CrudController
{
    protected static function modele(): string
    {
        return Citation::class;
    }

    protected static function config(): array
    {
        return [
            'cle'            => 'citations',
            'chemin'         => '/citations',
            'singulier'      => 'La citation',
            'pluriel'        => 'Citations',
            'titre_creation' => 'Nouvelle citation',
            'titre_edition'  => 'Modifier la citation',
            'feminin'        => true,
            'gabarit'        => 'citations',
            'libelle'        => 'texte',
        ];
    }

    protected static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->parmi('emplacement', 'Emplacement', array_keys(Citation::EMPLACEMENTS))
          ->requis('texte', 'Texte')->longueur('texte', 'Texte', 10, 2000)
          ->longueur('source', 'Source', 0, 200)
          ->parmi('statut', 'Statut', array_keys(Citation::STATUTS));

        return $v;
    }

    protected static function donnees(Validator $v, ?int $id): array
    {
        return [
            'emplacement' => $v->valeur('emplacement'),
            'texte'       => $v->valeur('texte'),
            'source'      => static::ouNull($v->valeur('source')),
            'statut'      => $v->valeur('statut', 'brouillon'),
            /*
             * La case n'est pas écrite directement : `apresEcriture()` la
             * repose juste après, de façon exclusive. L'écrire ici aussi
             * laisserait, le temps d'une requête, deux citations en avant pour
             * un même emplacement.
             */
            'en_avant'    => 0,
        ];
    }

    /**
     * L'activation, après l'enregistrement.
     *
     * **Une citation mise en avant retire la mise en avant des autres du même
     * emplacement.** Un bandeau ne montre qu'une citation ; la case se comporte
     * donc comme un bouton radio, faute de quoi l'éditeur en coche deux et se
     * demande laquelle le site affiche.
     *
     * Rien à faire quand la case est décochée : `donnees()` a déjà remis
     * `en_avant` à 0, et l'emplacement se retrouve muet — ce que la liste
     * signale en tête.
     */
    protected static function apresEcriture(int $id, Validator $v): void
    {
        if ($v->valeur('en_avant') === '') {
            return;
        }

        Citation::activer($id, $v->valeur('emplacement'));
    }
}
