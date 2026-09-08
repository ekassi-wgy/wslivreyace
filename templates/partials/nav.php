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
 *
 * **Les libellés viennent du lexique depuis le lot G11** : ce sont des textes
 * du site, pas des contenus d'éditeur, et ils vivent donc dans `src/lang/`.
 * Le sélecteur de langue ne paraît que si une seconde langue est ouverte —
 * proposer un choix qui répond 404 serait pire que ne rien proposer.
 */
$liens = [
    'accueil'  => ['/',            'nav.accueil'],
    'livre'    => ['/le-livre',    'nav.livre'],
    'bio'      => ['/biographie',  'nav.biographie'],
    'archives' => ['/archives',    'nav.archives'],
    'heritage' => ['/heritage',    'nav.heritage'],
    'actus'    => ['/actualites',  'nav.actualites'],
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

      <a class="logo" href="<?= $lien('/') ?>" aria-label="<?= t('nav.logo_aria') ?>">
        <svg class="logo__svg" aria-hidden="true" focusable="false"><use href="#pgy-logo"></use></svg>
      </a>

      <nav aria-label="<?= t('nav.principale_aria') ?>">
        <ul class="nav-menu" id="navMenu">
<?php foreach ($liens as $cle => [$href, $cleTexte]): ?>
          <li><a href="<?= $lien($href) ?>"<?= $cle === $page ? ' aria-current="page"' : '' ?>><?= t($cleTexte) ?></a></li>
<?php endforeach; ?>
          <?php /* Une loupe et non une huitième entrée : la barre porte déjà
                   les sept que le brief demande, et « Rechercher » n'est pas
                   une rubrique du site (lot G9). */ ?>
          <li><a class="nav-loupe" href="<?= $lien('/recherche') ?>" aria-label="<?= t('nav.recherche_aria') ?>">
            <svg viewBox="0 0 20 20" aria-hidden="true" focusable="false">
              <circle cx="8.5" cy="8.5" r="6" fill="none" stroke="currentColor" stroke-width="1.6"/>
              <line x1="13" y1="13" x2="18" y2="18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
          </a></li>
          <?php /* Le sélecteur de langue (lot G11). Il n'existe que si une
                   seconde langue est ouverte : tant que l'anglais est fermé,
                   `Langue::multilingue()` est faux et rien ne paraît. Les
                   liens pointent la **même page** dans l'autre langue —
                   `cheminNu()` est le chemin de la requête dépouillé de son
                   préfixe — et non la racine : renvoyer un lecteur à l'accueil
                   parce qu'il change de langue lui fait perdre sa place. */ ?>
<?php if (App\Core\Langue::multilingue()): ?>
          <li>
            <ul class="nav-langues" aria-label="<?= t('nav.langue_aria') ?>">
<?php foreach (App\Core\Langue::ouvertes() as $codeL => $infosL): ?>
              <li><a href="<?= App\Core\Langue::chemin(App\Core\Langue::cheminNu(), $codeL) ?>"
                     hreflang="<?= $codeL ?>"
                     lang="<?= $codeL ?>"
                     <?= $codeL === App\Core\Langue::code() ? 'aria-current="true"' : '' ?>><?= strtoupper($codeL) ?></a></li>
<?php endforeach; ?>
            </ul>
          </li>
<?php endif; ?>
          <li><a class="btn-pgy btn-pgy--sm" href="<?= $lien('/le-livre') ?>#acheter"><?= t('nav.commander') ?></a></li>
        </ul>
      </nav>

      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="navMenu" aria-label="<?= t('nav.ouvrir_menu') ?>">
        <span></span><span></span>
      </button>

    </div>
  </div>
</header>
