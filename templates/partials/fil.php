<?php
/**
 * Fil d'Ariane, visuel et structuré (brief §9, lot G9).
 *
 * Deux choses en une, et c'est délibéré : la liste que le visiteur voit et le
 * `BreadcrumbList` que Google lit. Les écrire séparément aurait garanti qu'ils
 * divergent — et un balisage qui contredit la page affichée est pire que pas
 * de balisage du tout.
 *
 * Attend `$fil` : un tableau de `[libellé, chemin ou null]`. Le dernier
 * élément est la page courante et ne porte pas de lien.
 *
 * @var array<int,array{0:string,1:?string}> $fil
 */

use App\Core\Langue;
use App\Core\Site;
use App\Core\View;

$fil = $fil ?? [];

if ($fil !== []):
    $elements = [];
    $rang = 1;

    foreach ($fil as [$libelle, $chemin]) {
        $element = ['@type' => 'ListItem', 'position' => $rang++, 'name' => $libelle];

        // Le dernier n'a pas d'`item` : la page courante n'a pas à se
        // désigner elle-même dans son propre fil.
        if ($chemin !== null) {
            $element['item'] = Site::url(Langue::chemin($chemin));
        }

        $elements[] = $element;
    }
?>
<nav class="fil reveal" aria-label="<?= t('fil.aria') ?>">
  <ol>
    <?php foreach ($fil as $i => [$libelle, $chemin]): ?>
      <li<?= $chemin === null ? ' aria-current="page"' : '' ?>>
        <?php if ($chemin === null): ?>
          <?= View::e($libelle) ?>
        <?php else: ?>
          <a href="<?= Langue::chemin($chemin) ?>"><?= View::e($libelle) ?></a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
<script type="application/ld+json">
<?= json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => $elements,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php endif; ?>
