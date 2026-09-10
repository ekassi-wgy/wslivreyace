-- ============================================================================
-- Philippe Grégoire Yacé — les citations sortent du lexique (lot G14)
--
-- CE QUE CE FICHIER RÉPARE. Trois blocs de citation du site public tiraient
-- leur texte de `src/lang/fr.php`, c'est-à-dire du code :
--
--   templates/pages/accueil.php     accueil.extrait.*      « Extrait »
--   templates/pages/livre.php       livre.extrait.*        « Extrait »
--   templates/pages/biographie.php  biographie.citations.* « Citations »
--
-- Aucun n'a jamais reçu de contenu : les trois affichent en ligne leur propre
-- consigne de remplissage — « Emplacement réservé à un extrait de l'ouvrage,
-- à choisir par l'éditeur ». Les deux premiers sont de surcroît le même texte
-- écrit deux fois, mot pour mot, sous deux jeux de clés. C'est le même
-- déplacement qu'au lot G0 pour la frise et au lot G12 pour les points de
-- vente : ce qui était du gabarit devient de la donnée.
--
-- POURQUOI UNE TABLE ET NON TROIS PARAMÈTRES. `parametre` aurait pu porter
-- « citation_accueil_texte » et ses jumeaux — et aurait figé une citation par
-- emplacement, sans mémoire de celles qu'on n'affiche pas. Or une citation
-- qu'on retire n'est pas une citation qu'on jette : elle a été sourcée,
-- vérifiée, et servira ailleurs ou plus tard. La table en garde un fonds ;
-- l'éditeur y puise.
--
-- LES DEUX RÉGIMES, ET POURQUOI IL EN FAUT DEUX.
--
--   `statut`   dit si la citation est prête — brouillon ou publiée. C'est le
--              régime de tout le contenu du site, et il ne change pas ici.
--   `en_avant` dit laquelle des citations prêtes occupe son emplacement.
--
-- Un seul des deux ne suffirait pas. Sans `statut`, une citation en cours de
-- vérification serait indistinguable d'une citation écartée. Sans `en_avant`,
-- deux citations publiées pour le même emplacement laisseraient à MySQL le
-- soin de choisir laquelle paraît — ce qu'aucun ordre ne garantit.
--
-- **L'activation est exclusive**, tenue par `App\Model\Citation::activer()` :
-- mettre une citation en avant retire la mise en avant des autres du même
-- emplacement. Une case à cocher qui se comporte comme un bouton radio, parce
-- qu'un emplacement ne montre qu'une citation. Sans cette règle, l'éditeur
-- coche deux citations et se demande laquelle le site affiche.
--
-- POURQUOI `emplacement` ET NON UN SIMPLE TYPE. Les trois blocs n'attendent
-- pas la même chose : les deux « Extrait » citent l'ouvrage — leur source est
-- un chapitre —, celui de la biographie cite Yacé — sa source est un lieu et
-- une date. Un ENUM d'emplacement dit à l'éditeur où sa citation va paraître,
-- ce qui est la seule question qu'il se pose ; le libellé du champ Source
-- suit l'emplacement choisi, sur la fiche.
--
-- AUCUNE AMORCE, ET C'EST DÉLIBÉRÉ. Les trois blocs ne portaient pas de
-- contenu à reprendre, seulement leur consigne de remplissage. Les verser
-- serait publier une citation que personne n'a écrite ; en inventer une,
-- attribuer un propos à un homme d'État sans source — ce que le gabarit de la
-- biographie interdit en toutes lettres depuis le premier jour :
--
--     <!-- CITATIONS — aucun propos ne doit être attribué sans source vérifiée -->
--
-- La table part donc vide, et **les trois sections disparaissent du site
-- jusqu'à ce que l'éditeur les remplisse**. C'est un progrès, pas une perte :
-- un bandeau qui affiche « Emplacement réservé » est pire qu'un bandeau absent.
--
-- À jouer une seule fois, après `sql/017_point_de_vente.sql`.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS citation (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  -- Où la citation paraît. Les trois valeurs correspondent aux trois blocs
  -- existants ; en ajouter un demandera d'étendre cet ENUM et le gabarit
  -- concerné, ce qui est le bon niveau de friction pour une section de page.
  emplacement ENUM('accueil','livre','biographie') NOT NULL,

  -- Le texte cité. TEXT et non VARCHAR : un extrait d'ouvrage peut courir sur
  -- un paragraphe entier, et la longueur utile se règle à la saisie plutôt
  -- que par une limite de colonne qui tronquerait sans rien dire.
  texte       TEXT NOT NULL,

  -- La provenance, telle qu'elle s'affiche sous la citation. Facultative :
  -- une citation se saisit parfois avant que sa source soit établie — elle
  -- reste alors en brouillon, ce que la liste signale.
  source      VARCHAR(200) NULL,

  statut      ENUM('brouillon','publie') NOT NULL DEFAULT 'brouillon',
  en_avant    TINYINT(1) NOT NULL DEFAULT 0,

  cree_le     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  maj_le      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  -- L'index de la seule requête que les pages publiques posent : la citation
  -- affichée d'un emplacement.
  KEY ix_citation_affichee (emplacement, statut, en_avant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
