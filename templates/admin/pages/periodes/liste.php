<?php
/**
 * Liste des périodes de la biographie (lot G10).
 *
 * Classée chronologiquement, les périodes non datées en fin de liste : c'est
 * l'ordre du récit public, et c'est ce que l'éditeur vient régler.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Periode;
use App\Model\Repere;

$sansSource = 0;

foreach ($lignes as $l) {
    if (empty($l['source'])) { $sansSource++; }
}

/*
 * Les repères que le découpage laisse dehors.
 *
 * **C'est le seul contrôle que cet écran ne peut pas faire au moment de la
 * saisie**, et le seul qui se voie sur le site : un jalon dont l'année ne
 * tombe dans aucune période publiée paraît bien sur la frise, mais aucun
 * onglet ne le filtre et aucune page de période ne le montre. Rien n'est
 * cassé — il manque une période, ou une borne est mal posée, et personne ne
 * s'en apercevrait sans cette ligne.
 */
$publiees = Periode::listerPubliees();
$orphelins = 0;

foreach (Repere::listerPubliees() as $r) {
    if (Periode::contenant($publiees, (int) $r['tri']) === null) { $orphelins++; }
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Biographie</h1>
    <p><?= count($lignes) ?> période<?= count($lignes) > 1 ? 's' : '' ?> &middot;
       le récit par périodes, une page par période</p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/periodes/nouvelle') ?>">
    <i class="mdi mdi-plus me-1" aria-hidden="true"></i> Nouvelle période
  </a>
</div>

<?php if ($sansSource > 0): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= $sansSource ?> période<?= $sansSource > 1 ? 's sont' : ' est' ?> sans source.
    La biographie est le seul endroit du site où l'on écrit la vie d'une personne
    réelle en continu : le cahier des charges (§6) exige la référence, et la
    publication est refusée sans elle.
  </div>
<?php endif; ?>

<?php if ($orphelins > 0): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-timeline-alert-outline me-1" aria-hidden="true"></i>
    <?php $pluriel = $orphelins > 1; ?>
    <?= $orphelins ?> repère<?= $pluriel ? 's publiés ne tombent' : ' publié ne tombe' ?> dans
    aucune période publiée&nbsp;: <?= $pluriel ? 'ils paraissent' : 'il paraît' ?> sur la frise,
    mais aucun onglet ne <?= $pluriel ? 'les' : 'le' ?> filtre et aucune page de période ne
    <?= $pluriel ? 'les' : 'le' ?> montre.
    <a href="<?= Admin::url('/reperes') ?>">Voir les repères</a>.
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <?php if ($lignes === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-book-open-page-variant-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">Aucune période pour l'instant</p>
            <p class="mb-3 small">Le découpage du parcours, période par période, chacune à son adresse.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/periodes/nouvelle') ?>">Créer la première</a>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table pgy-table" data-liste data-tri-colonne="1" data-tri-sens="asc">
              <thead>
                <tr>
                  <th scope="col">Titre</th>
                  <th scope="col">Années</th>
                  <th scope="col">Repères</th>
                  <th scope="col">Source</th>
                  <th scope="col">Statut</th>
                  <th scope="col" data-orderable="false">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lignes as $ligne): ?>
                  <tr>
                    <td>
                      <a class="pgy-lien-fiche" href="<?= Admin::url('/periodes/' . (int) $ligne['id']) ?>">
                        <?= View::e((string) $ligne['titre']) ?>
                      </a>
                    </td>
                    <td class="text-muted">
                      <?php $annees = Periode::annees($ligne); ?>
                      <?php if ($annees !== ''): ?>
                        <?= View::e($annees) ?>
                      <?php else: ?>
                        <?php /* Sans bornes, la période ne peut pas être publiée : la colonne
                                 le dit ici plutôt qu'au moment où l'enregistrement est refusé. */ ?>
                        <span class="pgy-statut pgy-statut--en_attente">à dater</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-muted">
                      <?php /* Combien de jalons de la frise cette période traverse. Le compte
                               se fait sur les bornes, rien n'est rattaché à la main. */ ?>
                      <?php if (empty($ligne['debut']) || empty($ligne['fin'])): ?>
                        <span aria-hidden="true">—</span>
                      <?php else: ?>
                        <?= count(Repere::entreAnnees((int) $ligne['debut'], (int) $ligne['fin'])) ?>
                      <?php endif; ?>
                    </td>
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
                        <?= View::e(Periode::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?>
                      </span>
                    </td>
                    <td>
                      <?php $confirmation = 'Supprimer définitivement la période « ' . $ligne['titre'] . ' » ?';
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
