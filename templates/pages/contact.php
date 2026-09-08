<?php
/**
 * Contact (CDC §4.11).
 *
 * Deux temps, comme la page des témoignages : ce qu'on peut lire d'abord — les
 * coordonnées —, ce qu'on peut écrire ensuite. Dans cet ordre, et pas
 * l'inverse : beaucoup de gens viennent chercher une adresse ou un numéro, pas
 * un formulaire, et leur imposer de le dépasser serait leur faire perdre du
 * temps.
 *
 * Les coordonnées viennent de la configuration, jamais du gabarit : elles
 * paraissent aussi dans les mentions légales, et une adresse recopiée à deux
 * endroits finit par diverger. Une valeur vide ne s'affiche pas.
 */

use App\Core\Langue;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Message;

$titre       = t('contact.titre_page');
$description = t('contact.description');

/** Valeur à réafficher après une erreur — la saisie d'abord, le vide ensuite. */
$val = static function (string $nom) use ($valeurs): string {
    $v = $valeurs[$nom] ?? '';
    return is_scalar($v) ? (string) $v : '';
};

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

$adresse = trim((string) ($contact['adresse'] ?? ''));
$ville   = trim((string) ($contact['ville'] ?? ''));
$pays    = trim((string) ($contact['pays'] ?? ''));
$email   = trim((string) ($contact['email'] ?? ''));
$tel     = trim((string) ($contact['telephone'] ?? ''));
$telLien = trim((string) ($contact['tel_lien'] ?? ''));
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('contact.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('contact.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('contact.lead') ?></p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== COORDONNÉES ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row" style="row-gap: var(--sp-7);">

      <?php if ($adresse !== ''): ?>
        <div class="col-md-6 col-lg-3">
          <div class="coord reveal">
            <p class="coord__titre"><?= t('contact.adresse') ?></p>
            <p class="coord__valeur">
              <?= View::e($adresse) ?>
              <?php if ($ville !== ''): ?><br><?= View::e($ville) ?><?php endif; ?>
              <?php if ($pays !== ''): ?><br><?= View::e($pays) ?><?php endif; ?>
            </p>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($email !== ''): ?>
        <div class="col-md-6 col-lg-3">
          <div class="coord reveal">
            <p class="coord__titre"><?= t('contact.email') ?></p>
            <p class="coord__valeur">
              <a class="link" href="mailto:<?= View::e($email) ?>"><?= View::e($email) ?></a>
            </p>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($tel !== ''): ?>
        <div class="col-md-6 col-lg-3">
          <div class="coord reveal">
            <p class="coord__titre"><?= t('contact.tel') ?></p>
            <p class="coord__valeur">
              <?php /* Le lien `tel:` porte la forme internationale sans espaces,
                       l'œil garde la forme lisible : un numéro composé depuis un
                       téléphone ne doit pas dépendre de la typographie. */ ?>
              <a class="link" href="tel:<?= View::e($telLien !== '' ? $telLien : $tel) ?>"><?= View::e($tel) ?></a>
            </p>
          </div>
        </div>
      <?php endif; ?>

      <div class="col-md-6 col-lg-3">
        <div class="coord reveal">
          <p class="coord__titre"><?= t('contact.reponse') ?></p>
          <p class="coord__valeur coord__valeur--texte"><?= t('contact.reponse_texte') ?></p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===================== ÉCRIRE ===================== -->
<section class="section section--sunk" id="ecrire">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal"><?= t('contact.form_kicker') ?></p>
        <h2 class="t-d1 reveal"><?= t('contact.form_titre') ?></h2>
      </div>
    </div>

    <div class="row" style="row-gap: var(--sp-8);">

      <div class="col-lg-7">

        <?php if (isset($erreurs['_global'])): ?>
          <p class="avis avis--refus" role="alert"><?= View::e($erreurs['_global']) ?></p>
        <?php endif; ?>

        <form class="formulaire" method="post" action="<?= Langue::chemin('/contact') ?>" novalidate>
          <?= Csrf::champ() ?>

          <div class="champ">
            <label class="form-label champ__titre" for="nom">
              <?= t('contact.nom') ?> <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="text" id="nom" name="nom" required
                   maxlength="160" autocomplete="name"
                   class="<?= $classe('nom') ?>"
                   value="<?= View::e($val('nom')) ?>"<?= $aria('nom') ?>>
            <?= $err('nom') ?>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="email">
              <?= t('contact.email_champ') ?> <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="email" id="email" name="email" required
                   maxlength="180" autocomplete="email"
                   class="<?= $classe('email') ?>"
                   value="<?= View::e($val('email')) ?>"<?= $aria('email') ?>>
            <?= $err('email') ?>
            <p class="champ__aide"><?= t('contact.email_aide') ?></p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="sujet"><?= t('contact.motif') ?></label>
            <select id="sujet" name="sujet" class="<?= $classe('sujet') ?>"<?= $aria('sujet') ?>>
              <?php foreach (Message::SUJETS as $cle => $libelle): ?>
                <option value="<?= View::e($cle) ?>"<?= $val('sujet') === $cle ? ' selected' : '' ?>>
                  <?= View::e($libelle) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?= $err('sujet') ?>
            <p class="champ__aide"><?= t('contact.motif_aide') ?></p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="contenu">
              <?= t('contact.message') ?> <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <textarea id="contenu" name="contenu" rows="8" required
                      maxlength="5000" class="<?= $classe('contenu') ?>"<?= $aria('contenu') ?>><?= View::e($val('contenu')) ?></textarea>
            <?= $err('contenu') ?>
            <p class="champ__aide"><?= t('contact.message_aide') ?></p>
          </div>

          <?php /* Piège à robots, identique à celui des témoignages : masqué à
                   l'œil et retiré aux lecteurs d'écran, seul un automate le
                   remplit. Le refus qu'il déclenche est dit en clair. */ ?>
          <div class="leurre" aria-hidden="true">
            <label for="<?= View::e($leurre) ?>"><?= t('contact.leurre') ?></label>
            <input type="text" id="<?= View::e($leurre) ?>" name="<?= View::e($leurre) ?>"
                   tabindex="-1" autocomplete="off" value="">
          </div>

          <button class="btn-pgy" type="submit">
            Envoyer <span class="btn-pgy__arrow" aria-hidden="true">→</span>
          </button>
        </form>
      </div>

      <div class="col-lg-4 offset-lg-1">
        <div class="note reveal">
          <p class="note__titre"><?= t('contact.note_titre') ?></p>
          <p>
            <?= t('contact.note_1') ?>
            <a class="link" href="<?= Langue::chemin('/temoignages') ?>"><?= t('contact.note_1_lien') ?></a> <?= t('contact.note_1_fin') ?>
          </p>
          <p>
            <?= t('contact.note_2') ?>
            <a class="link" href="<?= Langue::chemin('/mentions-legales') ?>#donnees"><?= t('contact.note_2_lien') ?></a>.
          </p>
          <p class="note__pied"><?= t('contact.note_pied') ?></p>
        </div>
      </div>

    </div>
  </div>
</section>
