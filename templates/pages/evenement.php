<?php
/**
 * Événement — la fiche, servie par son slug (CDC §4.10).
 *
 * Même patron que la fiche d'actualité : titre, date, illustration créditée,
 * corps en texte brut échappé, balisage structuré fabriqué par `json_encode`.
 * Ce qui change tient à la nature de l'objet — un rendez-vous a un lieu, une
 * durée, et il peut être annulé.
 */

use App\Core\DateFr;
use App\Core\Site;
use App\Core\View;
use App\Model\Media;

$annule    = ($evenement['statut'] ?? '') === 'annule';
$debut     = (string) $evenement['debut_le'];
$fin       = $evenement['fin_le'] ?? null;
$lieu      = trim((string) ($evenement['lieu'] ?? ''));
$ville     = trim((string) ($evenement['ville'] ?? ''));
$adresse   = implode(', ', array_filter([$lieu, $ville]));
$inscrire  = trim((string) ($evenement['inscription_url'] ?? ''));

$titre = $evenement['titre'] . ' — Philippe Grégoire Yacé : une destinée';

$description = trim(mb_strimwidth(
    preg_replace('/\s+/u', ' ', (string) ($evenement['description'] ?? '')) ?? '',
    0,
    200,
    '…'
));

if ($description === '') {
    // À défaut de description saisie, l'essentiel factuel : quoi, où, quand.
    $description = trim(sprintf(
        '%s — %s%s',
        (string) $evenement['titre'],
        $adresse === '' ? '' : $adresse . ', ',
        DateFr::longueTexte($debut)
    ));
}

if ($media !== null) {
    $ogImage  = Site::url(Media::url((string) $evenement['image']));
    $ogAlt    = Media::alternative($media);
    $ogTaille = ($media['largeur'] ?? 0) && ($media['hauteur'] ?? 0)
        ? [(int) $media['largeur'], (int) $media['hauteur']]
        : null;
}

$ogType = 'article';

/**
 * JSON-LD. `JSON_HEX_TAG` pour la même raison que sur la fiche d'actualité :
 * un titre contenant `</script>` fermerait la balise.
 *
 * `eventStatus` n'est pas décoratif : c'est par lui qu'un moteur ou un agenda
 * tiers apprend qu'un rendez-vous annoncé n'aura pas lieu.
 */
$donneesLd = array_filter([
    '@context'         => 'https://schema.org',
    '@type'            => 'Event',
    'name'             => (string) $evenement['titre'],
    'startDate'        => DateFr::isoHeure($debut),
    'endDate'          => DateFr::isoHeure(is_string($fin) ? $fin : null),
    'eventStatus'      => $annule
        ? 'https://schema.org/EventCancelled'
        : 'https://schema.org/EventScheduled',
    'inLanguage'       => 'fr',
    'url'              => Site::url('/evenements/' . $evenement['slug']),
    'description'      => $description,
    'image'            => $media !== null ? Site::url(Media::url((string) $evenement['image'])) : '',
    'location'         => $adresse === '' ? '' : array_filter([
        '@type'   => 'Place',
        'name'    => $lieu !== '' ? $lieu : $ville,
        'address' => $ville,
    ]),
    'about'            => [
        '@type' => 'Book',
        'name'  => 'Philippe Grégoire Yacé : une destinée (1920-1998)',
    ],
], static fn($v) => $v !== '' && $v !== null && $v !== []);

$ld = json_encode(
    $donneesLd,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_PRETTY_PRINT
) ?: '';
?>

<article>

<!-- ===================== EN-TÊTE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2">
        <p class="section-num reveal"><?= View::e(DateFr::annee($debut)) ?></p>
      </div>
      <div class="col-lg-8">
        <p class="kicker reveal">
          <?= $annule ? 'Rendez-vous annulé' : ($passe ? 'Rendez-vous passé' : 'À venir') ?>
        </p>
        <h1 class="t-d1 reveal"><?= View::e((string) $evenement['titre']) ?></h1>

        <p class="article__meta reveal">
          <time datetime="<?= View::e(DateFr::isoHeure($debut)) ?>">
            <?= DateFr::intervalle($debut, is_string($fin) ? $fin : null) ?>
          </time>
          <?php /* Le lieu se lit, il ne s'étiquette pas : une adresse en
                   capitales espacées — le traitement que porte l'organe de
                   presse sur une actualité — se déchiffre au lieu de se
                   lire, et c'est une information qu'on recopie. */ ?>
          <?php if ($adresse !== ''): ?>
            <span class="article__lieu"><?= View::e($adresse) ?></span>
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

        <?php if ($annule): ?>
          <?php /* En tête du corps et non en pied : quelqu'un qui ouvre cette
                   page pour vérifier une adresse doit apprendre l'annulation
                   avant de lire l'adresse. */ ?>
          <p class="avis avis--refus reveal" role="status">
            <strong>Ce rendez-vous est annulé.</strong> L'annonce reste en ligne
            pour celles et ceux qui l'avaient noté.
          </p>
        <?php endif; ?>

        <?php if ($media !== null): ?>
          <figure class="article__figure reveal">
            <span class="frame">
              <img loading="lazy" decoding="async"
                   src="<?= View::e(Media::url((string) $evenement['image'])) ?>"
                   <?php if (($media['largeur'] ?? 0) && ($media['hauteur'] ?? 0)): ?>
                   width="<?= (int) $media['largeur'] ?>" height="<?= (int) $media['hauteur'] ?>"
                   <?php endif; ?>
                   alt="<?= View::e(Media::alternative($media)) ?>">
            </span>
            <?php if (trim((string) ($media['credit'] ?? '')) !== ''): ?>
              <figcaption class="article__legende">
                <span class="article__credit">© <?= View::e(trim((string) $media['credit'])) ?></span>
              </figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>

        <div class="article">
          <?php /* Pas de récapitulatif « quand / où » : l'en-tête vient de le
                   dire, à trois centimètres au-dessus. Un encadré qui répète
                   ce qu'on vient de lire n'ajoute que du bruit. */ ?>
          <div class="reveal"><?= View::paragraphes((string) ($evenement['description'] ?? ''), 't-body') ?></div>

          <?php /* Le lien d'inscription ne survit ni à l'annulation ni à la
                   date : proposer de s'inscrire à un rendez-vous qui n'aura
                   pas lieu, ou qui a eu lieu, est pire que ne rien proposer. */ ?>
          <?php if ($inscrire !== '' && !$annule && !$passe): ?>
            <p class="article__source reveal">
              <a class="btn-pgy" href="<?= View::e($inscrire) ?>" rel="noopener">
                S'inscrire <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
              </a>
            </p>
          <?php elseif ($inscrire !== '' && $passe && !$annule): ?>
            <p class="article__source reveal t-small">
              Les inscriptions sont closes&nbsp;: ce rendez-vous a eu lieu.
            </p>
          <?php endif; ?>
        </div>

        <p class="article__retour reveal">
          <a class="link" href="/evenements">Tous les événements</a>
        </p>

      </div>
    </div>
  </div>
</section>

</article>
