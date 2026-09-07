<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Csrf;
use App\Core\Debit;
use App\Core\Quarantaine;
use App\Core\Session;
use App\Core\TeleversementErreur;
use App\Core\Validator;
use App\Core\View;
use App\Model\Contribution;

/**
 * « Contribuez aux archives » (brief §5, lot G8).
 *
 * Le troisième formulaire ouvert du site, et le premier qui **reçoit des
 * fichiers d'un inconnu**. Toute la plomberie des deux premiers est reprise —
 * session par route, jeton, pièges à robots, plafond de débit — et une
 * barrière s'y ajoute, qui est le cœur du lot : les fichiers n'atterrissent
 * pas dans `medias/`.
 *
 * `medias/` est servi par Apache : un fichier qui y est déposé est
 * téléchargeable par qui devine son nom, publié ou non. Le document d'un
 * inconnu que personne n'a encore ouvert attend donc en quarantaine, dans un
 * dossier refusé par le serveur, et n'en sort qu'à l'acceptation.
 *
 * @see \App\Core\Quarantaine
 */
final class ContributionController
{
    private const ACTION = 'contribution';

    /** Un formulaire à joindre des fichiers se remplit plus lentement encore. */
    private const DELAI_MINIMAL = 5;

    /** Nom du champ leurre. Anodin à dessein : un robot le remplira. */
    private const LEURRE = 'site_web';

    public static function page(): void
    {
        Session::demarrer('pgy', '/');
        Session::set('_contribution_ouvert_le', time());

        self::afficher([], []);
    }

    public static function envoyer(): void
    {
        Session::demarrer('pgy', '/');

        /*
         * Le dépassement du plafond serveur est testé AVANT le jeton, et
         * l'ordre compte plus encore ici qu'ailleurs : un envoi de plusieurs
         * fichiers arrive vite à la limite de `post_max_size`, et un POST
         * tronqué arrive sans jeton. Vérifier le jeton d'abord annoncerait
         * « session expirée » à quelqu'un dont le seul tort est d'avoir joint
         * trois scans.
         */
        if (\App\Core\Televersement::envoiTronque()) {
            self::afficher([], ['_global' => sprintf(
                "L'envoi dépasse ce que le serveur accepte en une fois (%s au total). "
                . 'Envoyez vos pièces en plusieurs fois.',
                \App\Core\Televersement::poids(\App\Core\Televersement::limiteServeur())
            )], 413);
        }

        Csrf::exiger();

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

        // Les fichiers ne sont reçus qu'une fois la saisie validée : rien ne
        // sert d'écrire sur le disque un envoi qu'on va refuser.
        [$fichiers, $erreurFichiers] = self::recevoirFichiers();

        if ($erreurFichiers !== null) {
            // Les fichiers déjà reçus dans ce lot sont effacés : un envoi
            // partiel laisserait des pièces orphelines qu'aucune ligne ne
            // désigne, et que personne n'irait jamais chercher.
            foreach ($fichiers as $f) {
                Quarantaine::effacer($f['chemin']);
            }

            self::afficher($_POST, ['fichiers' => $erreurFichiers], 422);
        }

        Contribution::deposer([
            'nom'         => $v->valeur('nom'),
            'prenom'      => $v->valeur('prenom'),
            'email'       => $v->valeur('email'),
            'telephone'   => $v->valeur('telephone'),
            'description' => $v->valeur('description'),
            'date_approx' => $v->valeur('date_approx'),
            'source'      => $v->valeur('source'),
        ], $fichiers);

        Session::oublier('_contribution_ouvert_le');

        Session::message('succes', sprintf(
            'Merci, %s. Votre contribution est bien arrivée%s. Elle sera examinée avant '
            . "toute publication — c'est la règle du fonds, et elle vaut pour toutes les "
            . 'pièces. Nous vous écrirons à %s.',
            $v->valeur('nom'),
            $fichiers === [] ? '' : sprintf(' avec %d fichier%s', count($fichiers), count($fichiers) > 1 ? 's' : ''),
            $v->valeur('email')
        ));

        header('Location: /contribuer#message', true, 303);
        exit;
    }

    // -- Rouages -----------------------------------------------------------

    /** @param array<string,mixed> $post */
    private static function valider(array $post): Validator
    {
        $v = new Validator($post);

        $v->requis('nom', 'Votre nom')->longueur('nom', 'Votre nom', 2, 120)
          ->longueur('prenom', 'Votre prénom', 0, 120)
          ->requis('email', 'Votre adresse électronique')
          ->courriel('email', 'Votre adresse électronique')
          ->longueur('email', 'Votre adresse électronique', 0, 180)
          ->longueur('telephone', 'Votre téléphone', 0, 40)
          ->requis('description', "Description de l'archive")
          ->longueur('description', "Description de l'archive", 20, 3000)
          ->longueur('date_approx', 'Date approximative', 0, 60)
          ->longueur('source', 'Origine de la pièce', 0, 300);

        /*
         * La cession de droits, et c'est une exigence juridique et non une
         * formalité : le contributeur nous confie un document dont il détient
         * les droits, et le site le publiera. Sans son accord explicite et
         * horodaté, rien ne prouverait qu'il a été donné.
         */
        if (($post['droits'] ?? '') !== '1') {
            $v->erreur('droits', 'Vous devez confirmer que vous détenez les droits sur les pièces envoyées.');
        }

        // Piège à robots : champ masqué, retiré aux lecteurs d'écran. Un
        // visiteur ne le voit pas, un robot le remplit.
        if (trim((string) ($post[self::LEURRE] ?? '')) !== '') {
            $v->erreur('_global', "Votre envoi n'a pas pu être traité.");
        }

        // Délai minimal. En session et non dans un champ caché : un champ
        // caché se réécrit, la session non.
        $ouvert = (int) Session::get('_contribution_ouvert_le', 0);

        if ($ouvert > 0 && (time() - $ouvert) < self::DELAI_MINIMAL) {
            $v->erreur('_global', 'Votre envoi est parti un peu vite. Réessayez dans quelques secondes.');
        }

        return $v;
    }

    /**
     * Reçoit les pièces jointes et les range en quarantaine.
     *
     * @return array{0:array<int,array{chemin:string,famille:string,octets:int,nom_origine:string}>,1:?string}
     */
    private static function recevoirFichiers(): array
    {
        $entree = $_FILES['fichiers'] ?? [];
        $noms   = $entree['name'] ?? [];

        if (!is_array($noms) || $noms === []) {
            // Aucun fichier : un témoignage écrit sans pièce reste une
            // contribution utile — quelqu'un qui sait où se trouve une archive
            // nous apprend quelque chose.
            return [[], null];
        }

        if (count($noms) > Quarantaine::LOT_MAX) {
            return [[], sprintf(
                '%d fichiers d\'un coup, c\'est trop : %d au maximum par envoi. '
                . 'Vous pourrez en envoyer d\'autres ensuite.',
                count($noms),
                Quarantaine::LOT_MAX
            )];
        }

        $recus = [];

        foreach (array_keys($noms) as $i) {
            $fichier = [
                'name'     => $entree['name'][$i]     ?? '',
                'type'     => $entree['type'][$i]     ?? '',
                'tmp_name' => $entree['tmp_name'][$i] ?? '',
                'error'    => $entree['error'][$i]    ?? UPLOAD_ERR_NO_FILE,
                'size'     => $entree['size'][$i]     ?? 0,
            ];

            // Une case de fichier laissée vide n'est pas une erreur.
            if ((int) $fichier['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            try {
                $recus[] = Quarantaine::recevoir($fichier);
            } catch (TeleversementErreur $e) {
                /*
                 * **Un fichier refusé arrête tout l'envoi**, à l'inverse du
                 * dépôt du back-office où chaque refus est signalé et les
                 * autres passent. La différence tient à qui est devant
                 * l'écran : un éditeur voit sa planche et sait ce qui est
                 * entré, un visiteur n'a aucun moyen de le savoir. Mieux vaut
                 * qu'il recommence en connaissance de cause.
                 */
                return [$recus, self::nom($fichier['name']) . ' — ' . $e->getMessage()];
            }
        }

        return [$recus, null];
    }

    /** Le nom d'un fichier, réduit à ce qui s'affiche sans danger. */
    private static function nom(string $brut): string
    {
        $nom = trim(str_replace(["\r", "\n", "\0"], '', basename($brut)));

        return $nom === '' ? 'Le fichier' : mb_substr($nom, 0, 80);
    }

    /**
     * @param array<string,mixed> $valeurs
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $valeurs, array $erreurs, int $statut = 200): void
    {
        View::render('pages/contribuer', [
            'page'    => 'archives',
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
            'leurre'  => self::LEURRE,
            'lotMax'  => Quarantaine::LOT_MAX,
        ], $statut);

        if ($erreurs !== []) {
            exit;
        }
    }
}
