<?php
/** Gabarit de page — le corps seul ; l'en-tête, la navigation et le pied
    viennent de templates/layout.php. */
$titre       = t('livre.titre_page');
$description = t('livre.description');
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
use App\Model\Evenement;
use App\Core\DateFr;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

/**
 * Le bloc de préface, rendu à l'une ou l'autre place selon le réglage.
 *
 * Défini une fois et appelé deux fois : la mise en avant remonte le bloc en
 * tête de page, sans mise en avant il reste à sa place ordinaire. Écrire le
 * bloc deux fois aurait garanti qu'une des deux versions cesse d'être tenue à
 * jour. Voir `Parametre::AUTOUR_LIVRE`.
 */
$blocPreface = static function (array $preface, bool $enAvant): void {
    ?>
    <section class="section<?= $enAvant ? ' section--dark' : ' section--sunk' ?>" id="preface">
      <div class="shell">
        <div class="row">
          <div class="col-lg-2"><p class="section-num reveal"><?= $enAvant ? '—' : '02' ?></p></div>
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
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('livre.tete.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('livre.tete.titre') ?></h1>
        <p class="t-lead page-head__lead reveal">
          <em><?= t('livre.tete.lead') ?></em>
        </p>
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
          <img loading="lazy" decoding="async" src="assets/img/couverture.svg"
               width="1200" height="1550" alt="<?= t('livre.couverture_alt') ?>">
        </span>
      </div>

      <div class="col-lg-6 offset-lg-1">
        <p class="kicker reveal"><?= t('livre.resume.kicker') ?></p>
        <!-- RÉSUMÉ LONG — à rédiger (CDC §4.2) -->
        <p class="t-body reveal"><?= t_brut('livre.resume.p1') ?></p>
        <p class="t-body reveal"><?= t_brut('livre.resume.p2') ?></p>

        <div class="rule reveal" style="margin-block: var(--sp-7);"></div>

        <p class="kicker reveal"><?= t('livre.editeur.kicker') ?></p>
        <p class="t-body reveal"><?= t_brut('livre.editeur.texte') ?></p>
      </div>

    </div>
  </div>
</section>

<!-- ===================== INFORMATIONS PRATIQUES ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.fiche.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('livre.fiche.titre') ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-5 offset-lg-2">
        <!-- FICHE TECHNIQUE — valeurs provisoires (CDC §4.2) -->
        <dl class="specs reveal">
          <div><dt><?= t('livre.fiche.auteur') ?></dt><dd><?= t('livre.fiche.a_renseigner') ?></dd></div>
          <div><dt><?= t('livre.fiche.editeur') ?></dt><dd><?= t('livre.fiche.a_renseigner') ?></dd></div>
          <div><dt><?= t('livre.fiche.parution') ?></dt><dd><?= t('livre.fiche.a_renseigner') ?></dd></div>
          <div><dt><?= t('livre.fiche.langue') ?></dt><dd><?= t('livre.fiche.langue_valeur') ?></dd></div>
        </dl>
      </div>
      <div class="col-lg-5">
        <dl class="specs reveal">
          <div><dt><?= t('livre.fiche.format') ?></dt><dd><?= t('livre.fiche.format_valeur') ?></dd></div>
          <div><dt><?= t('livre.fiche.pages') ?></dt><dd><?= t('livre.fiche.a_renseigner') ?></dd></div>
          <div><dt><?= t('livre.fiche.isbn') ?></dt><dd><?= t('livre.fiche.a_renseigner') ?></dd></div>
          <div><dt><?= t('livre.fiche.prix') ?></dt><dd><?= t('livre.fiche.a_renseigner') ?></dd></div>
        </dl>
      </div>
    </div>
  </div>
</section>

<!-- ===================== SOMMAIRE ===================== -->
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">03</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.sommaire.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('livre.sommaire.titre') ?></h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <!-- SOMMAIRE — intitulés et pagination à reprendre de l'ouvrage -->
        <ol class="toc reveal">
          <li><span class="toc__t"><?= t('livre.sommaire.partie') ?></span><span class="toc__lead"></span><span class="toc__p"><?= t('livre.sommaire.page') ?></span></li>
          <li><span class="toc__t"><?= t('livre.sommaire.partie') ?></span><span class="toc__lead"></span><span class="toc__p"><?= t('livre.sommaire.page') ?></span></li>
          <li><span class="toc__t"><?= t('livre.sommaire.partie') ?></span><span class="toc__lead"></span><span class="toc__p"><?= t('livre.sommaire.page') ?></span></li>
          <li><span class="toc__t"><?= t('livre.sommaire.partie') ?></span><span class="toc__lead"></span><span class="toc__p"><?= t('livre.sommaire.page') ?></span></li>
          <li><span class="toc__t"><?= t('livre.sommaire.partie') ?></span><span class="toc__lead"></span><span class="toc__p"><?= t('livre.sommaire.page') ?></span></li>
          <li><span class="toc__t"><?= t('livre.sommaire.partie') ?></span><span class="toc__lead"></span><span class="toc__p"><?= t('livre.sommaire.page') ?></span></li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- ===================== EXTRAIT ===================== -->
<section class="section section--dark" id="extrait">
  <div class="shell">
    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <p class="kicker reveal"><?= t('livre.extrait.kicker') ?></p>
        <blockquote class="quote reveal" style="margin:0;"><?= t('livre.extrait.texte') ?></blockquote>
        <p class="quote__src reveal"><?= t('livre.extrait.source') ?></p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== FEUILLETAGE ===================== -->
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">04</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.feuilletage.kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('livre.feuilletage.titre') ?></h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="#"><?= t('livre.feuilletage.pdf') ?></a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <div class="spread reveal">
          <img loading="lazy" decoding="async" src="assets/img/extrait-1.svg"
               width="1500" height="1000" alt="<?= t('livre.feuilletage.alt') ?>">
          <img loading="lazy" decoding="async" src="assets/img/extrait-2.svg"
               width="1500" height="1000" alt="<?= t('livre.feuilletage.alt') ?>">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== L'AUTEUR ===================== -->
<section class="section section--sunk" id="auteur">
  <div class="shell">
    <div class="row align-items-center" style="row-gap: var(--sp-9);">
      <div class="col-lg-4 offset-lg-2 order-lg-2">
        <span class="frame reveal">
          <img loading="lazy" decoding="async" src="assets/img/auteur.svg"
               width="1000" height="1250" alt="<?= t('livre.auteur.alt') ?>">
        </span>
      </div>
      <div class="col-lg-5 order-lg-1">
        <p class="section-num reveal">05</p>
        <p class="kicker reveal"><?= t('livre.auteur.kicker') ?></p>
        <?php $auteurNom = trim((string) ($reglages['auteur_nom'] ?? '')); ?>
        <h2 class="t-d2 reveal" style="margin-bottom: var(--sp-6);">
          <?= $auteurNom === '' ? t('livre.auteur.nom_vide') : View::e($auteurNom) ?>
        </h2>

        <?php $auteurBio = trim((string) ($reglages['auteur_bio'] ?? '')); ?>
        <?php if ($auteurBio !== ''): ?>
          <?php /* L'aperçu seul : la page de l'auteur porte le texte entier. */ ?>
          <p class="t-body reveal"><?= View::e(mb_strimwidth(preg_replace('/\s+/', ' ', $auteurBio) ?? '', 0, 320, '…')) ?></p>
        <?php else: ?>
          <p class="t-body reveal"><?= t_brut('livre.auteur.bio_vide') ?></p>
        <?php endif; ?>

        <?php if ($auteurNom !== ''): ?>
          <p class="reveal" style="margin-top: var(--sp-6);">
            <a class="link" href="<?= $lien('/auteur') ?>"><?= t('livre.auteur.lien') ?></a>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php /* Préface à sa place ordinaire, quand elle n'est pas mise en avant. */ ?>
<?php if ($preface['presente'] && !$preface['avant']): ?>
<?php $blocPreface($preface, false); ?>
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
                  <?= View::e(DateFr::longue((string) $e["debut_le"])) ?><?php
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
      <div class="col-lg-2"><p class="section-num reveal">06</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('livre.acheter.kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);">
          <?= t_brut('livre.acheter.titre') ?>
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="#">
            <?= t('livre.acheter.cta') ?>
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <!-- POINTS DE VENTE — à renseigner (CDC §4.2) -->
        <div class="pos row g-0 reveal">
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Abidjan</h3>
            <p class="t-small"><?= t('livre.acheter.adresse') ?></p>
          </div>
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Yamoussoukro</h3>
            <p class="t-small"><?= t('livre.acheter.adresse') ?></p>
          </div>
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Paris</h3>
            <p class="t-small"><?= t('livre.acheter.adresse') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
