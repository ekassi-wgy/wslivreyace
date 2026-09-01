<?php
/**
 * 404 du back-office. Distincte de celle du site public : elle garde la barre
 * latérale, parce qu'une URL fautive en interne se corrige en cliquant, pas
 * en revenant à l'accueil du site.
 */

use App\Core\Admin;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Erreur 404</span>
    <h1>Page introuvable</h1>
    <p>Cette adresse ne correspond à aucun écran du back-office.</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <div class="pgy-vide">
          <i class="mdi mdi-map-marker-question-outline" aria-hidden="true"></i>
          <p class="mb-3">
            L'écran demandé n'existe pas, ou fait partie d'un lot non encore livré —
            les entrées verrouillées de la barre latérale.
          </p>
          <a class="btn btn-primary" href="<?= Admin::url('/') ?>">Revenir au tableau de bord</a>
        </div>
      </div>
    </div>
  </div>
</div>
