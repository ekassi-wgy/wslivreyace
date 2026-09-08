<?php
/**
 * La page de commande (lot G3).
 *
 * **Une page, un formulaire.** Le site vend un seul ouvrage : il n'y a pas de
 * panier, seulement une quantité. Un tunnel en trois étapes pour un produit
 * unique est une cérémonie.
 *
 * **La page reste en ligne quand la boutique est fermée**, et dit pourquoi.
 * Un bouton « Commander » qui mène à une 404 fait croire à une panne ; un
 * bouton qui ne fait rien ment au clavier comme à la souris.
 *
 * **Un seul menu déroulant pour la zone**, portant le chemin complet et son
 * tarif. Trois menus en cascade exigeraient du JavaScript pour se remplir l'un
 * l'autre, et sans lui on pourrait composer un triplet incohérent. Le
 * récapitulatif se met à jour par script s'il y en a un, et la page reste
 * entièrement utilisable s'il n'y en a pas — le serveur recalcule de toute
 * façon.
 */

use App\Core\Boutique;
use App\Core\Csrf;
use App\Core\Langue;
use App\Core\View;
use App\Model\Commande;

$titre       = t_nu('commander.titre_page');
$description = t_nu('commander.description');

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

/** Valeur à réafficher après une erreur — la saisie d'abord, le défaut ensuite. */
$val = static fn(string $nom, string $defaut = ''): string
    => (string) ($valeurs[$nom] ?? $defaut);

$classe = static fn(string $nom): string
    => 'form-control champ__saisie' . (isset($erreurs[$nom]) ? ' est-fautif' : '');

$aria = static fn(string $nom): string
    => isset($erreurs[$nom]) ? ' aria-invalid="true" aria-describedby="err_' . $nom . '"' : '';

$err = static fn(string $nom): string => isset($erreurs[$nom])
    ? '<p class="champ__erreur" id="err_' . $nom . '" role="alert">' . View::e($erreurs[$nom]) . '</p>'
    : '';

$modeChoisi   = $val('livraison', Boutique::retraitPossible() ? 'retrait' : 'livraison');
$livrable     = $zones !== [];
$retraitOffert = $retrait !== '';
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <?php $fil = [[t('nav.livre'), '/le-livre'], [t('commander.fil'), null]];
              require dirname(__DIR__) . '/partials/fil.php'; ?>
        <p class="kicker reveal"><?= t('commander.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('commander.titre') ?></h1>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">

<?php if (!$ouverte): ?>

    <?php /* Boutique fermée : la page existe, elle explique, et elle renvoie
             ailleurs plutôt que de laisser le visiteur sans suite. */ ?>
    <div class="row">
      <div class="col-lg-7 offset-lg-2">
        <h2 class="t-d3 reveal"><?= t('commander.ferme_titre') ?></h2>
        <p class="t-body reveal"><?= t('commander.ferme_texte') ?></p>
        <p class="reveal" style="margin-top: var(--sp-6);">
          <a class="btn-pgy" href="<?= $lien('/le-livre') ?>">
            <?= t('commander.ferme_lien') ?>
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </p>
      </div>
    </div>

<?php elseif (!$livrable && !$retraitOffert): ?>

    <?php /* Boutique ouverte, mais rien pour remettre l'exemplaire : ni point
             de retrait, ni zone de livraison tarifée. Le formulaire refuserait
             tout envoi ; mieux vaut le dire d'emblée que de le laisser
             remplir pour rien. */ ?>
    <div class="row">
      <div class="col-lg-7 offset-lg-2">
        <h2 class="t-d3 reveal"><?= t('commander.aucun_mode_titre') ?></h2>
        <p class="t-body reveal"><?= t('commander.aucun_mode_texte') ?></p>
        <p class="reveal" style="margin-top: var(--sp-6);">
          <a class="btn-pgy" href="<?= $lien('/contact') ?>">
            <?= t('commander.aucun_mode_lien') ?>
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </p>
      </div>
    </div>

<?php else: ?>

    <div class="row" style="row-gap: var(--sp-8);">
      <div class="col-lg-7 offset-lg-1">

        <?php if (isset($erreurs['_global'])): ?>
          <p class="avis avis--refus" role="alert"><?= View::e($erreurs['_global']) ?></p>
        <?php endif; ?>

        <p class="t-lead reveal" style="margin-bottom: var(--sp-6);">
          <strong><?= t('commander.prix_unitaire') ?> :</strong>
          <?= View::e(Boutique::prixLisible()) ?>
        </p>

        <form class="formulaire reveal" method="post" action="<?= $lien('/commander') ?>" novalidate
              id="formCommande">
          <?= Csrf::champ() ?>

          <fieldset>
            <legend><?= t('commander.votre_commande') ?></legend>

            <div class="champ">
              <label class="champ__titre" for="quantite">
                <?= t('champ.quantite') ?> <span class="champ__requis" aria-hidden="true">*</span>
              </label>
              <input type="number" id="quantite" name="quantite"
                     class="<?= $classe('quantite') ?>" required
                     min="1" max="<?= Boutique::QUANTITE_MAX ?>" step="1"
                     value="<?= View::e($val('quantite', '1')) ?>"
                     data-prix="<?= (int) $prix ?>"<?= $aria('quantite') ?>>
              <?= $err('quantite') ?>
            </div>
          </fieldset>

          <fieldset>
            <legend><?= t('commander.mode') ?></legend>

            <?php if (!$livrable): ?>
              <p class="champ__aide"><?= t('commander.aucune_livraison') ?></p>
            <?php endif; ?>

            <div class="champ champ--case">
<?php if ($retraitOffert): ?>
              <label class="champ__titre" for="mode_retrait">
                <input type="radio" id="mode_retrait" name="livraison" value="retrait"
                       <?= $modeChoisi === 'retrait' ? 'checked' : '' ?> data-frais="0">
                <span>
                  <?= t('commander.retrait') ?>
                  <span class="champ__aide"><?= t('commander.retrait_gratuit') ?> — <?= nl2br(View::e($retrait)) ?></span>
                </span>
              </label>
<?php endif; ?>

<?php if ($livrable): ?>
              <label class="champ__titre" for="mode_livraison">
                <input type="radio" id="mode_livraison" name="livraison" value="livraison"
                       <?= $modeChoisi === 'livraison' ? 'checked' : '' ?>>
                <span><?= t('commander.livraison') ?></span>
              </label>
<?php endif; ?>
              <?= $err('livraison') ?>
            </div>

<?php if ($livrable): ?>
            <?php /* Le bloc de livraison reste dans le flux : masqué par
                     script quand le retrait est choisi, il reste accessible
                     sans script — le serveur ignore la zone si le mode est
                     « retrait ». */ ?>
            <div id="blocLivraison">
              <div class="champ">
                <label class="champ__titre" for="zone_id">
                  <?= t('commander.zone') ?> <span class="champ__requis" aria-hidden="true">*</span>
                </label>
                <select id="zone_id" name="zone_id" class="<?= $classe('zone_id') ?>"<?= $aria('zone_id') ?>>
                  <option value=""><?= t('commander.zone_choisir') ?></option>
<?php foreach ($zones as $groupe): ?>
                  <optgroup label="<?= View::e($groupe['pays']) ?>">
<?php foreach ($groupe['options'] as $o): ?>
                    <option value="<?= (int) $o['id'] ?>"
                            data-frais="<?= (int) $o['frais'] ?>"
                            <?= (string) $val('zone_id') === (string) $o['id'] ? 'selected' : '' ?>>
                      <?= View::e($o['libelle']) ?> — <?= View::e(Boutique::somme((int) $o['frais'])) ?>
                    </option>
<?php endforeach; ?>
                  </optgroup>
<?php endforeach; ?>
                </select>
                <p class="champ__aide"><?= t('commander.zone_aide') ?></p>
                <?= $err('zone_id') ?>
              </div>

              <div class="champ">
                <label class="champ__titre" for="adresse">
                  <?= t('champ.adresse') ?> <span class="champ__requis" aria-hidden="true">*</span>
                </label>
                <textarea id="adresse" name="adresse" rows="3" maxlength="500"
                          class="<?= $classe('adresse') ?>"<?= $aria('adresse') ?>><?= View::e($val('adresse')) ?></textarea>
                <p class="champ__aide"><?= t('commander.adresse_aide') ?></p>
                <?= $err('adresse') ?>
              </div>
            </div>
<?php endif; ?>
          </fieldset>

          <fieldset>
            <legend><?= t('commander.vous') ?></legend>

            <div class="champ">
              <label class="champ__titre" for="nom">
                <?= t('champ.nom') ?> <span class="champ__requis" aria-hidden="true">*</span>
              </label>
              <input type="text" id="nom" name="nom" required maxlength="160" autocomplete="name"
                     class="<?= $classe('nom') ?>" value="<?= View::e($val('nom')) ?>"<?= $aria('nom') ?>>
              <?= $err('nom') ?>
            </div>

            <div class="champ">
              <label class="champ__titre" for="telephone">
                <?= t('champ.telephone') ?> <span class="champ__requis" aria-hidden="true">*</span>
              </label>
              <input type="tel" id="telephone" name="telephone" required maxlength="40" autocomplete="tel"
                     class="<?= $classe('telephone') ?>" value="<?= View::e($val('telephone')) ?>"<?= $aria('telephone') ?>>
              <p class="champ__aide"><?= t('commander.tel_aide') ?></p>
              <?= $err('telephone') ?>
            </div>

            <div class="champ">
              <label class="champ__titre" for="email">
                <?= t('champ.email') ?> <span class="champ__requis" aria-hidden="true">*</span>
              </label>
              <input type="email" id="email" name="email" required maxlength="180" autocomplete="email"
                     class="<?= $classe('email') ?>" value="<?= View::e($val('email')) ?>"<?= $aria('email') ?>>
              <p class="champ__aide"><?= t('commander.email_aide') ?></p>
              <?= $err('email') ?>
            </div>
          </fieldset>

          <?php /* Piège à robots, comme sur les autres formulaires publics. */ ?>
          <div class="leurre" aria-hidden="true">
            <label for="<?= View::e($leurre) ?>"><?= t('commander.leurre') ?></label>
            <input type="text" id="<?= View::e($leurre) ?>" name="<?= View::e($leurre) ?>"
                   tabindex="-1" autocomplete="off" value="">
          </div>

          <p class="champ__envoi">
            <button class="btn-pgy" type="submit">
              <?= t('commander.envoyer') ?>
              <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
            </button>
          </p>
        </form>
      </div>

      <aside class="col-lg-3">
        <div class="note reveal" id="recap"
             data-devise="<?= View::e(Boutique::DEVISE) ?>">
          <p class="note__titre"><?= t('commander.recap') ?></p>
          <dl class="specs">
            <div>
              <dt id="recapLigneLivre"><?= t('commander.recap_livre', ['nombre' => (int) $val('quantite', '1')]) ?></dt>
              <dd id="recapLivre"><?= View::e(Boutique::somme((int) $prix * (int) $val('quantite', '1'))) ?></dd>
            </div>
            <div>
              <dt><?= t('commander.recap_frais') ?></dt>
              <dd id="recapFrais"><?= View::e(Boutique::somme(0)) ?></dd>
            </div>
            <div>
              <dt><strong><?= t('commander.recap_total') ?></strong></dt>
              <dd><strong id="recapTotal"><?= View::e(Boutique::somme((int) $prix * (int) $val('quantite', '1'))) ?></strong></dd>
            </div>
          </dl>
          <p class="note__pied"><?= t('commander.recap_apres') ?></p>
        </div>

        <div class="note reveal" style="margin-top: var(--sp-5);">
          <p class="note__titre"><?= t('commander.paiement_titre') ?></p>
          <p><?= t('commander.paiement_texte') ?></p>
        </div>
      </aside>
    </div>

<?php endif; ?>

  </div>
</section>
