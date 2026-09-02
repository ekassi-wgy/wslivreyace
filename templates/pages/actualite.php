<?php
/**
 * Actualité — la fiche, servie par son slug (CDC §4.7).
 *
 * C'est la première page du site dont le titre, la description et l'aperçu de
 * partage viennent de la base : trois raisons de plus de tout échapper, y
 * compris le JSON-LD, qui est écrit dans un `<script>` (voir plus bas).
 */

use App\Core\DateFr;
use App\Core\Site;
use App\Core\View;
use App\Model\Actualite;
use App\Model\Media;

$categorie = Actualite::categorie((string) $actu['categorie']);
$dateTexte = DateFr::longueTexte((string) $actu['publie_le']);
$chapo     = trim((string) ($actu['chapo'] ?? ''));
$source    = trim((string) ($actu['source'] ?? ''));
$sourceUrl = trim((string) ($actu['source_url'] ?? ''));

$titre = $actu['titre'] . ' — Philippe Grégoire Yacé : une destinée';

/* La description de partage : le chapô s'il existe, sinon l'entrée du texte.
   Coupée sur un mot entier — une phrase tranchée au milieu d'un mot se voit
   dans un aperçu de partage. */
$description = $chapo !== ''
    ? $chapo
    : trim(mb_strimwidth(preg_replace('/\s+/u', ' ', (string) $actu['contenu']) ?? '', 0, 200, '…'));

/* Aperçu de partage propre à l'article quand il est illustré. L'URL doit être
   absolue ; les dimensions ne sont écrites que si la médiathèque les connaît. */
if ($media !== null) {
    $ogImage  = Site::url(Media::url((string) $actu['image']));
    $ogAlt    = Media::alternative($media);
    $ogTaille = ($media['largeur'] ?? 0) && ($media['hauteur'] ?? 0)
        ? [(int) $media['largeur'], (int) $media['hauteur']]
        : null;
}

$ogType = 'article';

/**
 * JSON-LD.
 *
 * Écrit par `json_encode` et non à la main : les valeurs viennent de la base,
 * et un guillemet dans un titre casserait un gabarit écrit à la ficelle.
 * `JSON_HEX_TAG` est l'option qui compte — sans elle, un titre contenant
 * `</script>` fermerait la balise et ferait passer la suite pour du code. Les
 * pages statiques du site s'en dispensaient, leur JSON-LD étant écrit en dur.
 */
$donneesLd = array_filter([
    '@context'         => 'https://schema.org',
    '@type'            => 'NewsArticle',
    'headline'         => (string) $actu['titre'],
    'datePublished'    => DateFr::iso((string) $actu['publie_le']),
    'inLanguage'       => 'fr',
    'mainEntityOfPage' => Site::url('/actualites/' . $actu['slug']),
    'description'      => $description,
    'image'            => $media !== null ? Site::url(Media::url((string) $actu['image'])) : '',
    'about'            => [
        '@type' => 'Book',
        'name'  => 'Philippe Grégoire Yacé : une destinée (1920-1998)',
    ],
], static fn($v) => $v !== '' && $v !== null);

$ld = json_encode(
    $donneesLd,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT
) ?: '';
?>

<article>

<!-- ===================== EN-TÊTE DE L'ARTICLE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2">
        <p class="section-num reveal"><?= View::e(DateFr::annee((string) $actu['publie_le'])) ?></p>
      </div>
      <div class="col-lg-8">
        <p class="kicker reveal"><?= View::e($categorie) ?></p>
        <h1 class="t-d1 reveal"><?= View::e((string) $actu['titre']) ?></h1>

        <p class="article__meta reveal">
          <time datetime="<?= View::e(DateFr::iso((string) $actu['publie_le'])) ?>">
            <?= DateFr::longue((string) $actu['publie_le']) ?>
          </time>
          <?php if ($source !== ''): ?>
            <span class="article__organe"><?= View::e($source) ?></span>
          <?php endif; ?>
        </p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== CORPS ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">

        <?php if ($media !== null): ?>
          <?php /* Le crédit accompagne l'image, toujours : une archive publiée
                   sans mention de sa source expose l'éditeur (CDC §6). */ ?>
          <figure class="article__figure reveal">
            <span class="frame">
              <img loading="lazy" decoding="async"
                   src="<?= View::e(Media::url((string) $actu['image'])) ?>"
                   <?php if (($media['largeur'] ?? 0) && ($media['hauteur'] ?? 0)): ?>
                   width="<?= (int) $media['largeur'] ?>" height="<?= (int) $media['hauteur'] ?>"
                   <?php endif; ?>
                   alt="<?= View::e(Media::alternative($media)) ?>">
            </span>
            <?php if (trim((string) ($media['legende'] ?? '')) !== '' || trim((string) ($media['credit'] ?? '')) !== ''): ?>
              <figcaption class="article__legende">
                <?= View::e(trim((string) ($media['legende'] ?? ''))) ?>
                <?php if (trim((string) ($media['credit'] ?? '')) !== ''): ?>
                  <span class="article__credit">© <?= View::e(trim((string) $media['credit'])) ?></span>
                <?php endif; ?>
              </figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>

        <div class="article">
          <?php if ($chapo !== ''): ?>
            <p class="t-lead article__chapo reveal"><?= View::e($chapo) ?></p>
          <?php endif; ?>

          <?php /* Texte brut saisi au back-office : échappé, jamais servi
                   comme du HTML — voir View::paragraphes. */ ?>
          <div class="reveal"><?= View::paragraphes((string) $actu['contenu'], 't-body') ?></div>

          <?php if ($sourceUrl !== ''): ?>
            <?php /* Lien sortant. Pas de target="_blank" : forcer une nouvelle
                     fenêtre retire au visiteur le contrôle de sa navigation et
                     casse le retour arrière. `noopener` malgré tout, au cas où
                     un navigateur ou une extension l'ouvrirait quand même. */ ?>
            <p class="article__source reveal">
              <a class="link" href="<?= View::e($sourceUrl) ?>" rel="noopener nofollow">
                Lire l'article<?= $source !== '' ? ' sur ' . View::e($source) : ' original' ?>
                <span aria-hidden="true">&#8599;</span>
              </a>
            </p>
          <?php endif; ?>
        </div>

        <p class="article__retour reveal">
          <a class="link" href="/actualites">Toutes les actualités</a>
        </p>

      </div>
    </div>
  </div>
</section>

</article>

<?php if ($autres !== []): ?>
<!-- ===================== À LIRE ÉGALEMENT ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-7);">
      <div class="col-lg-8 offset-lg-2">
        <p class="kicker reveal">À lire également</p>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <div class="news">
<?php foreach ($autres as $a): ?>
          <a class="news__i reveal" href="/actualites/<?= View::e((string) $a['slug']) ?>">
            <time class="news__date" datetime="<?= View::e(DateFr::iso((string) $a['publie_le'])) ?>">
              <?= DateFr::longue((string) $a['publie_le']) ?>
            </time>
            <span class="news__t"><?= View::e((string) $a['titre']) ?></span>
            <span class="news__cat"><?= View::e(Actualite::categorie((string) $a['categorie'])) ?></span>
          </a>
<?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
