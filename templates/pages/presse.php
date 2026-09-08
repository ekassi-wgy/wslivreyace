<?php
/**
 * Revue de presse (CDC §4.7).
 *
 * Les mêmes entrées que la catégorie « presse » de la liste d'actualités, mais
 * lues autrement : par millésime, et l'organe en tête plutôt que la catégorie.
 * C'est la seule page du site où l'on cherche « ce qui est paru en 2026 »
 * plutôt que « la dernière nouvelle ».
 *
 * Chaque ligne mène à la fiche interne et non directement au site de l'organe.
 * Un lien dans un lien n'existe pas en HTML, il fallait choisir : la fiche
 * porte le lien sortant, son contexte et sa date de relevé, et elle reste
 * lisible quand l'article d'origine a disparu — ce qui arrive vite sur les
 * sites de presse.
 */

use App\Core\Langue;
use App\Core\DateLisible;
use App\Core\View;

$titre       = t_nu('presse.titre_page');
$description = t_nu('presse.description');

$total = array_sum(array_map('count', $parAnnee));
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('presse.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('presse.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('presse.lead') ?></p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== RÉFÉRENCES ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">

    <?php if ($parAnnee === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <p class="t-lead reveal"><?= t_brut('presse.vide') ?></p>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="link" href="<?= Langue::chemin('/actualites') ?>"><?= t('presse.voir_actus') ?></a>
          </p>
        </div>
      </div>

    <?php else: ?>

      <div class="row">
        <div class="col-lg-10 offset-lg-2">
          <p class="kicker kicker--bare reveal">
            <?= $total > 1
                  ? t('presse.compte', ['nombre' => (int) $total])
                  : t('presse.compte_un', ['nombre' => (int) $total]) ?>
          </p>

<?php foreach ($parAnnee as $annee => $entrees): ?>
          <div class="presse__an">
            <h2 class="presse__annee reveal"><?= View::e((string) $annee) ?></h2>

            <div class="news">
<?php foreach ($entrees as $e): ?>
              <a class="news__i reveal" href="<?= Langue::chemin('/actualites/' . (string) $e['slug']) ?>">
                <span class="news__organe"><?= View::e((string) ($e['source'] ?? '')) ?></span>
                <span class="news__body">
                  <span class="news__t"><?= View::e((string) $e['titre']) ?></span>
                  <?php if (!empty($e['chapo'])): ?>
                    <span class="news__chapo"><?= View::e((string) $e['chapo']) ?></span>
                  <?php endif; ?>
                </span>
                <time class="news__cat news__cat--date" datetime="<?= View::e(DateLisible::iso((string) $e['publie_le'])) ?>">
                  <?= DateLisible::longue((string) $e['publie_le']) ?>
                </time>
              </a>
<?php endforeach; ?>
            </div>
          </div>
<?php endforeach; ?>

        </div>
      </div>

    <?php endif; ?>

  </div>
</section>
