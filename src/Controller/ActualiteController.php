<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\DateFr;
use App\Core\View;
use App\Model\Actualite;
use App\Model\Media;

/**
 * Actualités et revue de presse (CDC §4.7).
 *
 * Trois pages en lecture seule : la liste, la fiche par slug, et la revue de
 * presse. Rien n'est écrit ici, donc **aucune session n'est ouverte** — la
 * règle posée avec les témoignages tient : un cookie n'apparaît que sur les
 * routes qui en ont besoin, et ces trois-là n'en ont pas.
 *
 * **Ce que le visiteur voit est ce que le back-office a publié**, et rien
 * d'autre : le tri des brouillons se fait en base (`Actualite::PUBLIQUE`), pas
 * dans un gabarit.
 *
 * **La revue de presse est une page à part, pas un filtre de plus.** Une
 * coupure de presse n'est pas une actualité du site : elle renvoie à un texte
 * publié ailleurs, et ce qui l'identifie est l'organe qui l'a publiée, pas la
 * date à laquelle nous l'avons saisie. Elle se lit donc comme une
 * bibliographie — organe, année, titre — là où la liste d'actualités se lit
 * comme un fil. Les articles de presse restent malgré tout dans la liste
 * générale : ils font partie de ce qui se passe autour de l'ouvrage, et les
 * masquer surprendrait l'éditeur qui vient d'en publier un. Ce qui disparaît
 * de la barre de filtres, en revanche, c'est la pastille « Presse » — elle
 * ferait doublon avec la page dédiée, qui prend sa place au même endroit.
 */
final class ActualiteController
{
    /** Nombre d'autres articles proposés au pied d'une fiche. */
    private const VOISINES = 3;

    /**
     * La liste, éventuellement filtrée par catégorie (`?categorie=dedicace`).
     *
     * Le filtre est un paramètre de requête et non un segment d'URL : c'est
     * une vue de la même ressource, et `Site::canonique()` — qui laisse
     * tomber la chaîne de requête — fait pointer toutes les vues filtrées sur
     * `/actualites`. C'est exactement ce qu'un canonical doit dire.
     *
     * Une catégorie inconnue affiche la liste entière plutôt qu'une 404 : la
     * ressource demandée existe, seul le point de vue est illisible, et un
     * cul-de-sac serait une réponse disproportionnée pour un lien mal recopié.
     */
    public static function liste(): void
    {
        $demande = trim((string) ($_GET['categorie'] ?? ''));
        $categorie = isset(Actualite::CATEGORIES[$demande]) ? $demande : null;

        View::render('pages/actualites', [
            'page'      => 'actus',
            'entrees'   => Actualite::listerPubliees($categorie),
            'categorie' => $categorie,
            'comptes'   => Actualite::comptesParCategorie(),
        ]);
    }

    /** Une actualité, par son slug. */
    public static function detail(array $params): void
    {
        $actu = Actualite::parSlug((string) ($params['slug'] ?? ''));

        // Inconnue ou non publiée : même réponse, sans distinguer les deux.
        if ($actu === null) {
            self::introuvable();
        }

        View::render('pages/actualite', [
            'page'   => 'actus',
            'actu'   => $actu,
            // La fiche de médiathèque porte la légende et le crédit ; la
            // colonne `image` de l'actualité ne porte qu'un chemin.
            'media'  => self::media($actu),
            'autres' => Actualite::autresQue((int) $actu['id'], self::VOISINES),
        ]);
    }

    /** La revue de presse : ce qui a été écrit ailleurs sur l'ouvrage. */
    public static function presse(): void
    {
        View::render('pages/presse', [
            'page'     => 'actus',
            'parAnnee' => self::parAnnee(Actualite::listerPubliees('presse')),
        ]);
    }

    // -- Rouages -----------------------------------------------------------

    /**
     * Fiche de médiathèque d'une illustration, si elle en a une.
     *
     * La colonne `image` porte un chemin, pas une clé étrangère : le fichier
     * peut avoir été retiré de la médiathèque sans que la fiche le sache. Un
     * `null` ici n'est donc pas une anomalie, et la page s'affiche sans image.
     *
     * @param array<string,mixed> $actu
     * @return array<string,mixed>|null
     */
    private static function media(array $actu): ?array
    {
        $fichier = trim((string) ($actu['image'] ?? ''));

        return $fichier === '' ? null : Media::parFichier($fichier);
    }

    /**
     * Regroupe des entrées par année de publication, ordre conservé.
     *
     * La revue de presse se lit par millésime : c'est la seule page du site
     * où l'on cherche « ce qui est paru en 2026 » plutôt que « la dernière
     * nouvelle ». Le regroupement est fait ici et non dans le gabarit, qui
     * n'aurait plus qu'à parcourir deux boucles.
     *
     * @param array<int,array<string,mixed>> $entrees déjà classées, récentes d'abord
     * @return array<string,array<int,array<string,mixed>>>
     */
    private static function parAnnee(array $entrees): array
    {
        $groupes = [];

        foreach ($entrees as $e) {
            $annee = DateFr::annee((string) ($e['publie_le'] ?? ''));
            $groupes[$annee][] = $e;
        }

        return $groupes;
    }

    /** 404 du site public, dans la charte, et l'exécution s'arrête là. */
    private static function introuvable(): never
    {
        View::render('pages/404', [], 404);
        exit;
    }
}
