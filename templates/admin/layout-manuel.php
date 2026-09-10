<?php
/**
 * Mise en page du manuel de l'éditeur, servi dans le back-office.
 *
 * **Ni barre latérale ni barre supérieure, et c'est délibéré.** Le manuel
 * porte sa propre feuille de style, celle du site — elle redéfinit `body`,
 * `h1` à `h4`, `table`, `ul`, `a`. Rendue dans `admin/layout.php`, elle
 * entrerait en collision avec le thème Bootstrap du back-office : les deux se
 * disputeraient les mêmes sélecteurs, et aucun des deux ne serait juste.
 *
 * Le manuel est donc servi comme ce qu'il est — un document — avec une seule
 * addition : un bandeau de retour, sans lequel on n'aurait que le bouton
 * « précédent » du navigateur pour revenir au back-office.
 *
 * La session est exigée comme partout ailleurs : la garde de `cmsadmin/index.php`
 * fonctionne par liste blanche, et cette route n'y figure pas.
 */

use App\Core\Admin;
use App\Core\View;

$titre = $titre ?? "Manuel de l'éditeur";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/assets/img/favicon-32.png" sizes="32x32" type="image/png">
<style>
  /* Le bandeau de retour. Les valeurs de repli comptent : si le manuel est
     absent du serveur, sa feuille de style ne pose aucun jeton et le bandeau
     doit rester lisible pour porter le message d'erreur. */
  .retour-admin {
    position: sticky;
    top: 0;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.7rem clamp(1.25rem, 4vw, 3rem);
    background: var(--paper-high, #FCFAF6);
    border-bottom: 1px solid var(--rule, #DED7C9);
    font: 500 0.75rem/1 var(--font-sans, system-ui, sans-serif);
    letter-spacing: 0.14em;
    text-transform: uppercase;
  }
  /* Sélecteur de classe (0,2,0) : la feuille du manuel, chargée plus bas, pose
     `a` (0,0,1) et l'emporterait sinon sur l'ordre d'apparition. */
  .retour-admin a { color: var(--ink-2, #4D4D4D); text-decoration: none; }
  .retour-admin a:hover { color: var(--brass-text, #7D6134); }
  .retour-admin__ou { color: var(--ink-3, #6B6862); letter-spacing: 0.1em; }

  /* Sur papier, le bandeau n'a pas de sens : il ne se clique pas. */
  @media print { .retour-admin { display: none; } }
</style>
</head>
<body>

<nav class="retour-admin" aria-label="Retour au back-office">
  <a href="<?= Admin::url('/') ?>">&larr; Retour au back-office</a>
  <span class="retour-admin__ou"><?= View::e($titre) ?></span>
</nav>

<?= $contenu ?>

</body>
</html>
