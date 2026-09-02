<?php
/**
 * Création et modification d'un compte.
 *
 * Le mot de passe a son propre formulaire en édition : le changer et corriger
 * un nom sont deux gestes différents, et les mêler ferait qu'enregistrer une
 * correction de nom réinitialiserait le mot de passe.
 */

use App\Controller\Admin\CompteController;
use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

require dirname(__DIR__, 2) . '/partials/champs.php';

$id     = $edition ? (int) $ligne['id'] : null;
$action = $edition ? Admin::url('/comptes/' . $id) : Admin::url('/comptes');
$estMoi = $edition && $id === $moi;

/**
 * État de la case « actif ».
 *
 * Au premier affichage, celui de la base. Après une erreur, celui que
 * l'utilisateur venait de poser — et une case décochée ne poste rien, d'où le
 * test de présence plutôt qu'une lecture de valeur.
 */
$actif = !$edition || ($erreurs === []
    ? (int) $ligne['actif'] === 1
    : isset($valeurs['actif']));
?>

<div class="pgy-entete">
  <div>
    <span class="pgy-surtitre">Comptes</span>
    <h1><?= $edition ? 'Modifier le compte' : 'Nouveau compte' ?></h1>
    <p>Les champs marqués d'un <span class="pgy-requis">*</span> sont obligatoires.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= Admin::url('/comptes') ?>">
    <i class="mdi mdi-arrow-left me-1" aria-hidden="true"></i> Retour à la liste
  </a>
</div>

<form method="post" action="<?= $action ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="row">
    <div class="col-lg-7 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Identité</h4>

        <?php champ_texte($valeurs, $erreurs, 'nom', 'Nom', [
            'requis' => true, 'attributs' => 'maxlength="120"',
        ]); ?>

        <?php champ_texte($valeurs, $erreurs, 'email', 'Adresse électronique', [
            'type' => 'email', 'requis' => true, 'attributs' => 'maxlength="180"',
            'aide' => "C'est l'identifiant de connexion. La changer change la façon "
                    . 'dont cette personne entre dans le back-office.',
        ]); ?>

        <?php if (!$edition): ?>
          <?php champ_texte($valeurs, $erreurs, 'mot_de_passe', 'Mot de passe', [
              'type'      => 'password',
              'attributs' => 'autocomplete="new-password"',
              'aide'      => 'Laissez vide — c\'est le cas normal : un mot de passe sera '
                           . 'fabriqué et affiché une seule fois, à transmettre de vive voix. '
                           . 'Sinon, ' . (int) $minimum . ' caractères au minimum.',
          ]); ?>
          <?php champ_texte($valeurs, $erreurs, 'mot_de_passe_bis', 'Confirmation', [
              'type'      => 'password',
              'attributs' => 'autocomplete="new-password"',
          ]); ?>
        <?php endif; ?>
      </div></div>
    </div>

    <div class="col-lg-5 grid-margin">
      <div class="card card-rounded"><div class="card-body">
        <h4 class="card-title card-title-dash">Droits</h4>

        <?php champ_choix($valeurs, $erreurs, 'role', 'Rôle', CompteController::ROLES, [
            'defaut' => 'editeur',
        ]); ?>

        <ul class="pgy-liste-notes">
          <?php foreach (CompteController::ROLES as $cle => $libelle): ?>
            <li><strong><?= View::e($libelle) ?></strong> — <?= View::e(CompteController::DROITS[$cle]) ?></li>
          <?php endforeach; ?>
        </ul>

        <?php if ($edition): ?>
          <div class="form-check mt-3">
            <input class="form-check-input<?= isset($erreurs['actif']) ? ' is-invalid' : '' ?>"
                   type="checkbox" value="1" id="actif" name="actif" <?= $actif ? 'checked' : '' ?>
                   <?= $estMoi ? 'disabled' : '' ?>>
            <label class="form-check-label" for="actif">Compte actif</label>
            <?php if ($estMoi): ?>
              <?php /* Désactivé ET renvoyé : un `disabled` ne poste rien, et le
                       compte serait désactivé à l'enregistrement. */ ?>
              <input type="hidden" name="actif" value="1">
              <div class="form-text">C'est votre compte : vous ne pouvez pas le désactiver.</div>
            <?php else: ?>
              <div class="form-text">
                Décoché, la personne ne peut plus se connecter et sa session ouverte tombe
                à la requête suivante.
              </div>
            <?php endif; ?>
            <?= champ_erreur($erreurs, 'actif') ?>
          </div>
        <?php endif; ?>
      </div></div>
    </div>
  </div>

  <div class="pgy-barre-actions">
    <button type="submit" class="btn btn-primary">
      <?= $edition ? 'Enregistrer les modifications' : 'Créer le compte' ?>
    </button>
    <a class="btn btn-outline-secondary" href="<?= Admin::url('/comptes') ?>">Annuler</a>

    <?php if ($edition): ?>
      <span class="pgy-barre-actions__info">Compte n<sup>o</sup> <?= $id ?></span>
    <?php endif; ?>
  </div>
</form>

<?php if ($edition): ?>
  <div class="card card-rounded">
    <div class="card-body">
      <h4 class="card-title card-title-dash">Mot de passe</h4>
      <p class="card-subtitle card-subtitle-dash">
        L'ancien n'est pas demandé : un administrateur qui réinitialise le mot de passe
        d'un tiers ne le connaît pas. Le nouveau n'est lisible qu'une fois.
      </p>

      <form method="post" action="<?= Admin::url('/comptes/' . $id . '/motdepasse') ?>"
            class="mt-3" novalidate>
        <?= Csrf::champ() ?>
        <div class="row">
          <div class="col-md-5">
            <?php champ_texte([], $erreurs, 'mot_de_passe', 'Nouveau mot de passe', [
                'type'      => 'password',
                'attributs' => 'autocomplete="new-password"',
                'aide'      => 'Vide = fabriqué et affiché une seule fois.',
            ]); ?>
          </div>
          <div class="col-md-5">
            <?php champ_texte([], $erreurs, 'mot_de_passe_bis', 'Confirmation', [
                'type'      => 'password',
                'attributs' => 'autocomplete="new-password"',
            ]); ?>
          </div>
        </div>
        <button type="submit" class="btn btn-outline-secondary">
          <i class="mdi mdi-key-variant me-1" aria-hidden="true"></i> Réinitialiser le mot de passe
        </button>
      </form>
    </div>
  </div>
<?php endif; ?>
