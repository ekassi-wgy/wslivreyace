<?php
/** Navigation. $page porte la clé de la page courante pour aria-current. */
$liens = [
    'livre'    => ['/le-livre',    "Le livre"],
    'bio'      => ['/biographie',  "Biographie"],
    'archives' => ['/#galerie',    "Archives"],
    'actus'    => ['/#actualites', "Actualités"],
];
$page = $page ?? '';
?>
<header class="nav-bar">
  <div class="shell">
    <div class="nav-bar__row">

      <a class="logo" href="/" aria-label="Philippe Grégoire Yacé — accueil">
        <span class="logo__mark">PGY</span>
        <span class="logo__rule" aria-hidden="true"></span>
        <span class="logo__text"><b>Philippe Grégoire</b><span>YACÉ</span></span>
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
