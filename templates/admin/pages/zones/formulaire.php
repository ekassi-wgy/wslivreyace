<?php
/**
 * Créer ou modifier une zone de livraison (lot G3).
 *
 * **Le niveau ne se saisit pas** : il découle du parent, et le formulaire
 * l'annonce plutôt que de l'offrir au choix. Un menu déroulant aurait permis
 * de ranger une commune sous un pays, et l'arbre aurait cessé de vouloir dire
 * quelque chose.
 *
 * **Le tarif hérité est affiché à côté du champ vide**, avec son origine : sans
 * cela, une case vide se lit comme un oubli et l'éditeur recopie le montant du
 * parent — ce qui casse l'héritage le jour où le parent change.
 */

use App\Core\Admin;
use App\Core\Boutique;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Zone;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = $edition ? (int) $zone['id'] : 0;
$action = $edition ? Admin::url('/zones/' . $id) : Admin::url('/zones');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">
      <a href="<?= Admin::url('/zones') ?>">Zones de livraison</a>
    </span>
    <h1><?= View::e($titre) ?></h1>
    <p>
      <?= View::e(Zone::niveau($niveau)) ?>
      <?php if ($parent !== null): ?>
        &middot; rattachée à <strong><?= View::e((string) $parent['nom']) ?></strong>
      <?php else: ?>
        &middot; racine
      <?php endif; ?>
    </p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/zones') ?>">Retour</a>
</div>

<form method="post" action="<?= View::e($action) ?>">
  <?= Csrf::champ() ?>
  <input type="hidden" name="parent_id" value="<?= $parent === null ? '' : (int) $parent['id'] ?>">

  <div class="row">
    <div class="col-lg-7 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">La zone</h4>

        <?php champ_texte($valeurs, $erreurs, 'nom', 'Nom de la zone', [
            'requis'    => true,
            'attributs' => 'maxlength="120" placeholder="' . View::e(match ($niveau) {
                'pays'    => "Côte d'Ivoire",
                'ville'   => 'Abidjan',
                default   => 'Cocody',
            }) . '"',
            'aide'      => 'Tel qu\'il doit apparaître dans le menu déroulant du client.',
        ]); ?>

        <?php if ($niveau === 'pays'): ?>
          <?php champ_texte($valeurs, $erreurs, 'code', 'Code pays', [
              'attributs' => 'maxlength="40" placeholder="CI"',
              'aide'      => 'Facultatif. Le code ISO à deux lettres — CI, FR, US — pour '
                           . 's\'y retrouver plus tard. Il ne s\'affiche pas côté public.',
          ]); ?>
        <?php endif; ?>

        <?php champ_texte($valeurs, $erreurs, 'frais', 'Frais de livraison', [
            'type'      => 'number',
            'attributs' => 'min="0" step="1" placeholder="'
                . ($herite === null ? 'aucun tarif hérité' : (string) $herite) . '"',
            'aide'      => $parent === null
                ? 'En francs CFA, le nombre seul. <strong>Laissé vide, cette zone n\'est pas '
                  . 'livrée</strong> — une racine n\'a personne de qui hériter.'
                : ($herite === null
                    ? 'En francs CFA, le nombre seul. <strong>Le parent ne porte aucun tarif</strong> : '
                      . 'tant que ce champ reste vide, cette zone n\'est pas livrée.'
                    : 'En francs CFA, le nombre seul. <strong>Laissé vide, cette zone hérite de '
                      . View::e(Boutique::somme((int) $herite)) . '</strong> — et suivra le parent '
                      . 'le jour où il changera. Ne le recopiez pas : c\'est ce qui casse l\'héritage.'),
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'ordre', 'Rang', [
            'type'      => 'number',
            'attributs' => 'min="0" step="1"',
            'aide'      => 'Petit d\'abord. À rang égal, l\'ordre alphabétique tranche.',
        ]); ?>

        <?php champ_case($valeurs, $erreurs, 'actif', 'Zone desservie', [
            'aide' => 'Décochée, elle disparaît du formulaire de commande sans être supprimée — '
                    . 'et ses zones filles avec elle. C\'est le geste à faire quand un '
                    . 'transporteur cesse de desservir un secteur.',
        ]); ?>
      </div></div>
    </div>

    <div class="col-lg-5 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Comment les tarifs se résolvent</h4>
        <ul class="pgy-liste-notes">
          <li>Le tarif appliqué est <strong>le premier trouvé en remontant</strong> :
              la commune, puis la ville, puis le pays.</li>
          <li>Un tarif à <strong>zéro</strong> est une livraison offerte — c'est un
              tarif, et il arrête la remontée. Un champ <strong>vide</strong> ne
              l'arrête pas.</li>
          <li>Une zone dont personne, sur toute la remontée, ne porte de tarif
              <strong>n'est pas proposée</strong> au client.</li>
          <li>Fermer un pays ferme ses villes et ses communes, sans avoir à les
              décocher une à une.</li>
        </ul>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/zones') ?>">Annuler</a>

    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Zone n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>

<?php if ($edition): ?>
  <?php /* La suppression est un formulaire à part : elle ne doit pas partir
           avec l'enregistrement, et elle est refusée si la zone porte des
           filles — voir ZoneController::supprimer(). */ ?>
  <div class="card card-rounded mt-4"><div class="card-body">
    <h4 class="card-title card-title-dash">Supprimer</h4>
    <p class="text-muted small">
      Les commandes déjà passées ne s'y opposent pas : elles gardent la zone
      écrite en toutes lettres. Une zone qui porte des zones filles ne peut pas
      être supprimée — décochez-la plutôt, elle cesse d'être proposée sans que
      rien ne soit perdu.
    </p>
    <form method="post" action="<?= Admin::url('/zones/' . $id . '/supprimer') ?>"
          onsubmit="return confirm('Supprimer cette zone ?');">
      <?= Csrf::champ() ?>
      <button type="submit" class="btn btn-outline-danger">
        <i class="mdi mdi-trash-can-outline me-1" aria-hidden="true"></i> Supprimer cette zone
      </button>
    </form>
  </div></div>
<?php endif; ?>
