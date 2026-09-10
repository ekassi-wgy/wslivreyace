<?php
/**
 * Le manuel de l'éditeur, lu depuis sa source.
 *
 * **Ce fichier ne contient pas le manuel, il le lit.** `documentation/manuel-administration.html`
 * en est la source unique, et elle en a déjà deux consommateurs : le générateur
 * Word à côté, et l'artifact publié pour le commanditaire. En recopier le texte
 * ici en ferait un troisième exemplaire — c'est-à-dire la garantie qu'un des
 * trois cessera d'être tenu à jour. Le générateur Word porte cet avertissement
 * en tête depuis le premier jour ; il vaut d'autant plus à trois.
 *
 * Le fichier source ne porte ni `<!doctype>`, ni `<html>`, ni `<head>`, ni
 * `<body>` : il commence à `<title>` et enchaîne sur sa feuille de style puis
 * son corps. C'est ce qui lui permet d'être servi tel quel dans deux coquilles
 * différentes — celle de l'artifact et `admin/layout-manuel.php` ici — sans
 * qu'aucune ligne ne change.
 *
 * **Le dossier `documentation/` doit donc être déployé** depuis ce lot, ce qui
 * n'était pas le cas avant. Il porte son propre `.htaccess` en `Require all
 * denied` : Apache refuse toute requête directe, et le manuel n'est lisible
 * que par cette page, derrière la session.
 */

use App\Core\Admin;

$fichier = dirname(__DIR__, 3) . '/documentation/manuel-administration.html';

if (is_file($fichier)) {
    /* Aucun échappement : ce fichier est du HTML du dépôt, pas une saisie.
       L'échapper afficherait ses balises en toutes lettres. */
    echo file_get_contents($fichier);
    return;
}
?>

<?php /* Le seul cas d'absence plausible : un envoi qui a laissé `documentation/`
         de côté, comme la règle de déploiement le prescrivait jusqu'à ce lot.
         Le dire précisément vaut mieux qu'une page blanche. */ ?>
<main style="max-width:38rem;margin:4rem auto;padding:0 1.5rem;
             font:16px/1.6 system-ui,sans-serif;color:#262523">
  <h1 style="font:400 1.9rem/1.2 Didot,'Times New Roman',serif;margin:0 0 1rem">
    Le manuel n'est pas sur le serveur.
  </h1>
  <p>
    Le fichier <code>documentation/manuel-administration.html</code> est absent.
    Il fait partie du dépôt&nbsp;; c'est l'envoi qui l'a laissé de côté.
  </p>
  <p>
    <strong>Envoyez le dossier <code>documentation/</code> en entier</strong> —
    il porte son propre <code>.htaccess</code> qui interdit tout accès direct,
    le manuel ne devient donc lisible que depuis cette page, une fois connecté.
  </p>
  <p style="margin-top:2rem">
    <a href="<?= Admin::url('/') ?>" style="color:#7D6134">&larr; Retour au back-office</a>
  </p>
</main>
