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
            'champs'   => Parametre::FICHE_LIVRE,
            'autour'   => Parametre::AUTOUR_LIVRE,
            'boutique' => Parametre::BOUTIQUE,
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

            // Les bornes sont propres au champ depuis le lot G3 : le prix de
            // l'ouvrage et son nombre de pages n'ont pas le même ordre de
            // grandeur, et une borne unique refusait 25 000 francs.
            match ($champ['type']) {
                'entier' => $v->entier($cle, $champ['libelle'], $champ['min'] ?? 1, $champ['max'] ?? 10000),
                'isbn'   => self::validerIsbn($v, $cle, $champ['libelle']),
                default  => null,
            };
        }

        // Préface et auteur (lot G2). Les textes longs ne sont pas bornés à
        // 200 signes : une préface en fait plusieurs milliers.
        foreach (Parametre::AUTOUR_LIVRE as $cle => $champ) {
            if ($champ['type'] === 'texte') {
                $v->longueur($cle, $champ['libelle'], 0, 200);
            }
        }

        // Boutique et livraison (lot G3), même régime que ci-dessus.
        foreach (Parametre::BOUTIQUE as $cle => $champ) {
            if ($champ['type'] === 'texte') {
                $v->longueur($cle, $champ['libelle'], 0, 200);
            }
        }

        /*
         * Ouvrir les commandes sans prix afficherait « 0 F CFA » sur la page
         * de vente et enregistrerait des commandes gratuites. `Boutique` s'en
         * garde déjà — elle exige les deux — mais un réglage qu'on coche sans
         * effet visible est un réglage qui ment : le refus se dit ici.
         */
        if (($_POST['boutique_ouverte'] ?? '') === '1' && trim((string) ($_POST['livre_prix'] ?? '')) === '') {
            $v->erreur('livre_prix', "Renseignez le prix avant d'ouvrir les commandes : "
                . 'sans lui, la boutique resterait fermée malgré la case cochée.');
        }

        /*
         * Une préface mise en avant sans nom de préfacier donnerait un bloc
         * signé de personne, en tête de la page la plus lue du site. La règle
         * est la même que pour le sourçage des repères : ce qui paraît doit
         * être attribuable.
         */
        if (($_POST['preface_avant'] ?? '') === '1' && trim((string) ($_POST['preface_auteur'] ?? '')) === '') {
            $v->erreur('preface_auteur', 'Une préface mise en avant doit porter le nom de son auteur.');
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

        // `array_merge` et non l'opérateur de décomposition : celui-ci ne
        // conserve les clés textuelles que depuis PHP 8.1, et une écriture qui
        // dépend d'une version se relit mal sur un hébergement mutualisé.
        foreach (array_merge(Parametre::AUTOUR_LIVRE, Parametre::BOUTIQUE) as $cle => $champ) {
            if ($champ['type'] === 'case') {
                // Une case décochée ne poste rien : la valeur est écrite dans
                // les deux cas, sinon décocher n'aurait aucun effet.
                Parametre::ecrire($cle, $v->valeur($cle) === '1' ? '1' : null, $champ['libelle']);
                continue;
            }

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
