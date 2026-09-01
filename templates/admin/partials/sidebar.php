<?php
/**
 * Barre latérale du back-office.
 *
 * Les entrées viennent d'Admin::menu(), source unique. Le thème marquait
 * l'entrée courante en JavaScript, par comparaison du nom de fichier de
 * l'URL ; nos URL n'ont pas d'extension et PHP connaît la route, donc la
 * classe `active` est posée ici, au rendu.
 *
 * Les entrées `bientot` sont les pages des lots C à E. Elles restent
 * affichées, désactivées : le back-office montre sa forme finale dès le
 * premier lot, et l'ordre des livraisons se lit d'un coup d'œil.
 */

use App\Core\Admin;
use App\Core\View;

$actif = $actif ?? '';
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php foreach (Admin::menu() as $entree): ?>

      <?php if (isset($entree['rubrique'])): ?>
        <li class="nav-item nav-category"><?= View::e($entree['rubrique']) ?></li>

      <?php elseif (!empty($entree['bientot'])): ?>
        <li class="nav-item">
          <span class="nav-link disabled text-muted" aria-disabled="true"
                title="Disponible dans une prochaine livraison">
            <i class="menu-icon mdi <?= View::e($entree['icone']) ?>"></i>
            <span class="menu-title"><?= View::e($entree['titre']) ?></span>
            <i class="mdi mdi-lock-outline ms-auto small"></i>
          </span>
        </li>

      <?php else: ?>
        <li class="nav-item<?= $actif === $entree['cle'] ? ' active' : '' ?>">
          <a class="nav-link" href="<?= View::e($entree['url']) ?>"
             <?= $actif === $entree['cle'] ? 'aria-current="page"' : '' ?>>
            <i class="menu-icon mdi <?= View::e($entree['icone']) ?>"></i>
            <span class="menu-title"><?= View::e($entree['titre']) ?></span>
          </a>
        </li>
      <?php endif; ?>

    <?php endforeach; ?>
  </ul>
</nav>
