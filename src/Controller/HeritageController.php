<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Site;
use App\Core\View;
use App\Model\Archive;
use App\Model\Heritage;
use App\Model\Media;
use App\Model\Temoignage;

/**
 * Héritage (brief §6, CDC §4.5, lot G7).
 *
 * La dernière page publique du cahier des charges d'origine, et la seule qui
 * n'attendait rien de technique — seulement de la matière. Le brief en a fait
 * une rubrique à dix sujets : elle est donc adossée aux données comme le reste
 * du site, et s'ouvre sujet par sujet à mesure qu'ils arrivent.
 *
 *   /heritage          l'index, groupé par rubrique
 *   /heritage/{slug}   un sujet — sa page, son adresse, son partage
 *
 * Lecture seule, aucune session.
 */
final class HeritageController
{
    public static function index(): void
    {
        $groupes = Heritage::parRubrique();

        // Les couvertures de tous les sujets en une requête, groupes confondus.
        $tous = [];

        foreach ($groupes as $lot) {
            $tous = [...$tous, ...$lot];
        }

        View::render('pages/heritage', [
            'page'        => 'heritage',
            'groupes'     => $groupes,
            'couvertures' => Heritage::couvertures($tous),
            // Les témoignages ne sont pas une rubrique d'Héritage : ils vivent
            // à leur adresse depuis le lot F1, avec leur formulaire et leur
            // modération. L'index y renvoie plutôt que de les recopier.
            'temoignages' => Temoignage::listerPubliees(3),
        ]);
    }

    public static function sujet(array $params): void
    {
        $sujet = Heritage::parSlug((string) ($params['slug'] ?? ''));

        if ($sujet === null) {
            View::render('pages/404', ['titre' => 'Page introuvable', 'page' => 'heritage'], 404);
            return;
        }

        $images = Heritage::images((int) $sujet['id']);

        View::render('pages/heritage-sujet', [
            'page'    => 'heritage',
            'sujet'   => $sujet,
            'images'  => $images,
            'video'   => Archive::videoYoutube($sujet['video_url'] ?? null),
            'voisins' => Heritage::voisins((int) $sujet['id'], (string) $sujet['rubrique']),
        ] + self::partage($sujet, $images));
    }

    /**
     * L'aperçu de partage d'un sujet.
     *
     * @param array<string,mixed> $sujet
     * @param array<int,array<string,mixed>> $images
     * @return array<string,mixed>
     */
    private static function partage(array $sujet, array $images): array
    {
        $donnees = [
            'titre'       => trim((string) $sujet['titre']) . ' — Héritage de Philippe Grégoire Yacé',
            'description' => self::resume($sujet),
            'ogType'      => 'article',
        ];

        if ($images === []) {
            return $donnees;
        }

        return $donnees + [
            'ogImage'  => Site::url(Media::urlMoyen((string) $images[0]['fichier'])),
            'ogAlt'    => Media::alternative($images[0]),
            // Les dimensions de la dérivée ne sont pas connues sans la lire :
            // mieux vaut n'en annoncer aucune que celles de l'original.
            'ogTaille' => null,
        ];
    }

    /** @param array<string,mixed> $sujet */
    private static function resume(array $sujet): string
    {
        foreach (['sous_titre', 'description'] as $champ) {
            $v = trim((string) ($sujet[$champ] ?? ''));

            if ($v !== '') {
                return mb_strimwidth(preg_replace('/\s+/', ' ', $v) ?? '', 0, 200, '…');
            }
        }

        // Sans texte, la rubrique et le lieu font la phrase : ils valent mieux
        // qu'un libellé générique répété sur tous les sujets.
        return implode(' · ', array_filter([
            Heritage::rubrique((string) $sujet['rubrique']),
            Heritage::date($sujet),
            trim((string) ($sujet['lieu'] ?? '')),
        ]));
    }
}
