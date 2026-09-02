<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Commande;

/**
 * Suivi des commandes (CDC §4.9).
 *
 * Ni CrudController ni rien d'approchant : il n'y a **ni création ni
 * suppression**. Une commande naît du tunnel de paiement — pas encore écrit —
 * et reste. C'est une pièce comptable : la retirer parce qu'elle gêne ferait
 * disparaître la trace d'un paiement contesté.
 *
 * Ce que l'administration peut faire tient en trois gestes : constater un
 * paiement, marquer une remise, annoter. Le reste — montant, client,
 * référence — appartient à la passerelle, qui fait foi.
 *
 * Écran réservé aux administrateurs : il porte des noms, des adresses et des
 * numéros de téléphone de clients.
 */
final class CommandeController
{
    private const CHEMIN = '/commandes';

    public static function liste(): void
    {
        Auth::exigerAdmin();

        $filtre = $_GET['statut'] ?? 'tous';
        if (!isset(Commande::STATUTS[$filtre])) {
            $filtre = 'tous';
        }

        View::admin('commandes/liste', [
            'titre'     => 'Commandes',
            'actif'     => 'commandes',
            'filtre'    => $filtre,
            'lignes'    => Commande::listerPar($filtre === 'tous' ? null : $filtre),
            'compteurs' => Commande::compteurs(),
            'encaisse'  => Commande::encaisse(),
            'styles'    => [Admin::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')],
            'scripts'   => [
                Admin::asset('vendors/datatables.net/jquery.dataTables.js'),
                Admin::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js'),
                Admin::asset('js/listes.js'),
            ],
        ]);
    }

    public static function fiche(array $params): void
    {
        Auth::exigerAdmin();

        $ligne = self::exigerLigne((int) $params['id']);

        self::afficher($ligne, $ligne, []);
    }

    /**
     * Fait avancer le statut. La transition visée est portée par la route.
     *
     * Elle est vérifiée contre `Commande::SUITES` : une commande remise ne
     * redevient pas initiée, et une commande échouée ne se repêche pas depuis
     * un bouton. Une URL forgée n'y change rien.
     */
    public static function avancer(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);
        $vers  = (string) ($params['statut'] ?? '');

        if (!isset(Commande::STATUTS[$vers])) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        $depuis = (string) $ligne['statut'];

        if (!Commande::suiteAutorisee($depuis, $vers)) {
            Session::message('erreur', sprintf(
                'Une commande %s ne peut pas passer au statut « %s ».',
                mb_strtolower(Commande::STATUTS[$depuis]),
                Commande::STATUTS[$vers]
            ));
            self::rediriger($id);
        }

        Commande::avancer($id, $vers, (int) (Auth::utilisateur()['id'] ?? 0));

        $messages = [
            'payee'   => 'Paiement constaté sur la commande %s.',
            'echouee' => 'La commande %s est marquée échouée.',
            'remise'  => 'La commande %s est remise. Elle est close.',
        ];

        Session::message('succes', sprintf($messages[$vers], $ligne['reference']));
        self::rediriger($id);
    }

    /**
     * Annotation de suivi.
     *
     * Le seul champ que l'administration écrit : « rappelé le 12, absent »,
     * « remis en main propre à la dédicace ». Tout le reste vient du paiement.
     */
    public static function annoter(array $params): void
    {
        Auth::exigerAdmin();
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $v = new Validator($_POST);
        $v->longueur('note', 'Note de suivi', 0, 500);

        if (!$v->estValide()) {
            self::afficher($ligne, $_POST, $v->erreurs());
        }

        Commande::modifier($id, ['note' => $v->valeur('note') === '' ? null : $v->valeur('note')]);

        Session::message('succes', 'Note enregistrée.');
        self::rediriger($id);
    }

    // -- Rouages -----------------------------------------------------------

    /**
     * @param array<string,mixed>  $ligne
     * @param array<string,mixed>  $valeurs
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $ligne, array $valeurs, array $erreurs): never
    {
        View::admin('commandes/fiche', [
            'titre'   => 'Commande ' . $ligne['reference'],
            'actif'   => 'commandes',
            'ligne'   => $ligne,
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
        ], $erreurs === [] ? 200 : 422);

        exit;
    }

    /** @return array<string,mixed> */
    private static function exigerLigne(int $id): array
    {
        $ligne = Commande::trouver($id);

        if ($ligne === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        return $ligne;
    }

    /**
     * Retour sur la fiche et non sur la liste : constater un paiement puis
     * marquer la remise sont deux gestes qui s'enchaînent sur la même
     * commande, souvent à quelques secondes d'intervalle.
     */
    private static function rediriger(int $id): never
    {
        header('Location: ' . Admin::url(self::CHEMIN . '/' . $id), true, 302);
        exit;
    }
}
