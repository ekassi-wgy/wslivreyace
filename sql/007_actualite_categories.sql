-- ============================================================================
-- Philippe Grégoire Yacé — catégories d'actualités (lot G0)
--
-- Le brief du 7 septembre (README §9) énumère ce que la rubrique Actualités
-- doit porter : sortie et promotion du livre, cérémonies, commémorations,
-- conférences, reportages, interviews, nouvelles archives retrouvées,
-- événements. Quatre de ces registres n'avaient pas de case.
--
-- Les cinq valeurs d'origine sont conservées telles quelles : des lignes les
-- portent peut-être déjà, et une ENUM se rétrécit en perdant des données.
-- Les quatre nouvelles s'ajoutent à la fin — l'ordre d'une ENUM MySQL n'a
-- d'importance que pour `ORDER BY categorie`, dont aucune requête ne se sert :
-- l'ordre d'affichage vient de `App\Model\Actualite::CATEGORIES`.
--
-- « commémoration » n'entre pas dans la liste : `hommage` la couvre, et deux
-- cases pour une même chose obligent l'éditeur à choisir entre elles à chaque
-- saisie, sans qu'aucune règle ne le guide.
--
-- À jouer une seule fois. Rejouer ce fichier est sans effet — la colonne aura
-- déjà la définition demandée — mais ne rattrape rien si elle en a une autre.
-- ============================================================================

SET NAMES utf8mb4;

ALTER TABLE actualite
  MODIFY categorie ENUM(
    'parution',
    'dedicace',
    'presse',
    'hommage',
    'evenement',
    'conference',
    'reportage',
    'interview',
    'archive'
  ) NOT NULL DEFAULT 'parution';
