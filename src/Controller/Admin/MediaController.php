<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Core\Admin;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Televersement;
use App\Core\TeleversementErreur;
use App\Core\Validator;
use App\Core\View;
use App\Model\Media;

/**
 * Médiathèque : archives photographiques et images des fiches (CDC §4.6).
 *
 * Comme la modération, et pour la même raison, ce n'est pas une sous-classe de
 * CrudController : on ne crée pas un média par un formulaire, on en dépose un
 * fichier. La création est un téléversement — un lot de fichiers, un jeu de
 * lignes — et l'écran de modification ne touche qu'aux métadonnées. Le fichier
 * lui-même n'est jamais modifiable : pour changer l'image, on en dépose une
 * autre et on supprime la première. C'est plus honnête qu'un remplacement
 * silencieux qui laisserait des fiches pointer sur autre chose que ce qui y
 * avait été choisi.
 *
 * Le dépôt se fait par lot : des archives arrivent par dizaines, en une
 * enveloppe. Les métadonnées se complètent ensuite, fiche par fiche.
 */
final class MediaController
{
    private const CHEMIN = '/medias';

    /** Au-delà, l'envoi est refusé avant même d'être lu. */
    private const LOT_MAX = 20;

    public static function liste(): void
    {
        $filtre = $_GET['categorie'] ?? 'tous';
        if (!isset(Media::CATEGORIES[$filtre])) {
            $filtre = 'tous';
        }

        View::admin('medias/liste', [
            'titre'      => 'Médiathèque',
            'actif'      => 'medias',
            'filtre'     => $filtre,
            'lignes'     => Media::listerPar($filtre === 'tous' ? null : $filtre),
            'compteurs'  => Media::compteurs(),
            'tailleMax'  => min(Televersement::TAILLE_MAX, Televersement::limiteServeur()),
            'lotMax'     => self::LOT_MAX,
            'scripts'    => [Admin::asset('js/medias.js')],
        ]);
    }

    /**
     * Dépôt d'un lot de fichiers.
     *
     * Un fichier refusé n'annule pas les autres : sur un lot de vingt scans,
     * le seul qui dépasse la taille ne doit pas faire recommencer les dix-neuf
     * qui sont passés. Chaque refus est dit, avec le nom du fichier concerné.
     */
    public static function televerser(): void
    {
        // AVANT le jeton, et c'est l'ordre qui compte : un envoi coupé par
        // post_max_size arrive avec $_POST vide, donc sans jeton. Vérifier le
        // jeton d'abord annoncerait « session expirée » à un éditeur dont le
        // seul tort est d'avoir déposé trop lourd.
        if (Televersement::envoiTronque()) {
            Session::message('erreur', sprintf(
                "L'envoi dépasse ce que le serveur accepte en une fois (%s au total). "
                . 'Déposez les fichiers en plusieurs lots.',
                Televersement::poids(Televersement::limiteServeur())
            ));
            self::rediriger();
        }

        Csrf::exiger();

        $categorie = (string) ($_POST['categorie'] ?? 'portrait');
        if (!isset(Media::CATEGORIES[$categorie])) {
            $categorie = 'portrait';
        }

        $fichiers = self::normaliser($_FILES['fichiers'] ?? []);

        if ($fichiers === []) {
            Session::message('erreur', 'Aucun fichier sélectionné.');
            self::rediriger();
        }

        if (count($fichiers) > self::LOT_MAX) {
            Session::message('erreur', sprintf(
                '%d fichiers d\'un coup, c\'est trop : %d au maximum par dépôt.',
                count($fichiers),
                self::LOT_MAX
            ));
            self::rediriger();
        }

        $deposes = 0;

        foreach ($fichiers as $fichier) {
            $nom = self::nomLisible((string) ($fichier['name'] ?? ''));

            try {
                $recu = Televersement::recevoir($fichier);

                Media::creer([
                    'fichier'   => $recu['fichier'],
                    'titre'     => self::titreDepuisNom($nom),
                    'categorie' => $categorie,
                    'largeur'   => $recu['largeur'],
                    'hauteur'   => $recu['hauteur'],
                    'octets'    => $recu['octets'],
                    'statut'    => 'brouillon',
                ]);

                $deposes++;
            } catch (TeleversementErreur $e) {
                Session::message('erreur', $nom . ' — ' . $e->getMessage());
            }
        }

        if ($deposes > 0) {
            // Le crédit avant la publication : l'écran de publication le
            // refuse sans, autant le dire au moment du dépôt.
            Session::message('succes', sprintf(
                '%d fichier%s déposé%s en brouillon. Complétez la légende et le crédit avant publication.',
                $deposes,
                $deposes > 1 ? 's' : '',
                $deposes > 1 ? 's' : ''
            ));
        }

        self::rediriger();
    }

    public static function formulaire(array $params): void
    {
        $ligne = self::exigerLigne((int) $params['id']);

        self::afficher($ligne, $ligne, []);
    }

    public static function mettreAJour(array $params): void
    {
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $v = new Validator($_POST);

        $v->requis('titre', 'Titre')->longueur('titre', 'Titre', 2, 200)
          ->longueur('legende', 'Légende', 0, 500)
          ->longueur('credit', 'Crédit', 0, 200)
          ->longueur('date_prise', 'Date de prise de vue', 0, 60)
          ->parmi('categorie', 'Catégorie', array_keys(Media::CATEGORIES))
          ->parmi('statut', 'Statut', array_keys(Media::STATUTS))
          ->entier('ordre', 'Rang', -999, 999);

        // Le sourçage des archives est une exigence du cahier des charges
        // (§6), et une protection : une photographie publiée sans mention de
        // provenance engage l'éditeur.
        if ($v->valeur('statut') === 'publie' && $v->valeur('credit') === '') {
            $v->erreur('credit', 'Une image publiée doit porter son crédit.');
        }

        if (!$v->estValide()) {
            self::afficher($ligne, $_POST, $v->erreurs());
        }

        Media::modifier($id, [
            'titre'      => $v->valeur('titre'),
            'legende'    => self::ouNull($v->valeur('legende')),
            'credit'     => self::ouNull($v->valeur('credit')),
            'date_prise' => self::ouNull($v->valeur('date_prise')),
            'categorie'  => $v->valeur('categorie', 'portrait'),
            'ordre'      => (int) $v->valeur('ordre', '0'),
            'statut'     => $v->valeur('statut', 'brouillon'),
        ]);

        Session::message('succes', sprintf('« %s » a été enregistrée.', $v->valeur('titre')));
        self::rediriger();
    }

    public static function basculerStatut(array $params): void
    {
        Csrf::exiger();

        $id    = (int) $params['id'];
        $ligne = self::exigerLigne($id);

        $enLigne = ($ligne['statut'] ?? 'brouillon') === 'publie';

        // Même règle que par le formulaire : la bascule est un raccourci, pas
        // une porte dérobée pour publier une archive sans crédit.
        if (!$enLigne && trim((string) ($ligne['credit'] ?? '')) === '') {
            Session::message('erreur', sprintf(
                '« %s » ne peut pas être publiée : son crédit manque.',
                $ligne['titre'] ?? '—'
            ));
            self::rediriger();
        }

        Media::modifier($id, ['statut' => $enLigne ? 'brouillon' : 'publie']);

        Session::message('succes', sprintf(
            '« %s » %s.',
            $ligne['titre'] ?? '—',
            $enLigne ? 'repasse en brouillon' : 'passe en ligne'
        ));

        self::rediriger();
    }

    /**
     * Supprime la ligne, le fichier et sa vignette.
     *
     * Les fiches qui affichaient l'image sont détachées dans le même
     * mouvement : sans cela, l'actualité garderait le chemin d'un fichier
     * effacé et la page publique servirait un cadre vide.
     */
    public static function supprimer(array $params): void
    {
        Csrf::exiger();

        $id      = (int) $params['id'];
        $ligne   = self::exigerLigne($id);
        $fichier = (string) $ligne['fichier'];

        $detachees = Media::detacher($fichier);
        Televersement::supprimer($fichier);
        Media::supprimer($id);

        $message = sprintf('« %s » a été supprimée.', $ligne['titre'] ?? '—');

        if ($detachees > 0) {
            $message .= sprintf(
                ' %d fiche%s qui l\'affichai%s se retrouve%s sans image.',
                $detachees,
                $detachees > 1 ? 's' : '',
                $detachees > 1 ? 'ent' : 't',
                $detachees > 1 ? 'nt' : ''
            );
        }

        Session::message('succes', $message);
        self::rediriger();
    }

    // -- Rouages -----------------------------------------------------------

    /**
     * @param array<string,mixed> $ligne
     * @param array<string,mixed> $valeurs
     * @param array<string,string> $erreurs
     */
    private static function afficher(array $ligne, array $valeurs, array $erreurs): never
    {
        View::admin('medias/formulaire', [
            'titre'   => 'Image',
            'actif'   => 'medias',
            'ligne'   => $ligne,
            'valeurs' => $valeurs,
            'erreurs' => $erreurs,
            'usages'  => Media::usages((string) $ligne['fichier']),
        ], $erreurs === [] ? 200 : 422);

        exit;
    }

    /**
     * Aplatit l'entrée multiple de $_FILES.
     *
     * PHP range un champ `fichiers[]` par propriété — name[0], name[1], puis
     * tmp_name[0]… — et non par fichier. La forme d'origine est illisible pour
     * qui traite les fichiers un par un.
     *
     * @param array<string,mixed> $entree
     * @return array<int,array<string,mixed>>
     */
    private static function normaliser(array $entree): array
    {
        if (!isset($entree['name'])) {
            return [];
        }

        if (!is_array($entree['name'])) {
            return (int) $entree['error'] === UPLOAD_ERR_NO_FILE ? [] : [$entree];
        }

        $liste = [];

        foreach (array_keys($entree['name']) as $i) {
            if ((int) $entree['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;   // champ laissé vide : ce n'est pas une erreur
            }

            $liste[] = [
                'name'     => $entree['name'][$i],
                'type'     => $entree['type'][$i] ?? '',
                'tmp_name' => $entree['tmp_name'][$i] ?? '',
                'error'    => $entree['error'][$i],
                'size'     => $entree['size'][$i] ?? 0,
            ];
        }

        return $liste;
    }

    /**
     * Nom d'origine ramené à quelque chose d'affichable.
     *
     * Il vient du poste de l'éditeur et sert uniquement de repère dans les
     * messages — jamais de nom de fichier sur le serveur, celui-ci étant
     * fabriqué par Televersement.
     */
    private static function nomLisible(string $nom): string
    {
        $nom = basename(str_replace('\\', '/', $nom));
        $nom = preg_replace('/[\x00-\x1F\x7F]/u', '', $nom) ?? '';

        return $nom === '' ? 'fichier sans nom' : mb_substr($nom, 0, 80);
    }

    /** « portrait-yace-1965.jpg » -> « Portrait yace 1965 ». */
    private static function titreDepuisNom(string $nom): string
    {
        $base = (string) pathinfo($nom, PATHINFO_FILENAME);
        $base = trim((string) preg_replace('/[_\-]+/', ' ', $base));
        $base = trim((string) preg_replace('/\s+/', ' ', $base));

        if ($base === '') {
            return 'Sans titre';
        }

        return mb_substr(mb_strtoupper(mb_substr($base, 0, 1)) . mb_substr($base, 1), 0, 200);
    }

    private static function ouNull(string $valeur): ?string
    {
        return $valeur === '' ? null : $valeur;
    }

    /** @return array<string,mixed> */
    private static function exigerLigne(int $id): array
    {
        $ligne = Media::trouver($id);

        if ($ligne === null) {
            View::admin('404', ['titre' => 'Page introuvable', 'actif' => ''], 404);
            exit;
        }

        return $ligne;
    }

    private static function rediriger(): never
    {
        // On revient sur la catégorie d'où l'on vient : classer trente
        // archives ne doit pas renvoyer trente fois sur la vue complète.
        $categorie = $_POST['retour'] ?? $_GET['categorie'] ?? '';
        $suffixe = isset(Media::CATEGORIES[$categorie]) ? '?categorie=' . $categorie : '';

        header('Location: ' . Admin::url(self::CHEMIN) . $suffixe, true, 302);
        exit;
    }
}
