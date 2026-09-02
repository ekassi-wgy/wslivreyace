<?php
/** Gabarit de page — le corps seul ; l'en-tête, la navigation et le pied
    viennent de templates/layout.php. */
$titre       = 'Philippe Grégoire Yacé — Une destinée (1920-1998)';
$description = "La biographie de Philippe Grégoire Yacé, figure de la construction de l'État ivoirien.";
$ld          = <<<'JSONLD'
{
  "@context": "https://schema.org", "@type": "Book",
  "name": "Philippe Grégoire Yacé : une destinée (1920-1998)", "inLanguage": "fr",
  "about": { "@type": "Person", "name": "Philippe Grégoire Yacé",
    "birthDate": "1920", "deathDate": "1998", "nationality": "Ivoirienne",
    "jobTitle": "Président de l\'Assemblée nationale de Côte d\'Ivoire (1959-1980)" }
}
JSONLD;
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
                La biographie de Philippe Grégoire Yacé — un parcours qui épouse
                celui de la Côte d'Ivoire, de la veille de l'indépendance aux
                dernières années du siècle.
              </p>
              <div class="hero__cta hero__fade" style="--d:680ms">
                <a class="btn-pgy" href="/le-livre">
                  Découvrir l'ouvrage
                  <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
                </a>
                <a class="link" href="#reperes">Parcourir les repères</a>
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
                Soixante-dix-huit années traversées par la naissance d'une nation.
                <em>Texte à compléter par l'éditeur.</em>
              </p>
              <div class="hero__cta hero__fade" style="--d:680ms">
                <a class="btn-pgy" href="/biographie">
                  L'homme
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
                <span class="mask"><span class="mask__i" style="--d:200ms">L'ouvrage</span></span>
              </h1>
              <p class="t-lead hero__lead hero__fade" style="--d:560ms">
                Un volume relié, richement documenté et illustré d'archives inédites.
                <em>Descriptif à compléter par l'éditeur.</em>
              </p>
              <div class="hero__cta hero__fade" style="--d:680ms">
                <a class="btn-pgy" href="/le-livre#acheter">
                  Commander
                  <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
                </a>
                <a class="link" href="/le-livre#acheter">Points de vente</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Indicateurs : peau maison branchée sur l'instance Bootstrap -->
  <div class="hero__nav" role="tablist" aria-label="Diapositives">
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

<!-- ===================== 01 · MANIFESTE ===================== -->
<section class="section" id="homme">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2">
        <p class="section-num reveal">01</p>
      </div>
      <div class="col-lg-9 col-xl-8">
        <p class="kicker reveal">L'homme</p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-7);">
          Une vie publique adossée<br>à la construction d'un État.
        </h2>
        <div class="row">
          <div class="col-md-10 col-lg-9">
            <p class="t-body reveal">
              <em>Texte de présentation à rédiger par l'éditeur.</em> Ce paragraphe
              tient la place du chapeau introductif : il pose en quelques lignes
              la stature du personnage et l'angle retenu par l'ouvrage.
            </p>
            <p class="t-body reveal">
              Président de l'Assemblée nationale de Côte d'Ivoire de 1959 à 1980,
              secrétaire général du PDCI-RDA, puis président du Conseil économique
              et social — Philippe Grégoire Yacé occupe pendant quatre décennies
              une position centrale dans la vie institutionnelle du pays.
            </p>
            <p class="reveal" style="margin-top: var(--sp-6);">
              <a class="link" href="/biographie">Lire la biographie complète</a>
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
        <p class="kicker reveal">L'ouvrage</p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);">Une destinée</h2>
        <p class="t-lead reveal" style="margin-bottom: var(--sp-7);">
          <em>Quatrième de couverture à fournir.</em> Quelques lignes suffisent :
          l'objet du livre, sa méthode, ce qu'il apporte de neuf.
        </p>

        <!-- FICHE TECHNIQUE — valeurs provisoires -->
        <dl class="specs reveal" style="margin-bottom: var(--sp-7);">
          <div><dt>Auteur</dt><dd>À renseigner</dd></div>
          <div><dt>Éditeur</dt><dd>À renseigner</dd></div>
          <div><dt>Parution</dt><dd>À renseigner</dd></div>
          <div><dt>Format</dt><dd>Relié, 240 × 310 mm</dd></div>
          <div><dt>Pages</dt><dd>À renseigner</dd></div>
          <div><dt>ISBN</dt><dd>À renseigner</dd></div>
        </dl>

        <a class="btn-pgy reveal" href="/le-livre#acheter">
          Commander l'ouvrage
          <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
        </a>
      </div>

      <div class="col-lg-5 order-lg-1">
        <span class="frame reveal">
          <img loading="lazy" decoding="async" src="assets/img/couverture.svg" alt="Couverture de l'ouvrage — visuel provisoire">
        </span>
      </div>

    </div>
  </div>
</section>

<!-- ===================== 03 · REPÈRES ===================== -->
<section class="section" id="reperes">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">03</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Repères</p>
        <h2 class="t-d1 reveal">Quatre dates,<br>un siècle ivoirien.</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <!-- CHRONOLOGIE — dates à faire valider par l'éditeur avant mise en ligne -->
        <div class="tl">
          <div class="tl__item reveal">
            <div class="tl__year">1920</div>
            <div class="tl__body">
              <h3 class="t-d3">Naissance</h3>
              <p class="t-body"><em>Notice à compléter.</em></p>
            </div>
          </div>
          <div class="tl__item reveal">
            <div class="tl__year">1959</div>
            <div class="tl__body">
              <h3 class="t-d3">Présidence de l'Assemblée nationale</h3>
              <p class="t-body">Il en occupe le perchoir pendant vingt et un ans.
                <em>Notice à compléter.</em></p>
            </div>
          </div>
          <div class="tl__item reveal">
            <div class="tl__year">1980</div>
            <div class="tl__body">
              <h3 class="t-d3">Conseil économique et social</h3>
              <p class="t-body"><em>Notice à compléter.</em></p>
            </div>
          </div>
          <div class="tl__item reveal">
            <div class="tl__year">1998</div>
            <div class="tl__body">
              <h3 class="t-d3">Disparition</h3>
              <p class="t-body"><em>Notice à compléter.</em></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CITATION ===================== -->
<section class="section section--dark">
  <div class="shell">
    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <p class="kicker reveal">Extrait</p>
        <blockquote class="quote reveal" style="margin:0;">
          Emplacement réservé à un extrait de l'ouvrage,
          à choisir par l'éditeur.
        </blockquote>
        <p class="quote__src reveal">Une destinée — chapitre à préciser</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== 04 · GALERIE ===================== -->
<section class="section" id="galerie">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">04</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Galerie</p>
        <h2 class="t-d1 reveal">Archives.</h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="/archives">Toutes les archives</a>
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
          <p class="t-lead reveal">
            <em>Les archives seront publiées ici.</em> Photographies, documents
            officiels et coupures&nbsp;: chaque pièce paraîtra avec sa légende et
            son crédit.
          </p>
        </div>
      </div>

    <?php else: ?>

      <ul class="gal">
        <?php foreach ($planche as $i => $img): ?>
          <li class="gal__i gal__i--<?= $trame[$i % count($trame)] ?> reveal">
            <a class="gal__lien" href="/archives">
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
        <p class="kicker reveal">Témoignages</p>
        <h2 class="t-d1 reveal">Ce qu'ils en disent.</h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="/temoignages#deposer">Déposer un témoignage</a>
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
          <p class="t-lead reveal">
            <em>Les premiers témoignages seront affichés ici.</em> Vous avez connu
            Philippe Grégoire Yacé, de près ou de loin&nbsp;? Votre souvenir a sa place.
          </p>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="btn-pgy btn-pgy--ghost" href="/temoignages#deposer">
              Déposer un témoignage <span class="btn-pgy__arrow" aria-hidden="true">→</span>
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
                <?= App\Core\View::e($t['auteur_fonction'] ?? 'Témoignage') ?>
                <span><?= App\Core\View::e($t['auteur_nom']) ?></span>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="row" style="margin-top: var(--sp-7);">
        <div class="col-12">
          <a class="link reveal" href="/temoignages">Lire tous les témoignages</a>
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
        <p class="kicker reveal">Actualités</p>
        <h2 class="t-d1 reveal">Autour de l'ouvrage.</h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="/actualites">Toutes les actualités</a>
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

          <p class="t-lead reveal">
            <em>Les actualités paraîtront ici.</em> Parutions, dédicaces et
            rendez-vous autour de l'ouvrage&nbsp;: rien n'est encore publié.
          </p>

        <?php else: ?>

          <div class="news">
            <?php foreach ($dernieres as $a): ?>
              <a class="news__i reveal" href="/actualites/<?= App\Core\View::e((string) $a['slug']) ?>">
                <time class="news__date" datetime="<?= App\Core\View::e(App\Core\DateFr::iso((string) $a['publie_le'])) ?>">
                  <?= App\Core\DateFr::longue((string) $a['publie_le']) ?>
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
        <p class="kicker reveal">Se procurer l'ouvrage</p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);">
          En librairie<br>et en ligne.
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="#">
            Commander en ligne
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <!-- POINTS DE VENTE — à renseigner -->
        <div class="pos row g-0 reveal">
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Abidjan</h3>
            <p class="t-small">Enseigne et adresse à renseigner</p>
          </div>
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Yamoussoukro</h3>
            <p class="t-small">Enseigne et adresse à renseigner</p>
          </div>
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Paris</h3>
            <p class="t-small">Enseigne et adresse à renseigner</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
