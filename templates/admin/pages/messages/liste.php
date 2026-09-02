<?php
/**
 * Boîte de réception du formulaire de contact.
 *
 * Même présentation que la file de modération, et pour la même raison : un
 * message se lit avant qu'on en fasse quelque chose, et une ligne de tableau
 * tronquée n'est pas lisible. Deux différences tiennent au fond — l'adresse du
 * correspondant est ici la donnée utile, puisque tout l'objet de l'écran est
 * de pouvoir répondre ; et il n'y a rien à corriger, ces messages ne paraîtront
 * jamais en public.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
use App\Model\Message;

$onglets = [
    'nouveau' => 'Nouveaux',
    'traite'  => 'Traités',
    'tous'    => 'Tous',
];
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Modération</span>
    <h1>Messages</h1>
    <p>
      <?= (int) $compteurs['nouveau'] ?> à traiter &middot;
      <?= (int) $compteurs['traite'] ?> traité<?= $compteurs['traite'] > 1 ? 's' : '' ?>
    </p>
  </div>
</div>

<div class="pgy-onglets" role="tablist" aria-label="Filtrer par statut">
  <?php foreach ($onglets as $cle => $libelle): ?>
    <?php $n = $cle === 'tous' ? $compteurs['tous'] : ($compteurs[$cle] ?? 0); ?>
    <a class="pgy-onglet<?= $filtre === $cle ? ' is-actif' : '' ?>"
       href="<?= Admin::url('/messages') . ($cle === 'tous' ? '' : '?statut=' . $cle) ?>"
       <?= $filtre === $cle ? 'aria-current="page"' : '' ?>>
      <?= View::e($libelle) ?>
      <span class="pgy-onglet__n"><?= (int) $n ?></span>
    </a>
  <?php endforeach; ?>
</div>

<?php if ($lignes === []): ?>
  <div class="card card-rounded"><div class="card-body">
    <div class="pgy-vide">
      <i class="mdi mdi-email-outline" aria-hidden="true"></i>
      <p class="mb-1 fw-semibold">
        <?= $filtre === 'nouveau' ? 'Rien à traiter' : 'Aucun message ici' ?>
      </p>
      <p class="mb-0 small">
        <?php if ($compteurs['tous'] === 0): ?>
          Les messages arriveront par le formulaire de la page Contact. Ils ne
          sont envoyés par courriel à personne : cet écran est le seul endroit
          où on les lit.
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
  $recu   = strtotime((string) $ligne['recu_le']);
  $email  = (string) $ligne['email'];
  ?>
  <div class="card card-rounded pgy-courrier pgy-courrier--<?= View::e($statut) ?>">
    <div class="card-body">

      <div class="pgy-courrier__tete">
        <div>
          <p class="pgy-courrier__nom"><?= View::e($ligne['nom']) ?></p>
          <p class="pgy-courrier__qualite"><?= View::e(Message::sujet($ligne['sujet'])) ?></p>
        </div>
        <span class="pgy-statut pgy-statut--<?= View::e($statut) ?>">
          <?= View::e(Message::STATUTS[$statut] ?? $statut) ?>
        </span>
      </div>

      <?php /* nl2br sur du texte déjà échappé : les retours à la ligne sont
               conservés, sans qu'aucune balise ne passe. */ ?>
      <blockquote class="pgy-courrier__texte"><?= nl2br(View::e($ligne['contenu'])) ?></blockquote>

      <p class="pgy-courrier__meta">
        Reçu le <?= $recu !== false ? View::e(date('d/m/Y \à H\hi', $recu)) : '—' ?>
        <?php if (!empty($ligne['traite_le'])): ?>
          &middot; traité le <?= View::e(date('d/m/Y', strtotime((string) $ligne['traite_le']))) ?>
          <?php if (!empty($ligne['traiteur_nom'])): ?>
            par <?= View::e($ligne['traiteur_nom']) ?>
          <?php endif; ?>
        <?php endif; ?>
      </p>

      <div class="pgy-courrier__actions">
        <?php /* Répondre est l'action principale, et elle ne se fait pas ici :
                 le lien ouvre le logiciel de courrier avec l'objet prérempli.
                 Écrire un envoi de courriel depuis le back-office demanderait
                 une configuration SMTP que ce site n'a pas. */ ?>
        <a class="btn btn-sm btn-primary"
           href="mailto:<?= View::e($email) ?>?subject=<?= rawurlencode('Votre message — Philippe Grégoire Yacé') ?>">
          <i class="mdi mdi-reply me-1" aria-hidden="true"></i>Répondre à <?= View::e($email) ?>
        </a>

        <?php
        // Seule la bascule qui change quelque chose est proposée.
        $bascule = $statut === 'traite'
            ? ['nouveau', 'Remettre à traiter', 'mdi-undo', 'btn-outline-secondary']
            : ['traite',  'Marquer traité',     'mdi-check', 'btn-outline-primary'];
        ?>
        <form method="post" action="<?= Admin::url("/messages/$id/{$bascule[0]}") ?>">
          <?= Csrf::champ() ?>
          <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
          <button type="submit" class="btn btn-sm <?= $bascule[3] ?>">
            <i class="mdi <?= $bascule[2] ?> me-1" aria-hidden="true"></i><?= View::e($bascule[1]) ?>
          </button>
        </form>

        <form method="post" action="<?= Admin::url("/messages/$id/supprimer") ?>" data-confirmation="Supprimer définitivement le message de <?= View::e($ligne['nom']) ?> ? Il n'y a pas de corbeille.">
          <?= Csrf::champ() ?>
          <input type="hidden" name="retour" value="<?= View::e($filtre) ?>">
          <button type="submit" class="btn btn-sm btn-outline-danger">
            <i class="mdi mdi-trash-can-outline me-1" aria-hidden="true"></i>Supprimer
          </button>
        </form>
      </div>

    </div>
  </div>
<?php endforeach; ?>
