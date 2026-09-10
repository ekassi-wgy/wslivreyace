<?php
/** Gabarit de page — le corps seul ; l'en-tête, la navigation et le pied
    viennent de templates/layout.php. */
$titre       = t_nu('accueil.titre_page');
$description = t_nu('accueil.description');
$ld = json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'Book',
    'name'       => 'Philippe Grégoire Yacé : une destinée (1920-1998)',
    // La langue de la page, et non « fr » en dur : un balisage qui contredit
    // `<html lang>` désoriente le moteur au lieu de le renseigner (lot G11).
    'inLanguage' => App\Core\Langue::code(),
    'about'      => [
        '@type'       => 'Person',
        'name'        => 'Philippe Grégoire Yacé',
        'birthDate'   => '1920',
        'deathDate'   => '1998',
        'nationality' => t('accueil.ld_nationalite'),
        'jobTitle'    => t('accueil.ld_fonction'),
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

/**
 * Repères de la frise, lus une fois pour deux emplacements : le lien du hero,
 * qui n'a de sens que s'il mène quelque part, et la section plus bas.
 * Table `repere`, écran « Repères » du back-office (lot G0, README §9).
 */
$jalons = App\Model\Repere::listerEnAvant(4);

/**
 * La préface, si l'éditeur l'a mise en avant (lot G2). Le bandeau ne paraît
 * qu'à ce moment-là : c'est l'argument que le brief veut voir dès l'accueil si
 * la préface présidentielle se confirme, et rien avant.
 */
$preface = App\Controller\LivreController::preface(App\Model\Parametre::toutes());

/**
 * Les points de vente publiés (lot G12). Trois villes vivaient en dur ici et
 * dans le gabarit du livre, sous une ligne « à renseigner » que rien ne
 * permettait de renseigner. Table `point_de_vente`, écran « Points de vente »
 * du back-office.
 */
$pointsDeVente = App\Model\PointDeVente::listerPublies();

/**
 * La citation du bandeau « Extrait » (lot G14). Elle venait du lexique, sous
 * la forme d'un « Emplacement réservé à un extrait de l'ouvrage » qui
 * s'affichait tel quel en ligne. Table `citation`, écran « Citations » du
 * back-office, un seul extrait mis en avant par emplacement.
 *
 * `null` fait disparaître la section entière : un bandeau sombre pleine
 * largeur autour d'un vide se remarquerait plus que son absence.
 */
$citation = App\Model\Citation::affichee('accueil');
?>

<!-- ===================== HERO ===================== -->
<section class="hero">
  <div id="heroCarousel" class="carousel slide carousel-fade hero__carousel"
       data-bs-ride="carousel" data-bs-interval="7000">
    <div class="carousel-inner">

      <!-- CONTENU PROVISOIRE — textes à valider par l'éditeur -->
      <div class="carousel-item active hero__slide">
        <div class="hero__media">
          <img class="hero__img" src="assets/img/hero-1.svg" alt="">
        </div>
        <div class="shell hero__content">
          <div class="row">
            <div class="col-lg-7 col-xl-6">
              <p class="hero__count hero__fade" style="--d:120ms">01 — 03</p>
              <h1 class="t-hero hero__title">
                <span class="mask"><span class="mask__i" style="--d:200ms">Une</span></span>
                <span class="mask"><span class="mask__i" style="--d:320ms">destinée</span></span>
              </h1>
              <p class="t-lead hero__lead hero__fade" style="--d:560ms">
                <?= t('accueil.hero.1_lead') ?>
              </p>
              <div class="hero__cta hero__fade" style="--d:680ms">
                <a class="btn-pgy" href="<?= App\Core\Langue::chemin('/le-livre') ?>">
                  <?= t('accueil.hero.1_cta') ?>
                  <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
                </a>
<?php if ($jalons !== []): ?>
                <a class="link" href="#reperes"><?= t('accueil.hero.1_lien') ?></a>
<?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item hero__slide">
        <div class="hero__media">
          <img class="hero__img" src="assets/img/hero-2.svg" alt="">
        </div>
        <div class="shell hero__content">
          <div class="row">
            <div class="col-lg-7 col-xl-6">
              <p class="hero__count hero__fade" style="--d:120ms">02 — 03</p>
              <h1 class="t-hero hero__title">
                <span class="mask"><span class="mask__i" style="--d:200ms">1920</span></span>
                <span class="mask"><span class="mask__i" style="--d:320ms">1998</span></span>
              </h1>
              <p class="t-lead hero__lead hero__fade" style="--d:560ms">
                <?= t_brut('accueil.hero.2_lead') ?>
              </p>
              <div class="hero__cta hero__fade" style="--d:680ms">
                <a class="btn-pgy" href="<?= App\Core\Langue::chemin('/biographie') ?>">
                  <?= t('accueil.hero.2_cta') ?>
                  <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item hero__slide">
        <div class="hero__media">
          <img class="hero__img" src="assets/img/hero-3.svg" alt="">
        </div>
        <div class="shell hero__content">
          <div class="row">
            <div class="col-lg-7 col-xl-6">
              <p class="hero__count hero__fade" style="--d:120ms">03 — 03</p>
              <h1 class="t-hero hero__title">
                <span class="mask"><span class="mask__i" style="--d:200ms"><?= t('accueil.hero.3_titre') ?></span></span>
              </h1>
              <p class="t-lead hero__lead hero__fade" style="--d:560ms">
                <?= t_brut('accueil.hero.3_lead') ?>
              </p>
              <div class="hero__cta hero__fade" style="--d:680ms">
                <a class="btn-pgy" href="<?= App\Core\Langue::chemin('/commander') ?>">
                  <?= t('accueil.hero.3_cta') ?>
                  <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
                </a>
                <a class="link" href="<?= App\Core\Langue::chemin('/le-livre') ?>#acheter"><?= t('accueil.hero.3_lien') ?></a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Indicateurs : peau maison branchée sur l'instance Bootstrap -->
  <div class="hero__nav" role="tablist" aria-label="<?= t('accueil.hero.diapos_aria') ?>">
    <button class="hero__dot is-active" type="button" role="tab" aria-current="true">
      <i aria-hidden="true"></i>01
    </button>
    <button class="hero__dot" type="button" role="tab" aria-current="false">
      <i aria-hidden="true"></i>02
    </button>
    <button class="hero__dot" type="button" role="tab" aria-current="false">
      <i aria-hidden="true"></i>03
    </button>
  </div>

  <div class="hero__progress" aria-hidden="true"><i></i></div>
</section>

<?php /* ============ PRÉFACE MISE EN AVANT ============ */ ?>
<?php if ($preface['avant']): ?>
<section class="section section--dark" style="padding-block: var(--sp-9);">
  <div class="shell">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <p class="kicker reveal"><?= t('accueil.preface.kicker') ?></p>
        <?php if ($preface['extrait'] !== ''): ?>
          <blockquote class="quote reveal" style="margin: 0;"><?= App\Core\View::e($preface['extrait']) ?></blockquote>
        <?php endif; ?>
        <p class="quote__src reveal">
          <?= App\Core\View::e($preface['auteur']) ?><?php
            echo $preface['qualite'] === '' ? '' : '<br>' . App\Core\View::e($preface['qualite']);
          ?>
        </p>
        <p class="reveal" style="margin-top: var(--sp-6);">
          <a class="link" href="<?= App\Core\Langue::chemin('/le-livre') ?>#preface"><?= t('accueil.preface.lien') ?></a>
        </p>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>


<!-- ===================== 01 · MANIFESTE ===================== -->
<section class="section" id="homme">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2">
        <p class="section-num reveal">01</p>
      </div>
      <div class="col-lg-9 col-xl-8">
        <p class="kicker reveal"><?= t('accueil.homme.kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-7);">
          <?= t_brut('accueil.homme.titre') ?>
        </h2>
        <div class="row">
          <div class="col-md-10 col-lg-9">
            <p class="t-body reveal"><?= t_brut('accueil.homme.p1') ?></p>
            <p class="t-body reveal"><?= t_brut('accueil.homme.p2') ?></p>
            <p class="reveal" style="margin-top: var(--sp-6);">
              <a class="link" href="<?= App\Core\Langue::chemin('/biographie') ?>"><?= t('accueil.homme.lien') ?></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== 02 · L'OUVRAGE ===================== -->
<section class="section section--sunk" id="ouvrage">
  <div class="shell">
    <div class="row align-items-center" style="row-gap: var(--sp-9);">

      <div class="col-lg-5 offset-lg-1 order-lg-2">
        <p class="section-num reveal">02</p>
        <p class="kicker reveal"><?= t('accueil.ouvrage.kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);"><?= t('accueil.ouvrage.titre') ?></h2>
        <p class="t-lead reveal" style="margin-bottom: var(--sp-7);"><?= t_brut('accueil.ouvrage.lead') ?></p>

        <!-- FICHE TECHNIQUE — valeurs provisoires -->
        <dl class="specs reveal" style="margin-bottom: var(--sp-7);">
          <div><dt><?= t('accueil.ouvrage.auteur') ?></dt><dd><?= t('accueil.ouvrage.a_renseigner') ?></dd></div>
          <div><dt><?= t('accueil.ouvrage.editeur') ?></dt><dd><?= t('accueil.ouvrage.a_renseigner') ?></dd></div>
          <div><dt><?= t('accueil.ouvrage.parution') ?></dt><dd><?= t('accueil.ouvrage.a_renseigner') ?></dd></div>
          <div><dt><?= t('accueil.ouvrage.format') ?></dt><dd><?= t('accueil.ouvrage.format_valeur') ?></dd></div>
          <div><dt><?= t('accueil.ouvrage.pages') ?></dt><dd><?= t('accueil.ouvrage.a_renseigner') ?></dd></div>
          <div><dt><?= t('accueil.ouvrage.isbn') ?></dt><dd><?= t('accueil.ouvrage.a_renseigner') ?></dd></div>
        </dl>

        <a class="btn-pgy reveal" href="<?= App\Core\Langue::chemin('/commander') ?>">
          <?= t('accueil.ouvrage.cta') ?>
          <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
        </a>
      </div>

      <div class="col-lg-5 order-lg-1">
        <span class="frame reveal">
          <img loading="lazy" decoding="async" src="assets/img/couverture.svg" alt="<?= t('accueil.ouvrage.alt') ?>">
        </span>
      </div>

    </div>
  </div>
</section>

<!-- ===================== 03 · REPÈRES ===================== -->
<?php
/**
 * Les repères viennent de la base, comme la frise de la biographie — table
 * `repere`, écran « Repères » du back-office (lot G0, README §9).
 *
 * Quatre au plus, et **choisis par l'éditeur** : une case sur la fiche du
 * repère décide de ce qui remonte ici. L'accueil donne l'échelle, la
 * biographie donne la frise entière.
 *
 * Le titre compte donc ce qu'il affiche réellement plutôt que d'annoncer
 * quatre dates devant trois — la matière est administrable, elle peut varier.
 *
 * `$jalons` est lu en tête de gabarit : le lien du hero en dépend aussi.
 */
$compte = [
    1 => t('accueil.reperes.compte_1'),
    2 => t('accueil.reperes.compte_2'),
    3 => t('accueil.reperes.compte_3'),
    4 => t('accueil.reperes.compte_4'),
];
?>
<?php if ($jalons !== []): ?>
<section class="section" id="reperes">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">03</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('accueil.reperes.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= $compte[count($jalons)] ?? t('accueil.reperes.compte_autre') ?><?= t_brut('accueil.reperes.titre_suite') ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <div class="tl">
<?php foreach ($jalons as $j): ?>
          <div class="tl__item reveal">
            <div class="tl__year"><?= App\Core\View::e((string) $j['annee']) ?></div>
            <div class="tl__body">
              <h3 class="t-d3"><?= App\Core\View::e((string) $j['titre']) ?></h3>
<?= App\Core\View::paragraphes((string) ($j['notice'] ?? ''), 't-body') ?>
            </div>
          </div>
<?php endforeach; ?>
        </div>
      </div>
    </div>

<?php /* La frise entière est sur la biographie ; l'accueil n'en montre que
         l'amorce. Le lien n'a de sens que s'il y a plus à voir. */ ?>
<?php if (count($jalons) >= 4): ?>
    <div class="row" style="margin-top: var(--sp-7);">
      <div class="col-lg-10 offset-lg-2">
        <a class="link reveal" href="<?= App\Core\Langue::chemin('/biographie') ?>#chronologie"><?= t('accueil.reperes.lien') ?></a>
      </div>
    </div>
<?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- ===================== CITATION ===================== -->
<?php if ($citation !== null): ?>
<section class="section section--dark">
  <div class="shell">
    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <p class="kicker reveal"><?= t('accueil.extrait.kicker') ?></p>
        <blockquote class="quote reveal" style="margin:0;"><?= App\Core\View::e((string) $citation['texte']) ?></blockquote>
        <?php if (trim((string) ($citation['source'] ?? '')) !== ''): ?>
          <p class="quote__src reveal"><?= App\Core\View::e((string) $citation['source']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== 04 · GALERIE ===================== -->
<section class="section" id="galerie">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">04</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('accueil.galerie.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('accueil.galerie.titre') ?></h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="<?= App\Core\Langue::chemin('/archives') ?>"><?= t('accueil.galerie.lien') ?></a>
      </div>
    </div>

    <?php
    /* Les quatre premières archives publiées, dans l'ordre de la médiathèque.
       La planche complète et sa visionneuse vivent sur /archives : l'accueil
       n'en montre que la trame. */
    $planche = App\Model\Media::listerPubliees(null, 4);
    $trame   = ['large', 'haut', 'carre', 'pano'];
    ?>

    <?php if ($planche === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <p class="t-lead reveal"><?= t_brut('accueil.galerie.vide') ?></p>
        </div>
      </div>

    <?php else: ?>

      <ul class="gal">
        <?php foreach ($planche as $i => $img): ?>
          <li class="gal__i gal__i--<?= $trame[$i % count($trame)] ?> reveal">
            <a class="gal__lien" href="<?= App\Core\Langue::chemin('/archives') ?>">
              <?php $srcset = App\Model\Media::srcset($img); ?>
              <img loading="lazy" decoding="async"
                   src="<?= App\Core\View::e(App\Model\Media::urlVignette((string) $img['fichier'])) ?>"
                   <?= $srcset === '' ? '' : 'srcset="' . App\Core\View::e($srcset) . '" sizes="(max-width: 767px) 50vw, 45vw"' ?>
                   alt="<?= App\Core\View::e(App\Model\Media::alternative($img)) ?>">
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

    <?php endif; ?>
  </div>
</section>

<!-- ===================== 05 · TÉMOIGNAGES ===================== -->
<section class="section section--sunk" id="temoignages">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">05</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('accueil.temoignages.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('accueil.temoignages.titre') ?></h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="<?= App\Core\Langue::chemin('/temoignages') ?>#deposer"><?= t('accueil.temoignages.deposer') ?></a>
      </div>
    </div>

    <?php
    /* Les trois derniers témoignages validés. Rien ici n'est écrit en dur :
       ce qui s'affiche a été relu et publié depuis la file de modération, et
       la page /temoignages porte la liste complète. */
    $apercu = App\Model\Temoignage::listerPubliees(3);
    ?>

    <?php if ($apercu === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <p class="t-lead reveal"><?= t_brut('accueil.temoignages.vide') ?></p>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="btn-pgy btn-pgy--ghost" href="<?= App\Core\Langue::chemin('/temoignages') ?>#deposer">
              <?= t('accueil.temoignages.deposer') ?> <span class="btn-pgy__arrow" aria-hidden="true">→</span>
            </a>
          </p>
        </div>
      </div>

    <?php else: ?>

      <div class="row" style="row-gap: var(--sp-7);">
        <?php foreach ($apercu as $t): ?>
          <div class="col-md-4">
            <div class="testi__i reveal">
              <?php /* Coupé à 260 caractères : la page dédiée porte le texte
                       entier, l'accueil n'en montre que l'entrée. */ ?>
              <p class="testi__q"><?= App\Core\View::e(mb_strimwidth((string) $t['contenu'], 0, 260, '…')) ?></p>
              <p class="testi__a">
                <?= App\Core\View::e($t['auteur_fonction'] ?? '') ?: t('accueil.temoignages.defaut') ?>
                <span><?= App\Core\View::e($t['auteur_nom']) ?></span>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="row" style="margin-top: var(--sp-7);">
        <div class="col-12">
          <a class="link reveal" href="<?= App\Core\Langue::chemin('/temoignages') ?>"><?= t('accueil.temoignages.tous') ?></a>
        </div>
      </div>

    <?php endif; ?>
  </div>
</section>

<!-- ===================== 06 · ACTUALITÉS ===================== -->
<section class="section" id="actualites">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">06</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('accueil.actualites.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('accueil.actualites.titre') ?></h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="<?= App\Core\Langue::chemin('/actualites') ?>"><?= t('accueil.actualites.lien') ?></a>
      </div>
    </div>

    <?php
    /* Les trois dernières actualités publiées. Comme pour les témoignages,
       rien n'est écrit en dur : la page /actualites porte la liste entière. */
    $dernieres = App\Model\Actualite::listerPubliees(null, 3);
    ?>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">

        <?php if ($dernieres === []): ?>

          <p class="t-lead reveal"><?= t_brut('accueil.actualites.vide') ?></p>

        <?php else: ?>

          <div class="news">
            <?php foreach ($dernieres as $a): ?>
              <a class="news__i reveal" href="<?= App\Core\Langue::chemin('/actualites/' . (string) $a['slug']) ?>">
                <time class="news__date" datetime="<?= App\Core\View::e(App\Core\DateLisible::iso((string) $a['publie_le'])) ?>">
                  <?= App\Core\DateLisible::longue((string) $a['publie_le']) ?>
                </time>
                <span class="news__t"><?= App\Core\View::e((string) $a['titre']) ?></span>
                <span class="news__cat"><?= App\Core\View::e(App\Model\Actualite::categorie((string) $a['categorie'])) ?></span>
              </a>
            <?php endforeach; ?>
          </div>

        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<!-- ===================== 07 · COMMANDER ===================== -->
<section class="section section--sunk" id="commander">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">07</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('accueil.commander.kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);">
          <?= t_brut('accueil.commander.titre') ?>
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="<?= App\Core\Langue::chemin('/commander') ?>">
            <?= t('accueil.commander.cta') ?>
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <!-- POINTS DE VENTE — table `point_de_vente`, lot G12 -->
        <?php require dirname(__DIR__) . '/partials/points-de-vente.php'; ?>
      </div>
    </div>
  </div>
</section>
