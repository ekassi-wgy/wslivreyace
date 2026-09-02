<?php
/**
 * Fiche d'une commande : ce que la passerelle a enregistré, et les deux ou
 * trois gestes qui restent à l'administration.
 *
 * Le corps de la fiche est en lecture seule, délibérément. Corriger un montant
 * ou un nom ici ferait diverger la commande de ce que la passerelle a gardé, et
 * c'est elle qui fait foi le jour où un client conteste.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Paiement;
use App\Core\View;
use App\Model\Commande;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id      = (int) $ligne['id'];
$statut  = (string) $ligne['statut'];
$suites  = Commande::SUITES[$statut] ?? [];
$recue   = strtotime((string) $ligne['cree_le']);
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Commandes</span>
    <h1><?= View::e($ligne['reference']) ?></h1>
    <p>
      Reçue le <?= View::e(date('d/m/Y à H\hi', $recue)) ?> &middot;
      <?= (int) $ligne['quantite'] ?> exemplaire<?= $ligne['quantite'] > 1 ? 's' : '' ?> &middot;
      <?= View::e(Commande::montant((float) $ligne['montant'], (string) $ligne['devise'])) ?>
    </p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/commandes') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la liste
  </a>
</div>

<div class="row">
  <div class="col-lg-7 grid-margin">
    <div class="card card-rounded mb-3"><div class="card-body">
      <h4 class="card-title card-title-dash">Client</h4>

      <dl class="pgy-donnees">
        <dt>Nom</dt>
        <dd><?= View::e($ligne['client_nom']) ?></dd>

        <dt>Courriel</dt>
        <dd><a href="mailto:<?= View::e($ligne['client_email']) ?>"><?= View::e($ligne['client_email']) ?></a></dd>

        <dt>Téléphone</dt>
        <dd>
          <?php if (!empty($ligne['client_tel'])): ?>
            <a href="tel:<?= View::e($ligne['client_tel']) ?>"><?= View::e($ligne['client_tel']) ?></a>
          <?php else: ?>
            <span class="text-muted">—</span>
          <?php endif; ?>
        </dd>

        <dt>Remise</dt>
        <dd><?= View::e(Commande::LIVRAISONS[$ligne['livraison']] ?? $ligne['livraison']) ?></dd>

        <?php if (!empty($ligne['adresse'])): ?>
          <dt>Adresse</dt>
          <dd><?= nl2br(View::e($ligne['adresse'])) ?></dd>
        <?php endif; ?>
      </dl>
    </div></div>

    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Note de suivi</h4>
      <p class="card-subtitle card-subtitle-dash">
        Interne, jamais montrée au client : « rappelé le 12, absent », « remis en
        main propre à la dédicace ».
      </p>

      <form method="post" action="<?= Admin::url('/commandes/' . $id . '/note') ?>" class="mt-3" novalidate>
        <?= Csrf::champ() ?>
        <?php champ_zone($valeurs, $erreurs, 'note', 'Note', [
            'lignes' => 3,
            'aide'   => '500 caractères au plus.',
        ]); ?>
        <button type="submit" class="btn btn-outline-secondary">Enregistrer la note</button>
      </form>
    </div></div>
  </div>

  <div class="col-lg-5 grid-margin">
    <div class="card card-rounded mb-3"><div class="card-body">
      <h4 class="card-title card-title-dash">Statut</h4>

      <p>
        <span class="pgy-statut pgy-statut--<?= View::e($statut) ?>">
          <?= View::e(Commande::STATUTS[$statut] ?? $statut) ?>
        </span>
      </p>

      <?php if ($suites === []): ?>
        <p class="form-text mb-0">
          <?= $statut === 'remise'
              ? 'La commande est close : l\'exemplaire est entre les mains du client.'
              : 'Le paiement a échoué. La commande reste en base : elle dit qu\'une tentative a eu lieu.' ?>
        </p>
      <?php else: ?>
        <div class="pgy-temoignage__actions">
          <?php foreach ($suites as $vers): ?>
            <form method="post" action="<?= Admin::url('/commandes/' . $id . '/' . $vers) ?>"
                  <?= $vers === 'echouee'
                      ? 'data-confirmation="Marquer cette commande échouée ? Elle ne pourra plus avancer."'
                      : '' ?>>
              <?= Csrf::champ() ?>
              <button type="submit" class="btn btn-sm <?= $vers === 'echouee' ? 'btn-outline-danger' : 'btn-primary' ?>">
                <?= View::e(Commande::VERBES[$vers] ?? $vers) ?>
              </button>
            </form>
          <?php endforeach; ?>
        </div>

        <p class="form-text mt-3 mb-0">
          <?= $statut === 'initiee'
              ? 'Le back-office ne décide pas d\'un paiement, il en prend acte : ne constatez le paiement qu\'après l\'avoir vu chez ' . View::e(Paiement::nom()) . '.'
              : 'Marquez la remise quand l\'exemplaire a été retiré ou livré.' ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($ligne['remise_le'])): ?>
        <p class="form-text mb-0">
          Remise le <?= View::e(date('d/m/Y à H\hi', strtotime((string) $ligne['remise_le']))) ?>
          <?php if (!empty($ligne['remise_par_nom'])): ?>
            par <?= View::e($ligne['remise_par_nom']) ?>
          <?php endif; ?>.
        </p>
      <?php endif; ?>
    </div></div>

    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Paiement</h4>

      <dl class="pgy-donnees">
        <dt>Montant</dt>
        <dd><?= View::e(Commande::montant((float) $ligne['montant'], (string) $ligne['devise'])) ?></dd>

        <dt>Mode</dt>
        <dd><?= View::e(Paiement::libelleMode($ligne['mode_paiement'])) ?></dd>

        <dt>Passerelle</dt>
        <dd>
          <?= !empty($ligne['passerelle'])
              ? View::e($ligne['passerelle'])
              : '<span class="text-muted">—</span>' ?>
        </dd>

        <dt>Transaction</dt>
        <dd>
          <?php if (!empty($ligne['transaction_ref'])): ?>
            <code><?= View::e($ligne['transaction_ref']) ?></code>
          <?php else: ?>
            <span class="text-muted">—</span>
          <?php endif; ?>
        </dd>
      </dl>

      <p class="form-text mb-0">
        C'est le code de transaction qu'il faut donner à la passerelle pour
        retrouver un paiement contesté.
      </p>
    </div></div>
  </div>
</div>
