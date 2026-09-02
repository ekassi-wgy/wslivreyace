<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Utilisateur;

/**
 * Comptes du back-office.
 *
 * `bin/compte.php` reste : c'est lui qui crée le premier administrateur, et
 * lui qui rouvre la porte si personne ne peut plus entrer. Une page
 * d'installation qui crée le premier compte est ouverte par définition ; la
 * ligne de commande, elle, suppose déjà un accès au serveur. Cet écran prend
 * le relais pour l'usage courant : ajouter un éditeur, changer un rôle,
 * réinitialiser un mot de passe.
 *
 * **Aucune suppression.** Un compte se désactive. Il a modéré des témoignages
 * et remis des commandes ; l'effacer viderait ces traces de leur nom, et la
 * question « qui a validé ceci ? » perdrait sa réponse. Un compte désactivé ne
 * peut plus se connecter — c'est ce qu'on cherche — et Auth relit son état à
 * chaque requête, donc une session ouverte tombe aussitôt.
 *
 * **Trois manœuvres sont refusées**, toutes pour la même raison : elles
 * laisseraient le back-office sans personne pour y entrer. Se désactiver soi-
 * même, se retirer le rôle d'administrateur, et retirer le dernier
 * administrateur actif.
 */
final class CompteController
{
    private const CHEMIN = '/comptes';

    /** Longueur minimale, la même que celle de `bin/compte.php`. */
    private const LONGUEUR_MINIMALE = 12;

    public const ROLES = [
        'admin'   => 'Administrateur',
        'editeur' => 'Éditeur',
    ];

    /** Ce que chaque rôle donne, dit en clair sur l'écran. */
    public const DROITS = [
        'admin'   => 'Tout le back-office, comptes et commandes compris.',
        'editeur' => 'Contenus, médiathèque et modération. Ni comptes ni commandes.',
    ];

    public static function liste(): void
    {
        Auth::exigerAdmin();

        View::admin('comptes/liste', [
            'titre'   => 'Comptes',
            'actif'   => 'comptes',
            'lignes'  => Utilisateur::lister(),
            'moi'     => (int) (Auth::utilisateur()['id'] ?? 0),
            'admins'  => Utilisateur::adminsActifs(),
        ]);
    }

    public static function formulaireCreation(): void
    {
        Auth::exigerAdmin();

        self::afficher(null, ['role' => 'editeur'], []);
    }

    public static function formulaireEdition(array $params): void
    {
        Auth::exigerAdmin();

        $ligne = self::exigerLigne((int) $params['id']);

        self::afficher($ligne, $ligne, []);
    }

    public static function creer(): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $v = self::valider($_POST, null);

        if (!$v->estValide()) {
            self::afficher(null, $_POST, $v->erreurs());
        }

        [$motDePasse, $fabrique] = self::motDePasse($v);

        $id = Utilisateur::creer(
            $v->valeur('email'),
            $motDePasse,
            $v->valeur('nom'),
            $v->valeur('role', 'editeur')
        );

        Session::message('succes', sprintf(
            'Compte créé pour %s (%s).',
            $v->valeur('nom'),
            self::ROLES[$v->valeur('role', 'editeur')]
        ));

        if ($fabrique) {
            self::annoncerMotDePasse($motDePasse);
        }

        self::rediriger($id);
    }

    public static function mettreAJour(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $v = self::valider($_POST, $id);

        $role  = $v->valeur('role', 'editeur');
        $actif = ($_POST['actif'] ?? '') === '1';

        self::verifierDerniersDroits($v, $id, $role, $actif);

        if (!$v->estValide()) {
            self::afficher($ligne, $_POST, $v->erreurs());
        }

        Utilisateur::modifier($id, $v->valeur('nom'), $role, $actif);

        if (mb_strtolower($v->valeur('email')) !== mb_strtolower((string) $ligne['email'])) {
            Utilisateur::changerEmail($id, $v->valeur('email'));
        }

        Session::message('succes', sprintf('Le compte de %s a été enregistré.', $v->valeur('nom')));
        self::rediriger($id);
    }

    /**
     * Réinitialise le mot de passe.
     *
     * L'ancien n'est pas demandé : un administrateur qui réinitialise le mot de
     * passe d'un tiers ne le connaît pas, et exiger le sien n'ajouterait rien —
     * il vient de l'employer pour ouvrir sa session.
     */
    public static function reinitialiser(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $v = new Validator($_POST);
        self::validerMotDePasse($v);

        if (!$v->estValide()) {
            self::afficher($ligne, $ligne, $v->erreurs());
        }

        [$motDePasse, $fabrique] = self::motDePasse($v);

        Utilisateur::changerMotDePasse($id, $motDePasse);

        Session::message('succes', sprintf('Mot de passe remplacé pour %s.', $ligne['nom']));

        if ($fabrique) {
            self::annoncerMotDePasse($motDePasse);
        }

        self::rediriger($id);
    }

    /** Bascule actif / désactivé depuis la liste. */
    public static function basculerActif(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $actif   = (int) $ligne['actif'] === 1;
        $refus   = self::refusDeDesactivation($id, (string) $ligne['role'], !$actif);

        if ($refus !== null) {
            Session::message('erreur', $refus);
            self::redirigerListe();
        }

        Utilisateur::modifier($id, (string) $ligne['nom'], (string) $ligne['role'], !$actif);

        Session::message('succes', sprintf(
            'Le compte de %s est %s.',
            $ligne['nom'],
            $actif ? 'désactivé' : 'réactivé'
        ));

        self::redirigerListe();
    }

    // -- Rouages -----------------------------------------------------------

    private static function valider(array $post, ?int $id): Validator
    {
        $v = new Validator($post);

        $v->requis('nom', 'Nom')->longueur('nom', 'Nom', 2, 120)
          ->requis('email', 'Adresse électronique')
          ->courriel('email', 'Adresse électronique')
          ->longueur('email', 'Adresse électronique', 0, 180)
          ->parmi('role', 'Rôle', array_keys(self::ROLES));

        if ($v->valeur('email') !== '' && Utilisateur::emailPris($v->valeur('email'), $id)) {
            $v->erreur('email', 'Un compte existe déjà pour cette adresse.');
        }

        // À la création seulement : la modification ne touche pas au mot de
        // passe, qui a son propre formulaire.
        if ($id === null) {
            self::validerMotDePasse($v);
        }

        return $v;
    }

    private static function validerMotDePasse(Validator $v): void
    {
        $mdp = $v->valeur('mot_de_passe');

        // Champ vide : le mot de passe sera fabriqué. C'est le cas normal.
        if ($mdp === '') {
            return;
        }

        if (mb_strlen($mdp) < self::LONGUEUR_MINIMALE) {
            $v->erreur('mot_de_passe', sprintf(
                'Le mot de passe doit faire au moins %d caractères. Laissez le champ '
                . 'vide pour en faire fabriquer un.',
                self::LONGUEUR_MINIMALE
            ));
            return;
        }

        if ($mdp !== $v->valeur('mot_de_passe_bis')) {
            $v->erreur('mot_de_passe_bis', 'Les deux saisies diffèrent.');
        }
    }

    /**
     * @return array{0:string,1:bool} le mot de passe, et s'il a été fabriqué
     */
    private static function motDePasse(Validator $v): array
    {
        $saisi = $v->valeur('mot_de_passe');

        return $saisi === ''
            ? [Utilisateur::motDePasseFabrique(), true]
            : [$saisi, false];
    }

    /**
     * Affiche le mot de passe fabriqué, une fois.
     *
     * Il transite par le bandeau de messages, donc par la session, le temps
     * d'une redirection — et nulle part ailleurs : ni journal, ni base, ni
     * courriel. C'est le seul moment où il est lisible ; ensuite, seule sa
     * réinitialisation permet d'en obtenir un autre.
     */
    private static function annoncerMotDePasse(string $motDePasse): void
    {
        Session::message('info', sprintf(
            'Mot de passe à transmettre, affiché une seule fois : %s — '
            . 'communiquez-le de vive voix ou par un canal séparé, jamais dans le même '
            . 'courriel que l\'adresse de connexion.',
            $motDePasse
        ));
    }

    /**
     * Refuse les manœuvres qui fermeraient la porte.
     *
     * Les trois cas se ramènent à un : après l'opération, il doit rester au
     * moins un administrateur actif — et ce ne peut pas être un compte
     * désactivé ni un éditeur.
     */
    private static function verifierDerniersDroits(Validator $v, int $id, string $role, bool $actif): void
    {
        $refus = self::refusDeDesactivation($id, $role, $actif);

        if ($refus !== null) {
            $v->erreur($role === 'admin' ? 'actif' : 'role', $refus);
        }
    }

    /**
     * Le compte $id resterait-il administrateur actif après l'opération ?
     * Rend le message de refus, ou null si la manœuvre est permise.
     */
    private static function refusDeDesactivation(int $id, string $role, bool $actif): ?string
    {
        $moi = (int) (Auth::utilisateur()['id'] ?? 0);

        if ($role === 'admin' && $actif) {
            return null;   // le compte reste administrateur actif
        }

        if ($id === $moi) {
            return $actif
                ? 'Vous ne pouvez pas retirer votre propre rôle d\'administrateur : '
                  . 'vous perdriez l\'accès à cet écran dans la seconde.'
                : 'Vous ne pouvez pas désactiver votre propre compte.';
        }

        if (Utilisateur::adminsActifs($id) === 0) {
            return 'C\'est le dernier administrateur actif. Nommez-en un autre d\'abord, '
                 . 'sans quoi plus personne ne pourra entrer dans le back-office.';
        }

        return null;
    }

    /**
     * @param array<string,mixed>|null $ligne
     * @param array<string,mixed>      $valeurs
     * @param array<string,string>     $erreurs
     */
    private static function afficher(?array $ligne, array $valeurs, array $erreurs): never
    {
        $edition = $ligne !== null;

        View::admin('comptes/formulaire', [
            'titre'   => $edition ? 'Modifier le compte' : 'Nouveau compte',
            'actif'   => 'comptes',
            'edition' => $edition,
            'ligne'   => $ligne,
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
            'moi'     => (int) (Auth::utilisateur()['id'] ?? 0),
            'minimum' => self::LONGUEUR_MINIMALE,
        ], $erreurs === [] ? 200 : 422);

        exit;
    }

    /** @return array<string,mixed> */
    private static function exigerLigne(int $id): array
    {
        $ligne = Utilisateur::parId($id);

        if ($ligne === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        return $ligne;
    }

    private static function rediriger(int $id): never
    {
        header('Location: ' . Admin::url(self::CHEMIN . '/' . $id), true, 302);
        exit;
    }

    private static function redirigerListe(): never
    {
        header('Location: ' . Admin::url(self::CHEMIN), true, 302);
        exit;
    }
}
