<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Traduction;
use App\Core\Validator;
use App\Core\View;
use App\Model\Parametre;

/**
 * Les textes des pages publiques (lot G16).
 *
 * Le contexte historique de la biographie vivait dans `src/lang/fr.php` et
 * affichait en ligne sa propre consigne — « Texte à rédiger. » — dans les
 * deux langues. Il est désormais saisi ici, rangé dans `parametre`.
 *
 * **Un écran à part, et non une carte de plus sur « Paramètres ».** Celui-ci
 * est la fiche technique de l'ouvrage : son titre, son compteur « x sur 8 » et
 * son bouton « Enregistrer la fiche » parlent du livre. Un texte de la
 * biographie y aurait été introuvable, et son enregistrement aurait annoncé
 * « Fiche technique enregistrée ».
 *
 * **Ouvert aux éditeurs**, comme les citations : rédiger le contexte d'une page
 * est un acte éditorial, pas un réglage.
 *
 * **Le français seulement.** L'anglais se saisit à l'écran des traductions,
 * fiche « Textes des pages », le français en regard — même règle que pour tout
 * le reste du site. Cet écran dit en revanche, sous chaque section, ce que la
 * page anglaise affichera : c'est ici qu'on s'en soucie en écrivant.
 */
final class TexteController
{
    public static function formulaire(array $erreurs = [], array $valeurs = []): void
    {
        $enBase = Parametre::toutes();

        View::admin('textes', [
            'titre'    => 'Textes des pages',
            'actif'    => 'textes',
            'sections' => Parametre::TEXTES_PAGES,
            'valeurs'  => $valeurs !== [] ? $valeurs : $enBase,
            // L'état affiché sous chaque section est celui de la base, même
            // quand le formulaire revient en erreur : c'est ce que le site
            // montre, et la saisie refusée n'y est pas.
            'enBase'   => $enBase,
            'erreurs'  => $erreurs,
            'langues'  => TraductionController::languesCibles(),
            'traduits' => TraductionController::posees('parametre', Traduction::SANS_ID),
        ], $erreurs === [] ? 200 : 422);
    }

    public static function enregistrer(): void
    {
        Csrf::exiger();

        $v = new Validator($_POST);

        foreach (Parametre::champsTextes() as $cle => $champ) {
            if ($champ['type'] === 'titre') {
                // Le titre s'affiche en très grands caractères : 200 signes
                // en font déjà quatre lignes pleines.
                $v->longueur($cle, $champ['libelle'], 0, 200);
            }
        }

        if (!$v->estValide()) {
            self::formulaire($v->erreurs(), $_POST);
            exit;
        }

        foreach (Parametre::champsTextes() as $cle => $champ) {
            // Un champ vidé redevient NULL et non chaîne vide, comme sur
            // « Paramètres » : la page publique teste l'absence de valeur pour
            // retirer la section.
            $valeur = str_replace("\r\n", "\n", $v->valeur($cle));
            Parametre::ecrire($cle, $valeur === '' ? null : $valeur, $champ['libelle']);
        }

        Session::message('succes', 'Textes des pages enregistrés.');

        header('Location: ' . Admin::url('/textes'), true, 302);
        exit;
    }
}
