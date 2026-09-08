<?php
/**
 * Résultats de recherche (brief §9, lot G9).
 *
 * Groupés par rubrique et non mélangés par pertinence : sur un fonds
 * patrimonial, savoir qu'on a trouvé « Jacqueville » dans une archive **et**
 * dans un lieu de mémoire vaut mieux qu'un classement dont on ne sait pas ce
 * qui le décide.
 */

use App\Core\Langue;
use App\Core\Recherche;
use App\Core\View;

$titre = $terme === ''
    ? t_nu('recherche.titre_page')
    : t_nu('recherche.titre_terme', ['terme' => $terme]);

$description = t_nu('recherche.description');

$lien = static fn(string $chemin): string => Langue::chemin($chemin);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('recherche.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('recherche.titre') ?></h1>

        <form class="arch-rech reveal" method="get" action="<?= $lien('/recherche') ?>" role="search"
              style="margin-top: var(--sp-6);">
          <label class="arch-rech__label" for="q"><?= t('recherche.label') ?></label>
          <div class="arch-rech__ligne">
            <input class="arch-rech__champ" type="search" id="q" name="q" value="<?= View::e($terme) ?>"
                   placeholder="<?= t('recherche.placeholder') ?>"
                   autofocus>
            <button class="btn-pgy btn-pgy--sm" type="submit"><?= t('recherche.chercher') ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">

        <?php if ($terme === ''): ?>
          <p class="t-body reveal"><?= t('recherche.invite') ?></p>

        <?php elseif (mb_strlen($terme) < Recherche::MINIMUM): ?>
          <p class="t-body reveal"><?= t('recherche.trop_court', ['minimum' => Recherche::MINIMUM]) ?></p>

        <?php elseif ($resultats === []): ?>
          <p class="t-body reveal"><?= t_brut('recherche.aucun', ['terme' => $terme]) ?></p>
          <p class="t-body reveal">
            <?= t('recherche.aucun_suite') ?>
            <a class="link" href="<?= $lien('/contribuer') ?>"><?= t('recherche.aucun_lien') ?></a>
          </p>

        <?php else: ?>
          <p class="kicker kicker--bare reveal" style="margin-bottom: var(--sp-6);">
            <?= $total > 1
                  ? t('recherche.compte',    ['nombre' => (int) $total, 'terme' => $terme])
                  : t('recherche.compte_un', ['nombre' => (int) $total, 'terme' => $terme]) ?>
          </p>

          <?php foreach ($resultats as $type => $lot): ?>
            <section class="rech-groupe reveal">
              <h2 class="rech-groupe__titre">
                <span aria-hidden="true"><?= Recherche::TYPES[$type]['signe'] ?? '' ?></span>
                <?= View::e(Recherche::TYPES[$type]['libelle'] ?? $type) ?>
                <span class="rech-groupe__n"><?= count($lot) ?></span>
              </h2>

              <ul class="rech-liste">
                <?php foreach ($lot as $r): ?>
                  <li>
                    <a href="<?= $lien($r['chemin']) ?>">
                      <span class="rech-item__titre"><?= Recherche::surligner($r['titre'], $terme) ?></span>

                      <?php $meta = array_filter([$r['contexte'] ?? '', $r['date']]); ?>
                      <?php if ($meta !== []): ?>
                        <span class="rech-item__meta"><?= View::e(implode(' · ', $meta)) ?></span>
                      <?php endif; ?>

                      <?php if ($r['extrait'] !== ''): ?>
                        <span class="rech-item__extrait"><?= Recherche::surligner($r['extrait'], $terme) ?></span>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </section>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>
