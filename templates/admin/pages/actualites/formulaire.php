<?php
/**
 * Création et modification d'une actualité.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Actualite;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = $edition ? (int) $ligne['id'] : null;
$action = $edition ? Admin::url('/actualites/' . $id) : Admin::url('/actualites');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Actualités</span>
    <h1><?= $edition ? "Modifier l'actualité" : 'Nouvelle actualité' ?></h1>
    <p>Les champs marqués d'un <span class="pgy-requis">*</span> sont obligatoires.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/actualites') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la liste
  </a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Contenu</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', ['requis' => true, 'attributs' => 'maxlength="200"']); ?>
        <?php champ_zone($valeurs, $erreurs, 'chapo', 'Chapô', [
            'lignes' => 2,
            'aide'   => "Deux lignes d'accroche, reprises sur la liste publique. 400 caractères au plus.",
        ]); ?>
        <?php champ_zone($valeurs, $erreurs, 'contenu', 'Corps du texte', [
            'lignes' => 12,
            'aide'   => 'Une ligne vide sépare deux paragraphes.',
        ]); ?>
      </div></div>
    </div>

    <div class="col-lg-4 grid-margin">
      <div class="card card-rounded mb-3"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>

        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Actualite::STATUTS, ['defaut' => 'brouillon']); ?>
        <?php champ_texte($valeurs, $erreurs, 'publie_le', 'Date de publication', [
            'type' => 'date',
            'aide' => 'Obligatoire dès que le statut passe à « Publié » : la page publique classe par cette date.',
        ]); ?>
        <?php champ_choix($valeurs, $erreurs, 'categorie', 'Catégorie', Actualite::CATEGORIES, ['defaut' => 'parution']); ?>
      </div></div>

      <div class="card card-rounded mb-3"><div class="card-body">
        <h4 class="card-title card-title-dash">Illustration</h4>
        <?php champ_media($valeurs, $erreurs, 'image', 'Image', $medias, [
            'aide' => 'Choisie dans la médiathèque. Déposez-la d\'abord si elle n\'y est pas.',
        ]); ?>
      </div></div>

      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Source</h4>

        <?php champ_texte($valeurs, $erreurs, 'source', 'Organe de presse', [
            'attributs' => 'maxlength="200"',
            'aide'      => 'Obligatoire pour la catégorie « Presse » (CDC §6).',
        ]); ?>
        <?php champ_texte($valeurs, $erreurs, 'source_url', "Lien vers l'article", [
            'type'      => 'url',
            'attributs' => 'maxlength="500" placeholder="https://…"',
        ]); ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : "Créer l'actualité" ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/actualites') ?>">Annuler</a>

    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">
        Fiche n<sup>o</sup> <?= $id ?><?php if (!empty($ligne['slug'])): ?> &middot;
          <code><?= View::e($ligne['slug']) ?></code>
        <?php endif; ?>
      </span>
    <?php endif; ?>
  </div>
</form>

<?php /* La boîte de dialogue du sélecteur, hors du formulaire. */ ?>
<?php require dirname(__DIR__, 2) . '/partials/selecteur-media.php'; ?>
