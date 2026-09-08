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
 *
 * **Ce fichier écrit dans la portée de la liste qui l'inclut** — un `require`
 * ne crée pas de portée à lui. Ses variables portent donc un préfixe depuis le
 * lot G10 : `$alTitre` valait auparavant le titre de la ligne, écrasait celui de
 * la page, et la mise en page d'administration servait ensuite le nom du
 * dernier contenu de la liste en `<title>` de l'onglet. Les listes vides n'y
 * échappaient que parce que la boucle ne tournait pas.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

$alId      = (int) $ligne['id'];
$alStatut  = (string) ($ligne['statut'] ?? 'brouillon');
$alChemin  = $config['chemin'];
/* La colonne qui identifie la ligne. `titre` partout, sauf là où l'entité n'en
   a pas : un point de vente a une ville (lot G12). Voir la clé `libelle` de
   `CrudController::config()` — les libellés lus ici et les messages écrits
   là-bas doivent désigner la même chose, sans quoi la confirmation de
   suppression et le message qui la suit ne parleraient pas de la même fiche. */
$alTitre   = (string) ($ligne[$config['libelle'] ?? 'titre'] ?? '');
$alEnLigne = $alStatut === 'publie';
?>
<div class="pgy-actions">

  <form method="post" action="<?= Admin::url("$alChemin/$alId/statut") ?>" class="d-inline">
    <?= Csrf::champ() ?>
    <button type="submit" class="btn btn-sm <?= $alEnLigne ? 'btn-outline-secondary' : 'btn-primary' ?>"
            title="<?= $alEnLigne ? 'Repasser en brouillon' : 'Publier' ?>">
      <i class="mdi <?= $alEnLigne ? 'mdi-eye-off-outline' : 'mdi-eye-outline' ?>" aria-hidden="true"></i>
      <span class="visually-hidden"><?= $alEnLigne ? 'Repasser en brouillon' : 'Publier' ?> : <?= View::e($alTitre) ?></span>
    </button>
  </form>

  <a class="btn btn-sm btn-outline-secondary" href="<?= Admin::url("$alChemin/$alId") ?>" title="Modifier">
    <i class="mdi mdi-pencil-outline" aria-hidden="true"></i>
    <span class="visually-hidden">Modifier : <?= View::e($alTitre) ?></span>
  </a>

  <form method="post" action="<?= Admin::url("$alChemin/$alId/supprimer") ?>" class="d-inline"
        data-confirmation="<?= View::e($confirmation ?? 'Supprimer définitivement « ' . $alTitre . ' » ?') ?>">
    <?= Csrf::champ() ?>
    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
      <i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>
      <span class="visually-hidden">Supprimer : <?= View::e($alTitre) ?></span>
    </button>
  </form>

</div>
