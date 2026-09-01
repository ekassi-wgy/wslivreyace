<?php
/**
 * Liste des repères chronologiques.
 *
 * Classée par année croissante — une frise ne se lit pas à l'envers. Le tri de
 * DataTables est calé sur la même colonne au chargement.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Repere;

$sansSource = 0;
foreach ($lignes as $l) {
    if (empty($l['source'])) { $sansSource++; }
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Repères</h1>
    <p><?= count($lignes) ?> entrée<?= count($lignes) > 1 ? 's' : '' ?> &middot; frise chronologique de la biographie</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/reperes/nouveau') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouveau repère
  </a>
</div>

<?php if ($sansSource > 0): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= $sansSource ?> repère<?= $sansSource > 1 ? 's sont' : ' est' ?> sans source.
    Yacé est une figure historique réelle : le cahier des charges (§6) exige une
    référence pour tout fait biographique, et la publication est refusée sans elle.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-timeline-text-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucun repère pour l'instant</p>
            <p class="mb-3 small">Naissance, formation, mandats, distinctions.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/reperes/nouveau') ?>">Créer le premier</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="1" data-tri-sens="asc">
              <thead>
                <tr>
                  <th scope="col">Année</th>
                  <th scope="col">Tri</th>
                  <th scope="col">Titre</th>
                  <th scope="col">Période</th>
                  <th scope="col">Source</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <tr>
                    <td class="fw-semibold"><?= View::e($ligne['annee']) ?></td>
                    <td class="text-muted"><?= (int) $ligne['tri'] ?></td>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/reperes/' . (int) $ligne['id']) ?>">
                        <?= View::e($ligne['titre']) ?>
                      </a>
                    </td>
                    <td class="text-muted"><?= View::e(Repere::PERIODES[$ligne['periode']] ?? $ligne['periode']) ?></td>
                    <td>
                      <?php if (!empty($ligne['source'])): ?>
                        <span class="pgy-sous" title="<?= View::e($ligne['source']) ?>">
                          <?= View::e(mb_strimwidth((string) $ligne['source'], 0, 40, '…')) ?>
                        </span>
                      <?php else: ?>
                        <span class="pgy-statut pgy-statut--en_attente">à sourcer</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e($ligne['statut']) ?>">
                        <?= View::e(Repere::STATUTS[$ligne['statut']] ?? $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement le repère « ' . $ligne['titre'] . ' » ?';
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
