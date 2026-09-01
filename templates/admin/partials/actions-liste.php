<?php
/**
 * Colonne d'actions d'une ligne de liste : publier/dépublier, modifier,
 * supprimer.
 *
 * Les trois actions passent par POST avec jeton. La suppression n'est pas un
 * lien : un lien se déclenche par un préchargement de navigateur, un aspirateur
 * de site ou une balise sur un site tiers — et il n'y a pas de corbeille.
 *
 * Attend $ligne, $config et $confirmation dans la portée appelante.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

$id      = (int) $ligne['id'];
$statut  = (string) ($ligne['statut'] ?? 'brouillon');
$chemin  = $config['chemin'];
$titre   = (string) ($ligne['titre'] ?? '');
$enLigne = $statut === 'publie';
?>
<div class="pgy-actions">

  <form method="post" action="<?= Admin::url("$chemin/$id/statut") ?>" class="d-inline">
    <?= Csrf::champ() ?>
    <button type="submit" class="btn btn-sm <?= $enLigne ? 'btn-outline-secondary' : 'btn-primary' ?>"
            title="<?= $enLigne ? 'Repasser en brouillon' : 'Publier' ?>">
      <i class="mdi <?= $enLigne ? 'mdi-eye-off-outline' : 'mdi-eye-outline' ?>" aria-hidden="true"></i>
      <span class="visually-hidden"><?= $enLigne ? 'Repasser en brouillon' : 'Publier' ?> : <?= View::e($titre) ?></span>
    </button>
  </form>

  <a class="btn btn-sm btn-outline-secondary" href="<?= Admin::url("$chemin/$id") ?>" title="Modifier">
    <i class="mdi mdi-pencil-outline" aria-hidden="true"></i>
    <span class="visually-hidden">Modifier : <?= View::e($titre) ?></span>
  </a>

  <form method="post" action="<?= Admin::url("$chemin/$id/supprimer") ?>" class="d-inline"
        data-confirmation="<?= View::e($confirmation ?? 'Supprimer définitivement « ' . $titre . ' » ?') ?>">
    <?= Csrf::champ() ?>
    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
      <i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>
      <span class="visually-hidden">Supprimer : <?= View::e($titre) ?></span>
    </button>
  </form>

</div>
