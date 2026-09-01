<?php
/**
 * Mise en page unique du site. $contenu est le corps de la page, déjà rendu
 * par View::render. $titre, $description et $ld sont posés par le gabarit de page.
 */
$titre       = $titre       ?? 'Philippe Grégoire Yacé — Une destinée';
$description = $description ?? '';
$ld          = $ld          ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?></title>
<meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">

<?php /* Le SVG sert partout où il est compris ; l'.ico à la racine couvre les
         requêtes automatiques vers /favicon.ico et les navigateurs anciens. */ ?>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="/assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<meta name="theme-color" content="#A88B5C">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,400;6..96,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

<meta property="og:type" content="website">
<meta property="og:locale" content="fr_FR">
<meta property="og:title" content="<?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:card" content="summary_large_image">

<?php if ($ld !== ''): ?>
<script type="application/ld+json">
<?= $ld ?>
</script>
<?php endif; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/css/tokens.css" rel="stylesheet">
<link href="/assets/css/base.css" rel="stylesheet">
<link href="/assets/css/components.css" rel="stylesheet">
</head>

<body>
<a class="skip-link" href="#main">Aller au contenu</a>

<?php require __DIR__ . '/partials/nav.php'; ?>

<main id="main">
<?= $contenu ?>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>
