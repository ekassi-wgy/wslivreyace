/**
 * Écrans de liste : tri, recherche, pagination.
 *
 * DataTables est livré avec ses libellés en anglais et charge normalement sa
 * traduction depuis un CDN. L'admin n'émet aucune requête sortante : les
 * libellés sont donc écrits ici.
 */
(function ($) {
  'use strict';

  var LIBELLES = {
    emptyTable:     'Aucune entrée',
    info:           'Entrées _START_ à _END_ sur _TOTAL_',
    infoEmpty:      'Aucune entrée',
    infoFiltered:   '(filtré sur _MAX_ entrées au total)',
    lengthMenu:     'Afficher _MENU_ entrées',
    loadingRecords: 'Chargement…',
    processing:     'Traitement…',
    search:         'Rechercher :',
    zeroRecords:    'Aucune entrée ne correspond',
    paginate: {
      first:    'Première',
      last:     'Dernière',
      next:     'Suivante',
      previous: 'Précédente'
    },
    aria: {
      sortAscending:  ' : trier par ordre croissant',
      sortDescending: ' : trier par ordre décroissant'
    }
  };

  $(function () {

    $('table[data-liste]').each(function () {
      var $t = $(this);

      $t.DataTable({
        language: LIBELLES,
        pageLength: 25,
        lengthChange: false,
        // La colonne de tri initiale et son sens sont déclarés par le gabarit :
        // une frise se lit dans l'ordre croissant, une liste d'actualités dans
        // l'ordre inverse.
        order: [[
          parseInt($t.data('tri-colonne'), 10) || 0,
          $t.data('tri-sens') || 'desc'
        ]],
        columnDefs: [{
          targets: 'no-sort',
          orderable: false
        }]
      });
    });

  });

})(jQuery);
