-- ============================================================================
-- Philippe Grégoire Yacé — notices d'archives (lot G4)
--
-- LA DÉCISION QUI COMMANDE TOUT LE RESTE (brief §4, décision 1 du README §9)
--
-- Jusqu'ici, une ligne de `media` = un fichier image. Suffisant pour une
-- galerie, pas pour un fonds. Un discours de 1980, c'est une pièce unique qui
-- porte un contexte historique, une vidéo, un enregistrement, une
-- transcription et un document scanné : cinq choses sous une seule adresse.
--
-- D'où deux niveaux, et non un :
--
--   `archive`       la NOTICE — ce qui se catalogue, se date, se situe, se
--                   cite et se partage. Elle a un slug, donc une adresse.
--   `archive_media` ce qu'elle PORTE — un fichier, ou vingt.
--   `media`         inchangée : elle reste le magasin de fichiers, avec son
--                   téléversement contrôlé et ses tailles dérivées.
--
-- Ce seul choix règle d'un coup la galerie photo (plusieurs images sous une
-- notice), le discours, le document, la correspondance, et l'exigence « une
-- URL par pièce » du §9 du brief.
--
-- POURQUOI `media` N'EST PAS TOUCHÉE. Elle marche, elle est éprouvée, et elle
-- répond à une autre question : « quels fichiers ai-je déposés ? ». La notice
-- répond à « qu'est-ce que cette pièce, et d'où vient-elle ? ». Les fondre
-- aurait obligé à choisir entre les deux.
--
-- LES CATÉGORIES SONT LEURS PROPRES ADRESSES. `discours` donne
-- `/archives/discours/…`. Les clés sont donc au pluriel là où le français
-- l'exige : elles sont imprimables, et la décision 3 dit qu'une adresse
-- imprimée ne bouge plus jamais.
--
-- À jouer une seule fois.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS archive (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(200) NOT NULL,
  slug        VARCHAR(200) NOT NULL,
  categorie   ENUM('photographies','videos','discours','documents','presse','correspondances')
              NOT NULL DEFAULT 'photographies',

  -- --- Le catalogue : ce que le brief demande de renseigner « si disponible »
  description MEDIUMTEXT   NULL,
  lieu        VARCHAR(200) NULL,
  personnes   VARCHAR(500) NULL,   -- « personnes présentes », saisi en clair
  mots_cles   VARCHAR(500) NULL,   -- séparés par des virgules ; sert la recherche

  -- La date d'une archive est souvent imprécise. `date_texte` s'affiche
  -- (« vers 1965 », « mars 1980 »), `annee` classe et filtre. Les deux
  -- coexistent pour la même raison que `annee`/`tri` sur les repères.
  date_texte  VARCHAR(60)  NULL,
  annee       SMALLINT UNSIGNED NULL,

  -- Le crédit n'est pas décoratif : le CDC §6 l'exige, et l'écran de
  -- publication refuse de publier sans lui.
  source      VARCHAR(300) NULL,
  credit      VARCHAR(200) NULL,

  -- --- Propre aux discours (brief §4) --------------------------------------
  -- Portés par la notice et non par une table à part : ce sont trois champs de
  -- texte, et une table de plus pour trois colonnes nullables coûterait une
  -- jointure à chaque lecture sans rien apporter.
  contexte      MEDIUMTEXT NULL,   -- « contexte historique »
  transcription MEDIUMTEXT NULL,   -- « transcription intégrale »

  -- La vidéo n'est pas hébergée ici (décision 2) : le site porte la notice, la
  -- plateforme porte les octets. Une URL suffit donc, et non un fichier.
  video_url   VARCHAR(500) NULL,

  statut      ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uk_archive_slug (slug),
  KEY ix_archive_public (statut, categorie, annee),
  KEY ix_archive_annee (statut, annee)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Les fichiers que porte une notice --------------------------------------
-- `ordre` fixe la suite d'une galerie, et le premier fait l'image de partage.
-- Clés étrangères des deux côtés : une notice supprimée emporte ses liens, un
-- fichier supprimé de la médiathèque disparaît des notices qui l'affichaient
-- — sans quoi la page publique servirait une image cassée.
CREATE TABLE IF NOT EXISTS archive_media (
  archive_id INT UNSIGNED NOT NULL,
  media_id   INT UNSIGNED NOT NULL,
  ordre      SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (archive_id, media_id),
  KEY ix_archive_media_ordre (archive_id, ordre),
  CONSTRAINT fk_archive_media_archive FOREIGN KEY (archive_id)
    REFERENCES archive (id) ON DELETE CASCADE,
  CONSTRAINT fk_archive_media_media FOREIGN KEY (media_id)
    REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
