/**
 * Médiathèque : retour visuel sur le dépôt, et sélecteur d'image des fiches.
 *
 * Rien d'indispensable ici. Sans JavaScript, le formulaire de dépôt fonctionne
 * (c'est un <input type="file"> ordinaire) et une fiche garde l'image qu'elle
 * porte — on ne peut simplement plus en changer. Ce fichier n'est chargé que
 * sur les deux écrans qui s'en servent.
 */
(function ($) {
  'use strict';

  $(function () {

    /* --- Dépôt : dire combien de fichiers sont sélectionnés ---------------
       Le navigateur affiche « 12 fichiers » dans un contrôle minuscule, et le
       poids total — qui est ce qui fait échouer l'envoi — n'y figure pas. */
    $('[data-depot-fichiers]').on('change', function () {
      var fichiers = this.files || [];
      var $note = $(this).closest('form').find('[data-depot-compte]');
      var octets = 0;
      var i;

      if (!fichiers.length) {
        $note.text('Sélection multiple possible : maintenez ⌘ (ou Ctrl) en cliquant.');
        return;
      }

      for (i = 0; i < fichiers.length; i++) { octets += fichiers[i].size; }

      $note.text(
        fichiers.length + (fichiers.length > 1 ? ' fichiers' : ' fichier') +
        ' — ' + (octets / 1048576).toFixed(1).replace('.', ',') + ' Mo au total'
      );
    });

    /* --- Sélecteur d'image ------------------------------------------------
       La boîte de dialogue est partagée : plusieurs champs image pourraient
       coexister sur une même fiche. On retient donc lequel l'a ouverte. */
    var $cible = null;

    $('#selecteur-media').on('show.bs.modal', function (ev) {
      $cible = $(ev.relatedTarget).closest('[data-selecteur-media]');
    });

    $('#selecteur-media').on('click', '[data-media-choix]', function () {
      if (!$cible || !$cible.length) { return; }

      var $bouton = $(this);
      var $img = $('<img>').attr({ src: $bouton.data('vignette'), alt: $bouton.data('alt') });

      $cible.find('[data-media-valeur]').val($bouton.data('fichier'));
      $cible.find('[data-media-apercu]').empty().append($img).append(
        $('<span>').text($bouton.data('titre'))
      );
      $cible.find('[data-media-retirer]').prop('hidden', false);

      bootstrap.Modal.getInstance(document.getElementById('selecteur-media')).hide();
    });

    $('[data-media-retirer]').on('click', function () {
      var $champ = $(this).closest('[data-selecteur-media]');

      $champ.find('[data-media-valeur]').val('');
      $champ.find('[data-media-apercu]').empty().append(
        $('<span class="pgy-choix__vide">').text('Aucune image')
      );
      $(this).prop('hidden', true);
    });

  });

})(jQuery);
