<?php
/**
 * Liste des citations (lot G14).
 *
 * Groupée par emplacement, parce que c'est la question que l'éditeur se pose en
 * ouvrant l'écran : qu'est-ce qui paraît sur l'accueil, sur la page du livre,
 * sur la biographie ? La colonne « Affichée » y répond d'un coup d'œil.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Citation;

/*
 * Les emplacements dont le bandeau ne paraît pas. C'est le seul endroit d'où
 * ça se voit : un bandeau muet ne laisse aucune trace dans le back-office, il
 * faut aller le constater sur le site.
 */
$muets = Citation::emplacementsMuets();
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Citations</h1>
    <p><?= count($lignes) ?> citation<?= count($lignes) > 1 ? 's' : '' ?> &middot; une seule paraît par emplacement</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/citations/nouvelle') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouvelle citation
  </a>
</div>

<?php if ($muets !== []): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= count($muets) > 1 ? 'Ces bandeaux ne paraissent pas' : 'Ce bandeau ne paraît pas' ?> sur le site :
    <ul class="mb-0 mt-2">
      <?php foreach ($muets as $emplacement => $raison): ?>
        <li>
          <strong><?= View::e(Citation::EMPLACEMENTS[$emplacement] ?? $emplacement) ?></strong>
          — <?= View::e($raison) ?>.
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="mb-0 mt-2 small">
      Une section sans citation disparaît de la page : elle n'affiche pas un cadre vide.
    </p>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-format-quote-close" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucune citation</p>
            <p class="mb-3 small">
              Les trois bandeaux — accueil, page du livre, biographie — restent
              alors absents du site.
            </p>
            <a class="btn btn-primary" href="<?= Admin::url('/citations/nouvelle') ?>">Créer la première</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="0" data-tri-sens="asc">
              <thead>
                <tr>
                  <th scope="col">Emplacement</th>
                  <th scope="col">Citation</th>
                  <th scope="col">Source</th>
                  <th scope="col">Affichée</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <?php
                    $texte   = preg_replace('/\s+/', ' ', (string) $ligne['texte']) ?? '';
                    $affiche = (int) $ligne['en_avant'] === 1 && (string) $ligne['statut'] === 'publie';
                  ?>
                  <tr>
                    <td class="text-muted">
                      <?= View::e(Citation::EMPLACEMENTS[$ligne['emplacement']] ?? (string) $ligne['emplacement']) ?>
                    </td>
                    <td>
                      <?php /* Le texte tient lieu de titre : c'est par lui qu'on
                               reconnaît la fiche, et il mène donc à l'édition. */ ?>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/citations/' . (int) $ligne['id']) ?>"
                         title="<?= View::e($texte) ?>">
                        <?= View::e(mb_strimwidth($texte, 0, 70, '…')) ?>
                      </a>
                    </td>
                    <td>
                      <?php $source = trim((string) ($ligne['source'] ?? '')); ?>
                      <?php if ($source !== ''): ?>
                        <span class="pgy-sous"><?= View::e(mb_strimwidth($source, 0, 40, '…')) ?></span>
                      <?php else: ?>
                        <span class="pgy-statut pgy-statut--en_attente">à préciser</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ($affiche): ?>
                        <i class="mdi mdi-check-circle text-success" aria-hidden="true"></i>
                        <span class="visually-hidden">Affichée sur le site</span>
                      <?php elseif ((int) $ligne['en_avant'] === 1): ?>
                        <?php /* Mise en avant mais pas publiée : la case ne fait
                                 rien tant que le statut ne suit pas, et le dire
                                 ici évite de chercher pourquoi le site ne montre
                                 rien. */ ?>
                        <span class="pgy-statut pgy-statut--en_attente" title="Le statut doit être « Publié »">
                          en attente de publication
                        </span>
                      <?php else: ?>
                        <span class="text-muted">—</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e((string) $ligne['statut']) ?>">
                        <?= View::e(Citation::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement la citation « '
                              . mb_strimwidth($texte, 0, 50, '…') . ' » ?';
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
