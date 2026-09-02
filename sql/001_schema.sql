-- ============================================================================
-- Philippe Grégoire Yacé — schéma initial
-- MySQL 8, InnoDB, utf8mb4. Les libellés d'énumération sont en français
-- pour rester lisibles depuis phpMyAdmin par un non-développeur.
-- ============================================================================

SET NAMES utf8mb4;

-- --- Comptes du back-office -------------------------------------------------
CREATE TABLE IF NOT EXISTS utilisateur (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(180) NOT NULL,
  mot_de_passe  VARCHAR(255) NOT NULL,   -- password_hash(), jamais en clair
  nom           VARCHAR(120) NOT NULL,
  role          ENUM('admin','editeur') NOT NULL DEFAULT 'editeur',
  actif         TINYINT(1) NOT NULL DEFAULT 1,
  cree_le       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_utilisateur_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Actualités et revue de presse (CDC §4.7) -------------------------------
CREATE TABLE IF NOT EXISTS actualite (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(200) NOT NULL,
  slug        VARCHAR(200) NOT NULL,
  categorie   ENUM('parution','dedicace','presse','hommage','evenement') NOT NULL DEFAULT 'parution',
  chapo       VARCHAR(400) NULL,
  contenu     MEDIUMTEXT NULL,
  image       VARCHAR(255) NULL,
  source      VARCHAR(200) NULL,          -- organe de presse, pour la revue
  source_url  VARCHAR(500) NULL,
  statut      ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  publie_le   DATE NULL,
  cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_actualite_slug (slug),
  KEY ix_actualite_public (statut, publie_le)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Événements (CDC §4.10) -------------------------------------------------
CREATE TABLE IF NOT EXISTS evenement (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre        VARCHAR(200) NOT NULL,
  slug         VARCHAR(200) NOT NULL,
  description  MEDIUMTEXT NULL,
  lieu         VARCHAR(200) NULL,
  ville        VARCHAR(120) NULL,
  debut_le     DATETIME NOT NULL,
  fin_le       DATETIME NULL,
  image        VARCHAR(255) NULL,
  inscription_url VARCHAR(500) NULL,
  statut       ENUM('brouillon','publie','annule') NOT NULL DEFAULT 'brouillon',
  cree_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_evenement_slug (slug),
  KEY ix_evenement_public (statut, debut_le)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Témoignages du public, avec modération (CDC §4.8) ----------------------
-- Rien n'est publié sans passage explicite en 'publie' par un modérateur :
-- il s'agit de propos sur une personne réelle.
CREATE TABLE IF NOT EXISTS temoignage (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  auteur_nom     VARCHAR(160) NOT NULL,
  auteur_fonction VARCHAR(200) NULL,
  auteur_email   VARCHAR(180) NULL,       -- jamais affiché en public
  contenu        TEXT NOT NULL,
  statut         ENUM('en_attente','publie','refuse') NOT NULL DEFAULT 'en_attente',
  soumis_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modere_le      DATETIME NULL,
  modere_par     INT UNSIGNED NULL,
  ip_soumission  VARBINARY(16) NULL,      -- anti-abus, purge périodique
  KEY ix_temoignage_statut (statut, soumis_le),
  CONSTRAINT fk_temoignage_moderateur FOREIGN KEY (modere_par)
    REFERENCES utilisateur (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Photothèque et archives (CDC §4.6) -------------------------------------
CREATE TABLE IF NOT EXISTS media (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fichier    VARCHAR(255) NOT NULL,
  titre      VARCHAR(200) NULL,
  legende    VARCHAR(500) NULL,
  credit     VARCHAR(200) NULL,           -- droits : obligatoire sur archives
  date_prise VARCHAR(60) NULL,            -- souvent imprécise : « vers 1965 »
  categorie  ENUM('portrait','officiel','prive','document','presse') NOT NULL DEFAULT 'portrait',
  largeur    SMALLINT UNSIGNED NULL,
  hauteur    SMALLINT UNSIGNED NULL,
  octets     INT UNSIGNED NULL,           -- poids du fichier, affiché à l'éditeur
  ordre      SMALLINT NOT NULL DEFAULT 0,
  statut     ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  cree_le    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_media_fichier (fichier),
  KEY ix_media_public (statut, categorie, ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Repères chronologiques (CDC §4.4) --------------------------------------
CREATE TABLE IF NOT EXISTS repere (
  id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  annee    VARCHAR(20) NOT NULL,          -- chaîne : une date peut être « v. 1945 »
  tri      SMALLINT NOT NULL,             -- année numérique pour l'ordre
  periode  ENUM('p1','p2','p3','p4') NOT NULL,
  titre    VARCHAR(200) NOT NULL,
  notice   TEXT NULL,
  source   VARCHAR(300) NULL,             -- sourçage exigé par le CDC §6
  statut   ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  KEY ix_repere_public (statut, tri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Commandes (CDC §4.9) ---------------------------------------------------
CREATE TABLE IF NOT EXISTS commande (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reference     VARCHAR(40) NOT NULL,
  client_nom    VARCHAR(160) NOT NULL,
  client_email  VARCHAR(180) NOT NULL,
  client_tel    VARCHAR(40) NULL,
  quantite      SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  montant       DECIMAL(10,2) NOT NULL,
  devise        CHAR(3) NOT NULL DEFAULT 'XOF',
  mode_paiement VARCHAR(40) NULL,
  passerelle    VARCHAR(60) NULL,           -- d'où vient le paiement ; peut changer
  transaction_ref VARCHAR(80) NULL,         -- code rendu par la passerelle
  livraison     ENUM('retrait','livraison') NOT NULL DEFAULT 'retrait',
  adresse       VARCHAR(500) NULL,
  note          VARCHAR(500) NULL,          -- annotation interne de suivi
  statut        ENUM('initiee','payee','echouee','remise') NOT NULL DEFAULT 'initiee',
  cree_le       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  remise_le     DATETIME NULL,
  remise_par    INT UNSIGNED NULL,
  UNIQUE KEY uk_commande_reference (reference),
  UNIQUE KEY uk_commande_transaction (transaction_ref),
  KEY ix_commande_statut (statut, cree_le),
  CONSTRAINT fk_commande_remise FOREIGN KEY (remise_par)
    REFERENCES utilisateur (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --- Contenus éditables et réglages -----------------------------------------
CREATE TABLE IF NOT EXISTS parametre (
  cle     VARCHAR(80) NOT NULL PRIMARY KEY,
  valeur  TEXT NULL,
  libelle VARCHAR(200) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Fiche technique de l'ouvrage : éditable sans toucher au code (CDC §4.2).
INSERT IGNORE INTO parametre (cle, valeur, libelle) VALUES
  ('livre_titre',    'Une destinée',        'Titre de l''ouvrage'),
  ('livre_auteur',   NULL,                  'Auteur'),
  ('livre_editeur',  NULL,                  'Éditeur'),
  ('livre_parution', NULL,                  'Date de parution'),
  ('livre_pages',    NULL,                  'Nombre de pages'),
  ('livre_isbn',     NULL,                  'ISBN'),
  ('livre_prix',     NULL,                  'Prix'),
  ('livre_format',   'Relié, 240 × 310 mm', 'Format');
