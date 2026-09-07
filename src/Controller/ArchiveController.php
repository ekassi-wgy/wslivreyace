<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Site;
use App\Core\View;
use App\Model\Archive;
use App\Model\Media;

/**
 * Les archives (brief §4, lot G4).
 *
 * Trois écrans, et une adresse par pièce :
 *
 *   /archives                        le fonds, ses six catégories, sa recherche
 *   /archives/{categorie}            une catégorie, filtrable par année
 *   /archives/{categorie}/{slug}     la notice — sa page, son adresse, son partage
 *
 * **La troisième est celle qui compte.** Le §9 du brief demande que chaque
 * photographie, chaque discours, chaque document se partage seul : c'est ce que
 * la page de notice rend possible, là où la visionneuse d'avant était une
 * surimpression sans adresse.
 *
 * Lecture seule, aucune session.
 */
final class ArchiveController
{
    /** Le fonds entier, avec la recherche du brief §4. */
    public static function index(): void
    {
        $recherche = trim((string) ($_GET['q'] ?? ''));
        $annee     = self::annee();

        View::render('pages/archives', [
            'page'      => 'archives',
            'categorie' => null,
            'notices'   => self::avecCouvertures(Archive::chercher(null, $annee, $recherche)),
            'comptes'   => Archive::comptesParCategorie(),
            'annees'    => Archive::annees(),
            'annee'     => $annee,
            'recherche' => $recherche,
        ]);
    }

    /**
     * Une catégorie du fonds.
     *
     * Une catégorie inconnue rend une 404 et non la planche entière : l'adresse
     * est publique et durable, elle doit dire la vérité sur ce qu'elle désigne.
     * C'est l'inverse du filtre par année, qui n'est qu'une commodité.
     */
    public static function categorie(array $params): void
    {
        $categorie = (string) ($params['categorie'] ?? '');

        if (!isset(Archive::CATEGORIES[$categorie])) {
            self::introuvable();
        }

        $recherche = trim((string) ($_GET['q'] ?? ''));
        $annee     = self::annee();
        $notices   = Archive::chercher($categorie, $annee, $recherche);

        $donnees = [
            'page'      => 'archives',
            'categorie' => $categorie,
            'comptes'   => Archive::comptesParCategorie(),
            'annees'    => Archive::annees($categorie),
            'annee'     => $annee,
            'recherche' => $recherche,
        ];

        /*
         * Les discours ont leur propre présentation (brief §4, lot G6).
         *
         * **La planche de vignettes ne leur convient pas** : un discours n'a
         * souvent aucune image, et une grille de tuiles grises ne dit rien de
         * ce qu'on y trouvera. L'index chronologique, lui, annonce pour chaque
         * pièce sa date, son lieu et ce qu'elle porte — vidéo, enregistrement,
         * transcription, document.
         *
         * Même adresse, même modèle, même filtres : seule la mise en page
         * change. Une adresse propre — `/discours` — aurait fait deux chemins
         * pour une même pièce, ce que la décision 3 interdit.
         */
        if ($categorie === 'discours') {
            View::render('pages/discours', $donnees + [
                'groupes'  => Archive::parDecennie($notices),
                'contenus' => Archive::contenus($notices),
                'total'    => count($notices),
            ]);

            return;
        }

        View::render('pages/archives', $donnees + [
            'notices' => self::avecCouvertures($notices),
        ]);
    }

    /** La notice : sa page propre, son adresse partageable. */
    public static function notice(array $params): void
    {
        $notice = Archive::parSlug(
            (string) ($params['categorie'] ?? ''),
            (string) ($params['slug'] ?? '')
        );

        if ($notice === null) {
            self::introuvable();
        }

        $fichiers = Archive::fichiers((int) $notice['id']);

        View::render('pages/archive', [
            'page'     => 'archives',
            'notice'   => $notice,
            'fichiers' => $fichiers,
            'video'    => Archive::videoYoutube($notice['video_url'] ?? null),
            // Les autres pièces de la même catégorie, pour ne pas laisser la
            // page en cul-de-sac. La notice courante en est retirée.
            'voisines' => self::avecCouvertures(array_values(array_filter(
                Archive::chercher((string) $notice['categorie'], null, '', 5),
                static fn(array $a): bool => (int) $a['id'] !== (int) $notice['id']
            ))),
        ] + self::partage($notice, $fichiers));
    }

    /**
     * L'aperçu de partage d'une notice.
     *
     * Le premier fichier fait l'image, et sa taille moyenne plutôt que
     * l'original : les plateformes rognent et recompressent de toute façon, et
     * un scan de huit mégaoctets ferait échouer la récupération de l'aperçu
     * chez plusieurs d'entre elles.
     *
     * @param array<string,mixed> $notice
     * @param array<int,array<string,mixed>> $fichiers
     * @return array<string,mixed>
     */
    private static function partage(array $notice, array $fichiers): array
    {
        $donnees = [
            'titre'       => trim((string) $notice['titre']) . ' — Archives Philippe Grégoire Yacé',
            'description' => self::resume($notice),
            'ogType'      => 'article',
        ];

        /*
         * L'aperçu de partage doit être une IMAGE, et le premier fichier n'en
         * est plus forcément une depuis le lot G5 : une notice de discours
         * peut commencer par son enregistrement. Servir un MP3 en `og:image`
         * ferait échouer la récupération de l'aperçu sans rien dire.
         */
        $images = array_values(array_filter($fichiers, static fn(array $f): bool => Media::est($f, 'image')));

        if ($images === []) {
            return $donnees;
        }

        $image = $images[0];

        return $donnees + [
            'ogImage'  => Site::url(Media::urlMoyen((string) $image['fichier'])),
            'ogAlt'    => Media::alternative($image),
            // Les dimensions de la dérivée ne sont pas connues sans la lire :
            // mieux vaut n'en annoncer aucune que d'annoncer celles de
            // l'original, qui donneraient un aperçu rogné de travers.
            'ogTaille' => null,
        ];
    }

    /**
     * La description de partage : la notice en une phrase.
     *
     * @param array<string,mixed> $notice
     */
    private static function resume(array $notice): string
    {
        $description = trim((string) ($notice['description'] ?? ''));

        if ($description !== '') {
            return mb_strimwidth(preg_replace('/\s+/', ' ', $description) ?? '', 0, 200, '…');
        }

        // Sans description, la fiche signalétique fait la phrase : elle vaut
        // mieux qu'un texte générique répété sur toutes les pièces du fonds.
        $morceaux = array_filter([
            Archive::categorie((string) $notice['categorie']),
            Archive::date($notice),
            trim((string) ($notice['lieu'] ?? '')),
        ]);

        return implode(' · ', $morceaux);
    }

    /**
     * Attache sa couverture à chaque notice, en une requête pour toute la
     * planche.
     *
     * @param array<int,array<string,mixed>> $notices
     * @return array<int,array<string,mixed>>
     */
    private static function avecCouvertures(array $notices): array
    {
        $couvertures = Archive::couvertures($notices);

        foreach ($notices as $i => $n) {
            $notices[$i]['couverture'] = $couvertures[(int) $n['id']] ?? null;
        }

        return $notices;
    }

    /** Le filtre par année, ou null. Une valeur illisible est ignorée. */
    private static function annee(): ?int
    {
        $brut = (string) ($_GET['annee'] ?? '');

        if ($brut === '' || preg_match('/^\d{4}$/', $brut) !== 1) {
            return null;
        }

        return (int) $brut;
    }

    private static function introuvable(): never
    {
        View::render('pages/404', ['titre' => 'Page introuvable', 'page' => 'archives'], 404);
        exit;
    }
}
