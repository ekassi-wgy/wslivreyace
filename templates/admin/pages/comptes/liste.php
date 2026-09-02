<?php
/**
 * Comptes du back-office.
 *
 * En tableau, contrairement à la médiathèque : il y en aura une poignée, et ce
 * qu'on vient y lire — qui a quel rôle, qui est encore actif — se compare de
 * ligne à ligne.
 */

use App\Controller\Admin\CompteController;
use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Administration</span>
    <h1>Comptes</h1>
    <p>
      <?= count($lignes) ?> compte<?= count($lignes) > 1 ? 's' : '' ?> &middot;
      <?= (int) $admins ?> administrateur<?= $admins > 1 ? 's' : '' ?> actif<?= $admins > 1 ? 's' : '' ?>
    </p>
  </div>
  <a class="btn btn-primary" href="<?= Admin::url('/comptes/nouveau') ?>">
    <i class="mdi mdi-account-plus-outline me-1" aria-hidden="true"></i> Nouveau compte
  </a>
</div>

<div class="row">
  <div class="col-12 grid-margin">
    <div class="card card-rounded">
      <div class="card-body">

        <p class="card-subtitle card-subtitle-dash">
          Un compte ne se supprime pas, il se désactive : il a modéré des témoignages
          et remis des commandes, et ces traces doivent garder son nom. Un compte
          désactivé perd l'accès à la seconde — son état est relu à chaque requête.
        </p>

        <div class="table-responsive mt-3">
          <table class="table pgy-table">
            <thead>
              <tr>
                <th scope="col">Nom</th>
                <th scope="col">Adresse de connexion</th>
                <th scope="col">Rôle</th>
                <th scope="col">État</th>
                <th scope="col">Créé le</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lignes as $ligne): ?>
                <?php
                $id     = (int) $ligne['id'];
                $actif  = (int) $ligne['actif'] === 1;
                $estMoi = $id === $moi;
                ?>
                <tr>
                  <td>
                    <a class="pgy-lien-fiche" href="<?= Admin::url('/comptes/' . $id) ?>">
                      <?= View::e($ligne['nom']) ?>
                    </a>
                    <?php if ($estMoi): ?>
                      <span class="pgy-sous">— vous</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-muted"><?= View::e($ligne['email']) ?></td>
                  <td>
                    <span class="pgy-statut pgy-statut--<?= $ligne['role'] === 'admin' ? 'publie' : 'brouillon' ?>">
                      <?= View::e(CompteController::ROLES[$ligne['role']] ?? $ligne['role']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="pgy-statut pgy-statut--<?= $actif ? 'publie' : 'refuse' ?>">
                      <?= $actif ? 'Actif' : 'Désactivé' ?>
                    </span>
                  </td>
                  <td class="text-muted"><?= View::e(date('d/m/Y', strtotime((string) $ligne['cree_le']))) ?></td>
                  <td>
                    <div class="pgy-actions">
                      <a class="btn btn-sm btn-outline-secondary" href="<?= Admin::url('/comptes/' . $id) ?>"
                         title="Modifier">
                        <i class="mdi mdi-pencil-outline" aria-hidden="true"></i>
                        <span class="visually-hidden">Modifier : <?= View::e($ligne['nom']) ?></span>
                      </a>

                      <?php /* Le bouton n'est pas proposé sur son propre compte : la
                               manœuvre serait refusée côté serveur, autant ne pas
                               l'offrir. */ ?>
                      <?php if (!$estMoi): ?>
                        <form method="post" action="<?= Admin::url('/comptes/' . $id . '/actif') ?>"
                              class="d-inline"
                              data-confirmation="<?= $actif
                                  ? 'Désactiver le compte de ' . View::e($ligne['nom']) . ' ? Sa session tombera aussitôt.'
                                  : 'Réactiver le compte de ' . View::e($ligne['nom']) . ' ?' ?>">
                          <?= Csrf::champ() ?>
                          <button type="submit" class="btn btn-sm <?= $actif ? 'btn-outline-danger' : 'btn-primary' ?>"
                                  title="<?= $actif ? 'Désactiver' : 'Réactiver' ?>">
                            <i class="mdi <?= $actif ? 'mdi-account-cancel-outline' : 'mdi-account-check-outline' ?>"
                               aria-hidden="true"></i>
                            <span class="visually-hidden">
                              <?= $actif ? 'Désactiver' : 'Réactiver' ?> : <?= View::e($ligne['nom']) ?>
                            </span>
                          </button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <p class="form-text mt-3 mb-0">
          Le premier administrateur se crée en ligne de commande
          (<code>php bin/compte.php creer</code>) ; c'est aussi la porte de secours
          si plus personne ne peut se connecter.
        </p>

      </div>
    </div>
  </div>
</div>
