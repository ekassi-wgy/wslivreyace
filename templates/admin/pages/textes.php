<?php
/**
 * Les textes des pages publiques (lot G16).
 *
 * Une carte par section de page — et par diapositive du diaporama de
 * l'accueil (lot G17) —, dérivée de `Parametre::TEXTES_PAGES` : une
 * section ajoutée au modèle paraît ici, dans la validation et à l'écran des
 * traductions sans qu'on retouche ce gabarit.
 *
 * **Sous chaque section, ce que le site affiche**, en français et dans chaque
 * langue déclarée. Une section qui disparaît faute de texte ne laisse aucune
 * trace dans le back-office, et un texte non traduit ne se voit que sur la page
 * anglaise : c'est le seul endroit d'où l'un et l'autre se voient en écrivant.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

require dirname(__DIR__) . '/partials/champs.php';

$rempli = static fn(array $valeurs, string $cle): bool => trim((string) ($valeurs[$cle] ?? '')) !== '';
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Textes des pages</h1>
    <p>Les textes rédigés d'une page qui ne sont ni une actualité, ni une période, ni une citation</p>
  </div>
</div>

<form method="post" action="<?= Admin::url('/textes') ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
<?php foreach ($sections as $section): ?>
    <?php
      /* Le texte long décide de la présence de la section ; le titre, s'il
         y en a un, n'est qu'un remplacement du titre par défaut. */
      $cleTexte = null;
      $cleTitre = null;

      foreach ($section['champs'] as $cle => $champ) {
          if ($champ['type'] === 'long' && $cleTexte === null) {
              $cleTexte = $cle;
          } elseif ($champ['type'] === 'titre' && $cleTitre === null) {
              $cleTitre = $cle;
          }
      }

      $visible = $cleTexte !== null && $rempli($enBase, $cleTexte);
    ?>
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash"><?= View::e($section['titre']) ?></h4>
        <p class="text-muted small"><?= View::e($section['aide']) ?></p>

        <?php foreach ($section['champs'] as $cle => $champ): ?>
          <?php
            /* Sous un champ qui a un texte par défaut, ce texte : c'est ce que
               le site affiche tant que le champ reste vide. */
            $aide = $champ['aide'];

            if (isset($champ['defaut'])) {
                $aide .= ' <span class="text-muted">Vide : « '
                       . nl2br(View::e(t_nu($champ['defaut'])), false) . ' ».</span>';
            }
          ?>
          <?php if ($champ['type'] === 'image'): ?>
            <?php champ_media($valeurs, $erreurs, $cle, $champ['libelle'], $medias, ['aide' => $aide]); ?>
          <?php elseif ($champ['type'] === 'document'): ?>
            <?php champ_choix($valeurs, $erreurs, $cle, $champ['libelle'], $documents, ['aide' => $aide]); ?>
          <?php elseif ($champ['type'] === 'ligne'): ?>
            <?php champ_texte($valeurs, $erreurs, $cle, $champ['libelle'], [
                'aide'      => $aide,
                'attributs' => 'maxlength="' . (int) ($champ['max'] ?? 200) . '"',
            ]); ?>
          <?php else: ?>
            <?php champ_zone($valeurs, $erreurs, $cle, $champ['libelle'], [
                'aide'   => $aide,
                'lignes' => match ($champ['type']) { 'titre' => 2, 'court' => 3, 'sommaire' => 8, default => 10 },
            ]); ?>
          <?php endif; ?>
        <?php endforeach; ?>

        <?php /* --- Ce que le site affiche aujourd'hui ------------------- */ ?>
        <?php if (!empty($section['toujours'])): ?>
          <?php
            /* Une section `toujours` — diapositive, « L'homme », présentation
               du livre — ne disparaît jamais : ce qui compte est de savoir ce
               qui y reste par défaut, ce qui en est absent faute de texte, et
               ce qui manque en anglais. */
            $parDefaut     = [];
            $absents       = [];
            $nonTraduits   = [];

            foreach ($section['champs'] as $cle => $champ) {
                if ($champ['type'] === 'image' || $champ['type'] === 'document') {
                    // Un fichier ne se traduit pas ; vide, il laisse un cadre
                    // d'attente ou ne laisse rien, selon le champ.
                    if (!$rempli($enBase, $cle)) {
                        if (!empty($champ['attente'])) {
                            $parDefaut[] = mb_strtolower($champ['libelle']) . ' (cadre d\'attente)';
                        } else {
                            $absents[] = mb_strtolower($champ['libelle']);
                        }
                    }
                } elseif (!$rempli($enBase, $cle)) {
                    // Sans texte par défaut, un champ vide n'affiche rien.
                    if (isset($champ['defaut'])) {
                        $parDefaut[] = mb_strtolower($champ['libelle']);
                    } else {
                        $absents[] = mb_strtolower($champ['libelle']);
                    }
                } else {
                    foreach ($langues as $code => $infos) {
                        if (trim((string) ($traduits[$code][$cle] ?? '')) === '') {
                            $nonTraduits[$code][] = mb_strtolower($champ['libelle']);
                        }
                    }
                }
            }
          ?>
          <div class="alert alert-success mb-3">
            <i class="mdi mdi-eye-outline me-1" aria-hidden="true"></i>
            <strong>Cette section paraît toujours</strong> sur la page
            <a href="<?= View::e($section['chemin']) ?>" target="_blank" rel="noopener"><?= View::e($section['page']) ?></a>.
            <?php if ($parDefaut !== []): ?>
              Par défaut : <?= View::e(implode(', ', $parDefaut)) ?>.
            <?php endif; ?>
            <?php if ($absents !== []): ?>
              Vides, donc absents du site : <?= View::e(implode(', ', $absents)) ?>.
            <?php endif; ?>
          </div>
          <?php foreach ($langues as $code => $infos): ?>
            <div class="alert <?= empty($nonTraduits[$code]) ? 'alert-light' : 'alert-warning' ?> mb-0 mt-2" role="note">
              <i class="mdi mdi-translate me-1" aria-hidden="true"></i>
              <strong><?= View::e($infos['nom']) ?></strong>
              <span class="text-muted">(<?= View::e($code) ?><?= $infos['active'] ? '' : ', fermée au public' ?>)</span> —
              <?php if (empty($nonTraduits[$code])): ?>
                tout ce qui est saisi est traduit ; les textes par défaut le sont déjà.
              <?php else: ?>
                <strong>non traduit</strong>, affiché en français :
                <?= View::e(implode(', ', $nonTraduits[$code])) ?>.
              <?php endif; ?>
              <a class="ms-1" href="<?= Admin::url('/traductions/parametre/textes') ?>">Traduire</a>
            </div>
          <?php endforeach; ?>
        <?php elseif ($visible): ?>
          <div class="alert alert-success mb-3">
            <i class="mdi mdi-eye-outline me-1" aria-hidden="true"></i>
            <strong>La section paraît</strong> sur la page
            <a href="<?= View::e($section['chemin']) ?>" target="_blank" rel="noopener"><?= View::e($section['page']) ?></a>.
            <?php if ($cleTitre !== null && !$rempli($enBase, $cleTitre)): ?>
              Elle porte son titre par défaut.
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-secondary mb-3">
            <i class="mdi mdi-eye-off-outline me-1" aria-hidden="true"></i>
            <strong>La section n'apparaît pas sur le site</strong>, dans aucune langue :
            le texte est vide. La page se referme proprement, sans cadre ni consigne.
          </div>
        <?php endif; ?>

        <?php if ($visible && empty($section['toujours'])): ?>
          <?php foreach ($langues as $code => $infos): ?>
            <?php
              $titrePropre  = $cleTitre !== null && $rempli($enBase, $cleTitre);
              $texteTraduit = trim((string) ($traduits[$code][$cleTexte] ?? '')) !== '';
              $titreTraduit = $cleTitre !== null && trim((string) ($traduits[$code][$cleTitre] ?? '')) !== '';

              /* Un titre laissé vide en français prend le titre par défaut
                 du lexique, qui existe dans toutes les langues : il n'y a
                 alors rien à traduire, et rien à signaler. */
              $etats = [$texteTraduit
                  ? 'texte traduit'
                  : '<strong>texte non traduit</strong> : la page affiche le français à sa place'];

              if ($cleTitre !== null) {
                  $etats[] = match (true) {
                      !$titrePropre => 'titre par défaut, déjà traduit',
                      $titreTraduit => 'titre traduit',
                      default       => '<strong>titre non traduit</strong>, affiché en français',
                  };
              }

              $complet = $texteTraduit && (!$titrePropre || $titreTraduit);
            ?>
            <div class="alert <?= $complet ? 'alert-light' : 'alert-warning' ?> mb-0 mt-2" role="note">
              <i class="mdi mdi-translate me-1" aria-hidden="true"></i>
              <strong><?= View::e($infos['nom']) ?></strong>
              <span class="text-muted">(<?= View::e($code) ?><?= $infos['active'] ? '' : ', fermée au public' ?>)</span> —
              <?= implode(' ; ', $etats) ?>.
              <a class="ms-1" href="<?= Admin::url('/traductions/parametre/textes') ?>">Traduire</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div></div>
    </div>
<?php endforeach; ?>

    <div class="col-lg-4 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Bon à savoir</h4>
        <ul class="pgy-liste-notes">
          <li><strong>Une ligne vide sépare deux paragraphes.</strong> C'est la seule
              mise en forme : le texte s'affiche tel qu'il est saisi, sans gras ni lien.</li>
          <li><strong>Un texte vide retire la section</strong> du site, en français
              comme en anglais. Mieux vaut pas de section qu'une consigne de rédaction
              affichée en ligne.</li>
          <li><strong>L'accueil et le livre font exception</strong> — le diaporama,
              « L'homme », la présentation du livre : ces sections paraissent toujours.
              Un champ vide y garde son texte par défaut, déjà traduit, ou disparaît
              s'il n'en a pas — l'aide sous le champ le dit ; une image absente laisse
              son cadre d'attente.</li>
          <li><strong>L'anglais se saisit à part</strong>, à l'écran
              <a href="<?= Admin::url('/traductions/parametre/textes') ?>">Traductions</a>,
              le français en regard. Un champ non traduit affiche le français.</li>
          <li>Corriger le français ne touche pas à la traduction déjà posée :
              pensez à la reprendre si le sens a changé.</li>
        </ul>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">Enregistrer les textes</button>
  </div>
</form>

<?php require dirname(__DIR__) . '/partials/selecteur-media.php'; ?>
