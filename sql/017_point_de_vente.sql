-- ============================================================================
-- Philippe Grégoire Yacé — les points de vente sortent du gabarit (lot G12)
--
-- CE QUE CE FICHIER RÉPARE. « Où se procurer l'ouvrage » affichait trois
-- villes — Abidjan, Yamoussoukro, Paris — écrites en dur dans deux gabarits,
-- `templates/pages/accueil.php` et `templates/pages/livre.php`, chacune sous
-- la même ligne « Enseigne et adresse à renseigner ». Trois villes que
-- personne ne pouvait changer sans toucher au code, et une adresse qu'aucun
-- éditeur ne pouvait renseigner depuis nulle part. C'est le même déplacement
-- qu'au lot G0 pour la frise : ce qui était du gabarit devient de la donnée.
--
-- POURQUOI UNE TABLE ET NON TROIS PARAMÈTRES. `parametre` aurait pu porter
-- « pdv_1_ville », « pdv_1_adresse » et leurs jumeaux — et aurait figé le
-- nombre à trois. Une librairie s'ajoute, une autre ferme, une dédicace
-- installe un point de vente le temps d'un salon : c'est une liste, pas un
-- réglage. La table le dit, l'écran d'administration le permet, et la page
-- publique s'adapte au nombre sans qu'on y retouche.
--
-- POURQUOI `ville` PORTE LE TITRE DE LA FICHE. La maquette met la ville en
-- grand et l'enseigne en dessous, et c'est le bon ordre : on cherche d'abord
-- où l'on est, l'enseigne ensuite. La ville est donc le seul champ
-- obligatoire — c'est elle qui identifie la fiche partout, y compris dans les
-- messages du back-office.
--
-- POURQUOI TOUT LE RESTE EST NULLABLE. Une librairie se pose avant d'être
-- documentée : l'éditeur sait qu'il vendra à Yamoussoukro bien avant de
-- connaître l'enseigne. Un champ vide ne s'affiche pas, la fiche se referme
-- sur ce qu'elle a. C'est aussi ce qui permet à l'amorce ci-dessous de verser
-- les trois villes telles qu'elles paraissaient, sans rien inventer.
--
-- LE STATUT PLUTÔT QU'UNE CASE `actif`. Un point de vente est du contenu
-- public, pas un réglage commercial comme une zone de livraison : il suit le
-- régime de tout le reste du site — brouillon, puis publié — et se dépublie
-- depuis la liste, d'un bouton, comme une actualité.
--
-- À jouer une seule fois, après `sql/016_boutique.sql`.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS point_de_vente (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  -- Le titre de la fiche, et le grand caractère de la maquette.
  ville     VARCHAR(120) NOT NULL,

  -- L'enseigne : « Librairie de France », « Fnac Ternes ». Facultative tant
  -- qu'elle n'est pas arrêtée.
  enseigne  VARCHAR(200) NULL,

  -- L'adresse en toutes lettres, telle qu'on la donnerait de vive voix. Pas
  -- de découpage rue / quartier / code postal : trois pays, trois façons
  -- d'écrire une adresse, et un formulaire à cases produirait des adresses
  -- fausses en Côte d'Ivoire comme en France.
  adresse   VARCHAR(300) NULL,

  -- Le téléphone tel qu'il s'affiche ; la page en tire le lien d'appel.
  telephone VARCHAR(60)  NULL,

  -- Le site de l'enseigne, quand elle en a un. Le schéma est vérifié à la
  -- saisie : cette valeur finit dans un `href`.
  url       VARCHAR(300) NULL,

  -- Le rang d'affichage. 0 en premier, comme partout ailleurs.
  ordre     SMALLINT NOT NULL DEFAULT 0,

  statut    ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  cree_le   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  KEY ix_point_de_vente_public (statut, ordre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Ce que les deux gabarits portaient -------------------------------------
-- Les trois villes écrites en dur, versées ici telles quelles — même geste
-- qu'au lot G0 pour les sept repères de la frise.
--
-- ELLES ARRIVENT EN `publie`, ET POUR UNE SEULE RAISON : elles l'étaient déjà,
-- de fait. Les verser en brouillon viderait la section « Où se procurer
-- l'ouvrage » de l'accueil et de la page du livre — une régression, là où ce
-- lot ne fait que déplacer la même matière du gabarit vers la base.
--
-- C'est la différence avec les cinq chapitres de biographie du lot G10, versés
-- en brouillon : ceux-là portaient « texte à rédiger » sur la vie d'une figure
-- historique. Une ville où l'on vend le livre n'affirme rien de tel.
--
-- AUCUNE ENSEIGNE, AUCUNE ADRESSE : les gabarits n'en portaient pas, et en
-- inventer reviendrait à publier une adresse que personne n'a vérifiée. Les
-- fiches affichent donc « Enseigne et adresse à renseigner », exactement comme
-- avant ce lot — à ceci près que la ligne se remplace désormais depuis
-- l'écran « Points de vente » du back-office.
--
-- Aucun garde-fou contre le doublon : une ligne d'amorce n'a pas de clé
-- naturelle sur laquelle en poser un. Sur une base où la table existe déjà et
-- porte des points de vente, ce bloc ne se joue pas — voir README §7.
INSERT INTO point_de_vente (ville, enseigne, adresse, ordre, statut) VALUES
  ('Abidjan',      NULL, NULL, 1, 'publie'),
  ('Yamoussoukro', NULL, NULL, 2, 'publie'),
  ('Paris',        NULL, NULL, 3, 'publie');
