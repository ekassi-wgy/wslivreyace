<?php
/**
 * 403 du back-office : l'écran existe, ce compte n'y a pas accès.
 *
 * Distincte de la 404, et c'est voulu : dire « introuvable » à un éditeur qui
 * demande les commandes lui ferait chercher une adresse mal tapée. Le back-
 * office n'est pas un site public, il n'y a rien à cacher à ses propres
 * utilisateurs — l'écran existe, il faut un autre rôle.
 */

use App\Core\Admin;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Erreur 403</span>
    <h1>Accès refusé</h1>
    <p>Cet écran est réservé aux administrateurs.</p>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <div class="pgy-vide">
          <i class="mdi mdi-lock-outline" aria-hidden="true"></i>
          <p class="mb-3">
            Les comptes et les commandes demandent le rôle d'administrateur — le
            premier parce qu'on y distribue les droits, le second parce qu'il porte
            des coordonnées de clients. Demandez le changement de rôle à un
            administrateur.
          </p>
          <a class="btn btn-primary" href="<?= Admin::url('/') ?>">Revenir au tableau de bord</a>
        </div>
      </div>
    </div>
  </div>
</div>
