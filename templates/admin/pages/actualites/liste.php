<?php
/**
 * Liste des actualités.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Actualite;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Actualités</h1>
    <p><?= count($lignes) ?> entrée<?= count($lignes) > 1 ? 's' : '' ?> &middot; parutions, dédicaces, revue de presse</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/actualites/nouvelle') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouvelle actualité
  </a>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-newspaper-variant-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucune actualité pour l'instant</p>
            <p class="mb-3 small">La première parution, une dédicace, un article de presse.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/actualites/nouvelle') ?>">Créer la première</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste>
              <thead>
                <tr>
                  <th scope="col">Titre</th>
                  <th scope="col">Catégorie</th>
                  <th scope="col">Date</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <tr>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/actualites/' . (int) $ligne['id']) ?>">
                        <?= View::e($ligne['titre']) ?>
                      </a>
                      <?php if (!empty($ligne['source'])): ?>
                        <span class="pgy-sous">— <?= View::e($ligne['source']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-muted"><?= View::e(Actualite::CATEGORIES[$ligne['categorie']] ?? $ligne['categorie']) ?></td>
                    <?php /* data-order : DataTables trierait « 14/03/2026 » comme du texte. */ ?>
                    <td data-order="<?= View::e((string) ($ligne['publie_le'] ?? '')) ?>">
                      <?= $ligne['publie_le'] ? View::e(date('d/m/Y', strtotime((string) $ligne['publie_le']))) : '<span class="text-muted">—</span>' ?>
                    </td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e($ligne['statut']) ?>">
                        <?= View::e(Actualite::STATUTS[$ligne['statut']] ?? $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement l\'actualité « ' . $ligne['titre'] . ' » ?';
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
