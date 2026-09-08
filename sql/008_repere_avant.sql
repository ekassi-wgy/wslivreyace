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
-- **Ce fichier portait le numéro 009 et passait après l'amorce des repères.**
-- L'ordre était intenable : `009_repere_amorce.sql` nomme `en_avant` dans son
-- `INSERT`, et sur une base installée avant le lot G0 — la production — la
-- colonne n'existe pas encore. L'amorce s'y arrêtait sur `#1054 Champ
-- 'en_avant' inconnu dans field list`. Rien ne s'en voyait en local, où la base
-- repart de `001_schema.sql`, mis à jour dans le même commit que le lot.
-- La colonne se pose donc avant qu'on écrive dedans, et les deux fichiers ont
-- échangé leurs numéros.
--
-- À jouer une seule fois, **avant `sql/009_repere_amorce.sql`**. `ADD COLUMN`
-- lève `Duplicate column name` si le fichier est rejoué, ce qui vaut mieux
-- qu'une modification silencieuse — et une base neuve installée depuis
-- `001_schema.sql` a déjà la colonne : ce fichier ne la concerne pas, les
-- migrations rattrapent une base existante.
-- ============================================================================

SET NAMES utf8mb4;

ALTER TABLE repere
  ADD COLUMN en_avant TINYINT(1) NOT NULL DEFAULT 0 AFTER statut;

-- Rattrape les repères déjà saisis : sont mis en avant ceux dont l'année est
-- établie, c'est-à-dire écrite en quatre chiffres et non en « — ». C'est le
-- plus proche de ce que l'accueil montrait avant ce lot, et l'éditeur reste
-- libre de décocher.
--
-- **Sans effet sur la production, où la table est vide** : les sept repères
-- d'amorce arrivent au fichier suivant, qui porte leur mise en avant ligne à
-- ligne — 1920, 1959, 1980 et 1998. Cet `UPDATE` ne sert que la base d'un
-- éditeur qui aurait pris les devants.
UPDATE repere
   SET en_avant = 1
 WHERE annee REGEXP '^[0-9]{4}$';
