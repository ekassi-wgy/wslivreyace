<?php
/**
 * La bibliothèque des discours (brief §4, lot G6).
 *
 * Le brief la décrit ainsi : « 1980 — Discours de clôture de la 5ᵉ législature »,
 * avec sur la même page contexte, vidéo, audio, transcription et document. La
 * page de notice porte tout cela depuis G4 et G5 ; ce qui manquait, c'était
 * **l'index qui y mène**.
 *
 * Un index et non une planche : un discours n'a souvent aucune image, et une
 * grille de tuiles grises ne dit rien de ce qu'on y trouvera. Chaque ligne
 * annonce donc sa date, son lieu et ce que la pièce porte réellement.
 */

use App\Core\Langue;
use App\Core\Site;
use App\Core\View;
use App\Model\Archive;

$titre       = t_nu('discours.titre_page');
$description = t_nu('discours.description');

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$filtre = static function (?int $an) use ($lien, $recherche): string {
    $q = array_filter(['annee' => $an, 'q' => $recherche === '' ? null : $recherche]);

    return $lien('/archives/discours') . ($q === [] ? '' : '?' . http_build_query($q));
};

/** Ce qu'une pièce porte, dit en toutes lettres plutôt qu'en pictogrammes seuls. */
$marques = [
    'video'         => ['🎥', t('discours.marque_video')],
    'audio'         => ['🎙️', t('discours.marque_audio')],
    'transcription' => ['📝', t('discours.marque_transcription')],
    'document'      => ['📄', t('discours.marque_document')],
];

$ld = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Collection',
    'name'     => t('discours.ld_nom'),
    'url'      => Site::url(Langue::chemin('/archives/discours')),
    'about'    => ['@type' => 'Person', 'name' => 'Philippe Grégoire Yacé'],
    'isPartOf' => [
        '@type' => 'Collection',
        'name'  => t('discours.ld_fonds'),
        'url'   => Site::url(Langue::chemin('/archives')),
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal" aria-hidden="true">🎙️</p></div>
      <div class="col-lg-8">
        <?php $fil = [[t('archives.fil'), '/archives'], [t('discours.fil'), null]];
              require dirname(__DIR__) . '/partials/fil.php'; ?>
        <p class="kicker reveal"><?= t('discours.kicker') ?></p>
        <h1 class="t-d1 reveal"><?= t('discours.titre') ?></h1>
        <p class="t-lead page-head__lead reveal"><?= t('discours.lead') ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">

    <div class="row" style="margin-bottom: var(--sp-6);">
      <div class="col-lg-10 offset-lg-2">

        <form class="arch-rech reveal" method="get" action="<?= $lien('/archives/discours') ?>" role="search">
          <label class="arch-rech__label" for="q"><?= t('discours.rechercher') ?></label>
          <div class="arch-rech__ligne">
            <?php /* La recherche du modèle couvre la transcription : c'est
                     l'intérêt de l'avoir saisie — on retrouve un discours par
                     une phrase qu'on en a retenue. */ ?>
            <input class="arch-rech__champ" type="search" id="q" name="q"
                   value="<?= View::e($recherche) ?>"
                   placeholder="<?= t('discours.placeholder') ?>">
            <?php if ($annee !== null): ?>
              <input type="hidden" name="annee" value="<?= (int) $annee ?>">
            <?php endif; ?>
            <button class="btn-pgy btn-pgy--sm" type="submit"><?= t('discours.chercher') ?></button>
          </div>
        </form>

        <?php if ($annees !== []): ?>
          <div class="chips chips--annees reveal" style="margin-top: var(--sp-5);" role="group" aria-label="<?= t('discours.filtre_annee') ?>">
            <a class="chip chip--fin<?= $annee === null ? ' is-active' : '' ?>" href="<?= $filtre(null) ?>"><?= t('discours.toutes_annees') ?></a>
            <?php foreach ($annees as $an => $n): ?>
              <a class="chip chip--fin<?= $an === $annee ? ' is-active' : '' ?>" href="<?= $filtre($an) ?>"><?= (int) $an ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">

        <?php if ($groupes === []): ?>
          <p class="t-body reveal">
            <?php if ($recherche !== '' || $annee !== null): ?>
              <?= t('discours.vide_recherche') ?>
              <a class="link" href="<?= $lien('/archives/discours') ?>"><?= t('discours.effacer') ?></a>
            <?php else: ?>
              <?= t('discours.vide') ?>
            <?php endif; ?>
          </p>
        <?php else: ?>

          <p class="kicker kicker--bare reveal" style="margin-bottom: var(--sp-6);">
            <?= (int) $total > 1
                  ? t('discours.compte', ['nombre' => (int) $total])
                  : t('discours.compte_un', ['nombre' => (int) $total]) ?>
          </p>

          <?php foreach ($groupes as $cle => $lot): ?>
            <section class="disc-groupe reveal">
              <?php /* « Années 1960 » et non « 1960s » : le pluriel anglais
                       d'une décennie n'existe pas en français. */ ?>
              <h2 class="disc-groupe__titre"><?= $cle === 'sans-date'
                  ? t('discours.sans_date')
                  : t('discours.decennie', ['annee' => $cle, 'annees' => $cle . 's']) ?></h2>

              <ol class="disc-liste">
                <?php foreach ($lot as $d): ?>
                  <?php
                    $porte = $contenus[(int) $d['id']] ?? [];
                    $quand = Archive::date($d);
                    $lieu  = trim((string) ($d['lieu'] ?? ''));
                  ?>
                  <li>
                    <a class="disc-item" href="<?= $lien(Archive::chemin($d)) ?>">
                      <span class="disc-item__annee"><?= $d['annee'] === null ? '—' : (int) $d['annee'] ?></span>

                      <span class="disc-item__corps">
                        <span class="disc-item__titre"><?= View::e((string) $d['titre']) ?></span>

                        <?php if ($quand !== '' || $lieu !== ''): ?>
                          <span class="disc-item__meta">
                            <?= View::e($quand) ?><?= $quand !== '' && $lieu !== '' ? ' · ' : '' ?><?= View::e($lieu) ?>
                          </span>
                        <?php endif; ?>

                        <?php /* Ce que la pièce porte, annoncé avant le clic :
                                 sur deux cents discours, ouvrir pour découvrir
                                 qu'il n'y a qu'un titre est une perte de temps
                                 répétée deux cents fois. */ ?>
                        <?php $presents = array_filter($marques, static fn($m, $c) => !empty($porte[$c]), ARRAY_FILTER_USE_BOTH); ?>
                        <?php if ($presents !== []): ?>
                          <span class="disc-item__porte">
                            <?php foreach ($presents as $signe): ?>
                              <span class="disc-marque">
                                <span aria-hidden="true"><?= $signe[0] ?></span><?= View::e($signe[1]) ?>
                              </span>
                            <?php endforeach; ?>
                          </span>
                        <?php endif; ?>
                      </span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ol>
            </section>
          <?php endforeach; ?>

        <?php endif; ?>

      </div>
    </div>
  </div>
</section>
