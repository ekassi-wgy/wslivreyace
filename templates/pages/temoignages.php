<?php
/**
 * Témoignages — page publique et formulaire de dépôt (CDC §4.8).
 *
 * Deux temps : ce qui a été validé, puis ce que le visiteur peut déposer. Dans
 * cet ordre, et pas l'inverse : on lit ce que d'autres ont écrit avant d'écrire
 * à son tour, et la page doit d'abord montrer qu'elle est vivante.
 */

use App\Core\Csrf;
use App\Core\View;

$titre       = 'Témoignages — Philippe Grégoire Yacé : une destinée';
$description = "Ceux qui ont connu Philippe Grégoire Yacé racontent. Déposez votre témoignage : "
             . 'il sera lu avant publication.';

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
        <p class="kicker reveal">Témoignages</p>
        <h1 class="t-d1 reveal">Ceux qui l'ont connu.</h1>
        <p class="t-lead page-head__lead reveal">
          Un compagnon de route, un élève, un adversaire d'un jour, un voisin de
          quartier. Ce que l'histoire officielle ne retient pas se garde ici.
        </p>
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
          <p class="t-lead reveal">
            <em>Aucun témoignage n'est encore publié.</em> Les premiers arriveront
            par le formulaire ci-dessous ; chacun est lu avant de paraître.
          </p>
          <p class="reveal" style="margin-top: var(--sp-5);">
            <a class="btn-pgy" href="#deposer">
              Déposer le premier <span class="btn-pgy__arrow" aria-hidden="true">→</span>
            </a>
          </p>
        </div>
      </div>

    <?php else: ?>

      <div class="row" style="margin-bottom: var(--sp-7);">
        <div class="col-lg-8">
          <p class="kicker kicker--bare reveal">
            <?= count($publiees) ?> témoignage<?= count($publiees) > 1 ? 's' : '' ?> publié<?= count($publiees) > 1 ? 's' : '' ?>
          </p>
        </div>
        <div class="col-lg-4 d-flex align-items-end justify-content-lg-end">
          <a class="link reveal" href="#deposer">Déposer le vôtre</a>
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
        <p class="kicker reveal">Votre tour</p>
        <h2 class="t-d1 reveal">Déposer un témoignage</h2>
      </div>
    </div>

    <div class="row" style="row-gap: var(--sp-8);">

      <div class="col-lg-7">

        <?php if (isset($erreurs['_global'])): ?>
          <p class="avis avis--refus" role="alert"><?= View::e($erreurs['_global']) ?></p>
        <?php endif; ?>

        <form class="formulaire" method="post" action="/temoignages" novalidate>
          <?= Csrf::champ() ?>

          <div class="champ">
            <label class="form-label champ__titre" for="auteur_nom">
              Votre nom <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="text" id="auteur_nom" name="auteur_nom" required
                   maxlength="160" autocomplete="name"
                   class="<?= $classe('auteur_nom') ?>"
                   value="<?= View::e($val('auteur_nom')) ?>"<?= $aria('auteur_nom') ?>>
            <?= $err('auteur_nom') ?>
            <p class="champ__aide">Il paraîtra sous votre témoignage s'il est publié.</p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="auteur_fonction">En quelle qualité</label>
            <input type="text" id="auteur_fonction" name="auteur_fonction"
                   maxlength="200" class="<?= $classe('auteur_fonction') ?>"
                   placeholder="Ancien collaborateur, historien, voisin de Yopougon…"
                   value="<?= View::e($val('auteur_fonction')) ?>"<?= $aria('auteur_fonction') ?>>
            <?= $err('auteur_fonction') ?>
            <p class="champ__aide">Facultatif, mais c'est souvent ce qui situe un témoignage.</p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="auteur_email">
              Votre adresse électronique <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="email" id="auteur_email" name="auteur_email" required
                   maxlength="180" autocomplete="email"
                   class="<?= $classe('auteur_email') ?>"
                   value="<?= View::e($val('auteur_email')) ?>"<?= $aria('auteur_email') ?>>
            <?= $err('auteur_email') ?>
            <p class="champ__aide">
              <strong>Jamais affichée.</strong> Elle sert uniquement à vous recontacter avant
              publication — un témoignage paraît sous votre nom, nous devons pouvoir le
              vérifier auprès de vous.
            </p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="contenu">
              Votre témoignage <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <textarea id="contenu" name="contenu" rows="9" required
                      maxlength="5000" class="<?= $classe('contenu') ?>"<?= $aria('contenu') ?>><?= View::e($val('contenu')) ?></textarea>
            <?= $err('contenu') ?>
            <p class="champ__aide">
              Un souvenir précis vaut mieux qu'un éloge général : une date, un lieu, une
              phrase entendue. Quarante caractères au minimum.
            </p>
          </div>

          <?php /* Piège à robots : masqué à l'œil et retiré aux lecteurs d'écran,
                   il n'est rempli que par un automate. Le refus qu'il déclenche est
                   dit en clair plutôt que silencieux — perdre le texte de quelqu'un
                   en lui laissant croire qu'il est parti serait pire. */ ?>
          <div class="leurre" aria-hidden="true">
            <label for="<?= View::e($leurre) ?>">Site web — laissez ce champ vide</label>
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
          <p class="note__titre">Ce qui se passe ensuite</p>
          <p>
            Votre texte n'apparaît pas tout de suite. Il est lu, et publié seulement
            s'il est vérifiable — c'est la règle que s'impose ce site : Philippe
            Grégoire Yacé est une figure historique réelle, et rien ne lui sera
            attribué sans source.
          </p>
          <p>
            Vous pouvez être recontacté avant publication. Votre adresse ne paraît
            jamais, ne part vers aucun service tiers, et n'alimente aucune lettre
            d'information.
          </p>
          <p class="note__pied">
            Cinq envois par heure au maximum, depuis une même connexion.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>
