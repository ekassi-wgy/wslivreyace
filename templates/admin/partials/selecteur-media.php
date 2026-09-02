<?php
/**
 * Sélecteur d'image : la planche de la médiathèque dans une boîte de dialogue.
 *
 * Rendu une seule fois par page, à la fin du gabarit — hors du formulaire :
 * une boîte de dialogue qui vit à l'intérieur d'un <form> lui envoie ses
 * boutons, et Bootstrap la déplace parfois dans le corps du document, ce qui
 * la sortirait du formulaire en cours de route.
 *
 * Attend $medias dans la portée appelante.
 */

use App\Core\Admin;
use App\Core\View;
use App\Model\Media;
?>
<div class="modal fade" id="selecteur-media" tabindex="-1" aria-labelledby="selecteur-media-titre" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="selecteur-media-titre">Choisir une image</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>

      <div class="modal-body">
        <?php if ($medias === []): ?>
          <div class="pgy-vide">
            <i class="mdi mdi-image-multiple-outline" aria-hidden="true"></i>
            <p class="mb-1 fw-semibold">La médiathèque est vide</p>
            <p class="mb-3 small">Déposez d'abord les images ; elles seront ensuite proposées ici.</p>
            <a class="btn btn-primary" href="<?= Admin::url('/medias') ?>" target="_blank" rel="noopener">
              Ouvrir la médiathèque
            </a>
          </div>
        <?php else: ?>
          <p class="form-text mt-0">
            Les brouillons sont proposés comme le reste : c'est la fiche qui décide
            de ce qui s'affiche, et une image ne se publie pas toute seule.
          </p>
          <div class="pgy-planche">
            <?php foreach ($medias as $m): ?>
              <button type="button" class="pgy-planche__item" data-media-choix
                      data-fichier="<?= View::e((string) $m['fichier']) ?>"
                      data-vignette="<?= View::e(Media::urlVignette((string) $m['fichier'])) ?>"
                      data-titre="<?= View::e((string) ($m['titre'] ?? '')) ?>"
                      data-alt="<?= View::e(Media::alternative($m)) ?>">
                <span class="pgy-planche__cadre">
                  <img src="<?= View::e(Media::urlVignette((string) $m['fichier'])) ?>"
                       alt="<?= View::e(Media::alternative($m)) ?>" loading="lazy">
                </span>
                <span><?= View::e($m['titre'] ?? 'Sans titre') ?></span>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>
