<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Messages reçus par le formulaire de contact (CDC §4.11).
 *
 * **Ils sont écrits en base, jamais envoyés par courriel — et c'est la
 * décision principale de ce lot.** `mail()` sur un hébergement mutualisé
 * échoue en silence ou finit en indésirable, sans que personne l'apprenne : un
 * message perdu est pire que pas de formulaire du tout. La base est donc la
 * source de vérité, et la boîte de réception vit dans le back-office. Une
 * notification par courriel pourra s'ajouter par-dessus ; elle ne remplacera
 * pas le stockage.
 *
 * Même mécanique que les témoignages : rien n'est assignable depuis un
 * formulaire — un message ne se modifie pas, il se lit et se traite. D'où
 * l'absence d'ASSIGNABLES et le dépôt par une méthode dédiée.
 *
 * `ip_soumission` ne sort jamais d'ici : elle sert à repérer un abus.
 */
final class Message extends Modele
{
    protected const TABLE = 'message';

    /** Aucun champ n'entre par formulaire : voir `deposer()`. */
    protected const ASSIGNABLES = [];

    /** La boîte se lit du plus récent au plus ancien. */
    protected const ORDRE = 'recu_le DESC, id DESC';

    public const STATUTS = [
        'nouveau' => 'Nouveau',
        'traite'  => 'Traité',
    ];

    /**
     * Motifs proposés au visiteur.
     *
     * Une liste fermée plutôt qu'un champ libre : elle sert au tri de la boîte
     * de réception, et « Objet : bonjour » ne trie rien. Les clés sont celles
     * de l'ENUM en base — une valeur hors liste est refusée à la validation.
     */
    public const SUJETS = [
        'ouvrage'  => "Une question sur l'ouvrage",
        'commande' => 'Une commande',
        'presse'   => 'Presse ou média',
        'archives' => "Un document ou une archive à proposer",
        'autre'    => 'Autre',
    ];

    /**
     * Dépôt par le formulaire public.
     *
     * Le statut est écrit ici et n'est pas un paramètre : un message arrive
     * toujours en `nouveau`. L'IP est relevée pour repérer un abus, jamais
     * affichée — `REMOTE_ADDR` seul, comme partout ailleurs : `X-Forwarded-For`
     * vient du client et se falsifie.
     *
     * @param array{nom:string,email:string,sujet:string,contenu:string} $donnees
     */
    public static function deposer(array $donnees): int
    {
        $pdo = Database::pdo();

        $pdo->prepare(
            "INSERT INTO message (nom, email, sujet, contenu, statut, ip_soumission)
             VALUES (?, ?, ?, ?, 'nouveau', ?)"
        )->execute([
            $donnees['nom'],
            $donnees['email'],
            isset(self::SUJETS[$donnees['sujet']]) ? $donnees['sujet'] : 'autre',
            $donnees['contenu'],
            inet_pton($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0') ?: inet_pton('0.0.0.0'),
        ]);

        return (int) $pdo->lastInsertId();
    }

    /**
     * La boîte de réception, éventuellement filtrée.
     *
     * Les nouveaux remontent en tête quel que soit leur âge : c'est la seule
     * pile sur laquelle il y a quelque chose à faire. Même règle que la file
     * de modération des témoignages.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function listerPar(?string $statut = null): array
    {
        $sql = 'SELECT m.*, u.nom AS traiteur_nom
                  FROM message m
                  LEFT JOIN utilisateur u ON u.id = m.traite_par';
        $params = [];

        if ($statut !== null) {
            $sql .= ' WHERE m.statut = ?';
            $params[] = $statut;
        }

        $sql .= " ORDER BY m.statut = 'nouveau' DESC, m.recu_le DESC, m.id DESC";

        return Database::all($sql, $params);
    }

    /** @return array<string,int> nombre par statut, plus 'tous' */
    public static function compteurs(): array
    {
        $n = ['nouveau' => 0, 'traite' => 0, 'tous' => 0];

        foreach (Database::all('SELECT statut, COUNT(*) AS n FROM message GROUP BY statut') as $l) {
            $n[$l['statut']] = (int) $l['n'];
            $n['tous'] += (int) $l['n'];
        }

        return $n;
    }

    /**
     * Marque un message traité, ou le remet en attente.
     *
     * Qui a traité, et quand, est conservé — même raison que pour la
     * modération : sur une boîte partagée, « qui s'en est occupé ? » doit
     * avoir une réponse, sans quoi deux personnes répondent au même message.
     */
    public static function marquer(int $id, string $statut, int $utilisateurId): void
    {
        if (!isset(self::STATUTS[$statut])) {
            throw new \InvalidArgumentException("Statut inconnu : $statut");
        }

        // Reprendre un message efface la trace : il redevient à traiter, et
        // laisser un traiteur sur une ligne en attente ferait croire à un
        // travail déjà fait.
        if ($statut === 'nouveau') {
            Database::pdo()
                ->prepare("UPDATE message SET statut = 'nouveau', traite_le = NULL, traite_par = NULL WHERE id = ?")
                ->execute([$id]);
            return;
        }

        Database::pdo()->prepare(
            'UPDATE message SET statut = ?, traite_le = NOW(), traite_par = ? WHERE id = ?'
        )->execute([$statut, $utilisateurId, $id]);
    }

    /** Libellé d'affichage d'un sujet ; la clé brute si elle est inconnue. */
    public static function sujet(?string $cle): string
    {
        return self::SUJETS[(string) $cle] ?? (string) $cle;
    }
}
