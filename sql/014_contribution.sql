-- ============================================================================
-- Philippe Grégoire Yacé — contributions du public (brief §5, lot G8)
--
-- « Vous avez connu Philippe Grégoire YACÉ ? » — le formulaire par lequel une
-- famille confie une photographie, une lettre, un enregistrement.
--
-- LA RÈGLE DU BRIEF, ÉCRITE DANS LE SCHÉMA : rien n'est publié
-- automatiquement. Une contribution n'entre pas dans `media` ni dans
-- `archive` ; elle attend dans sa propre table, et c'est un modérateur qui la
-- fait passer dans le fonds.
--
-- LES FICHIERS NE SONT PAS DANS `medias/`. C'est le point le plus important de
-- ce lot, et il ne va pas de soi : `medias/` est servi par Apache, donc un
-- fichier qui y est déposé est téléchargeable par qui devine son nom — publié
-- ou non. Un document envoyé par un inconnu, non encore relu, n'a rien à y
-- faire. Il attend dans `quarantaine/`, dossier fermé, et n'en sort qu'à
-- l'acceptation. Voir `App\Core\Quarantaine`.
--
-- LA CESSION DE DROITS EST HORODATÉE. Le contributeur confie un document dont
-- il détient les droits, et le site le publiera sous une licence qu'il faut
-- pouvoir prouver acceptée. `droits_le` porte l'instant de la case cochée.
-- ============================================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS contribution (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  -- --- Le contributeur -------------------------------------------------------
  nom           VARCHAR(120) NOT NULL,
  prenom        VARCHAR(120) NULL,
  email         VARCHAR(180) NOT NULL,   -- exigée : sans elle, pas de retour
  telephone     VARCHAR(40)  NULL,

  -- --- Ce qu'il confie -------------------------------------------------------
  description   TEXT         NOT NULL,
  date_approx   VARCHAR(60)  NULL,       -- « vers 1965 », « années 70 »
  source        VARCHAR(300) NULL,       -- d'où vient la pièce, qui la détient

  -- --- Droits ----------------------------------------------------------------
  droits_le     DATETIME     NULL,       -- horodatage de la case cochée

  -- --- Modération ------------------------------------------------------------
  -- `acceptee` et non `publiee` : accepter verse les fichiers dans la
  -- médiathèque, en brouillon. La publication reste un second geste, sur la
  -- notice d'archive que l'éditeur en tirera.
  statut        ENUM('en_attente','acceptee','refusee') NOT NULL DEFAULT 'en_attente',
  note          VARCHAR(500) NULL,       -- annotation interne du modérateur
  recu_le       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  traite_le     DATETIME NULL,
  traite_par    INT UNSIGNED NULL,
  ip_soumission VARBINARY(16) NULL,      -- anti-abus, jamais affichée en public

  KEY ix_contribution_statut (statut, recu_le),
  CONSTRAINT fk_contribution_traite_par FOREIGN KEY (traite_par)
    REFERENCES utilisateur (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --- Les fichiers en quarantaine ---------------------------------------------
-- `chemin` est relatif à `quarantaine/`, jamais à `medias/` : les deux dossiers
-- n'ont pas le même régime, et confondre les deux est exactement l'erreur que
-- ce lot cherche à rendre impossible.
--
-- `nom_origine` est conservé pour le modérateur — c'est souvent la seule
-- indication de ce que contient le fichier — mais il n'est jamais employé comme
-- nom sur le disque : le nom de destination est fabriqué, comme partout.
CREATE TABLE IF NOT EXISTS contribution_fichier (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  contribution_id INT UNSIGNED NOT NULL,
  chemin          VARCHAR(255) NOT NULL,   -- relatif à quarantaine/
  nom_origine     VARCHAR(255) NULL,       -- affiché au modérateur, jamais employé
  famille         ENUM('image','document','audio') NOT NULL DEFAULT 'image',
  octets          INT UNSIGNED NULL,
  media_id        INT UNSIGNED NULL,       -- rempli à l'acceptation
  KEY ix_contribution_fichier (contribution_id),
  CONSTRAINT fk_contribution_fichier_contribution FOREIGN KEY (contribution_id)
    REFERENCES contribution (id) ON DELETE CASCADE,
  CONSTRAINT fk_contribution_fichier_media FOREIGN KEY (media_id)
    REFERENCES media (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
