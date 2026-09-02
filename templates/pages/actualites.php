<?php
/**
 * Actualités — la liste (CDC §4.7).
 *
 * Liste éditoriale, sans vignettes : la décision est celle prise pour la
 * section d'accueil et elle vaut à plus forte raison ici — une actualité se
 * lit en ligne, date puis titre, et une grille de cartes ramènerait l'aspect
 * template que toute la charte évite. L'illustration existe, elle s'affiche
 * sur la fiche.
 *
 * Les filtres sont des liens, pas des boutons : chaque vue a son adresse, on
 * peut la partager et revenir dessus. La frise de la biographie filtre en
 * JavaScript parce qu'elle déplie du contenu déjà chargé ; ici, c'est une
 * requête différente, elle mérite une URL.
 */

use App\Core\DateFr;
use App\Core\View;
use App\Model\Actualite;

$titre       = 'Actualités — Philippe Grégoire Yacé : une destinée';
$description = "Parutions, dédicaces, hommages et rendez-vous autour de l'ouvrage "
             . 'consacré à Philippe Grégoire Yacé.';

/** La pastille « Presse » cède sa place au lien vers la revue de presse. */
$aPresse  = isset($comptes['presse']);
$filtres  = array_diff_key($comptes, ['presse' => null]);
$courante = $categorie === null ? '' : Actualite::categorie($categorie);
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Actualités</p>
        <h1 class="t-d1 reveal">Autour de l'ouvrage.</h1>
        <p class="t-lead page-head__lead reveal">
          Ce qui se passe autour du livre et de la mémoire de Philippe Grégoire
          Yacé&nbsp;: parutions, dédicaces, hommages et rendez-vous.
        </p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== LISTE ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">

    <?php if ($filtres !== [] || $aPresse): ?>
      <nav class="filtres reveal" aria-label="Filtrer les actualités">
        <div class="chips">
          <a class="chip<?= $categorie === null ? ' is-active' : '' ?>" href="/actualites"
             <?= $categorie === null ? 'aria-current="page"' : '' ?>>Toutes</a>
<?php foreach ($filtres as $cle => $nombre): ?>
          <a class="chip<?= $categorie === $cle ? ' is-active' : '' ?>"
             href="/actualites?categorie=<?= urlencode($cle) ?>"
             <?= $categorie === $cle ? 'aria-current="page"' : '' ?>>
            <?= View::e(Actualite::categorie($cle)) ?>
            <span class="chip__n"><?= (int) $nombre ?></span>
          </a>
<?php endforeach; ?>
        </div>

        <?php /* La revue de presse n'est pas une pastille de plus : elle mène
                 à une autre page, qui présente les mêmes entrées par organe et
                 par année. Le lien la distingue donc des filtres. */ ?>
        <?php if ($aPresse): ?>
          <a class="link" href="/revue-de-presse">
            Revue de presse — <?= (int) $comptes['presse'] ?> article<?= $comptes['presse'] > 1 ? 's' : '' ?>
          </a>
        <?php endif; ?>
      </nav>
    <?php endif; ?>

    <?php if ($entrees === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <?php if ($categorie === null): ?>
            <p class="t-lead reveal">
              <em>Aucune actualité n'est encore publiée.</em> Les parutions,
              dédicaces et rendez-vous autour de l'ouvrage paraîtront ici.
            </p>
          <?php else: ?>
            <p class="t-lead reveal">
              <em>Rien dans la catégorie «&nbsp;<?= View::e($courante) ?>&nbsp;»</em>
              pour le moment.
            </p>
            <p class="reveal" style="margin-top: var(--sp-5);">
              <a class="link" href="/actualites">Voir toutes les actualités</a>
            </p>
          <?php endif; ?>
        </div>
      </div>

    <?php else: ?>

      <div class="row">
        <div class="col-lg-10 offset-lg-2">
          <p class="kicker kicker--bare reveal">
            <?= count($entrees) ?>
            <?= $courante === '' ? 'entrée' : View::e(mb_strtolower($courante)) ?><?= count($entrees) > 1 ? 's' : '' ?>
          </p>

          <div class="news">
<?php foreach ($entrees as $e): ?>
            <a class="news__i reveal" href="/actualites/<?= View::e((string) $e['slug']) ?>">
              <time class="news__date" datetime="<?= View::e(DateFr::iso((string) $e['publie_le'])) ?>">
                <?= DateFr::longue((string) $e['publie_le']) ?>
              </time>
              <span class="news__body">
                <span class="news__t"><?= View::e((string) $e['titre']) ?></span>
                <?php if (!empty($e['chapo'])): ?>
                  <span class="news__chapo"><?= View::e((string) $e['chapo']) ?></span>
                <?php endif; ?>
                <?php /* L'organe remplace la mention de catégorie pour un
                         article de presse : « Fraternité Matin » dit plus que
                         « Presse ». */ ?>
                <?php if ($e['categorie'] === 'presse' && !empty($e['source'])): ?>
                  <span class="news__source"><?= View::e((string) $e['source']) ?></span>
                <?php endif; ?>
              </span>
              <span class="news__cat"><?= View::e(Actualite::categorie((string) $e['categorie'])) ?></span>
            </a>
<?php endforeach; ?>
          </div>
        </div>
      </div>

    <?php endif; ?>

  </div>
</section>
