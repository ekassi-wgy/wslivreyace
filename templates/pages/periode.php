<?php
/**
 * Une période de la biographie (brief §3, lot G10).
 *
 * Sa page propre, donc son adresse : un récit d'époque se cite seul — dans un
 * dossier de presse, dans une note de bas de page, sur un QR code (décision 3).
 *
 * **Ce que cette page montre n'est rattaché nulle part à la main.** La période
 * connaît ses bornes ; les repères de la frise et les pièces du fonds qui s'y
 * affichent sont ceux dont la date y tombe. Un découpage revu les déplace avec
 * lui, sans qu'aucune fiche ne soit rouverte.
 */

use App\Core\Langue;
use App\Core\Site;
use App\Core\View;
use App\Model\Archive;
use App\Model\Media;
use App\Model\Periode;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$annees = Periode::annees($periode);
$recit  = trim((string) ($periode['recit'] ?? ''));
$source = trim((string) ($periode['source'] ?? ''));

/*
 * Un chapitre de biographie n'est ni un `Article` ni une `Person` : c'est une
 * partie d'un tout. `hasPart` sur la page mère l'aurait dit d'un seul côté ;
 * `isPartOf` le dit depuis la page qui existe, et `about` rattache le récit à
 * la personne dont il parle, ce qui est l'information qui compte pour un
 * moteur.
 */
$ld = json_encode(array_filter([
    '@context'      => 'https://schema.org',
    '@type'         => 'Article',
    'headline'      => (string) $periode['titre'],
    'description'   => $description ?? null,
    'url'           => Site::url(Langue::chemin(Periode::chemin($periode))),
    'temporalCoverage' => (int) $periode['debut'] . '/' . (int) $periode['fin'],
    'isPartOf'      => [
        '@type' => 'WebPage',
        'name'  => 'Biographie de Philippe Grégoire Yacé',
        'url'   => Site::url(Langue::chemin('/biographie')),
    ],
    'about'         => ['@type' => 'Person', 'name' => 'Philippe Grégoire Yacé'],
], static fn($v): bool => $v !== null), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <?php $fil = [
          ['Biographie', '/biographie'],
          [(string) $periode['titre'], null],
        ]; require dirname(__DIR__) . '/partials/fil.php'; ?>

        <p class="kicker reveal"><?= View::e($annees) ?></p>
        <h1 class="t-d1 reveal"><?= View::e((string) $periode['titre']) ?></h1>

        <?php $soustitre = trim((string) ($periode['sous_titre'] ?? '')); ?>
        <?php if ($soustitre !== ''): ?>
          <p class="t-lead page-head__lead reveal"><?= View::e($soustitre) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-7 offset-lg-2">

        <?php if ($images !== []): ?>
          <div class="arch-planche reveal">
            <?php foreach ($images as $img): ?>
              <?php $srcset = Media::srcset($img); ?>
              <figure class="arch-piece">
                <img loading="lazy" decoding="async"
                     src="<?= View::e(Media::urlMoyen((string) $img['fichier'])) ?>"
                     <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 58vw"' ?>
                     alt="<?= View::e(Media::alternative($img)) ?>">
                <?php $legende = trim((string) ($img['legende'] ?? '')); ?>
                <?php if ($legende !== '' || !empty($img['credit'])): ?>
                  <figcaption>
                    <?= View::e($legende) ?>
                    <?php if (!empty($img['credit'])): ?>
                      <span class="arch-piece__credit"><?= View::e((string) $img['credit']) ?></span>
                    <?php endif; ?>
                  </figcaption>
                <?php endif; ?>
              </figure>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($recit !== ''): ?>
          <div class="arch-bloc reveal"><?= View::paragraphes($recit, 't-body') ?></div>
        <?php endif; ?>

        <?php /* La source, en pied du récit : le CDC §6 l'exige, et c'est ce
                 qui distingue une biographie d'un propos rapporté. */ ?>
        <?php if ($source !== ''): ?>
          <p class="her-source reveal">Source : <?= View::e($source) ?></p>
        <?php endif; ?>

        <div class="her-citer reveal">
          <p class="arch-fiche__lbl">Adresse de cette page</p>
          <p class="arch-fiche__url"><?= View::e(Site::url(Langue::chemin(Periode::chemin($periode)))) ?></p>
        </div>

      </div>
    </div>

    <?php /* --- Les jalons de ces années ------------------------------------
             La frise de la biographie, réduite aux bornes de la période. Elle
             n'est pas dépliable ici : la page porte déjà le récit, et un
             accordéon de plus ferait deux textes à ouvrir pour la même
             matière. Les notices restent sur /biographie#chronologie. */ ?>
    <?php if ($reperes !== []): ?>
      <div class="row" style="margin-top: var(--sp-9);">
        <div class="col-lg-10 offset-lg-2">
          <div class="rule reveal" style="margin-bottom: var(--sp-6);"></div>
          <p class="kicker reveal">Jalons</p>
          <ul class="bio-jalons">
            <?php foreach ($reperes as $r): ?>
              <li class="reveal">
                <span class="bio-jalons__an"><?= View::e((string) $r['annee']) ?></span>
                <span class="bio-jalons__t"><?= View::e((string) $r['titre']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="link" href="<?= $lien('/biographie') ?>#chronologie">La frise entière, 1920 — 1998</a>
          </p>
        </div>
      </div>
    <?php endif; ?>

    <?php /* --- Le fonds de ces années ---------------------------------------
             Ce que le brief appelle « relier la biographie aux archives ». Le
             rattachement se fait par la date : une pièce datée de ces années
             appartient au récit de ces années, et personne n'a eu à le dire
             pièce par pièce. */ ?>
    <?php if ($pieces !== []): ?>
      <div class="row" style="margin-top: var(--sp-9);">
        <div class="col-lg-10 offset-lg-2">
          <div class="rule reveal" style="margin-bottom: var(--sp-6);"></div>
          <p class="kicker reveal">Le fonds, <?= View::e($annees) ?></p>
          <p class="t-lead reveal" style="margin-bottom: var(--sp-6);">
            Les pièces d'archives datées de ces années.
          </p>

          <ul class="bio-fonds">
            <?php foreach ($pieces as $piece): ?>
              <?php $couverture = $couvertures[(int) $piece['id']] ?? null; ?>
              <li class="reveal">
                <a href="<?= $lien(Archive::chemin($piece)) ?>">
                  <?php if ($couverture !== null): ?>
                    <img loading="lazy" decoding="async"
                         src="<?= View::e(Media::urlVignette((string) $couverture['fichier'])) ?>"
                         alt="<?= View::e(Media::alternative($couverture)) ?>">
                  <?php else: ?>
                    <?php /* Sans image, le signe de la catégorie : un discours ou
                             un document n'en a souvent aucune, et un cadre gris
                             ne dirait pas de quoi il s'agit. */ ?>
                    <span class="bio-fonds__signe" aria-hidden="true"><?= Archive::signe((string) $piece['categorie']) ?></span>
                  <?php endif; ?>
                  <span class="bio-fonds__corps">
                    <span class="bio-fonds__t"><?= View::e((string) $piece['titre']) ?></span>
                    <span class="bio-fonds__meta">
                      <?= View::e(Archive::categorie((string) $piece['categorie'])) ?>
                      <?php $quand = Archive::date($piece); ?>
                      <?= $quand === '' ? '' : ' · ' . View::e($quand) ?>
                    </span>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

          <?php if ($totalPieces > count($pieces)): ?>
            <?php /* Le fonds ne se filtre pas par intervalle d'années — sa
                     planche filtre par catégorie et par millésime exact. Le
                     compte est donc donné en clair et le lien ouvre le fonds
                     entier : annoncer un filtre qui n'existe pas serait pire
                     que de ne rien annoncer. */ ?>
            <p class="reveal" style="margin-top: var(--sp-5);">
              <?= (int) $totalPieces ?> pièces du fonds sont datées de ces années.
              <a class="link" href="<?= $lien('/archives') ?>">Ouvrir le fonds</a>
            </p>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php /* --- Avant, après ------------------------------------------------
             Une biographie se lit dans l'ordre, et on arrive ici par un lien
             profond aussi souvent que par la page mère. */ ?>
    <?php if ($precedente !== null || $suivante !== null): ?>
      <div class="row" style="margin-top: var(--sp-9);">
        <div class="col-lg-10 offset-lg-2">
          <div class="rule reveal" style="margin-bottom: var(--sp-6);"></div>
          <nav class="bio-suite" aria-label="Périodes voisines">
            <?php if ($precedente !== null): ?>
              <a class="bio-suite__lien reveal" href="<?= $lien(Periode::chemin($precedente)) ?>" rel="prev">
                <span class="bio-suite__sens">Période précédente</span>
                <span class="bio-suite__t"><?= View::e((string) $precedente['titre']) ?></span>
                <span class="bio-suite__ans"><?= View::e(Periode::annees($precedente)) ?></span>
              </a>
            <?php endif; ?>
            <?php if ($suivante !== null): ?>
              <a class="bio-suite__lien bio-suite__lien--fin reveal" href="<?= $lien(Periode::chemin($suivante)) ?>" rel="next">
                <span class="bio-suite__sens">Période suivante</span>
                <span class="bio-suite__t"><?= View::e((string) $suivante['titre']) ?></span>
                <span class="bio-suite__ans"><?= View::e(Periode::annees($suivante)) ?></span>
              </a>
            <?php endif; ?>
          </nav>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
