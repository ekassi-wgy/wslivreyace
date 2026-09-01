-- ============================================================================
-- Philippe Grégoire Yacé — journal des tentatives de connexion
--
-- Le back-office est la seule porte d'écriture du site : une attaque par
-- essais successifs sur /cmsadmin/connexion doit coûter cher. La table sert de
-- compteur glissant ; elle n'a pas vocation à être conservée longtemps.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS tentative_connexion (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  identifiant VARCHAR(180) NOT NULL,      -- l'adresse saisie, existante ou non
  ip          VARBINARY(16) NOT NULL,     -- inet6_pton : couvre IPv4 et IPv6
  reussie     TINYINT(1) NOT NULL DEFAULT 0,
  tentee_le   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_tentative_ip (ip, tentee_le),
  KEY ix_tentative_identifiant (identifiant, tentee_le)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
