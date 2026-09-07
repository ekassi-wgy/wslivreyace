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
    ? 'Rechercher — Philippe Grégoire Yacé'
    : sprintf('« %s » — recherche', $terme);

$description = 'Rechercher dans le fonds Philippe Grégoire Yacé : archives, discours, '
             . 'héritage, actualités et événements.';

$lien = static fn(string $chemin): string => Langue::chemin($chemin);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Recherche</p>
        <h1 class="t-d1 reveal">Chercher.</h1>

        <form class="arch-rech reveal" method="get" action="<?= $lien('/recherche') ?>" role="search"
              style="margin-top: var(--sp-6);">
          <label class="arch-rech__label" for="q">Dans tout le site</label>
          <div class="arch-rech__ligne">
            <input class="arch-rech__champ" type="search" id="q" name="q" value="<?= View::e($terme) ?>"
                   placeholder="Un nom, un lieu, une année, un mot d'un discours…"
                   autofocus>
            <button class="btn-pgy btn-pgy--sm" type="submit">Chercher</button>
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
          <p class="t-body reveal">
            La recherche parcourt les archives — transcriptions des discours
            comprises —, l'héritage, les actualités et les événements.
          </p>

        <?php elseif (mb_strlen($terme) < Recherche::MINIMUM): ?>
          <p class="t-body reveal">
            Il faut au moins <?= Recherche::MINIMUM ?> caractères pour chercher.
          </p>

        <?php elseif ($resultats === []): ?>
          <p class="t-body reveal">
            Aucun résultat pour « <strong><?= View::e($terme) ?></strong> ».
          </p>
          <p class="t-body reveal">
            Le fonds s'enrichit régulièrement : ce que vous cherchez n'y est
            peut-être pas encore.
            <a class="link" href="<?= $lien('/contribuer') ?>">Si vous le possédez, confiez-le-nous.</a>
          </p>

        <?php else: ?>
          <p class="kicker kicker--bare reveal" style="margin-bottom: var(--sp-6);">
            <?= (int) $total ?> résultat<?= $total > 1 ? 's' : '' ?>
            pour « <?= View::e($terme) ?> »
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
