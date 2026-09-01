<?php
/**
 * Création et modification d'un événement.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Evenement;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = $edition ? (int) $ligne['id'] : null;
$action = $edition ? Admin::url('/evenements/' . $id) : Admin::url('/evenements');

/**
 * `datetime-local` n'accepte que « AAAA-MM-JJTHH:MM » ; MySQL rend
 * « AAAA-MM-JJ HH:MM:SS ». Sans cette conversion, rouvrir une fiche affichait
 * un champ vide et l'enregistrer effaçait la date.
 */
$pourChamp = static function (array $valeurs, string $nom): string {
    $v = (string) ($valeurs[$nom] ?? '');
    if ($v === '') {
        return '';
    }
    $t = strtotime($v);
    return $t === false ? $v : date('Y-m-d\TH:i', $t);
};
$valeurs['debut_le'] = $pourChamp($valeurs, 'debut_le');
$valeurs['fin_le']   = $pourChamp($valeurs, 'fin_le');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Événements</span>
    <h1><?= $edition ? "Modifier l'événement" : 'Nouvel événement' ?></h1>
    <p>Les champs marqués d'un <span class="pgy-requis">*</span> sont obligatoires.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/evenements') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la liste
  </a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">L'événement</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', ['requis' => true, 'attributs' => 'maxlength="200"']); ?>
        <?php champ_zone($valeurs, $erreurs, 'description', 'Description', ['lignes' => 8]); ?>

        <div class="row">
          <div class="col-md-6">
            <?php champ_texte($valeurs, $erreurs, 'ville', 'Ville', [
                'attributs' => 'maxlength="120"',
                'aide'      => 'Obligatoire dès que le statut passe à « Publié ».',
            ]); ?>
          </div>
          <div class="col-md-6">
            <?php champ_texte($valeurs, $erreurs, 'lieu', 'Lieu précis', [
                'attributs' => 'maxlength="200" placeholder="Librairie, salle, adresse…"',
            ]); ?>
          </div>
        </div>

        <?php champ_texte($valeurs, $erreurs, 'inscription_url', "Lien d'inscription", [
            'type'      => 'url',
            'attributs' => 'maxlength="500" placeholder="https://…"',
        ]); ?>
      </div></div>
    </div>

    <div class="col-lg-4 grid-margin">
      <div class="card card-rounded mb-3"><div class="card-body">
        <h4 class="card-title card-title-dash">Dates</h4>

        <?php champ_texte($valeurs, $erreurs, 'debut_le', 'Début', [
            'type' => 'datetime-local', 'requis' => true,
        ]); ?>
        <?php champ_texte($valeurs, $erreurs, 'fin_le', 'Fin', [
            'type' => 'datetime-local',
            'aide' => 'Facultatif. Doit être postérieure au début.',
        ]); ?>
      </div></div>

      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Evenement::STATUTS, ['defaut' => 'brouillon']); ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : "Créer l'événement" ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/evenements') ?>">Annuler</a>

    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">
        Fiche n<sup>o</sup> <?= $id ?><?php if (!empty($ligne['slug'])): ?> &middot;
          <code><?= View::e($ligne['slug']) ?></code>
        <?php endif; ?>
      </span>
    <?php endif; ?>
  </div>
</form>
