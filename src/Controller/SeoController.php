<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Database;
use App\Core\Site;

/**
 * Les deux fichiers que les moteurs demandent en premier : `robots.txt` et
 * `sitemap.xml` (brief §9, README §9).
 *
 * **Servis par le routeur, pas posés à la racine**, et c'est le point qui
 * décide de tout le reste : le plan du site doit lister les actualités et les
 * événements publiés. Un fichier statique se périmerait à la première
 * publication, et personne ne penserait à le régénérer — le sitemap dirait
 * alors à Google exactement le contraire de ce qu'on lui demande.
 *
 * Aucune session n'est ouverte : ces deux réponses ne font que lire, et un
 * cookie posé sur une requête de robot ne sert à personne.
 */
final class SeoController
{
    /**
     * Pages fixes du site, avec leur poids relatif.
     *
     * `priority` ne classe rien dans les résultats — c'est une indication de
     * hiérarchie interne, et Google la traite comme telle. Elle dit ici ce que
     * le site considère comme son cœur : l'accueil et l'ouvrage d'abord.
     *
     * Les pages qui n'ont pas à être indexées n'y sont pas : la 404 n'existe
     * pas comme adresse, et les écrans du back-office sont interdits par
     * `robots.txt`.
     *
     * @var array<string,string> chemin => priorité
     */
    private const FIXES = [
        '/'                => '1.0',
        '/le-livre'        => '0.9',
        '/biographie'      => '0.9',
        '/archives'        => '0.8',
        '/actualites'      => '0.7',
        '/evenements'      => '0.6',
        '/temoignages'     => '0.6',
        '/revue-de-presse' => '0.5',
        '/contact'         => '0.4',
        '/mentions-legales' => '0.2',
    ];

    /**
     * Le plan du site.
     *
     * Les dates de dernière modification viennent de la base quand elle en
     * porte une — `maj_le` sur les actualités et les événements. Les pages
     * fixes n'en déclarent aucune : annoncer la date du jour à chaque requête
     * apprendrait au moteur que la valeur ne veut rien dire, et il cesserait
     * de la lire.
     */
    public static function sitemap(): void
    {
        $urls = [];

        foreach (self::FIXES as $chemin => $priorite) {
            $urls[] = ['loc' => Site::url($chemin), 'priorite' => $priorite, 'maj' => null];
        }

        /*
         * Les mêmes conditions de publication que les pages elles-mêmes, et
         * c'est une exigence et non une précaution : une adresse listée ici
         * mais rendue en 404 fait chuter la confiance que le moteur accorde
         * au plan entier. Les constantes vivent dans les modèles ; les
         * répéter ici les ferait diverger au premier changement, d'où la
         * requête écrite au plus près de leur définition.
         */
        foreach (Database::all(
            "SELECT slug, maj_le FROM actualite
              WHERE statut = 'publie' AND publie_le IS NOT NULL
              ORDER BY publie_le DESC"
        ) as $a) {
            $urls[] = [
                'loc'      => Site::url('/actualites/' . $a['slug']),
                'priorite' => '0.6',
                'maj'      => self::jour((string) $a['maj_le']),
            ];
        }

        // Publiés **et annulés** : un événement annulé garde sa page, qui dit
        // qu'il est annulé. Voir `App\Model\Evenement`.
        foreach (Database::all(
            "SELECT slug, maj_le FROM evenement
              WHERE statut IN ('publie', 'annule')
              ORDER BY debut_le DESC"
        ) as $e) {
            $urls[] = [
                'loc'      => Site::url('/evenements/' . $e['slug']),
                'priorite' => '0.5',
                'maj'      => self::jour((string) $e['maj_le']),
            ];
        }

        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->setIndent(true);
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $u) {
            $xml->startElement('url');
            $xml->writeElement('loc', $u['loc']);

            if ($u['maj'] !== null) {
                $xml->writeElement('lastmod', $u['maj']);
            }

            $xml->writeElement('priority', $u['priorite']);
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();

        header('Content-Type: application/xml; charset=UTF-8');
        echo $xml->outputMemory();
    }

    /**
     * Ce que les robots ont le droit de parcourir.
     *
     * Le back-office est interdit — non qu'un `Disallow` protège quoi que ce
     * soit, il n'est qu'une convention polie, mais indexer un écran de
     * connexion n'apporte rien et le fait remonter sur le nom du site.
     *
     * `medias/` reste ouvert, et c'est délibéré : les images d'archives ont
     * vocation à être trouvées, c'est même tout l'objet du §10 du brief.
     */
    public static function robots(): void
    {
        $lignes = [
            'User-agent: *',
            'Disallow: /cmsadmin/',
            '',
            'Sitemap: ' . Site::url('/sitemap.xml'),
            '',
        ];

        header('Content-Type: text/plain; charset=UTF-8');
        echo implode("\n", $lignes);
    }

    /**
     * La date d'un horodatage MySQL, au format que le protocole attend.
     *
     * Le jour suffit : `sitemap.xml` accepte la date seule, et la minute d'une
     * correction de coquille n'apprend rien à un moteur qui repasse au mieux
     * une fois par jour. Rend `null` sur une valeur vide ou illisible plutôt
     * qu'une date fausse — un `lastmod` erroné est pire qu'absent.
     */
    private static function jour(string $horodatage): ?string
    {
        $t = strtotime($horodatage);

        return $t === false ? null : date('Y-m-d', $t);
    }
}
