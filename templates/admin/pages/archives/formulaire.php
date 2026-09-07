<?php
/**
 * Fiche d'une notice d'archive (lot G4).
 *
 * Une notice porte **plusieurs** fichiers : le sélecteur est donc une planche
 * à cases et non le choix unique des actualités. L'ordre retenu est celui de
 * la médiathèque — c'est l'éditeur qui l'y règle — et le premier fichier coché
 * fait la vignette et l'image de partage de la notice.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Archive;
use App\Model\Media;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id = $edition ? (int) $ligne['id'] : null;

/*
 * Les fichiers cochés. La saisie renvoyée l'emporte sur la base : après une
 * erreur de validation, l'éditeur doit retrouver ce qu'il venait de choisir et
 * non ce qui était enregistré avant.
 *
 * **C'est `$erreurs` qui distingue les deux cas, et rien d'autre.** Une
 * première version testait la présence du titre dans `$valeurs` — mais à
 * l'ouverture d'une fiche, `$valeurs` EST la ligne en base et porte donc un
 * titre. La liste ressortait vide, aucune case n'était cochée, et le premier
 * enregistrement détachait tous les fichiers de la notice sans rien dire.
 *
 * `formulaire()` n'est rappelé avec des erreurs que depuis `ecrire()`, et un
 * enregistrement valide ne réaffiche jamais le formulaire : `$erreurs === []`
 * signifie donc exactement « fiche ouverte, pas encore soumise ».
 */
$choisis = $erreurs === []
    ? ($edition ? Archive::idsFichiers((int) $id) : [])
    : array_map('intval', (array) ($valeurs['fichiers'] ?? []));

$action = $edition ? Admin::url('/archives/' . $id) : Admin::url('/archives');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1><?= View::e($titre) ?></h1>
    <p>Une pièce du fonds : sa notice, ses fichiers, sa provenance.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/archives') ?>">Retour à la liste</a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">La pièce</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', [
          'aide' => "Ce qui s'affichera en tête de page et dans les partages. Ex. : « Discours de clôture de la 5ᵉ législature ».",
        ]); ?>

        <?php champ_choix($valeurs, $erreurs, 'categorie', 'Catégorie', Archive::CATEGORIES, [
          'defaut' => 'photographies',
          'aide'   => "La catégorie fait partie de l'adresse de la pièce : elle ne se change pas sans casser les liens déjà partagés.",
        ]); ?>

        <?php champ_zone($valeurs, $erreurs, 'description', 'Description', [
          'aide' => 'Ce que montre ou dit la pièce. Une ligne vide sépare deux paragraphes.',
        ]); ?>

        <div class="row">
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'date_texte', 'Date affichée', [
            'aide' => "Telle qu'elle se lit : « 12 mars 1980 », « vers 1965 ».",
          ]); ?></div>
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'annee', 'Année de classement', [
            'type' => 'number',
            'aide' => "Sert au tri et au filtre par année. Laissez vide si la date est inconnue.",
          ]); ?></div>
        </div>

        <div class="row">
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'lieu', 'Lieu'); ?></div>
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'personnes', 'Personnes présentes', [
            'aide' => 'Séparées par des virgules.',
          ]); ?></div>
        </div>

        <?php champ_texte($valeurs, $erreurs, 'mots_cles', 'Mots-clés', [
          'aide' => 'Séparés par des virgules. Ils alimentent la recherche du fonds.',
        ]); ?>
      </div></div>

      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Discours et enregistrements</h4>
        <p class="text-muted small">
          Ces trois champs ne servent qu'aux pièces qui en ont : laissez-les vides ailleurs.
        </p>

        <?php champ_zone($valeurs, $erreurs, 'contexte', 'Contexte historique', [
          'aide' => "Les circonstances de la pièce : la séance, l'événement, l'enjeu du moment.",
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'video_url', 'Lien de la vidéo', [
          'aide' => "Adresse YouTube. Les vidéos ne sont pas hébergées sur le site : elles restent chez l'hébergeur, seule la notice vit ici.",
        ]); ?>

        <?php champ_zone($valeurs, $erreurs, 'transcription', 'Transcription intégrale', [
          'aide' => "Le texte complet. C'est lui qui rendra le discours trouvable sur Google, mot par mot.",
        ]); ?>
      </div></div>

      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Fichiers</h4>
        <p class="text-muted small">
          Cochez les images de la médiathèque qui composent cette pièce.
          <strong>La première cochée fait la vignette</strong> et l'image de partage.
          L'ordre suit celui de la médiathèque.
        </p>

        <?php
          /*
           * Les fichiers déjà rattachés sont rendus À PART et toujours, même
           * s'ils sont sortis du lot que le sélecteur propose.
           *
           * C'est la précaution qui compte ici : le formulaire ne poste que
           * les cases présentes dans la page. Un fichier attaché il y a six
           * mois, absent des deux cents derniers dépôts, serait détaché en
           * silence au premier enregistrement — et personne ne s'en
           * apercevrait avant de regarder la page publique.
           */
          $rattaches = Media::parIds($choisis);
          $dejaVus = array_map(static fn(array $m): int => (int) $m['id'], $rattaches);
          $pool = array_values(array_filter(
              $medias,
              static fn(array $m): bool => !in_array((int) $m['id'], $dejaVus, true)
          ));
        ?>

        <?php if ($rattaches !== []): ?>
          <p class="pgy-sous mb-2">Fichiers de cette notice — décochez pour retirer</p>
          <div class="pgy-planche-choix mb-4">
            <?php foreach ($rattaches as $m): ?>
              <label class="pgy-choix is-choisi">
                <input type="checkbox" name="fichiers[]" value="<?= (int) $m['id'] ?>" checked>
                <img src="<?= View::e(Media::urlVignette((string) $m['fichier'])) ?>"
                     alt="<?= View::e(Media::alternative($m)) ?>" loading="lazy">
                <span><?= View::e(mb_strimwidth((string) ($m['titre'] ?? $m['fichier']), 0, 28, '…')) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($pool === []): ?>
          <?php if ($rattaches === []): ?>
            <div class="pgy-vide">
              <p class="mb-1 fw-semibold">La médiathèque est vide</p>
              <p class="mb-3 small">Déposez d'abord les fichiers, puis revenez les rattacher.</p>
              <a class="btn btn-primary" href="<?= Admin::url('/medias') ?>">Aller à la médiathèque</a>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <p class="pgy-sous mb-2">Ajouter des fichiers — les <?= count($pool) ?> derniers dépôts</p>

          <?php /* Le filtre est côté navigateur : le formulaire est en cours de
                   saisie, et une recherche qui rechargerait la page ferait
                   perdre tout ce qui n'est pas encore enregistré. */ ?>
          <div class="mb-2">
            <label class="visually-hidden" for="filtre-fichiers">Filtrer les fichiers</label>
            <input class="form-control" type="search" id="filtre-fichiers"
                   data-filtre-choix="#planche-fichiers"
                   placeholder="Filtrer par titre ou nom de fichier…" autocomplete="off">
          </div>

          <div class="pgy-planche-choix" id="planche-fichiers">
            <?php foreach ($pool as $m): ?>
              <?php $etiquette = (string) ($m['titre'] ?? '') . ' ' . (string) $m['fichier']; ?>
              <label class="pgy-choix" data-etiquette="<?= View::e(mb_strtolower($etiquette)) ?>">
                <input type="checkbox" name="fichiers[]" value="<?= (int) $m['id'] ?>">
                <img src="<?= View::e(Media::urlVignette((string) $m['fichier'])) ?>"
                     alt="<?= View::e(Media::alternative($m)) ?>" loading="lazy">
                <span><?= View::e(mb_strimwidth((string) ($m['titre'] ?? $m['fichier']), 0, 28, '…')) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <p class="form-text" data-filtre-vide hidden>Aucun fichier ne correspond.
            Les dépôts plus anciens se retrouvent depuis la <a href="<?= Admin::url('/medias') ?>">médiathèque</a>.</p>
        <?php endif; ?>
      </div></div>
    </div>

    <div class="col-lg-4">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Provenance</h4>
        <p class="text-muted small">
          Obligatoire pour publier : une pièce sans provenance expose l'éditeur (CDC §6).
        </p>
        <?php champ_texte($valeurs, $erreurs, 'source', 'Source', [
          'aide' => 'Le fonds, l\'institution ou la personne qui détient la pièce.',
        ]); ?>
        <?php champ_texte($valeurs, $erreurs, 'credit', 'Crédit', [
          'aide' => 'Le photographe ou le détenteur des droits, tel qu\'il doit être cité.',
        ]); ?>
      </div></div>

      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Archive::STATUTS, ['defaut' => 'brouillon']); ?>
        <?php if ($edition): ?>
          <p class="text-muted small mb-0">
            Adresse publique :<br>
            <code><?= View::e('/archives/' . ($valeurs['categorie'] ?? $ligne['categorie']) . '/' . ($ligne['slug'] ?? '')) ?></code>
          </p>
        <?php endif; ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : "Créer l'archive" ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/archives') ?>">Annuler</a>
    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>
