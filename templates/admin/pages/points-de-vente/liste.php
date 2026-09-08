<?php
/**
 * Liste des points de vente (lot G12).
 *
 * Classée par rang puis par ville : c'est l'ordre de la section « Où se
 * procurer l'ouvrage » sur l'accueil et sur la page du livre, et c'est ce que
 * l'éditeur vient régler.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\PointDeVente;

/*
 * Les fiches qui ne disent encore ni l'enseigne ni l'adresse. Elles paraissent
 * en ligne sous « Enseigne et adresse à renseigner » : c'est exactement l'état
 * dans lequel le lot les a trouvées, et le rappeler ici est le seul moyen que
 * quelqu'un s'en occupe.
 */
$aRenseigner = 0;
foreach ($lignes as $l) {
    if ((string) $l['statut'] === 'publie' && empty($l['enseigne']) && empty($l['adresse'])) {
        $aRenseigner++;
    }
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Points de vente</h1>
    <p><?= count($lignes) ?> point<?= count($lignes) > 1 ? 's' : '' ?> de vente &middot; où l'on trouve le livre</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/points-de-vente/nouveau') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouveau point de vente
  </a>
</div>

<?php if ($aRenseigner > 0): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= $aRenseigner ?> point<?= $aRenseigner > 1 ? 's de vente publiés affichent' : ' de vente publié affiche' ?>
    « Enseigne et adresse à renseigner » sur l'accueil et sur la page du livre.
    Une ville seule ne dit pas où aller.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-store-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucun point de vente</p>
            <p class="mb-3 small">
              La section « Où se procurer l'ouvrage » ne montre alors que le bouton
              de commande en ligne.
            </p>
            <a class="btn btn-primary" href="<?= Admin::url('/points-de-vente/nouveau') ?>">Créer le premier</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="1" data-tri-sens="asc">
              <thead>
                <tr>
                  <th scope="col">Ville</th>
                  <th scope="col">Rang</th>
                  <th scope="col">Enseigne</th>
                  <th scope="col">Adresse</th>
                  <th scope="col">Contact</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <tr>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/points-de-vente/' . (int) $ligne['id']) ?>">
                        <?= View::e((string) $ligne['ville']) ?>
                      </a>
                    </td>
                    <td class="text-muted"><?= (int) $ligne['ordre'] ?></td>
                    <td>
                      <?php $enseigne = trim((string) ($ligne['enseigne'] ?? '')); ?>
                      <?php if ($enseigne !== ''): ?>
                        <span class="text-muted"><?= View::e($enseigne) ?></span>
                      <?php else: ?>
                        <span class="pgy-statut pgy-statut--en_attente">à renseigner</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php $adresse = trim((string) ($ligne['adresse'] ?? '')); ?>
                      <?php if ($adresse !== ''): ?>
                        <span class="pgy-sous" title="<?= View::e($adresse) ?>">
                          <?= View::e(mb_strimwidth($adresse, 0, 40, '…')) ?>
                        </span>
                      <?php else: ?>
                        <span class="pgy-statut pgy-statut--en_attente">à renseigner</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-muted">
                      <?php
                        /* Le téléphone et le site tiennent dans une colonne : ce
                           sont deux facultatifs qu'on lit d'un coup d'œil, et
                           deux colonnes de plus feraient déborder le tableau. */
                        $contact = array_filter([
                            trim((string) ($ligne['telephone'] ?? '')),
                            trim((string) ($ligne['url'] ?? '')) !== '' ? 'site' : '',
                        ]);
                      ?>
                      <?= $contact === [] ? '—' : View::e(implode(' · ', $contact)) ?>
                    </td>
                    <td>
                      <span class="pgy-statut pgy-statut--<?= View::e((string) $ligne['statut']) ?>">
                        <?= View::e(PointDeVente::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement le point de vente « ' . $ligne['ville'] . ' » ?';
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
