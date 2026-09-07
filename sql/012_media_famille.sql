-- ============================================================================
-- Philippe Grégoire Yacé — familles de fichiers (lot G5)
--
-- La médiathèque n'acceptait que des images. Le brief §4 demande aussi des
-- documents, des correspondances et des discours enregistrés : ni les uns ni
-- les autres ne tiennent en JPEG.
--
-- Trois familles, et la colonne le dit :
--
--   image     JPEG, PNG, WebP — vignette et taille moyenne fabriquées
--   document  PDF — ni vignette, ni dimensions ; se télécharge
--   audio     MP3, M4A, OGG — ni vignette, ni dimensions ; se lit dans la page
--
-- Pourquoi une colonne plutôt qu'une déduction sur l'extension : la question
-- « que sait-on faire de ce fichier ? » se pose à chaque affichage, dans la
-- planche du back-office comme dans la page publique. La déduire à chaque fois
-- reviendrait à écrire la même table de correspondance à cinq endroits.
--
-- La valeur par défaut est `image` : toutes les lignes existantes en sont,
-- puisque rien d'autre ne pouvait entrer avant ce lot.
--
-- La vidéo n'a pas de famille ici : elle reste chez son hébergeur, et la
-- notice d'archive porte son adresse (décision 2 du brief, README §9).
--
-- À jouer une seule fois. `ADD COLUMN` lève `Duplicate column name` si le
-- fichier est rejoué, ce qui vaut mieux qu'une modification silencieuse.
-- ============================================================================

SET NAMES utf8mb4;

ALTER TABLE media
  ADD COLUMN famille ENUM('image','document','audio') NOT NULL DEFAULT 'image' AFTER fichier;

-- L'index de la galerie publique tient compte de la famille : une planche de
-- photographies n'a pas à parcourir les PDF pour les écarter.
ALTER TABLE media
  ADD KEY ix_media_famille (famille, statut);
