<?php
/**
 * Fiche technique de l'ouvrage.
 *
 * Les champs sont dérivés de Parametre::FICHE_LIVRE : ajouter une valeur à
 * cette table l'ajoute ici, dans le contrôleur et dans la validation, sans
 * retoucher trois fichiers.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

require dirname(__DIR__) . '/partials/champs.php';

$total = count($champs);
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Administration</span>
    <h1>Fiche technique de l'ouvrage</h1>
    <p>
      Ces valeurs alimentent la page « Le livre » et l'appel à commander.
      <strong><?= (int) $remplis ?> sur <?= $total ?></strong> renseignée<?= $remplis > 1 ? 's' : '' ?>.
    </p>
  </div>
</div>

<?php if ($remplis < $total): ?>
  <div class="alert alert-warning" role="alert">
    <i class="mdi mdi-alert-outline me-1" aria-hidden="true"></i>
    <?= $total - $remplis ?> valeur<?= ($total - $remplis) > 1 ? 's' : '' ?>
    manque<?= ($total - $remplis) > 1 ? 'nt' : '' ?> encore.
    La fiche technique est <strong>bloquante pour la mise en ligne</strong> :
    sans elle, la page « Le livre » affiche des lignes vides et le visiteur ne
    sait ni combien coûte l'ouvrage ni comment le commander.
  </div>
<?php endif; ?>

<form method="post" action="<?= Admin::url('/parametres') ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <div class="row">
          <?php foreach ($champs as $cle => $champ): ?>
            <div class="col-md-6">
              <?php champ_texte($valeurs, $erreurs, $cle, $champ['libelle'], [
                  'type'      => $champ['type'] === 'entier' ? 'number' : 'text',
                  'aide'      => $champ['aide'],
                  'attributs' => $champ['type'] === 'entier'
                      ? 'min="1" max="10000" step="1" placeholder="' . View::e($champ['exemple']) . '"'
                      : 'maxlength="200" placeholder="' . View::e($champ['exemple']) . '"',
              ]); ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div></div>
    </div>

    <?php /* --- Préface et auteur (lot G2) -------------------------------
             La mise en avant est un réglage et non un choix de gabarit : le
             jour où la préface se confirme, l'éditeur coche la case et la page
             du livre se réorganise seule. Voir Parametre::AUTOUR_LIVRE. */ ?>
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Préface et auteur</h4>
        <p class="text-muted small">
          La page « Le livre » et la page de l'auteur s'en nourrissent.
          Un champ vide n'affiche rien plutôt qu'un titre sans contenu.
        </p>

        <?php foreach ($autour as $cle => $champ): ?>
          <?php if ($champ['type'] === 'case'): ?>
            <?php champ_case($valeurs, $erreurs, $cle, $champ['libelle'], ['aide' => $champ['aide']]); ?>
          <?php elseif ($champ['type'] === 'long'): ?>
            <?php champ_zone($valeurs, $erreurs, $cle, $champ['libelle'], ['aide' => $champ['aide']]); ?>
          <?php else: ?>
            <?php champ_texte($valeurs, $erreurs, $cle, $champ['libelle'], [
                'aide'      => $champ['aide'],
                'attributs' => 'maxlength="200"'
                    . ($champ['exemple'] === '' ? '' : ' placeholder="' . View::e($champ['exemple']) . '"'),
            ]); ?>
          <?php endif; ?>
        <?php endforeach; ?>
      </div></div>
    </div>

    <?php /* --- Boutique et livraison (lot G3) ------------------------------
             Un groupe à part : la fiche technique décrit l'ouvrage, ceci
             décide s'il se vend. Voir Parametre::BOUTIQUE. */ ?>
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Boutique et livraison</h4>
        <p class="text-muted small">
          Les commandes sont en <strong>paiement à la livraison</strong> : le client
          ne paie rien en ligne, et l'argent est encaissé à la remise. Les tarifs de
          livraison se règlent à l'écran
          <a href="<?= App\Core\Admin::url('/zones') ?>">Zones de livraison</a>.
        </p>

        <?php foreach ($boutique as $cle => $champ): ?>
          <?php if ($champ['type'] === 'case'): ?>
            <?php champ_case($valeurs, $erreurs, $cle, $champ['libelle'], ['aide' => $champ['aide']]); ?>
          <?php elseif ($champ['type'] === 'long'): ?>
            <?php champ_zone($valeurs, $erreurs, $cle, $champ['libelle'], ['aide' => $champ['aide']]); ?>
          <?php else: ?>
            <?php champ_texte($valeurs, $erreurs, $cle, $champ['libelle'], [
                'aide'      => $champ['aide'],
                'attributs' => 'maxlength="200"'
                    . ($champ['exemple'] === '' ? '' : ' placeholder="' . View::e($champ['exemple']) . '"'),
            ]); ?>
          <?php endif; ?>
        <?php endforeach; ?>

        <?php /* L'état réel, dit en clair : deux conditions et il les faut
                 toutes, ce qu'une case à cocher seule ne laisse pas deviner. */ ?>
        <?php if (App\Core\Boutique::ouverte()): ?>
          <p class="alert alert-success mb-0">
            <i class="mdi mdi-cart-check me-1" aria-hidden="true"></i>
            <strong>Les commandes sont ouvertes</strong> — le prix est de
            <?= View::e(App\Core\Boutique::prixLisible()) ?>.
            <?php if (!App\Model\Zone::livraisonPossible()): ?>
              Aucune zone ne porte de tarif : seul le retrait est proposé.
            <?php endif; ?>
          </p>
        <?php else: ?>
          <p class="alert alert-secondary mb-0">
            <i class="mdi mdi-cart-off me-1" aria-hidden="true"></i>
            <strong>Les commandes sont fermées.</strong>
            <?php if (App\Core\Boutique::prix() === null): ?>
              Le prix n'est pas renseigné — la case ne suffit pas.
            <?php else: ?>
              La case « Ouvrir les commandes » n'est pas cochée.
            <?php endif; ?>
            La page Commander reste en ligne et annonce l'ouverture à la parution.
          </p>
        <?php endif; ?>
      </div></div>
    </div>

    <div class="col-lg-4 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Ce qui est vérifié</h4>
        <ul class="pgy-liste-notes">
          <li>L'<strong>ISBN</strong> est contrôlé sur ses 13 chiffres et sa clé :
              un numéro mal recopié servirait à commander l'ouvrage.</li>
          <li>Le <strong>nombre de pages</strong> doit être un entier.</li>
          <li>Le <strong>prix</strong> est du texte libre, devise comprise :
              le site dessert plusieurs zones monétaires.</li>
          <li>Un champ vidé redevient vide en base, et sa ligne disparaît de la
              page publique plutôt que d'y rester en blanc.</li>
        </ul>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">Enregistrer la fiche</button>
    <span class="pgy-barre-actions__info"><?= (int) $remplis ?> / <?= $total ?> renseignée<?= $remplis > 1 ? 's' : '' ?></span>
  </div>
</form>
