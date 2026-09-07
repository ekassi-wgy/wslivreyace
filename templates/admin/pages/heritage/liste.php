<?php
/**
 * Liste des sujets d'Héritage (lot G7).
 *
 * Classée par rubrique puis par rang : c'est l'ordre de l'index public, et
 * c'est ce que l'éditeur vient régler.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Heritage;

$sansProvenance = 0;
foreach ($lignes as $l) {
    if (empty($l['source'])) { $sansProvenance++; }
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Héritage</h1>
    <p><?= count($lignes) ?> sujet<?= count($lignes) > 1 ? 's' : '' ?> &middot; ce qui perpétue sa mémoire</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/heritage/nouveau') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouveau sujet
  </a>
</div>

<?php if ($sansProvenance > 0): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= $sansProvenance ?> sujet<?= $sansProvenance > 1 ? 's sont' : ' est' ?> sans source.
    Une liste de décorations ou d'hommages se recopie avec ses erreurs : le
    cahier des charges (§6) exige la référence, et la publication est refusée
    sans elle.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-bank-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucun sujet pour l'instant</p>
            <p class="mb-3 small">Pont, boulevard, buste, décorations, publications, musique.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/heritage/nouveau') ?>">Créer le premier</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="0" data-tri-sens="asc">
              <thead>
                <tr>
                  <th scope="col">Titre</th>
                  <th scope="col">Rubrique</th>
                  <th scope="col">Rang</th>
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
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/heritage/' . (int) $ligne['id']) ?>">
                        <?= View::e((string) $ligne['titre']) ?>
                      </a>
                    </td>
                    <td class="text-muted"><?= View::e(Heritage::rubrique((string) $ligne['rubrique'])) ?></td>
                    <td class="text-muted"><?= (int) $ligne['ordre'] ?></td>
                    <td class="text-muted"><?= View::e((string) ($ligne['lieu'] ?? '')) ?></td>
                    <td>
                      <?php $provenance = trim((string) ($ligne['source'] ?? '')); ?>
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
                        <?= View::e(Heritage::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement le sujet « ' . $ligne['titre'] . ' » ?';
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
