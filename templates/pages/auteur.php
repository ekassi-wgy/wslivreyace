<?php
/**
 * L'auteur de l'ouvrage (CDC §4.3, brief §2, lot G2).
 *
 * **Une page et non une section**, comme le brief le demande : l'auteur se
 * partage seul — sur un plateau, dans un dossier de presse, sur une fiche de
 * libraire. Une ancre dans « Le livre » ne se partage pas.
 *
 * Tout vient des paramètres, donc du back-office : nom, qualité, biographie.
 * Le contrôleur a déjà refusé la page si le nom manque — une fiche d'auteur
 * sans auteur n'est pas une page, c'est un gabarit vide.
 */

use App\Core\Langue;
use App\Core\Site;
use App\Core\View;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$titre       = $nom . " — auteur de « " . $livreTitre . " »";
$description = trim($qualite) !== ''
    ? $nom . ', ' . $qualite . ", auteur de « " . $livreTitre . " »."
    : $nom . ", auteur de « " . $livreTitre . " ».";

$ld = json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type'    => 'Person',
    'name'     => $nom,
    'jobTitle' => trim($qualite) ?: null,
    'url'      => Site::url(Langue::chemin('/auteur')),
    // Le lien vers l'ouvrage : c'est ce qui rattache la personne au livre
    // pour un moteur, et ce qui fait remonter la page sur le nom de l'auteur.
    'author'   => ['@type' => 'Book', 'name' => $livreTitre],
], static fn($v): bool => $v !== null), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <?php $fil = [['Le livre', '/le-livre'], ["L'auteur", null]];
              require dirname(__DIR__) . '/partials/fil.php'; ?>
        <p class="kicker reveal">L'auteur</p>
        <h1 class="t-d1 reveal"><?= View::e($nom) ?></h1>
        <?php if (trim($qualite) !== ''): ?>
          <p class="t-lead page-head__lead reveal"><?= View::e($qualite) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-7 offset-lg-2">
        <?php if (trim($bio) !== ''): ?>
          <?= View::paragraphes($bio, 't-body') ?>
        <?php else: ?>
          <p class="t-body reveal"><em>Notice biographique à compléter.</em></p>
        <?php endif; ?>

        <p class="reveal" style="margin-top: var(--sp-7);">
          <a class="btn-pgy" href="<?= $lien('/le-livre') ?>">
            Découvrir l'ouvrage
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </p>
      </div>
    </div>
  </div>
</section>
