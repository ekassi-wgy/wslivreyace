<?php
/** Gabarit de page — le corps seul ; l'en-tête, la navigation et le pied
    viennent de templates/layout.php. */
$titre       = 'Biographie — Philippe Grégoire Yacé (1920-1998)';
$description = 'Le parcours de Philippe Grégoire Yacé : formation, engagement politique, carrière institutionnelle et chronologie 1920-1998.';
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
          Président de l'Assemblée nationale de Côte d'Ivoire de 1959 à 1980,
          secrétaire général du PDCI-RDA, puis président du Conseil économique et social.
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
               alt="Philippe Grégoire Yacé jeune homme, en veste claire et lunettes rondes — photographie d'archive">
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
        <p class="kicker reveal">Contexte</p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-7);">
          Une trajectoire<br>et un pays qui naît.
        </h2>
        <div class="row"><div class="col-md-10 col-lg-9">
          <!-- CONTEXTE HISTORIQUE — à rédiger (CDC §4.4) -->
          <p class="t-body reveal">
            <em>Texte à rédiger.</em> Situer le personnage dans la Côte d'Ivoire
            pré- et post-indépendance, son rôle aux côtés de Félix Houphouët-Boigny,
            et la place de l'Assemblée nationale dans la construction de l'État.
          </p>
        </div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== BIOGRAPHIE STRUCTURÉE ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Biographie</p>
        <h2 class="t-d1 reveal">Le parcours.</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3 col-xl-2 offset-xl-1">
        <nav class="subnav reveal" aria-label="Chapitres de la biographie">
          <ul>
            <li><a href="#enfance">Enfance et formation</a></li>
            <li><a href="#engagement">L'engagement politique</a></li>
            <li><a href="#carriere">La carrière institutionnelle</a></li>
            <li><a href="#prive">L'homme privé</a></li>
            <li><a href="#dernieres">Les dernières années</a></li>
          </ul>
        </nav>
      </div>
      <div class="col-lg-8 offset-lg-1">
        <div class="chap reveal" id="enfance">
          <span class="chap__num">01</span>
          <h3 class="t-d3">Enfance et formation</h3>
          <p class="t-body"><em>Texte à rédiger : Origines familiales, années de jeunesse, parcours scolaire et formation.</em></p>
          <p class="t-body"><em>Les faits avancés ici devront être sourcés (CDC §6).</em></p>
        </div>
        <div class="chap reveal" id="engagement">
          <span class="chap__num">02</span>
          <h3 class="t-d3">L'engagement politique</h3>
          <p class="t-body"><em>Texte à rédiger : Entrée en politique, rencontre avec Félix Houphouët-Boigny, rôle au sein du PDCI-RDA.</em></p>
          <p class="t-body"><em>Les faits avancés ici devront être sourcés (CDC §6).</em></p>
        </div>
        <div class="chap reveal" id="carriere">
          <span class="chap__num">03</span>
          <h3 class="t-d3">La carrière institutionnelle</h3>
          <p class="t-body"><em>Texte à rédiger : Vingt et un ans à la présidence de l'Assemblée nationale, puis le Conseil économique et social.</em></p>
          <p class="t-body"><em>Les faits avancés ici devront être sourcés (CDC §6).</em></p>
        </div>
        <div class="chap reveal" id="prive">
          <span class="chap__num">04</span>
          <h3 class="t-d3">L'homme privé</h3>
          <p class="t-body"><em>Texte à rédiger : Vie familiale, convictions, rapports aux siens — dans le respect dû à la sphère privée.</em></p>
          <p class="t-body"><em>Les faits avancés ici devront être sourcés (CDC §6).</em></p>
        </div>
        <div class="chap reveal" id="dernieres">
          <span class="chap__num">05</span>
          <h3 class="t-d3">Les dernières années</h3>
          <p class="t-body"><em>Texte à rédiger : Retrait de la vie publique, derniers engagements, disparition en 1998.</em></p>
          <p class="t-body"><em>Les faits avancés ici devront être sourcés (CDC §6).</em></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== FRISE CHRONOLOGIQUE ===================== -->
<?php
/**
 * La frise vient de la base — table `repere`, écran « Repères » du
 * back-office (lot G0, README §9). Elle portait ses dates en dur jusqu'ici :
 * ce qu'un éditeur saisissait n'apparaissait nulle part.
 *
 * Les filtres ne montrent que les périodes qui portent quelque chose : un
 * onglet qui donne sur une frise vide est un lien mort.
 */
$reperes  = App\Model\Repere::listerPubliees();
$periodes = App\Model\Repere::periodesPubliees();
?>
<?php if ($reperes !== []): ?>
<section class="section" id="chronologie">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-7);">
      <div class="col-lg-2"><p class="section-num reveal">03</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Chronologie</p>
        <h2 class="t-d1 reveal">1920 — 1998.</h2>
      </div>
    </div>

<?php /* Un seul groupe de périodes ne se filtre pas : les chips n'offriraient
         qu'un choix, « Tout », et un autre qui donne le même résultat. */ ?>
<?php if (count($periodes) > 1): ?>
    <div class="row" style="margin-bottom: var(--sp-6);">
      <div class="col-lg-10 offset-lg-2">
        <div class="chips reveal" role="group" aria-label="Filtrer par période">
          <button class="chip is-active" type="button" data-period="tout" aria-pressed="true">Tout</button>
<?php foreach ($periodes as $cle => $n): ?>
          <button class="chip" type="button" data-period="<?= App\Core\View::e($cle) ?>" aria-pressed="false"><?= App\Core\View::e(App\Model\Repere::periode($cle)) ?></button>
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
?>
          <div class="chrono__item reveal" data-period="<?= App\Core\View::e((string) $r['periode']) ?>">
<?php if ($depliable): ?>
            <button class="chrono__head" type="button" data-bs-toggle="collapse"
                    data-bs-target="#<?= $cible ?>" aria-expanded="false" aria-controls="<?= $cible ?>">
              <span class="chrono__year"><?= App\Core\View::e((string) $r['annee']) ?></span>
              <span class="chrono__t"><?= App\Core\View::e((string) $r['titre']) ?></span>
              <span class="chrono__sign" aria-hidden="true"></span>
            </button>
            <div class="collapse" id="<?= $cible ?>">
              <div class="chrono__body">
                <div class="row"><div class="col-lg-8 offset-lg-3">
<?= App\Core\View::paragraphes($notice, 't-body') ?>
<?php if ($source !== ''): ?>
                  <p class="chrono__src"><?= App\Core\View::e($source) ?></p>
<?php endif; ?>
                </div></div>
              </div>
            </div>
<?php else: ?>
<?php /* Sans notice ni source, rien à déplier : un bouton qui n'ouvre rien
         ment au clavier comme à la souris. La date reste, en clair. */ ?>
            <div class="chrono__head chrono__head--plat">
              <span class="chrono__year"><?= App\Core\View::e((string) $r['annee']) ?></span>
              <span class="chrono__t"><?= App\Core\View::e((string) $r['titre']) ?></span>
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
        <p class="kicker reveal">Citations</p>
        <!-- CITATIONS — aucun propos ne doit être attribué sans source vérifiée -->
        <blockquote class="quote reveal" style="margin:0;">
          Emplacement réservé à une citation sourcée
          de Philippe Grégoire Yacé.
        </blockquote>
        <p class="quote__src reveal">Source et date à préciser</p>
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
        <p class="kicker reveal">Portraits</p>
        <h2 class="t-d1 reveal">Images d'époque.</h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="/archives?categorie=portrait">Toute la photothèque</a>
      </div>
    </div>

    <?php
    /* Les portraits de la médiathèque, catégorie « portrait » : c'est la même
       matière que la galerie d'archives, vue par une entrée. La planche
       complète et sa visionneuse vivent sur /archives. */
    $portraits = App\Model\Media::listerPubliees('portrait', 4);
    $trame     = ['large', 'haut', 'carre', 'pano'];
    ?>

    <?php if ($portraits === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <p class="t-lead reveal">
            <em>Les portraits d'époque paraîtront ici</em>, à mesure que les
            archives sont numérisées et leurs droits vérifiés.
          </p>
        </div>
      </div>

    <?php else: ?>

      <ul class="gal">
        <?php foreach ($portraits as $i => $img): ?>
          <li class="gal__i gal__i--<?= $trame[$i % count($trame)] ?> reveal">
            <a class="gal__lien" href="/archives?categorie=portrait">
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

<!-- ===================== RENVOI VERS L'OUVRAGE ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row align-items-center" style="row-gap: var(--sp-7);">
      <div class="col-lg-7 offset-lg-2">
        <p class="kicker reveal">L'ouvrage</p>
        <h2 class="t-d2 reveal" style="margin-bottom: var(--sp-6);">
          Le récit complet dans <em>Une destinée</em>.
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="/le-livre">
            Découvrir le livre
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
