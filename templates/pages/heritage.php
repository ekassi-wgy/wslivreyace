<?php
/**
 * Héritage — l'index (brief §6, lot G7).
 *
 * Seules paraissent les rubriques qui portent quelque chose : c'est ce qui
 * permet d'ouvrir la page avant que les dix sujets ne soient réunis. Elle
 * montre ce qui existe et se tait sur le reste, au lieu d'afficher cinq
 * intertitres suivis de vide.
 */

use App\Core\Langue;
use App\Core\Site;
use App\Core\View;
use App\Model\Heritage;
use App\Model\Media;

$titre       = 'Héritage — Philippe Grégoire Yacé';
$description = "Ce qui perpétue aujourd'hui la mémoire de Philippe Grégoire Yacé : "
             . 'lieux, hommages, décorations, publications, musique et culture.';

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$ld = json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'CollectionPage',
    'name'     => 'Héritage de Philippe Grégoire Yacé',
    'url'      => Site::url(Langue::chemin('/heritage')),
    'about'    => ['@type' => 'Person', 'name' => 'Philippe Grégoire Yacé'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">—</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Héritage</p>
        <h1 class="t-d1 reveal">Ce qui reste.</h1>
        <p class="t-lead page-head__lead reveal">
          Un pont, un boulevard, un buste, une chanson. Des distinctions, des
          commémorations, des livres. Ce que la Côte d'Ivoire a gardé de
          Philippe Grégoire Yacé, et ce qu'elle en dit encore.
        </p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">

    <?php if ($groupes === []): ?>
      <div class="row">
        <div class="col-lg-8 offset-lg-2">
          <p class="t-body reveal">
            Cette rubrique se constitue. Les premiers sujets seront publiés
            prochainement.
          </p>
        </div>
      </div>
    <?php endif; ?>

    <?php foreach ($groupes as $cle => $sujets): ?>
      <section class="her-rubrique" id="<?= View::e($cle) ?>">
        <div class="row" style="margin-bottom: var(--sp-6);">
          <div class="col-lg-8 offset-lg-2">
            <p class="kicker reveal"><?= View::e(Heritage::rubrique($cle)) ?></p>
            <?php if (isset(Heritage::CHAPOS[$cle])): ?>
              <p class="t-lead reveal"><?= View::e(Heritage::CHAPOS[$cle]) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-10 offset-lg-2">
            <ul class="her-liste">
              <?php foreach ($sujets as $s): ?>
                <?php
                  $couverture = $couvertures[(int) $s['id']] ?? null;
                  $quand      = Heritage::date($s);
                  $lieu       = trim((string) ($s['lieu'] ?? ''));
                ?>
                <li class="reveal">
                  <a class="her-carte" href="<?= $lien(Heritage::chemin($s)) ?>">
                    <span class="her-carte__vue">
                      <?php if ($couverture !== null): ?>
                        <?php $srcset = Media::srcset($couverture); ?>
                        <img loading="lazy" decoding="async"
                             src="<?= View::e(Media::urlVignette((string) $couverture['fichier'])) ?>"
                             <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 30vw"' ?>
                             alt="<?= View::e(Media::alternative($couverture)) ?>">
                      <?php else: ?>
                        <?php /* Un sujet sans photographie garde sa carte : la
                                 matière visuelle arrivera après le texte, et
                                 attendre l'image retarderait la publication. */ ?>
                        <span class="her-carte__vide" aria-hidden="true"></span>
                      <?php endif; ?>
                    </span>

                    <span class="her-carte__corps">
                      <span class="her-carte__titre"><?= View::e((string) $s['titre']) ?></span>

                      <?php $soustitre = trim((string) ($s['sous_titre'] ?? '')); ?>
                      <?php if ($soustitre !== ''): ?>
                        <span class="her-carte__chapo"><?= View::e($soustitre) ?></span>
                      <?php endif; ?>

                      <?php if ($quand !== '' || $lieu !== ''): ?>
                        <span class="her-carte__meta">
                          <?= View::e($quand) ?><?= $quand !== '' && $lieu !== '' ? ' · ' : '' ?><?= View::e($lieu) ?>
                        </span>
                      <?php endif; ?>
                    </span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </section>
    <?php endforeach; ?>

    <?php /* --- Témoignages -------------------------------------------------
             Le brief les range sous Héritage. Ils vivent à leur adresse depuis
             le lot F1, avec leur formulaire et leur file de modération : on y
             renvoie plutôt que de les recopier ici, ce qui ferait deux endroits
             à tenir à jour. */ ?>
    <?php if ($temoignages !== []): ?>
      <section class="her-rubrique" id="temoignages">
        <div class="row" style="margin-bottom: var(--sp-6);">
          <div class="col-lg-8 offset-lg-2">
            <p class="kicker reveal">Témoignages</p>
            <p class="t-lead reveal">Ceux qui l'ont connu, et ce qu'ils en disent.</p>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-10 offset-lg-2">
            <ul class="her-mots">
              <?php foreach ($temoignages as $t): ?>
                <li class="reveal">
                  <blockquote>
                    <?= View::e(mb_strimwidth(preg_replace('/\s+/', ' ', (string) $t['contenu']) ?? '', 0, 220, '…')) ?>
                  </blockquote>
                  <p class="her-mots__qui">
                    <?= View::e((string) $t['auteur_nom']) ?>
                    <?php if (!empty($t['auteur_fonction'])): ?>
                      <span><?= View::e((string) $t['auteur_fonction']) ?></span>
                    <?php endif; ?>
                  </p>
                </li>
              <?php endforeach; ?>
            </ul>
            <p class="reveal" style="margin-top: var(--sp-6);">
              <a class="link" href="<?= $lien('/temoignages') ?>">Lire tous les témoignages, ou déposer le vôtre</a>
            </p>
          </div>
        </div>
      </section>
    <?php endif; ?>

  </div>
</section>
