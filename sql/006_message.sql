-- ============================================================================
-- Philippe Grégoire Yacé — messages du formulaire de contact (lot F4)
--
-- À jouer une seule fois, sur une base déjà installée. Une base créée à partir
-- de 001_schema.sql porte déjà cette table : elle y a été reportée, pour
-- qu'une installation neuve n'ait pas à rejouer l'historique.
--
-- `CREATE TABLE IF NOT EXISTS` : rejouer ce fichier ne casse rien, mais ne
-- rattrape rien non plus si la table existe sous une autre forme.
--
-- Pourquoi une table et pas un envoi de courriel : un message perdu est pire
-- que pas de formulaire du tout. `mail()` sur un hébergement mutualisé échoue
-- en silence ou finit en indésirable, et personne ne l'apprend. La base est la
-- source de vérité ; la notification, si elle s'ajoute un jour, ne sera qu'un
-- confort par-dessus.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS message (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom           VARCHAR(160) NOT NULL,
  email         VARCHAR(180) NOT NULL,      -- exigée : sans elle, pas de réponse
  sujet         ENUM('ouvrage','commande','presse','archives','autre')
                NOT NULL DEFAULT 'autre',
  contenu       TEXT NOT NULL,
  statut        ENUM('nouveau','traite') NOT NULL DEFAULT 'nouveau',
  recu_le       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  traite_le     DATETIME NULL,
  traite_par    INT UNSIGNED NULL,
  ip_soumission VARBINARY(16) NULL,         -- anti-abus, jamais affichée en public
  KEY ix_message_statut (statut, recu_le),
  CONSTRAINT fk_message_traite_par FOREIGN KEY (traite_par)
    REFERENCES utilisateur (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
