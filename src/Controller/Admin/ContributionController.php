<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Quarantaine;
use App\Core\Session;
use App\Core\View;
use App\Model\Contribution;

/**
 * File de modération des contributions (brief §5, lot G8).
 *
 * **C'est le seul endroit d'où une contribution peut entrer dans le fonds.**
 * Le brief l'exige : rien n'est publié automatiquement.
 *
 * Trois gestes : accepter — les fichiers passent en médiathèque, **en
 * brouillon** —, refuser — les fichiers sont effacés —, ou supprimer la fiche
 * entière. Plus un quatrième, moins visible et pourtant le plus employé : lire
 * le fichier avant de trancher, ce qui suppose de le servir sans le sortir de
 * quarantaine.
 */
final class ContributionController
{
    public static function liste(): void
    {
        View::admin('contributions/liste', [
            'titre'   => 'Contributions',
            'actif'   => 'contributions',
            'lignes'  => Contribution::lister(),
            'styles'  => [Admin::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')],
            'scripts' => [
                Admin::asset('vendors/datatables.net/jquery.dataTables.js'),
                Admin::asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js'),
                Admin::asset('js/listes.js'),
            ],
        ]);
    }

    public static function fiche(array $params): void
    {
        $ligne = self::exigerLigne((int) $params['id']);

        View::admin('contributions/fiche', [
            'titre'    => 'Contribution',
            'actif'    => 'contributions',
            'ligne'    => $ligne,
            'fichiers' => Contribution::fichiers((int) $ligne['id']),
        ]);
    }

    /**
     * Sert un fichier encore en quarantaine, au modérateur seul.
     *
     * **La seule lecture possible d'un fichier non relu**, et elle passe par
     * PHP : le dossier est refusé par Apache, il n'y a donc pas d'adresse
     * directe. La garde d'authentification du back-office s'applique comme à
     * toute autre route sous `/cmsadmin/`.
     *
     * Servi en pièce jointe et en `application/octet-stream` : le modérateur
     * ouvre le fichier dans son propre lecteur, hors du navigateur et hors de
     * l'origine du site. Un PDF non relu n'a rien à faire dans un onglet.
     */
    public static function fichier(array $params): void
    {
        $contribution = self::exigerLigne((int) $params['id']);
        $fichier = Contribution::fichier((int) $contribution['id'], (int) $params['fichier']);

        if ($fichier === null) {
            View::admin('404', ['titre' => 'Fichier introuvable', 'actif' => 'contributions'], 404);
            return;
        }

        $absolu = Quarantaine::chemin((string) $fichier['chemin']);

        if ($absolu === null) {
            Session::message('erreur', 'Ce fichier ne se trouve plus sur le serveur.');
            self::rediriger('/contributions/' . (int) $contribution['id']);
        }

        // Le nom proposé au téléchargement est fabriqué, jamais celui d'origine :
        // il vient d'un inconnu et finirait dans un en-tête HTTP.
        $nom = sprintf(
            'contribution-%d-%d.%s',
            (int) $contribution['id'],
            (int) $fichier['id'],
            pathinfo((string) $fichier['chemin'], PATHINFO_EXTENSION)
        );

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $nom . '"');
        header('Content-Length: ' . (string) filesize($absolu));
        header('X-Content-Type-Options: nosniff');
        readfile($absolu);
        exit;
    }

    public static function accepter(array $params): void
    {
        Csrf::exiger();

        $ligne = self::exigerLigne((int) $params['id']);
        $verses = Contribution::accepter(
            (int) $ligne['id'],
            (int) Auth::utilisateur()['id'],
            (string) ($_POST['note'] ?? '')
        );

        Session::message('succes', $verses === 0
            ? 'Contribution acceptée. Aucun fichier à verser.'
            : sprintf(
                '%d fichier%s versé%s dans la médiathèque, en brouillon. '
                . 'Complétez la légende et le crédit, puis rattachez-les à une notice.',
                $verses, $verses > 1 ? 's' : '', $verses > 1 ? 's' : ''
            ));

        self::rediriger('/contributions');
    }

    public static function refuser(array $params): void
    {
        Csrf::exiger();

        $ligne = self::exigerLigne((int) $params['id']);

        Contribution::refuser(
            (int) $ligne['id'],
            (int) Auth::utilisateur()['id'],
            (string) ($_POST['note'] ?? '')
        );

        Session::message('succes', 'Contribution refusée. Ses fichiers ont été effacés du serveur.');
        self::rediriger('/contributions');
    }

    public static function supprimer(array $params): void
    {
        Csrf::exiger();

        $ligne = self::exigerLigne((int) $params['id']);
        Contribution::supprimer((int) $ligne['id']);

        Session::message('succes', 'Contribution supprimée.');
        self::rediriger('/contributions');
    }

    /** @return array<string,mixed> */
    private static function exigerLigne(int $id): array
    {
        $ligne = Contribution::trouver($id);

        if ($ligne === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => 'contributions'], 404);
            exit;
        }

        return $ligne;
    }

    private static function rediriger(string $chemin): never
    {
        header('Location: ' . Admin::url($chemin), true, 302);
        exit;
    }
}
