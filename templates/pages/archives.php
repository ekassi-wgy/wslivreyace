<?php
/**
 * Le fonds d'archives (brief §4, lot G4).
 *
 * La planche liste des **notices** et non des fichiers : chaque tuile mène à
 * une page propre, à son adresse, partageable seule. C'est ce que le §9 du
 * brief demande, et ce que la visionneuse d'avant — une surimpression sans
 * adresse — ne pouvait pas donner.
 */

use App\Core\Langue;
use App\Core\View;
use App\Model\Archive;
use App\Model\Media;

$titreCat = $categorie === null ? null : Archive::categorie($categorie);

$titre = $titreCat === null
    ? 'Archives — Philippe Grégoire Yacé'
    : $titreCat . ' — Archives Philippe Grégoire Yacé';

$description = $titreCat === null
    ? "Le fonds numérique Philippe Grégoire Yacé : photographies, vidéos, discours, documents, presse et correspondances."
    : $titreCat . " du fonds numérique Philippe Grégoire Yacé.";

/** Toute adresse interne passe par la langue courante (lot G1). */
$lien = static fn(string $chemin): string => Langue::chemin($chemin);

/** Le lien d'un filtre, en conservant la recherche en cours. */
$filtre = static function (?string $cat, ?int $an) use ($lien, $recherche): string {
    $chemin = $cat === null ? '/archives' : '/archives/' . $cat;
    $q = array_filter(['annee' => $an, 'q' => $recherche === '' ? null : $recherche]);

    return $lien($chemin) . ($q === [] ? '' : '?' . http_build_query($q));
};

/** Quatre proportions qui se répètent : la planche garde son rythme. */
$formes = ['gal__i--large', 'gal__i--haut', 'gal__i--carre', 'gal__i--pano'];
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Fonds numérique</p>
        <h1 class="t-d1 reveal"><?= View::e($titreCat ?? 'Archives.') ?></h1>
        <p class="t-lead page-head__lead reveal">
          <?php if ($categorie === null): ?>
            Photographies, vidéos, discours, documents, presse et correspondances.
            Une bibliothèque qui s'enrichit — chaque pièce a sa page et son adresse.
          <?php else: ?>
            <?= View::e($titreCat) ?> du fonds Philippe Grégoire Yacé.
            <a class="link" href="<?= $lien('/archives') ?>">Revenir au fonds entier</a>
          <?php endif; ?>
        </p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">

    <?php /* --- Recherche et filtres (brief §4) ------------------------- */ ?>
    <div class="row" style="margin-bottom: var(--sp-6);">
      <div class="col-lg-10 offset-lg-2">

        <form class="arch-rech reveal" method="get" action="<?= $categorie === null ? $lien('/archives') : $lien('/archives/' . $categorie) ?>" role="search">
          <label class="arch-rech__label" for="q">Rechercher dans le fonds</label>
          <div class="arch-rech__ligne">
            <input class="arch-rech__champ" type="search" id="q" name="q"
                   value="<?= View::e($recherche) ?>"
                   placeholder="Un nom, un lieu, une année, un mot du texte…">
            <?php /* L'année en cours voyage avec la recherche : chercher ne
                     doit pas défaire le filtre qu'on venait de poser. */ ?>
            <?php if ($annee !== null): ?>
              <input type="hidden" name="annee" value="<?= (int) $annee ?>">
            <?php endif; ?>
            <button class="btn-pgy btn-pgy--sm" type="submit">Chercher</button>
          </div>
        </form>

        <?php if ($comptes !== []): ?>
          <div class="chips reveal" style="margin-top: var(--sp-5);" role="group" aria-label="Filtrer par catégorie">
            <a class="chip<?= $categorie === null ? ' is-active' : '' ?>" href="<?= $filtre(null, $annee) ?>">Tout</a>
            <?php foreach ($comptes as $cle => $n): ?>
              <a class="chip<?= $cle === $categorie ? ' is-active' : '' ?>" href="<?= $filtre($cle, null) ?>">
                <span aria-hidden="true"><?= Archive::signe($cle) ?></span>
                <?= View::e(Archive::categorie($cle)) ?>
                <span class="chip__n"><?= (int) $n ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($annees !== []): ?>
          <div class="chips chips--annees reveal" style="margin-top: var(--sp-4);" role="group" aria-label="Filtrer par année">
            <a class="chip chip--fin<?= $annee === null ? ' is-active' : '' ?>" href="<?= $filtre($categorie, null) ?>">Toutes les années</a>
            <?php foreach ($annees as $an => $n): ?>
              <a class="chip chip--fin<?= $an === $annee ? ' is-active' : '' ?>" href="<?= $filtre($categorie, $an) ?>"><?= (int) $an ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </div>

    <?php /* --- La planche ---------------------------------------------- */ ?>
    <div class="row">
      <div class="col-lg-10 offset-lg-2">

        <?php if ($notices === []): ?>
          <p class="t-body reveal">
            <?php if ($recherche !== '' || $annee !== null): ?>
              Aucune pièce ne correspond à cette recherche.
              <a class="link" href="<?= $filtre($categorie, null) ?>">Effacer les filtres</a>
            <?php else: ?>
              Le fonds se constitue. Les premières pièces seront versées prochainement.
            <?php endif; ?>
          </p>
        <?php else: ?>

          <p class="kicker kicker--bare reveal" style="margin-bottom: var(--sp-5);">
            <?= count($notices) ?> pièce<?= count($notices) > 1 ? 's' : '' ?>
          </p>

          <ul class="gal">
            <?php foreach ($notices as $i => $n): ?>
              <?php
                $couverture = $n['couverture'] ?? null;
                $quand      = Archive::date($n);
              ?>
              <li class="gal__i <?= $formes[$i % 4] ?> reveal">
                <a class="gal__lien" href="<?= $lien(Archive::chemin($n)) ?>">
                  <?php if ($couverture !== null): ?>
                    <?php $srcset = Media::srcset($couverture); ?>
                    <img loading="lazy" decoding="async"
                         src="<?= View::e(Media::urlVignette((string) $couverture['fichier'])) ?>"
                         <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 40vw"' ?>
                         alt="<?= View::e(Media::alternative($couverture)) ?>">
                  <?php else: ?>
                    <?php /* Un discours, un document sans image numérisée : la
                             tuile porte le pictogramme de sa catégorie plutôt
                             qu'un cadre vide. */ ?>
                    <span class="gal__signe" aria-hidden="true"><?= Archive::signe((string) $n['categorie']) ?></span>
                  <?php endif; ?>

                  <span class="gal__legende">
                    <?php if ($quand !== ''): ?>
                      <span class="gal__quand"><?= View::e($quand) ?></span>
                    <?php endif; ?>
                    <?= View::e((string) $n['titre']) ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

        <?php endif; ?>

      </div>
    </div>
  </div>
</section>
