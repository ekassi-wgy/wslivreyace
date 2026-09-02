-- ============================================================================
-- Philippe Grégoire Yacé — médiathèque (lot D2)
--
-- La table `media` existe depuis le schéma initial ; le lot D2 lui ajoute ce
-- que le téléversement mesure et ce que l'unicité impose.
--
-- À jouer une seule fois, sur une base déjà installée. Une base créée à partir
-- de 001_schema.sql porte déjà ces deux modifications : elles y ont été
-- reportées, pour qu'une installation neuve n'ait pas à rejouer l'historique.
-- MySQL ne connaît pas `ADD COLUMN IF NOT EXISTS` : rejouer ce fichier lève
-- une erreur, ce qui est préférable à une modification silencieuse.
-- ============================================================================

SET NAMES utf8mb4;

-- Poids du fichier, affiché dans la médiathèque. L'éditeur repère ainsi le
-- scan de 11 Mo déposé tel quel, qui alourdira la galerie publique.
ALTER TABLE media
  ADD COLUMN octets INT UNSIGNED NULL AFTER hauteur;

-- Un même chemin ne peut désigner qu'une ligne : le nom est fabriqué au dépôt
-- avec huit caractères aléatoires, une collision serait le signe d'un bogue et
-- non d'un doublon légitime. La contrainte le fait dire à la base.
ALTER TABLE media
  ADD UNIQUE KEY uk_media_fichier (fichier);
