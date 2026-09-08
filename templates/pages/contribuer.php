<?php
/**
 * « Contribuez aux archives » (brief §5, lot G8).
 *
 * Le texte du brief est repris presque mot pour mot : c'est lui qui dit ce
 * qu'on demande et pourquoi, et il le dit mieux qu'une reformulation.
 */

use App\Core\Csrf;
use App\Core\Langue;
use App\Core\Televersement;
use App\Core\View;

$titre       = t('contribuer.titre_page');
$description = t('contribuer.description');

$lien = static fn(string $chemin): string => Langue::chemin($chemin);
/**
 * Les trois aides du formulaire des témoignages, reprises telles quelles : même
 * vocabulaire de classes, mêmes attributs `aria`. Un second jeu de styles pour
 * un troisième formulaire aurait divergé au premier ajustement.
 */
$val    = static fn(string $c): string => View::e((string) ($valeurs[$c] ?? ''));
$err    = static fn(string $c): string => isset($erreurs[$c])
    ? '<p class="champ__erreur" id="err-' . $c . '">' . View::e($erreurs[$c]) . '</p>' : '';
$aria   = static fn(string $c): string => isset($erreurs[$c])
    ? ' aria-describedby="err-' . $c . '"' : '';
$classe = static fn(string $c): string => 'form-control champ__saisie'
    . (isset($erreurs[$c]) ? ' est-fautif' : '');
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <?php $fil = [[t('archives.fil'), '/archives'], [t('contribuer.fil'), null]];
              require dirname(__DIR__) . '/partials/fil.php'; ?>
        <p class="kicker reveal"><?= t('contribuer.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t_brut('contribuer.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('contribuer.lead') ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row" style="row-gap: var(--sp-8);">

      <div class="col-lg-6 offset-lg-2">

        <?php if (isset($erreurs['_global'])): ?>
          <p class="avis avis--refus" role="alert"><?= View::e($erreurs['_global']) ?></p>
        <?php endif; ?>

        <form method="post" action="<?= $lien('/contribuer') ?>" enctype="multipart/form-data"
              class="formulaire reveal" novalidate>
          <?= Csrf::champ() ?>

          <?php /* Piège à robots : masqué à l'écran et retiré aux lecteurs
                   d'écran, mais présent dans le HTML. Un visiteur ne le voit
                   pas, un robot le remplit. */ ?>
          <div class="leurre" aria-hidden="true">
            <label for="<?= View::e($leurre) ?>"><?= t('contribuer.leurre') ?></label>
            <input type="text" id="<?= View::e($leurre) ?>" name="<?= View::e($leurre) ?>" tabindex="-1" autocomplete="off">
          </div>

          <fieldset>
            <legend><?= t('contribuer.vous') ?></legend>

            <div class="champ-duo">
              <div class="champ">
                <label class="champ__titre" for="nom"><?= t('contribuer.nom') ?> <span class="champ__requis" aria-hidden="true">*</span></label>
                <input type="text" class="<?= $classe('nom') ?>" id="nom" name="nom" value="<?= $val('nom') ?>" required maxlength="120"<?= $aria('nom') ?>>
                <?= $err('nom') ?>
              </div>
              <div class="champ">
                <label class="champ__titre" for="prenom"><?= t('contribuer.prenom') ?></label>
                <input type="text" class="<?= $classe('prenom') ?>" id="prenom" name="prenom" value="<?= $val('prenom') ?>" maxlength="120"<?= $aria('prenom') ?>>
                <?= $err('prenom') ?>
              </div>
            </div>

            <div class="champ-duo">
              <div class="champ">
                <label class="champ__titre" for="email"><?= t('contribuer.email') ?> <span class="champ__requis" aria-hidden="true">*</span></label>
                <input type="email" class="<?= $classe('email') ?>" id="email" name="email" value="<?= $val('email') ?>" required maxlength="180"<?= $aria('email') ?>>
                <span class="champ__aide"><?= t('contribuer.email_aide') ?></span>
                <?= $err('email') ?>
              </div>
              <div class="champ">
                <label class="champ__titre" for="telephone"><?= t('contribuer.telephone') ?></label>
                <input type="tel" class="<?= $classe('telephone') ?>" id="telephone" name="telephone" value="<?= $val('telephone') ?>" maxlength="40"<?= $aria('telephone') ?>>
                <?= $err('telephone') ?>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend><?= t('contribuer.piece') ?></legend>

            <div class="champ">
              <label class="champ__titre" for="description"><?= t('contribuer.description_champ') ?> <span class="champ__requis" aria-hidden="true">*</span></label>
              <textarea class="<?= $classe('description') ?>" id="description" name="description" rows="6" required maxlength="3000"<?= $aria('description') ?>><?= $val('description') ?></textarea>
              <span class="champ__aide"><?= t('contribuer.description_aide') ?></span>
              <?= $err('description') ?>
            </div>

            <div class="champ-duo">
              <div class="champ">
                <label class="champ__titre" for="date_approx"><?= t('contribuer.date') ?></label>
                <input type="text" class="<?= $classe('date_approx') ?>" id="date_approx" name="date_approx" value="<?= $val('date_approx') ?>"
                       maxlength="60" placeholder="<?= t('contribuer.date_ph') ?>"<?= $aria('date_approx') ?>>
                <?= $err('date_approx') ?>
              </div>
              <div class="champ">
                <label class="champ__titre" for="source"><?= t('contribuer.origine') ?></label>
                <input type="text" class="<?= $classe('source') ?>" id="source" name="source" value="<?= $val('source') ?>"
                       maxlength="300" placeholder="<?= t('contribuer.origine_ph') ?>"<?= $aria('source') ?>>
                <?= $err('source') ?>
              </div>
            </div>

            <div class="champ">
              <label class="champ__titre" for="fichiers"><?= t('contribuer.fichiers') ?></label>
              <input type="file" id="fichiers" name="fichiers[]" multiple
                     accept="<?= View::e(Televersement::ACCEPTE) ?>"<?= $aria('fichiers') ?>>
              <span class="champ__aide">
                <?= t('contribuer.fichiers_aide', ['nombre' => (int) $lotMax]) ?>
                <br><?= t('contribuer.fichiers_aide2') ?>
              </span>
              <?= $err('fichiers') ?>
            </div>
          </fieldset>

          <fieldset>
            <legend><?= t('contribuer.droits') ?></legend>
            <div class="champ champ--case">
              <label class="champ__titre" for="droits">
                <input type="checkbox" id="droits" name="droits" value="1"
                       <?= ($valeurs['droits'] ?? '') === '1' ? 'checked' : '' ?> required<?= $aria('droits') ?>>
                <span>
                  <?= t('contribuer.droits_texte') ?> <span class="champ__requis" aria-hidden="true">*</span>
                </span>
              </label>
              <?= $err('droits') ?>
            </div>
          </fieldset>

          <p class="champ__envoi">
            <button class="btn-pgy" type="submit">
              <?= t('contribuer.envoyer') ?>
              <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
            </button>
          </p>
        </form>
      </div>

      <aside class="col-lg-3 offset-lg-1">
        <div class="contrib-note reveal">
          <p class="kicker kicker--bare"><?= t('contribuer.note_titre') ?></p>
          <ol class="contrib-etapes">
            <li><?= t('contribuer.etape_1') ?></li>
            <li><?= t('contribuer.etape_2') ?></li>
            <li><?= t('contribuer.etape_3') ?></li>
          </ol>
          <p class="contrib-note__fin"><?= t_brut('contribuer.note_fin') ?></p>
        </div>
      </aside>

    </div>
  </div>
</section>
