<?php
/**
 * Page introuvable.
 *
 * **Elle offre la recherche depuis le lot G9**, et ce n'est pas un ornement.
 * La décision 3 du brief prévoit des adresses imprimées — QR codes,
 * filigranes, dossiers de presse, peut-être le livre lui-même. Une adresse
 * imprimée finit par être mal recopiée, et la 404 est alors le dernier endroit
 * où l'on peut encore rattraper le visiteur.
 *
 * Le terme de recherche est **pré-rempli avec ce que l'adresse contenait** :
 * quelqu'un qui tape `/archives/discours-de-1980` de travers cherche
 * probablement « discours de 1980 ».
 */

use App\Core\Langue;
use App\Core\View;

$titre       = t_nu('e404.titre_page');
$description = t_nu('e404.description');
$robots      = 'noindex, follow';

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

/**
 * Le dernier segment de l'adresse demandée, rendu lisible.
 *
 * Il vient de la requête, donc du visiteur : il n'est employé que comme valeur
 * d'un champ, échappé, et jamais dans une requête. Les segments purement
 * techniques ne donnent rien d'utile, on ne propose alors aucun terme.
 */
$segment = basename(parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '');
$suppose = trim(str_replace('-', ' ', preg_replace('/\.[a-z0-9]{1,5}$/i', '', $segment) ?? ''));
$suppose = preg_match('/^[\p{L}\p{N} ]{3,60}$/u', $suppose) === 1 ? $suppose : '';
?>
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num">404</p></div>
      <div class="col-lg-8">
        <p class="kicker"><?= t('e404.kicker') ?></p>
        <h1 class="t-d1"><?= t('e404.titre') ?></h1>
        <p class="t-lead page-head__lead">
          <?= t('e404.lead') ?>
        </p>

        <form class="arch-rech" method="get" action="<?= $lien('/recherche') ?>" role="search"
              style="margin-top: var(--sp-6);">
          <label class="arch-rech__label" for="q"><?= t('e404.label') ?></label>
          <div class="arch-rech__ligne">
            <input class="arch-rech__champ" type="search" id="q" name="q"
                   value="<?= View::e($suppose) ?>"
                   placeholder="<?= t('e404.placeholder') ?>">
            <button class="btn-pgy btn-pgy--sm" type="submit"><?= t('e404.chercher') ?></button>
          </div>
        </form>

        <p style="margin-top: var(--sp-7);">
          <a class="link" href="<?= $lien('/') ?>"><?= t('e404.retour') ?></a>
          &nbsp;·&nbsp;
          <a class="link" href="<?= $lien('/archives') ?>"><?= t('e404.archives') ?></a>
        </p>
      </div>
    </div>
    <div class="rule" style="margin-top: var(--sp-9);"></div>
  </div>
</section>
