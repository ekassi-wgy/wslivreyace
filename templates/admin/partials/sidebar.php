<?php
/**
 * Barre latérale du back-office.
 *
 * Les entrées viennent d'Admin::menu(), source unique. Le thème marquait
 * l'entrée courante en JavaScript, par comparaison du nom de fichier de
 * l'URL ; nos URL n'ont pas d'extension et PHP connaît la route, donc la
 * classe `active` est posée ici, au rendu.
 *
 * Les entrées `bientot` — il n'en reste plus, tous les lots étant livrés —
 * s'affichaient désactivées : le back-office montrait sa forme finale dès le
 * premier lot. Le mécanisme reste en place pour la suite.
 *
 * Les entrées réservées aux administrateurs sont retirées du menu d'un
 * éditeur, pas grisées : elles existent, il n'y a simplement pas accès.
 */

use App\Core\Admin;
use App\Core\Auth;
use App\Core\View;

$actif = $actif ?? '';
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <?php foreach (Admin::menuPour(Auth::estAdmin()) as $entree): ?>

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
