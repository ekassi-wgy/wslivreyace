<?php
/**
 * La liste des contenus d'une rubrique, avec l'état de chacun (lot G11).
 *
 * L'intitulé affiché est le **premier champ traduisible** de l'entité — son
 * titre, dans tous les cas — pris en français : c'est par lui qu'un traducteur
 * reconnaît la ligne qu'il cherche.
 */

use App\Core\Admin;
use App\Core\View;

$total = count($def['champs']);
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">
      <a href="<?= Admin::url('/traductions') ?>">Traductions</a>
    </span>
    <h1><?= View::e($def['titre']) ?></h1>
    <p><?= count($lignes) ?> contenu<?= count($lignes) > 1 ? 's' : '' ?>
       &middot; <?= $total ?> champ<?= $total > 1 ? 's' : '' ?> traduisible<?= $total > 1 ? 's' : '' ?> par contenu</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/traductions') ?>">Retour</a>
</div>

<?php if ($lignes === []): ?>
  <div class="alert alert-secondary" role="status">
    Rien à traduire pour le moment : cette rubrique est vide.
  </div>
<?php else: ?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Contenu</th>
<?php foreach ($langues as $code => $infos): ?>
                <th class="text-end"><?= View::e($infos['nom']) ?></th>
<?php endforeach; ?>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($lignes as $l): ?>
              <?php $id = (int) $l['id']; ?>
              <tr>
                <td>
                  <?php $intitule = trim((string) ($l['intitule'] ?? '')); ?>
                  <?= $intitule === ''
                        ? '<span class="text-muted">Sans titre</span>'
                        : View::e($intitule) ?>
                  <span class="d-block text-muted small">n<sup>o</sup> <?= $id ?></span>
                </td>
<?php foreach (array_keys($langues) as $code): ?>
                <td class="text-end">
                  <?php $n = $faites[$id][$code] ?? 0; ?>
                  <?php if ($n === 0): ?>
                    <span class="text-muted">à traduire</span>
                  <?php elseif ($n >= $total): ?>
                    <span class="badge bg-success">complet</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark"><?= $n ?> / <?= $total ?></span>
                  <?php endif; ?>
                </td>
<?php endforeach; ?>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary"
                     href="<?= Admin::url('/traductions/' . $entite . '/' . $id) ?>">Traduire</a>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>
