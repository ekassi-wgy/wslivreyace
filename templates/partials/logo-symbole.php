<?php
/**
 * Définition du logotype, posée une seule fois par page.
 *
 * Le logo apparaît deux fois — en-tête et pied — et son portrait au trait pèse
 * à lui seul 15 Ko de tracés. L'inclure deux fois doublerait ce poids dans
 * chaque page servie. Il est donc défini ici en `<symbol>`, et les deux
 * emplacements le rappellent par `<use href="#pgy-logo">` : un seul dessin,
 * deux instances.
 *
 * Pourquoi pas un `<img src="logo.svg">`, qui aurait le même effet et serait
 * mis en cache : une image externe n'hérite pas de la couleur du texte. Le
 * logo doit passer de l'encre de l'en-tête au papier du pied ; en `<img>`, il
 * faudrait deux fichiers de 17 Ko au lieu d'un, et les garder d'équerre.
 *
 * Le bloc est retiré du flux sans `display:none`, qui empêcherait `<use>` de
 * résoudre sa référence dans certains navigateurs.
 */
?>
<svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;width:0;height:0;overflow:hidden"
     aria-hidden="true" focusable="false">
  <?php /* `fill` est porté par le symbole et non par chaque tracé : c'est une
           propriété héritée, et dans l'arbre d'ombre d'un <use> elle se résout
           contre la couleur du contexte appelant. C'est ce qui fait passer le
           logo de l'encre de l'en-tête au papier du pied sans second fichier —
           l'omettre le laisse en noir par défaut, invisible sur fond sombre. */ ?>
  <symbol id="pgy-logo" viewBox="193 530 2613 941" fill="currentColor">
    <?php
    /* Les tracés viennent d'assets/img/logo.svg, fichier de référence de la
       marque : le lire évite d'en tenir une seconde copie à jour ici. */
    $fichier = dirname(__DIR__, 2) . '/assets/img/logo.svg';
    $source  = is_file($fichier) ? file_get_contents($fichier) : '';

    if (preg_match_all('/<path\b[^>]*>/', $source, $tracés)) {
        echo implode('', $tracés[0]);
    }
    ?>
  </symbol>
</svg>
