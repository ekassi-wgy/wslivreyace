-- ============================================================================
-- Philippe Grégoire Yacé — amorce de la frise chronologique (lot G0)
--
-- La table `repere` et son écran d'administration existent depuis le lot C,
-- mais les deux frises publiques — accueil et biographie — portaient leurs
-- dates en dur dans le gabarit : ce qu'un éditeur saisissait n'arrivait nulle
-- part. Le lot G0 les branche sur cette table (README §9).
--
-- Ce fichier y verse **exactement les sept entrées qui étaient affichées**,
-- mot pour mot, de sorte que le site ne perde rien au passage. Elles arrivent
-- en `publie` pour cette seule raison : elles l'étaient déjà, de fait.
--
-- ATTENTION — ces libellés sont provisoires et le disent. Six des sept notices
-- portent la mention « à documenter » ou « à compléter », et le CDC §6 comme
-- le README §5 exigent que rien ne soit publié sur Yacé sans source. Ils sont
-- désormais corrigeables depuis le back-office, ce qui est tout l'objet de ce
-- lot ; les corriger reste à faire, et c'est à l'éditeur.
--
-- `en_avant` dit ce qui remonte sur l'accueil, où la place est comptée : les
-- quatre dates établies, comme le gabarit les affichait avant ce lot. Les trois
-- entrées encore à documenter restent sur la seule frise de la biographie.
--
-- **Ce fichier portait le numéro 008 et passait avant `sql/008_repere_avant.sql`,
-- qui pose la colonne `en_avant`.** L'ordre était intenable : sur une base
-- installée avant le lot G0 — la production — la colonne n'existait pas encore
-- au moment de cet `INSERT`, qui s'arrêtait sur `#1054 Champ 'en_avant' inconnu
-- dans field list`. Rien ne s'en voyait en local, où la base repart de
-- `001_schema.sql`, mis à jour dans le même commit que le lot. Les deux fichiers
-- ont échangé leurs numéros : la colonne se pose avant qu'on écrive dedans.
--
-- `tri` porte l'année numérique du classement, `annee` la date affichée. Les
-- deux diffèrent quand la date n'est pas établie : « — » se lit à l'écran,
-- 1935 range la ligne au bon endroit. Ces valeurs de tri sont des estimations
-- de position, pas des faits ; elles n'apparaissent nulle part.
--
-- À jouer une seule fois, **après `sql/008_repere_avant.sql`**, et seulement
-- sur une base dont la table `repere` est vide : le fichier n'a aucun garde-fou
-- contre le doublon, une ligne d'amorce n'ayant pas de clé naturelle sur
-- laquelle en poser un. Sur une base où l'éditeur a déjà saisi des repères, il
-- ne se joue pas du tout — c'est `008` qui a alors mis les siens en avant.
-- ============================================================================

SET NAMES utf8mb4;

INSERT INTO repere (annee, tri, periode, titre, notice, source, statut, en_avant) VALUES
  ('1920', 1920, 'p1', 'Naissance',
   'Philippe Grégoire Yacé naît en 1920. Lieu et date exacte à confirmer par l''éditeur.',
   NULL, 'publie', 1),

  ('—', 1935, 'p1', 'Formation',
   'Étape à documenter. Parcours scolaire et formation professionnelle.',
   NULL, 'publie', 0),

  ('—', 1945, 'p2', 'Entrée en vie publique',
   'Étape à documenter. Premiers engagements et responsabilités.',
   NULL, 'publie', 0),

  ('1959', 1959, 'p3', 'Présidence de l''Assemblée nationale',
   'Il accède à la présidence de l''Assemblée nationale de Côte d''Ivoire, fonction qu''il occupera pendant vingt et un ans.',
   NULL, 'publie', 1),

  ('—', 1965, 'p3', 'Secrétariat général du PDCI-RDA',
   'Dates à documenter. Responsabilités au sein du parti.',
   NULL, 'publie', 0),

  ('1980', 1980, 'p4', 'Conseil économique et social',
   'Il quitte le perchoir et prend la présidence du Conseil économique et social. Détails à documenter.',
   NULL, 'publie', 1),

  ('1998', 1998, 'p4', 'Disparition',
   'Notice à compléter.',
   NULL, 'publie', 1);
