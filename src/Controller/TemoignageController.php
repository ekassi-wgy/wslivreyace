<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Csrf;
use App\Core\Debit;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Model\Temoignage;

/**
 * Témoignages : la page publique et son formulaire (CDC §4.8).
 *
 * Premier écrit public du site, et le seul pour l'instant. Il porte donc la
 * plomberie que les formulaires suivants réutiliseront : ouverture de la
 * session côté visiteur, jeton anti-CSRF, plafond de débit, et le renvoi du
 * formulaire avec sa saisie quand quelque chose cloche.
 *
 * **La session n'est ouverte que sur ces deux routes.** Un site qui pose un
 * cookie à chaque visiteur, pour un formulaire que la plupart ne rencontreront
 * jamais, s'impose une bannière de consentement pour rien. Ici le cookie
 * n'apparaît qu'à ceux qui ouvrent la page des témoignages, et il ne sert qu'à
 * deux choses : porter le jeton, et retenir le message de confirmation le
 * temps d'une redirection.
 *
 * **Rien de ce qui arrive ici n'est publié.** Le dépôt entre en `en_attente`,
 * et seul un modérateur le fait passer en ligne (lot D1) — ce sont des propos
 * sur une personne réelle.
 */
final class TemoignageController
{
    /** Clé du barème de débit, déclarée dans App\Core\Debit. */
    private const ACTION = 'temoignage';

    /**
     * Délai minimal entre l'affichage du formulaire et son envoi.
     *
     * Un robot remplit et poste en moins d'une seconde ; un humain qui écrit
     * quelques lignes sur quelqu'un met bien davantage. Trois secondes ne
     * gênent personne et coupent l'essentiel des envois automatisés.
     */
    private const DELAI_MINIMAL = 3;

    /** Nom du champ leurre. Anodin à dessein : un robot le remplira. */
    private const LEURRE = 'site_web';

    public static function page(): void
    {
        Session::demarrer('pgy', '/');

        // Horodatage de l'ouverture, pour le délai minimal. En session et non
        // dans un champ caché : un champ caché se réécrit, la session non.
        Session::set('_temoignage_ouvert_le', time());

        self::afficher([], []);
    }

    public static function deposer(): void
    {
        Session::demarrer('pgy', '/');
        Csrf::exiger();

        // Le plafond d'abord : refuser tôt évite d'écrire quoi que ce soit.
        if (!Debit::autorise(self::ACTION)) {
            self::afficher($_POST, ['_global' => Debit::refus(self::ACTION)], 429);
        }

        // Toute soumission compte, valide ou non — sinon le plafond se
        // contournerait en envoyant des formulaires fautifs.
        Debit::enregistrer(self::ACTION);

        $v = self::valider($_POST);

        if (!$v->estValide()) {
            self::afficher($_POST, $v->erreurs(), 422);
        }

        Temoignage::deposer([
            'auteur_nom'      => $v->valeur('auteur_nom'),
            'auteur_fonction' => $v->valeur('auteur_fonction') ?: null,
            'auteur_email'    => $v->valeur('auteur_email'),
            'contenu'         => $v->valeur('contenu'),
        ]);

        Session::oublier('_temoignage_ouvert_le');

        Session::message('succes', sprintf(
            'Merci, %s. Votre témoignage est bien arrivé. Il sera lu avant publication : '
            . 'il porte sur une personne réelle, et rien ne paraît ici sans relecture.',
            $v->valeur('auteur_nom')
        ));

        // Redirection après écriture : sans elle, un rafraîchissement de page
        // redéposerait le même témoignage.
        header('Location: /temoignages#message', true, 303);
        exit;
    }

    // -- Rouages -----------------------------------------------------------

    /** @param array<string,mixed> $post */
    private static function valider(array $post): Validator
    {
        $v = new Validator($post);

        $v->requis('auteur_nom', 'Votre nom')->longueur('auteur_nom', 'Votre nom', 2, 160)
          ->longueur('auteur_fonction', 'En quelle qualité', 0, 200)
          ->requis('auteur_email', 'Votre adresse électronique')
          ->courriel('auteur_email', 'Votre adresse électronique')
          ->longueur('auteur_email', 'Votre adresse électronique', 0, 180)
          ->requis('contenu', 'Votre témoignage')
          ->longueur('contenu', 'Votre témoignage', 40, 5000);

        // L'adresse est exigée alors qu'elle ne s'affiche jamais : le
        // modérateur doit pouvoir revenir vers le signataire avant de publier
        // des propos sous son nom. C'est dit sur le formulaire.

        // Piège à robots. Le champ est masqué et retiré aux lecteurs d'écran ;
        // seul un automate le remplit.
        if (trim((string) ($post[self::LEURRE] ?? '')) !== '') {
            $v->erreur('_global', "Envoi refusé : un champ réservé au filtrage a été rempli. "
                . 'Si vous voyez ce message par erreur, laissez le champ « site web » vide.');
        }

        // Délai minimal. Le refus est explicite plutôt que silencieux : perdre
        // le témoignage de quelqu'un pour lui faire croire qu'il est parti
        // serait pire que de lui demander de renvoyer.
        $ouvert = Session::get('_temoignage_ouvert_le');

        if (is_int($ouvert) && time() - $ouvert < self::DELAI_MINIMAL) {
            $v->erreur('_global', 'Envoi trop rapide pour être relu. Reprenez votre texte '
                . 'et renvoyez-le : il est resté dans le champ.');
        }

        return $v;
    }

    /**
     * Rend la page : les témoignages publiés, puis le formulaire.
     *
     * En cas d'erreur la saisie repart avec la page — quelqu'un qui vient
     * d'écrire dix lignes sur un proche ne doit pas les retrouver effacées
     * parce qu'il a oublié son adresse.
     *
     * @param array<string,mixed>  $valeurs
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $valeurs, array $erreurs, int $statut = 200): void
    {
        // Le formulaire est réaffiché : le délai minimal repart d'ici, sans
        // quoi une correction rapide serait prise pour un envoi automatisé.
        if ($erreurs !== []) {
            Session::set('_temoignage_ouvert_le', time());
        }

        View::render('pages/temoignages', [
            'page'     => 'temoignages',
            'publiees' => Temoignage::listerPubliees(),
            'valeurs'  => $valeurs,
            'erreurs'  => $erreurs,
            'leurre'   => self::LEURRE,
        ], $statut);

        if ($statut !== 200) {
            exit;
        }
    }
}
