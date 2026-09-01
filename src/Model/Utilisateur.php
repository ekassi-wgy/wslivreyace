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
}
