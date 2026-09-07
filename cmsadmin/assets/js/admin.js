/**
 * Comportements de l'interface d'administration.
 *
 * Condensé de template.js / off-canvas.js / hoverable-collapse.js du thème
 * Star Admin. Tout ce qui pilotait les pages de démonstration a été retiré :
 * menu horizontal, panneau de réglages, fil de discussion, sélecteur de date,
 * champ de recherche de la barre, case « tout cocher ».
 *
 * Une omission volontaire : le thème marquait l'entrée de menu active en
 * comparant le nom de fichier de l'URL. Nos URL n'ont pas d'extension et la
 * route est connue de PHP — c'est le gabarit qui pose la classe `active`.
 */
(function ($) {
  'use strict';

  $(function () {
    var body    = $('body');
    var sidebar = $('.sidebar');

    // Un seul sous-menu ouvert à la fois.
    sidebar.on('show.bs.collapse', '.collapse', function () {
      sidebar.find('.collapse.show').collapse('hide');
    });

    // Barre latérale fixe : ascenseur discret sur la liste des entrées.
    if (body.hasClass('sidebar-fixed') && $('#sidebar .nav').length) {
      new PerfectScrollbar('#sidebar .nav');
    }

    // Bouton « réduire » : bascule en mode icônes seules.
    $('[data-bs-toggle="minimize"]').on('click', function () {
      body.toggleClass('sidebar-icon-only');
    });

    // Mobile : la barre latérale sort par-dessus le contenu.
    $('[data-bs-toggle="offcanvas"]').on('click', function () {
      $('.sidebar-offcanvas').toggleClass('active');
    });

    /**
     * Suppression : demander confirmation.
     *
     * Posé ici et non sur les écrans de liste : la médiathèque supprime depuis
     * une planche et depuis une fiche, ni l'une ni l'autre n'étant un tableau.
     *
     * Le garde-fou réel est côté serveur — POST et jeton CSRF. Celui-ci ne
     * protège que d'un clic malheureux, et il ne doit pas être le seul :
     * JavaScript désactivé, la suppression reste possible et c'est voulu.
     */
    $(document).on('submit', 'form[data-confirmation]', function (e) {
      if (!window.confirm($(this).data('confirmation'))) {
        e.preventDefault();
      }
    });
  });

  // Mode icônes seules : le sous-menu se déploie au survol, jamais au toucher.
  $(document).on('mouseenter mouseleave', '.sidebar .nav-item', function (ev) {
    if ('ontouchstart' in document.documentElement) { return; }
    if (!$('body').hasClass('sidebar-icon-only')) { return; }
    $(this).toggleClass('hover-open', ev.type === 'mouseenter');
  });

})(jQuery);

/* --- Filtre de la planche a cases (selecteur de fichiers d'une notice) ----
   Cote navigateur et non cote serveur : le formulaire est en cours de saisie,
   et une recherche qui rechargerait la page ferait perdre ce qui n'est pas
   encore enregistre. Le lot propose est borne, le filtre se contente donc de
   masquer des tuiles deja rendues. */
(function () {
  var champ = document.querySelector("[data-filtre-choix]");
  if (!champ) { return; }

  var planche = document.querySelector(champ.dataset.filtreChoix);
  var vide = document.querySelector("[data-filtre-vide]");
  if (!planche) { return; }

  var tuiles = Array.prototype.slice.call(planche.querySelectorAll("[data-etiquette]"));

  champ.addEventListener("input", function () {
    var cherche = champ.value.trim().toLowerCase();
    var visibles = 0;

    tuiles.forEach(function (tuile) {
      /* Une case cochee reste visible quoi qu'il arrive : la masquer donnerait
         a croire qu'on l'a retiree, alors qu'elle sera bien enregistree. */
      var cochee = tuile.querySelector("input").checked;
      var trouve = cherche === "" || tuile.dataset.etiquette.indexOf(cherche) !== -1;

      tuile.hidden = !(cochee || trouve);
      if (!tuile.hidden) { visibles++; }
    });

    if (vide) { vide.hidden = visibles > 0; }
  });
})();
