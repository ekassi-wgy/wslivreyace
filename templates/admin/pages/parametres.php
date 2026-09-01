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
