<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Commandes de l'ouvrage (CDC §4.9).
 *
 * **Une commande ne naît pas dans le back-office.** Elle naît du tunnel de
 * paiement, qui la crée et l'inscrit ici avec ce que la passerelle a rendu.
 * L'administration la suit : elle constate le paiement, marque la remise,
 * annote. Aucun écran ne crée ni ne supprime de commande — c'est une pièce
 * comptable, et une pièce comptable ne s'efface pas parce qu'elle gêne.
 *
 * Le statut suit un chemin, pas un menu déroulant : voir SUITES. Une commande
 * ne repasse jamais « initiée » — le paiement a eu lieu ou non, et rien dans
 * le back-office ne peut le défaire.
 */
final class Commande extends Modele
{
    protected const TABLE = 'commande';

    /**
     * Ce qu'un écran d'administration a le droit d'écrire : l'annotation de
     * suivi, et rien d'autre. Le montant, le client et la référence viennent
     * du paiement ; les corriger à la main ferait diverger la commande de ce
     * que la passerelle a enregistré, et c'est elle qui fait foi en cas de
     * contestation. Le statut passe par `avancer()`, qui pose aussi la trace.
     */
    protected const ASSIGNABLES = ['note'];

    /** Les dernières d'abord : une commande se traite quand elle arrive. */
    protected const ORDRE = 'cree_le DESC, id DESC';

    public const STATUTS = [
        'initiee' => 'Initiée',
        'payee'   => 'Payée',
        'echouee' => 'Échouée',
        'remise'  => 'Remise',
    ];

    /**
     * Suites autorisées, par statut de départ.
     *
     * « Payée » depuis « initiée » se saisit à la main quand le paiement a été
     * constaté auprès de la passerelle — le back-office ne décide pas d'un
     * paiement, il en prend acte. « Remise » clôt le parcours : l'exemplaire
     * est entre les mains du client. Une commande échouée reste en base, elle
     * dit qu'une tentative a eu lieu.
     */
    public const SUITES = [
        'initiee' => ['payee', 'echouee'],
        'payee'   => ['remise'],
        'echouee' => [],
        'remise'  => [],
    ];

    /** Libellé du bouton qui mène à chaque statut. */
    public const VERBES = [
        'payee'   => 'Constater le paiement',
        'echouee' => 'Marquer échouée',
        'remise'  => 'Marquer remise',
    ];

    public const LIVRAISONS = [
        'retrait'   => 'Retrait',
        'livraison' => 'Livraison',
    ];

    /**
     * @param string|null $statut null = toutes
     * @return array<int,array<string,mixed>>
     */
    public static function listerPar(?string $statut = null): array
    {
        $sql = 'SELECT c.*, u.nom AS remise_par_nom
                  FROM commande c
                  LEFT JOIN utilisateur u ON u.id = c.remise_par';
        $params = [];

        if ($statut !== null) {
            $sql .= ' WHERE c.statut = ?';
            $params[] = $statut;
        }

        return Database::all($sql . ' ORDER BY c.cree_le DESC, c.id DESC', $params);
    }

    /** @return array<string,mixed>|null */
    public static function trouver(int $id): ?array
    {
        return Database::one(
            'SELECT c.*, u.nom AS remise_par_nom
               FROM commande c
               LEFT JOIN utilisateur u ON u.id = c.remise_par
              WHERE c.id = ?',
            [$id]
        );
    }

    /** @return array<string,int> nombre par statut, plus 'tous' */
    public static function compteurs(): array
    {
        $n = array_fill_keys(array_keys(self::STATUTS), 0) + ['tous' => 0];

        foreach (Database::all('SELECT statut, COUNT(*) AS n FROM commande GROUP BY statut') as $l) {
            $n[$l['statut']] = (int) $l['n'];
            $n['tous'] += (int) $l['n'];
        }

        return $n;
    }

    /**
     * Ce qui a été encaissé, et en combien d'exemplaires.
     *
     * « Payée » et « remise » comptent, les deux autres non : une commande
     * initiée n'a rien encaissé, une échouée non plus. Le total est rendu par
     * devise — la passerelle en accepte plusieurs, additionner des francs CFA
     * et des euros ne voudrait rien dire.
     *
     * @return array<int,array{devise:string,montant:float,exemplaires:int,commandes:int}>
     */
    public static function encaisse(): array
    {
        $lignes = Database::all(
            "SELECT devise,
                    SUM(montant)  AS montant,
                    SUM(quantite) AS exemplaires,
                    COUNT(*)      AS commandes
               FROM commande
              WHERE statut IN ('payee','remise')
           GROUP BY devise
           ORDER BY montant DESC"
        );

        return array_map(static fn(array $l): array => [
            'devise'      => (string) $l['devise'],
            'montant'     => (float) $l['montant'],
            'exemplaires' => (int) $l['exemplaires'],
            'commandes'   => (int) $l['commandes'],
        ], $lignes);
    }

    /** Commandes payées mais pas encore remises — ce qui attend quelqu'un. */
    public static function aRemettre(): int
    {
        return (int) (Database::one(
            "SELECT COUNT(*) AS n FROM commande WHERE statut = 'payee'"
        )['n'] ?? 0);
    }

    public static function suiteAutorisee(string $depuis, string $vers): bool
    {
        return in_array($vers, self::SUITES[$depuis] ?? [], true);
    }

    /**
     * Fait avancer une commande d'un statut à l'autre.
     *
     * La transition est vérifiée par l'appelant ; la trace de remise est posée
     * ici, dans la même requête que le statut — deux écritures séparées
     * laisseraient une commande remise sans savoir par qui, si la seconde
     * échouait.
     */
    public static function avancer(int $id, string $statut, int $parUtilisateur): void
    {
        if (!isset(self::STATUTS[$statut])) {
            throw new \InvalidArgumentException("Statut inconnu : $statut");
        }

        if ($statut === 'remise') {
            Database::pdo()->prepare(
                'UPDATE commande SET statut = ?, remise_le = NOW(), remise_par = ? WHERE id = ?'
            )->execute([$statut, $parUtilisateur, $id]);
            return;
        }

        Database::pdo()
            ->prepare('UPDATE commande SET statut = ? WHERE id = ?')
            ->execute([$statut, $id]);
    }

    /**
     * Montant lisible : « 25 000 F CFA ».
     *
     * Les décimales ne sont affichées que si elles existent — le franc CFA n'a
     * pas de subdivision en usage, écrire « 25 000,00 » ferait bureaucratique
     * sans rien apprendre. L'euro, lui, les garde.
     */
    public static function montant(float $montant, string $devise): string
    {
        $decimales = fmod($montant, 1.0) === 0.0 ? 0 : 2;
        $chiffres  = number_format($montant, $decimales, ',', ' ');

        return $chiffres . ' ' . ($devise === 'XOF' ? 'F CFA' : $devise);
    }
}
