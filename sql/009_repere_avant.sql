-- ============================================================================
-- Philippe Grégoire Yacé — mise en avant des repères (lot G0)
--
-- En branchant l'accueil sur la table `repere`, sa frise s'est mise à montrer
-- les quatre premières entrées chronologiques — dont deux notices « à
-- documenter » — là où le gabarit affichait auparavant les quatre dates
-- marquantes : 1920, 1959, 1980, 1998.
--
-- Prendre les quatre premières était le choix implicite du code ; le remplacer
-- par une autre règle implicite — les quatre années pleines, les quatre mieux
-- notées — ne ferait que déplacer le problème. **C'est un choix éditorial, il
-- revient à l'éditeur** : cette colonne le lui donne, avec une case à cocher
-- sur la fiche du repère.
--
-- L'accueil n'affiche donc que des repères mis en avant, la biographie les
-- affiche tous. Aucun repère mis en avant, aucune section sur l'accueil — et
-- c'est cohérent : rien de choisi, rien de montré.
--
-- À jouer une seule fois. `ADD COLUMN` lève `Duplicate column name` si le
-- fichier est rejoué, ce qui vaut mieux qu'une modification silencieuse.
-- ============================================================================

SET NAMES utf8mb4;

ALTER TABLE repere
  ADD COLUMN en_avant TINYINT(1) NOT NULL DEFAULT 0 AFTER statut;

-- Rétablit ce que l'accueil montrait avant ce lot : les repères dont l'année
-- est établie, c'est-à-dire écrite en quatre chiffres et non en « — ». Sur la
-- base d'amorce, ce sont exactement 1920, 1959, 1980 et 1998.
--
-- Sans effet sur une base où personne n'a encore saisi de repère.
UPDATE repere
   SET en_avant = 1
 WHERE annee REGEXP '^[0-9]{4}$';
