<?php
/**
 * File de modération des contributions (lot G8).
 *
 * Les entrées en attente remontent en tête quelle que soit leur date : c'est
 * la seule pile sur laquelle le modérateur a quelque chose à faire.
 */

use App\Core\Admin;
use App\Core\DateLisible;
use App\Core\View;
use App\Model\Contribution;

$attente = 0;
foreach ($lignes as $l) {
    if ($l['statut'] === 'en_attente') { $attente++; }
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Modération</span>
    <h1>Contributions</h1>
    <p><?= count($lignes) ?> envoi<?= count($lignes) > 1 ? 's' : '' ?> &middot;
       <?= $attente ?> en attente</p>
  </div>
</div>

<?php if ($attente > 0): ?>
  <div class="alert alert-info" role="status">
    <i class="mdi mdi-inbox-arrow-down me-1" aria-hidden="true"></i>
    <?= $attente ?> contribution<?= $attente > 1 ? 's attendent' : ' attend' ?> votre relecture.
    Leurs fichiers sont en quarantaine : ils ne sont visibles de personne d'autre,
    et n'entreront dans la médiathèque qu'à votre acceptation.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-inbox-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucune contribution pour l'instant</p>
            <p class="mb-0 small">
              Le formulaire est en ligne à l'adresse <code>/contribuer</code>,
              avec un appel visible en tête des Archives.
            </p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="0" data-tri-sens="desc">
              <thead>
                <tr>
                  <th scope="col">Reçue</th>
                  <th scope="col">Contributeur</th>
                  <th scope="col">Description</th>
                  <th scope="col">Pièces</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <?php
                    $id = (int) $ligne['id'];
                    $qui = trim(($ligne['prenom'] ?? '') . ' ' . $ligne['nom']);
                    $n = count(Contribution::fichiers($id));
                  ?>
                  <tr>
                    <td class="text-muted"><?= View::e(DateLisible::longue((string) $ligne['recu_le'])) ?></td>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/contributions/' . $id) ?>">
                        <?= View::e($qui) ?>
                      </a>
                      <span class="pgy-sous d-block"><?= View::e((string) $ligne['email']) ?></span>
                    </td>
                    <td class="pgy-sous">
                      <?= View::e(mb_strimwidth(preg_replace('/\s+/', ' ', (string) $ligne['description']) ?? '', 0, 70, '…')) ?>
                    </td>
                    <td class="text-muted"><?= $n === 0 ? '—' : $n ?></td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e((string) $ligne['statut']) ?>">
                        <?= View::e(Contribution::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <a class="btn btn-sm btn-outline-secondary" href="<?= Admin::url('/contributions/' . $id) ?>">
                        Examiner
                      </a>
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
