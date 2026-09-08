<?php
/**
 * Fiche d'une contribution (lot G8).
 *
 * L'écran d'où l'on tranche. Les fichiers ne s'y affichent pas en aperçu : ils
 * sont en quarantaine, et un aperçu supposerait de les servir au navigateur —
 * ce que ce lot cherche justement à ne pas faire. Ils se téléchargent, un par
 * un, et s'ouvrent dans le lecteur du modérateur.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\DateLisible;
use App\Core\Televersement;
use App\Core\View;
use App\Model\Contribution;
use App\Model\Media;

$id      = (int) $ligne['id'];
$qui     = trim(($ligne['prenom'] ?? '') . ' ' . $ligne['nom']);
$attente = $ligne['statut'] === 'en_attente';
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Modération</span>
    <h1><?= View::e($qui) ?></h1>
    <p>Reçue le <?= View::e(DateLisible::longue((string) $ligne['recu_le'])) ?>
       &middot; <span class="pgy-statut pgy-statut--<?= View::e((string) $ligne['statut']) ?>">
         <?= View::e(Contribution::STATUTS[$ligne['statut']] ?? (string) $ligne['statut']) ?></span></p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/contributions') ?>">Retour à la file</a>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Ce qu'on nous confie</h4>

      <p class="pgy-texte"><?= nl2br(View::e((string) $ligne['description'])) ?></p>

      <dl class="pgy-fiche">
        <?php foreach ([
          'date_approx' => 'Date approximative',
          'source'      => 'Origine de la pièce',
        ] as $champ => $libelle): ?>
          <?php $v = trim((string) ($ligne[$champ] ?? '')); ?>
          <?php if ($v !== ''): ?>
            <div><dt><?= $libelle ?></dt><dd><?= View::e($v) ?></dd></div>
          <?php endif; ?>
        <?php endforeach; ?>
        <div>
          <dt>Cession de droits</dt>
          <dd>
            <?php if (!empty($ligne['droits_le'])): ?>
              Acceptée le <?= View::e(DateLisible::longue((string) $ligne['droits_le'])) ?>
            <?php else: ?>
              <span class="pgy-statut pgy-statut--en_attente">non enregistrée</span>
            <?php endif; ?>
          </dd>
        </div>
      </dl>
    </div></div>

    <div class="card card-rounded mt-4"><div class="card-body">
      <h4 class="card-title card-title-dash">Fichiers</h4>

      <?php if ($fichiers === []): ?>
        <p class="text-muted mb-0">
          Aucun fichier joint. La contribution reste utile : quelqu'un qui sait
          où se trouve une archive nous apprend quelque chose.
        </p>
      <?php else: ?>
        <p class="text-muted small">
          Ils sont <strong>en quarantaine</strong> : aucune adresse publique ne les
          sert, et ils n'entreront dans la médiathèque qu'à votre acceptation.
          Téléchargez-les pour les examiner.
        </p>

        <ul class="pgy-quarantaine">
          <?php foreach ($fichiers as $f): ?>
            <li>
              <span class="pgy-quarantaine__signe" aria-hidden="true">
                <?= Media::SIGNES_FAMILLE[$f['famille']] ?? '📎' ?>
              </span>
              <span class="pgy-quarantaine__corps">
                <span class="pgy-quarantaine__nom"><?= View::e((string) ($f['nom_origine'] ?? 'Sans nom')) ?></span>
                <span class="pgy-sous">
                  <?= View::e(Media::FAMILLES[$f['famille']] ?? (string) $f['famille']) ?>
                  <?php if (!empty($f['octets'])): ?>
                    &middot; <?= View::e(Televersement::poids((int) $f['octets'])) ?>
                  <?php endif; ?>
                  <?php if ($f['media_id'] !== null): ?>
                    &middot; <a href="<?= Admin::url('/medias/' . (int) $f['media_id']) ?>">versé dans la médiathèque</a>
                  <?php endif; ?>
                </span>
              </span>
              <?php if ($f['media_id'] === null): ?>
                <a class="btn btn-sm btn-outline-secondary"
                   href="<?= Admin::url('/contributions/' . $id . '/fichier/' . (int) $f['id']) ?>">
                  Télécharger
                </a>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div></div>
  </div>

  <div class="col-lg-4">
    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Le contributeur</h4>
      <dl class="pgy-fiche">
        <div><dt>Nom</dt><dd><?= View::e($qui) ?></dd></div>
        <div><dt>Courriel</dt><dd><a href="mailto:<?= View::e((string) $ligne['email']) ?>"><?= View::e((string) $ligne['email']) ?></a></dd></div>
        <?php if (!empty($ligne['telephone'])): ?>
          <div><dt>Téléphone</dt><dd><?= View::e((string) $ligne['telephone']) ?></dd></div>
        <?php endif; ?>
      </dl>
      <p class="text-muted small mb-0">
        Écrivez-lui avant de trancher si quelque chose manque : c'est souvent
        la seule personne qui sache ce que montre la pièce.
      </p>
    </div></div>

    <?php if ($attente): ?>
      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Décider</h4>

        <form method="post" action="<?= Admin::url('/contributions/' . $id . '/accepter') ?>">
          <?= Csrf::champ() ?>
          <div class="mb-3">
            <label class="form-label" for="note">Note interne</label>
            <textarea class="form-control" id="note" name="note" rows="3"
                      maxlength="500" placeholder="Ce qui a motivé la décision."></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100 mb-2">
            Accepter — verser les fichiers
          </button>
          <p class="form-text">
            Ils arrivent <strong>en brouillon</strong> : accepter dit « ce fonds
            nous intéresse », pas « publions-le tel quel ».
          </p>
        </form>

        <form method="post" action="<?= Admin::url('/contributions/' . $id . '/refuser') ?>"
              data-confirmation="Refuser cette contribution ? Ses fichiers seront effacés du serveur.">
          <?= Csrf::champ() ?>
          <button type="submit" class="btn btn-outline-danger w-100">Refuser</button>
        </form>
      </div></div>
    <?php else: ?>
      <div class="card card-rounded mt-4"><div class="card-body">
        <h4 class="card-title card-title-dash">Décision</h4>
        <p class="mb-2">
          <?= View::e(Contribution::STATUTS[$ligne['statut']] ?? '') ?>
          le <?= View::e(DateLisible::longue((string) $ligne['traite_le'])) ?>.
        </p>
        <?php if (!empty($ligne['note'])): ?>
          <p class="pgy-sous"><?= nl2br(View::e((string) $ligne['note'])) ?></p>
        <?php endif; ?>

        <form method="post" action="<?= Admin::url('/contributions/' . $id . '/supprimer') ?>" class="mt-3"
              data-confirmation="Supprimer définitivement cette fiche ?">
          <?= Csrf::champ() ?>
          <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer la fiche</button>
        </form>
      </div></div>
    <?php endif; ?>
  </div>
</div>
