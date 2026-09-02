<?php
/**
 * Suivi des commandes.
 *
 * Pas de bouton « nouvelle commande » : une commande naît du tunnel de
 * paiement, pas d'ici. L'écran suit, il ne crée pas.
 */

use App\Core\Admin;
use App\Core\Paiement;
use App\Core\View;
use App\Model\Commande;

$onglets = ['tous' => 'Toutes'] + Commande::STATUTS;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Administration</span>
    <h1>Commandes</h1>
    <p>
      <?= (int) $compteurs['tous'] ?> commande<?= $compteurs['tous'] > 1 ? 's' : '' ?> &middot;
      paiements par <?= View::e(Paiement::nom()) ?>
    </p>
  </div>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Encaissé</h4>
      <p class="card-subtitle card-subtitle-dash">
        Commandes payées et remises. Une commande initiée n'a rien encaissé, une
        échouée non plus. Les devises ne sont pas additionnées entre elles.
      </p>

      <?php if ($encaisse === []): ?>
        <p class="text-muted mb-0 mt-3">Rien encore.</p>
      <?php else: ?>
        <div class="statistics-details d-flex align-items-center justify-content-between mt-4">
          <?php foreach ($encaisse as $total): ?>
            <div>
              <p class="statistics-title">Recettes (<?= View::e($total['devise']) ?>)</p>
              <h3 class="rate-percentage"><?= View::e(Commande::montant($total['montant'], $total['devise'])) ?></h3>
              <p class="text-muted mb-0 small">
                <?= (int) $total['commandes'] ?> commande<?= $total['commandes'] > 1 ? 's' : '' ?>
              </p>
            </div>
            <div>
              <p class="statistics-title">Exemplaires</p>
              <h3 class="rate-percentage"><?= (int) $total['exemplaires'] ?></h3>
              <p class="text-muted mb-0 small">vendus</p>
            </div>
          <?php endforeach; ?>
          <div class="d-none d-md-block">
            <p class="statistics-title">À remettre</p>
            <h3 class="rate-percentage"><?= (int) $compteurs['payee'] ?></h3>
            <p class="text-muted mb-0 small">payées, pas encore remises</p>
          </div>
        </div>
      <?php endif; ?>
    </div></div>
  </div>
</div>

<div class="pgy-onglets" role="tablist" aria-label="Filtrer par statut">
  <?php foreach ($onglets as $cle => $libelle): ?>
    <?php $n = $cle === 'tous' ? $compteurs['tous'] : ($compteurs[$cle] ?? 0); ?>
    <a class="pgy-onglet<?= $filtre === $cle ? ' is-actif' : '' ?>"
       href="<?= Admin::url('/commandes') . ($cle === 'tous' ? '' : '?statut=' . $cle) ?>"
       <?= $filtre === $cle ? 'aria-current="page"' : '' ?>>
      <?= View::e($libelle) ?>
      <span class="pgy-onglet__n"><?= (int) $n ?></span>
    </a>
  <?php endforeach; ?>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded"><div class="card-body">

      <?php if ($lignes === []): ?>
        <div class="pgy-vide">
          <i class="mdi mdi-package-variant-closed" aria-hidden="true"></i>
          <p class="mb-1 fw-semibold">
            <?= $compteurs['tous'] === 0 ? 'Aucune commande' : 'Aucune commande dans ce statut' ?>
          </p>
          <p class="mb-0 small">
            <?php if ($compteurs['tous'] === 0): ?>
              Les commandes arriveront du tunnel de paiement, qui reste à écrire —
              il suppose les pages publiques de la boutique. La passerelle est
              arrêtée : <?= View::e(Paiement::nom()) ?>.
            <?php else: ?>
              Essayez un autre filtre.
            <?php endif; ?>
          </p>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table pgy-table" data-liste data-tri-colonne="4" data-tri-sens="desc">
            <thead>
              <tr>
                <th scope="col">Référence</th>
                <th scope="col">Client</th>
                <th scope="col">Montant</th>
                <th scope="col">Paiement</th>
                <th scope="col">Reçue le</th>
                <th scope="col">Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lignes as $ligne): ?>
                <?php $id = (int) $ligne['id']; ?>
                <tr>
                  <td>
                    <a class="pgy-lien-fiche" href="<?= Admin::url('/commandes/' . $id) ?>">
                      <?= View::e($ligne['reference']) ?>
                    </a>
                    <?php if ((int) $ligne['quantite'] > 1): ?>
                      <span class="pgy-sous">— <?= (int) $ligne['quantite'] ?> ex.</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?= View::e($ligne['client_nom']) ?>
                    <span class="pgy-sous d-block"><?= View::e($ligne['client_email']) ?></span>
                  </td>
                  <?php /* data-order : le tri doit porter sur le nombre, pas sur
                           « 25 000 F CFA » lu comme du texte. */ ?>
                  <td data-order="<?= View::e((string) $ligne['montant']) ?>">
                    <?= View::e(Commande::montant((float) $ligne['montant'], (string) $ligne['devise'])) ?>
                  </td>
                  <td class="text-muted"><?= View::e(Paiement::libelleMode($ligne['mode_paiement'])) ?></td>
                  <td data-order="<?= View::e((string) $ligne['cree_le']) ?>">
                    <?= View::e(date('d/m/Y', strtotime((string) $ligne['cree_le']))) ?>
                  </td>
                  <td>
                    <span class="pgy-statut pgy-statut--<?= View::e($ligne['statut']) ?>">
                      <?= View::e(Commande::STATUTS[$ligne['statut']] ?? $ligne['statut']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </div></div>
  </div>
</div>
