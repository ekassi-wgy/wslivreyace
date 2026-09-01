<?php
/**
 * Fabriques de champs de formulaire.
 *
 * Les six écrans du lot C répètent le même motif : libellé, contrôle, classe
 * d'erreur, message, aide. Le balisage Bootstrap qui va avec fait huit lignes ;
 * écrit à la main partout, une correction d'accessibilité en oublierait la
 * moitié.
 *
 * Les fonctions sont définies une seule fois : le fichier est inclus par
 * plusieurs gabarits au cours d'une même requête quand un formulaire est
 * réaffiché après erreur.
 */

use App\Core\View;

if (!function_exists('champ_valeur')) {

    /**
     * Valeur à réafficher : celle que l'éditeur vient de saisir en priorité,
     * la valeur en base ensuite. Après une erreur de validation, c'est la
     * saisie qu'il faut retrouver, pas l'ancienne valeur.
     *
     * @param array<string,mixed> $valeurs
     */
    function champ_valeur(array $valeurs, string $nom, string $defaut = ''): string
    {
        $v = $valeurs[$nom] ?? $defaut;
        return is_scalar($v) ? (string) $v : $defaut;
    }

    /** Bloc d'erreur et attribut aria, communs à tous les contrôles. */
    function champ_erreur(array $erreurs, string $nom): string
    {
        if (!isset($erreurs[$nom])) {
            return '';
        }
        return '<div class="invalid-feedback d-block" id="err-' . View::e($nom) . '">'
             . View::e($erreurs[$nom]) . '</div>';
    }

    function champ_classe(array $erreurs, string $nom, string $base = 'form-control'): string
    {
        return $base . (isset($erreurs[$nom]) ? ' is-invalid' : '');
    }

    function champ_aria(array $erreurs, string $nom): string
    {
        return isset($erreurs[$nom]) ? ' aria-describedby="err-' . View::e($nom) . '"' : '';
    }

    /**
     * @param array<string,mixed>  $valeurs
     * @param array<string,string> $erreurs
     * @param array<string,mixed>  $options  type, aide, requis, attributs
     */
    function champ_texte(array $valeurs, array $erreurs, string $nom, string $libelle, array $options = []): void
    {
        $type   = $options['type']   ?? 'text';
        $aide   = $options['aide']   ?? '';
        $requis = !empty($options['requis']);
        $attrs  = $options['attributs'] ?? '';
        ?>
        <div class="mb-3">
          <label class="form-label" for="<?= View::e($nom) ?>">
            <?= View::e($libelle) ?><?= $requis ? ' <span class="pgy-requis" aria-hidden="true">*</span>' : '' ?>
          </label>
          <input type="<?= View::e($type) ?>"
                 class="<?= champ_classe($erreurs, $nom) ?>"
                 id="<?= View::e($nom) ?>" name="<?= View::e($nom) ?>"
                 value="<?= View::e(champ_valeur($valeurs, $nom)) ?>"
                 <?= $requis ? 'required' : '' ?><?= champ_aria($erreurs, $nom) ?> <?= $attrs ?>>
          <?php if ($aide !== ''): ?><div class="form-text"><?= $aide ?></div><?php endif; ?>
          <?= champ_erreur($erreurs, $nom) ?>
        </div>
        <?php
    }

    function champ_zone(array $valeurs, array $erreurs, string $nom, string $libelle, array $options = []): void
    {
        $lignes = $options['lignes'] ?? 6;
        $aide   = $options['aide']   ?? '';
        ?>
        <div class="mb-3">
          <label class="form-label" for="<?= View::e($nom) ?>"><?= View::e($libelle) ?></label>
          <textarea class="<?= champ_classe($erreurs, $nom) ?>" id="<?= View::e($nom) ?>"
                    name="<?= View::e($nom) ?>" rows="<?= (int) $lignes ?>"<?= champ_aria($erreurs, $nom) ?>><?= View::e(champ_valeur($valeurs, $nom)) ?></textarea>
          <?php if ($aide !== ''): ?><div class="form-text"><?= $aide ?></div><?php endif; ?>
          <?= champ_erreur($erreurs, $nom) ?>
        </div>
        <?php
    }

    /** @param array<string,string> $choix valeur => libellé */
    function champ_choix(array $valeurs, array $erreurs, string $nom, string $libelle, array $choix, array $options = []): void
    {
        $aide     = $options['aide'] ?? '';
        $courante = champ_valeur($valeurs, $nom, $options['defaut'] ?? '');
        ?>
        <div class="mb-3">
          <label class="form-label" for="<?= View::e($nom) ?>"><?= View::e($libelle) ?></label>
          <select class="<?= champ_classe($erreurs, $nom, 'form-select') ?>"
                  id="<?= View::e($nom) ?>" name="<?= View::e($nom) ?>"<?= champ_aria($erreurs, $nom) ?>>
            <?php foreach ($choix as $valeur => $texte): ?>
              <option value="<?= View::e((string) $valeur) ?>"<?= (string) $valeur === $courante ? ' selected' : '' ?>>
                <?= View::e($texte) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($aide !== ''): ?><div class="form-text"><?= $aide ?></div><?php endif; ?>
          <?= champ_erreur($erreurs, $nom) ?>
        </div>
        <?php
    }
}
