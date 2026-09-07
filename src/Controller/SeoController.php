<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Database;
use App\Core\Langue;
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
        '/heritage'        => '0.8',
        '/actualites'      => '0.7',
        '/evenements'      => '0.6',
        '/temoignages'     => '0.6',
        '/revue-de-presse' => '0.5',
        '/contribuer'      => '0.5',
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
        $chemins = [];

        foreach (self::FIXES as $chemin => $priorite) {
            $chemins[] = ['chemin' => $chemin, 'priorite' => $priorite, 'maj' => null];
        }

        /*
         * La page de l'auteur n'existe que lorsque son nom est renseigné (lot
         * G2) : sans ce contrôle, le plan annoncerait une adresse qui répond
         * 404, ce qui est précisément ce qu'un sitemap ne doit jamais faire.
         */
        if (trim((string) \App\Model\Parametre::lire('auteur_nom', '')) !== '') {
            $chemins[] = ['chemin' => '/auteur', 'priorite' => '0.6', 'maj' => null];
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
            $chemins[] = [
                'chemin'   => '/actualites/' . $a['slug'],
                'priorite' => '0.6',
                'maj'      => self::jour((string) $a['maj_le']),
            ];
        }

        /*
         * Les notices d'archives, et leurs six catégories (lot G4).
         *
         * C'est la part du plan qui grossira : le fonds est fait pour
         * s'enrichir pendant des années, et chaque pièce versée doit être
         * trouvable. Priorité haute pour les discours, dont la transcription
         * porte le contenu le plus recherché.
         */
        foreach (array_keys(\App\Model\Archive::comptesParCategorie()) as $cle) {
            // La bibliothèque des discours est une page à part entière et non
            // une simple planche filtrée (lot G6) : elle porte l'index
            // chronologique du fonds le plus recherché.
            $chemins[] = [
                'chemin'   => '/archives/' . $cle,
                'priorite' => $cle === 'discours' ? '0.8' : '0.7',
                'maj'      => null,
            ];
        }

        foreach (Database::all(
            "SELECT categorie, slug, maj_le FROM archive
              WHERE statut = 'publie'
              ORDER BY annee IS NULL, annee ASC, id ASC"
        ) as $n) {
            $chemins[] = [
                'chemin'   => '/archives/' . $n['categorie'] . '/' . $n['slug'],
                'priorite' => $n['categorie'] === 'discours' ? '0.8' : '0.6',
                'maj'      => self::jour((string) $n['maj_le']),
            ];
        }

        // Les sujets d'Héritage (lot G7) : chacun a sa page et son adresse,
        // et celle d'un lieu de mémoire a vocation à finir sur une plaque.
        foreach (Database::all(
            "SELECT slug, maj_le FROM heritage WHERE statut = 'publie' ORDER BY rubrique, ordre"
        ) as $h) {
            $chemins[] = [
                'chemin'   => '/heritage/' . $h['slug'],
                'priorite' => '0.6',
                'maj'      => self::jour((string) $h['maj_le']),
            ];
        }

        // Publiés **et annulés** : un événement annulé garde sa page, qui dit
        // qu'il est annulé. Voir `App\Model\Evenement`.
        foreach (Database::all(
            "SELECT slug, maj_le FROM evenement
              WHERE statut IN ('publie', 'annule')
              ORDER BY debut_le DESC"
        ) as $e) {
            $chemins[] = [
                'chemin'   => '/evenements/' . $e['slug'],
                'priorite' => '0.5',
                'maj'      => self::jour((string) $e['maj_le']),
            ];
        }

        /*
         * Chaque page paraît une fois **par langue ouverte**, et déclare les
         * autres versions d'elle-même en `xhtml:link` — c'est la forme que le
         * protocole prévoit, et la seule que Google lit dans un sitemap.
         *
         * Aujourd'hui une seule langue est ouverte : la boucle tourne une fois
         * et aucun `xhtml:link` n'est écrit. Le fichier est donc exactement
         * celui d'avant — mais il n'aura pas à être repris le jour où
         * l'anglais s'ouvrira (lot G1, README §9).
         */
        $langues = array_keys(Langue::ouvertes());

        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->setIndent(true);
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        if (Langue::multilingue()) {
            $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        }

        foreach ($chemins as $c) {
            foreach ($langues as $langue) {
                $xml->startElement('url');
                $xml->writeElement('loc', Site::base() . Langue::chemin($c['chemin'], $langue));

                if ($c['maj'] !== null) {
                    $xml->writeElement('lastmod', $c['maj']);
                }

                $xml->writeElement('priority', $c['priorite']);

                // Chaque version se déclare elle-même en plus des autres :
                // le protocole l'exige, un jeu incomplet est ignoré en bloc.
                if (Langue::multilingue()) {
                    foreach ($langues as $autre) {
                        $xml->startElement('xhtml:link');
                        $xml->writeAttribute('rel', 'alternate');
                        $xml->writeAttribute('hreflang', $autre);
                        $xml->writeAttribute('href', Site::base() . Langue::chemin($c['chemin'], $autre));
                        $xml->endElement();
                    }
                }

                $xml->endElement();
            }
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
