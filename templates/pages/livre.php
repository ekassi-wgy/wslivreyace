<?php
/** Gabarit de page — le corps seul ; l'en-tête, la navigation et le pied
    viennent de templates/layout.php. */
$titre       = 'Le livre — Philippe Grégoire Yacé : une destinée';
$description = "Présentation détaillée de l'ouvrage : résumé, fiche technique, sommaire, extraits et points de vente.";
$ld          = <<<'JSONLD'
{
  "@context": "https://schema.org", "@type": "Book",
  "name": "Philippe Grégoire Yacé : une destinée (1920-1998)",
  "inLanguage": "fr", "bookFormat": "https://schema.org/Hardcover"
}
JSONLD;
?>

<!-- ===================== EN-TÊTE DE PAGE ===================== -->
<section class="page-head">
  <div class="shell">
    <div class="row">
      <div class="col-lg-2"><p class="section-num reveal">01</p></div>
      <div class="col-lg-8">
        <p class="kicker reveal">Le livre</p>
        <h1 class="t-d1 reveal">Une destinée</h1>
        <p class="t-lead page-head__lead reveal">
          <em>Sous-titre et accroche à fournir par l'éditeur.</em>
        </p>
      </div>
    </div>
    <div class="rule reveal"></div>
  </div>
</section>

<!-- ===================== PRÉSENTATION ===================== -->
<section class="section" style="padding-top: 0;">
  <div class="shell">
    <div class="row" style="row-gap: var(--sp-9);">

      <div class="col-lg-5">
        <span class="frame reveal">
          <img loading="lazy" decoding="async" src="assets/img/couverture.svg"
               width="1200" height="1550" alt="Couverture de l'ouvrage — visuel provisoire">
        </span>
      </div>

      <div class="col-lg-6 offset-lg-1">
        <p class="kicker reveal">Résumé</p>
        <!-- RÉSUMÉ LONG — à rédiger (CDC §4.2) -->
        <p class="t-body reveal">
          <em>Résumé long à fournir.</em> Trois à cinq paragraphes : l'objet du livre,
          la période couverte, les sources mobilisées, ce que l'ouvrage apporte de neuf
          à la connaissance du personnage et de son époque.
        </p>
        <p class="t-body reveal">
          <em>Suite du résumé à fournir.</em>
        </p>

        <div class="rule reveal" style="margin-block: var(--sp-7);"></div>

        <p class="kicker reveal">Mot de l'éditeur</p>
        <p class="t-body reveal"><em>Texte à fournir par la maison d'édition.</em></p>
      </div>

    </div>
  </div>
</section>

<!-- ===================== INFORMATIONS PRATIQUES ===================== -->
<section class="section section--sunk">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">02</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Informations pratiques</p>
        <h2 class="t-d1 reveal">La fiche technique.</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-5 offset-lg-2">
        <!-- FICHE TECHNIQUE — valeurs provisoires (CDC §4.2) -->
        <dl class="specs reveal">
          <div><dt>Auteur</dt><dd>À renseigner</dd></div>
          <div><dt>Éditeur</dt><dd>À renseigner</dd></div>
          <div><dt>Parution</dt><dd>À renseigner</dd></div>
          <div><dt>Langue</dt><dd>Français</dd></div>
        </dl>
      </div>
      <div class="col-lg-5">
        <dl class="specs reveal">
          <div><dt>Format</dt><dd>Relié, 240 × 310 mm</dd></div>
          <div><dt>Pages</dt><dd>À renseigner</dd></div>
          <div><dt>ISBN</dt><dd>À renseigner</dd></div>
          <div><dt>Prix</dt><dd>À renseigner</dd></div>
        </dl>
      </div>
    </div>
  </div>
</section>

<!-- ===================== SOMMAIRE ===================== -->
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">03</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Sommaire</p>
        <h2 class="t-d1 reveal">Table des matières.</h2>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <!-- SOMMAIRE — intitulés et pagination à reprendre de l'ouvrage -->
        <ol class="toc reveal">
          <li><span class="toc__t">Titre de partie à renseigner</span><span class="toc__lead"></span><span class="toc__p">p. —</span></li>
          <li><span class="toc__t">Titre de partie à renseigner</span><span class="toc__lead"></span><span class="toc__p">p. —</span></li>
          <li><span class="toc__t">Titre de partie à renseigner</span><span class="toc__lead"></span><span class="toc__p">p. —</span></li>
          <li><span class="toc__t">Titre de partie à renseigner</span><span class="toc__lead"></span><span class="toc__p">p. —</span></li>
          <li><span class="toc__t">Titre de partie à renseigner</span><span class="toc__lead"></span><span class="toc__p">p. —</span></li>
          <li><span class="toc__t">Titre de partie à renseigner</span><span class="toc__lead"></span><span class="toc__p">p. —</span></li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- ===================== EXTRAIT ===================== -->
<section class="section section--dark" id="extrait">
  <div class="shell">
    <div class="row">
      <div class="col-lg-9 offset-lg-2">
        <p class="kicker reveal">Extrait</p>
        <blockquote class="quote reveal" style="margin:0;">
          Emplacement réservé à un extrait de l'ouvrage,
          à choisir par l'éditeur.
        </blockquote>
        <p class="quote__src reveal">Une destinée — chapitre à préciser</p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== FEUILLETAGE ===================== -->
<section class="section">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">04</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Feuilletage</p>
        <h2 class="t-d1 reveal">Quelques pages.</h2>
      </div>
      <div class="col-lg-3 d-flex align-items-end justify-content-lg-end">
        <a class="link reveal" href="#">Télécharger l'extrait (PDF)</a>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <div class="spread reveal">
          <img loading="lazy" decoding="async" src="assets/img/extrait-1.svg"
               width="1500" height="1000" alt="Double page de l'ouvrage — visuel provisoire">
          <img loading="lazy" decoding="async" src="assets/img/extrait-2.svg"
               width="1500" height="1000" alt="Double page de l'ouvrage — visuel provisoire">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== L'AUTEUR ===================== -->
<section class="section section--sunk" id="auteur">
  <div class="shell">
    <div class="row align-items-center" style="row-gap: var(--sp-9);">
      <div class="col-lg-4 offset-lg-2 order-lg-2">
        <span class="frame reveal">
          <img loading="lazy" decoding="async" src="assets/img/auteur.svg"
               width="1000" height="1250" alt="Portrait de l'auteur — visuel provisoire">
        </span>
      </div>
      <div class="col-lg-5 order-lg-1">
        <p class="section-num reveal">05</p>
        <p class="kicker reveal">L'auteur</p>
        <h2 class="t-d2 reveal" style="margin-bottom: var(--sp-6);">Nom de l'auteur à renseigner</h2>
        <!-- BIOGRAPHIE DE L'AUTEUR — à fournir (CDC §4.3) -->
        <p class="t-body reveal">
          <em>Parcours et motivations à fournir.</em> Quelques lignes sur la démarche :
          pourquoi cet ouvrage, sur quelles sources, avec quels témoins.
        </p>
        <p class="reveal" style="margin-top: var(--sp-6);">
          <a class="link" href="#">Contact presse et interviews</a>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== OÙ ACHETER ===================== -->
<section class="section" id="acheter">
  <div class="shell">
    <div class="row" style="margin-bottom: var(--sp-8);">
      <div class="col-lg-2"><p class="section-num reveal">06</p></div>
      <div class="col-lg-7">
        <p class="kicker reveal">Où se procurer l'ouvrage</p>
        <h2 class="t-d1 reveal" style="margin-bottom: var(--sp-6);">
          En librairie<br>et en ligne.
        </h2>
        <div class="reveal">
          <a class="btn-pgy" href="#">
            Commander en ligne
            <span class="btn-pgy__arrow" aria-hidden="true">&#8594;</span>
          </a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 offset-lg-2">
        <!-- POINTS DE VENTE — à renseigner (CDC §4.2) -->
        <div class="pos row g-0 reveal">
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Abidjan</h3>
            <p class="t-small">Enseigne et adresse à renseigner</p>
          </div>
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Yamoussoukro</h3>
            <p class="t-small">Enseigne et adresse à renseigner</p>
          </div>
          <div class="pos__i col-md-4">
            <h3 class="t-d3">Paris</h3>
            <p class="t-small">Enseigne et adresse à renseigner</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
