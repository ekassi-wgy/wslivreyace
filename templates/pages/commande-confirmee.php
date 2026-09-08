<?php
/**
 * La confirmation de commande (lot G3).
 *
 * **Elle se lit une fois, depuis la session, et non depuis l'adresse.** Une
 * page `/commander/PGY-4F2K9A` serait partageable — et montrerait le nom, le
 * téléphone et l'adresse d'un client à qui aurait le lien. Le paiement à la
 * livraison n'a pas besoin d'une page à garder : il a besoin qu'on rappelle
 * le client, et la référence est là pour ça.
 *
 * **Pas de courriel.** Le README a tranché au lot F4 que `mail()` échoue en
 * silence sur un mutualisé. La référence est donc affichée en grand, avec la
 * consigne de la noter.
 */

use App\Core\Boutique;
use App\Core\Langue;
use App\Core\View;

$titre       = t_nu('confirmee.titre_page');
$description = '';
$robots      = 'noindex, nofollow';   // une confirmation n'a rien à faire dans un index

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$quantite = (int) $commande['quantite'];
$total    = (int) $commande['montant'];
$frais    = (int) $commande['frais_livraison'];
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= t('confirmee.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('confirmee.titre') ?></h1>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-7 offset-lg-2">

        <?php /* La référence, en grand : c'est ce que le client doit noter, et
                 ce qu'on lui demandera au téléphone. */ ?>
        <div class="her-citer reveal">
          <p class="arch-fiche__lbl"><?= t('confirmee.reference') ?></p>
          <p class="arch-fiche__url" style="font-size: 1.5rem; letter-spacing: .08em;">
            <?= View::e((string) $commande['reference']) ?>
          </p>
          <p class="t-small"><?= t('confirmee.reference_aide') ?></p>
        </div>

        <p class="t-body reveal" style="margin-top: var(--sp-7);">
          <?= t_brut('confirmee.suite') ?>
        </p>

        <?php /* Le mot de l'éditeur, s'il en a écrit un : délai de rappel,
                 moyens acceptés à la remise, un numéro à joindre. */ ?>
        <?php if ($message !== ''): ?>
          <div class="reveal" style="margin-top: var(--sp-6);">
            <?= View::paragraphes($message, 't-body') ?>
          </div>
        <?php endif; ?>

        <div class="rule reveal" style="margin-block: var(--sp-8);"></div>

        <p class="kicker kicker--bare reveal"><?= t('confirmee.detail') ?></p>
        <dl class="specs reveal">
          <div>
            <dt><?= $quantite > 1
                     ? t('confirmee.exemplaires_pluriel', ['nombre' => $quantite])
                     : t('confirmee.exemplaires', ['nombre' => $quantite]) ?></dt>
            <dd><?= View::e(Boutique::somme((int) $commande['prix_unitaire'] * $quantite)) ?></dd>
          </div>
          <div>
            <dt><?= $commande['livraison'] === 'livraison'
                     ? t('confirmee.mode_livraison')
                     : t('confirmee.mode_retrait') ?></dt>
            <dd>
              <?= View::e(Boutique::somme($frais)) ?>
              <?php if (!empty($commande['zone_libelle'])): ?>
                <span class="d-block t-small"><?= View::e((string) $commande['zone_libelle']) ?></span>
              <?php endif; ?>
            </dd>
          </div>
          <div>
            <dt><strong><?= t('confirmee.total') ?></strong></dt>
            <dd><strong><?= View::e(Boutique::somme($total)) ?></strong></dd>
          </div>
        </dl>

        <p class="reveal" style="margin-top: var(--sp-7);">
          <a class="link" href="<?= $lien('/le-livre') ?>"><?= t('confirmee.retour') ?></a>
        </p>

      </div>
    </div>
  </div>
</section>
