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
  });

  // Mode icônes seules : le sous-menu se déploie au survol, jamais au toucher.
  $(document).on('mouseenter mouseleave', '.sidebar .nav-item', function (ev) {
    if ('ontouchstart' in document.documentElement) { return; }
    if (!$('body').hasClass('sidebar-icon-only')) { return; }
    $(this).toggleClass('hover-open', ev.type === 'mouseenter');
  });

})(jQuery);
