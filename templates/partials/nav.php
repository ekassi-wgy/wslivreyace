<?php
/**
 * Navigation. $page porte la clé de la page courante pour aria-current.
 *
 * **« Accueil » est une entrée à part entière depuis le lot G0**, et non plus
 * le seul logotype. Sur un site de campagne on arrive par la page d'accueil ;
 * sur un site de référence on arrive par un lien profond — une archive
 * partagée, un discours cité — et le retour doit se nommer.
 *
 * **Les sept entrées du brief y sont depuis le lot G7**, « Héritage » ayant
 * rejoint la barre avec sa rubrique. Seul « Commander » ne pointe pas encore
 * sur le tunnel de commande (lot G3) : il mène à la section d'achat de la page
 * du livre.
 */
$liens = [
    'accueil'  => ['/',            "Accueil"],
    'livre'    => ['/le-livre',    "Le livre"],
    'bio'      => ['/biographie',  "Biographie"],
    'archives' => ['/archives',    "Archives"],
    'heritage' => ['/heritage',    "Héritage"],
    'actus'    => ['/actualites',  "Actualités"],
];
$page = $page ?? '';

/**
 * Tout lien interne passe par `Langue::chemin()` (lot G1). En français il rend
 * le chemin inchangé ; sous `/en/`, il le préfixe. Un `href="/le-livre"` écrit
 * en dur ramènerait le visiteur anglophone au français sans le dire.
 */
$lien = static fn(string $chemin): string => App\Core\Langue::chemin($chemin);
?>
<header class="nav-bar">
  <div class="shell">
    <div class="nav-bar__row">

      <a class="logo" href="<?= $lien('/') ?>" aria-label="Philippe Grégoire Yacé — accueil">
        <svg class="logo__svg" aria-hidden="true" focusable="false"><use href="#pgy-logo"></use></svg>
      </a>

      <nav aria-label="Navigation principale">
        <ul class="nav-menu" id="navMenu">
<?php foreach ($liens as $cle => [$href, $libelle]): ?>
          <li><a href="<?= $lien($href) ?>"<?= $cle === $page ? ' aria-current="page"' : '' ?>><?= $libelle ?></a></li>
<?php endforeach; ?>
          <?php /* Une loupe et non une huitième entrée : la barre porte déjà
                   les sept que le brief demande, et « Rechercher » n'est pas
                   une rubrique du site (lot G9). */ ?>
          <li><a class="nav-loupe" href="<?= $lien('/recherche') ?>" aria-label="Rechercher dans le site">
            <svg viewBox="0 0 20 20" aria-hidden="true" focusable="false">
              <circle cx="8.5" cy="8.5" r="6" fill="none" stroke="currentColor" stroke-width="1.6"/>
              <line x1="13" y1="13" x2="18" y2="18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </a></li>
          <li><a class="btn-pgy btn-pgy--sm" href="<?= $lien('/le-livre') ?>#acheter">Commander</a></li>
        </ul>
      </nav>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="navMenu" aria-label="Ouvrir le menu">
        <span></span><span></span>
      </button>

    </div>
  </div>
</header>
