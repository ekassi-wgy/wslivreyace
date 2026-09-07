<?php
/**
 * Fiche d'une période de la biographie (lot G10).
 *
 * Même mécanique que la fiche d'archive et celle d'un sujet d'Héritage : la
 * période porte plusieurs images, le sélecteur est donc une planche à cases.
 * La première cochée fait la vignette de l'index et l'image de partage.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Media;
use App\Model\Periode;
use App\Model\Repere;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id = $edition ? (int) $ligne['id'] : null;

/*
 * Les fichiers cochés. La saisie renvoyée l'emporte sur la base : après une
 * erreur de validation, l'éditeur doit retrouver ce qu'il venait de choisir et
 * non ce qui était enregistré avant. **C'est `$erreurs` qui distingue les deux
 * cas** — voir la fiche d'Héritage, où l'explication est écrite en entier.
 */
$choisis = $erreurs === []
    ? ($edition ? Periode::idsImages((int) $id) : [])
    : array_map('intval', (array) ($valeurs['fichiers'] ?? []));

$action = $edition ? Admin::url('/periodes/' . $id) : Admin::url('/periodes');
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1><?= View::e($titre) ?></h1>
    <p>Une période du parcours : ses années, son récit, ses images, sa source.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/periodes') ?>">Retour à la liste</a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-8">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">La période</h4>

        <?php champ_texte($valeurs, $erreurs, 'titre', 'Titre', [
          'requis' => true,
          'aide'   => "Ex. : « Enfance et formation », « Vingt et un ans au perchoir ».",
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'sous_titre', 'Sous-titre', [
          'aide' => "Une ligne, affichée sous le titre et sur la page Biographie.",
        ]); ?>

        <div class="row">
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'debut', 'Année de début', [
            'type' => 'number', 'attributs' => 'min="1800" max="2100" step="1" placeholder="1920"',
          ]); ?></div>
          <div class="col-md-6"><?php champ_texte($valeurs, $erreurs, 'fin', 'Année de fin', [
            'type' => 'number', 'attributs' => 'min="1800" max="2100" step="1" placeholder="1944"',
          ]); ?></div>
        </div>

        <p class="text-muted small">
          <strong>Les deux bornes sont incluses</strong>, et elles font tout le reste :
          la période affiche les repères de la frise et les pièces du fonds dont la date
          y tombe, sans qu'on ait à les rattacher un par un. Elles sont exigées pour
          publier, et deux périodes publiées ne peuvent pas se chevaucher — une année
          appartient à un seul récit.
        </p>

        <?php champ_zone($valeurs, $erreurs, 'recit', 'Récit', [
          'lignes' => 14,
          'aide'   => 'Une ligne vide sépare deux paragraphes.',
        ]); ?>
      </div></div>

      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Images</h4>
        <p class="text-muted small">
          Cochez les images de la médiathèque qui illustrent cette période.
          <strong>La première cochée fait la vignette</strong> de la page Biographie et
          l'image de partage. L'ordre suit celui de la médiathèque.
        </p>

        <?php
          /*
           * Les fichiers déjà rattachés sont rendus À PART et toujours, même
           * s'ils sont sortis du lot que le sélecteur propose : le formulaire
           * ne poste que les cases présentes dans la page, et une image
           * rattachée il y a six mois serait détachée en silence au premier
           * enregistrement.
           */
          $rattaches = Media::parIds($choisis);
          $dejaVus = array_map(static fn(array $m): int => (int) $m['id'], $rattaches);
          $pool = array_values(array_filter(
              $medias,
              static fn(array $m): bool => !in_array((int) $m['id'], $dejaVus, true)
          ));
        ?>

        <?php if ($rattaches !== []): ?>
          <p class="pgy-sous mb-2">Images de cette période — décochez pour retirer</p>
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
          La source est obligatoire pour publier. C'est ici qu'elle compte le plus :
          la biographie est le seul endroit du site où l'on écrit la vie d'une personne
          réelle en continu, et une phrase non sourcée y passe pour un fait établi
          (CDC §6).
        </p>
        <?php champ_texte($valeurs, $erreurs, 'source', 'Source', [
          'aide' => "L'ouvrage et la page, le fonds, l'institution — telle qu'elle doit être citée.",
        ]); ?>
      </div></div>

      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Publication</h4>
        <?php champ_choix($valeurs, $erreurs, 'statut', 'Statut', Periode::STATUTS, ['defaut' => 'brouillon']); ?>
        <?php if ($edition): ?>
          <p class="text-muted small mb-0">
            Adresse publique :<br>
            <code><?= View::e('/biographie/' . ($ligne['slug'] ?? '')) ?></code>
          </p>
        <?php endif; ?>
      </div></div>

      <?php
      /*
       * Ce que la période porte déjà, d'après ses seules bornes. Affiché sur la
       * fiche parce que c'est la vérification qu'un éditeur veut faire au moment
       * où il pose ses dates : un découpage se juge à ce qu'il recueille.
       */
      $debutSaisi = champ_valeur($valeurs, 'debut');
      $finSaisi   = champ_valeur($valeurs, 'fin');
      ?>
      <?php if ($debutSaisi !== '' && $finSaisi !== '' && (int) $finSaisi >= (int) $debutSaisi): ?>
        <?php $jalons = Repere::entreAnnees((int) $debutSaisi, (int) $finSaisi); ?>
        <div class="card card-rounded mt-4"><div class="card-body">
          <h4 class="card-title card-title-dash">Ce que ces années portent</h4>
          <?php if ($jalons === []): ?>
            <p class="text-muted small mb-0">
              Aucun repère publié entre <?= (int) $debutSaisi ?> et <?= (int) $finSaisi ?>.
              La page de la période affichera son récit sans frise.
            </p>
          <?php else: ?>
            <ul class="list-unstyled mb-0 small">
              <?php foreach ($jalons as $j): ?>
                <li class="mb-1">
                  <span class="text-muted"><?= View::e((string) $j['annee']) ?></span>
                  <?= View::e((string) $j['titre']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
            <p class="form-text mb-0">
              Repères publiés dont l'année de classement tombe dans ces bornes. Rien n'est
              rattaché à la main : changez une borne, la liste suit.
            </p>
          <?php endif; ?>
        </div></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : 'Créer la période' ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/periodes') ?>">Annuler</a>
    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Fiche n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>
