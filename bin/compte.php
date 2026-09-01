<?php
declare(strict_types=1);

/**
 * Création et réinitialisation des comptes du back-office, en ligne de commande.
 *
 *   php bin/compte.php creer   <adresse> <nom> [admin|editeur]
 *   php bin/compte.php motdepasse <adresse>
 *   php bin/compte.php lister
 *
 * Pourquoi en CLI et pas par une page d'installation : une page qui crée le
 * premier administrateur est ouverte par définition, et il suffit de l'oublier
 * en ligne pour offrir le site. Ici, il faut déjà un accès au serveur.
 *
 * Le mot de passe n'est jamais passé en argument : il serait lisible dans
 * l'historique du shell et dans la liste des processus. Il est demandé de façon
 * interactive, sans écho.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Ce script ne s'exécute qu'en ligne de commande.\n");
}

require dirname(__DIR__) . '/src/bootstrap.php';

use App\Core\Database;
use App\Model\Utilisateur;

const LONGUEUR_MINIMALE = 12;

$action = $argv[1] ?? '';

try {
    match ($action) {
        'creer'      => creer($argv),
        'motdepasse' => motDePasse($argv),
        'lister'     => lister(),
        default      => aide(),
    };
} catch (Throwable $e) {
    fwrite(STDERR, "Erreur : " . $e->getMessage() . "\n");
    exit(1);
}

function aide(): void
{
    echo <<<TXT
    Comptes du back-office — Philippe Grégoire Yacé

      php bin/compte.php creer <adresse> <nom> [admin|editeur]
      php bin/compte.php motdepasse <adresse>
      php bin/compte.php lister

    Le mot de passe est demandé de façon interactive, jamais en argument.

    TXT;
    exit(1);
}

function creer(array $argv): void
{
    $email = $argv[2] ?? '';
    $nom   = $argv[3] ?? '';
    $role  = $argv[4] ?? 'editeur';

    if ($email === '' || $nom === '') {
        aide();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException("« $email » n'est pas une adresse valide.");
    }
    if (!in_array($role, ['admin', 'editeur'], true)) {
        throw new RuntimeException("Le rôle doit être « admin » ou « editeur ».");
    }
    if (Utilisateur::parEmail($email) !== null) {
        throw new RuntimeException("Un compte existe déjà pour « $email ».");
    }

    $mdp = demanderMotDePasse();
    $id  = Utilisateur::creer($email, $mdp, $nom, $role);

    echo "Compte #$id créé : $email ($role).\n";
}

function motDePasse(array $argv): void
{
    $email  = $argv[2] ?? '';
    $compte = $email === '' ? null : Utilisateur::parEmail($email);

    if ($compte === null) {
        throw new RuntimeException("Aucun compte pour « $email ».");
    }

    Utilisateur::changerMotDePasse((int) $compte['id'], demanderMotDePasse());
    echo "Mot de passe remplacé pour {$compte['email']}.\n";
}

function lister(): void
{
    $lignes = Database::all(
        'SELECT id, email, nom, role, actif, cree_le FROM utilisateur ORDER BY id'
    );

    if ($lignes === []) {
        echo "Aucun compte. Créez le premier :\n";
        echo "  php bin/compte.php creer vous@exemple.org \"Votre Nom\" admin\n";
        return;
    }

    printf("%-4s %-32s %-22s %-9s %s\n", '#', 'Adresse', 'Nom', 'Rôle', 'État');
    foreach ($lignes as $l) {
        printf(
            "%-4s %-32s %-22s %-9s %s\n",
            $l['id'], $l['email'], $l['nom'], $l['role'],
            (int) $l['actif'] === 1 ? 'actif' : 'désactivé'
        );
    }
}

/**
 * Saisie sans écho, deux fois pour confirmation.
 *
 * `stty -echo` plutôt qu'une extension : readline n'est pas garantie présente,
 * et le script doit tourner sur n'importe quel PHP en ligne de commande.
 */
function demanderMotDePasse(): string
{
    $mdp = saisieMasquee('Mot de passe             : ');
    $bis = saisieMasquee('Confirmation             : ');

    if ($mdp !== $bis) {
        throw new RuntimeException('Les deux saisies diffèrent.');
    }
    if (mb_strlen($mdp) < LONGUEUR_MINIMALE) {
        throw new RuntimeException(
            'Le mot de passe doit faire au moins ' . LONGUEUR_MINIMALE . ' caractères.'
        );
    }

    return $mdp;
}

function saisieMasquee(string $invite): string
{
    echo $invite;

    $echoDesactive = false;
    if (stripos(PHP_OS_FAMILY, 'Windows') === false) {
        $echoDesactive = shell_exec('stty -echo 2>/dev/null; echo ok') !== null;
    }

    $valeur = rtrim((string) fgets(STDIN), "\r\n");

    if ($echoDesactive) {
        shell_exec('stty echo 2>/dev/null');
    }
    echo "\n";

    return $valeur;
}
