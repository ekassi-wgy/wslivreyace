-- ============================================================================
-- Philippe Grégoire Yacé — la biographie par périodes (brief §3, lot G10)
--
-- CE QUE LE BRIEF DEMANDE. La page Biographie porte cinq chapitres écrits en
-- dur dans le gabarit, sans date et sans adresse. Le brief en veut douze
-- périodes, chacune à son adresse, illustrées et reliées au fonds d'archives.
-- C'est le même déplacement que pour Héritage au lot G7 : ce qui était une
-- page devient des pièces cataloguées.
--
-- POURQUOI UNE TABLE ET NON DOUZE ANCRES. Une ancre ne se partage pas — ni sur
-- un plateau, ni dans un dossier de presse, ni sur un QR code — et surtout
-- elle ne se date pas. C'est la datation qui fait tout le reste : une période
-- qui connaît ses bornes sait quels repères de la frise la traversent et
-- quelles pièces du fonds ont été produites pendant qu'elle durait. Le
-- rattachement n'est alors saisi nulle part, il se déduit.
--
-- POURQUOI `debut` ET `fin` PEUVENT ÊTRE VIDES, MAIS PAS POUR PUBLIER. Une
-- période se rédige avant de se dater : l'auteur pose un titre, écrit, et
-- arrête les bornes ensuite. L'écran de saisie les exige à la publication, au
-- même titre que la source (CDC §6) — une période publiée sans dates ne
-- porterait ni frise ni fonds, et mentirait sur ce qu'elle est.
--
-- LES BORNES SONT INCLUSIVES ET NE SE CHEVAUCHENT PAS. Une année appartient à
-- une période et à une seule, sans quoi le même repère paraîtrait sous deux
-- filtres et la même pièce d'archive sous deux récits. Le découpage `p1`-`p4`
-- que ce lot remplace se chevauchait justement d'un an — 1980 fermait la
-- troisième période et ouvrait la quatrième — et c'est exactement ce qu'il ne
-- faut pas reconduire. L'écran de saisie refuse le chevauchement.
--
-- À jouer une seule fois.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS periode (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(200) NOT NULL,
  slug        VARCHAR(200) NOT NULL,

  -- Une ligne sous le titre, sur l'index de la biographie.
  sous_titre  VARCHAR(300) NULL,

  -- Les bornes, incluses toutes les deux. Nullables tant que la période est
  -- en brouillon ; exigées pour publier — voir l'en-tête.
  debut       SMALLINT UNSIGNED NULL,
  fin         SMALLINT UNSIGNED NULL,

  -- Le récit de la période. Saisi en texte brut, une ligne vide séparant deux
  -- paragraphes, et rendu échappé comme partout ailleurs.
  recit       MEDIUMTEXT   NULL,

  -- Le sourçage vaut ici plus qu'ailleurs : c'est le seul endroit du site où
  -- l'on écrit la vie d'une personne réelle en continu. Le CDC §6 l'exige et
  -- l'écran de publication refuse de publier sans lui.
  source      VARCHAR(300) NULL,

  statut      ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uk_periode_slug (slug),
  KEY ix_periode_public (statut, debut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Les illustrations d'une période — « frise illustrée », brief §3. Même forme
-- que `archive_media` et `heritage_media`, et pour la même raison : une
-- période se montre par plusieurs images, la première faisant la vignette de
-- l'index et l'image de partage.
CREATE TABLE IF NOT EXISTS periode_media (
  periode_id INT UNSIGNED NOT NULL,
  media_id   INT UNSIGNED NOT NULL,
  ordre      SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (periode_id, media_id),
  KEY ix_periode_media_ordre (periode_id, ordre),
  CONSTRAINT fk_periode_media_periode FOREIGN KEY (periode_id)
    REFERENCES periode (id) ON DELETE CASCADE,
  CONSTRAINT fk_periode_media_media FOREIGN KEY (media_id)
    REFERENCES media (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Ce que le gabarit portait ----------------------------------------------
-- Les cinq chapitres écrits en dur dans `templates/pages/biographie.php`,
-- versés ici mot pour mot pour que rien ne se perde au passage — même geste
-- qu'au lot G0 pour les sept repères de la frise.
--
-- MAIS EN BROUILLON, ET C'EST LA DIFFÉRENCE. Les repères de G0 portaient des
-- dates ; ces cinq-là ne portent que la consigne de rédaction affichée en
-- italique sur la page. Les publier reviendrait à publier « texte à rédiger »
-- sur la biographie d'une figure historique réelle. Ils arrivent donc là où
-- ils doivent être : dans l'écran de saisie, sous les yeux de l'auteur.
--
-- CE NE SONT PAS LES DOUZE PÉRIODES DU BRIEF, et ce fichier n'essaie pas de
-- les inventer. Quatre des cinq sont chronologiques, la cinquième —
-- « L'homme privé » — est thématique : c'est précisément pourquoi le brief
-- demande de passer des chapitres aux périodes. Le découpage en douze, daté et
-- sourcé, est dû par le commanditaire et l'auteur (README §9) ; l'écran est
-- prêt à le recevoir.
--
-- Aucune borne n'est posée : les chapitres n'en avaient pas, et en deviner
-- serait leur prêter une datation que personne n'a validée.
INSERT INTO periode (titre, slug, recit, statut) VALUES
  ('Enfance et formation', 'enfance-et-formation',
   'Texte à rédiger : Origines familiales, années de jeunesse, parcours scolaire et formation.

Les faits avancés ici devront être sourcés (CDC §6).',
   'brouillon'),

  ('L''engagement politique', 'l-engagement-politique',
   'Texte à rédiger : Entrée en politique, rencontre avec Félix Houphouët-Boigny, rôle au sein du PDCI-RDA.

Les faits avancés ici devront être sourcés (CDC §6).',
   'brouillon'),

  ('La carrière institutionnelle', 'la-carriere-institutionnelle',
   'Texte à rédiger : Vingt et un ans à la présidence de l''Assemblée nationale, puis le Conseil économique et social.

Les faits avancés ici devront être sourcés (CDC §6).',
   'brouillon'),

  ('L''homme privé', 'l-homme-prive',
   'Texte à rédiger : Vie familiale, convictions, rapports aux siens — dans le respect dû à la sphère privée.

Les faits avancés ici devront être sourcés (CDC §6).',
   'brouillon'),

  ('Les dernières années', 'les-dernieres-annees',
   'Texte à rédiger : Retrait de la vie publique, derniers engagements, disparition en 1998.

Les faits avancés ici devront être sourcés (CDC §6).',
   'brouillon');

-- --- La période d'un repère ne se saisit plus --------------------------------
-- `repere.periode` portait l'une des quatre valeurs `p1`-`p4`, choisie par
-- l'éditeur dans un menu déroulant, et l'écran vérifiait que l'année de
-- classement tombait bien dans la période retenue. Deux saisies pour une seule
-- information : l'année suffit à désigner la période qui la contient.
--
-- La colonne part donc, et rien ne se perd — `tri` porte l'année, les périodes
-- portent leurs bornes, le rattachement se calcule. C'était la seule façon
-- d'éviter que douze périodes en base et quatre valeurs figées dans un ENUM
-- ne divergent au premier découpage revu.
--
-- `DROP COLUMN` lève une erreur si le fichier est rejoué, ce qui vaut mieux
-- qu'une modification silencieuse.
ALTER TABLE repere DROP COLUMN periode;
