<?php
/**
 * Mise en page sans chrome, pour les écrans qui précèdent l'authentification.
 *
 * Ni barre latérale, ni barre supérieure : elles n'offriraient que des liens
 * inaccessibles, et la barre du compte n'a personne à nommer.
 */

use App\Core\Admin;
use App\Core\Session;
use App\Core\View;

$titre    = $titre ?? 'Administration';
$messages = Session::messages();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= View::e($titre) ?> — Administration</title>
<meta name="robots" content="noindex, nofollow">

<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<meta name="theme-color" content="#A88B5C">

<link rel="stylesheet" href="<?= Admin::asset('vendors/mdi/css/materialdesignicons.min.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('vendors/css/vendor.bundle.base.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('css/style.css') ?>">
<link rel="stylesheet" href="<?= Admin::asset('css/pgy-admin.css') ?>">
</head>

<body class="pgy-nu">
<main class="pgy-nu__centre">
  <div class="pgy-nu__carte">

    <a class="pgy-nu__marque" href="/">
      <span class="pgy-monogramme" aria-hidden="true">PGY</span>
      <span class="pgy-marque">
        <b>Une destinée</b>
        <span>Administration</span>
      </span>
    </a>

    <?php foreach ($messages as $m): ?>
      <div class="alert alert-<?= $m['type'] === 'succes' ? 'success' : ($m['type'] === 'erreur' ? 'danger' : 'info') ?>" role="alert">
        <?= View::e($m['texte']) ?>
      </div>
    <?php endforeach; ?>

    <?= $contenu ?>

  </div>

  <p class="pgy-nu__pied">
    <a href="/">&larr; Retour au site</a>
  </p>
</main>
</body>
</html>
