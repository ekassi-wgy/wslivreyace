<?php
/**
 * Témoignages — page publique et formulaire de dépôt (CDC §4.8).
 *
 * Deux temps : ce qui a été validé, puis ce que le visiteur peut déposer. Dans
 * cet ordre, et pas l'inverse : on lit ce que d'autres ont écrit avant d'écrire
 * à son tour, et la page doit d'abord montrer qu'elle est vivante.
 */

use App\Core\Csrf;
use App\Core\Langue;
use App\Core\View;

$titre       = t('temoignages.titre_page');
$description = t('temoignages.description');

/** Valeur à réafficher après une erreur — la saisie d'abord, le vide ensuite. */
$val = static function (string $nom) use ($valeurs): string {
    $v = $valeurs[$nom] ?? '';
    return is_scalar($v) ? (string) $v : '';
};

/** Bloc d'erreur d'un champ, et l'attribut aria qui va avec. */
$err = static function (string $nom) use ($erreurs): string {
    return isset($erreurs[$nom])
        ? '<p class="champ__erreur" id="err-' . View::e($nom) . '">' . View::e($erreurs[$nom]) . '</p>'
        : '';
};

$aria = static function (string $nom) use ($erreurs): string {
    return isset($erreurs[$nom]) ? ' aria-describedby="err-' . View::e($nom) . '"' : '';
};

$classe = static function (string $nom) use ($erreurs): string {
    return 'form-control champ__saisie' . (isset($erreurs[$nom]) ? ' est-fautif' : '');
};
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('temoignages.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('temoignages.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('temoignages.lead') ?></p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== CE QUI A ÉTÉ VALIDÉ ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">

    <?php if ($publiees === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <p class="t-lead reveal"><?= t_brut('temoignages.vide') ?></p>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="btn-pgy" href="#deposer">
              <?= t('temoignages.premier') ?> <span class="btn-pgy__arrow" aria-hidden="true">→</span>
            </a>
          </p>
        </div>
      </div>

    <?php else: ?>

      <div class="row" style="margin-bottom: var(--sp-7);">
        <div class="col-lg-8">
          <p class="kicker kicker--bare reveal">
            <?= count($publiees) > 1
                  ? t('temoignages.compte',    ['nombre' => count($publiees)])
                  : t('temoignages.compte_un', ['nombre' => count($publiees)]) ?>
          </p>
        </div>
        <div class="col-lg-4 d-flex align-items-end justify-content-lg-end">
          <a class="link reveal" href="#deposer"><?= t('temoignages.le_votre') ?></a>
        </div>
      </div>

      <div class="temoins">
        <?php foreach ($publiees as $t): ?>
          <figure class="temoin reveal">
            <blockquote class="temoin__texte">
              <?= nl2br(View::e($t['contenu'])) ?>
            </blockquote>
            <figcaption class="temoin__signature">
              <?= View::e($t['auteur_nom']) ?>
              <?php if (!empty($t['auteur_fonction'])): ?>
                <span><?= View::e($t['auteur_fonction']) ?></span>
              <?php endif; ?>
            </figcaption>
          </figure>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>

  </div>
</section>

<!-- ===================== DÉPOSER ===================== -->
<section class="section section--sunk" id="deposer">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('temoignages.form_kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('temoignages.form_titre') ?></h2>
      </div>
    </div>

    <div class="row" style="row-gap: var(--sp-8);">

      <div class="col-lg-7">

        <?php if (isset($erreurs['_global'])): ?>
          <p class="avis avis--refus" role="alert"><?= View::e($erreurs['_global']) ?></p>
        <?php endif; ?>

        <form class="formulaire" method="post" action="<?= Langue::chemin('/temoignages') ?>" novalidate>
          <?= Csrf::champ() ?>

          <div class="champ">
            <label class="form-label champ__titre" for="auteur_nom">
              <?= t('temoignages.nom') ?> <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="text" id="auteur_nom" name="auteur_nom" required
                   maxlength="160" autocomplete="name"
                   class="<?= $classe('auteur_nom') ?>"
                   value="<?= View::e($val('auteur_nom')) ?>"<?= $aria('auteur_nom') ?>>
            <?= $err('auteur_nom') ?>
            <p class="champ__aide"><?= t('temoignages.nom_aide') ?></p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="auteur_fonction"><?= t('temoignages.qualite') ?></label>
            <input type="text" id="auteur_fonction" name="auteur_fonction"
                   maxlength="200" class="<?= $classe('auteur_fonction') ?>"
                   placeholder="<?= t('temoignages.qualite_ph') ?>"
                   value="<?= View::e($val('auteur_fonction')) ?>"<?= $aria('auteur_fonction') ?>>
            <?= $err('auteur_fonction') ?>
            <p class="champ__aide"><?= t('temoignages.qualite_aide') ?></p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="auteur_email">
              <?= t('temoignages.email') ?> <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="email" id="auteur_email" name="auteur_email" required
                   maxlength="180" autocomplete="email"
                   class="<?= $classe('auteur_email') ?>"
                   value="<?= View::e($val('auteur_email')) ?>"<?= $aria('auteur_email') ?>>
            <?= $err('auteur_email') ?>
            <p class="champ__aide"><?= t_brut('temoignages.email_aide') ?></p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="contenu">
              <?= t('temoignages.texte') ?> <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <textarea id="contenu" name="contenu" rows="9" required
                      maxlength="5000" class="<?= $classe('contenu') ?>"<?= $aria('contenu') ?>><?= View::e($val('contenu')) ?></textarea>
            <?= $err('contenu') ?>
            <p class="champ__aide"><?= t('temoignages.texte_aide') ?></p>
          </div>

          <?php /* Piège à robots : masqué à l'œil et retiré aux lecteurs d'écran,
                   il n'est rempli que par un automate. Le refus qu'il déclenche est
                   dit en clair plutôt que silencieux — perdre le texte de quelqu'un
                   en lui laissant croire qu'il est parti serait pire. */ ?>
          <div class="leurre" aria-hidden="true">
            <label for="<?= View::e($leurre) ?>"><?= t('temoignages.leurre') ?></label>
            <input type="text" id="<?= View::e($leurre) ?>" name="<?= View::e($leurre) ?>"
                   tabindex="-1" autocomplete="off" value="">
          </div>

          <button class="btn-pgy" type="submit">
            <?= t('temoignages.envoyer') ?> <span class="btn-pgy__arrow" aria-hidden="true">→</span>
          </button>
        </form>
      </div>

      <div class="col-lg-4 offset-lg-1">
        <div class="note reveal">
          <p class="note__titre"><?= t('temoignages.note_titre') ?></p>
          <p><?= t('temoignages.note_1') ?></p>
          <p><?= t('temoignages.note_2') ?></p>
          <p class="note__pied"><?= t('temoignages.note_pied') ?></p>
        </div>
      </div>

    </div>
  </div>
</section>
