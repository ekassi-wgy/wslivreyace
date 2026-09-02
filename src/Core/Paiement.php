<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Passerelle de paiement : ce que le site sait d'elle, en un seul endroit.
 *
 * **Décision prise : carte.abidjan.net**, le backend de paiement du site de
 * référence — mobile money (Orange, MTN, Moov), Wave, Visa/Mastercard, APaym
 * et Visa QR. Il est en service, il connaît déjà la zone et ses moyens de
 * paiement, et le remplacer avant d'avoir vendu un exemplaire coûterait un
 * chantier pour un bénéfice nul.
 *
 * **Elle peut changer**, et c'est pourquoi elle est décrite ici plutôt que
 * répandue dans le code. Le site de référence écrivait ses sept adresses en
 * dur dans le JavaScript de trois pages ; en changer demandait de les
 * retrouver. Ici, `config/config.php` porte l'hôte et le nom, cette classe
 * porte les points d'entrée, et rien d'autre n'en sait rien.
 *
 * Ce que cette classe ne fait pas : appeler la passerelle. Le tunnel de
 * commande n'est pas écrit — il suppose les pages publiques de la boutique.
 * Ce qui est ici est ce dont le back-office a besoin aujourd'hui (nommer un
 * mode de paiement, dire d'où vient une commande) et ce que le tunnel trouvera
 * posé le jour où il s'écrira.
 */
final class Paiement
{
    /**
     * Modes de paiement de la passerelle, et le point d'entrée de chacun.
     *
     * Les chemins sont relevés sur l'intégration du site de référence
     * (`reference/views/checkout.php`). Ils appartiennent à carte.abidjan.net :
     * une autre passerelle apporterait sa propre table.
     */
    public const MODES = [
        'orange-money' => ['libelle' => 'Orange Money',       'chemin' => '/livre/orange-money'],
        'mtn-money'    => ['libelle' => 'MTN Money',          'chemin' => '/livre/mtn-money'],
        'moov-money'   => ['libelle' => 'Moov Money',         'chemin' => '/livre/moov-money'],
        'wave'         => ['libelle' => 'Wave',               'chemin' => '/livre/wave'],
        'visa'         => ['libelle' => 'Visa / Mastercard',  'chemin' => '/livre/visa'],
        'apaym'        => ['libelle' => 'APaym',              'chemin' => '/livre/apaym/paiement_apaym'],
        'visa-qr'      => ['libelle' => 'Visa QR',            'chemin' => '/livre/apaym/paiement_qr'],
    ];

    /** Identifiant de la passerelle, écrit dans `commande.passerelle`. */
    public static function passerelle(): string
    {
        return (string) (Config::get('paiement')['passerelle'] ?? '');
    }

    /** Nom lisible, affiché dans le back-office. */
    public static function nom(): string
    {
        return (string) (Config::get('paiement')['nom'] ?? self::passerelle());
    }

    /** Racine de la passerelle, sans barre finale. */
    public static function base(): string
    {
        return rtrim((string) (Config::get('paiement')['base'] ?? ''), '/');
    }

    /**
     * Adresse d'un mode de paiement, pour le tunnel à venir.
     *
     * Null si le mode est inconnu ou si la passerelle n'est pas configurée —
     * l'appelant doit décider quoi en faire, jamais poster à l'aveugle.
     */
    public static function url(string $mode): ?string
    {
        if (!isset(self::MODES[$mode]) || self::base() === '') {
            return null;
        }

        return self::base() . self::MODES[$mode]['chemin'];
    }

    /**
     * Libellé d'un mode.
     *
     * Une commande ancienne peut porter un mode que la passerelle actuelle ne
     * propose plus : on rend alors la valeur brute plutôt que rien, une
     * commande devant rester lisible après un changement de passerelle.
     */
    public static function libelleMode(?string $mode): string
    {
        $mode = (string) $mode;

        return self::MODES[$mode]['libelle'] ?? ($mode === '' ? '—' : $mode);
    }

    /** @return array<string,string> clé => libellé, pour les filtres et les listes */
    public static function libelles(): array
    {
        return array_map(static fn(array $m): string => $m['libelle'], self::MODES);
    }
}
