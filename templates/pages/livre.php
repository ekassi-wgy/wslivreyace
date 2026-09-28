<?php
/** Gabarit de page — le corps seul ; l'en-tête, la navigation et le pied
    viennent de templates/layout.php. */
$titre       = t_nu('livre.titre_page');
$description = t_nu('livre.description');
$ld = json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'Book',
    'name'       => 'Philippe Grégoire Yacé : une destinée (1920-1998)',
    'inLanguage' => App\Core\Langue::code(),   // voir accueil.php, lot G11
    'bookFormat' => 'https://schema.org/Hardcover',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

use App\Core\Langue;
use App\Core\View;
use App\Model\Actualite;
use App\Model\Citation;
use App\Model\Evenement;
use App\Model\Media;
use App\Model\Parametre;
use App\Model\PointDeVente;
use App\Core\DateLisible;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

/**
 * Les points de vente publiés (lot G12), lus ici comme sur l'accueil : les
 * deux pages portent la même section « Où se procurer l'ouvrage », et le même
 * gabarit partagé la dessine désormais. Voir `App\Model\PointDeVente`.
 */
$pointsDeVente = PointDeVente::listerPublies();

/**
 * La citation du bandeau « Extrait » (lot G14). Elle venait du lexique et
 * portait le même texte que celle de l'accueil, mot pour mot, sous un second
 * jeu de clés : deux copies d'un même bloc, dont une aurait fini par ne plus
 * être tenue à jour. Ce sont désormais deux lignes de la table `citation`,
 * distinctes et chacune à son emplacement.
 *
 * `null` fait disparaître la section entière.
 */
$citation = Citation::affichee('livre');

/**
 * La présentation et la fiche technique (lot G17). La couverture et le résumé
 * se saisissent à l'écran « Textes des pages », la fiche à l'écran
 * « Paramètres » — qu'aucune page ne lisait jusqu'ici : les deux colonnes
 * affichaient « À renseigner » en dur. Une valeur vide retire sa ligne, un
 * résumé vide sa rubrique ; sans couverture, le cadre d'attente reste.
 */
$presentation = Parametre::section('livre_presentation');
$couverture   = Parametre::image('livre_presentation_couverture');

/*
 * Sommaire, feuilletage et portrait de l'auteur (lot G17), écran « Textes
 * des pages » ; le nom et la biographie de l'auteur viennent de « Paramètres ».
 * Chacune de ces sections disparaît tant qu'elle n'a rien à montrer — elles
 * affichaient jusqu'ici leurs consignes en ligne.
 */
$sommaire   = Parametre::sommaire();
$extraits   = array_values(array_filter([
    Parametre::image('livre_contenu_extrait_1'),
    Parametre::image('livre_contenu_extrait_2'),
]));
$extraitPdf = Parametre::document('livre_contenu_extrait_pdf');
$portrait   = Parametre::image('livre_contenu_portrait');

/**
 * Numéro de section : une section absente ne laisse pas de trou dans la
 * suite, comme sur la biographie.
 */
$rang   = 0;
$numero = static function () use (&$rang): string {
    return sprintf('%02d', ++$rang);
};
$ficheGauche  = Parametre::fiche(['livre_auteur', 'livre_editeur', 'livre_parution']);
$ficheDroite  = Parametre::fiche(['livre_format', 'livre_pages', 'livre_isbn', 'livre_prix']);
$libellesFiche = [
    'livre_auteur'   => t('livre.fiche.auteur'),
    'livre_editeur'  => t('livre.fiche.editeur'),
    'livre_parution' => t('livre.fiche.parution'),
    'livre_format'   => t('livre.fiche.format'),
    'livre_pages'    => t('livre.fiche.pages'),
    'livre_isbn'     => t('livre.fiche.isbn'),
    'livre_prix'     => t('livre.fiche.prix'),
];

/**
 * Le bloc de préface, rendu à l'une ou l'autre place selon le réglage.
 *
 * Défini une fois et appelé deux fois : la mise en avant remonte le bloc en
 * tête de page, sans mise en avant il reste à sa place ordinaire. Écrire le
 * bloc deux fois aurait garanti qu'une des deux versions cesse d'être tenue à
 * jour. Voir `Parametre::AUTOUR_LIVRE`.
 */
$blocPreface = static function (array $preface, bool $enAvant, string $num = '—'): void {
    ?>
    <section class="section<?= $enAvant ? ' section--dark' : ' section--sunk' ?>" id="preface">
      <div class="shell">
        <div class="row">
          <div class="col-lg-2"><p class="section-num reveal"><?= View::e($num) ?></p></div>
          <div class="col-lg-8">
            <p class="kicker reveal"><?= t('livre.preface.kicker') ?></p>

            <?php if ($preface['extrait'] !== ''): ?>
              <blockquote class="quote reveal" style="margin: 0 0 var(--sp-6);">
                <?= View::e($preface['extrait']) ?>
              </blockquote>
            <?php endif; ?>

            <?php if ($preface['auteur'] !== ''): ?>
              <p class="quote__src reveal">
                <?= View::e($preface['auteur']) ?><?php
                  echo $preface['qualite'] === '' ? '' : '<br>' . View::e($preface['qualite']);
                ?>
              </p>
            <?php endif; ?>

            <?php if ($preface['texte'] !== ''): ?>
              <div class="reveal" style="margin-top: var(--sp-7);">
                <?= View::paragraphes($preface['texte'], 't-body') ?>
              </div>
            <?php elseif ($preface['auteur'] !== ''): ?>
              <p class="t-body reveal" style="margin-top: var(--sp-6);">
                <em><?= t('livre.preface.attente') ?></em>
              </p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
    <?php
};
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal"><?= $numero() ?></p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('livre.tete.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('livre.tete.titre') ?></h1>
        <?php if ($presentation['accroche'] !== ''): ?>
          <p class="t-lead page-head__lead reveal"><?= nl2br(View::e($presentation['accroche']), false) ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<?php /* La préface mise en avant passe avant tout le reste : c'est ce que le
         brief demande si la préface présidentielle se confirme. */ ?>
<?php if ($preface['avant']): ?>
<?php $blocPreface($preface, true); ?>
<?php endif; ?>


<!-- ===================== PRÉSENTATION ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row" style="row-gap: var(--sp-9);">

      <div class="col-lg-5">
        <span class="frame reveal">
          <?php if ($couverture !== null): ?>
            <?php $srcset = Media::srcset($couverture); ?>
            <img loading="lazy" decoding="async"
                 src="<?= View::e(Media::urlMoyen((string) $couverture['fichier'])) ?>"
                 <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 40vw"' ?>
                 <?php if ((int) ($couverture['largeur'] ?? 0) > 0 && (int) ($couverture['hauteur'] ?? 0) > 0): ?>
                 width="<?= (int) $couverture['largeur'] ?>" height="<?= (int) $couverture['hauteur'] ?>"
                 <?php endif; ?>
                 alt="<?= View::e(Media::alternative($couverture)) ?>">
          <?php else: ?>
            <img loading="lazy" decoding="async" src="assets/img/couverture.svg"
                 width="1200" height="1550" alt="<?= t('livre.couverture_alt') ?>">
          <?php endif; ?>
        </span>
      </div>

      <div class="col-lg-6 offset-lg-1">
        <?php if ($presentation['resume'] !== ''): ?>
          <p class="kicker reveal"><?= t('livre.resume.kicker') ?></p>
          <?= View::paragraphes($presentation['resume'], 't-body reveal') ?>
        <?php endif; ?>

        <?php if ($presentation['resume'] !== '' && $presentation['editeur'] !== ''): ?>
          <div class="rule reveal" style="margin-block: var(--sp-7);"></div>
        <?php endif; ?>

        <?php if ($presentation['editeur'] !== ''): ?>
          <p class="kicker reveal"><?= t('livre.editeur.kicker') ?></p>
          <?= View::paragraphes($presentation['editeur'], 't-body reveal') ?>
        <?php endif; ?>
      </div>

    </div>
  </div>
</section>

<!-- ===================== INFORMATIONS PRATIQUES ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal"><?= $numero() ?></p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.fiche.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('livre.fiche.titre') ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-5 offset-lg-2">
        <dl class="specs reveal">
          <?php foreach ($ficheGauche as $cle => $valeur): ?>
          <div><dt><?= $libellesFiche[$cle] ?></dt><dd><?= View::e($valeur) ?></dd></div>
          <?php endforeach; ?>
          <div><dt><?= t('livre.fiche.langue') ?></dt><dd><?= t('livre.fiche.langue_valeur') ?></dd></div>
        </dl>
      </div>
      <?php if ($ficheDroite !== []): ?>
      <div class="col-lg-5">
        <dl class="specs reveal">
          <?php foreach ($ficheDroite as $cle => $valeur): ?>
          <div><dt><?= $libellesFiche[$cle] ?></dt><dd><?= View::e($valeur) ?></dd></div>
          <?php endforeach; ?>
        </dl>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ===================== SOMMAIRE ===================== -->
<?php if ($sommaire !== []): ?>
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal"><?= $numero() ?></p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.sommaire.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('livre.sommaire.titre') ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <ol class="toc reveal">
          <?php foreach ($sommaire as $entree): ?>
          <li><span class="toc__t"><?= View::e($entree['titre']) ?></span><span class="toc__lead"></span><span class="toc__p"><?= $entree['page'] === '' ? '' : View::e(t_nu('livre.sommaire.page', ['page' => $entree['page']])) ?></span></li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== EXTRAIT ===================== -->
<?php if ($citation !== null): ?>
<section class="section section--dark" id="extrait">
  <div class="shell">
    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <p class="kicker reveal"><?= t('livre.extrait.kicker') ?></p>
        <blockquote class="quote reveal" style="margin:0;"><?= View::e((string) $citation['texte']) ?></blockquote>
        <?php if (trim((string) ($citation['source'] ?? '')) !== ''): ?>
          <p class="quote__src reveal"><?= View::e((string) $citation['source']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== FEUILLETAGE ===================== -->
<?php if ($extraits !== []): ?>
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal"><?= $numero() ?></p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.feuilletage.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('livre.feuilletage.titre') ?></h2>
      </div>
      <?php if ($extraitPdf !== null): ?>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="<?= View::e(Media::url((string) $extraitPdf['fichier'])) ?>" download><?= t('livre.feuilletage.pdf') ?></a>
      </div>
      <?php endif; ?>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <div class="spread reveal">
          <?php foreach ($extraits as $extrait): ?>
            <?php $srcset = Media::srcset($extrait); ?>
            <img loading="lazy" decoding="async"
                 src="<?= View::e(Media::urlMoyen((string) $extrait['fichier'])) ?>"
                 <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 767px) 100vw, 40vw"' ?>
                 <?php if ((int) ($extrait['largeur'] ?? 0) > 0 && (int) ($extrait['hauteur'] ?? 0) > 0): ?>
                 width="<?= (int) $extrait['largeur'] ?>" height="<?= (int) $extrait['hauteur'] ?>"
                 <?php endif; ?>
                 alt="<?= View::e(Media::alternative($extrait)) ?>">
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== L'AUTEUR ===================== -->
<?php $auteurNom = trim((string) ($reglages['auteur_nom'] ?? '')); ?>
<?php if ($auteurNom !== ''): ?>
<?php /* Sans nom, pas de section : la page de l'auteur est elle-même en 404
         tant qu'il n'est pas saisi. */ ?>
<section class="section section--sunk" id="auteur">
  <div class="shell">
    <div class="row align-items-center" style="row-gap: var(--sp-9);">
      <?php if ($portrait !== null): ?>
      <div class="col-lg-4 offset-lg-2 order-lg-2">
        <span class="frame reveal">
          <?php $srcset = Media::srcset($portrait); ?>
          <img loading="lazy" decoding="async"
               src="<?= View::e(Media::urlMoyen((string) $portrait['fichier'])) ?>"
               <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 33vw"' ?>
               <?php if ((int) ($portrait['largeur'] ?? 0) > 0 && (int) ($portrait['hauteur'] ?? 0) > 0): ?>
               width="<?= (int) $portrait['largeur'] ?>" height="<?= (int) $portrait['hauteur'] ?>"
               <?php endif; ?>
               alt="<?= View::e(Media::alternative($portrait)) ?>">
        </span>
      </div>
      <?php endif; ?>
      <div class="col-lg-5 order-lg-1">
        <p class="section-num reveal"><?= $numero() ?></p>
        <p class="kicker reveal"><?= t('livre.auteur.kicker') ?></p>
        <h2 class="t-d2 reveal" style="margin-bottom: var(--sp-6);"><?= View::e($auteurNom) ?></h2>

        <?php $auteurBio = trim((string) ($reglages['auteur_bio'] ?? '')); ?>
        <?php if ($auteurBio !== ''): ?>
          <?php /* L'aperçu seul : la page de l'auteur porte le texte entier. */ ?>
          <p class="t-body reveal"><?= View::e(mb_strimwidth(preg_replace('/\s+/', ' ', $auteurBio) ?? '', 0, 320, '…')) ?></p>
        <?php endif; ?>

        <p class="reveal" style="margin-top: var(--sp-6);">
          <a class="link" href="<?= $lien('/auteur') ?>"><?= t('livre.auteur.lien') ?></a>
        </p>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php /* Préface à sa place ordinaire, quand elle n'est pas mise en avant. */ ?>
<?php if ($preface['presente'] && !$preface['avant']): ?>
<?php $blocPreface($preface, false, $numero()); ?>
<?php endif; ?>

<?php /* ============ REVUE DE PRESSE ET ÉVÉNEMENTS DE LANCEMENT ============
         Le brief §2 les rattache à la page du livre. Les deux existaient
         chacun à son adresse, sans que rien n'y mène depuis ici. Chaque bloc
         disparaît quand il est vide : une rubrique sans contenu ne dit rien de
         bon sur un site qu'on découvre. */ ?>
<?php if ($presse !== [] || $aVenir !== []): ?>
<section class="section">
  <div class="shell">
    <div class="row" style="row-gap: var(--sp-8);">

      <?php if ($presse !== []): ?>
        <div class="col-lg-5 offset-lg-2">
          <p class="kicker reveal"><?= t('livre.presse.kicker') ?></p>
          <h2 class="t-d3 reveal" style="margin-bottom: var(--sp-5);"><?= t('livre.presse.titre') ?></h2>
          <ul class="liste-nue">
            <?php foreach ($presse as $a): ?>
              <li class="reveal">
                <a href="<?= $lien('/actualites/' . $a['slug']) ?>"><?= View::e((string) $a['titre']) ?></a>
                <?php if (!empty($a['source'])): ?>
                  <span class="liste-nue__meta"><?= View::e((string) $a['source']) ?></span>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="link" href="<?= $lien('/revue-de-presse') ?>"><?= t('livre.presse.lien') ?></a>
          </p>
        </div>
      <?php endif; ?>

      <?php if ($aVenir !== []): ?>
        <div class="col-lg-4<?= $presse === [] ? ' offset-lg-2' : ' offset-lg-1' ?>">
          <p class="kicker reveal"><?= t('livre.agenda.kicker') ?></p>
          <h2 class="t-d3 reveal" style="margin-bottom: var(--sp-5);"><?= t('livre.agenda.titre') ?></h2>
          <ul class="liste-nue">
            <?php foreach ($aVenir as $e): ?>
              <li class="reveal">
                <a href="<?= $lien('/evenements/' . $e['slug']) ?>"><?= View::e((string) $e['titre']) ?></a>
                <span class="liste-nue__meta">
                  <?= View::e(DateLisible::longue((string) $e["debut_le"])) ?><?php
                    $ou = trim((string) ($e['ville'] ?? ''));
                    echo $ou === '' ? '' : ' · ' . View::e($ou);
                  ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="link" href="<?= $lien('/evenements') ?>"><?= t('livre.agenda.lien') ?></a>
          </p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== OÙ ACHETER ===================== -->
<section class="section" id="acheter">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal"><?= $numero() ?></p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.acheter.kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);">
          <?= t_brut('livre.acheter.titre') ?>
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="<?= $lien('/commander') ?>">
            <?= t('livre.acheter.cta') ?>
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <!-- POINTS DE VENTE — table `point_de_vente` (CDC §4.2, lot G12) -->
        <?php require dirname(__DIR__) . '/partials/points-de-vente.php'; ?>
      </div>
    </div>
  </div>
</section>
