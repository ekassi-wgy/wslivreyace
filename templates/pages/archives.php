<?php
/**
 * Galerie d'archives (CDC §4.6).
 *
 * **La grille casse le rythme à dessein** : quatre proportions qui se répètent
 * — paysage large, portrait, carré, panoramique. Une grille régulière est le
 * premier réflexe de gabarit ; c'est déjà la règle de la section d'accueil, et
 * elle vaut à plus forte raison sur une planche qui compte des dizaines
 * d'images.
 *
 * **Chaque tuile est un lien vers l'image**, et non un bouton. Sans
 * JavaScript, la galerie reste entièrement parcourable : on clique, l'image
 * s'ouvre. La visionneuse est un supplément qui intercepte ce lien, pas une
 * condition pour voir les archives.
 */

use App\Core\View;
use App\Model\Media;

$titre       = 'Archives — Philippe Grégoire Yacé : une destinée';
$description = "Photographies et documents d'archives autour de Philippe Grégoire Yacé : "
             . 'portraits, images officielles, documents et coupures de presse.';

$courante = $categorie === null ? '' : Media::categorie($categorie);

/**
 * Les proportions de la trame, dans l'ordre de répétition. Portées par une
 * classe et non par un style en ligne : le CSS garde la main sur le rendu, et
 * la boucle ne fait que distribuer.
 */
$trame = ['large', 'haut', 'carre', 'pano'];
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Archives</p>
        <h1 class="t-d1 reveal">Ce qu'il reste.</h1>
        <p class="t-lead page-head__lead reveal">
          Photographies, documents officiels et coupures conservés par la
          famille et les institutions. Chaque pièce porte sa légende et son
          crédit&nbsp;: rien n'est publié ici sans qu'on sache d'où cela vient.
        </p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== PLANCHE ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">

    <?php if ($comptes !== []): ?>
      <nav class="filtres reveal" aria-label="Filtrer les archives">
        <div class="chips">
          <a class="chip<?= $categorie === null ? ' is-active' : '' ?>" href="/archives"
             <?= $categorie === null ? 'aria-current="page"' : '' ?>>Toutes</a>
<?php foreach ($comptes as $cle => $nombre): ?>
          <a class="chip<?= $categorie === $cle ? ' is-active' : '' ?>"
             href="/archives?categorie=<?= urlencode($cle) ?>"
             <?= $categorie === $cle ? 'aria-current="page"' : '' ?>>
            <?= View::e(Media::categorie($cle)) ?>
            <span class="chip__n"><?= (int) $nombre ?></span>
          </a>
<?php endforeach; ?>
        </div>
      </nav>
    <?php endif; ?>

    <?php if ($images === []): ?>

      <div class="row">
        <div class="col-lg-7">
          <?php if ($categorie === null): ?>
            <p class="t-lead reveal">
              <em>Aucune archive n'est encore publiée.</em> Les photographies et
              documents paraîtront ici au fur et à mesure de leur numérisation
              et de la vérification de leurs droits.
            </p>
          <?php else: ?>
            <p class="t-lead reveal">
              <em>Rien dans «&nbsp;<?= View::e($courante) ?>&nbsp;»</em> pour le moment.
            </p>
            <p class="reveal" style="margin-top: var(--sp-5);">
              <a class="link" href="/archives">Voir toutes les archives</a>
            </p>
          <?php endif; ?>
        </div>
      </div>

    <?php else: ?>

      <p class="kicker kicker--bare reveal" style="margin-bottom: var(--sp-7);">
        <?= count($images) ?> pièce<?= count($images) > 1 ? 's' : '' ?>
        <?= $courante === '' ? '' : '— ' . View::e(mb_strtolower($courante)) ?>
      </p>

      <?php /* La liste est annoncée comme telle : une planche d'archives est
               une énumération, et un lecteur d'écran doit pouvoir en connaître
               la longueur avant de s'y engager. */ ?>
      <ul class="gal" id="galerie">
<?php foreach ($images as $i => $img): ?>
        <?php
        $fichier = (string) $img['fichier'];
        $alt     = Media::alternative($img);
        $legende = trim((string) ($img['legende'] ?? ''));
        $credit  = trim((string) ($img['credit'] ?? ''));
        $prise   = trim((string) ($img['date_prise'] ?? ''));
        $srcset  = Media::srcset($img);
        ?>
        <li class="gal__i gal__i--<?= $trame[$i % count($trame)] ?> reveal">
          <?php /* href pointe la taille moyenne : c'est ce que la visionneuse
                   affiche, et ce que le clic ouvre quand elle n'est pas là. */ ?>
          <a class="gal__lien" href="<?= View::e(Media::urlMoyen($fichier)) ?>"
             data-visionneuse="<?= (int) $i ?>"
             data-legende="<?= View::e($legende) ?>"
             data-credit="<?= View::e($credit) ?>"
             data-prise="<?= View::e($prise) ?>"
             data-categorie="<?= View::e(Media::categorie((string) $img['categorie'])) ?>"
             data-alt="<?= View::e($alt) ?>">
            <img loading="lazy" decoding="async"
                 src="<?= View::e(Media::urlVignette($fichier)) ?>"
                 <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 767px) 100vw, 45vw"' ?>
                 alt="<?= View::e($alt) ?>">
            <?php if ($legende !== '' || $prise !== ''): ?>
              <span class="gal__legende">
                <?php if ($prise !== ''): ?><span class="gal__quand"><?= View::e($prise) ?></span><?php endif; ?>
                <?= View::e($legende) ?>
              </span>
            <?php endif; ?>
          </a>
        </li>
<?php endforeach; ?>
      </ul>

    <?php endif; ?>

  </div>
</section>

<?php if ($images !== []): ?>
<?php /* ===================== VISIONNEUSE =====================
     `<dialog>` et non une boîte maison : le navigateur donne le piège au
     clavier, la fermeture par Échap, le fond inerte et le retour du focus sur
     la tuile d'origine. Trois de ces quatre choses sont régulièrement ratées
     quand on les réécrit. Le dialogue est vide au chargement — son contenu est
     posé à l'ouverture, depuis les données de la tuile cliquée. */ ?>
<dialog class="vis" id="visionneuse" aria-label="Visionneuse d'archives">
  <div class="vis__cadre">
    <img class="vis__img" id="visImage" src="" alt="">
  </div>

  <?php /* `aria-live` : au passage d'une pièce à l'autre, seule l'image et
           cette zone changent. Sans annonce, un lecteur d'écran reste sur la
           légende de la pièce précédente. Ce n'est pas un `<figcaption>` : il
           n'y a pas de `<figure>` autour, et une balise de légende sans sa
           figure n'est pas du HTML valide. */ ?>
  <div class="vis__pied" aria-live="polite">
    <p class="vis__legende" id="visLegende"></p>
    <p class="vis__meta">
      <span id="visCredit"></span>
      <span class="vis__compte" id="visCompte"></span>
    </p>
  </div>

  <button class="vis__btn vis__btn--prec" id="visPrec" type="button" aria-label="Archive précédente">
    <span aria-hidden="true">&#8592;</span>
  </button>
  <button class="vis__btn vis__btn--suiv" id="visSuiv" type="button" aria-label="Archive suivante">
    <span aria-hidden="true">&#8594;</span>
  </button>
  <?php /* `autofocus` : sans lui, l'ouverture pose le focus sur la première
           commande venue — la flèche « précédente ». Le point d'entrée d'une
           visionneuse est sa sortie. */ ?>
  <button class="vis__fermer" id="visFermer" type="button" autofocus aria-label="Fermer la visionneuse">
    <span aria-hidden="true">&#215;</span>
  </button>
</dialog>
<?php endif; ?>
