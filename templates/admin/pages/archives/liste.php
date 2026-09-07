<?php
/**
 * Liste des notices d'archives (lot G4).
 *
 * Classée par année croissante : un fonds se parcourt dans le sens de
 * l'histoire, à l'inverse d'une liste d'actualités.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Archive;

$sansProvenance = 0;
foreach ($lignes as $l) {
    if (empty($l['credit']) && empty($l['source'])) { $sansProvenance++; }
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Archives</h1>
    <p><?= count($lignes) ?> pièce<?= count($lignes) > 1 ? 's' : '' ?> &middot; le fonds numérique</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/archives/nouvelle') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouvelle archive
  </a>
</div>

<?php if ($sansProvenance > 0): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= $sansProvenance ?> pièce<?= $sansProvenance > 1 ? 's sont' : ' est' ?> sans crédit ni source.
    Le cahier des charges (§6) exige la provenance de toute archive, et la
    publication est refusée sans elle.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-archive-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Le fonds est vide</p>
            <p class="mb-3 small">Photographies, vidéos, discours, documents, presse, correspondances.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/archives/nouvelle') ?>">Verser la première pièce</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="1" data-tri-sens="asc">
              <thead>
                <tr>
                  <th scope="col">Titre</th>
                  <th scope="col">Année</th>
                  <th scope="col">Catégorie</th>
                  <th scope="col">Lieu</th>
                  <th scope="col">Provenance</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <tr>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/archives/' . (int) $ligne['id']) ?>">
                        <?= View::e((string) $ligne['titre']) ?>
                      </a>
                    </td>
                    <td class="text-muted"><?= $ligne['annee'] === null ? '—' : (int) $ligne['annee'] ?></td>
                    <td class="text-muted">
                      <span aria-hidden="true"><?= Archive::signe((string) $ligne['categorie']) ?></span>
                      <?= View::e(Archive::categorie((string) $ligne['categorie'])) ?>
                    </td>
                    <td class="text-muted"><?= View::e((string) ($ligne['lieu'] ?? '')) ?></td>
                    <td>
                      <?php $provenance = trim((string) ($ligne['credit'] ?? $ligne['source'] ?? '')); ?>
                      <?php if ($provenance !== ''): ?>
                        <span class="pgy-sous" title="<?= View::e($provenance) ?>">
                          <?= View::e(mb_strimwidth($provenance, 0, 32, '…')) ?>
                        </span>
                      <?php else: ?>
                        <span class="pgy-statut pgy-statut--en_attente">à sourcer</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e((string) $ligne['statut']) ?>">
                        <?= View::e(Archive::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement l\'archive « ' . $ligne['titre'] . ' » ?';
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
