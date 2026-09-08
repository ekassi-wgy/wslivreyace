<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Boutique;
use App\Core\Csrf;
use App\Core\Debit;
use App\Core\Langue;
use App\Core\Lexique;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Commande;
use App\Model\Parametre;
use App\Model\Zone;

/**
 * Le tunnel de commande, en paiement à la livraison (lot G3).
 *
 * **Une page, un formulaire.** Le site vend un seul ouvrage : il n'y a pas de
 * panier, seulement une quantité. Un tunnel en trois étapes pour un produit
 * unique est une cérémonie — trois pages à charger, trois occasions
 * d'abandonner, et un état à porter entre elles.
 *
 * **Le total est recalculé à l'envoi, jamais repris du formulaire.** Ce que la
 * page affiche est indicatif ; ce qui s'enregistre vient de `Boutique::total()`
 * relu en base. Un total posté se ramène à zéro franc en modifiant un champ.
 *
 * **Aucune passerelle n'est appelée.** `App\Core\Paiement` la décrit depuis le
 * lot E2 et continue de ne pas l'être : le client paie à la remise. La phase 2
 * se branchera ici, et les statuts de `Commande` la prévoient déjà.
 *
 * **Pas de courriel de confirmation.** Le README a tranché au lot F4 que
 * `mail()` échoue en silence sur un mutualisé, et c'est pourquoi les messages
 * sont stockés plutôt qu'envoyés. La confirmation est donc à l'écran, avec la
 * référence, et la commande attend dans le back-office — où quelqu'un appelle,
 * ce que le paiement à la livraison impose de toute façon.
 */
final class CommandeController
{
    private const ACTION = 'commande';

    /**
     * Délai minimal entre l'ouverture de la page et l'envoi.
     *
     * Plus long que sur les autres formulaires : celui-ci demande une adresse
     * et un numéro de téléphone, personne ne le remplit en quatre secondes.
     */
    private const DELAI_MINIMAL = 4;

    /** Nom du champ-leurre, dérivé comme ailleurs pour ne pas être reconnaissable. */
    private const LEURRE = 'site_web_commande';

    /** La page de vente et son formulaire. */
    public static function page(): void
    {
        self::afficher([], []);
    }

    /** Réception d'une commande. */
    public static function envoyer(): void
    {
        /*
         * Le débit d'abord, le jeton ensuite : une rafale automatisée ne doit
         * pas coûter une vérification de session par envoi. Même ordre que
         * pour les contributions (lot G8).
         */
        Session::demarrer('pgy', '/');

        if (!Debit::autorise(self::ACTION)) {
            self::afficher($_POST, ['_global' => Debit::refus(self::ACTION)], 429);
        }

        Csrf::exiger();

        /*
         * La boutique peut avoir été fermée entre l'affichage de la page et
         * l'envoi. Le refus est dit, jamais silencieux : quelqu'un qui a saisi
         * son adresse doit savoir pourquoi elle ne part pas.
         */
        if (!Boutique::ouverte()) {
            self::afficher($_POST, ['_global' => Lexique::nu('commander.fermee_entretemps')], 409);
        }

        $v = self::valider($_POST);
        self::garder($_POST, $v);

        if (!$v->estValide()) {
            self::afficher($_POST, $v->erreurs(), 422);
        }

        /*
         * L'AUTORITÉ EST ICI. La quantité et la zone viennent du formulaire ;
         * le prix et les frais viennent de la base, et le total s'en déduit.
         * Rien de ce que le client a pu voir ou modifier n'entre dans le calcul.
         */
        $livraison = $v->valeur('livraison') === 'livraison' ? 'livraison' : 'retrait';
        $zoneId    = $livraison === 'livraison' ? (int) $v->valeur('zone_id') : null;
        $calcul    = Boutique::total((int) $v->valeur('quantite'), $zoneId);

        $reference = Commande::passer([
            'nom'     => $v->valeur('nom'),
            'email'   => $v->valeur('email'),
            'tel'     => $v->valeur('telephone'),
            'adresse' => $livraison === 'livraison' ? $v->valeur('adresse') : '',
        ], $calcul, $livraison);

        Debit::enregistrer(self::ACTION);
        Session::oublier('_commande_ouverte_le');

        /*
         * La référence voyage en session et non dans l'adresse. Une page
         * `/commander/PGY-4F2K9A` serait partageable — et montrerait le nom,
         * le téléphone et l'adresse d'un client à qui aurait le lien. Le
         * paiement à la livraison n'a pas besoin d'une page à garder : il a
         * besoin qu'on rappelle le client.
         */
        Session::set('_commande_reference', $reference);

        header('Location: ' . Langue::chemin('/commander/confirmation'), true, 303);
        exit;
    }

    /** La page de confirmation, lue une fois en session. */
    public static function confirmation(): void
    {
        // La session porte la référence : sans l'ouvrir, `get()` rend null et
        // la page renvoie au formulaire alors que la commande est passée.
        Session::demarrer('pgy', '/');

        $reference = Session::get('_commande_reference');

        if (!is_string($reference) || $reference === '') {
            header('Location: ' . Langue::chemin('/commander'), true, 303);
            exit;
        }

        $commande = Commande::parReference($reference);

        if ($commande === null) {
            Session::oublier('_commande_reference');
            header('Location: ' . Langue::chemin('/commander'), true, 303);
            exit;
        }

        /*
         * La référence reste en session plutôt que d'être consommée : un
         * rafraîchissement de page ne doit pas effacer la seule trace que le
         * client ait de sa commande. Elle part avec la session, comme le
         * cookie que les mentions légales décrivent.
         */
        View::render('pages/commande-confirmee', [
            'page'     => 'livre',
            'commande' => $commande,
            'message'  => trim((string) Parametre::lire('commande_message')),
        ]);
    }

    // -- Rouages -----------------------------------------------------------

    /** @param array<string,mixed> $post */
    private static function valider(array $post): Validator
    {
        $v = new Validator($post);

        $nom   = Lexique::nu('champ.nom');
        $email = Lexique::nu('champ.email');
        $tel   = Lexique::nu('champ.telephone');

        $v->requis('nom', $nom)->longueur('nom', $nom, 2, 160)
          ->requis('email', $email)->courriel('email', $email)->longueur('email', $email, 0, 180)
          /*
           * Le téléphone est **obligatoire ici**, alors qu'il ne l'est nulle
           * part ailleurs sur le site : en paiement à la livraison, la
           * commande se confirme par un appel. Sans numéro, elle ne peut ni
           * être confirmée ni être livrée.
           */
          ->requis('telephone', $tel)->longueur('telephone', $tel, 4, 40)
          ->entier('quantite', Lexique::nu('champ.quantite'), 1, Boutique::QUANTITE_MAX);

        $livraison = (string) ($post['livraison'] ?? '');

        if (!isset(Commande::LIVRAISONS[$livraison])) {
            $v->erreur('livraison', Lexique::nu('commander.mode_inconnu'));

            return $v;
        }

        if ($livraison === 'retrait') {
            if (!Boutique::retraitPossible()) {
                $v->erreur('livraison', Lexique::nu('commander.retrait_indisponible'));
            }

            return $v;
        }

        // --- Livraison : la zone doit exister, être ouverte et porter un tarif.
        $zoneId = (int) ($post['zone_id'] ?? 0);

        if ($zoneId <= 0) {
            $v->erreur('zone_id', Lexique::nu('commander.zone_requise'));
        } elseif (!Zone::livrable($zoneId)) {
            // Une zone fermée entre l'affichage et l'envoi, ou un identifiant
            // fabriqué : dans les deux cas on refuse plutôt que de livrer
            // gratuitement.
            $v->erreur('zone_id', Lexique::nu('commander.zone_indisponible'));
        }

        $adresse = Lexique::nu('champ.adresse');
        $v->requis('adresse', $adresse)->longueur('adresse', $adresse, 10, 500);

        return $v;
    }

    /**
     * Les deux gardes communes aux formulaires publics, appliquées à part
     * pour qu'un retour anticipé de `valider()` ne les saute pas.
     *
     * Le refus est **dit en clair**, jamais silencieux : perdre l'adresse de
     * quelqu'un en lui laissant croire que sa commande est partie serait pire
     * que de refuser.
     *
     * @param array<string,mixed> $post
     */
    private static function garder(array $post, Validator $v): void
    {
        // Piège à robots : masqué à l'œil et retiré aux lecteurs d'écran.
        if (trim((string) ($post[self::LEURRE] ?? '')) !== '') {
            $v->erreur('_global', Lexique::nu('form.leurre_refus'));
        }

        // Délai minimal. En session et non dans un champ caché : un champ
        // caché se réécrit, la session non.
        $ouvert = Session::get('_commande_ouverte_le');

        if (is_int($ouvert) && time() - $ouvert < self::DELAI_MINIMAL) {
            $v->erreur('_global', Lexique::nu('form.trop_rapide'));
        }
    }

    /**
     * Rend la page de commande.
     *
     * @param array<string,mixed>  $valeurs
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $valeurs, array $erreurs, int $code = 200): void
    {
        /*
         * La session n'est ouverte que par cette page et son envoi — comme
         * pour le contact et les témoignages. Les pages qui ne portent pas de
         * formulaire ne déposent aucun cookie, et les mentions légales le
         * disent (§ Cookies).
         */
        Session::demarrer('pgy', '/');

        /*
         * L'horodatage n'est posé qu'à la première ouverture : le réécrire à
         * chaque réaffichage ferait que renvoyer aussitôt un formulaire refusé
         * serait refusé à son tour, pour lenteur inverse.
         */
        if (!is_int(Session::get('_commande_ouverte_le'))) {
            Session::set('_commande_ouverte_le', time());
        }

        View::render('pages/commander', [
            'page'      => 'livre',
            'ouverte'   => Boutique::ouverte(),
            'prix'      => Boutique::prix(),
            'zones'     => Zone::optionsPubliques(),
            'retrait'   => Boutique::retrait(),
            'valeurs'   => $valeurs,
            'erreurs'   => $erreurs,
            'leurre'    => self::LEURRE,
        ], $code);

        if ($erreurs !== [] || $code !== 200) {
            exit;
        }
    }
}
