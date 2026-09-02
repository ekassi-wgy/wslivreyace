-- ============================================================================
-- Philippe Grégoire Yacé — commandes (lot E2)
--
-- La table `commande` existe depuis le schéma initial. Le lot E2 lui ajoute ce
-- que la passerelle de paiement rend, et la trace de la remise.
--
-- Pourquoi la provenance est une colonne et non une constante : le site de
-- référence passait par carte.abidjan.net, et c'est la passerelle retenue pour
-- l'instant. Elle peut changer. Le jour où une commande naîtra ailleurs, les
-- anciennes doivent continuer à dire d'où elles viennent — sans quoi on ne
-- saura plus où aller vérifier un paiement contesté.
--
-- À jouer une seule fois, sur une base déjà installée ; une base créée à partir
-- de 001_schema.sql porte déjà ces colonnes.
-- ============================================================================

SET NAMES utf8mb4;

ALTER TABLE commande
  ADD COLUMN passerelle      VARCHAR(60)  NULL AFTER mode_paiement,
  ADD COLUMN transaction_ref VARCHAR(80)  NULL AFTER passerelle,
  ADD COLUMN note            VARCHAR(500) NULL AFTER adresse,
  ADD COLUMN remise_le       DATETIME     NULL AFTER maj_le,
  ADD COLUMN remise_par      INT UNSIGNED NULL AFTER remise_le;

-- Le code rendu par la passerelle : c'est lui qu'on lui donne pour retrouver
-- un paiement. Unique, mais nullable — une commande saisie hors ligne n'en a
-- pas, et MySQL accepte plusieurs NULL sous une contrainte d'unicité.
ALTER TABLE commande
  ADD UNIQUE KEY uk_commande_transaction (transaction_ref);

-- Qui a remis l'exemplaire, comme on garde qui a modéré un témoignage : la
-- question « qui a marqué cette commande remise ? » doit avoir une réponse.
-- ON DELETE SET NULL, le compte pouvant disparaître après coup.
ALTER TABLE commande
  ADD CONSTRAINT fk_commande_remise FOREIGN KEY (remise_par)
    REFERENCES utilisateur (id) ON DELETE SET NULL;
