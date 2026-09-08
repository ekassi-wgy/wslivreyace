<?php
/**
 * Biographie — le parcours par périodes (brief §3, lot G10).
 *
 * Gabarit de page : le corps seul ; l'en-tête, la navigation et le pied
 * viennent de templates/layout.php.
 *
 * La page portait cinq chapitres écrits en dur, avec un sommaire d'ancres.
 * Elle porte maintenant les périodes que le back-office tient, **chacune à son
 * adresse** : une ancre ne se partage pas et ne se date pas. Ce qui reste
 * écrit ici — contexte, citations, renvoi vers l'ouvrage — est du texte de
 * page et non du contenu catalogué.
 *
 * Reçoit du contrôleur : $periodes, $couvertures, $reperes, $situation,
 * $onglets. Voir App\Controller\BiographieController.
 */

use App\Core\Langue;
use App\Core\View;
use App\Model\Media;
use App\Model\Periode;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$titre       = t('biographie.titre_page');
$description = t('biographie.description');
$ld          = <<<'JSONLD'
{
  "@context": "https://schema.org", "@type": "Person",
  "name": "Philippe Grégoire Yacé", "birthDate": "1920", "deathDate": "1998",
  "nationality": "Ivoirienne",
  "jobTitle": "Président de l\'Assemblée nationale de Côte d\'Ivoire (1959-1980)"
}
JSONLD;
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row align-items-end" style="row-gap: var(--sp-7);">
      <div class="col-lg-7">
        <p class="kicker reveal">Philippe Grégoire Yacé</p>
        <h1 class="t-hero reveal" style="margin-bottom: var(--sp-5);">1920<br>1998</h1>
        <p class="t-lead page-head__lead reveal">
          <?= t('biographie.tete.lead') ?>
        </p>
      </div>
      <div class="col-lg-4 offset-lg-1">
        <?php /* Photographie d'archive. Pas de `loading="lazy"` ici : l'image
                 est au-dessus de la ligne de flottaison et candidate au plus
                 grand rendu de la page — la différer retarderait précisément
                 ce qu'on mesure. CRÉDIT À OBTENIR avant mise en ligne (§5) :
                 fonds, photographe ou détenteur des droits. */ ?>
        <span class="frame reveal">
          <img decoding="async" fetchpriority="high" src="/assets/img/portrait.webp"
               width="1400" height="1750"
               alt="<?= t('biographie.tete.alt') ?>">
        </span>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== CONTEXTE HISTORIQUE ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('biographie.contexte.kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-7);">
          <?= t_brut('biographie.contexte.titre') ?>
        </h2>
        <div class="row"><div class="col-md-10 col-lg-9">
          <!-- CONTEXTE HISTORIQUE — à rédiger (CDC §4.4) -->
          <p class="t-body reveal"><?= t_brut('biographie.contexte.texte') ?></p>
        </div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== LE PARCOURS, PAR PÉRIODES ===================== -->
<?php
/**
 * Les périodes de la biographie (lot G10).
 *
 * Cinq chapitres à ancres vivaient ici, écrits dans le gabarit. Ce qui change
 * n'est pas leur nombre : **une période a son adresse et ses dates**, ce qui
 * la rend citable et lui permet de porter les repères de la frise et les
 * pièces du fonds de ces années-là.
 *
 * Tant qu'aucune n'est publiée, la section le dit et ne montre rien — même
 * règle qu'Héritage. Cinq intertitres suivis de « texte à rédiger » sur la
 * biographie d'une personne réelle valent moins qu'une phrase honnête.
 */
?>
<section class="section section--sunk" id="parcours">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('biographie.parcours.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('biographie.parcours.titre') ?></h2>
      </div>
    </div>

    <?php if ($periodes === []): ?>
      <div class="row">
        <div class="col-lg-7 offset-lg-2">
          <p class="t-lead reveal"><?= t_brut('biographie.parcours.vide') ?></p>
        </div>
      </div>
    <?php else: ?>
      <div class="row">
        <div class="col-lg-10 offset-lg-2">
          <ol class="bio-periodes">
            <?php foreach ($periodes as $i => $per): ?>
              <?php
                $couverture = $couvertures[(int) $per['id']] ?? null;
                $chapo      = trim((string) ($per['sous_titre'] ?? ''));
              ?>
              <li class="reveal">
                <a class="bio-per" href="<?= $lien(Periode::chemin($per)) ?>">
                  <span class="bio-per__vue">
                    <?php if ($couverture !== null): ?>
                      <?php $srcset = Media::srcset($couverture); ?>
                      <img loading="lazy" decoding="async"
                           src="<?= View::e(Media::urlVignette((string) $couverture['fichier'])) ?>"
                           <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 28vw"' ?>
                           alt="<?= View::e(Media::alternative($couverture)) ?>">
                    <?php else: ?>
                      <?php /* Une période sans photographie garde sa place : le
                               texte arrive avant l'image, et attendre les
                               visuels retarderait la publication du récit. */ ?>
                      <span class="bio-per__vide" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
                    <?php endif; ?>
                  </span>

                  <span class="bio-per__corps">
                    <span class="bio-per__ans"><?= View::e(Periode::annees($per)) ?></span>
                    <span class="bio-per__t"><?= View::e((string) $per['titre']) ?></span>
                    <?php if ($chapo !== ''): ?>
                      <span class="bio-per__chapo"><?= View::e($chapo) ?></span>
                    <?php endif; ?>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ol>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ===================== FRISE CHRONOLOGIQUE ===================== -->
<?php
/**
 * La frise vient de la base — table `repere`, écran « Repères » du
 * back-office (lot G0, README §9). Elle portait ses dates en dur jusqu'ici :
 * ce qu'un éditeur saisissait n'apparaissait nulle part.
 *
 * **Les onglets sont les périodes de la biographie** depuis le lot G10, et non
 * plus quatre tranches d'années figées dans le code : un repère se range sous
 * la période qui contient son année de classement. Ne paraissent que les
 * périodes qui ont recueilli un repère — un onglet qui donne sur une frise
 * vide est un lien mort — et un repère qu'aucune période ne couvre reste sur
 * la frise, sans onglet pour le filtrer.
 */
?>
<?php if ($reperes !== []): ?>
<section class="section" id="chronologie">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-7);">
      <div class="col-lg-2"><p class="section-num reveal">03</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('biographie.chrono.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('biographie.chrono.titre') ?></h2>
      </div>
    </div>

<?php /* Un seul groupe de périodes ne se filtre pas : les chips n'offriraient
         qu'un choix, « Tout », et un autre qui donne le même résultat. */ ?>
<?php if (count($onglets) > 1): ?>
    <div class="row" style="margin-bottom: var(--sp-6);">
      <div class="col-lg-10 offset-lg-2">
        <div class="chips reveal" role="group" aria-label="<?= t('biographie.chrono.filtrer') ?>">
          <button class="chip is-active" type="button" data-period="tout" aria-pressed="true"><?= t('biographie.chrono.tout') ?></button>
<?php foreach ($onglets as $idPeriode => $per): ?>
          <button class="chip" type="button" data-period="p<?= (int) $idPeriode ?>" aria-pressed="false"><?= View::e((string) $per['titre']) ?></button>
<?php endforeach; ?>
        </div>
      </div>
    </div>
<?php endif; ?>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <div class="chrono">
<?php foreach ($reperes as $r): ?>
<?php
  /* L'identifiant du dépliant vient de la clé primaire : deux repères de même
     titre ne peuvent pas se retrouver avec la même ancre. */
  $cible  = 'repere-' . (int) $r['id'];
  $notice = trim((string) ($r['notice'] ?? ''));
  $source = trim((string) ($r['source'] ?? ''));
  $depliable = $notice !== '' || $source !== '';

  /* La période du repère, calculée par le contrôleur d'après son année.
     `hors` quand aucune période publiée ne couvre cette année : aucun onglet ne
     porte cette valeur, l'entrée se lit donc sous « Tout » et disparaît dès
     qu'une période est choisie — ce qui est exact, elle n'est dans aucune. */
  $sien = $situation[(int) $r['id']] ?? null;
?>
          <div class="chrono__item reveal" data-period="<?= $sien === null ? 'hors' : 'p' . (int) $sien ?>">
<?php if ($depliable): ?>
            <button class="chrono__head" type="button" data-bs-toggle="collapse"
                    data-bs-target="#<?= $cible ?>" aria-expanded="false" aria-controls="<?= $cible ?>">
              <span class="chrono__year"><?= View::e((string) $r['annee']) ?></span>
              <span class="chrono__t"><?= View::e((string) $r['titre']) ?></span>
              <span class="chrono__sign" aria-hidden="true"></span>
            </button>
            <div class="collapse" id="<?= $cible ?>">
              <div class="chrono__body">
                <div class="row"><div class="col-lg-8 offset-lg-3">
<?= View::paragraphes($notice, 't-body') ?>
<?php if ($source !== ''): ?>
                  <p class="chrono__src"><?= View::e($source) ?></p>
<?php endif; ?>
                </div></div>
              </div>
            </div>
<?php else: ?>
<?php /* Sans notice ni source, rien à déplier : un bouton qui n'ouvre rien
         ment au clavier comme à la souris. La date reste, en clair. */ ?>
            <div class="chrono__head chrono__head--plat">
              <span class="chrono__year"><?= View::e((string) $r['annee']) ?></span>
              <span class="chrono__t"><?= View::e((string) $r['titre']) ?></span>
            </div>
<?php endif; ?>
          </div>
<?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== CITATIONS ===================== -->
<section class="section section--dark">
  <div class="shell">
    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <p class="kicker reveal"><?= t('biographie.citations.kicker') ?></p>
        <!-- CITATIONS — aucun propos ne doit être attribué sans source vérifiée -->
        <blockquote class="quote reveal" style="margin:0;"><?= t('biographie.citations.texte') ?></blockquote>
        <p class="quote__src reveal"><?= t('biographie.citations.source') ?></p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== GALERIE ===================== -->
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">04</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('biographie.portraits.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('biographie.portraits.titre') ?></h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="<?= Langue::chemin('/archives') ?>?categorie=portrait"><?= t('biographie.portraits.lien') ?></a>
      </div>
    </div>

    <?php
    /* Les portraits de la médiathèque, catégorie « portrait » : c'est la même
       matière que la galerie d'archives, vue par une entrée. La planche
       complète et sa visionneuse vivent sur /archives. */
    $portraits = Media::listerPubliees('portrait', 4);
    $trame     = ['large', 'haut', 'carre', 'pano'];
    ?>

    <?php if ($portraits === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <p class="t-lead reveal"><?= t_brut('biographie.portraits.vide') ?></p>
        </div>
      </div>

    <?php else: ?>

      <ul class="gal">
        <?php foreach ($portraits as $i => $img): ?>
          <li class="gal__i gal__i--<?= $trame[$i % count($trame)] ?> reveal">
            <a class="gal__lien" href="<?= Langue::chemin('/archives') ?>?categorie=portrait">
              <?php $srcset = Media::srcset($img); ?>
              <img loading="lazy" decoding="async"
                   src="<?= View::e(Media::urlVignette((string) $img['fichier'])) ?>"
                   <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 767px) 50vw, 45vw"' ?>
                   alt="<?= View::e(Media::alternative($img)) ?>">
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

    <?php endif; ?>
  </div>
</section>

<!-- ===================== RENVOI VERS L'OUVRAGE ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row align-items-center" style="row-gap: var(--sp-7);">
      <div class="col-lg-7 offset-lg-2">
        <p class="kicker reveal"><?= t('biographie.ouvrage.kicker') ?></p>
        <h2 class="t-d2 reveal" style="margin-bottom: var(--sp-6);">
          <?= t_brut('biographie.ouvrage.titre') ?>
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="<?= Langue::chemin('/le-livre') ?>">
            <?= t('biographie.ouvrage.cta') ?>
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
