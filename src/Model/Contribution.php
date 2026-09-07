<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;
use App\Core\Quarantaine;

/**
 * Contributions du public (brief §5, lot G8).
 *
 * **Rien n'entre dans le fonds par ici.** Une contribution attend dans sa
 * propre table, ses fichiers dans un dossier fermé ; c'est un modérateur qui
 * la fait passer. Le brief l'exige, et le schéma le rend structurel plutôt que
 * de le confier à la vigilance.
 *
 * @see \App\Core\Quarantaine pour les fichiers
 */
final class Contribution extends Modele
{
    protected const TABLE = 'contribution';

    /**
     * Aucune colonne assignable.
     *
     * Une contribution n'est jamais écrite par `Modele::creer()` : elle arrive
     * par `deposer()`, qui fixe elle-même le statut et l'horodatage. Laisser
     * la liste ouverte aurait permis à un champ `statut` glissé dans le POST
     * d'arriver en base — et une contribution qui se publie elle-même est
     * exactement ce que ce lot interdit.
     */
    protected const ASSIGNABLES = [];

    /** File de modération : les plus anciennes en tête, on les traite dans l'ordre. */
    protected const ORDRE = "statut = 'en_attente' DESC, recu_le ASC, id ASC";

    public const STATUTS = [
        'en_attente' => 'En attente',
        'acceptee'   => 'Acceptée',
        'refusee'    => 'Refusée',
    ];

    /**
     * Enregistre une contribution et ses fichiers.
     *
     * Le statut n'est pas un paramètre : il vaut `en_attente`, toujours.
     *
     * @param array<string,mixed> $donnees champs validés du formulaire
     * @param array<int,array{chemin:string,famille:string,octets:int,nom_origine:string}> $fichiers
     */
    public static function deposer(array $donnees, array $fichiers): int
    {
        $pdo = Database::pdo();

        $pdo->prepare(
            'INSERT INTO contribution (nom, prenom, email, telephone, description,'
            . ' date_approx, source, droits_le, ip_soumission)'
            . ' VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)'
        )->execute([
            $donnees['nom'],
            $donnees['prenom']    ?: null,
            $donnees['email'],
            $donnees['telephone'] ?: null,
            $donnees['description'],
            $donnees['date_approx'] ?: null,
            $donnees['source']      ?: null,
            self::ip(),
        ]);

        $id = (int) $pdo->lastInsertId();

        $st = $pdo->prepare(
            'INSERT INTO contribution_fichier (contribution_id, chemin, nom_origine, famille, octets)'
            . ' VALUES (?, ?, ?, ?, ?)'
        );

        foreach ($fichiers as $f) {
            $st->execute([$id, $f['chemin'], $f['nom_origine'], $f['famille'], $f['octets']]);
        }

        return $id;
    }

    /**
     * L'adresse du visiteur, sous forme binaire, ou null.
     *
     * Anti-abus, jamais affichée en public — même règle que les témoignages et
     * les messages de contact.
     */
    private static function ip(): ?string
    {
        $brut = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
        $bin  = $brut === '' ? false : @inet_pton($brut);

        return $bin === false ? null : $bin;
    }

    /** @return array<int,array<string,mixed>> */
    public static function fichiers(int $contributionId): array
    {
        return Database::all(
            'SELECT * FROM contribution_fichier WHERE contribution_id = ? ORDER BY id ASC',
            [$contributionId]
        );
    }

    /** Un fichier précis d'une contribution, pour la route de lecture protégée. */
    public static function fichier(int $contributionId, int $fichierId): ?array
    {
        return Database::one(
            'SELECT * FROM contribution_fichier WHERE id = ? AND contribution_id = ?',
            [$fichierId, $contributionId]
        );
    }

    /** Nombre de contributions en attente, pour la pastille du menu. */
    public static function enAttente(): int
    {
        return (int) (Database::one(
            "SELECT COUNT(*) AS n FROM contribution WHERE statut = 'en_attente'"
        )['n'] ?? 0);
    }

    /**
     * Accepte une contribution : ses fichiers entrent dans la médiathèque.
     *
     * **En brouillon**, et c'est voulu : accepter dit « ce fonds nous
     * intéresse », pas « publions-le tel quel ». Il reste à légender, créditer
     * et rattacher à une notice — trois gestes que personne ne peut faire à la
     * place de l'éditeur.
     *
     * Un fichier disparu du disque n'interrompt pas l'acceptation : les autres
     * passent, et la ligne garde `media_id` à NULL, ce qui se voit à l'écran.
     *
     * @return int nombre de fichiers effectivement versés
     */
    public static function accepter(int $id, int $moderateurId, string $note = ''): int
    {
        $verses = 0;

        foreach (self::fichiers($id) as $f) {
            if ($f['media_id'] !== null) {
                continue;   // déjà versé : une seconde acceptation ne double pas
            }

            $recu = Quarantaine::promouvoir((string) $f['chemin'], 'contribution');

            if ($recu === null) {
                continue;
            }

            $mediaId = Media::creer([
                'fichier' => $recu['fichier'],
                'famille' => (string) $f['famille'],
                'titre'   => mb_substr((string) ($f['nom_origine'] ?? 'Contribution'), 0, 200),
                'largeur' => $recu['largeur'],
                'hauteur' => $recu['hauteur'],
                'octets'  => $recu['octets'],
                'statut'  => 'brouillon',
            ]);

            Database::pdo()
                ->prepare('UPDATE contribution_fichier SET media_id = ? WHERE id = ?')
                ->execute([$mediaId, (int) $f['id']]);

            $verses++;
        }

        self::trancher($id, 'acceptee', $moderateurId, $note);

        return $verses;
    }

    /**
     * Refuse une contribution, et efface ses fichiers.
     *
     * Un refus ne laisse rien sur le disque : le site n'a aucune raison de
     * garder le document d'un tiers qu'il a décidé de ne pas publier.
     */
    public static function refuser(int $id, int $moderateurId, string $note = ''): void
    {
        foreach (self::fichiers($id) as $f) {
            if ($f['media_id'] === null) {
                Quarantaine::effacer((string) $f['chemin']);
            }
        }

        self::trancher($id, 'refusee', $moderateurId, $note);
    }

    private static function trancher(int $id, string $statut, int $moderateurId, string $note): void
    {
        Database::pdo()->prepare(
            'UPDATE contribution SET statut = ?, note = ?, traite_le = NOW(), traite_par = ? WHERE id = ?'
        )->execute([$statut, $note === '' ? null : mb_substr($note, 0, 500), $moderateurId, $id]);
    }

    /**
     * Supprime une contribution et ses fichiers restés en quarantaine.
     *
     * `ON DELETE CASCADE` emporte les lignes ; les fichiers du disque, non —
     * aucune contrainte ne les connaît. D'où ce passage explicite.
     */
    public static function supprimer(int $id): void
    {
        foreach (self::fichiers($id) as $f) {
            if ($f['media_id'] === null) {
                Quarantaine::effacer((string) $f['chemin']);
            }
        }

        parent::supprimer($id);
    }
}
