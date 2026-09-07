<?php
/**
 * Une notice d'archive (brief §4, lot G4).
 *
 * **La page qui donne son adresse à chaque pièce.** Sur un discours, elle
 * réunit ce que le brief énumère : contexte historique, vidéo, transcription
 * intégrale, document — et la fiche signalétique commune à toutes les
 * catégories.
 *
 * $titre, $description et l'aperçu de partage sont posés par le contrôleur :
 * ils dépendent du premier fichier, que le gabarit n'a pas à aller chercher.
 */

use App\Core\DateFr;
use App\Core\Langue;
use App\Core\Site;
use App\Core\View;
use App\Model\Archive;
use App\Model\Media;

$lien = static fn(string $chemin): string => Langue::chemin($chemin);

$quand    = Archive::date($notice);
$motsCles = Archive::motsCles($notice);
$cat      = (string) $notice['categorie'];

/**
 * Données structurées. Le type suit la catégorie : Google ne traite pas une
 * photographie comme un discours, et un `CreativeWork` générique n'aurait
 * apporté aucun des enrichissements qu'on cherche ici (brief §9).
 */
$type = match ($cat) {
    'photographies'   => 'Photograph',
    'videos'          => 'VideoObject',
    'documents', 'correspondances' => 'DigitalDocument',
    'presse'          => 'Article',
    default           => 'CreativeWork',
};

$ld = json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => $type,
    'name'        => (string) $notice['titre'],
    'description' => $description ?? null,
    'inLanguage'  => Langue::code(),
    'url'         => Site::url(Langue::chemin(Archive::chemin($notice))),
    'dateCreated' => ($notice['annee'] ?? null) ? (string) $notice['annee'] : null,
    'contentLocation' => trim((string) ($notice['lieu'] ?? '')) ?: null,
    'creditText'  => trim((string) ($notice['credit'] ?? '')) ?: null,
    'about'       => [
        '@type' => 'Person',
        'name'  => 'Philippe Grégoire Yacé',
    ],
    'isPartOf'    => [
        '@type' => 'Collection',
        'name'  => 'Fonds numérique Philippe Grégoire Yacé',
        'url'   => Site::url(Langue::chemin('/archives')),
    ],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>

<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal" aria-hidden="true"><?= Archive::signe($cat) ?></p></div>
      <div class="col-lg-8">

        <?php /* Fil d'Ariane : trois niveaux de profondeur, un visiteur arrivé
                 par un partage doit pouvoir remonter (brief §9). */ ?>
        <nav class="fil reveal" aria-label="Fil d'Ariane">
          <ol>
            <li><a href="<?= $lien('/archives') ?>">Archives</a></li>
            <li><a href="<?= $lien('/archives/' . $cat) ?>"><?= View::e(Archive::categorie($cat)) ?></a></li>
            <li aria-current="page"><?= View::e((string) $notice['titre']) ?></li>
          </ol>
        </nav>

        <h1 class="t-d1 reveal"><?= View::e((string) $notice['titre']) ?></h1>

        <?php if ($quand !== ''): ?>
          <p class="t-lead page-head__lead reveal"><?= View::e($quand) ?><?php
            $lieu = trim((string) ($notice['lieu'] ?? ''));
            echo $lieu === '' ? '' : ' · ' . View::e($lieu);
          ?></p>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-7 offset-lg-2">

        <?php /* --- La vidéo, chez son hébergeur (décision 2) ------------ */ ?>
        <?php if ($video !== null): ?>
          <div class="arch-video reveal">
            <iframe src="https://www.youtube-nocookie.com/embed/<?= View::e($video) ?>"
                    title="<?= View::e((string) $notice['titre']) ?>"
                    loading="lazy" allowfullscreen
                    referrerpolicy="strict-origin-when-cross-origin"></iframe>
          </div>
        <?php endif; ?>

        <?php
          /*
           * Les fichiers, séparés par famille (lot G5) : une image se regarde,
           * un enregistrement s'écoute, un document se télécharge. Les mêler
           * dans une seule boucle aurait donné trois `if` imbriqués dans un
           * gabarit, là où trois blocs se lisent d'un trait.
           */
          $images    = array_values(array_filter($fichiers, static fn(array $f): bool => Media::est($f, 'image')));
          $sons      = array_values(array_filter($fichiers, static fn(array $f): bool => Media::est($f, 'audio')));
          $documents = array_values(array_filter($fichiers, static fn(array $f): bool => Media::est($f, 'document')));
        ?>

        <?php /* --- Enregistrements ------------------------------------- */ ?>
        <?php if ($sons !== []): ?>
          <div class="arch-bloc reveal">
            <h2 class="t-d3">Écouter</h2>
            <?php foreach ($sons as $s): ?>
              <figure class="arch-son">
                <?php /* `preload="none"` : la page d'un discours peut porter
                         plusieurs pistes, et rien ne doit se télécharger avant
                         que le visiteur ne le demande. */ ?>
                <audio controls preload="none" src="<?= View::e(Media::url((string) $s['fichier'])) ?>">
                  Votre navigateur ne sait pas lire cet enregistrement.
                  <a href="<?= View::e(Media::url((string) $s['fichier'])) ?>">Le télécharger</a>.
                </audio>
                <figcaption>
                  <?= View::e(trim((string) ($s['titre'] ?? '')) ?: 'Enregistrement') ?>
                  <span class="arch-son__meta"><?= View::e(Media::etiquette($s)) ?></span>
                  <?php if (!empty($s['credit'])): ?>
                    <span class="arch-piece__credit"><?= View::e((string) $s['credit']) ?></span>
                  <?php endif; ?>
                </figcaption>
              </figure>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php /* --- Documents originaux --------------------------------- */ ?>
        <?php if ($documents !== []): ?>
          <div class="arch-bloc reveal">
            <h2 class="t-d3">Document original</h2>
            <ul class="arch-docs">
              <?php foreach ($documents as $d): ?>
                <li>
                  <?php /* `download` propose l'enregistrement plutôt que
                           l'ouverture dans le lecteur du navigateur : une pièce
                           d'archive se garde. Le nom déposé n'est pas parlant,
                           on redonne celui de la notice. */ ?>
                  <a href="<?= View::e(Media::url((string) $d['fichier'])) ?>"
                     download="<?= View::e(($notice['slug'] ?? 'archive') . '.' . strtolower(pathinfo((string) $d['fichier'], PATHINFO_EXTENSION))) ?>">
                    <span class="arch-docs__signe" aria-hidden="true"><?= Media::SIGNES_FAMILLE['document'] ?></span>
                    <span class="arch-docs__nom"><?= View::e(trim((string) ($d['titre'] ?? '')) ?: 'Télécharger le document') ?></span>
                    <span class="arch-docs__meta"><?= View::e(Media::etiquette($d)) ?></span>
                  </a>
                  <?php if (!empty($d['credit'])): ?>
                    <span class="arch-piece__credit"><?= View::e((string) $d['credit']) ?></span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php /* --- Les images ------------------------------------------ */ ?>
        <?php if ($images !== []): ?>
          <div class="arch-planche reveal">
            <?php foreach ($images as $f): ?>
              <?php $srcset = Media::srcset($f); ?>
              <figure class="arch-piece">
                <a href="<?= View::e(Media::url((string) $f['fichier'])) ?>"
                   title="Voir le fichier en pleine résolution">
                  <img loading="lazy" decoding="async"
                       src="<?= View::e(Media::urlMoyen((string) $f['fichier'])) ?>"
                       <?= $srcset === '' ? '' : 'srcset="' . View::e($srcset) . '" sizes="(max-width: 991px) 100vw, 58vw"' ?>
                       alt="<?= View::e(Media::alternative($f)) ?>">
                </a>
                <?php $legende = trim((string) ($f['legende'] ?? '')); ?>
                <?php if ($legende !== '' || !empty($f['credit'])): ?>
                  <figcaption>
                    <?= View::e($legende) ?>
                    <?php if (!empty($f['credit'])): ?>
                      <span class="arch-piece__credit"><?= View::e((string) $f['credit']) ?></span>
                    <?php endif; ?>
                  </figcaption>
                <?php endif; ?>
              </figure>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php /* --- Contexte historique : propre aux discours ------------ */ ?>
        <?php $contexte = trim((string) ($notice['contexte'] ?? '')); ?>
        <?php if ($contexte !== ''): ?>
          <div class="arch-bloc reveal">
            <h2 class="t-d3">Contexte historique</h2>
            <?= View::paragraphes($contexte, 't-body') ?>
          </div>
        <?php endif; ?>

        <?php /* --- Description ------------------------------------------ */ ?>
        <?php $texte = trim((string) ($notice['description'] ?? '')); ?>
        <?php if ($texte !== ''): ?>
          <div class="arch-bloc reveal">
            <h2 class="t-d3">Description</h2>
            <?= View::paragraphes($texte, 't-body') ?>
          </div>
        <?php endif; ?>

        <?php /* --- Transcription intégrale ------------------------------ */ ?>
        <?php $transcription = trim((string) ($notice['transcription'] ?? '')); ?>
        <?php if ($transcription !== ''): ?>
          <div class="arch-bloc reveal">
            <h2 class="t-d3">Transcription intégrale</h2>
            <div class="arch-transcription"><?= View::paragraphes($transcription, 't-body') ?></div>
          </div>
        <?php endif; ?>

      </div>

      <?php /* --- La fiche signalétique ------------------------------- */ ?>
      <aside class="col-lg-3">
        <div class="arch-fiche reveal">
          <h2 class="kicker kicker--bare">Fiche</h2>
          <dl>
            <div><dt>Catégorie</dt><dd><a href="<?= $lien('/archives/' . $cat) ?>"><?= View::e(Archive::categorie($cat)) ?></a></dd></div>
            <?php if ($quand !== ''): ?><div><dt>Date</dt><dd><?= View::e($quand) ?></dd></div><?php endif; ?>
            <?php foreach ([
              'lieu'      => 'Lieu',
              'personnes' => 'Personnes présentes',
              'source'    => 'Source',
              'credit'    => 'Crédit',
            ] as $champ => $libelle): ?>
              <?php $valeur = trim((string) ($notice[$champ] ?? '')); ?>
              <?php if ($valeur !== ''): ?>
                <div><dt><?= $libelle ?></dt><dd><?= View::e($valeur) ?></dd></div>
              <?php endif; ?>
            <?php endforeach; ?>
          </dl>

          <?php if ($motsCles !== []): ?>
            <div class="arch-fiche__mots">
              <?php foreach ($motsCles as $mot): ?>
                <a class="chip chip--fin" href="<?= $lien('/archives') ?>?q=<?= urlencode($mot) ?>"><?= View::e($mot) ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php /* Le permalien, en clair et copiable. Un fonds patrimonial se
                   cite : l'adresse est ce qu'on recopie dans une note de bas
                   de page, un dossier de presse, un QR code (décision 3). */ ?>
          <div class="arch-fiche__citer">
            <p class="arch-fiche__lbl">Citer cette archive</p>
            <p class="arch-fiche__url"><?= View::e(Site::url(Langue::chemin(Archive::chemin($notice)))) ?></p>
          </div>
        </div>
      </aside>
    </div>

    <?php /* --- Les voisines : ne pas laisser la page en cul-de-sac ---- */ ?>
    <?php if ($voisines !== []): ?>
      <div class="row" style="margin-top: var(--sp-9);">
        <div class="col-lg-10 offset-lg-2">
          <div class="rule reveal" style="margin-bottom: var(--sp-6);"></div>
          <p class="kicker reveal">Dans la même catégorie</p>
          <ul class="arch-voisines">
            <?php foreach (array_slice($voisines, 0, 4) as $v): ?>
              <li class="reveal">
                <a href="<?= $lien(Archive::chemin($v)) ?>">
                  <?php $c = $v['couverture'] ?? null; ?>
                  <?php if ($c !== null): ?>
                    <img loading="lazy" decoding="async"
                         src="<?= View::e(Media::urlVignette((string) $c['fichier'])) ?>"
                         alt="<?= View::e(Media::alternative($c)) ?>">
                  <?php else: ?>
                    <span class="arch-voisines__signe" aria-hidden="true"><?= Archive::signe((string) $v['categorie']) ?></span>
                  <?php endif; ?>
                  <span><?= View::e((string) $v['titre']) ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
