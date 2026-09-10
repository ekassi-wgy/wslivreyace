<?php
/**
 * Page servie quand la configuration d'environnement manque.
 *
 * **Sans dépendance, volontairement.** Ni jetons de design, ni feuille du
 * site, ni polices distantes, ni lexique : elle doit s'afficher quand le reste
 * n'est pas en état, y compris au milieu d'un envoi FTP à moitié terminé. Les
 * couleurs sont donc écrites en clair — ce sont celles de `tokens.css`, et
 * c'est le seul endroit du projet où les recopier se justifie.
 *
 * **Elle ne dit rien de la cause.** Le détail part dans le journal d'erreurs
 * du serveur ; l'annoncer ici renseignerait un visiteur sur ce qui manque.
 *
 * L'en-tête HTTP est posé par `src/bootstrap.php`, qui l'appelle : 503 et non
 * 500. Un 500 dit « cette page est cassée », un 503 dit « le service est
 * momentanément indisponible, reviens » — et c'est ce second message que les
 * moteurs doivent recevoir, un 500 répété finissant par désindexer.
 */
?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Site momentanément indisponible — Philippe Grégoire Yacé</title>
<style>
  html { -webkit-text-size-adjust: 100%; }
  body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.5rem;
    background: #F7F4EE;
    color: #262523;
    font-family: "Jost", "Futura", "Century Gothic", system-ui, sans-serif;
    line-height: 1.7;
  }
  main { max-width: 34rem; }
  h1 {
    margin: 0 0 1.75rem;
    font-family: "Bodoni Moda", "Didot", "Times New Roman", serif;
    font-weight: 400;
    font-size: clamp(1.75rem, 4.5vw, 2.75rem);
    line-height: 1.15;
  }
  .filet { width: 3.5rem; height: 1px; background: #A88B5C; margin-bottom: 2rem; }
  p { margin: 0 0 1rem; color: #4D4D4D; }
  .mention {
    margin-top: 2.5rem;
    font-size: 0.75rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #6B6862;
  }
</style>
</head>
<body>
  <main>
    <div class="filet"></div>
    <h1>Le site est momentanément indisponible.</h1>
    <p>Une intervention technique est en cours. Le site revient dans quelques instants.</p>
    <p>Merci de votre patience.</p>
    <p class="mention">Philippe Grégoire Yacé — Une destinée</p>
  </main>
</body>
</html>
