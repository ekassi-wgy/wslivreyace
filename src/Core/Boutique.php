<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Commande;
use App\Model\Parametre;
use App\Model\Zone;

/**
 * Ce que coûte une commande, et si la boutique accepte d'en prendre (lot G3).
 *
 * **Le total se calcule ici et nulle part ailleurs.** Le formulaire public
 * l'affiche, mais ce qu'il affiche n'est jamais ce qu'on enregistre : le
 * contrôleur rappelle `total()` au moment de l'envoi, à partir de la quantité
 * et de la zone reçues, et des tarifs lus en base. Un total repris du
 * formulaire se commande à zéro franc en modifiant un champ caché.
 *
 * **La boutique est fermée tant que l'éditeur ne l'ouvre pas.** Le brief n'a
 * pas de date de parution — le commanditaire l'a confirmé le 7 septembre 2026 —
 * et prendre des commandes en paiement à la livraison pour un ouvrage qui
 * n'existe pas encore, c'est promettre une remise qu'on ne peut pas tenir. Deux
 * conditions, et il les faut toutes : la case cochée **et** un prix saisi.
 * Ouvrir sans prix afficherait « 0 F CFA ».
 *
 * @see \App\Core\Paiement pour la passerelle, décrite et toujours pas appelée
 */
final class Boutique
{
    /**
     * Le franc CFA est la seule devise du site aujourd'hui, et il n'a pas de
     * subdivision : tous les montants sont des entiers.
     */
    public const DEVISE = 'XOF';

    /**
     * Plafond par commande.
     *
     * Ni une règle commerciale ni une gestion de stock : une borne de bon sens.
     * Une librairie qui en veut cinquante appelle — et un formulaire ouvert au
     * public sans barrière de paiement doit avoir une borne quelque part.
     */
    public const QUANTITE_MAX = 20;

    /** La boutique prend-elle des commandes ? */
    public static function ouverte(): bool
    {
        return Parametre::lire('boutique_ouverte') === '1' && self::prix() !== null;
    }

    /**
     * Le prix d'un exemplaire, en francs entiers. Null s'il n'est pas saisi.
     *
     * Le paramètre est du texte — la colonne `parametre.valeur` l'est — mais sa
     * saisie est un entier depuis le lot G3 : une valeur vide, un zéro ou une
     * saisie fautive rendent null plutôt que zéro, pour que l'appelant ne
     * puisse pas facturer gratuitement sans le vouloir.
     */
    public static function prix(): ?int
    {
        $brut = trim((string) Parametre::lire('livre_prix'));

        if ($brut === '' || !ctype_digit($brut)) {
            return null;
        }

        $prix = (int) $brut;

        return $prix > 0 ? $prix : null;
    }

    /** Le prix écrit comme on le lit : « 25 000 F CFA ». */
    public static function prixLisible(): string
    {
        $prix = self::prix();

        return $prix === null ? '' : Commande::montant((float) $prix, self::DEVISE);
    }

    /** Un montant quelconque, dans la devise de la boutique. */
    public static function somme(int $montant): string
    {
        return Commande::montant((float) $montant, self::DEVISE);
    }

    /**
     * Le décompte d'une commande : prix unitaire, frais, total.
     *
     * **C'est l'autorité.** Elle borne la quantité elle-même plutôt que de
     * faire confiance à l'appelant, et refuse une zone qui n'est pas livrable
     * — auquel cas `zone` revient null et les frais à zéro, ce que le
     * contrôleur doit traiter comme un refus et non comme une livraison
     * gratuite.
     *
     * @return array{
     *   quantite:int, prix_unitaire:int, frais:int, total:int,
     *   zone_id:?int, zone_libelle:string, livrable:bool
     * }
     */
    public static function total(int $quantite, ?int $zoneId): array
    {
        $prix     = self::prix() ?? 0;
        $quantite = max(1, min(self::QUANTITE_MAX, $quantite));

        $frais    = 0;
        $libelle  = '';
        $livrable = false;

        if ($zoneId !== null && Zone::livrable($zoneId)) {
            $frais    = Zone::frais($zoneId) ?? 0;
            $libelle  = Zone::chaine($zoneId);
            $livrable = true;
        }

        return [
            'quantite'      => $quantite,
            'prix_unitaire' => $prix,
            'frais'         => $frais,
            'total'         => $prix * $quantite + $frais,
            'zone_id'       => $livrable ? $zoneId : null,
            'zone_libelle'  => $libelle,
            'livrable'      => $livrable,
        ];
    }

    /** Le point de retrait, tel que l'éditeur l'a écrit. Vide s'il n'y en a pas. */
    public static function retrait(): string
    {
        return trim((string) Parametre::lire('retrait_lieu'));
    }

    /**
     * Le retrait est-il proposé ?
     *
     * Seulement si un lieu est renseigné : offrir « retrait sur place » sans
     * dire où est une promesse creuse, et le client s'en aperçoit après avoir
     * commandé.
     */
    public static function retraitPossible(): bool
    {
        return self::retrait() !== '';
    }
}
