<?php
/**
 * Fiche d'un sujet d'Héritage (lot G7).
 *
 * Même mécanique que la fiche d'archive : un sujet porte plusieurs images, le
 * sélecteur est donc une planche à cases. La première cochée fait la vignette
 * de l'index et l'image de partage.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Heritage;
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
    ? ($edition ? Heritage::idsImages((int) $id) : [])
    : array_map('intval', (array) ($valeurs['fichiers'] ?? []));

$action = $edition ? Admin::url('/heritage/' . $id) : Admin::url('/heritage');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1><?= View::e($titre) ?></h1>
    <p>Un sujet de la rubrique Héritage : son texte, ses images, sa source.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/heritage') ?>">Retour à la liste</a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Le sujet</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', [
          'aide' => "Ex. : « Pont Philippe Grégoire Yacé », « Buste de Marcory ».",
        ]); ?>

        <?php champ_choix($valeurs, $erreurs, 'rubrique', 'Rubrique', Heritage::RUBRIQUES, [
          'defaut' => 'lieux',
          'aide'   => "Elle regroupe les sujets sur l'index. Elle ne fait pas partie de l'adresse : la changer ne casse aucun lien.",
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'sous_titre', 'Sous-titre', [
          'aide' => "Une ligne, affichée sous le titre et sur l'index.",
        ]); ?>

        <?php champ_zone($valeurs, $erreurs, 'description', 'Texte', [
          'aide' => 'Une ligne vide sépare deux paragraphes.',
        ]); ?>

        <div class="row">
          <div class="col-md-4"><?php champ_texte($valeurs, $erreurs, 'date_texte', 'Date affichée', [
            'aide' => "« inauguré en 2003 », « 1991 ».",
          ]); ?></div>
          <div class="col-md-4"><?php champ_texte($valeurs, $erreurs, 'annee', 'Année', [
            'type' => 'number', 'aide' => 'Sert au classement.',
          ]); ?></div>
          <div class="col-md-4"><?php champ_texte($valeurs, $erreurs, 'ordre', 'Ordre', [
            'type' => 'number',
            'aide' => "Rang dans la rubrique. 0 en premier.",
          ]); ?></div>
        </div>

        <?php champ_texte($valeurs, $erreurs, 'lieu', 'Lieu', [
          'aide' => "Pour un lieu de mémoire : la commune, le quartier.",
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'video_url', 'Lien de la vidéo', [
          'aide' => "Adresse YouTube — la chanson, une captation de commémoration. Les vidéos ne sont pas hébergées ici.",
        ]); ?>
      </div></div>

      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Images</h4>
        <p class="text-muted small">
          Cochez les images de la médiathèque qui illustrent ce sujet.
          <strong>La première cochée fait la vignette</strong> de l'index et l'image de partage.
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
          <p class="pgy-sous mb-2">Images de ce sujet — décochez pour retirer</p>
          <div class="pgy-planche-choix mb-4">
            <?php foreach ($rattaches as $m): ?>
              <label class="pgy-choix is-choisi">
                <input type="checkbox" name="fichiers[]" value="<?= (int) $m['id'] ?>" checked>
                <?php if (Media::aVignette($m)): ?>
                  <img src="<?= View::e(Media::urlVignette((string) $m['fichier'])) ?>"
                       alt="<?= View::e(Media::alternative($m)) ?>" loading="lazy">
                <?php else: ?>
                  <span class="pgy-choix__signe" aria-hidden="true"><?= Media::SIGNES_FAMILLE[Media::famille($m)] ?></span>
                <?php endif; ?>
                <span><?= View::e(mb_strimwidth((string) ($m['titre'] ?? $m['fichier']), 0, 28, '…')) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($pool === []): ?>
          <?php if ($rattaches === []): ?>
            <div class="pgy-vide">
              <p class="mb-1 fw-semibold">La médiathèque est vide</p>
              <p class="mb-3 small">Déposez d'abord les images, puis revenez les rattacher.</p>
              <a class="btn btn-primary" href="<?= Admin::url('/medias') ?>">Aller à la médiathèque</a>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <p class="pgy-sous mb-2">Ajouter des images — les <?= count($pool) ?> derniers dépôts</p>

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
                <?php if (Media::aVignette($m)): ?>
                  <img src="<?= View::e(Media::urlVignette((string) $m['fichier'])) ?>"
                       alt="<?= View::e(Media::alternative($m)) ?>" loading="lazy">
                <?php else: ?>
                  <span class="pgy-choix__signe" aria-hidden="true"><?= Media::SIGNES_FAMILLE[Media::famille($m)] ?></span>
                <?php endif; ?>
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
          La source est obligatoire pour publier. Une liste de décorations se recopie
          de proche en proche avec ses erreurs : publier la nôtre sans référence
          ajouterait une source de plus au malentendu (CDC §6).
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
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Heritage::STATUTS, ['defaut' => 'brouillon']); ?>
        <?php if ($edition): ?>
          <p class="text-muted small mb-0">
            Adresse publique :<br>
            <code><?= View::e('/heritage/' . ($ligne['slug'] ?? '')) ?></code>
          </p>
        <?php endif; ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : "Créer le sujet" ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/heritage') ?>">Annuler</a>
    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>
