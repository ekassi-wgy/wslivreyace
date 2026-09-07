<?php
/**
 * Un sujet d'Héritage (brief §6, lot G7).
 *
 * Sa page propre, donc son adresse : le pont, le buste, la chanson se
 * partagent séparément — et l'adresse d'un lieu de mémoire a vocation à finir
 * sur une plaque ou un QR code (décision 3).
 */

use App\Core\Langue;
use App\Core\Site;
use App\Core\View;
use App\Model\Heritage;
use App\Model\Media;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$rubrique = (string) $sujet['rubrique'];
$quand    = Heritage::date($sujet);
$lieu     = trim((string) ($sujet['lieu'] ?? ''));

/**
 * Le type structuré suit la rubrique. Un lieu de mémoire est un `Place` — ce
 * qui le rend éligible aux résultats de recherche locale, et c'est précisément
 * ce qu'on veut pour un pont qu'on cherche à situer.
 */
$type = match ($rubrique) {
    'lieux'        => 'Place',
    'publications' => 'Book',
    'culture'      => 'CreativeWork',
    default        => 'CreativeWork',
};

$ld = json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => $type,
    'name'        => (string) $sujet['titre'],
    'description' => $description ?? null,
    'url'         => Site::url(Langue::chemin(Heritage::chemin($sujet))),
    'address'     => $lieu !== '' ? $lieu : null,
    'about'       => ['@type' => 'Person', 'name' => 'Philippe Grégoire Yacé'],
], static fn($v): bool => $v !== null), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <nav class="fil reveal" aria-label="Fil d'Ariane">
          <ol>
            <li><a href="<?= $lien('/heritage') ?>">Héritage</a></li>
            <li><a href="<?= $lien('/heritage') ?>#<?= View::e($rubrique) ?>"><?= View::e(Heritage::rubrique($rubrique)) ?></a></li>
            <li aria-current="page"><?= View::e((string) $sujet['titre']) ?></li>
          </ol>
        </nav>

        <h1 class="t-d1 reveal"><?= View::e((string) $sujet['titre']) ?></h1>

        <?php $soustitre = trim((string) ($sujet['sous_titre'] ?? '')); ?>
        <?php if ($soustitre !== ''): ?>
          <p class="t-lead page-head__lead reveal"><?= View::e($soustitre) ?></p>
        <?php endif; ?>

        <?php if ($quand !== '' || $lieu !== ''): ?>
          <p class="kicker kicker--bare reveal">
            <?= View::e($quand) ?><?= $quand !== '' && $lieu !== '' ? ' · ' : '' ?><?= View::e($lieu) ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-7 offset-lg-2">

        <?php if ($video !== null): ?>
          <div class="arch-video reveal">
            <iframe src="https://www.youtube-nocookie.com/embed/<?= View::e($video) ?>"
                    title="<?= View::e((string) $sujet['titre']) ?>"
                    loading="lazy" allowfullscreen
                    referrerpolicy="strict-origin-when-cross-origin"></iframe>
          </div>
        <?php endif; ?>

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

        <?php $texte = trim((string) ($sujet['description'] ?? '')); ?>
        <?php if ($texte !== ''): ?>
          <div class="arch-bloc reveal"><?= View::paragraphes($texte, 't-body') ?></div>
        <?php endif; ?>

        <?php /* La source, en pied de page du sujet : le CDC §6 l'exige, et
                 c'est ce qui distingue une notice d'un propos rapporté. */ ?>
        <?php $source = trim((string) ($sujet['source'] ?? '')); ?>
        <?php if ($source !== ''): ?>
          <p class="her-source reveal">Source : <?= View::e($source) ?></p>
        <?php endif; ?>

        <div class="her-citer reveal">
          <p class="arch-fiche__lbl">Adresse de cette page</p>
          <p class="arch-fiche__url"><?= View::e(Site::url(Langue::chemin(Heritage::chemin($sujet)))) ?></p>
        </div>

      </div>
    </div>

    <?php if ($voisins !== []): ?>
      <div class="row" style="margin-top: var(--sp-9);">
        <div class="col-lg-10 offset-lg-2">
          <div class="rule reveal" style="margin-bottom: var(--sp-6);"></div>
          <p class="kicker reveal"><?= View::e(Heritage::rubrique($rubrique)) ?></p>
          <ul class="her-voisins">
            <?php foreach ($voisins as $v): ?>
              <li class="reveal">
                <a href="<?= $lien(Heritage::chemin($v)) ?>"><?= View::e((string) $v['titre']) ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
