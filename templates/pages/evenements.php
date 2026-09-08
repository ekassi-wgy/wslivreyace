<?php
/**
 * Agenda des événements (CDC §4.10).
 *
 * Une page, deux temps : ce qui vient, puis ce qui a eu lieu. L'ordre s'inverse
 * entre les deux — le prochain rendez-vous d'abord, le dernier souvenir
 * d'abord — parce que ce sont deux questions différentes posées à la même
 * liste.
 *
 * **Le cartouche de date porte le quantième en grand.** C'est l'idiome de
 * l'agenda, et il permet de balayer la page sans lire les titres.
 */

use App\Core\Langue;
use App\Core\DateFr;
use App\Core\View;

$titre       = t('evenements.titre_page');
$description = t('evenements.description');

/**
 * Rendu d'une entrée d'agenda. Écrit une fois : les deux listes n'affichent
 * pas la même chose au même endroit, mais la ligne est la même.
 */
$entree = static function (array $e, bool $passe): void {
    $annule = ($e['statut'] ?? '') === 'annule';
    $debut  = (string) $e['debut_le'];
    $lieu   = trim((string) ($e['lieu'] ?? ''));
    $ville  = trim((string) ($e['ville'] ?? ''));
    ?>
    <li class="agenda__i reveal<?= $annule ? ' est-annule' : '' ?>">
      <a class="agenda__lien" href="<?= Langue::chemin('/evenements/' . (string) $e['slug']) ?>">

        <time class="agenda__quand" datetime="<?= View::e(DateFr::isoHeure($debut)) ?>">
          <span class="agenda__jour"><?= (int) date('j', strtotime($debut)) ?></span>
          <span class="agenda__mois"><?= View::e(DateFr::moisCourt($debut)) ?></span>
          <span class="agenda__an"><?= View::e(DateFr::annee($debut)) ?></span>
        </time>

        <span class="agenda__corps">
          <span class="agenda__t"><?= View::e((string) $e['titre']) ?></span>
          <?php if ($lieu !== '' || $ville !== ''): ?>
            <span class="agenda__lieu">
              <?= View::e(implode(', ', array_filter([$lieu, $ville]))) ?>
            </span>
          <?php endif; ?>
          <span class="agenda__horaire"><?= DateFr::intervalle($debut, $e['fin_le'] ?? null) ?></span>
        </span>

        <?php if ($annule): ?>
          <span class="agenda__etat agenda__etat--annule"><?= t('evenements.annule') ?></span>
        <?php elseif (!$passe): ?>
          <span class="agenda__etat"><?= t('evenements.a_venir') ?></span>
        <?php endif; ?>

      </a>
    </li>
    <?php
};
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('evenements.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('evenements.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('evenements.lead') ?></p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== À VENIR ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-10 offset-lg-2">

        <p class="kicker kicker--bare reveal" style="margin-bottom: var(--sp-6);">
          <?php if ($aVenir === []): ?><?= t('evenements.a_venir') ?><?php else: ?><?= count($aVenir) > 1
                  ? t('evenements.compte', ['nombre' => count($aVenir)])
                  : t('evenements.compte_un', ['nombre' => count($aVenir)]) ?><?php endif; ?>
        </p>

        <?php if ($aVenir === []): ?>

          <p class="t-lead reveal"><?= t_brut('evenements.vide') ?></p>

        <?php else: ?>

          <ul class="agenda">
            <?php foreach ($aVenir as $e) { $entree($e, false); } ?>
          </ul>

        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<?php if ($passes !== []): ?>
<!-- ===================== PASSÉS ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('evenements.passes_kicker') ?></p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-8);"><?= t('evenements.passes_titre') ?></h2>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <ul class="agenda agenda--passe">
          <?php foreach ($passes as $e) { $entree($e, true); } ?>
        </ul>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
