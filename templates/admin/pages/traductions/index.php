<?php
/**
 * L'index des traductions (lot G11).
 *
 * Il dit deux choses et pas une de plus : ce qui se traduit, et combien de
 * champs sont déjà posés. Pas de barre de progression en pourcentage — un
 * champ vide peut être vide à bon droit (un sous-titre qui n'existe pas), et
 * un pourcentage qui n'atteint jamais cent est un reproche permanent.
 */

use App\Core\Admin;
use App\Core\Langue;
use App\Core\View;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Traductions</h1>
    <p>Ce que le site dira dans une autre langue que le français</p>
  </div>
</div>

<?php /* L'état de la version : elle se prépare fermée, et c'est voulu. */ ?>
<div class="alert alert-info" role="note">
  <i class="mdi mdi-information-outline me-1" aria-hidden="true"></i>
  <?php $ouvertes = array_intersect_key(Langue::ouvertes(), $langues); ?>
  <?php if ($ouvertes === []): ?>
    <strong>Aucune de ces langues n'est ouverte au public.</strong>
    Les adresses en <code>/en/</code> répondent « page introuvable », et c'est
    délibéré : servir des pages à moitié françaises apprendrait aux moteurs de
    recherche que le site ment sur son contenu, et c'est long à défaire.
    Traduisez ici à votre rythme ; l'ouverture est un réglage à part, et rien
    de ce que vous saisissez ne paraît avant.
  <?php else: ?>
    <strong>La version <?= View::e(implode(', ', array_column($ouvertes, 'nom'))) ?>
    est ouverte au public.</strong>
    Ce que vous enregistrez ici paraît en ligne. Un champ laissé vide n'est pas
    un trou : la page affiche alors le français.
  <?php endif; ?>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Rubrique</th>
                <th class="text-end">Contenus</th>
<?php foreach ($langues as $code => $infos): ?>
                <th class="text-end"><?= View::e($infos['nom']) ?></th>
<?php endforeach; ?>
                <th></th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($rubriques as $r): ?>
              <tr>
                <td><strong><?= View::e($r['titre']) ?></strong></td>
                <td class="text-end text-muted"><?= (int) $r['total'] ?></td>
<?php foreach (array_keys($langues) as $code): ?>
                <td class="text-end">
                  <?php $n = $r['traduites'][$code] ?? 0; ?>
                  <?php if ($n === 0): ?>
                    <span class="text-muted">—</span>
                  <?php else: ?>
                    <span class="badge bg-success"><?= (int) $n ?> champ<?= $n > 1 ? 's' : '' ?></span>
                  <?php endif; ?>
                </td>
<?php endforeach; ?>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary"
                     href="<?= Admin::url('/traductions/' . $r['entite']) ?>">Ouvrir</a>
                </td>
              </tr>
<?php endforeach; ?>

              <?php /* Les paramètres n'ont pas de lignes : une seule fiche. */ ?>
              <tr>
                <td>
                  <strong>Le livre et son auteur</strong>
                  <span class="d-block text-muted small">Titre, préface, biographie de l'auteur</span>
                </td>
                <td class="text-end text-muted">1</td>
<?php foreach (array_keys($langues) as $code): ?>
                <td class="text-end">
                  <?php $n = $parametres['traduites'][$code] ?? 0; ?>
                  <?php if ($n === 0): ?>
                    <span class="text-muted">—</span>
                  <?php else: ?>
                    <span class="badge bg-success"><?= (int) $n ?> champ<?= $n > 1 ? 's' : '' ?></span>
                  <?php endif; ?>
                </td>
<?php endforeach; ?>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary"
                     href="<?= Admin::url('/traductions/parametre') ?>">Ouvrir</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <p class="text-muted small mb-0">
          Les textes de l'interface — les boutons, les intitulés de section, les
          messages de formulaire — ne sont pas ici : ils appartiennent au site
          et non à la rédaction, et vivent dans le dépôt avec le code qui les
          affiche. Cet écran ne porte que ce que vous avez saisi.
        </p>
      </div>
    </div>
  </div>
</div>
