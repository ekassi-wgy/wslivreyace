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
use App\Core\View;

$titre  = $titre  ?? 'Administration';
$actif  = $actif  ?? '';
$scripts = $scripts ?? [];   // scripts propres à la page, chargés en fin de corps
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= View::e($titre) ?> — Administration</title>

<?php /* Le back-office n'a rien à faire dans un index de moteur de recherche. */ ?>
<meta name="robots" content="noindex, nofollow">

<link rel="stylesheet" href="<?= Admin::asset('vendors/mdi/css/materialdesignicons.min.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('vendors/css/vendor.bundle.base.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('css/style.css') ?>">
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
