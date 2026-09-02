<?php
/**
 * Mentions légales et politique de confidentialité (CDC §4.12).
 *
 * **Ce qui est écrit ici doit être vrai.** Les sections « données », « cookies »
 * et « services tiers » décrivent le comportement réel du site, vérifié dans le
 * code : un cookie de session posé sur les deux pages qui portent un
 * formulaire et nulle part ailleurs, aucune mesure d'audience, et deux
 * ressources chargées depuis des domaines tiers. Elles ne sont pas recopiées
 * d'un modèle.
 *
 * Ce qui relève de l'état civil de la structure éditrice — raison sociale,
 * registre, directeur de la publication, hébergeur — ne peut pas s'inventer et
 * reste balisé comme à fournir. Voir le §5 du README.
 *
 * Les coordonnées viennent de la configuration, comme sur la page Contact :
 * une adresse recopiée à deux endroits finit par diverger.
 */

use App\Core\View;

$titre       = 'Mentions légales — Philippe Grégoire Yacé : une destinée';
$description = "Éditeur, hébergement, propriété intellectuelle, données personnelles et "
             . 'cookies du site consacré à Philippe Grégoire Yacé.';

$adresse = trim((string) ($contact['adresse'] ?? ''));
$ville   = trim((string) ($contact['ville'] ?? ''));
$pays    = trim((string) ($contact['pays'] ?? ''));
$email   = trim((string) ($contact['email'] ?? ''));
$tel     = trim((string) ($contact['telephone'] ?? ''));
$site    = trim((string) ($contact['site'] ?? ''));

$postale = implode(', ', array_filter([$adresse, $ville, $pays]));
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Mentions légales</p>
        <h1 class="t-d1 reveal">Qui édite ce site.</h1>
        <p class="t-lead page-head__lead reveal">
          L'éditeur, l'hébergement, les droits sur les contenus, et ce que ce
          site fait — ou ne fait pas — de vos données.
        </p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">

        <div class="chap reveal" id="editeur">
          <span class="chap__num">01</span>
          <h2 class="t-d3">Éditeur du site</h2>
          <p class="t-body">
            <em>Raison sociale, forme juridique et numéro d'immatriculation à
            fournir par la structure éditrice.</em>
          </p>
          <?php if ($postale !== ''): ?>
            <p class="t-body"><?= View::e($postale) ?></p>
          <?php endif; ?>
          <p class="t-body">
            <?php if ($email !== ''): ?>
              Courriel&nbsp;: <a class="link" href="mailto:<?= View::e($email) ?>"><?= View::e($email) ?></a><br>
            <?php endif; ?>
            <?php if ($tel !== ''): ?>Téléphone&nbsp;: <?= View::e($tel) ?><br><?php endif; ?>
            <?php if ($site !== ''): ?>Site&nbsp;: <?= View::e($site) ?><?php endif; ?>
          </p>
          <p class="t-body">
            <strong>Directeur de la publication&nbsp;:</strong>
            <em>nom et qualité à fournir.</em>
          </p>
        </div>

        <div class="chap reveal" id="hebergement">
          <span class="chap__num">02</span>
          <h2 class="t-d3">Hébergement</h2>
          <p class="t-body">
            <em>Nom, adresse postale et téléphone de l'hébergeur à fournir</em> —
            ils dépendent du prestataire retenu, et la loi impose de les
            publier.
          </p>
        </div>

        <div class="chap reveal" id="propriete">
          <span class="chap__num">03</span>
          <h2 class="t-d3">Propriété intellectuelle</h2>
          <p class="t-body">
            Les textes, la charte graphique et le logotype de ce site sont
            protégés. Leur reproduction, même partielle, suppose l'accord écrit
            de l'éditeur.
          </p>
          <p class="t-body">
            Les <strong>photographies et documents d'archives</strong> restent la
            propriété de leurs détenteurs de droits. Chacun paraît avec la
            mention de sa provenance, sur la page où il s'affiche&nbsp;: une
            pièce sans crédit n'est pas publiée. Toute réutilisation demande
            l'accord du détenteur, pas seulement celui de l'éditeur.
          </p>
          <p class="t-body">
            Les <strong>témoignages</strong> appartiennent à celles et ceux qui
            les ont écrits. Ils sont publiés avec leur accord, sous leur nom, et
            se retirent sur simple demande.
          </p>
        </div>

        <div class="chap reveal" id="donnees">
          <span class="chap__num">04</span>
          <h2 class="t-d3">Données personnelles</h2>
          <p class="t-body">
            Ce site ne collecte que ce que vous lui écrivez, et seulement par
            ses deux formulaires.
          </p>
          <p class="t-body">
            <strong>Formulaire de témoignage.</strong> Nom, qualité si vous
            l'indiquez, adresse électronique et texte du témoignage.
            <strong>L'adresse électronique n'est jamais publiée</strong>&nbsp;:
            elle sert à vous recontacter avant publication, parce que des propos
            paraissent sous votre nom. Rien n'est mis en ligne sans relecture.
          </p>
          <p class="t-body">
            <strong>Formulaire de contact.</strong> Nom, adresse électronique,
            motif et message. Ils servent à vous répondre, et à rien d'autre. Ces
            messages ne sont jamais publiés.
          </p>
          <p class="t-body">
            <strong>Adresse IP.</strong> Elle est relevée à chaque envoi de
            formulaire, pour repérer les abus et faire tenir le plafond de cinq
            envois par heure. Le journal qui sert à ce comptage est
            <strong>purgé au bout d'un jour</strong>. L'adresse enregistrée avec
            un témoignage ou un message ne quitte jamais la base et ne s'affiche
            nulle part.
          </p>
          <p class="t-body">
            <strong>Durées.</strong> Un témoignage publié le reste tant que le
            site existe. Un message de contact est conservé le temps d'y
            répondre, puis supprimé.
          </p>
          <p class="t-body">
            <strong>Vos droits.</strong> Vous pouvez demander à consulter, à
            corriger ou à faire effacer ce qui vous concerne&nbsp;: écrivez
            <?php if ($email !== ''): ?>à
              <a class="link" href="mailto:<?= View::e($email) ?>"><?= View::e($email) ?></a><?php else: ?>
              à l'éditeur<?php endif; ?>. Aucune donnée n'est vendue, louée,
            cédée, ni utilisée pour de la prospection.
          </p>
        </div>

        <div class="chap reveal" id="cookies">
          <span class="chap__num">05</span>
          <h2 class="t-d3">Cookies</h2>
          <p class="t-body">
            <strong>Ce site ne mesure pas son audience</strong> et ne dépose
            aucun cookie publicitaire ou de suivi. Il n'y a rien à accepter ni à
            refuser, d'où l'absence de bandeau.
          </p>
          <p class="t-body">
            Un <strong>unique cookie technique</strong> est posé, et seulement
            sur les deux pages qui portent un formulaire — Témoignages et
            Contact. Il ne contient qu'un identifiant de session, sert à valider
            l'envoi du formulaire contre la fraude et à afficher le message de
            confirmation, et disparaît à la fermeture du navigateur. Les autres
            pages n'en déposent aucun.
          </p>
          <p class="t-body">
            Un second cookie existe pour le back-office. Il ne concerne que les
            personnes qui s'y connectent.
          </p>
        </div>

        <div class="chap reveal" id="tiers">
          <span class="chap__num">06</span>
          <h2 class="t-d3">Services tiers</h2>
          <p class="t-body">
            Deux ressources sont chargées depuis des domaines tiers&nbsp;: les
            polices de caractères depuis <strong>Google Fonts</strong>
            (<code>fonts.googleapis.com</code>, <code>fonts.gstatic.com</code>)
            et une bibliothèque d'affichage depuis <strong>jsDelivr</strong>
            (<code>cdn.jsdelivr.net</code>). Afficher une page transmet donc
            votre adresse IP à ces deux services, qui appliquent leurs propres
            règles. Aucune autre requête ne part vers un tiers.
          </p>
        </div>

        <div class="chap reveal" id="signalement">
          <span class="chap__num">07</span>
          <h2 class="t-d3">Signaler une erreur</h2>
          <p class="t-body">
            Philippe Grégoire Yacé est une figure historique réelle. Si un fait,
            une date ou une image vous paraît inexacte, ou si une pièce publiée
            porte atteinte à un droit dont vous disposez,
            <a class="link" href="/contact">écrivez-nous</a>&nbsp;: la correction
            ou le retrait est immédiat, la discussion vient après.
          </p>
        </div>

      </div>
    </div>
  </div>
</section>
