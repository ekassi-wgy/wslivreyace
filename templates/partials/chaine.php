<?php
/**
 * Le lien vers la chaîne WhatsApp, sous ses deux formes.
 *
 * Il paraît à deux endroits — le pied de page et la page des actualités — et
 * c'est pourquoi il vit ici plutôt que recopié dans les deux gabarits : le
 * jour où l'adresse change, ou où la chaîne ferme, il n'y a qu'un endroit à
 * rouvrir. Même raison que `points-de-vente.php`.
 *
 * L'adresse vient de `config/config.php`. **Vide, rien ne s'affiche** : ni le
 * bloc du pied, ni le bandeau. Fermer le canal, c'est vider la ligne de
 * configuration, pas retoucher deux gabarits.
 *
 * Il ne sert plus qu'au bandeau des actualités : la ligne du pied a rejoint la
 * rangée de `reseaux.php` le jour où Facebook, Instagram et YouTube sont
 * arrivés. Le texte reste ici parce qu'il est propre à la chaîne — un bouton
 * de rangée n'aurait pas pu porter « diffusion seule, aucun numéro n'est
 * visible », et c'est cette phrase qui lève l'hésitation à s'abonner.
 *
 * **Le glyphe est servi en `currentColor`, jamais dans le vert de la marque
 * WhatsApp.** Le fichier de jetons ne reconnaît qu'un accent, le laiton ; un
 * #25D366 posé sur ce papier serait la couleur la plus saturée du site et
 * ferait basculer une plaque commémorative en widget. La forme du glyphe
 * suffit à la reconnaissance — c'est le même raisonnement que pour le
 * logotype, qui prend lui aussi l'encre de son contexte.
 *
 * Contrairement au logotype, le dessin n'est pas défini en `<symbol>` : il
 * pèse 1,1 Ko contre 15 Ko, il ne paraît que deux fois au plus dans une page,
 * et gzip absorbe la répétition d'une chaîne identique. Le `<symbol>` ne
 * gagnerait ici que de l'indirection.
 */

use App\Core\Config;
use App\Core\View;

$urlChaine = trim((string) (Config::get('reseaux')['whatsapp_chaine'] ?? ''));

if ($urlChaine !== ''):
    /* `target="_blank"` ici, alors qu'un lien sortant d'article s'en passe : ce
       lien ne mène pas à une page, il passe la main à une application. Sans
       nouvel onglet, le visiteur qui revient trouve WhatsApp à la place du
       site. `noopener` dans tous les cas. */
    $sortant = ' target="_blank" rel="noopener"';

    /* Le glyphe est décoratif : le libellé du lien porte déjà le sens, et le
       répéter à la synthèse vocale ferait « WhatsApp WhatsApp ». */
    $glyphe = '<svg class="chaine__glyphe" viewBox="0 0 24 24" aria-hidden="true" focusable="false">'
            . '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>';
?>

  <?php /* Un filet et un fond légèrement surélevé, pas une carte à ombre :
           c'est la grammaire de `.pos`, et le fichier de composants proscrit
           l'ombre portée diffuse. */ ?>
  <a class="chaine-bande reveal" href="<?= View::e($urlChaine) ?>"<?= $sortant ?>
     aria-label="<?= t('chaine.aria') ?>">
    <span class="chaine-bande__txt">
      <?= $glyphe ?>
      <span>
        <span class="chaine-bande__t"><?= t('chaine.titre') ?></span>
        <span class="chaine-bande__p"><?= t('chaine.texte') ?></span>
      </span>
    </span>
    <?php /* Un `<span>` et non un `<a>` : le bandeau entier est déjà le lien,
             et un lien dans un lien n'est pas un balisage valide. */ ?>
    <span class="btn-pgy btn-pgy--sm">
      <?= t('chaine.rejoindre') ?>
      <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
    </span>
  </a>

<?php endif; ?>
