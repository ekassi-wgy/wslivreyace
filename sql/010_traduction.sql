-- ============================================================================
-- Philippe Grégoire Yacé — traductions (lot G1)
--
-- Une seule table pour tout le site, et c'est une décision, pas une facilité.
--
-- L'autre forme possible était une table par entité — `actualite_traduction`,
-- `evenement_traduction`, et ainsi de suite. Elle donne des colonnes typées et
-- des requêtes naturelles, mais **elle coûte une migration par entité**. Or le
-- fonds patrimonial va en créer plusieurs : notices d'archives, discours,
-- lieux de mémoire, périodes de la biographie. Chacune aurait demandé sa table
-- de traduction, et l'oubli d'une seule ne se serait vu qu'en anglais, donc
-- tard.
--
-- Ici, une entité nouvelle est traduisible le jour où elle existe, sans
-- toucher au schéma.
--
-- La contrepartie est assumée : les valeurs ne sont pas typées, et une
-- traduction se lit en deux temps plutôt qu'en une jointure. À l'échelle du
-- site — quelques milliers de lignes — cela ne se mesure pas, et la lecture
-- française n'interroge même pas la table (voir App\Core\Traduction).
--
-- `entite` porte le nom de la table traduite, `ligne_id` la clé primaire de
-- la ligne, `champ` le nom de la colonne. Aucune clé étrangère : elles
-- viseraient une table différente à chaque ligne, ce qu'InnoDB ne sait pas
-- faire. Le ménage est donc applicatif — voir `Traduction::oublier`.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS traduction (
  id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  entite   VARCHAR(40)  NOT NULL,       -- 'actualite', 'evenement', 'repere'…
  ligne_id INT UNSIGNED NOT NULL,       -- clé primaire dans la table d'origine
  langue   CHAR(2)      NOT NULL,       -- code ISO 639-1 ; voir App\Core\Langue
  champ    VARCHAR(40)  NOT NULL,       -- nom de la colonne traduite
  valeur   MEDIUMTEXT   NULL,           -- MEDIUMTEXT : un corps d'actualité y tient
  maj_le   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  -- Une seule traduction par champ, par ligne et par langue. Sans cette
  -- contrainte, deux enregistrements successifs laisseraient deux versions et
  -- la page afficherait celle que MySQL rendrait en premier.
  UNIQUE KEY uk_traduction_cible (entite, ligne_id, langue, champ),

  -- L'index de lecture : toutes les traductions d'une ligne, en une passe.
  KEY ix_traduction_lecture (entite, langue, ligne_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
