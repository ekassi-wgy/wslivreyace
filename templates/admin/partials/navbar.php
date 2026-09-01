<?php
/**
 * Barre supérieure du back-office.
 *
 * Le thème en livrait une bien plus fournie : sélecteur de catégorie, date,
 * champ de recherche, cloche de notifications, boîte de messages, avatars de
 * démonstration. Rien de tout cela ne correspond à un besoin du cahier des
 * charges ; tout a été retiré plutôt que laissé en décor inerte.
 *
 * Ce qui reste : la marque, le bouton de repli de la barre latérale, le
 * raccourci vers le site public, et le menu du compte.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

$utilisateur = $utilisateur ?? null;
?>
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">

  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
    <div class="me-3">
      <button class="navbar-toggler align-self-center" type="button"
              data-bs-toggle="minimize" aria-label="Replier la barre latérale">
        <span class="mdi mdi-menu"></span>
      </button>
    </div>
    <div>
      <a class="navbar-brand brand-logo" href="<?= Admin::url('/') ?>">
        <span class="pgy-monogramme" aria-hidden="true">PGY</span>
        <span class="pgy-marque">
          <b>Une destinée</b>
          <span>Administration</span>
        </span>
      </a>
      <a class="navbar-brand brand-logo-mini" href="<?= Admin::url('/') ?>">
        <span class="pgy-monogramme" aria-hidden="true">PGY</span>
      </a>
    </div>
  </div>

  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <ul class="navbar-nav ms-auto align-items-center">

      <li class="nav-item d-none d-md-block">
        <a class="nav-link" href="/" target="_blank" rel="noopener">
          <i class="mdi mdi-open-in-new me-1"></i> Voir le site
        </a>
      </li>

      <li class="nav-item dropdown user-dropdown">
        <a class="nav-link d-flex align-items-center" id="menuCompte" href="#"
           data-bs-toggle="dropdown" aria-expanded="false">
          <span class="pgy-monogramme" aria-hidden="true">
            <?= View::e(mb_strtoupper(mb_substr($utilisateur['nom'] ?? 'A', 0, 1))) ?>
          </span>
          <i class="mdi mdi-chevron-down ms-1"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="menuCompte">
          <div class="dropdown-header">
            <p class="mb-0 fw-semibold"><?= View::e($utilisateur['nom'] ?? 'Non connecté') ?></p>
            <p class="fw-light text-muted mb-0 small"><?= View::e($utilisateur['email'] ?? '—') ?></p>
            <?php if (($utilisateur['role'] ?? '') === 'admin'): ?>
              <span class="pgy-statut pgy-statut--publie mt-2 d-inline-block">administrateur</span>
            <?php endif; ?>
          </div>
          <div class="dropdown-divider"></div>
          <?php /* En POST et non en lien : une balise <img src="…/deconnexion">
                   sur un site tiers suffirait sinon à déconnecter l'éditeur. */ ?>
          <form method="post" action="<?= Admin::url('/deconnexion') ?>" class="px-2 pb-1">
            <?= Csrf::champ() ?>
            <button type="submit" class="dropdown-item px-2">
              <i class="dropdown-item-icon mdi mdi-power me-2"></i>Se déconnecter
            </button>
          </form>
        </div>
      </li>
    </ul>

    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas" aria-label="Ouvrir le menu">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
