<?php
/**
 * Mentions légales et politique de confidentialité (CDC §4.12).
 *
 * **Ce qui est écrit ici doit être vrai.** Les sections « données », « cookies »
 * et « services tiers » décrivent le comportement réel du site, vérifié dans le
 * code : un cookie de session posé sur les deux pages qui portent un
 * formulaire et nulle part ailleurs, aucune mesure d'audience, et deux
 * ressources chargées depuis des domaines tiers. Elles ne sont pas recopiées
 * d'un modèle.
 *
 * Ce qui relève de l'état civil de la structure éditrice — raison sociale,
 * registre, directeur de la publication, hébergeur — ne peut pas s'inventer et
 * reste balisé comme à fournir. Voir le §5 du README.
 *
 * Les coordonnées viennent de la configuration, comme sur la page Contact :
 * une adresse recopiée à deux endroits finit par diverger.
 */

use App\Core\Langue;
use App\Core\View;

$titre       = t_nu('mentions.titre_page');
$description = t_nu('mentions.description');

$adresse = trim((string) ($contact['adresse'] ?? ''));
$ville   = trim((string) ($contact['ville'] ?? ''));
$pays    = trim((string) ($contact['pays'] ?? ''));
$email   = trim((string) ($contact['email'] ?? ''));
$tel     = trim((string) ($contact['telephone'] ?? ''));
$site    = trim((string) ($contact['site'] ?? ''));

$postale = implode(', ', array_filter([$adresse, $ville, $pays]));
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('mentions.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('mentions.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('mentions.lead') ?></p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">

        <div class="chap reveal" id="editeur">
          <span class="chap__num">01</span>
          <h2 class="t-d3"><?= t('mentions.editeur') ?></h2>
          <p class="t-body"><?= t_brut('mentions.editeur_texte') ?></p>
          <?php if ($postale !== ''): ?>
            <p class="t-body"><?= View::e($postale) ?></p>
          <?php endif; ?>
          <p class="t-body">
            <?php if ($email !== ''): ?>
              <?= t('mentions.courriel') ?> <a class="link" href="mailto:<?= View::e($email) ?>"><?= View::e($email) ?></a><br>
            <?php endif; ?>
            <?php if ($tel !== ''): ?><?= t('mentions.telephone') ?> <?= View::e($tel) ?><br><?php endif; ?>
            <?php if ($site !== ''): ?><?= t('mentions.site') ?> <?= View::e($site) ?><?php endif; ?>
          </p>
          <p class="t-body">
            <strong><?= t('mentions.directeur') ?></strong>
            <?= t_brut('mentions.directeur_texte') ?>
          </p>
        </div>

        <div class="chap reveal" id="hebergement">
          <span class="chap__num">02</span>
          <h2 class="t-d3"><?= t('mentions.hebergement') ?></h2>
          <p class="t-body"><?= t_brut('mentions.hebergement_texte') ?></p>
        </div>

        <div class="chap reveal" id="propriete">
          <span class="chap__num">03</span>
          <h2 class="t-d3"><?= t('mentions.propriete') ?></h2>
          <p class="t-body"><?= t('mentions.propriete_1') ?></p>
          <p class="t-body"><?= t_brut('mentions.propriete_2') ?></p>
          <p class="t-body"><?= t_brut('mentions.propriete_3') ?></p>
        </div>

        <div class="chap reveal" id="donnees">
          <span class="chap__num">04</span>
          <h2 class="t-d3"><?= t('mentions.donnees') ?></h2>
          <p class="t-body"><?= t('mentions.donnees_1') ?></p>
          <p class="t-body"><?= t_brut('mentions.donnees_2') ?></p>
          <p class="t-body"><?= t_brut('mentions.donnees_3') ?></p>
          <p class="t-body"><?= t_brut('mentions.donnees_4') ?></p>
          <p class="t-body"><?= t_brut('mentions.donnees_5') ?></p>
          <p class="t-body">
            <?= t_brut('mentions.droits_titre') ?>
            <?php if ($email !== ''): ?><?= t('mentions.droits_a') ?>
              <a class="link" href="mailto:<?= View::e($email) ?>"><?= View::e($email) ?></a><?php else: ?>
              <?= t('mentions.droits_editeur') ?><?php endif; ?>. <?= t('mentions.droits_fin') ?>
          </p>
        </div>

        <div class="chap reveal" id="cookies">
          <span class="chap__num">05</span>
          <h2 class="t-d3"><?= t('mentions.cookies') ?></h2>
          <p class="t-body"><?= t_brut('mentions.cookies_1') ?></p>
          <p class="t-body"><?= t_brut('mentions.cookies_2') ?></p>
          <p class="t-body"><?= t_brut('mentions.cookies_3') ?></p>
        </div>

        <div class="chap reveal" id="tiers">
          <span class="chap__num">06</span>
          <h2 class="t-d3"><?= t('mentions.tiers') ?></h2>
          <p class="t-body"><?= t_brut('mentions.tiers_texte') ?></p>
        </div>

        <div class="chap reveal" id="signalement">
          <span class="chap__num">07</span>
          <h2 class="t-d3"><?= t('mentions.signalement') ?></h2>
          <p class="t-body">
            <?= t('mentions.signalement_1') ?>
            <a class="link" href="<?= Langue::chemin('/contact') ?>"><?= t('mentions.signalement_lien') ?></a><?= t('mentions.signalement_2') ?>
          </p>
        </div>

      </div>
    </div>
  </div>
</section>
