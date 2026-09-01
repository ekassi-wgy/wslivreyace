<?php
/**
 * Liste des événements.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Evenement;

$maintenant = time();
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Événements</h1>
    <p><?= count($lignes) ?> entrée<?= count($lignes) > 1 ? 's' : '' ?> &middot; dédicaces, colloques, hommages</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/evenements/nouveau') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouvel événement
  </a>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-calendar-star" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucun événement pour l'instant</p>
            <p class="mb-3 small">Une séance de dédicace, une présentation, un hommage.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/evenements/nouveau') ?>">Créer le premier</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste>
              <thead>
                <tr>
                  <th scope="col">Titre</th>
                  <th scope="col">Quand</th>
                  <th scope="col">Où</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <?php $debut = strtotime((string) $ligne['debut_le']); ?>
                  <tr>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/evenements/' . (int) $ligne['id']) ?>">
                        <?= View::e($ligne['titre']) ?>
                      </a>
                      <?php if ($debut !== false && $debut < $maintenant): ?>
                        <span class="pgy-sous">— passé</span>
                      <?php endif; ?>
                    </td>
                    <td data-order="<?= View::e((string) $ligne['debut_le']) ?>">
                      <?= $debut !== false ? View::e(date('d/m/Y \à H\hi', $debut)) : '<span class="text-muted">—</span>' ?>
                    </td>
                    <td class="text-muted">
                      <?= $ligne['ville'] ? View::e($ligne['ville']) : '—' ?>
                      <?php if (!empty($ligne['lieu'])): ?>
                        <span class="pgy-sous"><?= View::e($ligne['lieu']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e($ligne['statut']) ?>">
                        <?= View::e(Evenement::STATUTS[$ligne['statut']] ?? $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement l\'événement « ' . $ligne['titre'] . ' » ?';
                            require dirname(__DIR__, 2) . '/partials/actions-liste.php'; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
