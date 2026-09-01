# Philippe Grégoire Yacé — *Une destinée* (1920-1998)

Site éditorial de l'ouvrage. Trois pages publiques livrées, et le back-office en
cours de construction : **lots A à D1 posés, plus une part du lot E** — ossature de `/cmsadmin/`,
authentification, gestion des actualités, événements et repères, et modération
des témoignages.

---

## 1. Direction artistique

### Palette

Le logotype est **strictement monochrome** (un seul gris, `#4D4D4D`). Il fournit donc
l'axe neutre de la palette, mais aucune couleur d'accent. Le **laiton patiné** a été
choisi et validé pour ce rôle : registre commémoratif — dorure à chaud sur reliure,
médaille, plaque gravée — qui répond à la stature du sujet sans jamais concurrencer
le gris du logo.

| Rôle | Jeton | Valeur | Part |
|---|---|---|---|
| Papier | `--paper` | `#F7F4EE` | ~60 % |
| Papier en retrait | `--paper-sunk` | `#EFEAE0` | — |
| Encre | `--ink` | `#262523` | ~30 % |
| Gris du logo | `--ink-2` | `#4D4D4D` | rappel identitaire |
| Laiton — décor | `--brass` | `#A88B5C` | ~10 % |
| Laiton — texte | `--brass-text` | `#7D6134` | ~10 % |

**Deux valeurs de laiton, délibérément.** `--brass` (2,94:1 sur papier) ne porte que des
filets et des traits ; il ne doit **jamais** écrire du texte. `--brass-text` (5,27:1)
est la seule variante autorisée pour les liens, capitales d'attaque et chiffres de
section. Confondre les deux casse l'accessibilité — c'est arrivé une fois en cours de
construction, sur `.section-num`.

Aucune couleur n'est employée brute en grande surface : le papier est un blanc chaud
et non `#FFF`, l'encre un noir chaud et non `#000`.

### Typographie

- **Bodoni Moda** (display) — contraste vertical élevé, empattements filiformes.
  Registre monumental. Réservé aux grandes tailles : sous 24 px son contraste casse.
- **Jost** (interface et corps de texte) — géométrique, écho direct du logotype :
  mêmes coupes franches, même construction circulaire.

L'interlignage des titres ne descend pas sous ~1,06 : le français capitalise des
lettres accentuées (É, À, Ê) et un interlignage plus serré mutile l'accent.

### Mouvement

Courbes lentes en sortie, aucun ressort, aucun dépassement — ce sont des marqueurs de
template. Une seule animation appuyée : la révélation par masque des titres du hero.
`prefers-reduced-motion` est respecté partout.

### Favicon

Le logotype fait `PGY | PHILIPPE GRÉGOIRE / YACÉ`. À 16 px, trois lettres
deviennent une tache : le favicon garde donc la seule initiale du patronyme,
la plus tenue géométriquement des trois. Registre : **la lettre gravée dans une
plaque de laiton**, prolongement direct de la palette — médaille, dorure à
chaud, plaque commémorative.

**Le laiton porte la tuile, pas la lettre**, et c'est mesuré. Une tuile en encre
profonde donnait 13,8:1 contre un bandeau d'onglets clair mais 1,5:1 contre un
bandeau sombre : invisible en thème sombre. Le laiton `#A88B5C`, ton moyen,
garde 2,6:1 sur l'un et 3,5:1 sur l'autre. L'encre sur ce laiton donne 5,2:1,
la lettre reste franche à 16 px.

Le Y est tracé en géométrie pure, jamais en texte : un favicon ne dispose
d'aucune police chargée. Traits d'épaisseur constante, terminaisons coupées
d'équerre — la Jost du logotype.

| Fichier | Rôle |
|---|---|
| `assets/img/favicon.svg` | source vectorielle, servie aux navigateurs modernes |
| `assets/img/favicon-32.png` | repli matriciel |
| `assets/img/apple-touch-icon.svg` | source de la variante iOS : sans arrondi (iOS applique son propre masque) et Y plus rentré |
| `assets/img/apple-touch-icon.png` | 180 × 180, écran d'accueil iOS |
| `favicon.ico` | à la racine, pour la requête automatique vers `/favicon.ico` ; 16/32/48 empaquetées en PNG |

Le même jeu sert au site public et au back-office : une seule identité, un seul
jeu de fichiers. Aucun rasteriseur n'étant installé sur la machine, les PNG sont
rendus par Chrome à partir du SVG et l'`.ico` est empaqueté par un script Python
de vingt lignes — le format accepte des PNG tels quels depuis Vista.

---

## 2. Fichiers

Le site occupe la racine : MAMP y pointe directement
(`DocumentRoot "…/projetsmamp/livreyace"`). PHP 8.3, MySQL 8, base `livreyace_sbd`.

```
livreyace/                  ← racine web
├── .htaccess               réécriture vers le contrôleur frontal
├── index.php               contrôleur frontal public : routes du site
├── assets/                 css, js, visuels du thème        [public]
├── medias/                 fichiers téléversés               [public]
├── cmsadmin/                                                 [public]
│   ├── .htaccess           réécriture propre au back-office
│   ├── index.php           contrôleur frontal du back-office
│   └── assets/             thème d'administration élagué
├── bin/                    scripts en ligne de commande      [interdit]
├── config/                 configuration                     [interdit]
├── src/
│   ├── bootstrap.php       autoload + régime d'erreurs       [interdit]
│   ├── Core/               Config, Database, Router, View,
│   │                       Admin, Session, Csrf, Auth,
│   │                       Validator                         [interdit]
│   │                       Slug                             [interdit]
│   ├── Controller/Admin/   Auth, Crud (Actualite, Evenement,
│   │                       Repere), Temoignage, Parametre  [interdit]
│   └── Model/              Modele, Actualite, Evenement,
│                           Repere, Temoignage, Parametre,
│                           Utilisateur, TentativeConnexion   [interdit]
├── templates/
│   ├── layout.php          mise en page du site public       [interdit]
│   ├── partials/           navigation, pied                  [interdit]
│   ├── pages/              corps des pages publiques         [interdit]
│   └── admin/              mise en page, partials et pages
│                           du back-office                    [interdit]
├── sql/                    migrations                        [interdit]
└── reference/              ancien site Abidjan.net           [interdit]
```

Seuls `cmsadmin/index.php` et `cmsadmin/assets/` sont servis. Toute la logique
et tous les gabarits de l'admin vivent dans `src/` et `templates/`, déjà
verrouillés en 403 — le dossier public de l'admin ne contient aucun code
métier.

### Socle applicatif

PHP structuré à la main : contrôleur frontal, routeur à motifs, PDO, gabarits.
Pas de framework — le choix a été fait d'assumer l'écriture du back-office plutôt
que d'imposer une racine web en `public/`.

Deux protections se recouvrent volontairement : une règle `RewriteRule … [F,L]`
dans le `.htaccess` racine, **et** un `.htaccess` par dossier applicatif. Si
`mod_rewrite` venait à manquer sur l'hébergement, les identifiants de base ne
fuiteraient pas pour autant. Vérifié : `config/config.php` répond 403 et sa réponse
ne contient aucune occurrence du mot de passe.

`config/config.php` porte les valeurs MAMP locales. Pour la production, créer
`config/config.local.php` (ignoré par git) retournant un tableau partiel : il est
fusionné par-dessus.

### Authentification et formulaires

**Comptes.** Créés en ligne de commande, jamais par une page web : une page
d'installation qui crée le premier administrateur est ouverte par définition, et
il suffit de l'oublier en ligne pour offrir le site.

```
php bin/compte.php creer <adresse> <nom> [admin|editeur]
php bin/compte.php motdepasse <adresse>
php bin/compte.php lister
```

Le mot de passe est demandé sans écho, jamais passé en argument — il serait
lisible dans l'historique du shell et dans la liste des processus. Douze
caractères minimum, `password_hash()` en bcrypt, coût 12.

**Session.** Nom et chemin de cookie propres au back-office (`pgyadmin`,
`/cmsadmin`) : le cookie d'administration ne part donc jamais avec une requête
vers une page publique. `HttpOnly`, `SameSite=Lax`, `Secure` dès que le site est
en HTTPS. `use_strict_mode` est forcé à 1 — sans lui, un identifiant de session
choisi par un tiers est accepté, ce qui suffit à une fixation de session.
L'identifiant est régénéré à la connexion. Deux bornes d'expiration : 2 h
d'inactivité, 12 h en absolu.

**CSRF.** Un jeton par session et non par formulaire : deux onglets ouverts sur
deux formulaires doivent pouvoir être soumis dans n'importe quel ordre.
Comparaison par `hash_equals`. Le jeton est renouvelé à la connexion, en même
temps que l'identifiant de session. Un POST sans jeton valable répond 419. La
déconnexion elle-même passe par POST : en GET, une balise `<img>` sur un site
tiers suffirait à déconnecter l'éditeur au passage.

**Garde.** Posée une fois dans `cmsadmin/index.php`, avant le routage, et par
liste blanche : seuls `/connexion` et `/deconnexion` sont ouverts, tout le reste
exige une session. Une route ajoutée plus tard et oubliée est donc protégée par
défaut — l'inverse, une liste de routes à protéger, fait qu'un oubli ouvre une
page.

**Ce que la réponse ne dit pas.** Adresse inconnue, mot de passe faux et compte
désactivé donnent le même message. Et la même durée : quand l'adresse n'existe
pas, `password_verify` est quand même exécuté contre une empreinte leurre, sans
quoi la réponse arriverait en 0,2 ms au lieu de 217 ms et l'écart suffirait à
dresser la liste des comptes. Le leurre est un vrai hachage au coût courant ;
une chaîne inventée serait rejetée comme malformée et rouvrirait l'écart.

**Essais successifs.** Table `tentative_connexion`, fenêtre glissante de
15 minutes : 5 échecs sur une même adresse, 15 depuis une même IP. Deux plafonds
parce qu'ils couvrent deux attaques — le forçage d'un compte visé, et le balayage
d'un dictionnaire d'adresses. Une connexion réussie remet le compteur à zéro,
sinon l'éditeur qui s'est trompé quatre fois resterait à un essai du blocage
pendant un quart d'heure. Le blocage est toujours temporaire : verrouiller pour
de bon offrirait à un tiers le moyen d'interdire l'accès à l'éditeur légitime en
échouant assez souvent. Seul `REMOTE_ADDR` est lu, jamais `X-Forwarded-For` —
cet en-tête vient du client et se falsifie.

### Les écrans de contenu

Trois entités — actualités, événements, repères — partagent une même mécanique :
`Modele` pour le SQL, `CrudController` pour le déroulé (lister, créer, modifier,
publier, supprimer). Ne varient que les champs, les règles et les libellés.

**Rien n'entre en base par accident.** `Modele::ASSIGNABLES` est une liste
blanche de colonnes : une clé glissée dans un POST qui n'y figure pas est
écartée avant la requête. Vérifié — un POST portant `id=999` et
`cree_le=1900-01-01` laisse la ligne intacte.

**Les règles qui portent sur le fond**, pas seulement sur la forme :

- un **repère publié doit être sourcé** — c'est le §6 du cahier des charges, pas
  une commodité technique. La liste affiche en tête le nombre d'entrées sans
  source ;
- une **actualité publiée doit porter une date** : la page publique classe par
  `publie_le`, une entrée sans date s'y rangerait n'importe où ;
- un article de **catégorie « presse » exige son organe** ;
- un **événement publié doit indiquer sa ville**, et sa fin ne peut pas précéder
  son début ;
- l'**année de classement d'un repère doit tomber dans la période choisie** :
  les bornes sont reprises des filtres de la frise publique, les laisser diverger
  ferait apparaître l'entrée sous un filtre où elle n'a rien à faire.

**Slugs.** Translittération explicite plutôt que `iconv('ASCII//TRANSLIT')`, qui
dépend de la locale du serveur : « Séance de dédicace à Abidjan » donne
`seance-de-dedicace-a-abidjan` partout. Le slug se décline en `-2` s'il est
pris, mais **pas** quand on réenregistre une fiche sans changer son titre — sinon
les URL déjà partagées casseraient à chaque modification.

**Suppression et publication passent par POST avec jeton**, jamais par un lien :
un lien se déclenche par un préchargement de navigateur ou une balise sur un
site tiers, et il n'y a pas de corbeille. La confirmation JavaScript ne protège
que d'un clic malheureux ; le garde-fou réel est côté serveur.

**DataTables** est chargé sur les listes seulement, avec ses libellés écrits en
français dans `js/listes.js` : le greffon va normalement chercher sa traduction
sur un CDN, ce que l'admin s'interdit.

### La file de modération

Les témoignages ne suivent pas la mécanique des contenus, et ne réutilisent donc
pas `CrudController` : on ne crée pas un témoignage depuis l'admin, on en reçoit
et on décide. Le verbe central est « publier » ou « refuser ».

Ils sont présentés **en cartes et non en tableau** : un témoignage se lit avant
d'être jugé, et une ligne tronquée n'est pas lisible. La décision se prend sous
le texte, au moment où on vient de le lire. Un filet de couleur en bord de carte
donne l'état sans qu'il faille lire chaque badge, et seules les actions qui
changent quelque chose sont proposées — un bouton sans effet est un bouton qu'on
finit par cliquer.

**Qui a décidé, et quand, est conservé** (`modere_le`, `modere_par`). Sur des
propos publiés au nom d'un tiers, la question « qui a validé ceci ? » doit avoir
une réponse.

**Ce que le formulaire de correction peut toucher se limite à trois champs** :
nom, qualité, texte. Le statut, l'adresse du signataire et la trace de
modération ne sont pas assignables — vérifié, un POST qui les porte les laisse
intacts. Une bannière rappelle que la correction porte sur la forme : ce sont
les mots de quelqu'un d'autre.

`auteur_email` et `ip_soumission` restent en base et ne sortent jamais en
public : la première sert à recontacter le signataire, la seconde à repérer un
abus.

### Fiche technique et tableau de bord

Les huit valeurs de la fiche technique de l'ouvrage (`parametre`) se saisissent
depuis l'admin : un changement de prix ou d'ISBN ne demande pas d'intervention
technique. `Parametre::FICHE_LIVRE` est la source unique — elle décrit les
champs, leur ordre et leur validation ; en ajouter un ne touche qu'un fichier.

**L'ISBN est vérifié sur sa clé de contrôle**, pas seulement sur sa longueur :
c'est le numéro qui sert à commander l'ouvrage, un chiffre mal recopié se paie
en commandes perdues. Un champ vidé redevient `NULL` et non chaîne vide — la
page publique masque la ligne au lieu de l'afficher en blanc.

Le tableau de bord lit désormais la base. **Ce qu'il met en avant n'est pas le
volume mais ce qui attend une décision** : témoignages à modérer, repères sans
source, fiche technique incomplète. Chaque ligne mène à l'écran concerné. Les
compteurs affichent le nombre publié, le total en dessous.

### `medias/` n'exécute rien

Le dossier reçoit des fichiers déposés par un utilisateur ; un fichier déposé ne
doit jamais être exécuté. `medias/.htaccess` retire les gestionnaires PHP, CGI et
SSI, coupe le moteur PHP, refuse les extensions sensibles et pose `nosniff`.

Vérifié : un `.php` déposé répond 403 ; un `.php.jpg` — la contournement
classique par double extension — sort en source brute, non interprétée ; une
vraie image est servie normalement. Le contrôle de type au téléversement
(lot D) reste la première barrière, celle-ci est la seconde et tient si la
première cède.

### La duplication du chrome est soldée

Navigation et pied de page vivaient en trois exemplaires dans les pages statiques.
Ils sont désormais dans `templates/partials/`, inclus une seule fois par
`templates/layout.php`. Vérifié sur les trois pages : un seul `<header>`, un seul
`<main>`, un seul `<footer>` par rendu.

### Base de données

Huit tables, `utf8mb4`, InnoDB — voir `sql/001_schema.sql` :
`utilisateur`, `actualite`, `evenement`, `temoignage`, `media`, `repere`,
`commande`, `parametre`. Plus `tentative_connexion` (`sql/002_auth.sql`), le
compteur glissant des essais de connexion.

Les migrations s'appliquent dans l'ordre de leur numéro ; il n'y a pas encore de
table de suivi, le projet en est à deux fichiers.

Deux partis pris qui tiennent au sujet : les témoignages arrivent en
`statut = 'en_attente'` et rien ne s'affiche sans passage explicite en `'publie'`
— il s'agit de propos sur une personne réelle. Et `repere.source` existe pour
imposer le sourçage des faits biographiques exigé au §6 du cahier des charges.

## 3. Bootstrap : ce qui est conservé, ce qui est démonté

**Conservé** — grille 12 colonnes, points de rupture, et le moteur JS du carrousel
(accessibilité clavier, autoplay, swipe, pause au survol).

**Démonté** — `base.css` efface le rendu natif de `.btn`, `.navbar`, `.card`,
`.form-control`, `.accordion`, des contrôles de carrousel et des `<button>` nus, avant
que `components.css` ne reconstruise tout. Le focus bleu système est remplacé par un
anneau laiton.

Le carrousel du hero illustre le principe : instance Bootstrap intacte, apparence
intégralement réécrite (indicateurs numérotés, jauge de progression, révélation par
masque). Aucune classe Bootstrap n'est visible à l'écran.

### Le thème d'administration

`cmsadmin/assets/` est un extrait de **Star Admin 2 Free** (BootstrapDash, MIT),
réduit de **56 Mo à 2,6 Mo**. Le template d'origine n'est pas dans le dépôt : il
vit dans `~/Documents/KP/Templates/staradmin-2-free`.

**Gardé** — `style.css`, le bundle jQuery 3.7.1 + Bootstrap 5.3.2 +
PerfectScrollbar, les icônes MDI (woff2 seul), DataTables et Chart.js pour les
lots à venir, et trois comportements d'interface fusionnés en un seul
`js/admin.js` : repli de la barre latérale, mode icônes, sortie mobile.

**Jeté** — les 12 pages de démonstration, le SCSS et la chaîne gulp, un second
thème complet (`css/light/`, 713 Ko), les polices Nunito et Roboto (6,2 Mo,
jamais référencées par le CSS), et neuf bibliothèques d'icônes ou de widgets
inutilisées, dont `flag-icon-css` à elle seule 6,5 Mo.

**Aucun appel sortant.** Quatre sources ont été coupées : l'`@import` vers
`fonts.googleapis.com` en tête de `style.css`, remplacé par sept `@font-face`
sur la Manrope livrée en local ; le bandeau « Buy Now » vers `bootstrapdash.com`
présent en haut de chaque page, balisage et 57 lignes de CSS ; le crédit du pied
de page ; et les avatars `via.placeholder.com`. Les `sourceMappingURL` et les
déclarations pointant sur des visuels que le template ne livre pas ont été
retirées aussi — elles produisaient des 404 silencieuses.

**La teinte.** Star Admin est compilé depuis SCSS : ses couleurs sont en dur,
pas en variables CSS. Une surcouche par redéfinition de variables était donc
impossible ; 399 valeurs hexadécimales ont été substituées directement dans
`style.css` vers les jetons du projet — indigo `#1F3BB3` → laiton `#7D6134`,
surface sombre → encre profonde, gris froids → filets chauds. Les couleurs de
statut (vert, rouge, ambre) sont **conservées** : dans un back-office elles
portent du sens, elles ne doivent pas se fondre dans la charte.

Le reste vit dans `css/pgy-admin.css`, chargé après : marque, en-tête de page,
badges de statut adossés aux énumérations du schéma, anneau de focus laiton, et
six corrections du thème. Trois de mise : `text-transform: capitalize` sur les
titres de carte (qui donnait « Livraison Du Back-Office », convention anglaise
fautive en français), le cyan `#05C3FB` des boutons secondaires, et une rangée
de compteurs qui ne se repliait pas sous 768 px. Trois relevées à la mesure, pas
à l'œil :

| Défaut du thème | Effet | Mesuré |
|---|---|---|
| `height: 2.75rem` groupé sur `.form-control` | un `<textarea rows="12">` s'affichait sur une seule ligne — le corps d'une actualité se saisissait dans une fente | hauteur calculée au navigateur |
| `color: #c9c8c8` sur les `select` | la valeur choisie était plus pâle que son libellé ; un « Brouillon » ressemblait à un champ vide | **1,60:1** → 14,69:1 |
| même gris sur les `::placeholder` | nos indications de saisie (« https://… », « 1959, v. 1945 ») étaient invisibles | **1,60:1** → 5,33:1 |

L'admin reste sur **Manrope**, servie localement. Bodoni et Jost sont la voix
publique du site ; les charger ici imposerait un appel à Google Fonts pour un
outil interne.

---

## 4. Accessibilité

Contrastes mesurés (WCAG AA = 4,5:1 pour le texte) :

| Paire | Ratio |
|---|---|
| Encre / papier | 13,95:1 |
| Gris du logo / papier | 7,70:1 |
| Texte secondaire / papier | 5,06:1 |
| Laiton texte / papier | 5,27:1 |
| Papier / fond sombre | 15,34:1 |
| Laiton clair / fond sombre | 8,34:1 |

Également : lien d'évitement, `:focus-visible` sur tous les interactifs, navigation
clavier, `prefers-reduced-motion`, alternatives textuelles.

---

## 5. À fournir avant mise en ligne

- **Logotype** — le verrou `.logo` est une reconstruction HTML approchée. Remplacer
  par le SVG officiel à trois endroits : `templates/partials/nav.php`,
  `templates/partials/footer.php`, et le monogramme `.pgy-monogramme` de la barre
  du back-office (`templates/admin/partials/navbar.php`).
  **Le favicon découle de cette reconstruction** : si le Y officiel a une autre
  géométrie, redessiner `assets/img/favicon.svg` et regénérer le jeu (voir §1).
- **Visuels** — les `.svg` de `assets/img/` sont des cadres d'attente générés. Chacun
  affiche la dimension de livraison attendue et son cadrage. Ces valeurs sont calculées
  sur la largeur réelle d'affichage en écran 2× ; le hero est en dérive `scale(1.1)`,
  d'où sa marge supplémentaire.

  | Fichier | Dimensions | Cadrage |
  |---|---|---|
  | `hero-1/2/3.svg` | **2000 × 2600 px** | portrait, sujet décentré à droite |
  | `couverture.svg` | **1200 × 1550 px** | ratio 240 × 310 mm |
  | `portrait.svg` | **1400 × 1750 px** | buste |
  | `auteur.svg` | **1000 × 1250 px** | portrait 4:5 |
  | `extrait-1/2.svg` | **1500 × 1000 px** | double page |
  | `gal-1.svg` | **1800 × 1350 px** | paysage 4:3 |
  | `gal-2.svg` | **1300 × 1730 px** | portrait 3:4 |
  | `gal-3.svg` | **1100 × 1100 px** | carré |
  | `gal-4.svg` | **2000 × 1125 px** | panoramique 16:9 |

  Les visuels du hero sont recadrés en `object-fit: cover` sur un panneau vertical :
  **prévoir le sujet dans la moitié droite**, la gauche étant recouverte par le voile
  papier. Livrer en JPEG qualité 80 après redimensionnement — les archives brutes sont
  souvent bien plus lourdes que nécessaire (CDC §5, performance).

  Traitement homogène des archives : le CSS applique déjà
  `grayscale(1) contrast(1.04) sepia(0.14)`, ce qui unifie des sources d'origines
  diverses sans retouche préalable (CDC §6).
- **Contenus** — tout le texte éditorial est balisé provisoire. **Les dates de la frise
  et les citations doivent être validées avant publication** : Yacé est une figure
  historique réelle, aucun propos ne doit lui être attribué sans source.
- **URL absolue** — `og:image` et `canonical` pointent sur `example.org`.

---

## 6. Dette connue

La duplication de la navigation et du pied de page — qui figurait ici — **est
soldée** depuis le passage aux gabarits (voir §2). Ce qu'il reste :

- **La limitation de débit ne couvre que la connexion.** Le jeton CSRF et la
  validation sont posés (voir §2) et servent déjà tout le back-office ; le
  comptage d'essais, lui, ne protège que `/cmsadmin/connexion`. Le formulaire de
  témoignage sera ouvert à tout le monde et portera des propos sur une personne
  réelle : il lui faut son propre plafond avant d'être exposé.
- **Aucun test.** Le socle a été vérifié à la main (codes HTTP, connexion PDO,
  absence de warning, mesure du rendu au navigateur). Ces vérifications ne sont
  pas rejouables automatiquement.
- **Pas d'éditeur enrichi ni de téléversement d'image sur les fiches.** Le corps
  d'une actualité se saisit en texte brut, une ligne vide séparant deux
  paragraphes. La colonne `image` existe en base mais aucun écran ne la remplit :
  elle attend la médiathèque du lot D.
- **Le CSS du thème d'administration porte des règles mortes.** Des composants
  que le back-office n'emploie pas (graphiques de démonstration, menu horizontal,
  panneau de réglages) gardent leurs styles dans `style.css`. Sans effet visible,
  mais quelques dizaines de kilo-octets pour rien ; à élaguer une fois les lots C
  à E écrits, quand on saura ce qui sert vraiment.
- **`reference/` pèse ~70 Mo dans l'arborescence servie.** Verrouillé en 403,
  mais toujours à exclure explicitement de la règle de déploiement.
- **L'historique git porte les images non optimisées de l'ancien site** (`.git`
  fait 58 Mo). Sans conséquence fonctionnelle ; à savoir si le dépôt part un jour
  sur un hébergement distant.

## 7. État et suite

### Où en est le projet

**Fait** — socle de design complet et documenté ; trois pages publiques (accueil
avec hero slider, Le livre, Biographie) rendues par le moteur de gabarits, plus une
page 404 dessinée dans la charte et servie par le routeur ; contrôleur frontal,
routeur, PDO, mise en page unique ; base `livreyace_sbd` avec ses huit tables ;
étanchéité des dossiers applicatifs vérifiée sur Apache.

**Non fait** — tout ce qui a besoin d'un back-office ou d'un formulaire.

### Le back-office, lot par lot

Le back-office est livré en cinq lots, validables l'un après l'autre. Les entrées
verrouillées de la barre latérale correspondent aux lots restants : la forme
finale de l'outil est visible dès le premier.

| Lot | Objet | État |
|---|---|---|
| **A** | Ossature — thème élagué, mise en page, barre latérale, routage `/cmsadmin/` | livré |
| **B** | Authentification — connexion, session, CSRF, validation, garde de route | livré |
| **C** | Contenus — actualités, événements, repères | livré |
| **D1** | Modération — file des témoignages | **livré** |
| **D2** | Médiathèque — téléversement avec contrôle de type réel, vignettes | à venir |
| **E1** | Pilotage — compteurs réels du tableau de bord, fiche technique de l'ouvrage | **livré** |
| **E2** | Comptes et commandes | à venir |

Ce que le **lot A** pose : `src/bootstrap.php` (amorçage partagé par les deux
contrôleurs frontaux), `src/Core/Admin.php` (préfixe d'URL déduit de
l'emplacement réel du dossier — renommer `cmsadmin` ne demande aucune retouche —
et arborescence du menu), `Router::introuvable()` pour que l'admin ait sa propre
404, et un quatrième argument à `View::render` choisissant la mise en page. Le
site public n'est pas touché : ses trois pages et sa 404 rendent à l'octet près
comme avant.

Le lot A ne lit ni n'écrit aucune donnée, donc aucune route n'est encore
protégée. La garde arrive au lot B, avec la session.

### Prochaines étapes, dans l'ordre

1. **Back-office** — c'est le gros morceau du choix « PHP à la main », et il
   débloque tout le reste. Découpé en cinq lots ci-dessus ; le lot A est livré.
   Restent l'authentification sur `utilisateur`
   (`password_hash`/`password_verify`, session régénérée à la connexion), le CRUD
   actualités / événements / repères, la file de modération des témoignages, et le
   téléversement dans `medias/` avec contrôle de type réel et non d'extension.
2. **Couche formulaire** — jeton CSRF, validation, limitation de débit. Le CSRF et
   la validation sont avancés au **lot B** : la page de connexion est elle-même un
   formulaire, elle ne peut pas les précéder. Ne reste au titre du §6 que la
   limitation de débit sur les formulaires publics.
3. **Pages publiques adossées aux données** — actualités et détail, galerie avec
   visionneuse, événements, contact. Rapides une fois 1 et 2 posés : le système
   de composants existe déjà.
4. **Commande** — le site de référence s'appuyait sur un backend de paiement
   séparé (`carte.abidjan.net` : mobile money, Visa, Wave, Apaym). Décider s'il
   est réutilisé ou remplacé **avant** d'écrire le tunnel.
5. **Phase 3 du CDC** — newsletter, recherche interne, multilinguisme.

### Ce qui bloque, et sur qui

Les contenus. Tout le texte éditorial du site est provisoire et balisé comme tel,
et **aucune ligne ne peut être publiée sans validation de l'éditeur** — Yacé est
une figure historique réelle. Voir §5 pour la liste complète des contenus et
visuels attendus, dimensions comprises.

## 8. Couverture du cahier des charges

Accueil (§4.1) — **complet** : hero slider, accroche, aperçu du livre, teaser
biographie, frise de repères, témoignages, actualités, CTA commande.

Le livre (§4.2) — **complet** : résumé long, mot de l'éditeur, fiche technique,
sommaire, extrait, feuilletage, où acheter. L'auteur (§4.3) y est traité en section
plutôt qu'en page dédiée, faute de matière ; à détacher dès que le contenu existe.

Biographie (§4.4) — **complet** : contexte historique, biographie structurée en cinq
chapitres avec sommaire latéral collé, frise chronologique filtrable par période et
dépliable, citations, galerie de portraits.

Reste à construire — Héritage (§4.5), Galerie/Archives (§4.6), Actualités/Presse
(§4.7), Témoignages (§4.8), Boutique (§4.9), Événements (§4.10), Contact (§4.11),
Mentions légales (§4.12).

Transverses (§5) — **partiel** : responsive, accessibilité AA vérifiée par mesure,
structure sémantique, métadonnées, schema.org `Book`/`Person`, lazy loading sont en
place. Newsletter, recherche interne, multilinguisme et partage social restent à
faire — ils sont désormais réalisables, le socle dynamique étant en place.

### Le socle applicatif : décision prise

Le CDC exige un back-office utilisable sans intervention technique, des formulaires
avec modération, des commandes et une recherche interne — rien de tout cela ne
tenait en pages statiques.

**Choix retenu : PHP structuré à la main** (contrôleur frontal, routeur, PDO,
gabarits), plutôt qu'un framework ou un CMS. Motif : garder la racine web sur
`livreyace/` — Symfony et Laravel imposent une racine en `public/` — et rester dans
la continuité du site de référence. Contrepartie assumée : le back-office est à
écrire intégralement, et c'est l'essentiel de la charge restante.
