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

$titre       = 'Contribuez aux archives — Philippe Grégoire Yacé';
$description = "Vous possédez une photographie, une vidéo, un discours, une lettre ou un "
             . 'document lié au parcours de Philippe Grégoire Yacé ? Aidez-nous à préserver sa mémoire.';

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
        <nav class="fil reveal" aria-label="Fil d'Ariane">
          <ol>
            <li><a href="<?= $lien('/archives') ?>">Archives</a></li>
            <li aria-current="page">Contribuer</li>
          </ol>
        </nav>
        <p class="kicker reveal">Contribuez aux archives</p>
        <h1 class="t-d1 reveal">Vous avez connu<br>Philippe Grégoire Yacé&nbsp;?</h1>
        <p class="t-lead page-head__lead reveal">
          Vous possédez une photographie, une vidéo, un discours, une lettre, un
          document ou un témoignage lié à son parcours&nbsp;? Aidez-nous à
          préserver et transmettre sa mémoire.
        </p>
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
            <label for="<?= View::e($leurre) ?>">Ne remplissez pas ce champ</label>
            <input type="text" id="<?= View::e($leurre) ?>" name="<?= View::e($leurre) ?>" tabindex="-1" autocomplete="off">
          </div>

          <fieldset>
            <legend>Vous</legend>

            <div class="champ-duo">
              <div class="champ">
                <label class="champ__titre" for="nom">Nom <span class="champ__requis" aria-hidden="true">*</span></label>
                <input type="text" class="<?= $classe('nom') ?>" id="nom" name="nom" value="<?= $val('nom') ?>" required maxlength="120"<?= $aria('nom') ?>>
                <?= $err('nom') ?>
              </div>
              <div class="champ">
                <label class="champ__titre" for="prenom">Prénom</label>
                <input type="text" class="<?= $classe('prenom') ?>" id="prenom" name="prenom" value="<?= $val('prenom') ?>" maxlength="120"<?= $aria('prenom') ?>>
                <?= $err('prenom') ?>
              </div>
            </div>

            <div class="champ-duo">
              <div class="champ">
                <label class="champ__titre" for="email">Adresse électronique <span class="champ__requis" aria-hidden="true">*</span></label>
                <input type="email" class="<?= $classe('email') ?>" id="email" name="email" value="<?= $val('email') ?>" required maxlength="180"<?= $aria('email') ?>>
                <span class="champ__aide">Elle ne sera jamais publiée. Elle sert à vous répondre.</span>
                <?= $err('email') ?>
              </div>
              <div class="champ">
                <label class="champ__titre" for="telephone">Téléphone</label>
                <input type="tel" class="<?= $classe('telephone') ?>" id="telephone" name="telephone" value="<?= $val('telephone') ?>" maxlength="40"<?= $aria('telephone') ?>>
                <?= $err('telephone') ?>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend>La pièce</legend>

            <div class="champ">
              <label class="champ__titre" for="description">Description <span class="champ__requis" aria-hidden="true">*</span></label>
              <textarea class="<?= $classe('description') ?>" id="description" name="description" rows="6" required maxlength="3000"<?= $aria('description') ?>><?= $val('description') ?></textarea>
              <span class="champ__aide">
                Ce que montre ou dit la pièce, qui y figure, dans quelles circonstances.
                Tout ce dont vous vous souvenez nous aide, même incertain.
              </span>
              <?= $err('description') ?>
            </div>

            <div class="champ-duo">
              <div class="champ">
                <label class="champ__titre" for="date_approx">Date approximative</label>
                <input type="text" class="<?= $classe('date_approx') ?>" id="date_approx" name="date_approx" value="<?= $val('date_approx') ?>"
                       maxlength="60" placeholder="vers 1965, années 70…"<?= $aria('date_approx') ?>>
                <?= $err('date_approx') ?>
              </div>
              <div class="champ">
                <label class="champ__titre" for="source">Origine de la pièce</label>
                <input type="text" class="<?= $classe('source') ?>" id="source" name="source" value="<?= $val('source') ?>"
                       maxlength="300" placeholder="album de famille, fonds…"<?= $aria('source') ?>>
                <?= $err('source') ?>
              </div>
            </div>

            <div class="champ">
              <label class="champ__titre" for="fichiers">Vos fichiers</label>
              <input type="file" id="fichiers" name="fichiers[]" multiple
                     accept="<?= View::e(Televersement::ACCEPTE) ?>"<?= $aria('fichiers') ?>>
              <span class="champ__aide">
                Images, PDF ou enregistrements — <?= (int) $lotMax ?> fichiers au plus par envoi.
                Vous pourrez en envoyer d'autres ensuite.
                <br>Si la pièce est trop lourde ou si vous préférez nous la montrer
                d'abord, décrivez-la simplement et nous vous répondrons.
              </span>
              <?= $err('fichiers') ?>
            </div>
          </fieldset>

          <fieldset>
            <legend>Droits</legend>
            <div class="champ champ--case">
              <label class="champ__titre" for="droits">
                <input type="checkbox" id="droits" name="droits" value="1"
                       <?= ($valeurs['droits'] ?? '') === '1' ? 'checked' : '' ?> required<?= $aria('droits') ?>>
                <span>
                  Je certifie détenir les droits sur les pièces que j'envoie, ou être
                  autorisé à les transmettre, et j'autorise leur publication sur ce
                  site avec la mention de leur provenance. <span class="champ__requis" aria-hidden="true">*</span>
                </span>
              </label>
              <?= $err('droits') ?>
            </div>
          </fieldset>

          <p class="champ__envoi">
            <button class="btn-pgy" type="submit">
              Envoyer ma contribution
              <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
            </button>
          </p>
        </form>
      </div>

      <aside class="col-lg-3 offset-lg-1">
        <div class="contrib-note reveal">
          <p class="kicker kicker--bare">Ce qu'il advient</p>
          <ol class="contrib-etapes">
            <li>Votre envoi arrive dans un espace fermé, que le site ne publie pas.</li>
            <li>Un relecteur l'examine, et vous écrit.</li>
            <li>S'il rejoint le fonds, il est catalogué, daté et crédité à votre nom
                ou à celui que vous indiquez.</li>
          </ol>
          <p class="contrib-note__fin">
            <strong>Rien n'est publié automatiquement</strong> — ni ici, ni ailleurs
            sur ce site. Une pièce refusée est effacée de nos serveurs.
          </p>
        </div>
      </aside>

    </div>
  </div>
</section>
