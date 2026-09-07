<?php
/**
 * Pied de page. Comme la navigation, tout lien interne passe par
 * `Langue::chemin()` (lot G1) : en français il rend le chemin inchangé, sous
 * `/en/` il le préfixe.
 */
$lien = static fn(string $chemin): string => App\Core\Langue::chemin($chemin);
?>
<footer class="foot">
  <div class="shell" style="padding-block: var(--sp-9) var(--sp-7);">
    <div class="row" style="row-gap: var(--sp-7);">
      <div class="col-lg-5 col-xl-4">
        <a class="logo" href="<?= $lien('/') ?>" aria-label="Philippe Grégoire Yacé — accueil">
          <svg class="logo__svg" aria-hidden="true" focusable="false"><use href="#pgy-logo"></use></svg>
        </a>
      </div>
      <div class="col-lg-3 foot__col">
        <p>L'ouvrage</p>
        <ul>
          <li><a href="<?= $lien('/le-livre') ?>">Le livre</a></li>
          <li><a href="<?= $lien('/le-livre') ?>#auteur">L'auteur</a></li>
          <li><a href="<?= $lien('/le-livre') ?>#extrait">Extraits</a></li>
          <li><a href="<?= $lien('/le-livre') ?>#acheter">Commander</a></li>
        </ul>
      </div>
      <div class="col-lg-2 foot__col">
        <p>Le personnage</p>
        <ul>
          <li><a href="<?= $lien('/biographie') ?>">Biographie</a></li>
          <li><a href="<?= $lien('/biographie') ?>#chronologie">Repères</a></li>
          <li><a href="<?= $lien('/heritage') ?>">Héritage</a></li>
          <li><a href="<?= $lien('/archives') ?>">Archives</a></li>
        </ul>
      </div>
      <div class="col-lg-2 foot__col">
        <p>Le site</p>
        <ul>
          <li><a href="<?= $lien('/actualites') ?>">Actualités</a></li>
          <li><a href="<?= $lien('/revue-de-presse') ?>">Revue de presse</a></li>
          <li><a href="<?= $lien('/evenements') ?>">Événements</a></li>
          <li><a href="<?= $lien('/temoignages') ?>">Témoignages</a></li>
          <li><a href="<?= $lien('/contact') ?>">Contact</a></li>
        </ul>
      </div>
    </div>

    <div class="foot__rule" style="margin-block: var(--sp-7) var(--sp-5);"></div>

    <div class="row align-items-center" style="row-gap: var(--sp-3);">
      <div class="col-md-8">
        <p class="t-small" style="color: rgba(247,244,238,.5); font-size:.8125rem;">
          &copy; <?= date('Y') ?> — Tous droits réservés. Structure porteuse à renseigner.
        </p>
      </div>
      <div class="col-md-4 text-md-end">
        <p class="t-small" style="color: rgba(247,244,238,.5); font-size:.8125rem;">
          <?php /* Un seul document : la politique de confidentialité est une
                   section des mentions légales, et deux pages qui se renvoient
                   l'une à l'autre finissent par se contredire. */ ?>
          <a href="<?= $lien('/mentions-legales') ?>">Mentions légales</a> ·
          <a href="<?= $lien('/mentions-legales') ?>#donnees">Confidentialité</a>
        </p>
      </div>
    </div>
  </div>
</footer>
