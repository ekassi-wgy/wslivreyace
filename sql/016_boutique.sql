-- ============================================================================
-- Philippe Grégoire Yacé — la boutique et ses zones de livraison (lot G3)
--
-- CE QUE CE LOT FAIT, ET CE QU'IL NE FAIT PAS. Il ouvre la commande en
-- **paiement à la livraison**, et rien d'autre : aucune passerelle n'est
-- appelée. `App\Core\Paiement` décrit carte.abidjan.net depuis le lot E2 et
-- continue de ne pas être appelée. La phase 2 branchera le paiement en ligne,
-- et ce fichier est écrit pour qu'elle n'ait aucune migration à jouer.
--
-- À jouer une seule fois, après `sql/015_periode.sql`.
-- ============================================================================

SET NAMES utf8mb4;

-- --- Zones de livraison ------------------------------------------------------
-- POURQUOI UNE TABLE QUI SE RÉFÉRENCE ELLE-MÊME, ET NON TROIS TABLES.
-- Pays, ville et commune ont la même forme : un nom, un parent, un tarif.
-- Trois tables auraient triplé les écrans d'administration et les jointures, et
-- un quatrième niveau — un quartier d'Abidjan, un arrondissement de Paris — en
-- aurait demandé une quatrième. Une table, un écran, une récursion.
--
-- POURQUOI LES FRAIS S'HÉRITENT. `frais` est **nullable**, et null veut dire
-- « ceux du parent ». C'est ce qui rend le système tenable à la main : on pose
-- « Côte d'Ivoire = 2 000 », puis seulement les exceptions — « Abidjan =
-- 1 500 », « Cocody = 1 000 ». Sans héritage, il faudrait un tarif pour chacune
-- des treize communes du district, et pour chaque commune ajoutée ensuite.
--
-- L'autre forme — un tableau plat de triplets (pays, ville, commune) avec un
-- tarif chacun — explose en combinaisons et oblige à retrouver le tarif de la
-- ville pour le recopier. Elle a été écartée pour cela.
--
-- UNE RACINE SANS TARIF N'EST PAS LIVRABLE. Un pays dont ni lui ni personne
-- au-dessus ne porte de tarif ne peut pas être facturé : le tunnel ne le
-- propose pas, plutôt que de laisser commander à zéro franc. `actif` dit
-- l'inverse — une zone qu'on ne dessert plus se décoche sans se supprimer, et
-- les commandes anciennes gardent leur libellé.
--
-- LE TARIF EST UN ENTIER. Le franc CFA n'a pas de subdivision : des centimes
-- inviteraient à saisir des valeurs que personne ne peut payer.
CREATE TABLE IF NOT EXISTS zone_livraison (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  parent_id INT UNSIGNED NULL,               -- null = racine (un pays)
  niveau    ENUM('pays','ville','commune') NOT NULL,
  nom       VARCHAR(120) NOT NULL,
  code      VARCHAR(40) NULL,                -- ISO 3166-1 alpha-2 pour un pays
  frais     INT UNSIGNED NULL,               -- null = hérite du parent
  actif     TINYINT(1) NOT NULL DEFAULT 1,
  ordre     SMALLINT NOT NULL DEFAULT 0,
  cree_le   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY ix_zone_parent (parent_id, actif, ordre),
  KEY ix_zone_niveau (niveau, actif),
  -- `RESTRICT` et non `CASCADE` : supprimer un pays emporterait silencieusement
  -- ses villes et leurs communes, donc les tarifs qu'on venait d'y poser.
  -- L'écran d'administration refuse la suppression d'une zone qui a des
  -- enfants, et dit pourquoi.
  CONSTRAINT fk_zone_parent FOREIGN KEY (parent_id)
    REFERENCES zone_livraison (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Ce qu'une commande doit figer -------------------------------------------
-- LE PRIX ET LES FRAIS CHANGERONT. Une commande de janvier doit garder ce
-- qu'elle a coûté en janvier : recalculer un total ancien à partir des tarifs
-- du jour ferait mentir la fiche de suivi, et une contestation se tranche sur
-- ce qui a été facturé, pas sur ce qui se facture aujourd'hui.
--
-- `montant` reste le **total** — prix unitaire × quantité + frais — et garde
-- donc son sens : l'écran de suivi et le calcul de recette du lot E2 ne
-- bougent pas.
ALTER TABLE commande
  ADD COLUMN prix_unitaire   INT UNSIGNED NOT NULL DEFAULT 0 AFTER quantite,
  ADD COLUMN frais_livraison INT UNSIGNED NOT NULL DEFAULT 0 AFTER prix_unitaire;

-- LA ZONE EST GARDÉE DEUX FOIS, ET C'EST VOULU. `zone_id` sert aux
-- rapprochements tant que la zone existe ; `zone_libelle` porte la chaîne
-- « Côte d'Ivoire · Abidjan · Cocody » **en texte**, pour qu'une commande reste
-- lisible après un renommage ou une suppression. `SET NULL` plutôt que
-- `RESTRICT` ici : une zone qu'on ne dessert plus doit pouvoir disparaître sans
-- que les commandes anciennes s'y opposent — elles gardent leur libellé.
ALTER TABLE commande
  ADD COLUMN zone_id      INT UNSIGNED NULL  AFTER livraison,
  ADD COLUMN zone_libelle VARCHAR(255) NULL  AFTER zone_id,
  ADD CONSTRAINT fk_commande_zone FOREIGN KEY (zone_id)
    REFERENCES zone_livraison (id) ON DELETE SET NULL;

-- --- Les statuts d'une commande payée à la livraison -------------------------
-- LE CHEMIN N'EST PLUS LE MÊME. « initiée → payée → remise » décrivait un
-- paiement **en ligne, avant** l'expédition. En paiement à la livraison,
-- l'argent arrive **à la remise** :
--
--     initiée ──→ confirmée ──→ remise
--        └────────────┴───────→ annulée
--
-- `payee` et `echouee` sont **conservées pour la phase 2** : le jour où la
-- passerelle sera branchée, les deux parcours cohabiteront dans un seul jeu de
-- statuts et il n'y aura **aucune migration** à jouer — seulement une suite à
-- rouvrir dans `App\Model\Commande::SUITES`.
--
-- Réutiliser `payee` pour dire « confirmée » aurait tenu sans migration, mais
-- aurait fait mentir le mot sur toutes les commandes et faussé le calcul de
-- recette, qui compte `payee` et `remise`.
ALTER TABLE commande
  MODIFY statut ENUM('initiee','confirmee','payee','echouee','annulee','remise')
    NOT NULL DEFAULT 'initiee';

-- --- Le prix du livre devient un nombre ---------------------------------------
-- Il était saisi en texte libre — « 25 000 F CFA » — et n'était affiché nulle
-- part : la fiche technique de la page du livre est en dur. Un tunnel qui
-- calcule `prix × quantité + frais` ne peut pas partir de là, et garder les
-- deux formes ferait diverger l'affiché et le facturé.
--
-- La colonne `valeur` reste du texte : c'est le catalogue de
-- `App\Model\Parametre` qui change de type. Ce `UPDATE` ne fait que nettoyer ce
-- qui aurait déjà été saisi — il retire tout ce qui n'est pas un chiffre, de
-- sorte que « 25 000 F CFA » devienne « 25000 ». Sans effet si la valeur est
-- vide, ce qui est le cas sur la base d'amorce.
UPDATE parametre
   SET valeur = REGEXP_REPLACE(valeur, '[^0-9]', '')
 WHERE cle = 'livre_prix' AND valeur IS NOT NULL AND valeur <> '';

-- --- Amorce des zones ---------------------------------------------------------
-- CE QUI SUIT EST DU DÉCOUPAGE ADMINISTRATIF PUBLIC, PAS UNE LISTE VALIDÉE.
-- Les treize communes du district autonome d'Abidjan, plus la racine « Reste du
-- monde » pour qu'un acheteur d'un pays non listé ne se heurte pas à un mur.
--
-- **Aucune ne porte de tarif** : elles héritent toutes de leur racine, qui n'en
-- a pas non plus. Tant que l'éditeur n'a pas posé de montant, la livraison
-- n'est proposée nulle part et seul le retrait est possible — c'est le bon
-- défaut, il ne promet rien.
INSERT INTO zone_livraison (parent_id, niveau, nom, code, frais, actif, ordre) VALUES
  (NULL, 'pays', 'Côte d''Ivoire',   'CI', NULL, 1, 1),
  (NULL, 'pays', 'Reste du monde',   NULL, NULL, 1, 99);

-- Abidjan, sous la Côte d'Ivoire. Le `SELECT` retrouve la racine plutôt que de
-- supposer que son identifiant vaut 1 : la table peut ne pas être vide.
INSERT INTO zone_livraison (parent_id, niveau, nom, code, frais, actif, ordre)
SELECT id, 'ville', 'Abidjan', NULL, NULL, 1, 1
  FROM zone_livraison WHERE code = 'CI' AND parent_id IS NULL;

-- Les dix communes de la ville, puis les trois du district — dans l'ordre
-- alphabétique, qui est celui qu'un habitant cherche dans une liste.
INSERT INTO zone_livraison (parent_id, niveau, nom, code, frais, actif, ordre)
SELECT v.id, 'commune', c.nom, NULL, NULL, 1, c.ordre
  FROM zone_livraison v
  JOIN zone_livraison p ON p.id = v.parent_id AND p.code = 'CI'
  JOIN (
              SELECT 'Abobo' AS nom, 1 AS ordre
    UNION ALL SELECT 'Adjamé', 2
    UNION ALL SELECT 'Anyama', 3
    UNION ALL SELECT 'Attécoubé', 4
    UNION ALL SELECT 'Bingerville', 5
    UNION ALL SELECT 'Cocody', 6
    UNION ALL SELECT 'Koumassi', 7
    UNION ALL SELECT 'Marcory', 8
    UNION ALL SELECT 'Plateau', 9
    UNION ALL SELECT 'Port-Bouët', 10
    UNION ALL SELECT 'Songon', 11
    UNION ALL SELECT 'Treichville', 12
    UNION ALL SELECT 'Yopougon', 13
  ) AS c
 WHERE v.niveau = 'ville' AND v.nom = 'Abidjan';
