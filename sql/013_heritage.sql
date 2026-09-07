-- ============================================================================
-- Philippe Grégoire Yacé — Héritage (brief §6, lot G7)
--
-- Ce qui perpétue aujourd'hui la mémoire de Philippe Grégoire Yacé : un pont,
-- un boulevard, un buste, des décorations, des publications, une chanson.
--
-- POURQUOI UNE TABLE ET NON UNE PAGE. Le cahier des charges d'origine prévoyait
-- une page unique, et c'est ce qui l'a laissée non écrite pendant tout le
-- projet : une page de texte attend que TOUT son texte existe. Le brief §6 en
-- énumère dix sujets qui n'arriveront pas ensemble — le crédit d'une
-- photographie du buste ne dépend pas de la liste des décorations. Une table
-- laisse publier sujet par sujet, à mesure que la matière arrive.
--
-- POURQUOI PAS `archive`. Une notice d'archive est une PIÈCE du fonds — datée,
-- créditée, cataloguée. Un lieu de mémoire n'est pas une pièce : il existe
-- aujourd'hui, il se visite, et sa photographie n'est qu'une illustration.
-- Les mêler aurait fait remonter le pont de Marcory dans les résultats de
-- recherche du fonds documentaire.
--
-- LES ADRESSES SONT PLATES : `/heritage/pont-philippe-gregoire-yace` et non
-- `/heritage/lieux/pont-…`. Elles sont destinées à l'impression — plaque, QR
-- code, dossier de presse — et chaque segment compte. La rubrique reste un
-- regroupement d'affichage, pas un niveau d'adresse.
--   Réservation : si une rubrique demandait un jour sa page, elle prendrait
--   `/heritage/rubrique/{cle}` — jamais `/heritage/{cle}`, qui entrerait en
--   collision avec un slug de sujet.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS heritage (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(200) NOT NULL,
  slug        VARCHAR(200) NOT NULL,
  rubrique    ENUM('lieux','hommages','decorations','publications','culture')
              NOT NULL DEFAULT 'lieux',

  sous_titre  VARCHAR(300) NULL,       -- une ligne sous le titre, sur l'index
  description MEDIUMTEXT   NULL,

  lieu        VARCHAR(200) NULL,       -- pour les lieux de mémoire
  date_texte  VARCHAR(60)  NULL,       -- « inauguré en 2003 »
  annee       SMALLINT UNSIGNED NULL,  -- classement et repérage

  -- Le sourçage vaut ici comme partout : le CDC §6 l'exige, et l'écran de
  -- publication refuse de publier sans lui.
  source      VARCHAR(300) NULL,
  credit      VARCHAR(200) NULL,

  -- La chanson « YACÉ » de Reine Pélagie, une captation de commémoration :
  -- même règle que les archives, la vidéo reste chez son hébergeur.
  video_url   VARCHAR(500) NULL,

  -- Ordre manuel dans sa rubrique : ce n'est ni chronologique ni
  -- alphabétique — c'est l'éditeur qui sait ce qui vient en premier.
  ordre       SMALLINT NOT NULL DEFAULT 0,

  statut      ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uk_heritage_slug (slug),
  KEY ix_heritage_public (statut, rubrique, ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Les illustrations d'un sujet. Même forme que `archive_media`, et pour la
-- même raison : un lieu se montre sous plusieurs angles.
CREATE TABLE IF NOT EXISTS heritage_media (
  heritage_id INT UNSIGNED NOT NULL,
  media_id    INT UNSIGNED NOT NULL,
  ordre       SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (heritage_id, media_id),
  KEY ix_heritage_media_ordre (heritage_id, ordre),
  CONSTRAINT fk_heritage_media_sujet FOREIGN KEY (heritage_id)
    REFERENCES heritage (id) ON DELETE CASCADE,
  CONSTRAINT fk_heritage_media_media FOREIGN KEY (media_id)
    REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
