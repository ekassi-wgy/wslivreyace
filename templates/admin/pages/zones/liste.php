<?php
/**
 * L'arbre des zones de livraison (lot G3).
 *
 * **Un arbre et non une liste triée.** L'héritage des tarifs ne se lit que
 * dans la hiérarchie : voir « Cocody : 1 000 » sous « Abidjan : 1 500 » sous
 * « Côte d'Ivoire : 2 000 » dit d'un coup d'œil ce qu'une liste plate
 * demanderait de reconstituer.
 *
 * Chaque ligne dit **d'où vient son tarif** — le sien, ou celui d'un ancêtre
 * nommé. Une case vide sans explication ferait croire à un oubli.
 */

use App\Core\Admin;
use App\Core\Boutique;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Zone;

/** Rendu récursif d'une branche. Défini une fois, appelé à chaque étage. */
$branche = static function (array $noeuds, int $profondeur) use (&$branche): void {
    foreach ($noeuds as $n):
        $z        = $n['zone'];
        $id       = (int) $z['id'];
        $decochee = (int) $z['actif'] !== 1;
        ?>
        <tr class="<?= $decochee ? 'text-muted' : '' ?>">
          <td>
            <span style="display:inline-block; width: <?= $profondeur * 1.5 ?>rem;"></span>
            <?php if ($profondeur > 0): ?><span class="text-muted" aria-hidden="true">└</span> <?php endif; ?>
            <strong><?= View::e((string) $z['nom']) ?></strong>
            <?php if (!empty($z['code'])): ?>
              <span class="badge bg-light text-dark ms-1"><?= View::e((string) $z['code']) ?></span>
            <?php endif; ?>
            <span class="d-block text-muted small" style="margin-left: <?= $profondeur * 1.5 + 1 ?>rem;">
              <?= View::e(Zone::niveau((string) $z['niveau'])) ?>
            </span>
          </td>

          <td class="text-end">
            <?php if ($n['frais'] === null): ?>
              <span class="badge bg-warning text-dark">aucun tarif</span>
            <?php elseif ($n['propre']): ?>
              <strong><?= View::e(Boutique::somme((int) $n['frais'])) ?></strong>
            <?php else: ?>
              <span><?= View::e(Boutique::somme((int) $n['frais'])) ?></span>
              <span class="d-block text-muted small">
                hérité <?= View::e(View::de((string) $n['origine']['nom'])) ?>
              </span>
            <?php endif; ?>
          </td>

          <td class="text-center">
            <?php if ($decochee): ?>
              <span class="badge bg-secondary">fermée</span>
            <?php elseif ($n['livrable']): ?>
              <span class="badge bg-success">livrée</span>
            <?php else: ?>
              <span class="badge bg-warning text-dark">sans tarif</span>
            <?php endif; ?>
          </td>

          <td class="text-end pgy-actions">
            <?php if (isset(Zone::SOUS_NIVEAU[(string) $z['niveau']])): ?>
              <a class="btn btn-sm btn-outline-secondary"
                 href="<?= Admin::url('/zones/nouveau') ?>?parent=<?= $id ?>"
                 title="Ajouter une <?= View::e(strtolower(Zone::niveau(Zone::SOUS_NIVEAU[(string) $z['niveau']]))) ?>">
                <i class="mdi mdi-plus" aria-hidden="true"></i>
              </a>
            <?php endif; ?>

            <a class="btn btn-sm btn-outline-primary" href="<?= Admin::url('/zones/' . $id) ?>">Modifier</a>

            <form method="post" action="<?= Admin::url('/zones/' . $id . '/actif') ?>" class="d-inline">
              <?= Csrf::champ() ?>
              <button type="submit" class="btn btn-sm btn-outline-secondary">
                <?= $decochee ? 'Rouvrir' : 'Fermer' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php
        $branche($n['enfants'], $profondeur + 1);
    endforeach;
};
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Boutique</span>
    <h1>Zones de livraison</h1>
    <p>Où l'on livre, et ce que la livraison coûte</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/zones/nouveau') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouveau pays
  </a>
</div>

<?php /* La règle d'héritage, dite une fois et en tête : c'est la seule chose
         à comprendre pour se servir de cet écran. */ ?>
<div class="alert alert-info" role="note">
  <i class="mdi mdi-information-outline me-1" aria-hidden="true"></i>
  <strong>Un tarif laissé vide est hérité du parent.</strong>
  Posez « Côte d'Ivoire », puis seulement les exceptions — Abidjan moins cher que
  le reste du pays, Cocody moins cher qu'Abidjan. Une zone dont ni elle ni ses
  parents ne portent de tarif <strong>n'est pas proposée</strong> à la commande :
  mieux vaut ne pas offrir la livraison quelque part que de la facturer zéro franc.
</div>

<?php if (!$ouvert): ?>
  <div class="alert alert-secondary" role="note">
    <i class="mdi mdi-cart-off me-1" aria-hidden="true"></i>
    Les commandes sont fermées : ces tarifs se préparent, ils ne s'appliquent pas
    encore. L'ouverture se règle à l'écran
    <a href="<?= Admin::url('/parametres') ?>">Paramètres</a>.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
<?php if ($arbre === []): ?>
        <p class="text-muted mb-0">
          Aucune zone. Tant que rien n'est saisi, la livraison n'est proposée nulle
          part et seul le retrait est possible.
        </p>
<?php else: ?>
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Zone</th>
                <th class="text-end">Frais de livraison</th>
                <th class="text-center">État</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $branche($arbre, 0); ?>
            </tbody>
          </table>
        </div>
<?php endif; ?>
      </div>
    </div>
  </div>
</div>
