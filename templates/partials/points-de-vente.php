<?php
/**
 * La grille des points de vente (lot G12).
 *
 * Écrite une fois pour deux pages : l'accueil et « Le livre » montrent la même
 * section « Où se procurer l'ouvrage », et les trois villes y étaient écrites
 * en dur des deux côtés. Deux copies d'un même bloc, c'est la garantie qu'une
 * des deux cessera d'être tenue à jour — c'est d'ailleurs ce qui vient
 * d'arriver, les deux gabarits ayant dérivé jusqu'à ne plus appeler la même
 * clé de lexique pour la même phrase.
 *
 * Attend `$pointsDeVente`, tels que `App\Model\PointDeVente::listerPublies()`
 * les rend. Rien n'est affiché s'il n'y en a aucun : la section garde son
 * titre et son bouton de commande, et ne montre pas un cadre vide.
 *
 * @var array<int,array<string,mixed>> $pointsDeVente
 */

use App\Core\View;
use App\Model\PointDeVente;

$pointsDeVente = $pointsDeVente ?? [];

if ($pointsDeVente !== []):
?>
<div class="pos reveal">
  <?php foreach ($pointsDeVente as $pdv): ?>
    <?php
      $enseigne  = trim((string) ($pdv['enseigne'] ?? ''));
      $adresse   = trim((string) ($pdv['adresse'] ?? ''));
      $telephone = trim((string) ($pdv['telephone'] ?? ''));
      $url       = trim((string) ($pdv['url'] ?? ''));
    ?>
    <div class="pos__i">
      <h3 class="t-d3"><?= View::e((string) $pdv['ville']) ?></h3>

      <?php if ($enseigne === '' && $adresse === ''): ?>
        <?php /* Exactement ce que les gabarits affichaient avant ce lot. La
                 fiche existe, elle attend son enseigne — et l'écran « Points
                 de vente » du back-office signale celles qui en sont là. */ ?>
        <p class="t-small"><?= t('points_de_vente.a_renseigner') ?></p>
      <?php else: ?>
        <p class="t-small">
          <?php if ($enseigne !== ''): ?>
            <?php /* L'enseigne devient un lien quand elle a un site : un
                     libellé « voir le site » de plus alourdirait une carte qui
                     tient en trois lignes. */ ?>
            <?php if ($url !== ''): ?>
              <a class="link" href="<?= View::e($url) ?>" target="_blank" rel="noopener">
                <?= View::e($enseigne) ?>
              </a>
            <?php else: ?>
              <?= View::e($enseigne) ?>
            <?php endif; ?>
            <?= $adresse !== '' ? '<br>' : '' ?>
          <?php endif; ?>
          <?= $adresse !== '' ? View::e($adresse) : '' ?>
        </p>
      <?php endif; ?>

      <?php if ($telephone !== ''): ?>
        <p class="t-small pos__tel">
          <a href="tel:<?= View::e(PointDeVente::appel($telephone)) ?>"><?= View::e($telephone) ?></a>
        </p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
