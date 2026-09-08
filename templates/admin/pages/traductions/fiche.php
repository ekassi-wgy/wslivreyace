<?php
/**
 * Traduire un contenu : le français à gauche, la langue cible à droite (G11).
 *
 * **Le français est affiché et non modifiable.** Corriger l'original se fait
 * sur sa propre fiche, et le mélange des deux gestes ferait qu'une faute de
 * frappe corrigée ici partirait sans que personne ne l'ait relue côté
 * rédaction.
 *
 * **Un champ vide n'est pas un trou.** `Traduction::poser()` efface la
 * traduction plutôt que d'enregistrer une chaîne vide, et la page publique
 * retombe alors sur le français. C'est ce qui permet d'ouvrir une langue
 * rubrique par rubrique au lieu d'attendre que tout soit fait — et c'est dit
 * en toutes lettres sous le formulaire, parce que rien ne le laisse deviner.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">
      <a href="<?= Admin::url($retour) ?>">Traductions</a>
    </span>
    <h1><?= View::e($def['titre']) ?></h1>
    <?php $intitule = trim((string) ($source[array_key_first($def['champs'])] ?? '')); ?>
    <?php if ($intitule !== ''): ?>
      <p><?= View::e($intitule) ?></p>
    <?php endif; ?>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url($retour) ?>">Retour</a>
</div>

<form method="post" action="<?= Admin::url('/traductions/' . $entite . '/' . $id) ?>">
  <?= Csrf::champ() ?>

<?php foreach ($langues as $code => $infos): ?>
  <div class="card mb-4">
    <div class="card-body">
      <h2 class="h5 mb-4">
        <?= View::e($infos['nom']) ?>
        <span class="text-muted small">(<?= View::e($code) ?>)</span>
      </h2>

<?php foreach ($def['champs'] as $champ => $reglage): ?>
      <?php
        $original = trim((string) ($source[$champ] ?? ''));
        $traduit  = (string) ($cibles[$code][$champ] ?? '');
        $nom      = 'traduction[' . $code . '][' . $champ . ']';
        $idChamp  = 'tr_' . $code . '_' . $champ;
      ?>
      <div class="row mb-4">
        <div class="col-lg-6">
          <label class="form-label text-muted" for="<?= View::e($idChamp) ?>">
            <?= View::e($reglage['libelle']) ?> — français
          </label>
          <?php /* Le français en lecture seule : voir l'en-tête du gabarit. */ ?>
          <?php if ($original === ''): ?>
            <p class="form-control-plaintext text-muted fst-italic">Ce champ est vide.</p>
          <?php elseif ($reglage['zone']): ?>
            <div class="pgy-source-longue"><?= nl2br(View::e($original)) ?></div>
          <?php else: ?>
            <p class="form-control-plaintext"><?= View::e($original) ?></p>
          <?php endif; ?>
        </div>
        <div class="col-lg-6">
          <label class="form-label" for="<?= View::e($idChamp) ?>">
            <?= View::e($reglage['libelle']) ?> — <?= View::e($infos['nom']) ?>
          </label>
<?php if ($reglage['zone']): ?>
          <textarea class="form-control" rows="8"
                    id="<?= View::e($idChamp) ?>"
                    name="<?= View::e($nom) ?>"><?= View::e($traduit) ?></textarea>
<?php else: ?>
          <input class="form-control" type="text"
                 id="<?= View::e($idChamp) ?>"
                 name="<?= View::e($nom) ?>"
                 value="<?= View::e($traduit) ?>">
<?php endif; ?>
        </div>
      </div>
<?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>

  <div class="alert alert-secondary" role="note">
    <i class="mdi mdi-lightbulb-outline me-1" aria-hidden="true"></i>
    <strong>Un champ laissé vide n'est pas un trou.</strong> La page affiche
    alors le texte français à sa place, et reste donc lisible. Vider un champ
    déjà traduit efface la traduction — c'est ainsi qu'on revient en arrière.
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">Enregistrer les traductions</button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url($retour) ?>">Annuler</a>
  </div>
</form>
