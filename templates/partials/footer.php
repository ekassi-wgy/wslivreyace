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
        <a class="logo" href="<?= $lien('/') ?>" aria-label="<?= t('nav.logo_aria') ?>">
          <svg class="logo__svg" aria-hidden="true" focusable="false"><use href="#pgy-logo"></use></svg>
        </a>
      </div>
      <div class="col-lg-3 foot__col">
        <p><?= t('pied.ouvrage') ?></p>
        <ul>
          <li><a href="<?= $lien('/le-livre') ?>"><?= t('pied.livre') ?></a></li>
          <li><a href="<?= $lien('/le-livre') ?>#auteur"><?= t('pied.auteur') ?></a></li>
          <li><a href="<?= $lien('/le-livre') ?>#extrait"><?= t('pied.extraits') ?></a></li>
          <li><a href="<?= $lien('/le-livre') ?>#acheter"><?= t('pied.commander') ?></a></li>
        </ul>
      </div>
      <div class="col-lg-2 foot__col">
        <p><?= t('pied.personnage') ?></p>
        <ul>
          <li><a href="<?= $lien('/biographie') ?>"><?= t('pied.biographie') ?></a></li>
          <li><a href="<?= $lien('/biographie') ?>#chronologie"><?= t('pied.reperes') ?></a></li>
          <li><a href="<?= $lien('/heritage') ?>"><?= t('pied.heritage') ?></a></li>
          <li><a href="<?= $lien('/archives') ?>"><?= t('pied.archives') ?></a></li>
        </ul>
      </div>
      <div class="col-lg-2 foot__col">
        <p><?= t('pied.site') ?></p>
        <ul>
          <li><a href="<?= $lien('/actualites') ?>"><?= t('pied.actualites') ?></a></li>
          <li><a href="<?= $lien('/revue-de-presse') ?>"><?= t('pied.revue_presse') ?></a></li>
          <li><a href="<?= $lien('/evenements') ?>"><?= t('pied.evenements') ?></a></li>
          <li><a href="<?= $lien('/temoignages') ?>"><?= t('pied.temoignages') ?></a></li>
          <li><a href="<?= $lien('/contact') ?>"><?= t('pied.contact') ?></a></li>
        </ul>
      </div>
    </div>

    <div class="foot__rule" style="margin-block: var(--sp-7) var(--sp-5);"></div>

    <div class="row align-items-center" style="row-gap: var(--sp-3);">
      <div class="col-md-8">
        <p class="t-small" style="color: rgba(247,244,238,.5); font-size:.8125rem;">
          &copy; <?= date('Y') ?> — <?= t('pied.droits') ?>
        </p>
      </div>
      <div class="col-md-4 text-md-end">
        <p class="t-small" style="color: rgba(247,244,238,.5); font-size:.8125rem;">
          <?php /* Un seul document : la politique de confidentialité est une
                   section des mentions légales, et deux pages qui se renvoient
                   l'une à l'autre finissent par se contredire. */ ?>
          <a href="<?= $lien('/mentions-legales') ?>"><?= t('pied.mentions') ?></a> ·
          <a href="<?= $lien('/mentions-legales') ?>#donnees"><?= t('pied.confidentialite') ?></a>
        </p>
      </div>
    </div>
  </div>
</footer>
