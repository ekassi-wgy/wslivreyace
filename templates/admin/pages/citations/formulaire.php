<?php
/**
 * Fiche d'une citation (lot G14).
 *
 * Trois champs seulement — où, quoi, d'où — plus le régime de publication.
 *
 * **L'aide du champ Source suit l'emplacement**, parce qu'ils n'attendent pas
 * la même chose : les deux « Extrait » citent l'ouvrage, leur source est un
 * chapitre ; celui de la biographie cite Yacé, sa source est un lieu et une
 * date. Un seul libellé pour les deux aurait fait saisir l'un à la place de
 * l'autre.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Citation;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = $edition ? (int) $ligne['id'] : null;
$action = $edition ? Admin::url('/citations/' . $id) : Admin::url('/citations');

/*
 * L'aide du champ Source, celle de l'emplacement en cours de saisie. À la
 * création, aucun emplacement n'est encore choisi : les deux formes sont donc
 * annoncées ensemble plutôt que d'en imposer une au hasard.
 */
$emplacementCourant = champ_valeur($valeurs, 'emplacement');
$aideSource = Citation::AIDES_SOURCE[$emplacementCourant]
    ?? 'Selon l\'emplacement : le chapitre de l\'ouvrage, ou le lieu et la date du propos.';
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1><?= View::e($titre) ?></h1>
    <p>Une phrase de l'ouvrage, ou un propos de Philippe Grégoire Yacé.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/citations') ?>">Retour à la liste</a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">La citation</h4>

        <?php champ_choix($valeurs, $erreurs, 'emplacement', 'Emplacement', Citation::EMPLACEMENTS, [
          'aide' => 'Le bandeau où la citation paraîtra. Chacun n\'en montre qu\'une à la fois.',
        ]); ?>

        <?php /* `champ_zone` n'a pas d'option `requis` — c'est le validateur du
                 contrôleur qui l'impose, et l'aide le dit plutôt qu'une
                 astérisque que le gabarit ne saurait pas poser. */ ?>
        <?php champ_zone($valeurs, $erreurs, 'texte', 'Texte', [
          'lignes' => 5,
          /* Le texte est rendu tel quel : `.quote` n'ajoute pas de guillemets,
             ni sur ce bandeau ni sur celui de la préface, qui partage la même
             classe. Le dire, plutôt que de laisser l'éditeur les découvrir
             absents après publication. */
          'aide'   => 'Le texte s\'affiche tel quel, en grands caractères — les guillemets '
                    . 'ne sont pas ajoutés, mettez-en si vous en voulez. La ligne est courte '
                    . 'à cet endroit : une phrase qui porte vaut mieux qu\'un paragraphe entier.',
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'source', 'Source', [
          'aide' => $aideSource,
        ]); ?>
      </div></div>
    </div>

    <div class="col-lg-4">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Citation::STATUTS, ['defaut' => 'brouillon']); ?>
        <?php champ_case($valeurs, $erreurs, 'en_avant', 'Afficher cette citation sur le site', [
          'aide' => '<strong>Une seule citation par emplacement.</strong> Cocher cette case '
                  . 'décoche automatiquement celle qui occupait la place — elle n\'est pas '
                  . 'supprimée, elle cesse simplement de paraître.<br>'
                  . 'Sans publication, cette case ne fait rien.',
        ]); ?>
        <p class="text-muted small mb-0">
          Tant qu'aucune citation n'est cochée et publiée pour un emplacement, la
          section correspondante disparaît de la page — elle n'affiche pas un
          cadre vide.
        </p>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : 'Créer la citation' ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/citations') ?>">Annuler</a>
    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>
