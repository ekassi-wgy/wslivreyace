<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\Site;
use App\Core\View;
use App\Model\Archive;
use App\Model\Media;
use App\Model\Periode;
use App\Model\Repere;

/**
 * La biographie et ses périodes (brief §3, CDC §4.4, lot G10).
 *
 *   /biographie          le parcours : les périodes, puis la frise des repères
 *   /biographie/{slug}   une période — son récit, ses images, ses jalons,
 *                        et les pièces du fonds produites pendant qu'elle durait
 *
 * La page portait cinq chapitres écrits dans le gabarit, sans date et sans
 * adresse. Ce qui change n'est pas leur nombre : c'est qu'une période **est
 * datée**, et que sa datation suffit à lui rattacher la frise et le fonds sans
 * qu'aucun lien ne soit saisi nulle part.
 *
 * Lecture seule, aucune session.
 */
final class BiographieController
{
    /** Combien de pièces du fonds paraissent sur la page d'une période. */
    private const PIECES_PAR_PERIODE = 8;

    public static function page(): void
    {
        $periodes = Periode::listerPubliees();
        $reperes  = Repere::listerPubliees();

        /*
         * La période de chaque repère, calculée une fois ici plutôt que dans le
         * gabarit : elle sert deux fois — aux onglets de la frise et à
         * l'attribut que le filtre lit sur chaque ligne — et la faire calculer
         * deux fois aurait suffi à ce que les deux divergent.
         *
         * Les onglets ne portent que les périodes qui ont recueilli un repère,
         * règle inchangée depuis le lot G0 : un onglet qui donne sur une frise
         * vide est un lien mort.
         */
        $situation = [];
        $onglets   = [];

        foreach ($reperes as $r) {
            $p = Periode::contenant($periodes, (int) $r['tri']);
            $situation[(int) $r['id']] = $p === null ? null : (int) $p['id'];

            if ($p !== null) {
                $onglets[(int) $p['id']] = $p;
            }
        }

        View::render('pages/biographie', [
            'page'        => 'bio',
            'periodes'    => $periodes,
            'couvertures' => Periode::couvertures($periodes),
            'reperes'     => $reperes,
            'situation'   => $situation,
            'onglets'     => $onglets,
        ]);
    }

    /**
     * Une période, à son adresse propre.
     *
     * Les périodes publiées sont lues en une fois plutôt qu'une par slug : il
     * y en a douze, et la même requête donne la voisine d'avant et celle
     * d'après — une biographie se lit dans l'ordre, et arriver par un lien
     * profond ne doit pas priver du reste du récit.
     */
    public static function periode(array $params): void
    {
        $slug     = (string) ($params['slug'] ?? '');
        $periodes = Periode::listerPubliees();
        $rang     = null;

        foreach ($periodes as $i => $p) {
            if ((string) $p['slug'] === $slug) {
                $rang = $i;
                break;
            }
        }

        if ($rang === null) {
            View::render('pages/404', ['titre' => 'Page introuvable', 'page' => 'bio'], 404);
            return;
        }

        $periode = $periodes[$rang];
        $debut   = (int) $periode['debut'];
        $fin     = (int) $periode['fin'];
        $images  = Periode::images((int) $periode['id']);

        $pieces = Archive::entreAnnees($debut, $fin, self::PIECES_PAR_PERIODE);

        View::render('pages/periode', [
            'page'        => 'bio',
            'periode'     => $periode,
            'images'      => $images,
            'reperes'     => Repere::entreAnnees($debut, $fin),
            'pieces'      => $pieces,
            'couvertures' => Archive::couvertures($pieces),
            // Le total, pour dire ce qui n'est pas montré. Une seule requête de
            // plus, et seulement quand la page en affiche déjà.
            'totalPieces' => $pieces === [] ? 0 : Archive::compterEntreAnnees($debut, $fin),
            'precedente'  => $periodes[$rang - 1] ?? null,
            'suivante'    => $periodes[$rang + 1] ?? null,
        ] + self::partage($periode, $images));
    }

    /**
     * L'aperçu de partage d'une période.
     *
     * @param array<string,mixed> $periode
     * @param array<int,array<string,mixed>> $images
     * @return array<string,mixed>
     */
    private static function partage(array $periode, array $images): array
    {
        $donnees = [
            'titre'       => trim((string) $periode['titre']) . ' — Philippe Grégoire Yacé',
            'description' => self::resume($periode),
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

    /** @param array<string,mixed> $periode */
    private static function resume(array $periode): string
    {
        foreach (['sous_titre', 'recit'] as $champ) {
            $v = trim((string) ($periode[$champ] ?? ''));

            if ($v !== '') {
                return mb_strimwidth(preg_replace('/\s+/', ' ', $v) ?? '', 0, 200, '…');
            }
        }

        // Sans texte, les bornes font la phrase : elles disent au moins de
        // quelles années il s'agit, ce qu'un libellé générique ne dirait pas.
        return trim(sprintf(
            'Philippe Grégoire Yacé, %s',
            Periode::annees($periode)
        ), ' ,');
    }
}
