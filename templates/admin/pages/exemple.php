<?php
/**
 * Planche de composants — page de contrôle du lot A.
 *
 * Elle rassemble ce que les lots suivants vont réellement employer : tableau
 * de liste, formulaire, boutons, badges de statut, alertes, état vide. Elle
 * sert à juger la teinte du thème sur pièces plutôt que sur description, et
 * à repérer un reste de bleu Bootstrap ou de violet Star Admin.
 *
 * Elle disparaît à la fin du lot C, quand les vraies pages la remplacent.
 */

use App\Core\Admin;
use App\Core\View;

$exemples = [
    ['Le manuscrit remis à l\'éditeur', 'parution',  'publie'],
    ['Séance de dédicace à Abidjan',    'dedicace',  'brouillon'],
    ['Portrait dans Fraternité Matin',  'presse',    'publie'],
];
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Lot A — contrôle</span>
    <h1>Planche de composants</h1>
    <p>Les briques réemployées par les lots suivants, sur la palette du projet.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/') ?>">
    <i class="mdi mdi-arrow-left me-1"></i> Retour
  </a>
</div>

<div class="row">

  <!-- Liste type des futurs écrans de contenu -->
  <div class="col-lg-7 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <h4 class="card-title card-title-dash">Tableau de liste</h4>
        <p class="card-subtitle card-subtitle-dash">Forme des écrans Actualités, Événements, Repères.</p>
        <div class="table-responsive mt-3">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Titre</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Statut</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($exemples as [$titreLigne, $categorie, $statut]): ?>
                <tr>
                  <td class="fw-semibold"><?= View::e($titreLigne) ?></td>
                  <td class="text-muted"><?= View::e($categorie) ?></td>
                  <td><span class="pgy-statut pgy-statut--<?= View::e($statut) ?>"><?= View::e($statut) ?></span></td>
                  <td class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                      <i class="mdi mdi-pencil-outline"></i><span class="visually-hidden">Modifier</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                      <i class="mdi mdi-trash-can-outline"></i><span class="visually-hidden">Supprimer</span>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <h4 class="card-title card-title-dash mt-4">Tous les statuts du schéma</h4>
        <p class="card-subtitle card-subtitle-dash">
          Le libellé porte le sens ; la couleur ne fait que le renforcer.
        </p>
        <div class="d-flex flex-wrap gap-2 mt-2">
          <?php foreach (['brouillon', 'publie', 'en_attente', 'refuse', 'annule'] as $s): ?>
            <span class="pgy-statut pgy-statut--<?= View::e($s) ?>"><?= View::e($s) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Formulaire type -->
  <div class="col-lg-5 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <h4 class="card-title card-title-dash">Formulaire</h4>
        <p class="card-subtitle card-subtitle-dash">
          Inerte : aucun formulaire ne sera soumis avant le jeton CSRF du lot B.
        </p>

        <div class="mb-3">
          <label class="form-label" for="ex-titre">Titre</label>
          <input type="text" class="form-control" id="ex-titre" value="Séance de dédicace" disabled>
        </div>

        <div class="mb-3">
          <label class="form-label" for="ex-cat">Catégorie</label>
          <select class="form-select" id="ex-cat" disabled>
            <option>parution</option><option>dedicace</option><option>presse</option>
            <option>hommage</option><option>evenement</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label" for="ex-corps">Corps</label>
          <textarea class="form-control" id="ex-corps" rows="3" disabled>Texte de l'actualité.</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label" for="ex-source">Source</label>
          <input type="text" class="form-control" id="ex-source" placeholder="Référence de l'archive" disabled>
          <div class="form-text">Le sourçage des faits biographiques est exigé au §6 du cahier des charges.</div>
        </div>

        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary" disabled>Enregistrer</button>
          <button type="button" class="btn btn-outline-secondary" disabled>Annuler</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Alertes et état vide -->
  <div class="col-lg-7 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <h4 class="card-title card-title-dash">Messages</h4>
        <div class="alert alert-success" role="alert">Le repère a été enregistré.</div>
        <div class="alert alert-warning" role="alert">Ce témoignage attend une décision de modération.</div>
        <div class="alert alert-danger" role="alert">Le fichier déposé n'est pas une image.</div>
      </div>
    </div>
  </div>

  <div class="col-lg-5 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        <h4 class="card-title card-title-dash">État vide</h4>
        <div class="pgy-vide">
          <i class="mdi mdi-inbox-outline" aria-hidden="true"></i>
          <p class="mb-1 fw-semibold">Aucun élément pour l'instant</p>
          <p class="mb-0 small">Une liste vide est la règle en début de projet.</p>
        </div>
      </div>
    </div>
  </div>

</div>
