<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Database;

/**
 * Comptes du back-office.
 *
 * Le mot de passe n'existe en clair nulle part : ni en base, ni en session, ni
 * dans un journal. Seul `password_hash()` en voit la valeur.
 */
final class Utilisateur
{
    /**
     * Empreinte factice, utilisée quand l'adresse saisie n'existe pas.
     *
     * Sans elle, une adresse inconnue répond tout de suite et une adresse
     * connue attend le calcul de `password_verify` : l'écart de durée suffit à
     * dresser la liste des comptes. On paie donc le même calcul dans les deux
     * cas.
     *
     * C'est un vrai hachage, produit par `password_hash(PASSWORD_DEFAULT)` sur
     * une valeur aléatoire jamais notée — et c'est essentiel : une chaîne
     * inventée serait rejetée comme malformée en 0,16 ms, contre 217 ms pour un
     * hachage réel. Mesuré : le leurre et un compte existant coûtent 216 ms
     * chacun.
     *
     * À regénérer si le coût par défaut de PHP change : un leurre moins cher
     * que les comptes réels rouvrirait l'écart qu'il est censé fermer.
     */
    private const EMPREINTE_LEURRE =
        '$2y$12$lQiaaZscu90je2qq7s9raufNh9bIio/j1ieqREITsKBG8CveJgpuu';

    /** @return array<string,mixed>|null */
    public static function parEmail(string $email): ?array
    {
        return Database::one(
            'SELECT id, email, mot_de_passe, nom, role, actif
               FROM utilisateur
              WHERE email = ?',
            [mb_strtolower(trim($email))]
        );
    }

    /** @return array<string,mixed>|null */
    public static function parId(int $id): ?array
    {
        return Database::one(
            'SELECT id, email, nom, role, actif FROM utilisateur WHERE id = ?',
            [$id]
        );
    }

    /**
     * Vérifie un couple adresse / mot de passe.
     *
     * Retourne le compte, ou null. Un compte désactivé échoue comme un mot de
     * passe faux : rien dans la réponse ne distingue les deux cas.
     *
     * @return array<string,mixed>|null
     */
    public static function authentifier(string $email, string $motDePasse): ?array
    {
        $compte = self::parEmail($email);
        $empreinte = $compte['mot_de_passe'] ?? self::EMPREINTE_LEURRE;

        $correspond = password_verify($motDePasse, $empreinte);

        if ($compte === null || !$correspond || (int) $compte['actif'] !== 1) {
            return null;
        }

        // Le coût de hachage évolue avec le matériel : on réhache au passage
        // si les réglages actuels sont plus exigeants que ceux d'origine.
        if (password_needs_rehash($empreinte, PASSWORD_DEFAULT)) {
            self::changerMotDePasse((int) $compte['id'], $motDePasse);
        }

        unset($compte['mot_de_passe']);
        return $compte;
    }

    public static function changerMotDePasse(int $id, string $motDePasse): void
    {
        Database::pdo()
            ->prepare('UPDATE utilisateur SET mot_de_passe = ? WHERE id = ?')
            ->execute([password_hash($motDePasse, PASSWORD_DEFAULT), $id]);
    }

    public static function creer(string $email, string $motDePasse, string $nom, string $role = 'editeur'): int
    {
        $pdo = Database::pdo();
        $pdo->prepare(
            'INSERT INTO utilisateur (email, mot_de_passe, nom, role)
             VALUES (?, ?, ?, ?)'
        )->execute([
            mb_strtolower(trim($email)),
            password_hash($motDePasse, PASSWORD_DEFAULT),
            trim($nom),
            $role,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function combien(): int
    {
        return (int) Database::pdo()
            ->query('SELECT COUNT(*) FROM utilisateur')
            ->fetchColumn();
    }

    /**
     * Les comptes, pour l'écran d'administration.
     *
     * `mot_de_passe` n'est pas dans la sélection, et ne le sera jamais : une
     * empreinte n'a rien à faire dans une vue, même échappée. Les colonnes
     * sont énumérées plutôt qu'un `*` — c'est ce qui rend cette garantie
     * lisible d'un coup d'œil.
     *
     * @return array<int,array<string,mixed>>
     */
    public static function lister(): array
    {
        return Database::all(
            'SELECT id, email, nom, role, actif, cree_le
               FROM utilisateur
              ORDER BY actif DESC, nom ASC, id ASC'
        );
    }

    /** Le nom, le rôle et l'état. L'adresse et le mot de passe ont leurs voies. */
    public static function modifier(int $id, string $nom, string $role, bool $actif): void
    {
        Database::pdo()
            ->prepare('UPDATE utilisateur SET nom = ?, role = ?, actif = ? WHERE id = ?')
            ->execute([trim($nom), $role, $actif ? 1 : 0, $id]);
    }

    /**
     * Change l'adresse de connexion.
     *
     * Séparée de `modifier()` : c'est l'identifiant du compte, et la changer
     * n'a rien d'anodin — l'unicité est vérifiée avant, en base ensuite.
     */
    public static function changerEmail(int $id, string $email): void
    {
        Database::pdo()
            ->prepare('UPDATE utilisateur SET email = ? WHERE id = ?')
            ->execute([mb_strtolower(trim($email)), $id]);
    }

    public static function emailPris(string $email, ?int $exclure = null): bool
    {
        $sql = 'SELECT id FROM utilisateur WHERE email = ?';
        $params = [mb_strtolower(trim($email))];

        if ($exclure !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $exclure;
        }

        return Database::one($sql, $params) !== null;
    }

    /**
     * Combien d'administrateurs actifs, en excluant éventuellement un compte.
     *
     * Sert à refuser la manœuvre qui laisserait le back-office sans personne
     * pour y entrer : se rétrograder, se désactiver, ou retirer le dernier
     * administrateur. La reprise passerait alors par la ligne de commande.
     */
    public static function adminsActifs(?int $sauf = null): int
    {
        $sql = "SELECT COUNT(*) AS n FROM utilisateur WHERE role = 'admin' AND actif = 1";
        $params = [];

        if ($sauf !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $sauf;
        }

        return (int) (Database::one($sql, $params)['n'] ?? 0);
    }

    /**
     * Mot de passe fabriqué, quand l'administrateur n'en propose pas.
     *
     * Deux raisons de le proposer : un mot de passe choisi à la volée pour
     * quelqu'un d'autre est faible par construction, et il finit recopié dans
     * un courriel. Celui-ci est tiré de `random_bytes`, montré une seule fois,
     * et jamais écrit ailleurs qu'à l'écran.
     *
     * L'alphabet écarte les caractères qui se confondent à la lecture — O/0,
     * I/l/1 — parce que ce mot de passe sera dicté ou recopié à la main.
     */
    public static function motDePasseFabrique(int $longueur = 16): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
        $max = strlen($alphabet) - 1;
        $mdp = '';

        for ($i = 0; $i < $longueur; $i++) {
            $mdp .= $alphabet[random_int(0, $max)];
        }

        return $mdp;
    }
}
