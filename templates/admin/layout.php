<?php
/**
 * Mise en page unique du back-office.
 *
 * $contenu est le corps de la page, déjà rendu par View::admin. Les gabarits
 * de page posent $titre et $actif (clé du menu à marquer).
 *
 * Le pendant de templates/layout.php pour le site public : même principe, un
 * seul en-tête, une seule barre latérale, un seul pied pour tout l'admin.
 */

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Session;
use App\Core\View;

$titre  = $titre  ?? 'Administration';
$actif  = $actif  ?? '';
$scripts = $scripts ?? [];   // scripts propres à la page, chargés en fin de corps
$styles  = $styles  ?? [];   // feuilles propres à la page (DataTables, p. ex.)

$utilisateur = Auth::utilisateur();
$messages    = Session::messages();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= View::e($titre) ?> — Administration</title>

<?php /* Le back-office n'a rien à faire dans un index de moteur de recherche. */ ?>
<meta name="robots" content="noindex, nofollow">

<?php /* Même marque que le site public, servie depuis les mêmes fichiers :
         une seule identité, un seul jeu d'icônes à maintenir. */ ?>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<meta name="theme-color" content="#A88B5C">

<link rel="stylesheet" href="<?= Admin::asset('vendors/mdi/css/materialdesignicons.min.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('vendors/css/vendor.bundle.base.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('css/style.css') ?>">
<?php /* Les feuilles de page passent AVANT la surcouche : celle-ci doit
         pouvoir corriger ce qu'un greffon impose. */ ?>
<?php foreach ($styles as $f): ?>
<link rel="stylesheet" href="<?= View::e($f) ?>">
<?php endforeach; ?>
<link rel="stylesheet" href="<?= Admin::asset('css/pgy-admin.css') ?>">
</head>

<body class="sidebar-fixed">
<a class="pgy-evitement" href="#contenu">Aller au contenu</a>

<div class="container-scroller">

  <?php require __DIR__ . '/partials/navbar.php'; ?>

  <div class="container-fluid page-body-wrapper">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>

    <div class="main-panel">
      <div class="content-wrapper" id="contenu">
        <?php if ($messages !== []): ?>
          <div class="pgy-messages">
            <?php foreach ($messages as $m): ?>
              <div class="alert alert-<?= $m['type'] === 'succes' ? 'success' : ($m['type'] === 'erreur' ? 'danger' : 'info') ?>" role="alert">
                <?= View::e($m['texte']) ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?= $contenu ?>
      </div>
      <?php require __DIR__ . '/partials/footer.php'; ?>
    </div>
  </div>
</div>

<?php /* jQuery 3.7.1 + Bootstrap 5.3.2 + PerfectScrollbar, servis localement. */ ?>
<script src="<?= Admin::asset('vendors/js/vendor.bundle.base.js') ?>"></script>
<script src="<?= Admin::asset('js/admin.js') ?>"></script>
<?php foreach ($scripts as $s): ?>
<script src="<?= View::e($s) ?>"></script>
<?php endforeach; ?>
</body>
</html>
