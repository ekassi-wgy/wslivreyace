<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Réception d'un fichier déposé depuis le back-office.
 *
 * Un fichier téléversé est la seule donnée qu'un site reçoive et qu'il puisse
 * ensuite exécuter. Trois barrières se recouvrent, et aucune n'est de trop :
 *
 * 1. le **type réel**, lu dans les octets du fichier (getimagesize + finfo),
 *    jamais l'extension ni le `Content-Type` du navigateur — les deux sont
 *    écrits par le client ;
 * 2. le **nom de destination**, fabriqué ici à partir d'un slug et de huit
 *    caractères aléatoires : le nom d'origine ne survit pas au dépôt, ce qui
 *    règle d'un coup les `../`, les caractères de contrôle, les doubles
 *    extensions et les collisions ;
 * 3. le `.htaccess` de `medias/`, qui neutralise tout gestionnaire de script
 *    dans le dossier — c'est la barrière qui tient si les deux premières
 *    cèdent.
 *
 * **Trois familles depuis le lot G5** : images, documents (PDF) et audio. Le
 * brief §4 demande des documents, des correspondances et des discours
 * enregistrés — ni l'un ni l'autre ne tient en JPEG.
 *
 * La vidéo n'y est pas, et ce n'est pas un oubli : elle reste chez son
 * hébergeur (décision 2 du brief, README §9). Une heure d'archive pèse
 * plusieurs gigaoctets et saturerait la bande passante d'un mutualisé dès la
 * première consultation groupée.
 *
 * **Ce que l'ouverture aux PDF change en matière de sûreté.** Une image est
 * validée sur ses octets par `getimagesize`, qui échoue sur tout ce qui n'en
 * est pas une. Un PDF n'a pas d'équivalent : seul son en-tête est vérifiable.
 * La barrière qui compte devient donc le `.htaccess` de `medias/`, qui
 * neutralise tout gestionnaire de script dans le dossier — un fichier déposé y
 * est une donnée servie, jamais un programme exécuté.
 */
final class Televersement
{
    /**
     * Plafond applicatif par famille, indépendant de celui du serveur.
     *
     * **Trois valeurs et non une**, parce que les trois familles n'ont pas le
     * même poids naturel : une photographie réduite tient dans huit méga-octets,
     * le scan d'une correspondance de vingt pages non, et l'enregistrement
     * d'un discours d'une heure encore moins. Un plafond unique aurait forcé à
     * prendre le plus large, ce qui aurait laissé passer des images de trente
     * méga-octets dans la galerie.
     */
    public const PLAFONDS = [
        'image'    =>  8 * 1024 * 1024,   //  8 Mio — une photo réduite
        'document' => 30 * 1024 * 1024,   // 30 Mio — un scan de plusieurs pages
        'audio'    => 60 * 1024 * 1024,   // 60 Mio — un discours d'une heure
    ];

    /** Plafond des images. Conservé : le nom est employé par les écrans. */
    public const TAILLE_MAX = 8 * 1024 * 1024;      // 8 Mio

    /**
     * Garde-fou contre la bombe de décompression : un PNG de 40 Ko peut
     * déclarer 30 000 × 30 000 pixels et réclamer plusieurs gigaoctets à
     * l'ouverture. La taille du fichier ne dit rien de celle de l'image.
     */
    public const PIXELS_MAX = 40_000_000;           // 40 Mpx

    /**
     * Tailles dérivées, par suffixe de nom de fichier.
     *
     * **Deux, et pas une.** La vignette (600 px) sert les planches du
     * back-office et les tuiles de la galerie ; la taille moyenne (1600 px)
     * sert la visionneuse d'archives et le second cran du `srcset` des tuiles
     * sur écran 2×. Sans elle, ouvrir une archive dans la visionneuse
     * téléchargerait le fichier d'origine — jusqu'à 8 Mio de scan pour
     * regarder une photo sur un téléphone.
     *
     * Les chemins sont **déduits du nom du fichier d'origine**, jamais
     * stockés : ajouter une taille ne demande donc aucune migration, et un
     * dérivé absent retombe sur l'original (voir `App\Model\Media`).
     */
    public const DERIVEES = ['vignette' => 600, 'moyen' => 1600];

    /**
     * Formats acceptés, indexés par la constante que rend `getimagesize`.
     * Le MIME sert de contre-épreuve, l'extension est celle du fichier écrit.
     */
    private const FORMATS = [
        IMAGETYPE_JPEG => ['mime' => 'image/jpeg', 'ext' => 'jpg'],
        IMAGETYPE_PNG  => ['mime' => 'image/png',  'ext' => 'png'],
        IMAGETYPE_WEBP => ['mime' => 'image/webp', 'ext' => 'webp'],
    ];

    /**
     * Formats non-image acceptés, par type MIME réel.
     *
     * Indexés par le MIME que rend `finfo`, et non par extension : c'est le
     * seul juge, l'extension étant écrite par le client. Un même format peut
     * en avoir plusieurs — `audio/mpeg` et `audio/mp3` désignent le même
     * fichier selon la version de la base magique du serveur.
     */
    private const FICHIERS = [
        'application/pdf' => ['ext' => 'pdf', 'famille' => 'document'],
        'audio/mpeg'      => ['ext' => 'mp3', 'famille' => 'audio'],
        'audio/mp3'       => ['ext' => 'mp3', 'famille' => 'audio'],
        'audio/mp4'       => ['ext' => 'm4a', 'famille' => 'audio'],
        'audio/x-m4a'     => ['ext' => 'm4a', 'famille' => 'audio'],
        'audio/ogg'       => ['ext' => 'ogg', 'famille' => 'audio'],
    ];

    /** Ce que le champ de dépôt annonce au navigateur. */
    public const ACCEPTE = 'image/jpeg,image/png,image/webp,application/pdf,audio/mpeg,audio/mp4,audio/ogg,.mp3,.m4a,.ogg,.pdf';

    /** Forme attendue d'un chemin stocké en base : « 2026/09/nom-a1b2c3d4.jpg ». */
    private const FORME = '#^\d{4}/\d{2}/[a-z0-9][a-z0-9\-]*\.(jpg|png|webp|pdf|mp3|m4a|ogg)$#';

    /**
     * Reçoit une entrée de `$_FILES` et rend la ligne à écrire en base.
     *
     * @param array<string,mixed> $fichier une entrée de $_FILES
     * @param string $nomSouhaite base du nom de fichier ; à défaut, le nom d'origine
     * @return array{fichier:string,largeur:int,hauteur:int,octets:int}
     * @throws TeleversementErreur message destiné à l'éditeur
     */
    public static function recevoir(array $fichier, string $nomSouhaite = ''): array
    {
        $mesure = self::examiner($fichier);
        $temporaire = (string) $fichier['tmp_name'];

        if ($mesure['famille'] !== 'image') {
            $relatif = self::deposer($temporaire, $fichier, $nomSouhaite, $mesure['ext'], true);

            return [
                'fichier' => $relatif,
                'famille' => $mesure['famille'],
                'largeur' => 0,
                'hauteur' => 0,
                'octets'  => $mesure['octets'],
            ];
        }

        $relatif = self::deposer($temporaire, $fichier, $nomSouhaite, $mesure['ext'], true);

        self::fabriquerDerivees(
            self::racine() . '/' . $relatif,
            $mesure['type'],
            $mesure['largeur'],
            $mesure['hauteur']
        );

        return [
            'fichier' => $relatif,
            'famille' => 'image',
            'largeur' => $mesure['largeur'],
            'hauteur' => $mesure['hauteur'],
            'octets'  => $mesure['octets'],
        ];
    }

    /**
     * Contrôle un fichier reçu **sans le déplacer**, et rend ce qu'il est.
     *
     * Extraite de `recevoir()` au lot G8 : les contributions du public passent
     * par exactement les mêmes barrières, mais atterrissent en quarantaine et
     * non dans `medias/`. Deux copies de ce contrôle auraient fini par
     * diverger, et c'est le genre de divergence dont on n'entend parler
     * qu'après.
     *
     * @param array<string,mixed> $fichier une entrée de $_FILES
     * @return array{famille:string,ext:string,octets:int,type:int,largeur:int,hauteur:int}
     * @throws TeleversementErreur message destiné à l'expéditeur
     */
    public static function examiner(array $fichier): array
    {
        $code = (int) ($fichier['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($code !== UPLOAD_ERR_OK) {
            throw new TeleversementErreur(self::messageCode($code));
        }

        $temporaire = (string) ($fichier['tmp_name'] ?? '');

        // Sans cette vérification, un `tmp_name` forgé ferait lire n'importe
        // quel fichier lisible par le serveur — /etc/passwd, la configuration.
        if ($temporaire === '' || !is_uploaded_file($temporaire)) {
            throw new TeleversementErreur("Le fichier n'a pas été reçu correctement. Réessayez.");
        }

        $octets = (int) filesize($temporaire);

        if ($octets === 0) {
            throw new TeleversementErreur('Le fichier est vide.');
        }

        /*
         * Le type réel est lu AVANT le plafond, et l'ordre compte depuis que
         * les familles ont des plafonds différents : refuser un discours de
         * quarante méga-octets au nom de la limite des images serait faux, et
         * le message le dirait de travers.
         */
        $mime = (string) (new \finfo(FILEINFO_MIME_TYPE))->file($temporaire);

        // `getimagesize` échoue sur tout ce qui n'est pas une image, y compris
        // un script PHP renommé en .jpg. C'est le juge des images.
        $mesure = @getimagesize($temporaire);
        $estImage = $mesure !== false && isset(self::FORMATS[$mesure[2]]);

        if (!$estImage && !isset(self::FICHIERS[$mime])) {
            throw new TeleversementErreur(
                'Format non accepté — quelle que soit son extension. '
                . 'Sont acceptés : les images JPEG, PNG et WebP, les documents PDF, '
                . 'les enregistrements MP3, M4A et OGG. '
                . "Les vidéos ne sont pas hébergées sur le site : elles se rattachent par leur adresse."
            );
        }

        $famille = $estImage ? 'image' : self::FICHIERS[$mime]['famille'];
        $plafond = self::PLAFONDS[$famille];

        if ($octets > $plafond) {
            throw new TeleversementErreur(sprintf(
                'Le fichier pèse %s ; la limite est de %s pour %s. Allégez-le avant de le déposer.',
                self::poids($octets),
                self::poids($plafond),
                match ($famille) {
                    'document' => 'un document',
                    'audio'    => 'un enregistrement',
                    default    => 'une image',
                }
            ));
        }

        /*
         * Les fichiers qui ne sont pas des images n'ont ni dimensions, ni
         * dérivées, ni contre-épreuve possible : `getimagesize` ne les lit pas,
         * et un PDF n'a pas d'équivalent qui vaille preuve. Le `.htaccess` de
         * `medias/` est ce qui les tient — voir l'en-tête de cette classe.
         */
        if (!$estImage) {
            return [
                'famille' => $famille,
                'ext'     => self::FICHIERS[$mime]['ext'],
                'octets'  => $octets,
                'type'    => 0,
                'largeur' => 0,
                'hauteur' => 0,
            ];
        }

        $format  = self::FORMATS[$mesure[2]];
        $largeur = (int) $mesure[0];
        $hauteur = (int) $mesure[1];

        // Contre-épreuve : le MIME lu par finfo doit confirmer celui du format
        // détecté. Deux lecteurs valent mieux qu'un sur un fichier hostile.
        if ($mime !== $format['mime']) {
            throw new TeleversementErreur("Le contenu du fichier ne correspond pas à son format annoncé.");
        }

        if ($largeur * $hauteur > self::PIXELS_MAX) {
            throw new TeleversementErreur(sprintf(
                'Image trop grande : %d × %d pixels, soit plus de %d millions. Réduisez-la.',
                $largeur,
                $hauteur,
                (int) (self::PIXELS_MAX / 1_000_000)
            ));
        }

        return [
            'famille' => 'image',
            'ext'     => $format['ext'],
            'octets'  => $octets,
            'type'    => $mesure[2],
            'largeur' => $largeur,
            'hauteur' => $hauteur,
        ];
    }

    /**
     * Adopte un fichier **déjà présent sur le disque** dans la médiathèque.
     *
     * Sert la promotion d'une contribution acceptée (lot G8) : le fichier a
     * quitté le navigateur il y a des jours, il n'est plus un envoi mais un
     * fichier du serveur. `move_uploaded_file` le refuserait, et c'est heureux
     * — c'est précisément ce qu'elle protège.
     *
     * Le contrôle de type n'est pas rejoué : il l'a été à la réception, et le
     * fichier n'a pas bougé depuis. Ses dimensions, en revanche, sont relues —
     * elles n'ont pas été conservées.
     *
     * @return array{fichier:string,largeur:int,hauteur:int,octets:int}|null
     */
    public static function adopter(string $absolu, string $nomSouhaite = ''): ?array
    {
        if (!is_file($absolu)) {
            return null;
        }

        $extension = strtolower((string) pathinfo($absolu, PATHINFO_EXTENSION));
        $octets    = (int) filesize($absolu);

        $mesure  = @getimagesize($absolu);
        $estImage = $mesure !== false && isset(self::FORMATS[$mesure[2]]);

        $relatif = self::deposer($absolu, ['name' => basename($absolu)], $nomSouhaite, $extension, false);

        if ($estImage) {
            self::fabriquerDerivees(
                self::racine() . '/' . $relatif,
                $mesure[2],
                (int) $mesure[0],
                (int) $mesure[1]
            );
        }

        return [
            'fichier' => $relatif,
            'largeur' => $estImage ? (int) $mesure[0] : 0,
            'hauteur' => $estImage ? (int) $mesure[1] : 0,
            'octets'  => $octets,
        ];
    }

    /**
     * Écrit le fichier reçu sur le disque et rend son chemin relatif.
     *
     * Commun aux trois familles : le rangement, le nom fabriqué et les droits
     * ne dépendent pas de ce qu'on dépose. Écrit une fois — la barrière n° 2
     * de l'en-tête de cette classe est ici, et une seconde copie de ces vingt
     * lignes finirait par en oublier un morceau.
     *
     * @param array<string,mixed> $fichier l'entrée de $_FILES, pour son nom d'origine
     * @throws TeleversementErreur
     */
    private static function deposer(
        string $temporaire,
        array $fichier,
        string $nomSouhaite,
        string $extension,
        bool $estUnEnvoi = true
    ): string {
        $mois    = date('Y/m');
        $dossier = self::racine() . '/' . $mois;

        // Rangement par mois : un dossier plat de plusieurs milliers d'archives
        // devient impraticable en FTP comme en sauvegarde.
        if (!is_dir($dossier) && !mkdir($dossier, 0755, true) && !is_dir($dossier)) {
            throw new TeleversementErreur("Le dossier de destination n'a pas pu être créé.");
        }

        $base = $nomSouhaite !== ''
            ? $nomSouhaite
            : (string) pathinfo((string) ($fichier['name'] ?? ''), PATHINFO_FILENAME);

        $base = mb_substr(Slug::depuis($base), 0, 60);

        // Un nom d'origine sans un seul caractère latin — « 討論.pdf » — ne
        // laisse rien après le slug. Sans ce repli, le fichier s'appellerait
        // « -a1b2c3d4.pdf » et sortirait de la forme attendue en base.
        if ($base === '') {
            $base = 'fichier';
        }

        do {
            $nom = $base . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
        } while (file_exists($dossier . '/' . $nom));

        /*
         * `move_uploaded_file` refuse tout ce qui n'est pas un envoi de la
         * requête en cours, et c'est sa raison d'être : elle empêche qu'un
         * chemin forgé fasse déplacer un fichier du serveur. Un fichier promu
         * depuis la quarantaine n'en est pas un — il a été contrôlé à sa
         * réception, des jours plus tôt — d'où `rename()` dans ce seul cas.
         */
        $place = $estUnEnvoi
            ? move_uploaded_file($temporaire, $dossier . '/' . $nom)
            : rename($temporaire, $dossier . '/' . $nom);

        if (!$place) {
            throw new TeleversementErreur("Le fichier n'a pas pu être enregistré sur le serveur.");
        }

        // 0644 explicite : la valeur par défaut dépend de l'umask du serveur,
        // et un fichier déposé n'a aucune raison d'être exécutable.
        chmod($dossier . '/' . $nom, 0644);

        return $mois . '/' . $nom;
    }

    /**
     * Fabrique les tailles dérivées : réduction dans un carré, sans recadrage
     * — une photo d'archive perd son sens si on lui coupe la moitié du sujet.
     *
     * La source n'est décodée qu'une fois pour les deux tailles : sur un scan
     * de plusieurs mégapixels, le décodage coûte davantage que les deux
     * réductions réunies.
     *
     * **Une taille plus grande que l'original n'est pas fabriquée.** Agrandir
     * ne rend aucun détail et produirait un JPEG plus lourd que le fichier
     * qu'il remplace ; l'appelant retombe sur l'original, qui est déjà à la
     * bonne échelle.
     *
     * L'échec n'annule pas le dépôt : la médiathèque retombe alors sur
     * l'original, et l'éditeur ne perd pas son fichier pour une extension
     * absente du serveur.
     */
    private static function fabriquerDerivees(string $absolu, int $type, int $largeur, int $hauteur): void
    {
        if (!function_exists('imagecreatetruecolor')) {
            return;   // GD absente : l'original fera office de vignette.
        }

        try {
            $source = match ($type) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($absolu),
                IMAGETYPE_PNG  => imagecreatefrompng($absolu),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($absolu) : false,
                default        => false,
            };

            if ($source === false) {
                return;
            }

            // Les scanners et les téléphones écrivent l'orientation en EXIF
            // plutôt que dans les pixels. Le navigateur en tient compte sur
            // l'original ; GD, non — sans cette rotation, la vignette d'un
            // portrait sortirait couchée à côté d'une image droite.
            if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($absolu);
                $angle = match ((int) ($exif['Orientation'] ?? 1)) {
                    3 => 180,
                    6 => -90,
                    8 => 90,
                    default => 0,
                };
                if ($angle !== 0) {
                    $pivote = imagerotate($source, $angle, 0);
                    if ($pivote !== false) {
                        imagedestroy($source);
                        $source  = $pivote;
                        $largeur = imagesx($source);
                        $hauteur = imagesy($source);
                    }
                }
            }

            foreach (self::DERIVEES as $suffixe => $cote) {
                $ratio = min($cote / max($largeur, 1), $cote / max($hauteur, 1), 1);

                // Ratio de 1 : l'original tient déjà dans le carré demandé.
                if ($ratio >= 1.0) {
                    continue;
                }

                $l = max(1, (int) round($largeur * $ratio));
                $h = max(1, (int) round($hauteur * $ratio));

                $derivee = imagecreatetruecolor($l, $h);

                // Les dérivées sortent en JPEG, qui ignore la transparence :
                // sans ce fond, une zone transparente de PNG virerait au noir.
                $blanc = imagecolorallocate($derivee, 255, 255, 255);
                imagefilledrectangle($derivee, 0, 0, $l, $h, $blanc);
                imagecopyresampled($derivee, $source, 0, 0, 0, 0, $l, $h, $largeur, $hauteur);

                imagejpeg($derivee, self::absoluDerivee($absolu, $suffixe), 82);

                imagedestroy($derivee);
            }

            imagedestroy($source);
        } catch (\Throwable) {
            // Image tronquée, mémoire insuffisante : l'original reste valable.
            return;
        }
    }

    /** Racine du dossier des médias, chemin absolu résolu. */
    public static function racine(): string
    {
        $dossier = (string) (Config::get('app')['medias'] ?? '');
        $reel    = realpath($dossier);

        if ($reel === false || !is_dir($reel)) {
            throw new \RuntimeException('Dossier des médias introuvable.');
        }

        return $reel;
    }

    /** Le chemin est-il de la forme qu'écrit `recevoir()` ? */
    public static function formeValide(string $relatif): bool
    {
        return (bool) preg_match(self::FORME, $relatif);
    }

    /**
     * Chemin absolu d'un fichier de la médiathèque, ou null s'il n'existe pas.
     *
     * La forme est vérifiée avant toute chose, et le chemin résolu est comparé
     * à la racine : un `fichier` altéré en base ne doit pas permettre de lire
     * — ni surtout de supprimer — hors de `medias/`.
     */
    public static function chemin(string $relatif): ?string
    {
        if (!self::formeValide($relatif)) {
            return null;
        }

        $absolu = realpath(self::racine() . '/' . $relatif);

        if ($absolu === false || !str_starts_with($absolu, self::racine() . '/') || !is_file($absolu)) {
            return null;
        }

        return $absolu;
    }

    /** Chemin relatif d'une taille dérivée. Déduit du nom, jamais stocké. */
    public static function relatifDerivee(string $relatif, string $suffixe): string
    {
        return preg_replace('/\.[^.]+$/', '', $relatif) . '-' . $suffixe . '.jpg';
    }

    /** Chemin relatif de la vignette (600 px). */
    public static function relatifVignette(string $relatif): string
    {
        return self::relatifDerivee($relatif, 'vignette');
    }

    /** Chemin relatif de la taille moyenne (1600 px), servie à la visionneuse. */
    public static function relatifMoyen(string $relatif): string
    {
        return self::relatifDerivee($relatif, 'moyen');
    }

    private static function absoluDerivee(string $absolu, string $suffixe): string
    {
        return preg_replace('/\.[^.]+$/', '', $absolu) . '-' . $suffixe . '.jpg';
    }

    /**
     * Supprime le fichier et toutes ses tailles dérivées.
     *
     * La liste est parcourue depuis `DERIVEES` : ajouter une taille demain
     * n'oubliera pas de la nettoyer — un dérivé orphelin resterait sur le
     * disque pour toujours, aucune ligne ne le désignant plus.
     */
    public static function supprimer(string $relatif): void
    {
        $cibles = [$relatif];

        foreach (array_keys(self::DERIVEES) as $suffixe) {
            $cibles[] = self::relatifDerivee($relatif, $suffixe);
        }

        foreach ($cibles as $cible) {
            $absolu = self::chemin($cible);
            if ($absolu !== null) {
                unlink($absolu);
            }
        }
    }

    /**
     * Plus petite des deux limites du serveur.
     *
     * `upload_max_filesize` borne un fichier, `post_max_size` borne l'envoi
     * entier. Annoncer la première quand c'est la seconde qui bloque envoie
     * l'éditeur redimensionner des images pour rien.
     */
    public static function limiteServeur(): int
    {
        return min(self::octetsIni('upload_max_filesize'), self::octetsIni('post_max_size'));
    }

    /**
     * L'envoi a-t-il été tronqué par `post_max_size` ?
     *
     * PHP vide alors `$_POST` et `$_FILES` sans le dire. Le jeton CSRF part
     * avec le reste : la vérification du jeton doit venir APRÈS ce test, sinon
     * un envoi trop lourd s'annonce comme une session expirée.
     */
    public static function envoiTronque(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
            && $_POST === []
            && $_FILES === []
            && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0;
    }

    /** « 2,4 Mo ». Le poids d'un fichier se lit, il ne se compte pas en octets. */
    public static function poids(int $octets): string
    {
        if ($octets < 1024) {
            return $octets . ' o';
        }
        if ($octets < 1024 * 1024) {
            return str_replace('.', ',', (string) round($octets / 1024)) . ' Ko';
        }

        // Un décimal, sauf quand il vaut zéro : « 8 Mo » et non « 8,0 Mo ».
        $mo = number_format($octets / 1048576, 1, ',', '');

        return str_replace(',0', '', $mo) . ' Mo';
    }

    /** Traduction des codes d'erreur de PHP en langue d'éditeur. */
    private static function messageCode(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => sprintf(
                'Fichier trop lourd : le serveur n\'accepte pas plus de %s par fichier.',
                self::poids(self::limiteServeur())
            ),
            UPLOAD_ERR_PARTIAL    => "L'envoi a été interrompu avant la fin. Réessayez.",
            UPLOAD_ERR_NO_FILE    => 'Aucun fichier sélectionné.',
            UPLOAD_ERR_NO_TMP_DIR => "Le serveur n'a pas de dossier temporaire configuré.",
            UPLOAD_ERR_CANT_WRITE => "Le serveur n'a pas pu écrire le fichier sur le disque.",
            UPLOAD_ERR_EXTENSION  => "Une extension PHP a bloqué l'envoi.",
            default               => "L'envoi a échoué (code $code).",
        };
    }

    /** « 512M » -> 536870912. */
    private static function octetsIni(string $directive): int
    {
        $valeur = trim((string) ini_get($directive));

        if ($valeur === '') {
            return PHP_INT_MAX;
        }

        $nombre = (int) $valeur;

        return match (strtolower(substr($valeur, -1))) {
            'g'     => $nombre * 1024 * 1024 * 1024,
            'm'     => $nombre * 1024 * 1024,
            'k'     => $nombre * 1024,
            default => $nombre,
        };
    }
}
