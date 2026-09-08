<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Commandes de l'ouvrage (CDC §4.9).
 *
 * **Une commande ne naît pas dans le back-office.** Elle naît du tunnel public
 * (`/commander`, lot G3), qui la crée avec ce que le client a saisi et ce que
 * `App\Core\Boutique` a calculé. L'administration la suit : elle confirme,
 * marque la remise, annote. Aucun écran ne crée ni ne supprime de commande —
 * c'est une pièce comptable, et une pièce comptable ne s'efface pas parce
 * qu'elle gêne.
 *
 * **Le paiement se fait à la livraison** (lot G3). Aucune passerelle n'est
 * appelée : `App\Core\Paiement` la décrit depuis le lot E2 et continue de ne
 * pas être appelée. Les statuts portent pourtant les deux parcours — voir
 * SUITES — pour que la phase 2 se branche sans migration.
 *
 * Le statut suit un chemin, pas un menu déroulant : voir SUITES. Une commande
 * ne repasse jamais « initiée » — ce qui a eu lieu a eu lieu, et rien dans le
 * back-office ne peut le défaire.
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

    /**
     * Les six statuts, et à quel parcours chacun appartient.
     *
     *   initiee    les deux      la commande est arrivée, personne n'a encore agi
     *   confirmee  livraison     appelée et confirmée, l'exemplaire part
     *   payee      phase 2       la passerelle a encaissé, avant la remise
     *   echouee    phase 2       la passerelle a refusé
     *   annulee    les deux      le client renonce, ou la commande est injoignable
     *   remise     les deux      l'exemplaire est entre les mains du client
     *
     * En paiement à la livraison, `remise` vaut aussi encaissement : l'argent
     * change de main au même moment que le livre.
     */
    public const STATUTS = [
        'initiee'   => 'Initiée',
        'confirmee' => 'Confirmée',
        'payee'     => 'Payée',
        'echouee'   => 'Échouée',
        'annulee'   => 'Annulée',
        'remise'    => 'Remise',
    ];

    /**
     * Suites autorisées, par statut de départ.
     *
     * **Le chemin du paiement à la livraison**, qui est le seul ouvert
     * aujourd'hui :
     *
     *     initiée ──→ confirmée ──→ remise
     *        └────────────┴───────→ annulée
     *
     * On confirme d'abord — un appel au client, que le paiement à la livraison
     * impose de toute façon — puis on remet. « Remise » clôt le parcours et
     * vaut encaissement. « Annulée » est atteignable des deux premiers états :
     * un client renonce, ou reste injoignable.
     *
     * **`payee` n'est atteignable depuis nulle part**, et c'est voulu : rien
     * ne l'écrit tant qu'aucune passerelle n'encaisse, et un bouton
     * « Constater le paiement » sur une commande payable à la livraison ferait
     * enregistrer un encaissement qui n'a pas eu lieu. La phase 2 rouvrira
     * `initiee => ['payee', 'echouee', ...]` — une ligne, et aucune migration.
     */
    public const SUITES = [
        'initiee'   => ['confirmee', 'annulee'],
        'confirmee' => ['remise', 'annulee'],
        'payee'     => ['remise'],
        'echouee'   => [],
        'annulee'   => [],
        'remise'    => [],
    ];

    /**
     * Libellé du bouton qui mène à chaque statut.
     *
     * « Remise et encaissée » dit les deux choses parce qu'elles n'en font
     * qu'une en paiement à la livraison : celui qui clique vient de recevoir
     * l'argent. Écrire « Marquer remise » laisserait croire qu'un encaissement
     * reste à saisir ailleurs.
     */
    public const VERBES = [
        'confirmee' => 'Confirmer la commande',
        'payee'     => 'Constater le paiement',
        'echouee'   => 'Marquer échouée',
        'annulee'   => 'Annuler la commande',
        'remise'    => 'Remise et encaissée',
    ];

    /**
     * Ce qu'on écrit dans `mode_paiement` pour une commande payée à la
     * livraison. `passerelle` reste nulle : aucune passerelle n'a été appelée,
     * et l'y inscrire ferait croire le contraire à la relecture.
     */
    public const MODE_LIVRAISON = 'a-la-livraison';

    public const LIVRAISONS = [
        'retrait'   => 'Retrait',
        'livraison' => 'Livraison',
    ];

    /**
     * Enregistre une commande venue du tunnel public (lot G3).
     *
     * **Le total n'est pas reçu, il est passé par `App\Core\Boutique` qui
     * l'a calculé** : le contrôleur ne fait que transmettre. Le prix unitaire
     * et les frais sont figés dans la ligne, ainsi que la chaîne lisible de la
     * zone — le tarif changera, la zone peut être renommée, et une commande
     * doit garder ce qu'elle a facturé.
     *
     * La référence est fabriquée ici, à la dernière seconde, et réessayée sur
     * collision : deux commandes passées à la même seconde sont rares mais pas
     * impossibles, et la contrainte d'unicité de la colonne est ce qui fait
     * foi — pas un `SELECT` préalable, qui laisserait une fenêtre entre les
     * deux.
     *
     * @param array<string,mixed> $client  nom, email, tel, adresse
     * @param array<string,mixed> $calcul  ce que `Boutique::total()` a rendu
     */
    public static function passer(array $client, array $calcul, string $livraison): string
    {
        $pdo = Database::pdo();

        $sql = 'INSERT INTO commande
                  (reference, client_nom, client_email, client_tel,
                   quantite, prix_unitaire, frais_livraison, montant, devise,
                   mode_paiement, livraison, zone_id, zone_libelle, adresse, statut)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        // Cinq essais : au-delà, ce n'est plus une collision de référence mais
        // une panne, et l'exception doit remonter plutôt que boucler.
        for ($essai = 0; $essai < 5; $essai++) {
            $reference = self::reference();

            try {
                $pdo->prepare($sql)->execute([
                    $reference,
                    (string) $client['nom'],
                    (string) $client['email'],
                    ($client['tel'] ?? '') === '' ? null : (string) $client['tel'],
                    (int) $calcul['quantite'],
                    (int) $calcul['prix_unitaire'],
                    (int) $calcul['frais'],
                    (int) $calcul['total'],
                    \App\Core\Boutique::DEVISE,
                    self::MODE_LIVRAISON,
                    $livraison,
                    $calcul['zone_id'],
                    $calcul['zone_libelle'] === '' ? null : (string) $calcul['zone_libelle'],
                    ($client['adresse'] ?? '') === '' ? null : (string) $client['adresse'],
                    'initiee',
                ]);

                return $reference;
            } catch (\PDOException $e) {
                // 23000 : violation de contrainte. Seule l'unicité de la
                // référence peut se rejouer ; tout le reste doit remonter.
                if ($e->getCode() !== '23000' || !str_contains($e->getMessage(), 'uk_commande_reference')) {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException('Impossible de générer une référence de commande unique.');
    }

    /**
     * Une référence dictable au téléphone : `PGY-4F2K9A`.
     *
     * **Six signes, pris dans un alphabet sans O ni I ni 0 ni 1** — un client
     * la lit au téléphone à quelqu'un qui la note, et « O » contre « 0 » est
     * l'erreur classique. Le paiement à la livraison impose cet appel : la
     * référence doit survivre à la voix.
     *
     * Aléatoire et non séquentielle : une référence qui s'incrémente annonce à
     * chaque client combien d'exemplaires ont été vendus avant lui.
     */
    public static function reference(): string
    {
        $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';   // ni O, ni I, ni 0, ni 1
        $signes   = '';

        for ($i = 0; $i < 6; $i++) {
            $signes .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return 'PGY-' . $signes;
    }

    /** Une commande, retrouvée par sa référence — pour la page de confirmation. */
    public static function parReference(string $reference): ?array
    {
        return Database::one('SELECT * FROM commande WHERE reference = ?', [$reference]);
    }

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
    /**
     * La recette réellement encaissée.
     *
     * `payee` et `remise`, et rien d'autre. En paiement à la livraison,
     * l'argent n'arrive qu'à la remise : compter les commandes confirmées
     * gonflerait la recette de ce qui n'est pas encore payé — et une commande
     * confirmée peut encore être annulée sur le pas de la porte.
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
    /**
     * Combien d'exemplaires attendent d'être remis.
     *
     * `confirmee` et `payee` : la première est le parcours du paiement à la
     * livraison, la seconde celui de la phase 2. Les deux décrivent une
     * commande dont l'exemplaire n'est pas encore parti, et c'est ce que le
     * tableau de bord annonce.
     */
    public static function aRemettre(): int
    {
        return (int) (Database::one(
            "SELECT COUNT(*) AS n FROM commande WHERE statut IN ('confirmee','payee')"
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
