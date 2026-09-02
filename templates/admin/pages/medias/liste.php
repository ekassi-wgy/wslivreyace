<?php
/**
 * Médiathèque : dépôt et planche de contact.
 *
 * En grille et non en tableau — on reconnaît une archive à son image, pas à
 * son nom de fichier. Le formulaire de dépôt est sur la même page que la
 * planche : l'éditeur voit arriver ce qu'il vient de déposer, et repère du
 * même coup ce qu'il a déposé deux fois.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Televersement;
use App\Core\View;
use App\Model\Media;

$onglets = ['tous' => 'Toutes'] + Media::CATEGORIES;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Contenus</span>
    <h1>Médiathèque</h1>
    <p>
      <?= (int) $compteurs['tous'] ?> image<?= $compteurs['tous'] > 1 ? 's' : '' ?> &middot;
      <?= (int) $compteurs['publie'] ?> en ligne &middot; portraits, archives officielles, coupures de presse
    </p>
  </div>
  <a class="btn btn-primary" href="#depot">
    <i class="mdi mdi-tray-arrow-up me-1" aria-hidden="true"></i> Déposer des images
  </a>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded" id="depot">
      <div class="card-body">
        <h4 class="card-title card-title-dash">Déposer</h4>
        <p class="card-subtitle card-subtitle-dash">
          JPEG, PNG ou WebP, <?= View::e(Televersement::poids($tailleMax)) ?> par fichier au plus,
          <?= (int) $lotMax ?> fichiers par dépôt. Les images arrivent en brouillon :
          rien n'est visible du public tant que la légende et le crédit ne sont pas saisis.
        </p>

        <form method="post" action="<?= Admin::url('/medias') ?>" enctype="multipart/form-data"
              class="pgy-depot" data-depot>
          <?= Csrf::champ() ?>
          <?php if ($filtre !== 'tous'): ?>
            <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
          <?php endif; ?>

          <div class="pgy-depot__champ">
            <label class="form-label" for="fichiers">Fichiers</label>
            <input type="file" class="form-control" id="fichiers" name="fichiers[]"
                   accept="image/jpeg,image/png,image/webp" multiple required
                   data-depot-fichiers>
            <div class="form-text" data-depot-compte>
              Sélection multiple possible : maintenez &#8984; (ou Ctrl) en cliquant.
            </div>
          </div>

          <div class="pgy-depot__champ pgy-depot__champ--court">
            <label class="form-label" for="categorie">Catégorie du lot</label>
            <select class="form-select" id="categorie" name="categorie">
              <?php foreach (Media::CATEGORIES as $valeur => $libelle): ?>
                <option value="<?= View::e($valeur) ?>"<?= $filtre === $valeur ? ' selected' : '' ?>>
                  <?= View::e($libelle) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">Modifiable image par image ensuite.</div>
          </div>

          <div class="pgy-depot__envoi">
            <button type="submit" class="btn btn-primary">
              <i class="mdi mdi-tray-arrow-up me-1" aria-hidden="true"></i> Déposer
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<div class="pgy-onglets" role="tablist" aria-label="Filtrer par catégorie">
  <?php foreach ($onglets as $cle => $libelle): ?>
    <?php $n = $cle === 'tous' ? $compteurs['tous'] : ($compteurs[$cle] ?? 0); ?>
    <a class="pgy-onglet<?= $filtre === $cle ? ' is-actif' : '' ?>"
       href="<?= Admin::url('/medias') . ($cle === 'tous' ? '' : '?categorie=' . $cle) ?>"
       <?= $filtre === $cle ? 'aria-current="page"' : '' ?>>
      <?= View::e($libelle) ?>
      <span class="pgy-onglet__n"><?= (int) $n ?></span>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($lignes === []): ?>
  <div class="card card-rounded"><div class="card-body">
    <div class="pgy-vide">
      <i class="mdi mdi-image-multiple-outline" aria-hidden="true"></i>
      <p class="mb-1 fw-semibold">
        <?= $compteurs['tous'] === 0 ? 'La médiathèque est vide' : 'Aucune image dans cette catégorie' ?>
      </p>
      <p class="mb-0 small">
        <?php if ($compteurs['tous'] === 0): ?>
          Les archives photographiques attendues sont listées au README §5, avec
          leurs dimensions de livraison. Déposez-les ci-dessus.
        <?php else: ?>
          Essayez un autre filtre.
        <?php endif; ?>
      </p>
    </div>
  </div></div>
<?php else: ?>

  <div class="pgy-grille">
    <?php foreach ($lignes as $ligne): ?>
      <?php
      $id       = (int) $ligne['id'];
      $enLigne  = $ligne['statut'] === 'publie';
      $credit   = trim((string) ($ligne['credit'] ?? ''));
      $fiche    = Admin::url('/medias/' . $id);
      ?>
      <figure class="pgy-media<?= $enLigne ? ' pgy-media--publie' : '' ?>">

        <a class="pgy-media__vue" href="<?= $fiche ?>">
          <img src="<?= View::e(Media::urlVignette((string) $ligne['fichier'])) ?>"
               alt="<?= View::e(Media::alternative($ligne)) ?>" loading="lazy">
        </a>

        <figcaption class="pgy-media__corps">
          <a class="pgy-media__titre" href="<?= $fiche ?>"><?= View::e($ligne['titre'] ?? 'Sans titre') ?></a>

          <p class="pgy-media__meta">
            <?= View::e(Media::CATEGORIES[$ligne['categorie']] ?? $ligne['categorie']) ?>
            <?php if (!empty($ligne['largeur'])): ?>
              &middot; <?= (int) $ligne['largeur'] ?>&thinsp;×&thinsp;<?= (int) $ligne['hauteur'] ?>
            <?php endif; ?>
            <?php if (!empty($ligne['octets'])): ?>
              &middot; <?= View::e(Televersement::poids((int) $ligne['octets'])) ?>
            <?php endif; ?>
          </p>

          <?php if ($credit === ''): ?>
            <p class="pgy-media__alerte">
              <i class="mdi mdi-alert-outline" aria-hidden="true"></i> Crédit manquant
            </p>
          <?php endif; ?>

          <div class="pgy-media__pied">
            <span class="pgy-statut pgy-statut--<?= View::e($ligne['statut']) ?>">
              <?= View::e(Media::STATUTS[$ligne['statut']] ?? $ligne['statut']) ?>
            </span>

            <div class="pgy-actions">
              <form method="post" action="<?= Admin::url("/medias/$id/statut") ?>" class="d-inline">
                <?= Csrf::champ() ?>
                <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
                <button type="submit" class="btn btn-sm <?= $enLigne ? 'btn-outline-secondary' : 'btn-primary' ?>"
                        title="<?= $enLigne ? 'Repasser en brouillon' : 'Publier' ?>">
                  <i class="mdi <?= $enLigne ? 'mdi-eye-off-outline' : 'mdi-eye-outline' ?>" aria-hidden="true"></i>
                  <span class="visually-hidden">
                    <?= $enLigne ? 'Repasser en brouillon' : 'Publier' ?> : <?= View::e($ligne['titre'] ?? '') ?>
                  </span>
                </button>
              </form>

              <a class="btn btn-sm btn-outline-secondary" href="<?= $fiche ?>" title="Modifier">
                <i class="mdi mdi-pencil-outline" aria-hidden="true"></i>
                <span class="visually-hidden">Modifier : <?= View::e($ligne['titre'] ?? '') ?></span>
              </a>

              <form method="post" action="<?= Admin::url("/medias/$id/supprimer") ?>" class="d-inline"
                    data-confirmation="Supprimer définitivement « <?= View::e($ligne['titre'] ?? '') ?> » ? Le fichier sera effacé du serveur.">
                <?= Csrf::champ() ?>
                <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                  <i class="mdi mdi-trash-can-outline" aria-hidden="true"></i>
                  <span class="visually-hidden">Supprimer : <?= View::e($ligne['titre'] ?? '') ?></span>
                </button>
              </form>
            </div>
          </div>
        </figcaption>

      </figure>
    <?php endforeach; ?>
  </div>

<?php endif; ?>
