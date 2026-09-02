<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Limitation de débit des formulaires publics.
 *
 * Le back-office a la sienne depuis le lot B (`TentativeConnexion`), et les
 * deux ne se confondent pas — ce n'est pas la même unité de compte :
 *
 * - à la connexion, on compte les **échecs**, et une réussite remet le
 *   compteur à zéro. L'éditeur qui se trompe quatre fois avant d'entrer ne
 *   doit pas rester à un essai du blocage pendant un quart d'heure ;
 * - sur un formulaire public, on compte les **soumissions**, réussies
 *   comprises. Il n'y a pas d'échec à surveiller : ce qu'on borne, c'est le
 *   nombre de témoignages qu'un même visiteur peut déposer dans l'heure.
 *
 * Les fusionner aurait demandé un drapeau à chaque appel pour servir un seul
 * des deux appelants. Deux compteurs de vingt lignes coûtent moins cher qu'un
 * compteur générique de soixante.
 *
 * **Toute soumission compte, valide ou non.** Ne compter que celles qui
 * passent la validation rendrait le contournement gratuit : il suffirait
 * d'envoyer des formulaires fautifs. Le plafond est donc réglé assez haut pour
 * absorber les fautes de saisie d'un visiteur de bonne foi.
 *
 * **Ce que cette classe ne fait pas.** Elle ne filtre pas le spam : un robot
 * qui reste sous le plafond passe. Un piège à robots — champ leurre, délai
 * minimal de remplissage — est le complément naturel, et il s'écrira avec le
 * formulaire qu'il protège, pas avant.
 */
final class Debit
{
    /**
     * Barèmes par action, en liste blanche : une action non déclarée lève une
     * exception plutôt que de passer sans plafond. Un oubli ferme, il n'ouvre
     * pas — c'est la même règle que la garde de route du back-office.
     *
     * Cinq soumissions par heure : un visiteur qui dépose un témoignage en
     * fait une, deux s'il se reprend, trois s'il s'y prend mal. Cinq laisse la
     * marge, et ramène un envoi automatisé à cent vingt par jour au lieu de
     * quelques milliers.
     *
     * @var array<string,array{plafond:int,fenetre:int,nom:string,pluriel:string}>
     */
    public const BAREMES = [
        'temoignage' => ['plafond' => 5, 'fenetre' => 60, 'nom' => 'témoignage', 'pluriel' => 'témoignages'],
        'contact'    => ['plafond' => 5, 'fenetre' => 60, 'nom' => 'message',    'pluriel' => 'messages'],
    ];

    /**
     * Le visiteur a-t-il encore droit à une soumission ?
     *
     * À appeler **avant** le traitement, et le rejet doit être rendu au
     * visiteur sans avoir rien écrit en base.
     */
    public static function autorise(string $action): bool
    {
        $bareme = self::bareme($action);

        return self::compter($action, $bareme['fenetre']) < $bareme['plafond'];
    }

    /**
     * Inscrit une soumission.
     *
     * À appeler dès que le formulaire est reçu — avant la validation, pas
     * après : c'est la requête qu'on borne, pas le succès.
     */
    public static function enregistrer(string $action): void
    {
        self::bareme($action);   // valide l'action avant d'écrire

        self::purger();

        Database::pdo()->prepare(
            'INSERT INTO soumission_publique (action, ip) VALUES (?, ?)'
        )->execute([$action, self::ipBinaire()]);
    }

    /**
     * Minutes à attendre avant que la plus ancienne soumission ne sorte de la
     * fenêtre. Toujours au moins 1 : annoncer « réessayez dans 0 minute » ferait
     * réessayer aussitôt, pour rien.
     */
    public static function attenteMinutes(string $action): int
    {
        $bareme = self::bareme($action);

        $ligne = Database::one(
            'SELECT TIMESTAMPDIFF(MINUTE, MIN(soumis_le), NOW()) AS ecoule
               FROM soumission_publique
              WHERE action = ?
                AND ip = ?
                AND soumis_le > NOW() - INTERVAL ? MINUTE',
            [$action, self::ipBinaire(), $bareme['fenetre']]
        );

        return max(1, $bareme['fenetre'] - (int) ($ligne['ecoule'] ?? 0));
    }

    /**
     * Message de refus, destiné au visiteur.
     *
     * Il dit ce qui s'est passé et quand réessayer, sans accuser : la personne
     * qui le lit est presque toujours quelqu'un qui a cliqué deux fois, pas un
     * robot. Le nombre déjà déposé n'est pas révélé — sur une adresse partagée,
     * ce ne sont pas forcément ses envois.
     *
     * Le contrôleur qui l'affiche doit réafficher la saisie avec, comme le fait
     * déjà tout formulaire du back-office : perdre son texte parce qu'on a
     * cliqué deux fois est la meilleure façon de faire renoncer un signataire.
     */
    public static function refus(string $action): string
    {
        $bareme = self::bareme($action);
        $minutes = self::attenteMinutes($action);

        return sprintf(
            'Trop d\'envois depuis cette connexion : le formulaire n\'accepte que %d %s par heure. '
            . 'Réessayez dans %d minute%s.',
            $bareme['plafond'],
            $bareme['pluriel'],
            $minutes,
            $minutes > 1 ? 's' : ''
        );
    }

    /** Soumissions du visiteur pour cette action, dans la fenêtre. */
    public static function compter(string $action, ?int $fenetre = null): int
    {
        $fenetre ??= self::bareme($action)['fenetre'];

        $ligne = Database::one(
            'SELECT COUNT(*) AS n
               FROM soumission_publique
              WHERE action = ?
                AND ip = ?
                AND soumis_le > NOW() - INTERVAL ? MINUTE',
            [$action, self::ipBinaire(), $fenetre]
        );

        return (int) ($ligne['n'] ?? 0);
    }

    /**
     * Purge les lignes sorties de toute fenêtre utile.
     *
     * À l'écriture plutôt que par une tâche planifiée : l'hébergement visé n'a
     * pas de planificateur, et la table ne porte que les soumissions du jour.
     * Un jour de rétention, soit bien au-delà de la plus longue fenêtre : de
     * quoi lire le journal après coup si un afflux se produit.
     */
    public static function purger(): void
    {
        Database::pdo()->exec(
            'DELETE FROM soumission_publique WHERE soumis_le < NOW() - INTERVAL 1 DAY'
        );
    }

    /**
     * @return array{plafond:int,fenetre:int,nom:string,pluriel:string}
     * @throws \InvalidArgumentException action non déclarée
     */
    private static function bareme(string $action): array
    {
        if (!isset(self::BAREMES[$action])) {
            // Erreur de programmation, jamais une saisie : le nom de l'action
            // est écrit dans le contrôleur, il ne vient pas de la requête.
            throw new \InvalidArgumentException("Action sans barème de débit : $action");
        }

        return self::BAREMES[$action];
    }

    /**
     * IP de l'appelant, en binaire — `inet_pton` couvre IPv4 et IPv6.
     *
     * `REMOTE_ADDR` seul, jamais `X-Forwarded-For` : cet en-tête est fourni par
     * le client, et le lire ici permettrait de repartir de zéro à chaque envoi
     * en changeant une chaîne de caractères. Derrière un répartiteur de charge,
     * il faudra le lire — mais seulement après avoir vérifié que la requête
     * vient bien de lui.
     *
     * Une adresse partagée — un cybercafé, un opérateur mobile derrière un NAT —
     * partage donc son plafond. C'est la contrepartie assumée : cinq témoignages
     * par heure restent tenables même à plusieurs, et l'alternative (un compteur
     * en session) se remet à zéro en vidant ses cookies.
     */
    private static function ipBinaire(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        return inet_pton($ip) ?: inet_pton('0.0.0.0');
    }
}
