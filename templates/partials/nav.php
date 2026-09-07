<?php
/**
 * Navigation. $page porte la clé de la page courante pour aria-current.
 *
 * **« Accueil » est une entrée à part entière depuis le lot G0**, et non plus
 * le seul logotype. Sur un site de campagne on arrive par la page d'accueil ;
 * sur un site de référence on arrive par un lien profond — une archive
 * partagée, un discours cité — et le retour doit se nommer.
 *
 * Le brief demande sept entrées (README §9). Deux manquent encore et ne
 * peuvent pas précéder ce qu'elles ouvrent : « Héritage » arrive avec sa
 * rubrique (lot G7), et « Commander » ne pointera sur le tunnel qu'au lot G3 —
 * il mène pour l'instant à la section d'achat de la page du livre.
 */
$liens = [
    'accueil'  => ['/',            "Accueil"],
    'livre'    => ['/le-livre',    "Le livre"],
    'bio'      => ['/biographie',  "Biographie"],
    'archives' => ['/archives',    "Archives"],
    'actus'    => ['/actualites',  "Actualités"],
];
$page = $page ?? '';
?>
<header class="nav-bar">
  <div class="shell">
    <div class="nav-bar__row">

      <a class="logo" href="/" aria-label="Philippe Grégoire Yacé — accueil">
        <svg class="logo__svg" aria-hidden="true" focusable="false"><use href="#pgy-logo"></use></svg>
      </a>

      <nav aria-label="Navigation principale">
        <ul class="nav-menu" id="navMenu">
<?php foreach ($liens as $cle => [$href, $libelle]): ?>
          <li><a href="<?= $href ?>"<?= $cle === $page ? ' aria-current="page"' : '' ?>><?= $libelle ?></a></li>
<?php endforeach; ?>
          <li><a class="btn-pgy btn-pgy--sm" href="/le-livre#acheter">Commander</a></li>
        </ul>
      </nav>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="navMenu" aria-label="Ouvrir le menu">
        <span></span><span></span>
      </button>

    </div>
  </div>
</header>
