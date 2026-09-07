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

$titre       = 'Page introuvable — Philippe Grégoire Yacé';
$description = "La page demandée n'existe pas.";
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
        <p class="kicker">Page introuvable</p>
        <h1 class="t-d1">Cette page n'existe pas.</h1>
        <p class="t-lead page-head__lead">
          Le lien est peut-être ancien, mal recopié, ou la page a été déplacée.
          Cherchez ce que vous vouliez trouver&nbsp;:
        </p>

        <form class="arch-rech" method="get" action="<?= $lien('/recherche') ?>" role="search"
              style="margin-top: var(--sp-6);">
          <label class="arch-rech__label" for="q">Rechercher dans le site</label>
          <div class="arch-rech__ligne">
            <input class="arch-rech__champ" type="search" id="q" name="q"
                   value="<?= View::e($suppose) ?>"
                   placeholder="Un nom, un lieu, une année…">
            <button class="btn-pgy btn-pgy--sm" type="submit">Chercher</button>
          </div>
        </form>

        <p style="margin-top: var(--sp-7);">
          <a class="link" href="<?= $lien('/') ?>">Retour à l'accueil</a>
          &nbsp;·&nbsp;
          <a class="link" href="<?= $lien('/archives') ?>">Parcourir les archives</a>
        </p>
      </div>
    </div>
    <div class="rule" style="margin-top: var(--sp-9);"></div>
  </div>
</section>
