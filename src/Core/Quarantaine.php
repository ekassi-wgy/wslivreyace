<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Les fichiers envoyés par le public, avant relecture (brief §5, lot G8).
 *
 * **Le brief dit : « les contributions ne devront jamais être publiées
 * automatiquement ».** Cette classe est la moitié technique de cette phrase.
 *
 * L'autre moitié — la file de modération — ne suffirait pas seule, et c'est le
 * point qui ne va pas de soi : `medias/` est un dossier servi par Apache. Un
 * fichier qui y est déposé est téléchargeable par qui devine son nom, publié ou
 * non. Y ranger le document d'un inconnu que personne n'a encore ouvert
 * reviendrait à le publier à demi, en comptant sur l'obscurité du nom.
 *
 * Les contributions attendent donc **ailleurs** : un dossier refusé par Apache,
 * exclu de la réécriture, et qu'aucune route publique ne lit. Elles n'en
 * sortent que par `promouvoir()`, appelée par un modérateur.
 *
 * Trois barrières se recouvrent, comme pour `medias/` :
 *
 * 1. le **contrôle de type** au dépôt — celui de `Televersement`, réemployé
 *    tel quel : les mêmes formats, les mêmes plafonds, la même lecture des
 *    octets ;
 * 2. le **nom de destination**, fabriqué ici : le nom d'origine ne survit pas,
 *    ce qui règle les `../`, les doubles extensions et les collisions ;
 * 3. le **`.htaccess`** du dossier, qui refuse tout — c'est la barrière qui
 *    tient si les deux premières cèdent.
 *
 * @see Televersement pour le contrôle de type et les plafonds
 */
final class Quarantaine
{
    /**
     * Fichiers acceptés par envoi.
     *
     * Plus bas que le lot du back-office (vingt) : un visiteur n'a pas à
     * verser un fonds entier d'un coup, et chaque fichier reçu est un fichier
     * qu'un modérateur devra ouvrir. Cinq couvrent le cas courant — une
     * pochette de photographies, une lettre et son enveloppe.
     */
    public const LOT_MAX = 5;

    /** Racine du dossier de quarantaine, chemin absolu résolu. */
    public static function racine(): string
    {
        $dossier = (string) (Config::get('app')['quarantaine'] ?? '');
        $reel    = realpath($dossier);

        if ($reel === false || !is_dir($reel)) {
            throw new \RuntimeException('Dossier de quarantaine introuvable.');
        }

        return $reel;
    }

    /** Forme attendue d'un chemin stocké en base : « 2026/09/nom-a1b2c3d4.jpg ». */
    private const FORME = '#^\d{4}/\d{2}/[a-z0-9][a-z0-9\-]*\.(jpg|png|webp|pdf|mp3|m4a|ogg)$#';

    /**
     * Reçoit un fichier du public et le range en quarantaine.
     *
     * Le contrôle de type est celui du back-office, sans allègement : les
     * mêmes formats, les mêmes plafonds, la même lecture des octets. Un
     * visiteur anonyme n'a aucune raison d'avoir plus de latitude qu'un
     * éditeur connecté.
     *
     * @param array<string,mixed> $fichier une entrée de $_FILES
     * @return array{chemin:string,famille:string,octets:int,nom_origine:string}
     * @throws TeleversementErreur message destiné au visiteur
     */
    public static function recevoir(array $fichier): array
    {
        $mesure = Televersement::examiner($fichier);

        $mois    = date('Y/m');
        $dossier = self::racine() . '/' . $mois;

        if (!is_dir($dossier) && !mkdir($dossier, 0700, true) && !is_dir($dossier)) {
            throw new TeleversementErreur("Le fichier n'a pas pu être reçu. Réessayez plus tard.");
        }

        // Le nom de destination ne doit rien au nom d'origine — voir la
        // barrière n° 2 en tête de classe. Il ne porte même pas de radical
        // lisible : personne n'a à deviner ce que contient la quarantaine.
        do {
            $nom = bin2hex(random_bytes(8)) . '.' . $mesure['ext'];
        } while (file_exists($dossier . '/' . $nom));

        if (!move_uploaded_file((string) $fichier['tmp_name'], $dossier . '/' . $nom)) {
            throw new TeleversementErreur("Le fichier n'a pas pu être enregistré. Réessayez plus tard.");
        }

        // 0600 et non 0644 : rien ni personne, hors le processus du site, n'a
        // à lire un fichier non relu.
        chmod($dossier . '/' . $nom, 0600);

        return [
            'chemin'      => $mois . '/' . $nom,
            'famille'     => $mesure['famille'],
            'octets'      => $mesure['octets'],
            // Conservé pour le modérateur : c'est souvent la seule indication
            // de ce que contient le fichier. Jamais employé comme nom sur le
            // disque, et échappé partout où il s'affiche.
            'nom_origine' => mb_substr((string) ($fichier['name'] ?? ''), 0, 255),
        ];
    }

    /**
     * Chemin absolu d'un fichier en quarantaine, ou null.
     *
     * La forme est vérifiée puis le chemin résolu est comparé à la racine : un
     * `chemin` altéré en base ne doit pas permettre de lire — ni de déplacer —
     * hors de la quarantaine.
     */
    public static function chemin(string $relatif): ?string
    {
        if (preg_match(self::FORME, $relatif) !== 1) {
            return null;
        }

        $absolu = realpath(self::racine() . '/' . $relatif);

        if ($absolu === false || !str_starts_with($absolu, self::racine() . '/') || !is_file($absolu)) {
            return null;
        }

        return $absolu;
    }

    /**
     * Fait sortir un fichier de la quarantaine vers la médiathèque.
     *
     * **C'est le seul chemin de sortie**, et il n'est appelé que par un
     * modérateur authentifié. Le fichier est déplacé, non copié : laisser une
     * copie en quarantaine ferait deux exemplaires à sauvegarder, et un jour
     * deux exemplaires divergents.
     *
     * Le nom fabriqué ici reprend celui de `Televersement` — radical lisible et
     * huit caractères aléatoires — parce que le fichier devient public et que
     * son nom paraîtra dans une adresse.
     *
     * @return array{fichier:string,largeur:int,hauteur:int,octets:int}|null
     *         null si le fichier a disparu du disque
     */
    public static function promouvoir(string $relatif, string $nomSouhaite = ''): ?array
    {
        $absolu = self::chemin($relatif);

        if ($absolu === null) {
            return null;
        }

        return Televersement::adopter($absolu, $nomSouhaite);
    }

    /**
     * Efface un fichier refusé.
     *
     * Un refus ne laisse rien : le site n'a aucune raison de garder le
     * document d'un tiers qu'il a décidé de ne pas publier.
     */
    public static function effacer(string $relatif): void
    {
        $absolu = self::chemin($relatif);

        if ($absolu !== null) {
            @unlink($absolu);
        }
    }
}
