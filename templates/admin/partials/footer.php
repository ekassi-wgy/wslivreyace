<?php
/**
 * Pied du back-office. Le crédit « Premium Bootstrap admin template from
 * BootstrapDash » du thème d'origine est retiré : le template est sous
 * licence MIT, l'attribution vit dans le README et non sur chaque écran.
 */

use App\Core\Config;
use App\Core\View;
?>
<footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted text-center text-sm-start d-block d-sm-inline-block">
      <?= View::e(Config::get('app')['nom'] ?? 'Administration') ?>
    </span>
    <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center text-muted">
      Back-office &middot; <?= date('Y') ?>
    </span>
  </div>
</footer>
