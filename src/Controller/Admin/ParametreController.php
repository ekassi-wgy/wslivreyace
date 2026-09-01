<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Parametre;

/**
 * Fiche technique de l'ouvrage (CDC §4.2).
 *
 * Huit valeurs que l'éditeur doit fournir avant la mise en ligne. Elles vivent
 * en base plutôt que dans le code pour qu'un changement d'ISBN ou de prix ne
 * demande pas une intervention technique.
 */
final class ParametreController
{
    public static function formulaire(array $erreurs = [], array $valeurs = []): void
    {
        View::admin('parametres', [
            'titre'   => 'Paramètres',
            'actif'   => 'parametres',
            'champs'  => Parametre::FICHE_LIVRE,
            'valeurs' => $valeurs !== [] ? $valeurs : Parametre::toutes(),
            'erreurs' => $erreurs,
            'remplis' => Parametre::ficheRemplie(),
        ], $erreurs === [] ? 200 : 422);
    }

    public static function enregistrer(): void
    {
        Csrf::exiger();

        $v = new Validator($_POST);

        foreach (Parametre::FICHE_LIVRE as $cle => $champ) {
            $v->longueur($cle, $champ['libelle'], 0, 200);

            match ($champ['type']) {
                'entier' => $v->entier($cle, $champ['libelle'], 1, 10000),
                'isbn'   => self::validerIsbn($v, $cle, $champ['libelle']),
                default  => null,
            };
        }

        if (!$v->estValide()) {
            self::formulaire($v->erreurs(), $_POST);
            exit;
        }

        foreach (Parametre::FICHE_LIVRE as $cle => $champ) {
            // Un champ vidé redevient NULL et non chaîne vide : la page
            // publique teste l'absence de valeur pour masquer la ligne.
            $valeur = $v->valeur($cle);
            Parametre::ecrire($cle, $valeur === '' ? null : $valeur, $champ['libelle']);
        }

        $reste = count(Parametre::FICHE_LIVRE) - Parametre::ficheRemplie();

        Session::message('succes', $reste === 0
            ? 'Fiche technique enregistrée. Elle est complète.'
            : sprintf('Fiche technique enregistrée. %d valeur%s reste%s à fournir.',
                      $reste, $reste > 1 ? 's' : '', $reste > 1 ? 'nt' : ''));

        header('Location: ' . Admin::url('/parametres'), true, 302);
        exit;
    }

    /**
     * ISBN-13 : treize chiffres, tirets et espaces tolérés à la saisie.
     *
     * La clé de contrôle est vérifiée. Un ISBN mal recopié passerait sinon
     * jusque sur la page publique, où il sert à commander l'ouvrage.
     */
    private static function validerIsbn(Validator $v, string $champ, string $libelle): void
    {
        $saisi = $v->valeur($champ);
        if ($saisi === '') {
            return;
        }

        $chiffres = preg_replace('/[^0-9]/', '', $saisi) ?? '';

        if (strlen($chiffres) !== 13) {
            $v->erreur($champ, "« $libelle » doit compter 13 chiffres (ISBN-13).");
            return;
        }

        // Somme pondérée 1,3,1,3… ; le total doit être un multiple de 10.
        $somme = 0;
        for ($i = 0; $i < 13; $i++) {
            $somme += (int) $chiffres[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        if ($somme % 10 !== 0) {
            $v->erreur($champ, "La clé de contrôle de « $libelle » est fausse : vérifiez la saisie.");
        }
    }
}
