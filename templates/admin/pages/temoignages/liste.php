<?php
/**
 * File de modération des témoignages.
 *
 * Présentés en cartes et non en tableau : un témoignage se lit avant d'être
 * jugé, et une ligne de tableau tronquée n'est pas lisible. La décision se
 * prend sous le texte, au moment où on vient de le lire.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Temoignage;

$onglets = [
    'en_attente' => 'En attente',
    'publie'     => 'Publiés',
    'refuse'     => 'Refusés',
    'tous'       => 'Tous',
];
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Modération</span>
    <h1>Témoignages</h1>
    <p>
      <?= (int) $compteurs['en_attente'] ?> en attente &middot;
      <?= (int) $compteurs['publie'] ?> en ligne &middot;
      <?= (int) $compteurs['refuse'] ?> refusé<?= $compteurs['refuse'] > 1 ? 's' : '' ?>
    </p>
  </div>
</div>

<div class="pgy-onglets" role="tablist" aria-label="Filtrer par statut">
  <?php foreach ($onglets as $cle => $libelle): ?>
    <?php $n = $cle === 'tous' ? $compteurs['tous'] : ($compteurs[$cle] ?? 0); ?>
    <a class="pgy-onglet<?= $filtre === $cle ? ' is-actif' : '' ?>"
       href="<?= Admin::url('/temoignages') . ($cle === 'tous' ? '' : '?statut=' . $cle) ?>"
       <?= $filtre === $cle ? 'aria-current="page"' : '' ?>>
      <?= View::e($libelle) ?>
      <span class="pgy-onglet__n"><?= (int) $n ?></span>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($lignes === []): ?>
  <div class="card card-rounded"><div class="card-body">
    <div class="pgy-vide">
      <i class="mdi mdi-comment-check-outline" aria-hidden="true"></i>
      <p class="mb-1 fw-semibold">
        <?= $filtre === 'en_attente' ? 'Rien à modérer' : 'Aucun témoignage ici' ?>
      </p>
      <p class="mb-0 small">
        <?php if ($compteurs['tous'] === 0): ?>
          Les témoignages arriveront par le formulaire public. En attendant, l'éditeur
          peut en fournir quelques-uns d'amorce — avec l'autorisation écrite des
          signataires.
        <?php else: ?>
          Essayez un autre filtre.
        <?php endif; ?>
      </p>
    </div>
  </div></div>
<?php endif; ?>

<?php foreach ($lignes as $ligne): ?>
  <?php
  $id     = (int) $ligne['id'];
  $statut = (string) $ligne['statut'];
  $soumis = strtotime((string) $ligne['soumis_le']);
  ?>
  <div class="card card-rounded pgy-temoignage pgy-temoignage--<?= View::e($statut) ?>">
    <div class="card-body">

      <div class="pgy-temoignage__tete">
        <div>
          <p class="pgy-temoignage__nom"><?= View::e($ligne['auteur_nom']) ?></p>
          <?php if (!empty($ligne['auteur_fonction'])): ?>
            <p class="pgy-temoignage__qualite"><?= View::e($ligne['auteur_fonction']) ?></p>
          <?php endif; ?>
        </div>
        <span class="pgy-statut pgy-statut--<?= View::e($statut) ?>">
          <?= View::e(Temoignage::STATUTS[$statut] ?? $statut) ?>
        </span>
      </div>

      <?php /* nl2br sur du texte déjà échappé : les retours à la ligne du
               signataire sont conservés, sans qu'aucune balise ne passe. */ ?>
      <blockquote class="pgy-temoignage__texte"><?= nl2br(View::e($ligne['contenu'])) ?></blockquote>

      <p class="pgy-temoignage__meta">
        Reçu le <?= $soumis !== false ? View::e(date('d/m/Y \à H\hi', $soumis)) : '—' ?>
        <?php if (!empty($ligne['modere_le'])): ?>
          &middot; modéré le <?= View::e(date('d/m/Y', strtotime((string) $ligne['modere_le']))) ?>
          <?php if (!empty($ligne['moderateur_nom'])): ?>
            par <?= View::e($ligne['moderateur_nom']) ?>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($ligne['auteur_email'])): ?>
          &middot; <a href="mailto:<?= View::e($ligne['auteur_email']) ?>"><?= View::e($ligne['auteur_email']) ?></a>
          <span class="pgy-temoignage__prive">(jamais affiché en public)</span>
        <?php endif; ?>
      </p>

      <div class="pgy-temoignage__actions">
        <?php
        // Publier n'est proposé que si ce n'est pas déjà le cas, etc. : un
        // bouton sans effet est un bouton qu'on finit par cliquer.
        $boutons = [];
        if ($statut !== 'publie')     { $boutons['publier']   = ['Publier', 'mdi-check', 'btn-primary']; }
        if ($statut !== 'refuse')     { $boutons['refuser']   = ['Refuser', 'mdi-close', 'btn-outline-danger']; }
        if ($statut !== 'en_attente') { $boutons['reprendre'] = ['Remettre en attente', 'mdi-undo', 'btn-outline-secondary']; }
        ?>
        <?php foreach ($boutons as $decision => [$libelle, $icone, $classe]): ?>
          <form method="post" action="<?= Admin::url("/temoignages/$id/$decision") ?>">
            <?= Csrf::champ() ?>
            <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
            <button type="submit" class="btn btn-sm <?= $classe ?>">
              <i class="mdi <?= $icone ?> me-1" aria-hidden="true"></i><?= View::e($libelle) ?>
            </button>
          </form>
        <?php endforeach; ?>

        <a class="btn btn-sm btn-outline-secondary" href="<?= Admin::url("/temoignages/$id") ?>">
          <i class="mdi mdi-pencil-outline me-1" aria-hidden="true"></i>Corriger
        </a>

        <form method="post" action="<?= Admin::url("/temoignages/$id/supprimer") ?>"
              data-confirmation="Supprimer définitivement le témoignage de <?= View::e($ligne['auteur_nom']) ?> ?">
          <?= Csrf::champ() ?>
          <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
          <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
            <i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>
            <span class="visually-hidden">Supprimer le témoignage de <?= View::e($ligne['auteur_nom']) ?></span>
          </button>
        </form>
      </div>

    </div>
  </div>
<?php endforeach; ?>
