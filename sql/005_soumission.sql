-- ============================================================================
-- Philippe Grégoire Yacé — journal des soumissions publiques
--
-- Compteur glissant des formulaires ouverts à tout le monde : témoignage,
-- contact. Il borne le nombre d'envois d'un même visiteur par heure.
--
-- Table distincte de `tentative_connexion`, qui compte autre chose : là-bas des
-- échecs, remis à zéro par une réussite ; ici des soumissions, réussies
-- comprises. Voir App\Core\Debit.
--
-- Elle n'a pas vocation à être conservée : une purge d'un jour tourne à chaque
-- écriture, l'hébergement visé n'ayant pas de planificateur.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS soumission_publique (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  action    VARCHAR(40) NOT NULL,        -- 'temoignage', 'contact' : voir Debit::BAREMES
  ip        VARBINARY(16) NOT NULL,      -- inet_pton : couvre IPv4 et IPv6
  soumis_le DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY ix_soumission_debit (action, ip, soumis_le)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
