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

use App\Core\Csrf;
use App\Core\View;
use App\Model\Message;

$titre       = 'Contact — Philippe Grégoire Yacé : une destinée';
$description = "Écrire à l'équipe éditoriale de l'ouvrage consacré à Philippe Grégoire Yacé : "
             . 'adresse, téléphone et formulaire de contact.';

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
        <p class="kicker reveal">Contact</p>
        <h1 class="t-d1 reveal">Nous écrire.</h1>
        <p class="t-lead page-head__lead reveal">
          Une question sur l'ouvrage, une commande, une demande de presse, ou un
          document d'archive à proposer&nbsp;: la même adresse pour tout.
        </p>
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
            <p class="coord__titre">Notre adresse</p>
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
            <p class="coord__titre">Adresse électronique</p>
            <p class="coord__valeur">
              <a class="link" href="mailto:<?= View::e($email) ?>"><?= View::e($email) ?></a>
            </p>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($tel !== ''): ?>
        <div class="col-md-6 col-lg-3">
          <div class="coord reveal">
            <p class="coord__titre">Téléphone</p>
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
          <p class="coord__titre">Réponse</p>
          <p class="coord__valeur coord__valeur--texte">
            Les messages sont lus par l'équipe éditoriale. Comptez quelques jours&nbsp;:
            personne n'est de permanence.
          </p>
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
        <p class="kicker reveal">Formulaire</p>
        <h2 class="t-d1 reveal">Envoyer un message</h2>
      </div>
    </div>

    <div class="row" style="row-gap: var(--sp-8);">

      <div class="col-lg-7">

        <?php if (isset($erreurs['_global'])): ?>
          <p class="avis avis--refus" role="alert"><?= View::e($erreurs['_global']) ?></p>
        <?php endif; ?>

        <form class="formulaire" method="post" action="/contact" novalidate>
          <?= Csrf::champ() ?>

          <div class="champ">
            <label class="form-label champ__titre" for="nom">
              Votre nom <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="text" id="nom" name="nom" required
                   maxlength="160" autocomplete="name"
                   class="<?= $classe('nom') ?>"
                   value="<?= View::e($val('nom')) ?>"<?= $aria('nom') ?>>
            <?= $err('nom') ?>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="email">
              Votre adresse électronique <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <input type="email" id="email" name="email" required
                   maxlength="180" autocomplete="email"
                   class="<?= $classe('email') ?>"
                   value="<?= View::e($val('email')) ?>"<?= $aria('email') ?>>
            <?= $err('email') ?>
            <p class="champ__aide">C'est par là que la réponse vous parviendra.</p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="sujet">Motif</label>
            <select id="sujet" name="sujet" class="<?= $classe('sujet') ?>"<?= $aria('sujet') ?>>
              <?php foreach (Message::SUJETS as $cle => $libelle): ?>
                <option value="<?= View::e($cle) ?>"<?= $val('sujet') === $cle ? ' selected' : '' ?>>
                  <?= View::e($libelle) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?= $err('sujet') ?>
            <p class="champ__aide">Il sert au tri&nbsp;: choisissez le plus proche, sans y passer de temps.</p>
          </div>

          <div class="champ">
            <label class="form-label champ__titre" for="contenu">
              Votre message <span class="champ__requis" aria-hidden="true">*</span>
            </label>
            <textarea id="contenu" name="contenu" rows="8" required
                      maxlength="5000" class="<?= $classe('contenu') ?>"<?= $aria('contenu') ?>><?= View::e($val('contenu')) ?></textarea>
            <?= $err('contenu') ?>
            <p class="champ__aide">Vingt caractères au minimum.</p>
          </div>

          <?php /* Piège à robots, identique à celui des témoignages : masqué à
                   l'œil et retiré aux lecteurs d'écran, seul un automate le
                   remplit. Le refus qu'il déclenche est dit en clair. */ ?>
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
          <p class="note__titre">Ce que devient votre message</p>
          <p>
            Il est enregistré et lu par l'équipe éditoriale. Il n'est jamais
            publié sur ce site&nbsp;: pour paraître en public, c'est la page
            <a class="link" href="/temoignages">Témoignages</a> qu'il faut.
          </p>
          <p>
            Votre nom et votre adresse ne servent qu'à vous répondre. Ils ne
            partent vers aucun service tiers et n'alimentent aucune lettre
            d'information. Voir les
            <a class="link" href="/mentions-legales#donnees">mentions légales</a>.
          </p>
          <p class="note__pied">
            Cinq envois par heure au maximum, depuis une même connexion.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>
