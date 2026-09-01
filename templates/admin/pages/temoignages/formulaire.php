<?php
/**
 * Correction d'un témoignage.
 *
 * Trois champs seulement, et un avertissement : ce sont les mots de quelqu'un
 * d'autre. On corrige une coquille, on retire un numéro de téléphone laissé
 * dans le corps du message — on ne réécrit pas le propos.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Temoignage;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = (int) $ligne['id'];
$statut = (string) $ligne['statut'];
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Témoignages</span>
    <h1>Corriger un témoignage</h1>
    <p>
      Reçu le <?= View::e(date('d/m/Y', strtotime((string) $ligne['soumis_le']))) ?>
      &middot; <span class="pgy-statut pgy-statut--<?= View::e($statut) ?>"><?= View::e(Temoignage::STATUTS[$statut] ?? $statut) ?></span>
    </p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/temoignages') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la file
  </a>
</div>

<div class="alert alert-warning" role="alert">
  <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
  Ce texte est signé par une personne identifiable et parle d'une figure historique
  réelle. La correction porte sur la forme — coquille, coordonnées laissées dans le
  message. <strong>Le propos ne se réécrit pas.</strong>
</div>

<form method="post" action="<?= Admin::url("/temoignages/$id") ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <?php champ_zone($valeurs, $erreurs, 'contenu', 'Témoignage', ['lignes' => 10]); ?>
      </div></div>
    </div>

    <div class="col-lg-4 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Signataire</h4>
        <?php champ_texte($valeurs, $erreurs, 'auteur_nom', 'Nom', ['requis' => true, 'attributs' => 'maxlength="160"']); ?>
        <?php champ_texte($valeurs, $erreurs, 'auteur_fonction', 'Qualité', [
            'attributs' => 'maxlength="200" placeholder="Ancien ministre, petite-fille, historien…"',
        ]); ?>

        <?php if (!empty($ligne['auteur_email'])): ?>
          <p class="form-text mb-0">
            Adresse déclarée : <strong><?= View::e($ligne['auteur_email']) ?></strong><br>
            Elle sert à recontacter le signataire et n'est jamais affichée sur le site.
          </p>
        <?php endif; ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">Enregistrer la correction</button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/temoignages') ?>">Annuler</a>
    <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
  </div>
</form>
