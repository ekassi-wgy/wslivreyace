<?php
/**
 * Fiche d'une image : métadonnées, publication, suppression.
 *
 * Le fichier lui-même ne se modifie pas. Remplacer l'image sous une fiche
 * changerait sans le dire ce que les pages qui l'affichent montrent au
 * visiteur — on dépose une nouvelle image et on supprime l'ancienne.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Televersement;
use App\Core\View;
use App\Model\Media;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id      = (int) $ligne['id'];
$fichier = (string) $ligne['fichier'];
$depose  = strtotime((string) $ligne['cree_le']);
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Médiathèque</span>
    <h1><?= View::e($ligne['titre'] ?? 'Image') ?></h1>
    <p>
      Déposée le <?= View::e(date('d/m/Y', $depose)) ?>
      <?php if (!empty($ligne['largeur'])): ?>
        &middot; <?= (int) $ligne['largeur'] ?>&thinsp;×&thinsp;<?= (int) $ligne['hauteur'] ?> px
      <?php endif; ?>
      <?php if (!empty($ligne['octets'])): ?>
        &middot; <?= View::e(Televersement::poids((int) $ligne['octets'])) ?>
      <?php endif; ?>
    </p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/medias') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la médiathèque
  </a>
</div>

<form method="post" action="<?= Admin::url('/medias/' . $id) ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-5 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Image</h4>

        <?php /* L'original, pas la vignette : c'est ici qu'on vérifie le
                 cadrage et la netteté avant de publier. */ ?>
        <a href="<?= View::e(Media::url($fichier)) ?>" target="_blank" rel="noopener">
          <img class="pgy-media__apercu"
               src="<?= View::e(Media::url($fichier)) ?>"
               alt="<?= View::e(Media::alternative($ligne)) ?>">
        </a>

        <p class="form-text mt-2 mb-0">
          <code><?= View::e($fichier) ?></code><br>
          Ouvrir dans un nouvel onglet pour voir l'image à sa taille réelle.
        </p>
      </div></div>
    </div>

    <div class="col-lg-7 grid-margin">
      <div class="card card-rounded mb-3"><div class="card-body">
        <h4 class="card-title card-title-dash">Description</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', [
            'requis'    => true,
            'attributs' => 'maxlength="200"',
            'aide'      => "Repère interne : c'est sous ce nom que l'image apparaît dans le sélecteur des fiches.",
        ]); ?>

        <?php champ_zone($valeurs, $erreurs, 'legende', 'Légende', [
            'lignes' => 3,
            'aide'   => 'Affichée sous l\'image en galerie, et servie comme texte de '
                      . 'remplacement aux lecteurs d\'écran. Décrivez ce que l\'on voit.',
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'credit', 'Crédit', [
            'attributs' => 'maxlength="200"',
            'aide'      => 'Obligatoire pour publier : fonds, photographe, ou détenteur des droits (CDC §6).',
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'date_prise', 'Date de prise de vue', [
            'attributs' => 'maxlength="60" placeholder="vers 1965"',
            'aide'      => 'Texte libre : une archive se date rarement au jour près.',
        ]); ?>
      </div></div>

      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Classement et publication</h4>

        <div class="row">
          <div class="col-md-4">
            <?php champ_choix($valeurs, $erreurs, 'categorie', 'Catégorie', Media::CATEGORIES, ['defaut' => 'portrait']); ?>
          </div>
          <div class="col-md-4">
            <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Media::STATUTS, ['defaut' => 'brouillon']); ?>
          </div>
          <div class="col-md-4">
            <?php champ_texte($valeurs, $erreurs, 'ordre', 'Rang', [
                'type'      => 'number',
                'attributs' => 'min="-999" max="999" step="1"',
                'aide'      => 'Croissant dans la galerie. 0 par défaut.',
            ]); ?>
          </div>
        </div>

        <?php if ($usages > 0): ?>
          <p class="form-text mb-0">
            <i class="mdi mdi-link-variant" aria-hidden="true"></i>
            Cette image illustre <?= (int) $usages ?> fiche<?= $usages > 1 ? 's' : '' ?>
            (actualité ou événement). La supprimer les laissera sans image.
          </p>
        <?php endif; ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/medias') ?>">Annuler</a>
    <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
  </div>
</form>

<?php /* Hors du formulaire d'édition : deux <form> ne s'imbriquent pas, et une
         suppression ne doit pas partager son bouton avec un enregistrement. */ ?>
<div class="card card-rounded">
  <div class="card-body">
    <h4 class="card-title card-title-dash">Supprimer</h4>
    <p class="card-subtitle card-subtitle-dash">
      Le fichier et sa vignette sont effacés du serveur. Il n'y a pas de corbeille.
      <?php if ($usages > 0): ?>
        <strong><?= (int) $usages ?> fiche<?= $usages > 1 ? 's' : '' ?></strong>
        perdra<?= $usages > 1 ? 'ont' : '' ?> son illustration.
      <?php endif; ?>
    </p>
    <form method="post" action="<?= Admin::url('/medias/' . $id . '/supprimer') ?>"
          data-confirmation="Supprimer définitivement « <?= View::e($ligne['titre'] ?? '') ?> » ? Le fichier sera effacé du serveur.">
      <?= Csrf::champ() ?>
      <button type="submit" class="btn btn-outline-danger">
        <i class="mdi mdi-trash-can-outline me-1" aria-hidden="true"></i> Supprimer cette image
      </button>
    </form>
  </div>
</div>
