<?php
/**
 * Fiche d'un point de vente (lot G12).
 *
 * Un seul champ obligatoire, la ville : c'est le grand caractère de la carte
 * publique, et souvent la seule chose qu'on sache au moment où l'on crée la
 * fiche. Tout le reste se complète quand l'information arrive — un champ vide
 * ne s'affiche pas sur le site.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\PointDeVente;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id = $edition ? (int) $ligne['id'] : null;

$action = $edition ? Admin::url('/points-de-vente/' . $id) : Admin::url('/points-de-vente');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1><?= View::e($titre) ?></h1>
    <p>Une librairie, une enseigne, un lieu de dédicace : où l'on se procure l'ouvrage.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/points-de-vente') ?>">Retour à la liste</a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Le point de vente</h4>

        <div class="row">
          <div class="col-md-8"><?php champ_texte($valeurs, $erreurs, 'ville', 'Ville', [
            'requis' => true,
            'aide'   => "Le grand caractère de la carte : « Abidjan », « Yamoussoukro », « Paris ».",
          ]); ?></div>
          <div class="col-md-4"><?php champ_texte($valeurs, $erreurs, 'ordre', 'Ordre', [
            'type' => 'number',
            'aide' => "Rang d'affichage. 0 en premier.",
          ]); ?></div>
        </div>

        <?php champ_texte($valeurs, $erreurs, 'enseigne', 'Enseigne', [
          'aide' => "Le nom du commerce : « Librairie de France », « Fnac Ternes ».",
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'adresse', 'Adresse', [
          'aide' => "En toutes lettres, telle qu'on la donnerait de vive voix. En Côte d'Ivoire, un repère vaut souvent mieux qu'un numéro.",
        ]); ?>

        <div class="row">
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'telephone', 'Téléphone', [
            'aide' => "Tel qu'il doit se lire — « +225 27 22 44 55 66 ». La page en tire le lien d'appel.",
          ]); ?></div>
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'url', 'Site', [
            'type' => 'url',
            'aide' => "Adresse complète, avec <code>https://</code>. L'enseigne devient alors un lien.",
          ]); ?></div>
        </div>
      </div></div>
    </div>

    <div class="col-lg-4">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', PointDeVente::STATUTS, ['defaut' => 'brouillon']); ?>
        <p class="text-muted small mb-0">
          Un point de vente publié paraît sur l'accueil et sur la page du livre,
          sous « Où se procurer l'ouvrage ». Sans enseigne ni adresse, la carte
          affiche « Enseigne et adresse à renseigner ».
        </p>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : 'Créer le point de vente' ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/points-de-vente') ?>">Annuler</a>
    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>
