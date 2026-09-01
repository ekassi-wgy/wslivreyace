<?php
/**
 * Tableau de bord — état du lot A.
 *
 * Les compteurs sont volontairement vides : ils supposent une lecture de la
 * base, qui vient au lot E. Afficher des chiffres inventés en attendant
 * donnerait un écran plus flatteur et strictement mensonger.
 */

use App\Core\Admin;
use App\Core\View;

$lots = [
    ['A', 'Ossature',                'Thème élagué, mise en page, barre latérale, routage',        'livré'],
    ['B', 'Authentification',        'Connexion, session, jeton CSRF, validation, garde de route', 'livré'],
    ['C', 'Contenus',                'Actualités, événements, repères biographiques',              'livré'],
    ['D', 'Modération et médias',    'File des témoignages, téléversement contrôlé',               'à venir'],
    ['E', 'Pilotage',                'Compteurs réels, paramètres, comptes, commandes',            'à venir'],
];
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Back-office</span>
    <h1>Tableau de bord</h1>
    <p>Philippe Grégoire Yacé — <i>Une destinée</i></p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/actualites') ?>">
    <i class="mdi mdi-newspaper-variant-outline me-1" aria-hidden="true"></i> Gérer les actualités
  </a>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">
        <h4 class="card-title card-title-dash">Contenus publiés</h4>
        <p class="card-subtitle card-subtitle-dash">
          Branchés sur la base au lot E. Rien n'est affiché tant que rien n'est lu.
        </p>
        <div class="statistics-details d-flex align-items-center justify-content-between mt-4">
          <?php foreach (['Actualités', 'Événements', 'Repères', 'Témoignages', 'Médias'] as $i => $libelle): ?>
            <div<?= $i >= 3 ? ' class="d-none d-md-block"' : '' ?>>
              <p class="statistics-title"><?= View::e($libelle) ?></p>
              <h3 class="rate-percentage text-muted">—</h3>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">
        <h4 class="card-title card-title-dash">Livraison du back-office</h4>
        <p class="card-subtitle card-subtitle-dash">
          Les entrées de menu verrouillées correspondent aux lots restants.
        </p>
        <div class="table-responsive mt-3">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">Lot</th>
                <th scope="col">Objet</th>
                <th scope="col">Contenu</th>
                <th scope="col">État</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lots as [$cle, $objet, $detail, $etat]): ?>
                <tr>
                  <td class="fw-bold"><?= View::e($cle) ?></td>
                  <td class="fw-semibold"><?= View::e($objet) ?></td>
                  <td class="text-muted"><?= View::e($detail) ?></td>
                  <td>
                    <span class="pgy-statut pgy-statut--<?= $etat === 'livré' ? 'publie' : 'brouillon' ?>">
                      <?= View::e($etat) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
