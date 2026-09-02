<?php
/**
 * Tableau de bord.
 *
 * Les compteurs sont lus en base. Ce qui est mis en avant n'est pas le volume
 * mais ce qui attend une décision : un témoignage en attente et un repère sans
 * source sont des choses à faire, pas des statistiques.
 */

use App\Core\Admin;
use App\Core\Database;
use App\Core\View;
use App\Model\Actualite;
use App\Model\Evenement;
use App\Model\Media;
use App\Model\Parametre;
use App\Model\Repere;
use App\Model\Temoignage;

$compteurs = [
    ['Actualités',  Actualite::compter('publie'),  Actualite::compter(),  '/actualites'],
    ['Événements',  Evenement::compter('publie'),  Evenement::compter(),  '/evenements'],
    ['Repères',     Repere::compter('publie'),     Repere::compter(),     '/reperes'],
    ['Témoignages', Temoignage::compter('publie'), Temoignage::compter(), '/temoignages'],
    ['Médias',      Media::compter('publie'),      Media::compter(),      '/medias'],
];

// Ce qui attend quelqu'un.
$enAttente  = Temoignage::compter('en_attente');
$sansSource = (int) (Database::one(
    "SELECT COUNT(*) AS n FROM repere WHERE source IS NULL OR source = ''"
)['n'] ?? 0);
$sansCredit = (int) (Database::one(
    "SELECT COUNT(*) AS n FROM media WHERE credit IS NULL OR credit = ''"
)['n'] ?? 0);
$ficheTotal   = count(Parametre::FICHE_LIVRE);
$ficheRemplie = Parametre::ficheRemplie();

$aFaire = [];
if ($enAttente > 0) {
    $aFaire[] = [
        'mdi-comment-alert-outline',
        sprintf('%d témoignage%s à modérer', $enAttente, $enAttente > 1 ? 's' : ''),
        'Des propos sur une personne réelle attendent une décision.',
        Admin::url('/temoignages?statut=en_attente'),
    ];
}
if ($sansSource > 0) {
    $aFaire[] = [
        'mdi-book-alert-outline',
        sprintf('%d repère%s sans source', $sansSource, $sansSource > 1 ? 's' : ''),
        'Ils ne pourront pas être publiés tant que la référence manque (CDC §6).',
        Admin::url('/reperes'),
    ];
}
if ($sansCredit > 0) {
    $aFaire[] = [
        'mdi-image-off-outline',
        sprintf('%d image%s sans crédit', $sansCredit, $sansCredit > 1 ? 's' : ''),
        'Elles ne peuvent pas être publiées tant que la provenance manque (CDC §6).',
        Admin::url('/medias'),
    ];
}
if ($ficheRemplie < $ficheTotal) {
    $aFaire[] = [
        'mdi-tune-variant',
        sprintf('Fiche technique incomplète (%d/%d)', $ficheRemplie, $ficheTotal),
        'Bloquante pour la mise en ligne : prix, ISBN, éditeur.',
        Admin::url('/parametres'),
    ];
}
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Back-office</span>
    <h1>Tableau de bord</h1>
    <p>Philippe Grégoire Yacé — <i>Une destinée</i></p>
  </div>
  <a class="btn btn-primary" href="/" target="_blank" rel="noopener">
    <i class="mdi mdi-open-in-new me-1" aria-hidden="true"></i> Voir le site
  </a>
</div>

<?php if ($aFaire !== []): ?>
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">À traiter</h4>
        <p class="card-subtitle card-subtitle-dash">
          Ce qui attend une décision, pas ce qu'il y a en volume.
        </p>
        <div class="pgy-afaire">
          <?php foreach ($aFaire as [$icone, $titre, $detail, $url]): ?>
            <a class="pgy-afaire__item" href="<?= View::e($url) ?>">
              <i class="mdi <?= View::e($icone) ?>" aria-hidden="true"></i>
              <span>
                <strong><?= View::e($titre) ?></strong>
                <em><?= View::e($detail) ?></em>
              </span>
              <i class="mdi mdi-chevron-right pgy-afaire__fleche" aria-hidden="true"></i>
            </a>
          <?php endforeach; ?>
        </div>
      </div></div>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Contenus</h4>
      <p class="card-subtitle card-subtitle-dash">
        Nombre publié, et total entre parenthèses — brouillons compris.
      </p>
      <div class="statistics-details d-flex align-items-center justify-content-between mt-4">
        <?php foreach ($compteurs as [$libelle, $publies, $total, $url]): ?>
          <div>
            <p class="statistics-title"><?= View::e($libelle) ?></p>
            <h3 class="rate-percentage">
              <a class="pgy-compteur" href="<?= Admin::url($url) ?>"><?= (int) $publies ?></a>
            </h3>
            <p class="text-muted mb-0 small">
              <?= $total > $publies
                  ? sprintf('%d au total', $total)
                  : ($total === 0 ? 'aucune entrée' : 'tout est publié') ?>
            </p>
          </div>
        <?php endforeach; ?>
        <div class="d-none d-md-block">
          <p class="statistics-title">Fiche technique</p>
          <h3 class="rate-percentage">
            <a class="pgy-compteur" href="<?= Admin::url('/parametres') ?>"><?= $ficheRemplie ?>/<?= $ficheTotal ?></a>
          </h3>
          <p class="text-muted mb-0 small">valeurs renseignées</p>
        </div>
      </div>
    </div></div>
  </div>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded"><div class="card-body">
      <h4 class="card-title card-title-dash">Ce qui reste à construire</h4>
      <p class="card-subtitle card-subtitle-dash">
        Les entrées verrouillées de la barre latérale.
      </p>
      <ul class="pgy-liste-notes mt-3">
        <li><strong>Commandes</strong> — suppose d'avoir tranché sur le backend de
            paiement avant d'écrire le tunnel.</li>
        <li><strong>Comptes</strong> — la création se fait en ligne de commande
            (<code>php bin/compte.php</code>) en attendant l'écran.</li>
      </ul>
    </div></div>
  </div>
</div>
