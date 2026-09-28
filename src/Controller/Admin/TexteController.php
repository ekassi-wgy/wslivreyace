<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Televersement;
use App\Core\Traduction;
use App\Core\Validator;
use App\Core\View;
use App\Model\Media;
use App\Model\Parametre;

/**
 * Les textes des pages publiques (lot G16), et le diaporama de l'accueil
 * (lot G17).
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
            'medias'    => self::images(),
            'documents' => self::documents(),
            'scripts'  => [Admin::asset('js/medias.js')],
        ], $erreurs === [] ? 200 : 422);
    }

    /**
     * Les images proposées au sélecteur du diaporama.
     *
     * Le même lot borné que les fiches de contenu, **images seules** : un PDF
     * n'a pas de vignette, et posé dans le diaporama il y rendrait un cadre
     * cassé (lot G5).
     *
     * @return array<int,array<string,mixed>>
     */
    private static function images(): array
    {
        return array_values(array_filter(
            Media::chercher(null, '', 200),
            static fn(array $m): bool => Media::aVignette($m)
        ));
    }

    /**
     * Les PDF proposés au menu d'un champ `document`, par titre.
     *
     * Un menu déroulant et non le sélecteur à vignettes : un PDF n'en a pas, et
     * c'est son titre qu'on reconnaît. Le premier choix, vide, retire le lien.
     *
     * @return array<string,string> chemin => libellé
     */
    private static function documents(): array
    {
        $choix = ['' => '— Aucun —'];

        foreach (Media::chercher(null, '', 200) as $m) {
            if (Media::est($m, 'document')) {
                $choix[(string) $m['fichier']] = trim((string) ($m['titre'] ?? '')) !== ''
                    ? (string) $m['titre']
                    : (string) $m['fichier'];
            }
        }

        return $choix;
    }

    /** Le PDF choisi existe-t-il, et en est-il un ? */
    private static function validerDocument(Validator $v, string $cle): void
    {
        $chemin = $v->valeur($cle);

        if ($chemin === '') {
            return;
        }

        $media = Televersement::formeValide($chemin) ? Media::parFichier($chemin) : null;

        if ($media === null) {
            $v->erreur($cle, "Le PDF choisi n'est plus dans la médiathèque. Choisissez-en un autre.");
        } elseif (!Media::est($media, 'document')) {
            $v->erreur($cle, "Ce fichier n'est pas un PDF.");
        }
    }

    /**
     * L'image choisie existe-t-elle, et en est-elle une ?
     *
     * Le champ est un contrôle caché, qui se réécrit comme un autre : la forme
     * du chemin, sa présence en médiathèque et sa famille sont vérifiées. La
     * page publique retomberait de toute façon sur le cadre d'attente, mais
     * l'éditeur croirait son image en place.
     */
    private static function validerImage(Validator $v, string $cle): void
    {
        $chemin = $v->valeur($cle);

        if ($chemin === '') {
            return;
        }

        $media = Televersement::formeValide($chemin) ? Media::parFichier($chemin) : null;

        if ($media === null) {
            $v->erreur($cle, "L'image choisie n'est plus dans la médiathèque. Choisissez-en une autre.");
        } elseif (!Media::aVignette($media)) {
            $v->erreur($cle, 'Ce fichier n\'est pas une image : choisissez une photographie.');
        }
    }

    public static function enregistrer(): void
    {
        Csrf::exiger();

        $v = new Validator($_POST);

        foreach (Parametre::champsTextes() as $cle => $champ) {
            if ($champ['type'] === 'image') {
                self::validerImage($v, $cle);
            } elseif ($champ['type'] === 'document') {
                self::validerDocument($v, $cle);
            } elseif ($champ['type'] !== 'long' && $champ['type'] !== 'sommaire') {
                // Un titre s'affiche en très grands caractères : 200 signes
                // en font déjà quatre lignes pleines. Ceux du diaporama, et
                // ses libellés, en ont moins encore — voir `max`.
                $v->longueur($cle, $champ['libelle'], 0, $champ['max'] ?? 200);
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

            // Une ligne reste une ligne : un retour collé depuis un traitement
            // de texte casserait un bouton en deux.
            if ($champ['type'] === 'ligne') {
                $valeur = trim(preg_replace('/\s+/u', ' ', $valeur) ?? '');
            }

            Parametre::ecrire($cle, $valeur === '' ? null : $valeur, $champ['libelle']);
        }

        Session::message('succes', 'Textes des pages enregistrés.');

        header('Location: ' . Admin::url('/textes'), true, 302);
        exit;
    }
}
