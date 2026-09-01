<?php
/**
 * Formulaire de connexion.
 *
 * Le message d'erreur est le même pour une adresse inconnue, un mot de passe
 * faux et un compte désactivé : les distinguer reviendrait à confirmer
 * l'existence d'un compte à qui le demanderait.
 */

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\View;

$erreurs = $erreurs ?? [];
$email   = $email ?? '';
?>

<h1 class="pgy-nu__titre">Connexion</h1>
<p class="pgy-nu__intro">Accès réservé aux personnes autorisées à modifier le site.</p>

<form method="post" action="<?= Admin::url('/connexion') ?>" novalidate>
  <?= Csrf::champ() ?>

  <div class="mb-3">
    <label class="form-label" for="email">Adresse électronique</label>
    <input type="email" class="form-control<?= isset($erreurs['email']) ? ' is-invalid' : '' ?>"
           id="email" name="email" value="<?= View::e($email) ?>"
           autocomplete="username" autofocus required
           <?= isset($erreurs['email']) ? 'aria-describedby="err-email"' : '' ?>>
    <?php if (isset($erreurs['email'])): ?>
      <div class="invalid-feedback d-block" id="err-email"><?= View::e($erreurs['email']) ?></div>
    <?php endif; ?>
  </div>

  <div class="mb-4">
    <label class="form-label" for="mot_de_passe">Mot de passe</label>
    <input type="password" class="form-control<?= isset($erreurs['mot_de_passe']) ? ' is-invalid' : '' ?>"
           id="mot_de_passe" name="mot_de_passe"
           autocomplete="current-password" required
           <?= isset($erreurs['mot_de_passe']) ? 'aria-describedby="err-mdp"' : '' ?>>
    <?php if (isset($erreurs['mot_de_passe'])): ?>
      <div class="invalid-feedback d-block" id="err-mdp"><?= View::e($erreurs['mot_de_passe']) ?></div>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn-primary w-100">Se connecter</button>
</form>

<p class="pgy-nu__note">
  Mot de passe oublié : il n'y a pas encore de procédure automatique.
  Demandez sa réinitialisation à l'administrateur du site.
</p>
