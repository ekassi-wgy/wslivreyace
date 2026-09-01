<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Temoignage;

/**
 * File de modération des témoignages (CDC §4.8).
 *
 * Volontairement pas une sous-classe de CrudController : la mécanique n'est pas
 * la même. Un modérateur ne crée pas de témoignage — il en reçoit, les lit, et
 * décide. Le verbe central est « publier » ou « refuser », pas « enregistrer ».
 *
 * Il reste une édition, limitée au nom, à la qualité et au texte : corriger une
 * coquille ou retirer un numéro de téléphone laissé dans le corps du message.
 * Rien de plus — ce sont les mots de quelqu'un d'autre.
 */
final class TemoignageController
{
    private const CHEMIN = '/temoignages';

    public static function liste(array $params = []): void
    {
        // Le filtre vient de la barre d'adresse : on ne garde qu'une valeur
        // connue, sinon la vue afficherait un onglet actif qui n'existe pas.
        $filtre = $_GET['statut'] ?? 'tous';
        if (!isset(Temoignage::STATUTS[$filtre])) {
            $filtre = 'tous';
        }

        View::admin('temoignages/liste', [
            'titre'     => 'Témoignages',
            'actif'     => 'temoignages',
            'filtre'    => $filtre,
            'lignes'    => Temoignage::listerPar($filtre === 'tous' ? null : $filtre),
            'compteurs' => Temoignage::compteurs(),
        ]);
    }

    public static function formulaire(array $params): void
    {
        $ligne = self::exigerLigne((int) $params['id']);

        View::admin('temoignages/formulaire', [
            'titre'   => 'Témoignage',
            'actif'   => 'temoignages',
            'ligne'   => $ligne,
            'valeurs' => $ligne,
            'erreurs' => [],
        ]);
    }

    public static function mettreAJour(array $params): void
    {
        Csrf::exiger();

        $id = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $v = new Validator($_POST);
        $v->requis('auteur_nom', 'Nom du signataire')->longueur('auteur_nom', 'Nom du signataire', 2, 160)
          ->longueur('auteur_fonction', 'Fonction', 0, 200)
          ->requis('contenu', 'Témoignage')->longueur('contenu', 'Témoignage', 10, 5000);

        if (!$v->estValide()) {
            View::admin('temoignages/formulaire', [
                'titre'   => 'Témoignage',
                'actif'   => 'temoignages',
                'ligne'   => $ligne,
                'valeurs' => $_POST,
                'erreurs' => $v->erreurs(),
            ], 422);
            exit;
        }

        Temoignage::modifier($id, [
            'auteur_nom'      => $v->valeur('auteur_nom'),
            'auteur_fonction' => $v->valeur('auteur_fonction') ?: null,
            'contenu'         => $v->valeur('contenu'),
        ]);

        Session::message('succes', 'Le témoignage a été corrigé.');
        self::rediriger();
    }

    /** Publier ou refuser. Le statut visé est porté par la route. */
    public static function moderer(array $params): void
    {
        Csrf::exiger();

        $id = (int) $params['id'];
        $ligne = self::exigerLigne($id);
        $decision = $params['decision'] ?? '';

        $statuts = ['publier' => 'publie', 'refuser' => 'refuse', 'reprendre' => 'en_attente'];

        if (!isset($statuts[$decision])) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        $moderateur = Auth::utilisateur();
        Temoignage::moderer($id, $statuts[$decision], (int) $moderateur['id']);

        $messages = [
            'publier'   => 'Le témoignage de %s est en ligne.',
            'refuser'   => 'Le témoignage de %s a été refusé. Il reste consultable ici.',
            'reprendre' => 'Le témoignage de %s repasse en attente.',
        ];

        Session::message('succes', sprintf($messages[$decision], $ligne['auteur_nom']));
        self::rediriger();
    }

    public static function supprimer(array $params): void
    {
        Csrf::exiger();

        $id = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        Temoignage::supprimer($id);

        Session::message('succes', sprintf(
            'Le témoignage de %s a été supprimé.',
            $ligne['auteur_nom']
        ));
        self::rediriger();
    }

    /** @return array<string,mixed> */
    private static function exigerLigne(int $id): array
    {
        $ligne = Temoignage::trouver($id);

        if ($ligne === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        return $ligne;
    }

    private static function rediriger(): never
    {
        // On revient sur le filtre d'où l'on vient : modérer dix entrées en
        // attente ne doit pas renvoyer dix fois sur la liste complète.
        $filtre = $_POST['retour'] ?? '';
        $suffixe = isset(Temoignage::STATUTS[$filtre]) ? '?statut=' . $filtre : '';

        header('Location: ' . Admin::url(self::CHEMIN) . $suffixe, true, 302);
        exit;
    }
}
