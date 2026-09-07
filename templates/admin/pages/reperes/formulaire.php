<?php
/**
 * Création et modification d'un repère chronologique.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Periode;
use App\Model\Repere;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = $edition ? (int) $ligne['id'] : null;
$action = $edition ? Admin::url('/reperes/' . $id) : Admin::url('/reperes');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Repères</span>
    <h1><?= $edition ? 'Modifier le repère' : 'Nouveau repère' ?></h1>
    <p>Les champs marqués d'un <span class="pgy-requis">*</span> sont obligatoires.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/reperes') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la liste
  </a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Le fait</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', [
            'requis'    => true,
            'attributs' => 'maxlength="200" placeholder="Naissance, Élection à la présidence…"',
        ]); ?>
        <?php champ_zone($valeurs, $erreurs, 'notice', 'Notice', [
            'lignes' => 8,
            'aide'   => "Le texte déplié sous l'entrée de la frise publique.",
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'source', 'Source', [
            'attributs' => 'maxlength="300" placeholder="Archives nationales, cote… / ouvrage, page…"',
            'aide'      => 'Obligatoire pour publier. Un fait attribué à une personne réelle doit être vérifiable (CDC §6).',
        ]); ?>
      </div></div>
    </div>

    <div class="col-lg-4 grid-margin">
      <div class="card card-rounded mb-3"><div class="card-body">
        <h4 class="card-title card-title-dash">Datation</h4>

        <?php champ_texte($valeurs, $erreurs, 'annee', 'Année affichée', [
            'requis'    => true,
            'attributs' => 'maxlength="20" placeholder="1959, v. 1945, 1959-1960…"',
            'aide'      => "Telle qu'elle doit apparaître sur la frise. Une date d'archive est souvent imprécise.",
        ]); ?>
        <?php champ_texte($valeurs, $erreurs, 'tri', 'Année de classement', [
            'type'      => 'number', 'requis' => true,
            'attributs' => 'min="1900" max="2100" step="1" placeholder="1959"',
            'aide'      => "L'année numérique qui sert à ordonner la frise, et qui range "
                         . "l'entrée sous une période de la biographie.",
        ]); ?>

        <?php
        /*
         * La période ne se choisit plus (lot G10) : elle se déduit de l'année
         * de classement. La fiche dit laquelle plutôt que de la faire saisir —
         * un menu déroulant qui répète ce que le champ du dessus dit déjà finit
         * par le contredire.
         */
        $triSaisi = champ_valeur($valeurs, 'tri');
        $periode  = $triSaisi === ''
            ? null
            : Periode::contenant(Periode::listerPubliees(), (int) $triSaisi);
        ?>
        <div class="mb-3">
          <span class="form-label d-block">Période de la biographie</span>
          <?php if ($triSaisi === ''): ?>
            <p class="mb-1 text-muted">—</p>
            <div class="form-text">Elle se déduit de l'année de classement, dès qu'elle est saisie.</div>
          <?php elseif ($periode !== null): ?>
            <p class="mb-1"><?= View::e((string) $periode['titre']) ?>
              <span class="text-muted">(<?= View::e(Periode::annees($periode)) ?>)</span></p>
            <div class="form-text">Déduite de l'année de classement. Pour la changer, changez l'année
              — ou le découpage, dans <a href="<?= Admin::url('/periodes') ?>">Biographie</a>.</div>
          <?php else: ?>
            <p class="mb-1 text-muted">Aucune</p>
            <div class="form-text">Aucune période publiée ne couvre cette année. Le repère paraît
              sur la frise, sans onglet pour le filtrer. Voir <a href="<?= Admin::url('/periodes') ?>">Biographie</a>.</div>
          <?php endif; ?>
        </div>
      </div></div>

      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Repere::STATUTS, ['defaut' => 'brouillon']); ?>
        <?php champ_case($valeurs, $erreurs, 'en_avant', "Afficher sur la page d'accueil", [
            'aide' => "L'accueil ne montre que quelques jalons ; la biographie affiche la frise entière. "
                    . 'Sans publication, cette case ne fait rien.',
        ]); ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : 'Créer le repère' ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/reperes') ?>">Annuler</a>

    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>
