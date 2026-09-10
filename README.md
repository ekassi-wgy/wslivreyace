# Philippe Grégoire Yacé — *Une destinée* (1920-1998)

**Fonds patrimonial numérique** consacré à Philippe Grégoire Yacé, adossé à la
sortie de l'ouvrage *Une destinée*. Le site a été construit comme la campagne
d'un livre ; le brief du 7 septembre 2026 en a fait un fonds destiné à
s'enrichir pendant des années. Ce brief, ce qu'il déplace et sa feuille de route
sont au **§9**, qui fait foi.

**Le cahier des charges d'origine est entièrement livré**, à une exception près :
le tunnel de commande — **qui existe désormais** (lot G3). **Les treize lots du
nouveau périmètre sont écrits.**

| | |
|---|---|
| **Back-office** | seize écrans : tableau de bord, actualités, événements, **biographie par périodes**, repères, **archives**, **héritage**, médiathèque cherchée et paginée, **traductions**, **zones de livraison**, modération des témoignages, **contributions du public**, messages, commandes, paramètres, comptes |
| **Site public** | accueil, Le livre, **auteur**, **Commander**, Biographie (**index et périodes**), **Archives** (fonds, catégorie, notice, **bibliothèque des discours**), **Héritage** (index et sujets), Actualités (liste, fiche, revue de presse), Événements, Témoignages, **Contribuez aux archives**, **Recherche**, Contact, Mentions légales, 404 |
| **Socle** | **bilingue et ouvert** — `/en/` sert l'intégralité du site, interface traduite et contenus traduisibles depuis le back-office —, plan du site et `robots.txt`, fil d'Ariane et données structurées partout, quarantaine des envois publics |

**La boutique est livrée et fermée** (G3) : les commandes se prennent en
**paiement à la livraison**, avec des frais gérables par pays, ville et
commune, et s'ouvriront d'une case à cocher le jour où l'ouvrage aura une date.
Le paiement en ligne est une phase 2 — la structure l'attend, aucune migration
ne sera nécessaire.

**La version anglaise (G11) est livrée et ouverte** depuis le 8 septembre 2026 :
`/en/` répond, l'interface est en anglais, et les contenus s'y traduisent depuis
le back-office — un champ non traduit affiche le français plutôt que rien.

⚠️ **Le dépôt est en avance sur le serveur, mais l'écart s'est réduit de
moitié le 8 septembre 2026 : les quinze migrations sont jouées en production.**
Le code, lui, n'est pas envoyé — c'est désormais tout ce qui sépare le serveur
du dépôt. Voir « Ce qui est en ligne » au §7.

---

## 1. Direction artistique

### Palette

Le logotype est **strictement monochrome** : le fichier de marque officiel porte un
seul gris, `#595959`. Il fournit donc l'axe neutre de la palette, mais aucune couleur
d'accent. Le **laiton patiné** a été choisi et validé pour ce rôle : registre
commémoratif — dorure à chaud sur reliure, médaille, plaque gravée — qui répond à la
stature du sujet sans jamais concurrencer le gris du logo.

**Le gris officiel n'écrit jamais.** `--ink-2` vaut `#4D4D4D` et non `#595959`, et
l'écart est mesuré : le gris de marque tombe à 6,38:1 sur le papier, en dessous du
AAA que ce ton tient pour le corps de texte (7,70:1). Le logo, lui, ne porte aucune
couleur en dur — il est servi en `currentColor` et prend l'encre de son contexte,
l'encre dans l'en-tête, le papier dans le pied.

| Rôle | Jeton | Valeur | Part |
|---|---|---|---|
| Papier | `--paper` | `#F7F4EE` | ~60 % |
| Papier en retrait | `--paper-sunk` | `#EFEAE0` | — |
| Encre | `--ink` | `#262523` | ~30 % |
| Gris de rappel | `--ink-2` | `#4D4D4D` | rappel identitaire |
| Gris du fichier de marque | — | `#595959` | référence, jamais employée telle quelle |
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
  mêmes coupes franches, même construction circulaire. Le pari a tenu : le
  logotype officiel, livré après coup, porte lui aussi une géométrique à
  terminaisons droites. Le système typographique n'a eu à bouger d'aucun cran.

L'interlignage des titres ne descend pas sous ~1,06 : le français capitalise des
lettres accentuées (É, À, Ê) et un interlignage plus serré mutile l'accent.

### Mouvement

Courbes lentes en sortie, aucun ressort, aucun dépassement — ce sont des marqueurs de
template. Une seule animation appuyée : la révélation par masque des titres du hero.
`prefers-reduced-motion` est respecté partout.

### Favicon

Le logotype officiel est un **portrait au trait suivi du nom**. À 16 px, le visage
se remplit et devient une tache — vérifié au rendu. Le favicon garde donc la seule
initiale du patronyme, et depuis la livraison du logo c'est le **Y officiel**,
découpé dans le mot YACÉ du fichier de marque, et non plus une lettre redessinée.
Registre inchangé : **la lettre gravée dans une plaque de laiton**, prolongement
direct de la palette — médaille, dorure à chaud, plaque commémorative.

**Le laiton porte la tuile, pas la lettre**, et c'est mesuré. Une tuile en encre
profonde donnait 13,8:1 contre un bandeau d'onglets clair mais 1,5:1 contre un
bandeau sombre : invisible en thème sombre. Le laiton `#A88B5C`, ton moyen,
garde 2,6:1 sur l'un et 3,5:1 sur l'autre. L'encre sur ce laiton donne 5,2:1,
la lettre reste franche à 16 px.

Le Y vient du fichier de marque, jamais d'une police : un favicon n'en a aucune
de chargée. Il est **posé par transformation plutôt que réécrit** — boîte d'origine
`1185 1025 411 408`, centrée et mise à l'échelle — de sorte que le tracé reste au
caractère près celui du logo livré. La même lettre tient la tuile de marque du
back-office, où le portrait ne passait pas à 36 px.

| Fichier | Rôle |
|---|---|
| `assets/img/favicon.svg` | source vectorielle, servie aux navigateurs modernes |
| `assets/img/favicon-32.png` | repli matriciel |
| `assets/img/apple-touch-icon.svg` | source de la variante iOS : sans arrondi (iOS applique son propre masque) et Y plus rentré |
| `assets/img/apple-touch-icon.png` | 180 × 180, écran d'accueil iOS |
| `favicon.ico` | à la racine, pour la requête automatique vers `/favicon.ico` ; 16/32/48 empaquetées en PNG |
| `assets/img/logo.svg` | logotype officiel, recadré sur la boîte réelle du dessin et passé en `currentColor` ; source du `<symbol>` servi aux pages |
| `assets/img/og-image.svg` → `.jpg` | 1200 × 630, aperçu de partage social. Typographique à dessein : un placard composé de texte se distingue au premier coup d'œil d'une photographie livrée, ce qui évite qu'il reste en place par inadvertance |

Le même jeu sert au site public et au back-office : une seule identité, un seul
jeu de fichiers. Aucun rasteriseur n'étant installé sur la machine, les PNG sont
rendus par Chrome à partir du SVG et l'`.ico` est empaqueté par un script Python
de vingt lignes — le format accepte des PNG tels quels depuis Vista.

---

## 2. Fichiers

Le site occupe la racine : MAMP y pointe directement
(`DocumentRoot "…/projetsmamp/livreyace"`). PHP 8.3, MySQL 8, base `livreyace_sbd`.

**Prérequis serveur : PHP 8.1 minimum.** Le type de retour `never` (cinq
méthodes des contrôleurs d'admin) est apparu en 8.1 ; `str_starts_with` et
`match` exigent 8.0. En dessous, le site public tourne mais le back-office tombe
en erreur fatale dès la page de connexion — l'autoload étant paresseux, les
fichiers fautifs ne se chargent qu'à ce moment-là. PHP 8.3 est recommandé :
c'est la version de développement, et 8.0 n'est plus maintenu depuis
novembre 2023.

**Collation : deux valeurs, et c'est délibéré.** Les fichiers de `sql/` déclarent
tous `utf8mb4_unicode_ci` — c'est la version qui part en ligne, et elle se charge
aussi bien sur MySQL 5.7, MySQL 8 que sur **MariaDB**. La base de développement,
créée sous MySQL 8, garde `utf8mb4_0900_ai_ci` : rien ne demande de la refaire.

L'écart est réel mais sans conséquence ici : les deux collations sont
insensibles à la casse et aux accents, elles ne diffèrent que par la version
d'Unicode qui les fonde (9.0.0 contre 4.0.0), sur des caractères que le français
n'emploie pas. Ce qu'il ne faut pas faire, en revanche, c'est écrire du code qui
dépende d'un ordre de tri à la lettre près : il pourrait différer entre les deux
machines. Aucun classement du projet n'est dans ce cas — les listes se trient sur
des dates, des identifiants ou des rangs numériques.

`utf8mb4_0900_ai_ci` reste donc admis **en local**, et interdit dans un fichier
destiné au serveur : un hébergement mutualisé sous MariaDB refuse le fichier
entier, pas seulement la ligne fautive.

**Ce n'est plus une précaution, c'est une mesure.** Le 8 septembre 2026, le
serveur a répondu **MariaDB 10.5.26** quand le poste de développement tourne
sous MySQL 8.0.40. Deux moteurs, deux comportements — MariaDB conserve par
exemple les largeurs d'affichage (`int(10) unsigned` là où MySQL 8 écrit `int
unsigned`), ce qui suffit à rendre bruyant un `diff` de schéma entre les deux.
**Conséquence pour qui vérifie un fichier SQL : un essai local ne prouve rien
pour la production.** Il faut relire ce que MariaDB 10.5 ne sait pas faire —
colonnes générées, index fonctionnels, collations `_0900_` — et, pour les
longueurs de clé, s'appuyer sur ce que le serveur porte déjà : `uk_media_fichier`
sur `varchar(255)` en utf8mb4 fait 1020 octets, ce qui établit à lui seul que
la vieille limite de 767 n'y est pas en vigueur.

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
│   │                       Validator, Slug, Site, DateLisible,
│   │                       Langue, Lexique, Traduction,
│   │                       Boutique, Televersement, Paiement,
│   │                       Debit                             [interdit]
│   ├── helpers.php         t(), t_brut(), t_nu()             [interdit]
│   ├── lang/               fr.php, en.php — les textes de
│   │                       l'interface, versionnés           [interdit]
│   ├── Controller/         Actualite, Archive, Commande,
│   │                       Contact, Evenement, Temoignage,
│   │                       Contribution, Recherche (public)  [interdit]
│   ├── Controller/Admin/   Auth, Crud (Actualite, Evenement,
│   │                       Repere, Archive, Heritage,
│   │                       Periode), Temoignage, Message,
│   │                       Media, Commande, Compte,
│   │                       Contribution, Traduction, Zone,
│   │                       Parametre                         [interdit]
│   └── Model/              Modele, Actualite, Evenement,
│                           Repere, Temoignage, Message,
│                           Media, Commande, Zone, Parametre,
│                           Archive, Heritage, Periode,
│                           Contribution,
│                           Utilisateur, TentativeConnexion    [interdit]
├── templates/
│   ├── layout.php          mise en page du site public       [interdit]
│   ├── partials/           navigation, pied, symbole du logo [interdit]
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

**`config/config.php` ne porte aucun identifiant.** Il voyage à chaque envoi —
il contient les coordonnées publiques, les points de vente, la chaîne WhatsApp —
et tout ce qu'on y laisserait d'un environnement écraserait celui du serveur.
C'était le cas jusqu'au lot G13 : il fallait retaper les identifiants en ligne
après chaque mise en ligne, le site en erreur pendant ce temps.

Ce qui dépend de la machine — base de données, `debug`, `url` — vit dans
`config/config.local.php`, ignoré par git et **jamais envoyé**. Il existe sur le
poste comme sur le serveur, chacun avec ses valeurs, et se crée une fois à
partir de `config/config.local.exemple.php`, qui est versionné et documente ce
qu'il faut renseigner.

Les valeurs par défaut sont sûres : `db` est à `null` et `debug` à `false`. Une
configuration absente ne se rabat donc plus sur MAMP en silence — `src/bootstrap.php`
le vérifie avant toute chose et rend une page de maintenance en **503** plutôt
qu'une demi-page ou une trace d'erreur.

### URL absolues

`canonical` et `og:image` ne peuvent pas être relatifs : un chemin y est ignoré
par les moteurs comme par les aperçus de partage. `App\Core\Site` les
construit à partir de `app.url`, posée en configuration.

**Jamais depuis `HTTP_HOST`**, sauf repli explicite de développement : cet
en-tête est fourni par le client, et un `Host:` forgé ferait pointer le
canonical d'une page vers un domaine tiers — exactement ce qu'un canonical est
censé empêcher. Vérifié : configuration posée, une requête portant un `Host`
falsifié rend malgré tout la bonne adresse.

La chaîne de requête est retirée du canonical : deux adresses qui ne diffèrent
que par un paramètre de suivi désignent la même page. C'est ce qui fait que les
vues filtrées des actualités — `/actualites?categorie=dedicace` — désignent
toutes `/actualites` : la ressource est la même, seul le point de vue change.

**L'image de partage se choisit par page.** Le placard typographique reste le
défaut du site ; une actualité illustrée passe la sienne, avec les dimensions
que la médiathèque connaît. Elles ne sont écrites que si on les connaît :
annoncer 1200 × 630 pour une image d'archive qui n'y ressemble pas donne un
aperçu rogné de travers, ce qui est pire que pas de dimensions du tout.

Le back-office n'en porte pas — il est en `noindex`.

### Authentification et formulaires

**Comptes.** Le **premier** administrateur se crée en ligne de commande, jamais
par une page web : une page d'installation qui crée le premier compte est
ouverte par définition, et il suffit de l'oublier en ligne pour offrir le site.
La ligne de commande reste aussi la porte de secours quand plus personne ne peut
se connecter. Les comptes suivants se gèrent depuis l'écran du lot E2 (voir
« Les comptes » plus bas).

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

### Le logotype, servi une fois pour deux emplacements

Le logo officiel est un portrait au trait suivi du nom : **15 Ko de tracés**, dont
12,5 pour le seul visage. Il paraît deux fois par page, en-tête et pied.

Il est donc défini une fois en `<symbol>` (`templates/partials/logo-symbole.php`,
inclus par la mise en page) et rappelé deux fois par `<use href="#pgy-logo">` :
un seul dessin, deux instances. Vérifié sur la page servie — un `id="pgy-logo"`,
deux `<use>`, vingt-et-un tracés et non quarante-deux.

**Pourquoi pas `<img src="logo.svg">`**, qui serait mis en cache par le
navigateur : une image externe n'hérite pas de la couleur du texte. Le logo doit
passer de l'encre de l'en-tête au papier du pied ; en `<img>`, il faudrait deux
fichiers de 17 Ko à garder d'équerre.

**`fill` est porté par le `<symbol>`**, pas par chaque tracé. C'est une propriété
héritée : dans l'arbre d'ombre d'un `<use>`, elle se résout contre la couleur du
contexte appelant. L'oublier laisse le logo en noir par défaut — invisible sur le
pied sombre, ce qui est exactement arrivé au premier essai.

Trois retouches ont été faites au fichier livré, aucune sur le dessin :
le **cadre** (le `viewBox` de 3000 × 2000 ne contenait le dessin que dans sa bande
centrale ; recadré sur la boîte réelle, `193 530 2613 941`, mesurée au navigateur),
la **couleur** (`fill:#595959` dans un bloc `<style>` avec une classe `.cls-1` →
`fill="currentColor"` sur la racine), et les **identifiants** (`id="Calque_1"`
retiré — deux exemplaires par page, deux `id` identiques se télescopent).

**Le rapport a changé la barre.** Le verrou reconstruit était un bloc typographique
de 6,4:1 ; le logo officiel fait 2,78:1. À hauteur égale il serait trois fois moins
large, et illisible. Il est donc posé à 48 px de haut dans l'en-tête (38 px une
fois la barre réduite au défilement) et 64 px dans le pied. À 38 px, la ligne
« PHILIPPE GRÉGOIRE » n'est plus lisible mais le nom et le portrait le restent —
c'est le plancher acceptable, mesuré au rendu.

### Limitation de débit des formulaires publics

Le comptage d'essais du lot B ne protège que la connexion. Les formulaires
ouverts à tout le monde — témoignage, contact — ont désormais le leur,
`App\Core\Debit`, posé **avant** qu'aucun ne soit exposé.

**Deux compteurs, et non un seul, parce que ce n'est pas la même unité de
compte.** À la connexion, on compte les échecs, et une réussite remet le
compteur à zéro : l'éditeur qui se trompe quatre fois avant d'entrer ne doit pas
rester à un essai du blocage pendant un quart d'heure. Sur un formulaire public,
on compte les soumissions, réussies comprises — ce qu'on borne, c'est le nombre
de témoignages qu'un même visiteur dépose dans l'heure. Les fusionner aurait
demandé un drapeau à chaque appel pour servir un seul des deux appelants.

**Toute soumission compte, valide ou non.** Ne compter que celles qui passent la
validation rendrait le contournement gratuit : il suffirait d'envoyer des
formulaires fautifs. D'où un plafond assez haut pour absorber les fautes de
saisie — cinq par heure et par action, réglés dans `Debit::BAREMES`, en liste
blanche : une action non déclarée lève une exception au lieu de passer sans
plafond.

**La clé est l'IP, lue dans `REMOTE_ADDR` seul**, jamais `X-Forwarded-For` —
cet en-tête vient du client, et le lire ici permettrait de repartir de zéro à
chaque envoi en changeant une chaîne de caractères. Contrepartie assumée : une
adresse partagée partage son plafond ; l'alternative, un compteur en session, se
remet à zéro en vidant ses cookies.

**Ce que ce n'est pas.** Un filtre anti-spam : un robot qui reste sous le plafond
passe. Le piège à robots — champ leurre, délai minimal de remplissage —
s'écrira avec le formulaire qu'il protège, pas avant.

Vérifié : plafond atteint au sixième envoi, cloisonnement par action et par
adresse (IPv4 et IPv6), fenêtre qui glisse, attente annoncée exacte à la minute,
action inconnue rejetée, purge d'un jour à l'écriture.

### La page des témoignages, et le premier écrit public

Première page publique adossée aux données, et premier formulaire ouvert à tout
le monde (CDC §4.8). Elle boucle une chaîne écrite en trois fois : le visiteur
dépose, la file de modération (lot D1) décide, la page affiche.

**La session n'est ouverte que sur ces deux routes**, `GET` et `POST
/temoignages`. Un site qui pose un cookie à chaque visiteur, pour un formulaire
que la plupart ne rencontreront jamais, s'impose une bannière de consentement
pour rien. Vérifié : l'accueil ne dépose aucun cookie, la page des témoignages
en dépose un.

**Quatre barrières, dans cet ordre.** Le jeton CSRF d'abord — un POST sans jeton
répond 419 sans rien lire. Le plafond de débit ensuite, avant toute écriture :
cinq envois par heure et par adresse. La soumission est alors comptée, valide ou
non, puis seulement la validation. Enfin les deux pièges à robots : un champ
leurre masqué et retiré aux lecteurs d'écran, et un délai minimal de trois
secondes entre l'affichage du formulaire et son envoi, mesuré en session — un
champ caché se réécrit, la session non.

**Les refus sont dits, jamais silencieux.** L'usage veut qu'on fasse croire au
robot que son envoi est passé ; on ne l'a pas suivi. Quelqu'un qui vient
d'écrire dix lignes sur un proche et qui a déclenché un piège doit pouvoir
renvoyer son texte — perdre son témoignage en lui laissant croire qu'il est
parti serait pire que tout ce que le piège évite. La saisie est donc renvoyée
avec la page, dans les cinq cas d'échec.

**L'adresse électronique est exigée et ne paraît jamais.** Le modérateur doit
pouvoir revenir vers le signataire avant de publier des propos sous son nom.
C'est écrit sur le formulaire, à côté du champ. Côté lecture,
`Temoignage::listerPubliees()` énumère ses colonnes au lieu d'un `SELECT *` :
`auteur_email` et `ip_soumission` ne peuvent pas fuir dans une vue par
distraction.

**Après écriture, redirection** (303) : sans elle, un rafraîchissement de page
redéposerait le même témoignage. Le message de confirmation passe par la
session, le temps de la redirection.

L'accueil montre les trois derniers témoignages validés, coupés à 260
caractères, et renvoie vers la page. Quand il n'y en a aucun, ni l'un ni l'autre
ne montre de faux témoignage : ils invitent à déposer le premier.

### Les actualités, la fiche et la revue de presse

Trois pages en lecture seule (CDC §4.7), les premières à servir un contenu
rédigé au back-office : la liste `/actualites`, la fiche `/actualites/{slug}` et
la revue de presse `/revue-de-presse`. **Aucune n'ouvre de session** — la règle
posée avec les témoignages tient : un cookie n'apparaît que sur les routes qui
en ont besoin. Vérifié : la page des actualités ne dépose rien, celle des
témoignages dépose toujours son jeton.

**La revue de presse est une page, pas un filtre de plus.** Une coupure de
presse n'est pas une actualité du site : elle renvoie à un texte publié
ailleurs, et ce qui l'identifie est l'organe qui l'a publiée, pas la date à
laquelle on l'a saisie. Elle se lit donc comme une bibliographie — organe,
millésime, titre — là où la liste d'actualités se lit comme un fil. Les articles
de presse restent malgré tout dans la liste générale : ils font partie de ce qui
se passe autour de l'ouvrage, et les en retirer surprendrait l'éditeur qui vient
d'en publier un. Ce qui disparaît de la barre de filtres, en revanche, c'est la
pastille « Presse » : elle ferait doublon avec la page dédiée, qui prend sa
place au même endroit.

**Les filtres sont des liens, pas des boutons.** Chaque vue a son adresse, elle
se partage et le retour arrière y ramène. La frise de la biographie filtre en
JavaScript parce qu'elle déplie du contenu déjà chargé ; ici c'est une autre
requête, elle mérite une URL. Seules paraissent les catégories qui ont au moins
une entrée — un onglet qui donne sur une liste vide est un lien mort. Une
catégorie inconnue affiche la liste entière plutôt qu'une 404 : la ressource
existe, seul le point de vue est illisible, et un cul-de-sac serait
disproportionné pour un lien mal recopié.

**Un brouillon n'existe pas pour le site, même quand son adresse est connue** —
un slug se devine. La fiche répond 404 dans ce cas comme pour un slug inconnu,
et sans distinguer les deux : dire « cette page existe mais n'est pas publiée »
renseignerait sur le contenu du back-office. Vérifié : les deux répondent 404,
avec la page 404 de la charte.

**Ce qui met en ligne est le statut, jamais la date.** Une actualité datée de
demain paraît aujourd'hui, en tête de liste. Le site n'a pas de publication
différée, et en simuler une ici ferait disparaître l'article de l'éditeur sans
qu'aucun écran ne le lui dise. La date nulle, elle, est écartée en plus du
statut : l'écran de publication la refuse déjà, mais une ligne modifiée en SQL
passerait au travers et se rangerait n'importe où dans un classement qui est
chronologique.

**Le corps du texte est du texte, jamais du HTML.** Il est saisi en clair — une
ligne vide sépare deux paragraphes, c'est ce que dit l'aide du champ — et rendu
par `View::paragraphes`, qui échappe puis découpe. Un éditeur n'écrit pas de
balises ; une balise qui apparaîtrait dans le champ viendrait d'ailleurs que de
lui, et c'est ce qui fait qu'un compte d'édition compromis ne peut pas injecter
de script dans une page publique.

**Le JSON-LD est fabriqué par `json_encode`, avec `JSON_HEX_TAG`.** C'est la
première page dont le balisage structuré vient de la base, et c'est l'option qui
compte : sans elle, un titre contenant `</script>` fermerait la balise et ferait
passer la suite pour du code. Les pages statiques s'en dispensaient, leur
JSON-LD étant écrit en dur. Vérifié : une actualité titrée
`</script><script>alert(1)</script>` sort en `\u003C` dans le JSON-LD, en
entités dans le titre, la description et les balises Open Graph.

**Les dates sont écrites par une table de douze mois par langue**
(`App\Core\DateLisible`, nommée `DateFr` jusqu'au lot G11 où elle a appris
l'anglais), et non par `IntlDateFormatter`, `setlocale` ou
`strftime` — même raison que la translittération des slugs : ces trois-là
dépendent de la machine. `ext-intl` n'est pas garantie sur un hébergement
mutualisé, `setlocale` exige que la locale `fr_FR` soit installée, et
`strftime` est dépréciée depuis PHP 8.1. Le premier du mois s'écrit « 1er » :
« 1 mars » ne se dit pas en français, et c'est le genre de détail qui trahit un
gabarit.

**L'illustration sort avec son crédit.** La fiche va chercher la ligne de
médiathèque derrière le chemin pour en tirer la légende, le crédit, le texte de
remplacement et les dimensions ; une archive publiée sans mention de sa source
expose l'éditeur (CDC §6). La colonne `image` porte un chemin et non une clé
étrangère : le fichier peut avoir été retiré de la médiathèque, la page
s'affiche alors sans image plutôt qu'avec un cadre vide.

**Le lien vers l'article d'origine ne force pas de nouvelle fenêtre.** Un
`target="_blank"` retire au visiteur le contrôle de sa navigation et casse le
retour arrière ; `rel="noopener nofollow"` est posé malgré tout, au cas où un
navigateur l'ouvrirait quand même. C'est aussi pourquoi une ligne de la revue de
presse mène à la fiche interne et non directement au site de l'organe : un lien
dans un lien n'existe pas en HTML, il fallait choisir, et la fiche reste lisible
quand l'article d'origine a disparu — ce qui arrive vite sur les sites de presse.

**Pas de pagination, et c'est délibéré.** Une actualité de livre paraît par
dizaines sur des années, pas par milliers : découper la liste coûterait un
appareil de navigation pour un problème qui n'existe pas, et couperait la
lecture d'un fil qui se parcourt d'un trait. La question se reposera le jour où
la liste dépassera la centaine d'entrées ; les filtres offrent déjà de quoi la
réduire.

L'accueil ne montre plus trois entrées de démonstration : il lit les trois
dernières publiées et renvoie vers la page. Quand il n'y en a aucune, ni l'un ni
l'autre ne fabrique de fausse actualité — comme pour les témoignages, ils le
disent.

### La galerie d'archives et sa visionneuse

La page publique de la médiathèque (CDC §4.6). Elle ne décide de rien : l'ordre
est celui que l'éditeur a posé dans la planche du back-office, les catégories
sont les siennes, et une image passe en public par le seul fait d'être publiée.
**Il n'y a pas de second réglage « galerie »** — un deuxième endroit où décider
l'ordre finirait par contredire le premier.

**La visionneuse est un `<dialog>` natif.** Le navigateur y porte le piège au
clavier, la fermeture par Échap, le fond inerte et le retour du focus sur la
tuile d'origine : quatre choses régulièrement ratées quand on les réécrit. Le
reste — l'image, la légende, le passage d'une pièce à la suivante — tient en
une soixantaine de lignes dans `main.js`. Bootstrap est chargé sur toutes les
pages et sa boîte modale aurait fait l'affaire, mais la règle posée avec le
carrousel tient : on garde son moteur là où il apporte quelque chose, et ici la
plateforme le fait déjà.

**Chaque tuile est un lien vers l'image, pas un bouton.** Sans JavaScript, la
galerie reste entièrement parcourable — on clique, l'image s'ouvre. La
visionneuse intercepte ce lien quand elle peut ; les clics avec modificateur
(nouvel onglet) lui échappent volontairement.

**Deux tailles dérivées au lieu d'une**, et c'est la dette que le lot D2 avait
laissée ouverte. La vignette (600 px) sert les planches et les tuiles ; une
taille moyenne (1600 px) sert la visionneuse et le second cran du `srcset` sur
écran 2×. Sans elle, ouvrir une archive téléchargeait le fichier d'origine —
jusqu'à 8 Mio de scan pour regarder une photo sur un téléphone. Les chemins
étant **déduits du nom** et jamais stockés, l'ajout n'a demandé **aucune
migration** ; un dérivé absent retombe sur l'original.

**Le fichier d'origine n'est servi nulle part en public**, sauf dans un cas
précis : quand il n'a pas de taille moyenne. Ce n'est pas une approximation
mais une garantie — la moyenne n'est justement pas fabriquée quand l'image tient
déjà dans 1600 px. Son absence dit donc que l'original est léger. L'inverse,
laisser l'original en dernier cran d'un `srcset`, enverrait un scan de plusieurs
mégaoctets à un grand écran pour afficher une tuile, et une planche en compte
des dizaines.

Les largeurs du `srcset` sont **calculées et non devinées** : une tuile carrée
et une tuile panoramique n'ont pas la même largeur pour un même côté maximal,
et un `w` faux ferait choisir au navigateur le mauvais fichier.

**La légende et le crédit sortent avec l'image**, sur la tuile au survol et dans
la visionneuse en clair. Une archive publiée est une archive créditée — c'est
la règle du §6 du cahier des charges, et l'écran de publication du back-office
la fait déjà respecter à la saisie.

### L'agenda des événements

Une page, deux temps (CDC §4.10) : ce qui vient, puis ce qui a eu lieu. L'ordre
s'inverse entre les deux — le prochain rendez-vous d'abord, le dernier souvenir
d'abord — parce que ce sont deux questions différentes posées à la même liste.
Et l'ordre s'inverse aussi par rapport au back-office : un agenda répond à
« qu'est-ce qui arrive ensuite ? », une liste d'administration à « qu'ai-je
saisi en dernier ? ».

**Un événement annulé n'est pas un brouillon.** Il a été annoncé, quelqu'un l'a
peut-être noté dans son agenda ; le retirer en silence laisserait cette personne
se déplacer. Il reste donc affiché tant qu'il n'est pas passé — filet rouge,
titre barré, mention en clair — et sa fiche l'annonce avant l'adresse, pour
celui qui l'ouvre justement pour vérifier l'adresse. Une fois la date franchie,
il disparaît : un rendez-vous qui n'a pas eu lieu n'a rien à archiver, et le
laisser dans les passés le ferait lire comme un événement qui s'est tenu.

**Le lien d'inscription ne survit ni à l'annulation ni à la date.** Proposer de
s'inscrire à un rendez-vous qui n'aura pas lieu, ou qui a eu lieu, est pire que
ne rien proposer.

**Le partage entre à venir et passés se fait en SQL, avec `NOW()`.** PHP et
MySQL peuvent ne pas porter le même fuseau ; prendre l'heure des deux côtés
ferait dépendre le classement de leur écart. Une seule horloge tranche. Et la
bascule accorde le jour entier : `fin_le` est facultative, une dédicace d'un
après-midi saisie sans heure de fin ne doit pas passer aux archives à l'instant
où elle commence.

**Les dates s'écrivent comme on les dit.** `App\Core\DateLisible` a gagné les
heures et les intervalles : « 14 mars 2026, de 18 h 30 à 21 h », « du 14 au
16 mars 2026 », « du 28 décembre 2025 au 3 janvier 2026 ». La langue a une forme
pour chaque cas et les employer est ce qui sépare un agenda d'un tableau de base
de données. Deux détails qui ne se voient que s'ils manquent : la lettre h
entourée d'espaces insécables — « 18h30 » est de l'anglais mal traduit — et
**minuit qui vaut « heure non précisée » et ne s'affiche pas**. Le champ de
saisie impose une heure ; celui qui ne la connaît pas encore laisse 00:00, et
« à 0 h » serait une information fausse plutôt qu'absente.

Le balisage `Event` porte `eventStatus`, qui n'est pas décoratif : c'est par lui
qu'un moteur ou un agenda tiers apprend qu'un rendez-vous annoncé n'aura pas
lieu.

### Le contact, et où vont les messages

Second formulaire ouvert du site (CDC §4.11), et il ne réinvente rien : session
ouverte route par route, jeton CSRF, plafond de débit, champ leurre, délai
minimal, saisie renvoyée en cas d'échec — tout vient des témoignages. Le barème
`contact` était même déjà déclaré dans `Debit::BAREMES`, avant qu'aucun
formulaire ne l'emploie.

**Les messages sont écrits en base, jamais envoyés par courriel, et c'est la
décision principale du lot.** `mail()` sur un hébergement mutualisé échoue en
silence ou finit en indésirable, sans que personne l'apprenne — et un message
perdu est pire que pas de formulaire du tout. La base est donc la source de
vérité. Une notification pourra s'ajouter par-dessus le jour où un SMTP sera
configuré ; elle ne remplacera pas le stockage.

**Conséquence assumée : le back-office gagne un écran, alors qu'il avait été
déclaré entier.** Une boîte que personne ne peut ouvrir ne sert à rien. Elle
reprend la présentation de la file de modération — des cartes, parce qu'un
message se lit avant qu'on en fasse quelque chose — avec deux différences de
fond : l'adresse du correspondant y est la donnée utile, tout l'objet de
l'écran étant de pouvoir répondre ; et il n'y a rien à corriger, ces messages ne
paraîtront jamais en public. Deux verbes seulement, « traité » et « supprimer ».
Qui a traité et quand est conservé : sur une boîte partagée, sans cette trace,
deux personnes répondent au même message.

**Répondre se fait dans le logiciel de courrier**, par un lien `mailto:` à
l'objet prérempli. Écrire un envoi depuis le back-office demanderait la
configuration SMTP qui manque précisément.

**Le motif est une liste fermée**, pas un champ libre : il sert au tri de la
boîte, et « Objet : bonjour » ne trie rien.

**Il n'y a pas de corbeille**, et les mentions légales le disent au visiteur :
un message est conservé le temps d'y répondre, puis supprimé.

### Les mentions légales, écrites d'après le code

Un seul document (CDC §4.12), pas deux : la politique de confidentialité est
une section des mentions légales. Deux pages qui se renvoient l'une à l'autre
finissent par se contredire.

**Les sections « données », « cookies » et « services tiers » décrivent le
comportement réel du site, relevé dans le code — elles ne sont pas recopiées
d'un modèle.** C'est ce qui les rend vérifiables :

- **un seul cookie**, `pgy`, posé sur les deux pages qui portent un formulaire
  et nulle part ailleurs. Vérifié à l'en-tête HTTP : `/contact` en dépose un,
  `/mentions-legales` aucun. La page peut donc affirmer qu'il n'y a rien à
  accepter ni à refuser, et le site se passer de bandeau ;
- **aucune mesure d'audience**, aucun cookie publicitaire ;
- **deux domaines tiers**, dits en clair : Google Fonts pour les polices et
  jsDelivr pour Bootstrap. Afficher une page transmet l'adresse IP du visiteur à
  ces deux services. C'est vrai, donc c'est écrit ; héberger les polices
  soi-même supprimerait le premier transfert, et c'est noté en dette ;
- **ce que chaque formulaire collecte**, champ par champ, et pourquoi :
  l'adresse électronique d'un témoignage sert à recontacter le signataire avant
  publication et ne paraît jamais ; l'IP sert au plafond anti-abus et son
  journal est purgé au bout d'un jour.

Ce qui relève de l'état civil de la structure éditrice — raison sociale,
immatriculation, directeur de la publication, hébergeur — **ne s'invente pas** et
reste balisé comme à fournir, en italique comme le reste du texte provisoire du
site. Voir le §5.

**Les coordonnées vivent dans `config/config.php`**, pas dans un gabarit : elles
paraissent à trois endroits — page Contact, mentions légales, pied de page — et
une adresse recopiée trois fois finit par diverger. Pas en base non plus : ce
sont des constantes d'organisation, pas du contenu éditorial ; les mettre dans
`parametre` demanderait un écran d'administration pour une valeur qu'on touche
tous les cinq ans. Une valeur vide ne s'affiche pas, la page se referme
proprement sur ce qui manque.

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

### Les commandes et la passerelle

**Décision prise : carte.abidjan.net**, le backend de paiement du site de
référence — mobile money (Orange, MTN, Moov), Wave, Visa/Mastercard, APaym et
Visa QR. Il est en service, il connaît la zone et ses moyens de paiement ; le
remplacer avant d'avoir vendu un exemplaire coûterait un chantier pour rien.
**Elle peut changer**, et c'est pourquoi ce qu'on sait d'elle tient en un
endroit : `config/config.php` porte l'hôte et le nom, `App\Core\Paiement` porte
les points d'entrée de chaque mode. Le site de référence, lui, écrivait ses sept
adresses en dur dans le JavaScript de trois pages.

`commande.passerelle` est une colonne et non une constante : le jour où une
commande naîtra ailleurs, les anciennes doivent continuer à dire d'où elles
viennent, sans quoi on ne saura plus où aller vérifier un paiement contesté.

**L'écran ne crée ni ne supprime de commande.** Une commande naît du tunnel de
paiement — qui reste à écrire, il suppose les pages publiques de la boutique — et
elle reste : c'est une pièce comptable. L'administration en fait trois choses :
constater un paiement, marquer une remise, annoter.

**Le statut suit un chemin, pas un menu déroulant.** Initiée → payée ou échouée ;
payée → remise ; échouée et remise sont terminales. La transition demandée est
vérifiée contre `Commande::SUITES`, donc une URL forgée n'y change rien. Le
back-office ne décide pas d'un paiement, il en prend acte : « constater le
paiement » se fait après l'avoir vu chez la passerelle, qui fait foi.

**Le corps de la fiche est en lecture seule**, et seule la note de suivi
s'écrit — `ASSIGNABLES` ne contient qu'elle. Corriger un montant ou un nom ici
ferait diverger la commande de ce que la passerelle a gardé. Qui a marqué la
remise, et quand, est conservé, comme pour la modération.

Les recettes sont totalisées **par devise** : la passerelle en accepte
plusieurs, et additionner des francs CFA et des euros ne voudrait rien dire.

### Les comptes

L'écran reprend ce que faisait `bin/compte.php` pour l'usage courant : ajouter un
éditeur, changer un rôle, réinitialiser un mot de passe.

**Aucune suppression, seulement la désactivation.** Un compte a modéré des
témoignages et remis des commandes ; l'effacer viderait ces traces de leur nom, et
la question « qui a validé ceci ? » perdrait sa réponse. Un compte désactivé ne
peut plus se connecter, et `Auth` relisant son état à chaque requête, une session
ouverte tombe à la requête suivante.

**Trois manœuvres sont refusées**, toutes pour la même raison — elles
laisseraient le back-office sans personne pour y entrer : se désactiver soi-même,
se retirer son propre rôle d'administrateur, et retirer le dernier administrateur
actif. Vérifié sur les trois chemins, formulaire comme bascule de liste.

**Le mot de passe est fabriqué par défaut**, et affiché une seule fois. Un mot de
passe choisi à la volée pour quelqu'un d'autre est faible par construction, et il
finit recopié dans un courriel. Celui-ci sort de `random_bytes`, dans un alphabet
sans caractères confondables — O/0, I/l/1 — parce qu'il sera dicté ou recopié.

**Deux rôles.** L'éditeur travaille sur les contenus, la médiathèque et la
modération. L'administrateur a en plus les comptes — on y distribue les droits —
et les commandes, qui portent des noms, des adresses et des numéros de téléphone
de clients. `Auth::exigerAdmin()` garde les deux écrans, en lecture comme en
écriture, et les entrées correspondantes sont **retirées** du menu d'un éditeur
plutôt que grisées : un verrou dirait « pas encore construit », ce qui serait
faux. Un éditeur qui force l'adresse obtient une **403** et non une 404 : le
back-office n'a rien à cacher à ses propres utilisateurs, l'écran existe, il faut
un autre rôle.

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

### La médiathèque

Troisième mécanique du back-office, après les contenus et la modération, et
encore une fois distincte : **on ne crée pas un média par un formulaire, on en
dépose un fichier**. `MediaController` ne dérive donc pas de `CrudController`.
Le dépôt se fait par lot — des archives arrivent par dizaines, en une enveloppe
— et les métadonnées se complètent ensuite, fiche par fiche. Un fichier refusé
n'annule pas les autres : sur vingt scans, celui qui dépasse la taille ne doit
pas faire recommencer les dix-neuf qui sont passés.

**Le type est lu dans les octets, jamais dans l'extension.** `getimagesize`
d'abord, puis `finfo` en contre-épreuve : les deux doivent désigner le même
format, et il doit figurer parmi JPEG, PNG et WebP. Vérifié — un script PHP
renommé en `.jpg` est refusé au dépôt, avec un message qui le dit.

**Le nom de destination est fabriqué**, jamais reçu : slug du nom d'origine, huit
caractères aléatoires, extension du format réellement détecté. Le nom du client
ne survit pas au dépôt, ce qui règle d'un coup les `../`, les caractères de
contrôle, les doubles extensions et les collisions. Les fichiers sont rangés par
mois (`medias/2026/09/…`) : un dossier plat de plusieurs milliers d'archives
n'est plus manipulable, ni en FTP ni en sauvegarde.

**Trois plafonds**, et ils ne disent pas la même chose : 8 Mio par fichier
(applicatif), 40 mégapixels par image — un PNG de 40 Ko peut déclarer
30 000 × 30 000 pixels et réclamer des gigaoctets à l'ouverture — et 20 fichiers
par dépôt. Quand c'est le serveur qui tranche, c'est sa limite qui est annoncée,
la plus petite de `upload_max_filesize` et `post_max_size`.

**Un envoi coupé par `post_max_size` est reconnu comme tel.** PHP vide alors
`$_POST` sans le dire, jeton CSRF compris : la vérification du dépassement passe
donc **avant** celle du jeton, sinon un envoi trop lourd s'annoncerait comme une
session expirée. Vérifié en simulant l'état exact que PHP produit.

**Vignettes** en 600 px de côté, générées par GD, calées sur l'orientation EXIF —
scanners et téléphones l'écrivent dans les métadonnées plutôt que dans les
pixels ; le navigateur en tient compte sur l'original, GD non, et un portrait
serait sorti couché. La transparence d'un PNG est aplatie sur blanc, le JPEG ne
la connaissant pas. L'échec de la vignette n'annule pas le dépôt : la
médiathèque retombe sur l'original.

**Le crédit conditionne la publication** (CDC §6) : une archive sans mention de
provenance engage l'éditeur. La règle vaut pour le formulaire comme pour la
bascule depuis la planche — un raccourci ne doit pas être une porte dérobée. Le
manque se signale sur la vignette et remonte au tableau de bord.

**La suppression efface le fichier, sa vignette, et détache les fiches** qui
l'affichaient. `actualite.image` porte un chemin et non une clé étrangère :
aucune contrainte de base ne le ferait, et l'actualité garderait le chemin d'un
fichier effacé.

**Le sélecteur d'image des fiches** montre la planche, pas une liste de noms de
fichiers. La valeur soumise est le chemin — la page publique n'aura aucune
jointure à faire — et elle est revalidée à l'enregistrement : forme du chemin,
puis présence en base. Vérifié : `../../config/config.php` est refusé, un chemin
bien formé mais inconnu aussi.

L'entrée « Médiathèque » du menu est passée de la rubrique « Modération », où le
découpage en lots l'avait mise, à « Contenus », qui est sa place.

### `medias/` n'exécute rien

Le dossier reçoit des fichiers déposés par un utilisateur ; un fichier déposé ne
doit jamais être exécuté. `medias/.htaccess` retire les gestionnaires PHP, CGI et
SSI, coupe le moteur PHP, refuse les extensions sensibles et pose `nosniff`.

Vérifié : un `.php` déposé répond 403 ; un `.php.jpg` — la contournement
classique par double extension — sort en source brute, non interprétée ; une
vraie image est servie normalement. Le contrôle de type au téléversement
(`App\Core\Televersement`, voir ci-dessus) est la première barrière, celle-ci
est la seconde et tient si la première cède.

### La duplication du chrome est soldée

Navigation et pied de page vivaient en trois exemplaires dans les pages statiques.
Ils sont désormais dans `templates/partials/`, inclus une seule fois par
`templates/layout.php`. Vérifié sur les trois pages : un seul `<header>`, un seul
`<main>`, un seul `<footer>` par rendu.

### Base de données

Neuf tables, `utf8mb4`, InnoDB — voir `sql/001_schema.sql` :
`utilisateur`, `actualite`, `evenement`, `temoignage`, `media`, `repere`,
`commande`, `message`, `parametre`. Plus deux compteurs glissants :
`tentative_connexion` (`sql/002_auth.sql`) pour les essais de connexion, et
`soumission_publique` (`sql/005_soumission.sql`) pour les formulaires publics.

Les migrations s'appliquent dans l'ordre de leur numéro ; il n'y a pas encore de
table de suivi. **Les six premiers fichiers sont décrits ici, les neuf suivants
au §9** — ce sont ceux du nouveau périmètre, et c'est là qu'ils sont listés dans
l'ordre où les jouer. `sql/003_media.sql` ajoute à
`media` le poids du fichier et l'unicité de son chemin ; `sql/004_commande.sql`
ajoute à `commande` la provenance du paiement, le code de transaction, la note de
suivi et la trace de remise ; `sql/005_soumission.sql` crée le journal des
soumissions publiques ; `sql/006_message.sql` crée la table des messages du
formulaire de contact. Tout est reporté dans `001_schema.sql` pour qu'une
installation neuve n'ait pas à rejouer l'historique.

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
PerfectScrollbar, les icônes MDI (woff2 seul), DataTables (listes d'actualités,
d'événements, de repères et de commandes) et Chart.js — ce dernier n'est encore
employé nulle part —, et trois comportements d'interface fusionnés en un seul
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

- ~~**Logotype**~~ — **livré et intégré.** Le verrou HTML a disparu des quatre
  emplacements où il vivait : en-tête, pied, barre du back-office et écran de
  connexion. Le favicon et l'icône iOS ont été redessinés autour du Y officiel et
  tout le jeu régénéré — `favicon.svg`, `favicon-32.png`, `apple-touch-icon.svg`
  et `.png`, `favicon.ico` (16/32/48). Voir §1 et §2.

  Deux remarques à renvoyer au studio, sans urgence : le fichier livré porte
  `fill:#595959` dans un bloc `<style>` avec une classe `.cls-1`, et un
  `id="Calque_1"` — trois choses qu'un logo inséré deux fois par page ne peut pas
  garder telles quelles (voir §2, « Le logotype, servi une fois »). Et son
  `viewBox` de 3000 × 2000 laissait un tiers de vide au-dessus et au-dessous du
  dessin. Rien de bloquant : les trois ont été corrigés à l'intégration, sans
  toucher à un seul tracé.
- **Visuels** — les `.svg` de `assets/img/` sont des cadres d'attente générés. Chacun
  affiche la dimension de livraison attendue et son cadrage. Ces valeurs sont calculées
  sur la largeur réelle d'affichage en écran 2× ; le hero est en dérive `scale(1.1)`,
  d'où sa marge supplémentaire.

  | Fichier | Dimensions | Cadrage |
  |---|---|---|
  | `hero-1/2/3.svg` | **2000 × 2600 px** | portrait, sujet décentré à droite |
  | `couverture.svg` | **1200 × 1550 px** | ratio 240 × 310 mm |
  | ~~`portrait.svg`~~ → `portrait.webp` | **1400 × 1750 px** | buste — **livré** |
  | `auteur.svg` | **1000 × 1250 px** | portrait 4:5 |
  | `extrait-1/2.svg` | **1500 × 1000 px** | double page |
  | ~~`gal-1` à `gal-4.svg`~~ | — | **retirés** : les archives passent par la médiathèque |

  **Les quatre cadres d'attente de la galerie ont disparu du thème.** Depuis F3,
  la planche d'archives, l'aperçu de l'accueil et les portraits de la biographie
  lisent la médiathèque : c'est le back-office qui fournit les images, avec leur
  légende et leur crédit, et non plus un fichier posé dans `assets/`. Les
  dimensions n'y sont donc plus imposées — la trame recadre en `object-fit:
  cover` selon quatre proportions qui se répètent (4:3, 3:4, 1:1, 16:9). Ce qu'il
  faut savoir en déposant : **le sujet doit tenir au centre**, un cadrage serré
  sur un bord sera coupé sur au moins une des quatre.

  Les visuels du hero sont recadrés en `object-fit: cover` sur un panneau vertical :
  **prévoir le sujet dans la moitié droite**, la gauche étant recouverte par le voile
  papier. Livrer en JPEG qualité 80 après redimensionnement — les archives brutes sont
  souvent bien plus lourdes que nécessaire (CDC §5, performance).

  Traitement homogène des archives : le CSS applique déjà
  `grayscale(1) contrast(1.04) sepia(0.14)`, ce qui unifie des sources d'origines
  diverses sans retouche préalable (CDC §6).

  **Le premier visuel réel est arrivé** : le portrait de jeunesse, en tête de la
  biographie, aux dimensions exactes qu'annonçait le cadre d'attente. Il est
  servi en WebP — le format que la médiathèque accepte déjà — sans repli JPEG :
  tous les navigateurs le comprennent depuis 2020, et un second fichier pour une
  part de marché résiduelle se paierait à chaque déploiement. Il est **réencodé
  depuis le JPEG d'origine en qualité 80, ce qui le fait passer de 467 à 50
  kilo-octets** — neuf fois moins pour une différence invisible à l'écran, même
  en zoom sur les lunettes, et à plus forte raison sous le filtre du CSS. Sur des
  connexions mobiles ouest-africaines, l'écart n'est pas cosmétique (CDC §5).

  Ce portrait vit dans le thème et non dans la médiathèque, parce que son
  emplacement est fixe : c'est une pièce du gabarit, pas une archive que
  l'éditeur choisit. Les archives qu'on remplace ou qu'on complète passent, elles,
  par le back-office.

  **Son crédit reste à obtenir** — fonds, photographe ou détenteur des droits —
  et un commentaire le rappelle dans le gabarit. La règle du §6 du cahier des
  charges vaut ici comme pour les images de la médiathèque, à ceci près
  qu'aucun écran ne la fait respecter sur un fichier du thème.
- **Archives photographiques** — elles ne passent plus par le dépôt : elles se
  déposent dans le back-office, rubrique Médiathèque (JPEG, PNG ou WebP, 8 Mo par
  fichier). **Chaque image demande son crédit** — fonds, photographe ou détenteur
  des droits — sans quoi elle ne peut pas être publiée, et une légende, qui sert
  aussi de texte de remplacement aux lecteurs d'écran.
- **Contenus** — tout le texte éditorial est balisé provisoire. **Les dates de la frise
  et les citations doivent être validées avant publication** : Yacé est une figure
  historique réelle, aucun propos ne doit lui être attribué sans source.
- **Visuel de partage définitif** — `assets/img/og-image.jpg` est un placard
  typographique provisoire (1200 × 630). La version finale portera la couverture
  de l'ouvrage ou le portrait ; le sujet doit tenir dans les 80 % centraux, les
  vignettes carrées de certaines plateformes rognant les bords.
- **Adresse publique** — **le domaine est arrêté : `https://www.philippeyace.ci`**.
  Il se pose en `'url'` dans la section `app` de `config/config.local.php`, qui
  existe sur le serveur depuis le 10 septembre ; **à confirmer**, la mise en
  place n'ayant porté que sur les identifiants de base. Il n'est volontairement pas écrit
  dans `config/config.php` : le poste de développement rendrait alors des
  `canonical` pointant vers la production. Sans cette valeur, `canonical` et
  `og:image` retombent sur l'hôte de la requête, acceptable en développement,
  pas sur un serveur public (voir « URL absolues » au §2).
- **Mentions légales — l'état civil de l'éditeur.** La page est écrite et
  publiée&nbsp;; quatre informations qui ne s'inventent pas y sont balisées en
  italique et doivent être fournies avant mise en ligne, la loi imposant de les
  publier :

  | Manquant | Qui le fournit |
  |---|---|
  | Raison sociale, forme juridique, immatriculation (RCCM) | la structure éditrice |
  | Nom et qualité du **directeur de la publication** | la structure éditrice |
  | Nom, adresse postale et téléphone de **l'hébergeur** | connu au choix du prestataire |
  | Capital social, si la forme juridique l'impose | la structure éditrice |
  | **Numéro de téléphone de la structure éditrice** — affiché masqué en attendant | la structure éditrice |

  Les coordonnées, elles, sont posées : boîte postale
  15 BP 10125 Abidjan 15, siège au 7186, Boulevard Philippe Grégoire Yacé,
  00216 Marcory, District d'Abidjan, et `contact@philippeyace.ci`. Elles vivent
  dans `config/config.php`, en un seul endroit pour les trois pages qui les
  affichent.

  **Le téléphone n'est pas arrêté** et s'affiche `+225 ** ** ** ** **`. Il ne se
  clique pas : `tel_lien` est vide dans la configuration, et la page Contact rend
  alors le masque en texte simple — un lien `tel:` sur des astérisques ouvrirait
  le composeur sur un numéro impossible. Le jour où le numéro est connu, les deux
  lignes de `config/config.php` se remplissent ensemble et le lien revient.

  **À revoir le jour où la boutique ouvrira** : la section « données
  personnelles » décrit les deux formulaires existants. Un tunnel de commande
  collectera une adresse de livraison et un identifiant de transaction, qu'il
  faudra y déclarer.

---

## 6. Dette connue

Deux lignes qui figuraient ici sont **soldées** : la duplication de la navigation
et du pied de page, depuis le passage aux gabarits, et la limitation de débit des
formulaires publics, posée avant qu'aucun formulaire ne soit exposé (voir §2 pour
les deux). Ce qu'il reste :

- **Deux ressources chargées depuis des domaines tiers.** Les polices viennent
  de Google Fonts, Bootstrap de jsDelivr : afficher une page transmet l'adresse
  IP du visiteur à ces deux services. C'est dit dans les mentions légales, mais
  le dire ne le supprime pas. Héberger les deux fichiers de polices dans
  `assets/` réglerait le premier cas — quelques dizaines de kilo-octets, une
  règle `@font-face`, et une dépendance de moins au moment du rendu.
- **Aucun test.** Le socle a été vérifié à la main (codes HTTP, connexion PDO,
  absence de warning, mesure du rendu au navigateur). Ces vérifications ne sont
  pas rejouables automatiquement.
- **Pas d'éditeur enrichi.** Le corps d'une actualité se saisit en texte brut,
  une ligne vide séparant deux paragraphes, et la page publique le rend échappé
  (voir §2). Pas de gras, pas de lien dans le corps du texte : le jour où
  l'éditeur en demandera, ce sera une syntaxe restreinte et une liste blanche de
  balises, jamais du HTML servi tel quel. L'illustration, elle, se choisit
  désormais dans la médiathèque (lot D2).
- **La médiathèque ne recadre ni ne remplace.** Une image mal cadrée se retaille
  hors du site, et changer le fichier d'une fiche demande d'en déposer un autre
  puis de supprimer le premier — un remplacement en place changerait sans le dire
  ce que montrent les pages qui l'affichent. ~~Une seule vignette est produite
  (600 px)~~ — **soldé avec la galerie** : deux tailles sont désormais fabriquées
  au dépôt, 600 et 1600 px, et les tuiles portent un `srcset` (voir §2).

  Reste, pour les fichiers déjà déposés : ils n'ont pas de taille moyenne, et
  personne ne la leur fabriquera. Le repli les couvre — ils sont servis en
  original —, ce qui est sans conséquence tant que la médiathèque est vide,
  comme c'est le cas aujourd'hui. Si des archives sont déposées avant une
  évolution des tailles, il faudra un script de rattrapage : une boucle sur
  `media`, l'appel du fabricant, rien de plus.
- **Le CSS du thème d'administration porte des règles mortes.** Des composants
  que le back-office n'emploie pas (graphiques de démonstration, menu horizontal,
  panneau de réglages) gardent leurs styles dans `style.css`. Sans effet visible,
  mais quelques dizaines de kilo-octets pour rien. Les sept lots étant écrits, on
  sait maintenant ce qui sert : l'élagage est faisable, il n'attend plus rien.

  S'y ajoute, côté public et pour vingt lignes, le **sommaire latéral collé** —
  `.subnav` en CSS et son repérage de section en JavaScript. Il ne servait qu'aux
  cinq chapitres de la biographie, que le lot G10 remplace par des pages. Gardé
  parce qu'une page de texte long peut le redemander ; à retirer si aucune ne
  le fait.
- **Le tunnel de commande n'est pas écrit.** L'écran de suivi existe et la
  passerelle est arrêtée, mais rien ne crée encore de commande : il faut les
  pages publiques de la boutique d'abord. Conséquence pratique, l'écran restera
  vide jusque-là — et aucune saisie manuelle n'est ouverte, faute d'avoir été
  demandée. Si l'éditeur prend des commandes au téléphone ou en dédicace, c'est
  un formulaire à ajouter, avec ses propres modes de paiement.
- **Le spam reste possible, en petite quantité.** Le formulaire des témoignages
  porte désormais ses deux pièges — champ leurre et délai minimal — et son
  plafond, mais un robot patient qui les franchit et reste sous cinq envois par
  heure passe. C'est assumé : la file de modération est là pour ça, et rien ne
  paraît sans décision humaine.
- **L'ordre des fichiers d'une notice suit celui de la médiathèque** (lot G4).
  Réordonner une notice passe donc par la planche générale, ce qui est
  indirect ; le premier fichier faisant la vignette et l'image de partage, la
  conséquence est visible. À reprendre le jour où une notice portera vraiment
  une galerie.

  *(Une ligne affirmait ici que le dépôt de fichiers était unitaire : c'était
  faux. Le dépôt multiple existe depuis le lot D2 — `fichiers[]`, vingt par
  lot, avec la garde sur les envois tronqués.)*
- **Aucune pagination sur les listes publiques.** Actualités, revue de presse,
  archives et agenda rendent tout ce qui est publié. *(Côté back-office, la
  médiathèque est paginée depuis le lot G4b.)* C'est le bon choix pour des
  dizaines d'entrées, et la galerie s'en tire mieux qu'on ne pourrait le croire :
  ses tuiles sont en `loading="lazy"`, un navigateur ne télécharge que ce qui
  approche de l'écran. La question se reposera au-delà de la centaine de pièces,
  où c'est le poids du HTML et la longueur de la planche qui gêneront, pas les
  images.
- **Le bouton « publier » des listes ne valide rien, sauf pour les périodes.**
  Il n'écrit qu'une colonne de statut, sans passer par le formulaire ni par
  `valider()` : un repère ou un sujet d'Héritage sans source peut donc être mis
  en ligne depuis la liste, alors que sa fiche le refuserait. Le point
  d'accroche existe depuis le lot G10 — `CrudController::refusDePublier()` — et
  n'est implémenté que pour les périodes de la biographie, dont la publication
  a des conditions que rien d'autre ne rattrape. L'étendre aux quatre autres
  écrans est mécanique ; ce n'était pas le périmètre de ce lot, et changer d'un
  coup le comportement de quatre écrans qu'un éditeur connaît demande d'être
  annoncé.
- **Pas d'export des commandes.** Ni CSV ni impression : la comptabilité devra
  relire l'écran ou la base. À voir quand il y aura des commandes.
- **`reference/` pèse ~70 Mo dans l'arborescence servie.** Verrouillé en 403,
  mais toujours à exclure explicitement de la règle de déploiement.
- **L'historique git porte les images non optimisées de l'ancien site** (`.git`
  fait 58 Mo). Sans conséquence fonctionnelle ; à savoir si le dépôt part un jour
  sur un hébergement distant.

## 7. État et suite

### Où en est le projet

**Le socle** — direction artistique complète et documentée, logotype officiel
intégré et jeu d'icônes dérivé de sa lettre ; contrôleur frontal, routeur, PDO,
mise en page unique ; base `livreyace_sbd`, onze tables ; étanchéité des
dossiers applicatifs vérifiée sur Apache. Deux briques transverses s'y sont
ajoutées avec les lots publics : `App\Core\DateLisible`, qui écrit les dates
sans dépendre de la machine, et `View::paragraphes`, qui rend échappé
tout corps de texte saisi au back-office.

**Le back-office** — **entier**, ses sept lots livrés : de l'ossature aux
comptes et commandes, en passant par les contenus, la modération, la médiathèque
et le pilotage. Un éditeur peut aujourd'hui tout saisir, tout modérer et tout
publier sans intervention technique. **Un huitième écran s'y est ajouté après
coup**, avec le lot public F4 : la boîte de réception du formulaire de contact.
Ce n'était pas prévu, et la raison est écrite au §2 — les messages sont stockés
plutôt qu'envoyés par courriel, et une boîte que personne ne peut ouvrir ne sert
à rien.

**La couche formulaire** — **complète** : jeton CSRF et validation depuis le
lot B, limitation de débit des formulaires publics, session côté visiteur
ouverte route par route, et les pièges à robots posés avec le premier
formulaire.

**Le site public** — neuf entrées du cahier des charges sur onze : accueil, Le
livre, Biographie, Témoignages, Actualités/Presse, Galerie/Archives,
Événements, Contact et Mentions légales. Onze pages en tout, les entrées
portant parfois plusieurs adresses — liste, fiche par slug, revue de presse,
agenda. Plus une 404 dessinée dans la charte, servie par le routeur comme par
une fiche introuvable.

**Non fait sur le périmètre initial** — Héritage, le tunnel de commande et la
phase 3 du CDC. Héritage n'attend rien de technique, tout de la matière
éditoriale ; **le tunnel de commande est le seul chantier de code qui restait au
titre du cahier des charges d'origine.**

**Le périmètre a changé le 7 septembre 2026.** Un nouveau brief demande que le
site devienne un fonds patrimonial numérique et non plus le site de campagne
d'un livre. Il est repris intégralement au **§9**, avec ce qu'il implique lot
par lot. Le présent §7 décrit donc l'achèvement du cahier des charges d'origine ;
il n'est plus la liste complète de ce qui reste à faire.

**Ce qui manque au périmètre initial n'est plus du travail de développement, à
une exception près.** Le site attend des contenus — textes validés, visuels
d'archives, fiche technique de l'ouvrage — et quatre informations légales que
seule la structure éditrice peut fournir. Voir §5, et « Ce qui bloque » plus bas.

### Ce qui est en ligne

**Cette section décrit un état daté, et elle doit porter sa date.** Elle a déjà
induit en erreur une fois : écrite le 2 septembre, elle affirmait encore une
semaine plus tard que le serveur était en retard de quatre lots, alors que le
déploiement avait eu lieu. Un document n'est pas une mesure. **Toute relecture
qui s'appuie sur ce qui suit doit d'abord vérifier la date.**

**État au 7 septembre 2026, matin : le dépôt et le serveur étaient au même
point.** Les onze pages publiques étaient déployées, les huit écrans du
back-office aussi, et les quatre migrations jouées en production.

**Le même jour, l'écart s'est recreusé** : dix lots du nouveau périmètre ont
été écrits et poussés dans la foulée, avec neuf migrations. La liste, dans
l'ordre où les jouer, est au §9 — « Où en est ce périmètre ».

**État au 8 septembre 2026 : la base est à jour jusqu'à `015`, le code ne l'est
pas, et `016` puis `017` se sont ajoutées depuis.** Les neuf migrations du nouveau périmètre
ont été jouées dans la journée, `007` à `015`, une à une dans phpMyAdmin — le serveur est un **MariaDB 10.5.26**, ce qui
n'avait jamais été relevé jusque-là (voir §2, qui raisonnait dessus sans
l'avoir constaté). `repere` porte ses sept entrées et ses quatre mises en avant ;
`repere.periode` a bien disparu.

**Ce qui reste est l'envoi des fichiers**, et c'est la plus grosse part : 117
fichiers ont changé depuis le dernier état déployé, dont 59 nouveaux et **un
supprimé** — `src/Core/DateFr.php`, renommé en `DateLisible.php` au lot G11 ;
un envoi FTP n'efface rien, il faut donc le retirer à la main, faute de quoi
deux classes coexistent et la lecture s'en trouve trompée.

Le compte se mesure, il ne se retient pas — **hors `sql/` et `README.md`, qui
ne s'envoient pas par FTP** : les migrations se jouent dans phpMyAdmin et le
présent fichier ne sert qu'au dépôt. `d83dde1` est le dernier état mis en
ligne, celui du 2 septembre.

    git diff --name-status d83dde1 HEAD | grep -v '	sql/\|	README.md'

**`config/config.php` est dans le lot**, et il porte les coordonnées publiques :
l'adresse et le téléphone ont changé le 8 septembre, et ils n'atteindront la
page Contact et les mentions légales que par ce fichier. Depuis le lot G13 il
n'emporte plus aucun identifiant : il s'envoie sans précaution et sans rien
écraser.

Les quatre pièges de cet envoi sont listés plus bas — « Côté fichiers » — et le
premier est que `quarantaine/` ne contient que des fichiers commençant par un
point, que les clients FTP masquent.

**Ce paragraphe vieillira ; la requête ci-dessous, non.** Elle est la mesure,
il n'en est que le résumé.

| Migration | Ce qu'elle apporte | État en production |
|---|---|---|
| `sql/003_media.sql` | poids du fichier et unicité du chemin sur `media` (lot D2) | jouée |
| `sql/004_commande.sql` | provenance du paiement, code de transaction, note et trace de remise (lot E2) | jouée |
| `sql/005_soumission.sql` | journal des soumissions publiques (limitation de débit) | jouée |
| `sql/006_message.sql` | table des messages du formulaire de contact (lot F4) | jouée |
| `sql/007_actualite_categories.sql` → `sql/015_periode.sql` | tout le nouveau périmètre, neuf fichiers | jouées le 8 septembre |
| `sql/016_boutique.sql` | la boutique : zones de livraison, décompte figé des commandes, statuts du paiement à la livraison | **à jouer** |
| `sql/017_point_de_vente.sql` | les points de vente, et l'amorce des trois villes qui étaient écrites dans les gabarits | **à jouer** |

**`001_schema.sql` ne se rejoue jamais sur une base installée.** Il a été mis à
jour pour qu'une installation neuve n'ait pas à rejouer l'historique, mais ses
`CREATE TABLE IF NOT EXISTS` ne toucheraient pas des tables existantes : les
nouvelles colonnes n'arriveraient pas, et la base paraîtrait à jour sans l'être.

`003` et `004` sont des `ALTER TABLE` : les rejouer lève `Duplicate column
name`. `005` et `006` créent des tables en `IF NOT EXISTS` : les rejouer ne
casse rien, mais ne rattrape rien non plus si la table existe sous une autre
forme.

**La requête de contrôle reste ici, et elle couvre désormais les quinze
migrations.** Elle dit où en est une base sans qu'on ait à la croire sur parole,
et se colle telle quelle dans phpMyAdmin après chaque fichier : **`1` partout**
sur une base à jour, un `0` sur chaque migration manquante. La ligne `009` fait
exception et porte un décompte — l'amorce verse des données, pas une structure ;
on y attend `7`.

Elle est écrite comme une liste d'objets attendus plutôt qu'en dix-huit
requêtes empilées : la liste se relit, et une migration à venir s'y ajoute d'une
ligne. La ligne `015` se lit à l'envers des autres — `periode retirée de repere`
vaut `1` quand la colonne a bien disparu.

```sql
SELECT o.migration, o.controle,
       CASE o.attendu
         WHEN 'table' THEN
           (SELECT COUNT(*) FROM information_schema.tables t
             WHERE t.table_schema = DATABASE() AND t.table_name = o.objet)
         WHEN 'colonne' THEN
           (SELECT COUNT(*) FROM information_schema.columns c
             WHERE c.table_schema = DATABASE() AND c.table_name = o.objet
               AND c.column_name = o.membre)
         WHEN 'retiree' THEN
           1 - (SELECT COUNT(*) FROM information_schema.columns c
                 WHERE c.table_schema = DATABASE() AND c.table_name = o.objet
                   AND c.column_name = o.membre)
       END AS present
  FROM (
              SELECT '003' AS migration, 'colonne' AS attendu, 'media' AS objet,
                     'octets' AS membre, 'octets sur media' AS controle
    UNION ALL SELECT '004', 'colonne', 'commande', 'passerelle', 'passerelle sur commande'
    UNION ALL SELECT '005', 'table',   'soumission_publique', '', 'table soumission_publique'
    UNION ALL SELECT '006', 'table',   'message', '', 'table message'
    UNION ALL SELECT '008', 'colonne', 'repere', 'en_avant', 'en_avant sur repere'
    UNION ALL SELECT '010', 'table',   'traduction', '', 'table traduction'
    UNION ALL SELECT '011', 'table',   'archive', '', 'table archive'
    UNION ALL SELECT '011', 'table',   'archive_media', '', 'table archive_media'
    UNION ALL SELECT '012', 'colonne', 'media', 'famille', 'famille sur media'
    UNION ALL SELECT '013', 'table',   'heritage', '', 'table heritage'
    UNION ALL SELECT '013', 'table',   'heritage_media', '', 'table heritage_media'
    UNION ALL SELECT '014', 'table',   'contribution', '', 'table contribution'
    UNION ALL SELECT '014', 'table',   'contribution_fichier', '', 'table contribution_fichier'
    UNION ALL SELECT '015', 'table',   'periode', '', 'table periode'
    UNION ALL SELECT '015', 'table',   'periode_media', '', 'table periode_media'
    UNION ALL SELECT '015', 'retiree', 'repere', 'periode', 'periode retirée de repere'
    UNION ALL SELECT '016', 'table',   'zone_livraison', '', 'table zone_livraison'
    UNION ALL SELECT '016', 'colonne', 'commande', 'prix_unitaire', 'prix_unitaire sur commande'
    UNION ALL SELECT '016', 'colonne', 'commande', 'frais_livraison', 'frais_livraison sur commande'
    UNION ALL SELECT '016', 'colonne', 'commande', 'zone_id', 'zone_id sur commande'
    UNION ALL SELECT '017', 'table',   'point_de_vente', '', 'table point_de_vente'
  ) AS o
UNION ALL
SELECT '007', 'catégories du brief sur actualite',
       (SELECT COUNT(*) FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = 'actualite'
           AND column_name = 'categorie' AND column_type LIKE '%conference%')
UNION ALL
SELECT '009', 'repères en base (7 après l''amorce)', (SELECT COUNT(*) FROM repere)
UNION ALL
SELECT '016', 'statut « confirmée » sur commande',
       (SELECT COUNT(*) FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = 'commande'
           AND column_name = 'statut' AND column_type LIKE '%confirmee%')
UNION ALL
SELECT '016', 'zones de livraison amorcées (16 attendues)', (SELECT COUNT(*) FROM zone_livraison)
UNION ALL
SELECT '017', 'points de vente amorcés (3 attendus)', (SELECT COUNT(*) FROM point_de_vente)
ORDER BY 1, 2;
```

**Elle s'appuie sur `DATABASE()` : la base doit être sélectionnée avant de la
lancer.** Dans phpMyAdmin, cliquer d'abord la base dans le panneau de gauche,
puis l'onglet SQL. Lancée depuis l'onglet SQL du serveur, ou depuis
`information_schema`, elle inspecte cette base-là et rend une liste de tables
système qui n'a rien à voir — le piège ne dit pas son nom, il rend un résultat.

**Les lignes `zone_livraison` et `point_de_vente` ne s'exécutent que si leur
table existe** : sur une base où `016` ou `017` n'est pas encore jouée, la
requête entière échoue sur elle. C'est voulu — un contrôle qui rendrait « 0 »
ferait croire que la table est vide alors qu'elle n'existe pas, et les deux se
corrigent différemment.

`007`, `009`, `016` et `017` ne se contrôlent pas comme les autres, et les
lignes le disent : `007` ne crée ni table ni colonne, il élargit un `ENUM` — le
contrôle cherche donc `conference` dans le type de `actualite.categorie`, et
`016` fait de même avec `confirmee` dans `commande.statut`. `009`, `016` et
`017` rendent en outre un décompte plutôt qu'un `1` : ils versent des données —
sept repères, seize zones de livraison, trois points de vente.

Depuis la bascule de collation (voir §2), les fichiers SQL se chargent aussi
bien sous MySQL que sous MariaDB — il n'y a plus de ligne à corriger avant
d'envoyer.

**Côté fichiers**, quatre points qu'un dépôt ne règle pas tout seul, et qui
valent pour tout déploiement futur :

- **`medias/` doit exister et être accessible en écriture** par le serveur.
  Son `.htaccess` doit partir avec, c'est lui qui empêche l'exécution de ce qui
  y sera déposé — attention aux clients FTP qui masquent les fichiers commençant
  par un point.
- **`config/config.local.php` est ignoré par git et ne s'envoie jamais** : il se
  crée une fois sur le serveur, à partir de `config.local.exemple.php`, avec les
  identifiants de production, `'debug' => false` et surtout
  `'url' => 'https://www.philippeyace.ci'`. Sans cette dernière valeur,
  `canonical` et `og:image` retombent sur l'en-tête `Host` de la requête, que le
  client choisit.

  **C'est fait : le fichier est en place sur le serveur depuis le 10 septembre,
  avec les identifiants de production.** La première bascule est donc derrière
  nous, et avec elle sa seule contrainte d'ordre — il fallait créer ce fichier
  *avant* d'envoyer le nouveau `config/config.php`, sans quoi le serveur se
  retrouvait sans identifiants. Le prochain envoi n'a plus à s'en soucier :
  `config.php` part comme n'importe quel autre fichier, et **il n'y a plus
  jamais rien à éditer en ligne.**

  Restent deux valeurs du même fichier que le dépôt ne peut ni renseigner ni
  vérifier, et qu'il faut confirmer une fois sur le serveur : `'debug' => false`
  et `'url' => 'https://www.philippeyace.ci'`. La première se constate en
  provoquant une erreur — aucune trace ne doit paraître ; la seconde en lisant
  la source d'une page en ligne, où `<link rel="canonical">` doit porter le
  domaine réel.

  S'il manque, le site ne rend plus une demi-page : `src/bootstrap.php` arrête
  tout et sert `templates/maintenance.php` en 503 avec un `Retry-After`. Un 500
  répété finit par désindexer ; un 503 dit aux moteurs de repasser.

  **C'est le seul réglage que le dépôt ne peut jamais renseigner ni vérifier**,
  et le seul dont l'absence ne se voit pas à l'œil : les pages s'affichent
  normalement, seules les adresses canoniques sont fausses. Le contrôle se fait
  en lisant la source d'une page en ligne — la balise `<link rel="canonical">`
  doit porter le domaine réel.
- **Les coordonnées publiques sont dans `config/config.php`**, donc dans le
  dépôt : boîte postale, adresse du siège, courriel, téléphone, domaine. Elles
  alimentent la page Contact, les mentions légales et le pied de page. Les
  corriger se fait à un seul endroit ; les surcharger sur le serveur reste
  possible par `config.local.php`, qui est fusionné par-dessus.

  **Et c'est justement le piège du prochain envoi** : si le `config.local.php`
  du serveur porte une clé `contact`, elle recouvrira les nouvelles
  coordonnées et la page Contact continuera d'afficher l'adresse de Cocody.
  `array_replace_recursive` fusionne clé par clé — il faut donc vérifier ce que
  le fichier du serveur contient avant de conclure que l'envoi n'a pas pris.
- **`reference/` n'a rien à faire en production** : 70 Mo verrouillés en 403,
  à exclure explicitement de la règle de déploiement.
- **`documentation/` non plus** : le manuel de l'éditeur se lit depuis le dépôt,
  pas depuis le serveur. Il porte le même `.htaccess` en `Require all denied` —
  il décrit l'administration du site et donne l'adresse de son entrée.

### Le back-office, lot par lot

Le back-office est livré par lots, validables l'un après l'autre. **Les sept
sont posés**, et un huitième écran s'est ajouté hors plan avec le lot public F4
— la boîte de réception du formulaire de contact, conséquence directe du choix
de stocker les messages plutôt que de les envoyer par courriel (voir §2).

Pendant la construction, les entrées non encore écrites restaient visibles dans
la barre latérale, verrouillées : la forme finale de l'outil se voyait dès le
premier lot. Il n'en reste aucune — le mécanisme, lui, reste en place pour la
suite, et c'est par lui que « Messages » a pris place sous « Témoignages ».

Les lots D et E ont été coupés en deux en cours de route. Ce n'est pas un
changement de plan : la médiathèque pèse à elle seule autant que tout le lot C,
et la mêler à la modération aurait donné une livraison qu'on ne peut pas
valider d'un bloc.

| Lot | Objet | État |
|---|---|---|
| **A** | Ossature — thème élagué, mise en page, barre latérale, routage `/cmsadmin/` | livré |
| **B** | Authentification — connexion, session, CSRF, validation, garde de route | livré |
| **C** | Contenus — actualités, événements, repères | livré |
| **D1** | Modération — file des témoignages | livré |
| **D2** | Médiathèque — téléversement avec contrôle de type réel, vignettes | livré |
| **E1** | Pilotage — compteurs réels du tableau de bord, fiche technique de l'ouvrage | livré |
| **E2** | Comptes et commandes | livré |
| **—** | Boîte de réception des messages — venue avec le lot public F4 | livré |

Ce que le **lot A** pose : `src/bootstrap.php` (amorçage partagé par les deux
contrôleurs frontaux), `src/Core/Admin.php` (préfixe d'URL déduit de
l'emplacement réel du dossier — renommer `cmsadmin` ne demande aucune retouche —
et arborescence du menu), `Router::introuvable()` pour que l'admin ait sa propre
404, et un quatrième argument à `View::render` choisissant la mise en page. Le
site public n'est pas touché : ses trois pages et sa 404 rendent à l'octet près
comme avant.

Le lot A ne lit ni n'écrit aucune donnée, donc aucune route n'est encore
protégée. La garde arrive au lot B, avec la session.

Ce que le **lot D2** pose : `src/Core/Televersement.php` (réception d'un fichier
— type réel, plafonds, nom fabriqué, vignette, suppression), `App\Model\Media`,
`MediaController` et ses six routes, la planche et la fiche d'image, le
sélecteur d'illustration des actualités et des événements, et
`sql/003_media.sql`. Le détail des barrières est au §2, « La médiathèque ».

Deux corrections de voisinage sont passées avec lui : la confirmation de
suppression a quitté `js/listes.js` pour `js/admin.js` — la médiathèque supprime
depuis une planche et depuis une fiche, ni l'une ni l'autre n'étant un tableau —
et l'entrée « Médiathèque » du menu est remontée sous « Contenus ».

Ce que le **lot E2** pose : `App\Core\Paiement` (la passerelle en un seul
endroit), `App\Model\Commande` et son écran de suivi, `CompteController` et la
gestion des comptes, `Auth::exigerAdmin()` avec la 403 qui va avec, le filtrage
du menu par rôle, et `sql/004_commande.sql`. Le détail est au §2, « Les commandes
et la passerelle » et « Les comptes ».

C'est le lot qui introduit la **distinction de rôles** : jusque-là, tout compte
connecté pouvait tout faire. Deux écrans en dépendent désormais, et la garde vaut
en lecture comme en écriture — vérifié, un éditeur qui poste sur
`/comptes/{id}/actif` obtient 403 et la base ne bouge pas.

### Le site public, lot par lot

Même méthode que pour le back-office : des tranches validables l'une après
l'autre, dans l'ordre où les dépendances tombent. Le découpage était une
proposition au moment où il a été écrit ; **les quatre lots sont livrés**.

| Lot | Objet | État |
|---|---|---|
| **F1** | Témoignages — page publique, formulaire de dépôt, aperçu sur l'accueil | livré |
| **F2** | Actualités — liste, détail par slug, revue de presse | livré |
| **F3** | Galerie/Archives avec visionneuse, et Événements | livré |
| **F4** | Contact et mentions légales | livré |

**F1 en premier, et ce n'était pas un hasard** : c'était la seule tranche
verticale complète qui restait — écriture, validation, modération, affichage —
et elle portait la plomberie que les suivantes ont reprise. F2 et F3 n'ont fait
que lire ce que le back-office remplissait déjà ; F4 a repris le formulaire de
F1, dont le barème de débit était même déjà déclaré.

**Ce que F2 a laissé derrière lui**, et dont F3 a hérité sans avoir à l'écrire :
`App\Core\DateLisible` pour les dates en français, `View::paragraphes` pour les
corps de texte saisis en clair, l'image de partage choisie par page, et le
patron d'une page publique adossée à une entité — liste filtrée par lien, fiche
par slug, 404 pour un brouillon. Les événements ont suivi exactement la même
forme, et la prévision s'est vérifiée : F3 n'a eu à inventer que ce qui lui est
propre — la trame de la planche, la visionneuse, les heures et les intervalles
de dates, la seconde taille d'image.

**Ce que F3 laisse à son tour :** les tailles dérivées et le `srcset` de
`App\Model\Media`, les heures et intervalles de `DateLisible`, et une visionneuse
qui ne tient à rien d'autre qu'à une liste de tuiles — elle resservira le jour
où une fiche portera plusieurs images.

**F4 a tenu sa promesse à une exception près.** Le formulaire de contact est
bien celui des témoignages, repris tel quel jusqu'au barème de débit déjà
déclaré. Ce qui n'était pas prévu, c'est la destination des messages : les
envoyer par courriel aurait été plus court, mais `mail()` échoue en silence sur
un mutualisé. Ils sont donc stockés, et le back-office a gagné un écran.
Les quatre lots publics sont livrés.

Restent en dehors de ce découpage, et ce sont les deux seules pages publiques
qui manquent : **Héritage** (§4.5), qui n'attend rien de technique mais tout de
la matière éditoriale, et la **Boutique** (§4.9), qui est le tunnel de commande
— voir ci-dessous.

### Prochaines étapes, dans l'ordre

Le back-office et la couche formulaire, qui occupaient les deux premières places
de cette liste, sont faits. Ce qui restait du cahier des charges d'origine :

1. **Héritage** (§4.5), la dernière page publique hors boutique. Rien n'y bloque
   techniquement : elle attend la matière éditoriale, et elle seule.
2. **La boutique et son tunnel de commande** — la passerelle est arrêtée
   (`carte.abidjan.net`, voir §2) et décrite en un seul endroit. C'est désormais
   le seul chantier de code qui reste : la page publique de vente, puis le
   tunnel, qui créera les commandes que l'écran de suivi attend depuis le lot E2,
   avec leur code de transaction. Indépendant du point 1.
3. **Phase 3 du CDC** — newsletter, recherche interne, multilinguisme.

**Ces trois points sont repris et réordonnés par le brief du 7 septembre.** Ils
n'ont pas disparu — Héritage y gagne dix sujets au lieu d'une page, la phase 3
devient exigible et non plus optionnelle — mais ils ne suffisent plus à décrire
la suite. **La feuille de route qui fait foi est celle du §9.**

**La navigation, maintenant que toutes les pages existent.** Les entrées qui
pointaient sur des ancres de l'accueil — Actualités, Archives — mènent à leurs
pages, et le pied porte la carte complète : revue de presse, événements,
contact, mentions légales. La barre du haut garde ses quatre entrées et le
bouton de commande ; **Témoignages, Événements et Contact n'y figurent pas**, et
c'est la question de fond qui reste ouverte — elle se tranchera mieux
maintenant que le site est complet qu'elle ne se serait tranchée au début.
S'y ajoute, inchangée, celle d'ouvrir ou non la saisie manuelle d'une commande,
pour celles qui se prendraient au téléphone ou en dédicace (voir §6).

### Ce qui bloque, et sur qui

**Les contenus et l'état civil de l'éditeur, et eux seuls désormais.** Le
logotype, qui ouvrait la liste des livrables attendus au §5, est arrivé et
intégré ; il ne reste plus rien de technique en attente d'un tiers.

Tout le texte éditorial du site est provisoire et balisé comme tel, et **aucune
ligne ne peut être publiée sans validation de l'éditeur** — Yacé est une figure
historique réelle. S'y ajoutent les visuels d'archives — le portrait de la
biographie est arrivé, il attend son crédit ; les autres cadres d'attente sont
toujours en place —, la fiche technique de l'ouvrage — six de ses huit valeurs
sont vides — et l'adresse publique en configuration sur le serveur, à vérifier
plutôt qu'à poser : le domaine est connu, `https://www.philippeyace.ci`, mais il
ne peut pas vivre dans le dépôt, donc rien ici ne peut dire s'il y est. Le
contrôle tient en une ligne — voir « Ce qui est en ligne » plus haut.

**Depuis le lot F4, une part de ce qui manque est légale et non éditoriale** :
la page des mentions est écrite, mais l'identité de la structure éditrice, son
immatriculation, le directeur de la publication et l'hébergeur y restent à
fournir. La loi impose de les publier, et ce sont les seules informations du
site qu'aucun travail technique ne peut produire. Voir §5 pour la liste
complète, dimensions comprises.

## 7 bis. Le manuel de l'éditeur

**Le back-office se prend en main sans nous.** `documentation/manuel-administration.html`
est un manuel complet destiné à l'équipe éditoriale, écrit sans un mot de
technique : se connecter, publier, déposer un fichier, modérer, ouvrir la
boutique. Il porte la charte du site — mêmes jetons, mêmes filets, Bodoni et
Jost — et se lit dans un navigateur.

**Il documente ce que le code fait réellement**, et c'est ce qui lui donne sa
valeur : les formats et les poids acceptés, les dimensions attendues à chaque
emplacement, et surtout **les neuf règles qui font refuser une publication** —
la source d'un repère, les bornes d'une période, le crédit d'une image, la date
d'une actualité. Elles étaient jusqu'ici dispersées dans neuf contrôleurs et ne
se découvraient qu'en butant dessus.

| Fichier | Ce que c'est |
|---|---|
| `documentation/manuel-administration.html` | La source de vérité. S'ouvre dans un navigateur, s'imprime telle quelle. |
| `documentation/generer-docx.py` | Produit la version Word. Son en-tête donne les deux commandes — celle du `.docx`, et celle du PDF tiré de la page HTML par Chrome. |

**Les exports ne sont pas versionnés.** Le `.docx` et le PDF se régénèrent en
une commande, et un binaire de 600 Ko dans un dépôt déployé par FTP partirait
sur le serveur pour rien.

**Il vieillira avec le site.** Toute règle de publication ajoutée ou modifiée
doit y passer, faute de quoi il dira le contraire de ce que l'écran fait.

---

## 8. Couverture du cahier des charges

Accueil (§4.1) — **complet** : hero slider, accroche, aperçu du livre, teaser
biographie, frise de repères, témoignages, actualités, CTA commande, points de
vente.

Le livre (§4.2) — **complet** : résumé long, mot de l'éditeur, fiche technique,
sommaire, extrait, feuilletage, où acheter — les points de vente sont
administrables depuis le lot G12, et attendent leurs enseignes. L'auteur (§4.3) y est traité en section
plutôt qu'en page dédiée, faute de matière ; à détacher dès que le contenu existe.

Biographie (§4.4) — **complet** : contexte historique, parcours découpé en
périodes administrables — chacune à son adresse, illustrée, portant les jalons
de la frise et les pièces du fonds de ses années (lot G10) —, frise
chronologique filtrable par période et dépliable, citations, galerie de
portraits. *(Les cinq chapitres écrits en dur et leur sommaire d'ancres ont
disparu avec ce lot : ils sont versés en brouillon dans l'écran des périodes.)*

Actualités/Presse (§4.7) — **complet** : la liste filtrable par catégorie, la
fiche par slug avec son illustration créditée et son balisage `NewsArticle`, et
la revue de presse par millésime. L'accueil en montre les trois dernières.

Témoignages (§4.8) — **complet** : page publique, formulaire de dépôt, file de
modération, et l'aperçu des trois derniers validés sur l'accueil. La chaîne
entière tourne, du visiteur qui écrit au modérateur qui décide.

Galerie/Archives (§4.6) — **complet** : la planche filtrable par catégorie, sa
visionneuse au clavier, la légende et le crédit sur chaque pièce. L'accueil en
montre les quatre premières.

Événements (§4.10) — **complet** : l'agenda en deux temps, la fiche par slug
avec son balisage `Event`, et les rendez-vous annulés qui restent affichés et le
disent.

Contact (§4.11) — **complet** : coordonnées, formulaire de contact avec ses
quatre barrières, et la boîte de réception côté administration.

Mentions légales (§4.12) — **complet quant à la page**, et vérifiable : les
sections données, cookies et services tiers décrivent le comportement réel du
site. Manquent quatre informations d'état civil que seule la structure éditrice
peut fournir (voir §5).

Reste à construire, côté public — Héritage (§4.5) et Boutique (§4.9). La
première attend du texte, la seconde est le tunnel de commande.

La **boutique (§4.9)** est le cas à part : son écran de suivi est livré et la
passerelle est arrêtée (`carte.abidjan.net`), mais le tunnel de paiement reste à
écrire — il suppose la page publique. Tant qu'il n'existe pas, aucune commande
n'entre en base et l'écran reste vide, ce qui est normal et non un défaut.

La fiche technique de l'ouvrage (§4.2) est éditable depuis l'admin mais **six de
ses huit valeurs sont vides** : elles font partie des contenus attendus de
l'éditeur (voir §5).

Transverses (§5) — **partiel** : responsive, accessibilité AA vérifiée par mesure,
structure sémantique, métadonnées, schema.org `Book`/`Person`, lazy loading et
partage social — canonical, Open Graph, Twitter Card — sont en place. Newsletter,
recherche interne et multilinguisme restent à faire ; ils sont désormais
réalisables, le socle dynamique étant en place.

**Ce §8 couvre le cahier des charges d'origine, et lui seul.** Le brief du
7 septembre élargit le périmètre — archives cataloguées, héritage, contributions
du public, bilinguisme — et sa propre couverture est tenue au **§9**.

### Le socle applicatif : décision prise

Le CDC exige un back-office utilisable sans intervention technique, des formulaires
avec modération, des commandes et une recherche interne — rien de tout cela ne
tenait en pages statiques.

**Choix retenu : PHP structuré à la main** (contrôleur frontal, routeur, PDO,
gabarits), plutôt qu'un framework ou un CMS. Motif : garder la racine web sur
`livreyace/` — Symfony et Laravel imposent une racine en `public/` — et rester dans
la continuité du site de référence. Contrepartie assumée : le back-office est à
écrire intégralement, et c'est l'essentiel de la charge restante.

---

## 9. Le nouveau périmètre — brief du 7 septembre 2026

### Où en est ce périmètre

**État au 8 septembre 2026.** Les treize lots sont écrits, testés et poussés. **Leurs neuf migrations sont jouées en
production ; leur code ne l'est pas** — tant qu'il ne l'est pas, ces tables sont
en place et personne ne les voit. Le §7 porte l'état daté et la requête qui le
vérifie.

| Lot | Objet | État |
|---|---|---|
| G0 | Gains immédiats — plan du site, frise branchée, menu | livré |
| G1 | Socle bilingue — structure seule, aucun contenu traduit | livré |
| G2 | Le livre complété — préface réglable, page auteur | livré |
| G4 | Modèle d'archives — la notice et ses fichiers | livré |
| G4b | Médiathèque cherchée et paginée | livré |
| G5 | PDF, audio, téléchargement | livré |
| G6 | Bibliothèque des discours | livré |
| G7 | Héritage | livré |
| G8 | Contribuez aux archives — quarantaine et modération | livré |
| G9 | Recherche transversale, fil d'Ariane, 404 qui rattrape | livré |
| G10 | Biographie par périodes — une adresse par période, frise et fonds rattachés | livré |
| G3 | Boutique et tunnel de commande — paiement à la livraison, boutique fermée | livré |
| G11 | Version anglaise — mécanisme complet, **anglais ouvert** | livré |
| G12 | Points de vente — la grille « Où se procurer l'ouvrage » sort du gabarit | livré |

**G3 est écrit, et la boutique est fermée.** Le commanditaire a confirmé le
7 septembre qu'aucune date de sortie n'est annoncée ; le lot a donc été livré
avec un interrupteur plutôt qu'avec une date. Prendre des commandes en paiement
à la livraison pour un ouvrage qui n'existe pas encore, c'est promettre une
remise qu'on ne peut pas tenir. **L'ouverture tient en une case à cocher et un
prix**, tous deux à l'écran Paramètres.

**Onze migrations à jouer, dans cet ordre**, et une seule fois. **Les neuf
premières sont jouées en production depuis le 8 septembre ; `016` et `017` ne le
sont pas** — le contrôle est au §7. La liste reste ici pour une installation neuve, et pour dire ce que
chacune apporte.

| Fichier | Lot | Ce qu'il apporte | |
|---|---|---|---|
| `sql/007_actualite_categories.sql` | G0 | quatre catégories d'actualités | jouée |
| `sql/008_repere_avant.sql` | G0 | la colonne `en_avant`, qui met un repère sur l'accueil | jouée |
| `sql/009_repere_amorce.sql` | G0 | les sept repères de la frise — **seulement si `repere` est vide** | jouée |
| `sql/010_traduction.sql` | G1 | la table de traduction | jouée |
| `sql/011_archive.sql` | G4 | `archive` et `archive_media` | jouée |
| `sql/012_media_famille.sql` | G5 | la colonne `famille` sur `media` | jouée |
| `sql/013_heritage.sql` | G7 | `heritage` et `heritage_media` | jouée |
| `sql/014_contribution.sql` | G8 | `contribution` et `contribution_fichier` | jouée |
| `sql/015_periode.sql` | G10 | `periode` et `periode_media`, l'amorce des cinq chapitres, et la colonne `repere.periode` qui **disparaît** | jouée |
| `sql/016_boutique.sql` | G3 | `zone_livraison` et son amorce, quatre colonnes sur `commande`, deux statuts de plus, le prix numérique | **à jouer** |
| `sql/017_point_de_vente.sql` | G12 | `point_de_vente`, et l'amorce des trois villes qui vivaient dans les gabarits | **à jouer** |

**Deux points de déploiement qu'aucune migration ne règle :**

- **`quarantaine/` doit exister, être accessible en écriture, et son
  `.htaccess` doit partir avec.** C'est lui qui empêche que les envois du
  public soient servis. Les clients FTP masquent les fichiers commençant par un
  point, et son absence ne se verrait pas.
- **Le `.htaccess` de la racine a changé** : il exclut désormais `quarantaine`
  de la réécriture. Celui de `medias/` aussi : il sert les PDF en pièce jointe.

### Ce que le brief déplace

Le site a été construit comme **la campagne d'un livre** : une sortie, des
dédicaces, une revue de presse, un bouton pour commander. Le brief lui demande
de devenir **un fonds patrimonial dont le livre n'est que le premier
événement**, la page Facebook gardant le rôle d'interaction. Ce n'est pas un
ajout de rubriques, c'est un changement de nature, et il a une traduction
technique unique :

> Jusqu'ici les contenus du site sont **des pages** — on les écrit, on les
> publie, on les lit. Désormais ce sont **des pièces cataloguées** : datées,
> situées, créditées, indexées, et chacune à son adresse propre, puisque chaque
> photographie, chaque discours, chaque document doit pouvoir se partager seul.

**Rien de ce qui est écrit n'est à jeter.** Les actualités sont déjà exactement
ce que le brief demande ; le back-office, la modération, le téléversement
contrôlé, les formulaires anti-robots, le partage social et l'accessibilité
servent tels quels le nouveau périmètre. Le chantier porte sur quatre points :
les archives, l'héritage, la biographie et le socle bilingue.

### Le menu demandé

```
ACCUEIL | LE LIVRE | BIOGRAPHIE | ARCHIVES | HÉRITAGE | ACTUALITÉS | COMMANDER
```

Contre les quatre entrées actuelles plus le bouton de commande. Deux manquent —
**Héritage**, qui n'existe pas, et **Accueil** en clair, le logotype servant
seul de retour aujourd'hui : suffisant sur un site de campagne, insuffisant sur
un site de référence où l'on arrive par un lien profond. Sept entrées tiendront
mal dans la barre mobile actuelle ; le menu déroulant est à revoir avec.

Cela tranche au passage la question laissée ouverte au §7 : **Témoignages,
Événements et Contact n'entrent pas dans la barre.** Les témoignages
appartiennent à Héritage, les événements se rattachent au livre et gardent leur
agenda, le contact reste au pied.

**Le menu se construit en trois temps**, et c'est pour cela qu'il n'a pas de lot
à lui : **G0** pose l'entrée Accueil et rouvre la barre mobile pour sept entrées
— le gabarit actuel en tient quatre plus un bouton ; **G3** rend le bouton
Commander effectif, il pointe aujourd'hui sur une ancre ; **G7** active l'entrée
Héritage, qui ne peut pas précéder la rubrique qu'elle ouvre.

### Lecture du brief, chapitre par chapitre

Onze chapitres — les dix du brief, plus l'exigence bilingue ajoutée à la note
d'analyse. Deux sont acquis, cinq à compléter, quatre à construire.

| # | Chapitre | État | Ce qui manque |
|---|---|---|---|
| 1 | Menu principal | à compléter | entrée Accueil et barre mobile (G0), bouton Commander effectif (G3), entrée Héritage (G7) |
| 2 | Le livre | à compléter | préface et sa mise en avant, page auteur à URL propre, rattachement presse et événements de lancement |
| 3 | Biographie et frise | **acquis** | livré aux lots G0 (frise branchée) et G10 (périodes, adresses, illustration, rattachement au fonds) ; attend le découpage éditorial |
| 4 | Archives | à construire | six catégories, champs de catalogue, une adresse par pièce, PDF/audio/vidéo, page de discours, recherche |
| 5 | Contribuez aux archives | **acquis** | livré au lot G8 |
| 6 | Héritage | **acquis** | livré au lot G7 ; attend la matière éditoriale |
| 7 | Actualités | **acquis** | quatre catégories à ajouter à l'énumération |
| 8 | Page d'accueil | **acquis** | validée telle quelle ; suivra les lots |
| 9 | Partage et référencement | **acquis** | complété par G0 (plan, robots), G4 (adresse par pièce, balisage) et G9 (recherche, fil d'Ariane) |
| 10 | Back-office du fonds | à compléter | dépôt multiple, recherche et pagination de la médiathèque, sauvegarde |
| 11 | Architecture FR \| EN | à compléter | préfixe de langue, tables de traduction, `hreflang`, textes sortis des gabarits |

**Le chapitre 3 méritait d'être lu deux fois, et il l'a été en deux temps.** La
table `repere` et son écran d'administration existaient, complets, depuis le
lot C — mais **les deux frises publiques étaient du HTML écrit en dur**
(`templates/pages/accueil.php`, `templates/pages/biographie.php`), et ce qu'un
éditeur saisissait n'apparaissait nulle part. Le lot G0 les a branchées. Le
lot G10 a fait le reste : les périodes en base, une adresse par période, et
leur datation comme seul lien vers la frise et vers le fonds. **Ne manque plus
que le découpage lui-même**, qui est éditorial et dû par l'auteur.

### Les six décisions — arrêtées

Ce sont les choix qu'il est coûteux de reprendre après coup. **Le commanditaire
les a déléguées le 7 septembre 2026** ; elles sont donc arrêtées telles
qu'exposées ci-dessous, et c'est sur elles que les lots s'écrivent. Les rouvrir
reste possible, mais chacune dit ce qu'elle coûterait.

**1. Séparer le fichier de la notice d'archive.** Aujourd'hui, une ligne de
`media` = un fichier image. C'est suffisant pour une galerie, pas pour un fonds.
Un discours de 1980, c'est une notice unique portant un contexte historique, une
vidéo, un enregistrement, une transcription et un document scanné : cinq choses
sous une seule adresse. Deux niveaux, donc — la **notice**, qui se catalogue, se
date, se situe, se cite et se partage, et les **fichiers** qu'elle porte, un ou
vingt. Ce seul choix règle la galerie photo, le discours, le document, la
correspondance et l'exigence « une URL par pièce ». C'est aussi le seul qu'on ne
rattrape pas : tout le reste des archives en dépend.

**2. Ne pas héberger la vidéo.** Une heure d'archive pèse plusieurs gigaoctets,
demande un transcodage en plusieurs qualités et saturera la bande passante d'un
mutualisé dès la première consultation groupée. Les vidéos vont sur une chaîne
YouTube dédiée et sont intégrées dans les notices : le site garde la notice, le
contexte, la transcription et la citation — ce qui a de la valeur — YouTube
porte les octets et devient un canal de découverte de plus vers
`philippeyace.ci`. L'audio et les PDF, légers et mal citables ailleurs, restent
hébergés ici.

**3. Arrêter le plan d'adresses maintenant, et ne plus y toucher.** Le brief
prévoit QR codes, filigranes, dossier de presse et peut-être une mention dans le
livre. **Une adresse imprimée l'est pour toujours.** Figer dès maintenant la
forme — `/archives/discours/1980-cloture-cinquieme-legislature` —, poser des
adresses courtes et stables pour les supports imprimés, et s'imposer la règle :
toute adresse qui change laisse une **redirection permanente** derrière elle.
C'est une discipline, pas une fonctionnalité, et elle ne coûte rien si elle est
prise au départ.

**4. Les contributions du public arrivent en zone fermée.** Le brief est clair :
rien n'est publié automatiquement. La contrepartie technique ne va pas de soi —
**un fichier envoyé par un inconnu ne doit pas atterrir dans `medias/`**, même
non publié : le dossier est servi par Apache, et qui devine un nom télécharge le
fichier. Les contributions vont donc dans un dossier de **quarantaine hors
racine web** et ne rejoignent le fonds qu'après validation explicite, avec
plafond de taille, nombre de fichiers borné, contrôle du type réel, et une case
de cession de droits horodatée.

**5. Bilingue : la structure maintenant, la traduction plus tard.** *(Posé au
lot G1.)* Il ne s'agit
pas de traduire le site mais de **le rendre traduisible sans le rouvrir**. Trois
choix qui se prennent une fois : les adresses en `/en/…` plutôt qu'en
sous-domaine ou en paramètre — c'est ce que Google attend et cela ne touche pas
au domaine ; une **table de traduction unique** plutôt que des colonnes
`titre_en` ajoutées partout, sinon chaque langue future est une migration ; et
les textes des gabarits sortis dans un fichier de langue, avec `hreflang` dès la
première page. **Aucun contenu n'est traduit avant que la communication
internationale ne démarre** — on pose la tuyauterie, pas l'eau. Deux à trois
jours maintenant ; après coup, c'est le routeur, tous les modèles et les
dix-huit gabarits à rouvrir.

**6. Une politique de sauvegarde du fonds.** Le site va recevoir des pièces qui,
pour certaines, n'existeront nulle part ailleurs sous forme numérique : une
correspondance prêtée par une famille, une photographie confiée, un
enregistrement retrouvé. **`medias/` n'est sauvegardé nulle part** — il n'est pas
versionné, et un hébergement mutualisé n'est pas un service d'archivage. Qui
garde une copie, où, à quelle fréquence, et en conservant **les originaux à
pleine résolution** et non les seules dérivées que le site affiche. Cela relève
autant de l'organisation que du code, mais c'est la condition pour que le mot
« patrimonial » soit tenu.

### Feuille de route

Même méthode que pour les lots précédents : des tranches validables l'une après
l'autre. Elle se lit en **deux pistes parallèles** — ce que la sortie du livre
exige à court terme, ce que le fonds demande dans la durée. Les charges sont
indicatives, pour un développeur, contenus fournis et validés ; elles n'incluent
ni la numérisation, ni la saisie éditoriale, ni la traduction.

| Lot | Objet | Contenu | Charge |
|---|---|---|---|
| **G0** | Gains immédiats | `sitemap.xml` et `robots.txt` ; frise branchée sur `repere` ; catégories d'actualités élargies ; entrée Accueil et barre mobile rouverte à sept entrées ; vérification du domaine en configuration | **livré** |
| **G1** | Socle bilingue | routeur préfixé, table de traduction, `hreflang`, liens du chrome. Structure seule, aucun contenu traduit | **livré** |
| **G2** | Le livre, complété | préface et sa mise en avant, page auteur à URL propre, rattachement de la revue de presse et des événements de lancement | **livré** |
| **G3** | Commander | tunnel en **paiement à la livraison**, zones de livraison par pays/ville/commune à frais hérités, boutique ouvrable d'une case ; le paiement en ligne est une phase 2 | **livré** |
| **G4** | Modèle d'archives | notice et fichiers (décision 1), six catégories, champs de catalogue, page par notice, recherche, écran d'administration | **livré** |
| **G5** | Formats et lecteurs | PDF, audio, plafonds par famille, lecteur et téléchargement de l'original | **livré** |
| **G6** | Bibliothèque des discours | l'index chronologique par décennie, et ce que chaque pièce porte | **livré** |
| **G7** | Héritage | cinq rubriques adossées aux données, une page par sujet, rattachement des témoignages | **livré** |
| **G8** | Contribuez aux archives | formulaire, quarantaine (décision 4), cession de droits, réception et validation au back-office | **livré** |
| **G9** | Recherche et navigation | recherche transversale, fil d'Ariane et `BreadcrumbList` partout, 404 qui rattrape | **livré** |
| **G10** | Biographie par périodes | les périodes en base, une adresse par période, frise illustrée et reliée aux archives | **livré** |
| **G11** | Version anglaise | interface sortie des gabarits, dates bilingues, écran de traduction du back-office, **anglais ouvert** ; reste la matière éditoriale | **livré** |

**Ordre recommandé — et suivi.** G0 et G1 d'abord, communs aux deux pistes.
Puis G2 et G3 pour la sortie du livre, G4 à G10 pour le fonds. G11 est fait et
**ouvert** ; la traduction des contenus se verse au fil de l'eau, sans nouvelle
livraison. G3 est fait et **fermé** : il s'ouvre le jour où l'ouvrage a une
date, sans livraison non plus.

**Une réserve sur cet ordre.** G4 est en seconde piste, mais **sa conception ne
peut pas attendre** : G5 à G10 en dépendent tous, et corriger le modèle après le
versement de plusieurs centaines de pièces serait douloureux. La décision 1 se
tranche maintenant, même si la construction vient après la sortie. À l'inverse,
G3 — le tunnel de commande — était le seul lot que rien n'appelait en
dépendance : il pouvait se placer où l'échéance commerciale l'exigeait. Il a
finalement été écrit en dernier, et livré fermé — ce qui revient au même que
de l'avoir écrit à temps, sans avoir eu à deviner la date.

### Lot G0 — livré

Le premier lot du nouveau périmètre. Rien n'en dépendait, rien ne le bloquait,
et il rend administrable ce qui ne l'était pas.

**La frise chronologique est branchée sur la base.** C'était l'anomalie la plus
coûteuse du site : `repere` et son écran d'administration existaient depuis le
lot C, mais les deux frises publiques portaient leurs dates en dur dans le
gabarit. Ce qu'un éditeur saisissait n'arrivait nulle part.

Trois choses en sont sorties, qui ne se voyaient pas avant :

- **`sql/009_repere_amorce.sql` verse les sept entrées qui étaient affichées**,
  mot pour mot, pour que le site ne perde rien au passage. Elles arrivent en
  `publie` parce qu'elles l'étaient déjà, de fait. Six des sept portent encore
  « à documenter » : elles sont désormais **corrigeables**, ce qui était tout
  l'objet du lot ; les corriger reste à faire, et c'est à l'éditeur.
- **Un repère sans notice ni source ne se déplie pas.** Le gabarit rendait un
  bouton pour chaque entrée ; sur une saisie réduite à une date et un titre,
  ce bouton n'ouvrait rien. La ligne reste, ce n'est plus un bouton — un
  contrôle qui n'ouvre rien ment au clavier comme à la souris.
- **L'accueil a gagné une colonne `en_avant`** (`sql/008_repere_avant.sql`).
  En branchant la frise, l'accueil s'est mis à montrer les quatre premières
  entrées chronologiques — dont deux notices vides — là où le gabarit affichait
  les quatre dates marquantes. Remplacer une règle implicite par une autre
  n'aurait fait que déplacer le problème : **c'est un choix éditorial**, il
  revient à l'éditeur, et une case sur la fiche du repère le lui donne. La
  liste des repères dit combien sont sur l'accueil, et signale une case cochée
  sur un brouillon — qui ne fait rien tant que rien n'est publié.

**`sitemap.xml` et `robots.txt` sont servis par le routeur**, pas posés à la
racine, et c'est ce qui décide de leur forme : le plan doit lister les
actualités et les événements publiés. Un fichier statique se périmerait à la
première publication, et le sitemap dirait alors à Google le contraire de ce
qu'on lui demande. Les conditions de publication y sont **les mêmes que celles
des pages** — une adresse listée mais rendue en 404 fait chuter la confiance
accordée au plan entier.

**Le routeur échappe désormais les segments littéraux d'un motif.**
`/sitemap.xml` est la première route du site à porter un point : sans
`preg_quote`, ce point valait « n'importe quel caractère » et `/sitemapaxml`
répondait la même chose. Sans conséquence ici, mais un motif de route qui ne
dit pas ce qu'il a l'air de dire finit par surprendre ailleurs.

**Quatre catégories d'actualités s'ajoutent** — conférence, reportage,
interview, archive retrouvée (`sql/007_actualite_categories.sql`). Pas de
« commémoration » : `hommage` la couvre, et deux cases pour une même chose
obligent l'éditeur à trancher entre elles à chaque saisie sans qu'aucune règle
ne le guide.

**« Accueil » est une entrée de menu à part entière**, et non plus le seul
logotype : sur un site de référence on arrive par un lien profond, et le retour
doit se nommer. Le panneau mobile se borne à la hauteur visible et défile en
dedans — à sept entrées, la dernière sortait de l'écran sur un téléphone bas.

**Trois migrations, dans cet ordre** (jouées en production le 8 septembre) :

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/007_actualite_categories.sql` | les quatre catégories d'actualités du brief |
| `sql/008_repere_avant.sql` | la colonne `en_avant`, et le rattrapage d'une base déjà saisie |
| `sql/009_repere_amorce.sql` | les sept repères qui étaient affichés en dur, mise en avant comprise |

`009` **ne se joue que sur une base dont la table `repere` est vide** : il n'a
aucun garde-fou contre le doublon, une ligne d'amorce n'ayant pas de clé
naturelle sur laquelle en poser un. `007` et `008` se contrôlent comme les
précédentes — voir la requête au §7.

**Ces deux fichiers ont échangé leurs numéros le 8 septembre**, et l'ordre est
la correction elle-même. L'amorce nomme `en_avant` dans son `INSERT` ; tant
qu'elle passait la première, elle s'arrêtait en production sur `#1054 Champ
'en_avant' inconnu dans field list`, la colonne n'arrivant qu'au fichier
suivant. Rien ne s'en voyait en local, où la base repart de `001_schema.sql` —
mis à jour dans le même commit que le lot, `en_avant` comprise : **le défaut ne
pouvait apparaître que sur une base installée avant G0, c'est-à-dire sur le
serveur seul.** La colonne se pose désormais avant qu'on écrive dedans.

### Lot G1 — livré

**Le site est bilingue par construction, et monolingue à l'écran.** C'est tout
l'objet du lot : ouvrir l'anglais ne demandera pas de rouvrir le routeur, les
modèles et les gabarits.

**L'anglais est déclaré mais fermé.** `App\Core\Langue::LANGUES` porte les deux
langues ; `'active' => false` sur l'anglais fait répondre 404 à `/en/`. Servir
des pages françaises sous `/en/` apprendrait aux moteurs que le site ment sur
son contenu, et c'est long à défaire. **Ouvrir l'anglais tiendra en un mot** —
`true` — une fois la matière traduite.

Les trois choix structurants, pris une fois :

- **`/en/le-livre`**, et non `en.philippeyace.ci` ni `?lang=en`. C'est la forme
  que Google recommande, elle ne demande rien au DNS ni au certificat.
- **Le français n'a pas de préfixe** : `/le-livre` reste `/le-livre`. Un `/fr/`
  ajouté après coup aurait rendu caduque chaque adresse déjà partagée, chaque
  QR code, chaque lien de la page Facebook — décision 3.
- **La langue vient de l'adresse, jamais de l'en-tête du navigateur.** Rediriger
  d'après `Accept-Language` donne deux contenus à une même adresse : le moteur
  en indexe un, l'utilisateur en voit l'autre.

**Une table de traduction pour tout le site**, et non une par entité
(`sql/010_traduction.sql`). L'autre forme donne des colonnes typées, mais coûte
**une migration par entité** — or le fonds va en créer plusieurs : notices
d'archives, discours, lieux de mémoire, périodes de la biographie. L'oubli d'une
seule ne se serait vu qu'en anglais, donc tard. Ici, une entité nouvelle est
traduisible le jour où elle existe, sans toucher au schéma.

**En français, la traduction ne fait rien et n'interroge rien.** Pas une
jointure, pas une requête : le coût du bilinguisme est nul tant que le site est
monolingue. Un champ non traduit garde sa valeur française plutôt que de
disparaître — une page anglaise incomplète reste lisible, et l'anglais pourra
s'ouvrir rubrique par rubrique au lieu d'attendre que tout soit traduit.

**Le routeur détache le préfixe en un seul endroit.** Les routes restent
déclarées une fois, sans préfixe : les déclarer deux fois aurait garanti qu'une
des deux séries finisse par manquer une adresse.

**Les liens du chrome passent par `Langue::chemin()`** — navigation et pied de
page. Un `href="/le-livre"` écrit en dur ramènerait le visiteur anglophone au
français sans le dire.

**Ce que G1 ne faisait pas, délibérément :** les textes des gabarits de page
n'étaient pas encore sortis dans un fichier de langue. C'était du travail
mécanique, page par page, sans conséquence architecturale, et il a été fait au
lot G11 — 533 clés. Ce qui devait être décidé au lot G1 l'était ; le reste
pouvait attendre sans coûter davantage, et n'a effectivement rien coûté de
plus.

**Vérifié en ouvrant l'anglais le temps d'un test** : `/en/` répond, `<html
lang="en">`, `hreflang` et `x-default` s'écrivent, le sitemap se dédouble en
`xhtml:link`, les liens du menu et du pied se préfixent, les champs traduits
sortent en anglais et les autres restent en français. L'anglais a ensuite été
refermé et les traductions de test effacées.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/010_traduction.sql` | la table de traduction, une pour tout le site |

### Lot G4 — livré

**La décision 1 est appliquée : une archive est une notice, pas un fichier.**

| Table | Ce qu'elle porte |
|---|---|
| `archive` | la **notice** — ce qui se catalogue, se date, se situe, se cite et se partage. Elle a un slug, donc une adresse |
| `archive_media` | ce qu'elle **porte** — un fichier, ou vingt |
| `media` | **inchangée** : elle reste le magasin de fichiers, avec son téléversement contrôlé et ses tailles dérivées |

`media` n'est pas touchée, et c'est délibéré : elle répond à « quels fichiers
ai-je déposés ? », la notice à « qu'est-ce que cette pièce, et d'où
vient-elle ? ». Les fondre aurait obligé à choisir entre les deux.

**Trois adresses, et la troisième est celle qui compte :**

```
/archives                        le fonds, ses six catégories, sa recherche
/archives/{categorie}            une catégorie, filtrable par année
/archives/{categorie}/{slug}     la notice — sa page, son adresse, son partage
```

C'est ce que le §9 du brief demande : chaque pièce se partage seule, là où la
visionneuse d'avant était une surimpression sans adresse. **Les clés de
catégorie sont les segments d'adresse** — `discours` donne `/archives/discours/…`
— et elles ne changeront plus : décision 3.

**Deux 404 qui ne vont pas de soi**, et qui protègent le plan d'adresses : une
catégorie inconnue répond 404 plutôt que la planche entière — l'adresse est
publique et durable, elle doit dire la vérité — et un slug demandé sous la
mauvaise catégorie répond 404 aussi, sans quoi la même pièce aurait deux
adresses valides et les moteurs y verraient un doublon.

**Les champs du catalogue** sont ceux du brief : titre, date, lieu,
description, personnes présentes, source, crédit, catégorie, fichiers — plus
les mots-clés, qui alimentent la recherche. La date est **double** : `date_texte`
s'affiche (« vers 1965 »), `annee` classe et filtre. Une archive est souvent mal
datée, et forcer une date exacte aurait fait inventer des dates.

**La bibliothèque des discours n'est pas une entité à part.** Le brief la
décrit comme une page réunissant contexte historique, vidéo, audio,
transcription et document : trois colonnes de texte sur la notice y suffisent
— `contexte`, `transcription`, `video_url`. Une table de plus pour trois
colonnes nullables aurait coûté une jointure à chaque lecture sans rien
apporter. L'audio et le document téléchargeable arrivent avec G5.

**La vidéo reste chez son hébergeur** (décision 2) : la notice porte une URL,
pas un fichier. Seul YouTube est reconnu, et l'écran de saisie **refuse** une
adresse qu'il ne sait pas intégrer — sans quoi la page publique afficherait un
cadre noir sans que personne ne le sache.

**La recherche est un `LIKE`, pas un index plein texte**, et c'est un choix :
`MATCH … AGAINST` ignore les mots de moins de quatre lettres et ceux qu'il tient
pour trop fréquents. Sur un fonds où l'on cherche « PDCI », un lieu ou un nom
propre, c'est exactement ce qu'il ne faut pas. Le coût — pas d'index — est sans
objet à quelques milliers de notices ; la question se reposera à cent mille.

**La provenance est exigée pour publier.** Une pièce sans crédit ni source reste
en brouillon : le fonds recevra des documents prêtés par des familles et des
organes de presse, et savoir de qui vient quoi n'est pas une formalité (CDC §6).

**Chaque notice porte ses données structurées**, du type qui lui correspond —
`Photograph`, `VideoObject`, `DigitalDocument`, `Article` — rattachées à la
personne et à la collection. Un `CreativeWork` générique n'aurait apporté aucun
des enrichissements qu'on cherche.

**Un bloc « citer cette archive »** donne le permalien en clair sur chaque
notice. Un fonds patrimonial se cite : l'adresse est ce qu'on recopie dans une
note de bas de page, un dossier de presse, un QR code.

**Le plan du site liste les notices**, avec une priorité plus haute pour les
discours — leur transcription porte le contenu le plus recherché.

**Ce que G4 ne fait pas :**

- **Le dépôt multiple de fichiers.** On coche les images déjà déposées dans la
  médiathèque ; les déposer se fait toujours une par une. C'est le point à
  reprendre en premier quand un vrai fonds sera versé.
- **L'ordre des fichiers par notice.** Il suit celui de la médiathèque, que
  l'éditeur règle déjà. Le premier fichier fait la vignette et l'image de
  partage — donc réordonner une notice se fait aujourd'hui depuis la
  médiathèque, ce qui est indirect.
- **La pagination.** La planche rend tout ce qui est publié, comme les autres
  listes du site. La question se reposera au-delà de la centaine de pièces.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/011_archive.sql` | les tables `archive` et `archive_media` |

### Lot G2 — livré

**La mise en avant de la préface est un réglage, pas un choix de gabarit.** Le
brief dit : « si la préface du Président de la République se confirme, nous
prévoirons une mise en avant spécifique ». Coder l'une des deux formes aurait
obligé à rouvrir la page le jour de la confirmation — et remonter une préface,
ce n'est pas déplacer un bloc, c'est refaire la hiérarchie de la page.

L'éditeur coche donc une case dans les Paramètres. Sans elle, la préface est une
section ordinaire de « Le livre ». Avec elle, le bloc remonte en tête de page,
passe sur fond sombre, et **un bandeau paraît sur l'accueil**. Même principe que
la mise en avant des repères (lot G0) : un choix éditorial appartient à
l'éditeur.

**Le développement n'a donc pas attendu la réponse du commanditaire**, et il n'y
avait aucune raison qu'il l'attende. Ce qui reste attendu — le texte, le nom et
la qualité du préfacier — bloque la publication, pas la construction.

**Une préface mise en avant sans nom est refusée à la saisie** : un bloc signé
de personne, en tête de la page la plus lue du site, n'aurait pas de sens. Même
règle que le sourçage des repères — ce qui paraît doit être attribuable.

**L'auteur a sa page**, `/auteur`, et non une ancre : le brief le demande, et
une ancre ne se partage ni sur un plateau, ni dans un dossier de presse. Elle
porte son balisage `Person` rattaché au `Book`, ce qui la fait remonter sur le
nom de l'auteur. **Elle répond 404 tant que le nom n'est pas renseigné**, et le
plan du site ne l'annonce pas : une adresse qui rend un gabarit creux se fait
indexer telle quelle. La section « L'auteur » de la page du livre en donne
l'aperçu et y renvoie.

**La revue de presse et les événements de lancement sont rattachés à la page du
livre**, comme le brief §2 le demande. Les deux existaient chacun à son adresse
sans que rien n'y mène depuis là. Chaque bloc disparaît quand il est vide : une
rubrique sans contenu ne dit rien de bon sur un site qu'on découvre.

**Aucune migration.** Tout passe par la table `parametre`, qui portait déjà la
fiche technique de l'ouvrage : huit clés s'y ajoutent, et l'écran des Paramètres
gagne un second bloc. Rien à jouer en production pour ce lot.

### Lot G4b — livré

**Correction préalable, parce qu'elle a orienté ce lot.** La dette écrite après
G4 affirmait que le dépôt de fichiers était unitaire et qu'il fallait le
reprendre en premier. C'était faux : le dépôt multiple existe depuis le lot D2
— `name="fichiers[]" multiple`, vingt fichiers par lot, avec la garde sur les
envois tronqués par `post_max_size`. Le vrai obstacle était ailleurs.

**`Media::listerPar()` rendait tout, sans limite ni recherche** — dans la
médiathèque comme dans le sélecteur de fichiers des notices. Tenable sur une
médiathèque vide, intenable sur le fonds qu'elle a vocation à porter : une
planche de trois mille vignettes ne s'ouvre pas.

- **La médiathèque est cherchée et paginée**, soixante par page. La recherche
  porte sur ce qu'un éditeur a en tête — titre, légende, crédit — et sur le nom
  du fichier, souvent la seule prise sur un scan qui vient d'être déposé. Le
  `WHERE` de la planche et celui de son décompte sont écrits une fois : deux
  formulations séparées finiraient par diverger, et une pagination qui compte
  autre chose que ce qu'elle affiche donne des pages vides à la fin.
- **Les filtres et la recherche voyagent** dans les liens de page et dans les
  onglets de catégorie. Une page de la planche s'ouvre dans un onglet, se met en
  favori, se partage entre deux éditeurs — d'où des liens et non des boutons.
- **Une page hors bornes est ramenée dans les bornes**, pas refusée : un lien
  vers la page 7 reste valide après une suppression qui ramène le fonds à cinq
  pages.
- **Le sélecteur de fichiers d'une notice est borné à deux cents dépôts**, les
  plus récents — ceux qu'on vient de déposer et qu'on rattache dans la foulée —
  avec un filtre côté navigateur. Il est côté navigateur et non côté serveur
  parce que le formulaire est en cours de saisie : une recherche qui
  rechargerait la page ferait perdre tout ce qui n'est pas encore enregistré.

**Le piège de ce lot, et il a mordu.** Borner le sélecteur ouvre une perte de
données silencieuse : le formulaire ne poste que les cases présentes dans la
page, donc un fichier rattaché il y a six mois, sorti des deux cents derniers
dépôts, serait détaché au premier enregistrement sans que rien ne le signale.
Les fichiers déjà rattachés sont donc rendus **à part et toujours**, cochés, en
tête du sélecteur — et retirés du lot proposé pour ne pas y figurer deux fois.

Le premier essai s'est fait prendre par une seconde version du même piège : la
liste des cochés se lisait en base seulement si `$valeurs` ne portait pas de
titre — or à l'ouverture d'une fiche, `$valeurs` **est** la ligne en base et
porte donc un titre. Aucune case n'était cochée, et le premier enregistrement
détachait tout. C'est `$erreurs` qui distingue les deux cas, et rien d'autre :
le formulaire n'est réaffiché avec des erreurs que depuis `ecrire()`, donc
`$erreurs === []` signifie exactement « fiche ouverte, pas encore soumise ».

**Vérifié sur 250 fichiers** : pagination et bornes, recherche par titre, par
nom de fichier et sans résultat, échappement du `%` dans la recherche, filtres
conservés dans les liens — et l'aller-retour complet d'une notice rattachée à
un fichier hors lot : rouvrir puis enregistrer conserve la liaison, décocher la
retire réellement. Données d'essai et compte temporaire effacés.

**Aucune migration.**

### Lot G5 — livré

**La médiathèque accepte trois familles** et non plus une seule : images,
documents PDF, enregistrements MP3/M4A/OGG. Le brief §4 demande des documents,
des correspondances et des discours enregistrés — ni les uns ni les autres ne
tiennent en JPEG. La vidéo n'y est pas, et ce n'est pas un oubli : elle reste
chez son hébergeur (décision 2), et la notice porte son adresse.

**Trois plafonds et non un.** Une photographie réduite tient dans 8 Mo, le scan
d'une correspondance de vingt pages non (30 Mo), l'enregistrement d'un discours
d'une heure encore moins (60 Mo). Un plafond unique aurait forcé à prendre le
plus large, ce qui aurait laissé passer des images de trente méga-octets dans
la galerie. Le type réel est donc lu **avant** le plafond : refuser un discours
au nom de la limite des images aurait été faux, et le message l'aurait dit de
travers.

**Ce que l'ouverture aux PDF change en matière de sûreté, et c'est à dire
clairement.** Une image est validée sur ses octets par `getimagesize`, qui
échoue sur tout ce qui n'en est pas une. **Un PDF n'a pas d'équivalent** : seul
son type MIME est vérifiable. La barrière qui compte devient donc le
`.htaccess` de `medias/`, qui neutralise tout gestionnaire de script dans le
dossier — et son absence sur le serveur ne se verrait pas, tout fonctionnant
exactement pareil jusqu'au jour où non. **À vérifier à chaque déploiement.**

Deux durcissements l'accompagnent :

- **Les PDF sont servis en pièce jointe**, pas affichés dans l'onglet. Un PDF
  peut porter du JavaScript, que le lecteur intégré du navigateur exécuterait
  dans **notre** origine, le fichier étant servi depuis le domaine du site. Le
  téléchargement coupe court. Sans conséquence sur l'usage : la page d'une
  notice les propose déjà en téléchargement.
- **Un nom d'origine sans un seul caractère latin** — « 討論.pdf » — ne laissait
  rien après le passage au slug, et le fichier serait sorti de la forme attendue
  en base. Un repli le nomme désormais « fichier ».

**Trois pièges d'affichage, réglés parce qu'ils ne préviennent pas :**

- **Ni PDF ni MP3 n'ont de vignette.** Une `<img>` pointée dessus rend un cadre
  cassé. La planche du back-office et le sélecteur d'une notice demandent donc
  la famille avant de poser une image, et affichent sinon le signe du format et
  le poids du fichier.
- **La couverture d'une notice doit être une image**, et le premier fichier n'en
  est plus forcément une : une notice de discours peut commencer par son
  enregistrement. `Archive::couverture()` et `couvertures()` filtrent sur la
  famille — des deux côtés de la jointure, sinon une notice ouverte par un PDF
  n'aurait aucune couverture alors qu'elle porte des photos.
- **L'aperçu de partage aussi.** Servir un MP3 en `og:image` ferait échouer la
  récupération de l'aperçu sans rien dire.

**Sur la page d'une notice**, les fichiers sont désormais séparés par famille :
les enregistrements se lisent dans la page (`preload="none"` — une page de
discours peut porter plusieurs pistes, rien ne se télécharge avant qu'on le
demande), les documents se téléchargent sous le nom de la notice plutôt que
sous le nom fabriqué au dépôt, les images restent une planche.

**Vérifié** sur un lot mêlant les trois familles plus **un script PHP renommé
en `.pdf`** : les trois passent avec la bonne famille, le piège est refusé et
nommé dans le message d'erreur — `finfo` le lit comme `text/x-php`. Aucune
`<img>` ne pointe vers un PDF ou un MP3, l'aperçu de partage reste l'image alors
que l'enregistrement est en tête de la notice, et la vignette de la planche
aussi. Données d'essai et compte temporaire effacés, `medias/` remis à vide.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/012_media_famille.sql` | la colonne `famille` sur `media`, et son index |

### Lot G6 — livré

Le brief décrit la bibliothèque des discours par un exemple : « 1980 — Discours
de clôture de la 5ᵉ législature », avec sur la même page contexte historique,
vidéo, audio, transcription intégrale et document original. **La page de notice
porte tout cela depuis G4 et G5** ; ce qui manquait, c'était l'index qui y mène.

**Un index et non une planche**, et c'est le cœur du lot. Un discours n'a
souvent aucune image : la grille de vignettes des autres catégories lui donnait
des tuiles grises qui ne disent rien. Chaque ligne annonce donc sa date, son
lieu, et **ce que la pièce porte réellement** — vidéo, enregistrement,
transcription, document. Sur une bibliothèque de deux cents discours, ouvrir une
page pour découvrir qu'il n'y a qu'un titre est une perte de temps répétée deux
cents fois.

Les quatre indications ne viennent pas du même endroit — deux sont des colonnes
de la notice, deux se lisent sur les fichiers rattachés — d'où une requête
unique pour tout le lot plutôt qu'un appel par ligne.

**Groupé par décennie et non par année.** La bibliothèque court sur quarante ans
avec des trous, et une liste d'années dont la moitié est vide se lit mal. Les
pièces non datées forment un groupe à part, en fin de liste, plutôt que d'être
rangées sous une décennie inventée.

**Même adresse, même modèle, même filtres : seule la mise en page change.**
`/archives/discours` reste l'adresse ; lui en donner une propre — `/discours` —
aurait fait deux chemins pour une même pièce, ce que la décision 3 interdit.
Elle porte une priorité plus haute dans le plan du site : sa transcription est
le contenu le plus recherché du fonds.

**La recherche couvre la transcription**, et c'est tout l'intérêt de l'avoir
saisie : on retrouve un discours par une phrase qu'on en a retenue.

**Vérifié** sur quatre discours — un complet, deux partiels, un sans date : les
décennies se forment dans l'ordre, les non datés ferment la marche, les quatre
marques n'apparaissent que sur la pièce qui les porte, la recherche par un mot
de la transcription retrouve la bonne. Une coquille corrigée au passage : le
titre de groupe rendait « 1960s », pluriel anglais qui n'existe pas en français.

**Aucune migration.**

### Lot G7 — livré

**La dernière page du cahier des charges d'origine, et celle qui n'attendait
rien de technique.** Elle est restée non écrite pendant tout le projet, et la
raison mérite d'être dite : elle était prévue en **page unique**, et une page de
texte attend que *tout* son texte existe. Le crédit d'une photographie du buste
ne dépend pourtant pas de la liste des décorations.

**Elle est donc adossée aux données comme le reste du site**, et s'ouvre sujet
par sujet à mesure que la matière arrive. C'est le déblocage réel du lot :
publier « Pont Philippe Grégoire Yacé » n'attend plus que la chanson de Reine
Pélagie soit documentée.

**Cinq rubriques**, qui regroupent les dix sujets du brief : lieux de mémoire —
pont, boulevard, buste, Jacqueville en forment une et non quatre —, hommages et
commémorations, décorations et distinctions, livres et publications, musique et
culture. **Seules paraissent celles qui portent quelque chose** : la page montre
ce qui existe et se tait sur le reste, au lieu d'afficher cinq intertitres
suivis de vide.

**Les témoignages ne sont pas une rubrique.** Ils vivent à leur adresse depuis
le lot F1, avec leur formulaire et leur file de modération ; l'index d'Héritage
en montre trois et y renvoie, plutôt que de les recopier — ce qui ferait deux
endroits à tenir à jour.

**Une table distincte d'`archive`, délibérément.** Une notice d'archive est une
*pièce* du fonds — datée, créditée, cataloguée. Un lieu de mémoire existe
aujourd'hui, se visite, et sa photographie n'est qu'une illustration. Les mêler
aurait fait remonter le pont de Marcory dans les résultats de recherche du fonds
documentaire.

**Les adresses sont plates** — `/heritage/pont-philippe-gregoire-yace` et non
`/heritage/lieux/pont-…`. Elles finiront sur une plaque, un QR code ou un
dossier de presse, et chaque segment compte ; la rubrique reste un regroupement
d'affichage, pas un niveau d'adresse. Elle peut donc changer sans casser un
seul lien. *Réservation notée dans le fichier SQL : si une rubrique demandait un
jour sa page, elle prendrait `/heritage/rubrique/{cle}` — jamais
`/heritage/{cle}`, qui entrerait en collision avec un slug de sujet.*

**Un lieu de mémoire est balisé `Place`**, ce qui le rend éligible aux résultats
de recherche locale — précisément ce qu'on veut pour un pont qu'on cherche à
situer.

**La barre du site porte enfin les sept entrées du brief.** « Héritage » y entre
avec sa rubrique — elle ne pouvait pas précéder ce qu'elle ouvre — et le lien
mort du pied de page disparaît. Seul « Commander » ne pointe pas encore sur le
tunnel (lot G3).

**Vérifié** sur cinq sujets répartis dans quatre rubriques, dont un brouillon :
les rubriques vides n'apparaissent pas, le brouillon et un slug inconnu rendent
404, l'ordre manuel est respecté, le balisage `Place` sort sur un lieu, le fil
d'Ariane renvoie à la bonne ancre, et le plan du site liste les quatre sujets
publiés sans le brouillon. Données d'essai effacées.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/013_heritage.sql` | les tables `heritage` et `heritage_media` |

### Lot G8 — livré

**Le troisième formulaire ouvert du site, et le premier qui reçoit des fichiers
d'un inconnu.** Toute la plomberie des deux premiers est reprise — session par
route, jeton, champ leurre, délai minimal, plafond de débit — et une barrière
s'y ajoute, qui est le cœur du lot.

**Les fichiers n'atterrissent pas dans `medias/`.** C'est la décision 4, et
elle ne va pas de soi : `medias/` est servi par Apache, donc un fichier qui y
est déposé est téléchargeable par qui devine son nom, **publié ou non**. Y
ranger le document d'un inconnu que personne n'a encore ouvert reviendrait à le
publier à demi, en comptant sur l'obscurité du nom.

Ils attendent donc dans `quarantaine/` : dossier en `Require all denied`, exclu
de la réécriture du contrôleur frontal, fichiers en `0600` et noms sans radical
lisible — personne n'a à deviner ce que la quarantaine contient. Ils n'en
sortent que par l'acceptation d'un modérateur.

**La seule lecture d'un fichier non relu passe par le back-office**, sur une
route authentifiée qui le sert en `application/octet-stream` et en pièce
jointe : le modérateur l'ouvre dans son propre lecteur, hors du navigateur et
hors de l'origine du site.

**Le contrôle de type est celui du back-office, sans allègement.** `examiner()`
a été extraite de `Televersement::recevoir()` pour être partagée : mêmes
formats, mêmes plafonds, même lecture des octets. Un visiteur anonyme n'a
aucune raison d'avoir plus de latitude qu'un éditeur connecté, et deux copies
de ce contrôle auraient fini par diverger.

**Trois différences assumées avec le dépôt du back-office :**

- **Cinq fichiers par envoi** et non vingt : chaque fichier reçu est un fichier
  qu'un modérateur devra ouvrir.
- **Trois envois par heure** et non cinq : une contribution occupe le disque
  avant même d'avoir été lue.
- **Un fichier refusé arrête tout l'envoi**, là où le back-office signale
  chaque refus et laisse passer les autres. La différence tient à qui est
  devant l'écran : un éditeur voit sa planche et sait ce qui est entré, un
  visiteur n'a aucun moyen de le savoir.

**La cession de droits est horodatée**, et c'est une exigence juridique : le
contributeur confie un document dont il détient les droits, et le site le
publiera. Sans accord explicite et daté, rien ne prouverait qu'il a été donné.

**Accepter verse les fichiers en brouillon**, jamais en ligne : accepter dit
« ce fonds nous intéresse », pas « publions-le tel quel ». Il reste à légender,
créditer et rattacher à une notice. **Refuser efface les fichiers du serveur** —
le site n'a aucune raison de garder le document d'un tiers qu'il a décidé de ne
pas publier.

**Vérifié de bout en bout** : envoi trop rapide refusé, cession de droits
manquante refusée, script PHP renommé en `.pdf` refusé, plafond horaire
appliqué. Un envoi valide range ses deux pièces en quarantaine et **rien dans
`medias/`** ; la route de lecture répond 302 sans session ; l'acceptation
déplace les fichiers, crée les lignes en brouillon avec leurs dérivées et vide
la quarantaine ; le refus efface. Données d'essai effacées, les deux dossiers
remis à vide.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/014_contribution.sql` | les tables `contribution` et `contribution_fichier` |
| `quarantaine/` | le dossier fermé — **son `.htaccess` doit partir au déploiement** |

### Lot G9 — livré

Chaque rubrique avait déjà sa recherche : le fonds depuis G4, les discours
depuis G6. Ce qui manquait, c'est **la question qu'on pose quand on ne sait pas
où chercher** — « Jacqueville » se trouve dans une notice d'archive, dans un
lieu de mémoire, dans une actualité et dans un événement, et le visiteur n'a
pas à deviner laquelle des quatre rubriques ouvrir.

**Une requête par entité, pas une `UNION` SQL.** Les tables n'ont ni les mêmes
colonnes ni les mêmes conditions de publication — un événement annulé reste
visible, une actualité sans date ne l'est pas. Une union aurait demandé de
recopier ces règles dans la recherche, où elles auraient divergé de leurs
modèles au premier changement. Quatre requêtes indexées coûtent moins cher
qu'une règle de publication fausse.

**L'extrait s'ouvre là où le terme apparaît**, pas au début du texte. Rendre les
deux cents premiers signes d'une transcription de discours ne dirait rien : le
mot cherché est peut-être à la page cinq. C'est ce qui permet de juger d'un
résultat sans l'ouvrir — et c'est là que la transcription saisie au lot G6 paie.

**Les résultats sont groupés par rubrique**, non mélangés par pertinence :
savoir qu'on a trouvé « Jacqueville » dans une archive **et** dans un lieu de
mémoire vaut mieux qu'un classement dont on ne sait pas ce qui le décide.

**La page de résultats est en `noindex, follow`** : elle change à chaque contenu
ajouté et duplique ce que les pages disent déjà. Google traite ces pages comme
du remplissage, et le plan du site ne l'annonce pas.

**Une loupe dans la barre, pas une huitième entrée** : la barre porte les sept
que le brief demande, et « Rechercher » n'est pas une rubrique du site.

**La 404 offre la recherche, et c'est le point de ce lot qui compte le plus à
long terme.** La décision 3 prévoit des adresses imprimées — QR codes,
filigranes, dossiers de presse, peut-être le livre. Une adresse imprimée finit
par être mal recopiée, et la 404 est alors le dernier endroit où rattraper le
visiteur. **Le champ est pré-rempli avec ce que l'adresse contenait** : qui tape
`/archives/discours-de-1980` de travers cherche probablement « discours de
1980 ». Le segment vient de la requête : il n'est employé que comme valeur d'un
champ, échappé, et rejeté s'il ne ressemble pas à des mots.

**Le fil d'Ariane est devenu un partial**, qui rend d'un même geste la liste
visible et le `BreadcrumbList` structuré. Les écrire séparément aurait garanti
qu'ils divergent, et un balisage qui contredit la page affichée est pire que pas
de balisage. Il couvre désormais les fiches d'actualité et d'événement, qui n'en
avaient pas — une fiche partagée sur WhatsApp est souvent la première page qu'on
voit du site.

**Vérifié** : « Jacqueville » trouvé dans les quatre rubriques, extrait centré
sur le terme au milieu d'une transcription, `%` échappé — chercher « 100 % » ne
rend pas tout le fonds —, terme trop court et sans résultat traités à part,
`BreadcrumbList` valide dont le dernier élément ne se désigne pas lui-même, 404
pré-remplie et rejetant un segment technique ou une tentative d'injection.
Données d'essai effacées.

**Aucune migration.**

### Lot G10 — livré

La page Biographie portait **cinq chapitres écrits en dur dans le gabarit**,
avec un sommaire d'ancres et cinq fois « texte à rédiger ». Le brief §3 en
demande douze, chacune à son adresse, illustrées et reliées au fonds. Ce n'est
pas une question de nombre : c'est le même déplacement qu'au lot G7 — ce qui
était une page devient des pièces cataloguées.

**Une période est définie par ses bornes, et tout le reste en découle.** C'est
la seule décision du lot, et elle se voit partout : une période qui connaît son
année de début et son année de fin sait quels repères de la frise la traversent
et quelles pièces du fonds ont été produites pendant qu'elle durait. **Rien de
ce rattachement n'est saisi.** Il n'y a pas de case « rattacher ce discours à
cette période », pas de table de liaison entre `periode` et `archive` : il y a
deux dates, et une comparaison. Un découpage revu déplace les repères et les
pièces avec lui, sans qu'aucune fiche ne soit rouverte — ce qui compte, parce
que ce découpage n'est pas encore arrêté.

**La colonne `repere.periode` disparaît**, et c'est la contrepartie du même
choix. Elle portait `p1` à `p4`, choisis dans un menu déroulant, et l'écran
vérifiait ensuite que l'année de classement tombait bien dans la période
retenue : deux saisies pour une seule information, et une erreur à corriger
chaque fois que le découpage changeait. L'année suffit. Garder l'ENUM à côté de
douze périodes en base aurait garanti que les deux divergent au premier
découpage revu — la fiche du repère affiche donc désormais sa période au lieu de
la demander, et la liste des repères l'affiche sans que personne l'entretienne.

**Les bornes sont incluses et ne se chevauchent pas.** Une année appartient à
une période et à une seule, sans quoi le même repère paraîtrait sous deux
onglets de la frise et la même pièce sous deux récits. Le découpage `p1`-`p4`
qui disparaît se chevauchait justement d'un an — 1980 fermait la troisième
période et ouvrait la quatrième — et c'est précisément ce qu'il ne fallait pas
reconduire. L'écran refuse le chevauchement **à la publication seulement** : un
brouillon en cours de découpage passe forcément par des états incohérents, on
déplace une borne puis l'autre, et refuser l'enregistrement intermédiaire
obligerait à tenir le découpage entier dans sa tête plutôt que dans l'écran.

**Trois conditions pour publier** : les deux bornes, la source, et l'absence de
recouvrement. La source, comme partout ailleurs (CDC §6), et elle compte plus
ici qu'ailleurs — la biographie est le seul endroit du site où l'on écrit la
vie d'une personne réelle en continu, et une phrase non sourcée y passe pour un
fait établi. Les bornes, parce qu'une période publiée sans dates ne porterait ni
frise ni fonds : elle dirait qu'elle est une période sans en être une.

**Un quatorzième écran, « Biographie », entre au back-office**, placé avant
« Repères » : la période est le récit, le repère est le jalon qu'elle traverse,
et c'est l'ordre dans lequel un éditeur travaille — on découpe, puis on date. Il
suit le patron des notices d'archives et des sujets d'Héritage : plusieurs
images par fiche, choisies dans la médiathèque, la première faisant la vignette
et l'image de partage. La fiche montre en outre **ce que les bornes saisies
recueillent déjà** — les repères de ces années, listés là où on pose les dates,
parce qu'un découpage se juge à ce qu'il attrape.

**Le bouton « publier » de la liste ne passait par aucune de ces règles**, et
c'est un défaut qui préexistait au lot : il n'écrit qu'une colonne, sans
formulaire, donc sans validation. `CrudController` reçoit un point d'accroche,
`refusDePublier()`, qui rend le message empêchant la mise en ligne ; il est
implémenté pour les périodes et rend `null` partout ailleurs, où le comportement
est donc inchangé. **Dépublier ne se refuse jamais** : retirer une page du site
est toujours permis, c'est la mettre en ligne qui se mérite. Voir la §6 pour ce
qui reste ouvert sur les autres écrans.

**L'amorce verse les cinq chapitres, en brouillon, et n'invente pas les douze.**
Même geste qu'au lot G0 pour les sept repères — ne rien perdre du gabarit — mais
une différence qui tient à ce qui est versé : les repères de G0 portaient des
dates, ces cinq chapitres ne portent que la consigne de rédaction affichée en
italique. Les publier reviendrait à publier « texte à rédiger » sur la
biographie d'une figure historique réelle. Ils arrivent donc là où ils doivent
être : dans l'écran de saisie, sous les yeux de l'auteur. Et ce ne sont pas les
douze périodes du brief — quatre des cinq sont chronologiques, la cinquième,
« L'homme privé », est thématique, ce qui est exactement pourquoi le brief
demande de passer des chapitres aux périodes. **Le découpage en douze, daté et
sourcé, reste dû par le commanditaire et l'auteur** ; l'écran est prêt à le
recevoir, et aucune borne n'a été devinée ici.

**Tant qu'aucune période n'est publiée, la page le dit et ne montre rien** —
même règle qu'Héritage à son ouverture. Deux conséquences visibles, et
assumées : les cinq chapitres d'attente ne paraissent plus sur le site public,
et les onglets de la frise disparaissent, puisqu'ils suivent désormais les
périodes. La frise reste entière et se lit d'un bloc ; les onglets reviennent
avec le découpage. Un repère qu'aucune période publiée ne couvre reste sur la
frise sans onglet pour le filtrer, et **l'écran des périodes le signale** : c'est
le seul contrôle qui ne peut pas se faire à la saisie, et le seul qui se voie
sur le site.

**Les adresses sont plates — `/biographie/enfance-et-formation`** —, sur le
modèle d'Héritage et pour la même raison : elles ont vocation à être imprimées
et chaque segment compte (décision 3). Le millésime n'y entre pas : une borne se
corrige, une adresse publiée ne se corrige plus.

**La page d'une période porte ce que le brief appelle « relier la biographie aux
archives »** : son récit, ses images, les jalons de la frise de ces années-là, et
les pièces du fonds qui en sont datées. Plus la période précédente et la
suivante — une biographie se lit dans l'ordre, et on arrive sur une période par
un lien profond aussi souvent que par la page mère. Le lien vers le fonds ouvre
le fonds entier et le dit : la planche des archives filtre par catégorie et par
millésime exact, pas par intervalle, et annoncer un filtre qui n'existe pas
serait pire que de ne rien annoncer.

**La recherche transversale gagne une rubrique** (lot G9) : le récit d'une
période est le texte le plus long du site après les transcriptions de discours,
et il répond souvent mieux à un nom de lieu ou d'institution qu'une pièce
isolée. Les périodes entrent aussi au plan du site, aux mêmes conditions que la
page publique — publiées **et** datées : une adresse listée mais rendue en 404
fait chuter la confiance accordée au plan entier.

**Deux défauts anciens sont tombés en chemin**, tous deux invisibles à l'œil :

- **`og:image:width` et `og:image:height` annonçaient 1200 × 630 pour toutes les
  images d'archives.** Trois contrôleurs — archives, héritage, et maintenant
  biographie — passent `null` pour dire « je ne connais pas les dimensions de
  cette dérivée », mais la mise en page se repliait par `??`, qui ne distingue
  pas « non fourni » de « fourni à null ». Les aperçus de partage des pièces du
  fonds annonçaient donc des dimensions fausses, ce que le commentaire du
  gabarit interdisait explicitement. `array_key_exists` distingue les deux cas.
- **Le `<title>` d'une liste du back-office portait le nom de son dernier
  contenu.** `actions-liste.php` est inclus par `require` dans la boucle, donc
  dans la portée de la page, et il y écrasait `$titre` — que la mise en page
  d'administration lit ensuite. Les listes vides y échappaient parce que la
  boucle ne tournait pas, ce qui explique qu'il ait tenu depuis le lot C. Les
  variables du partial portent désormais un préfixe.

**Vérifié** : les cinq chapitres versés en brouillon et invisibles du public ;
une période publiée sans borne, sans source, ou recouvrant une autre, refusée
par la fiche **et** par le bouton de la liste ; l'année de fin antérieure à
celle de début refusée ; les sept repères d'amorce répartis sous les bonnes
périodes et les onglets nommés d'après elles ; un repère hors de toute période
visible sous « Tout », signalé au back-office ; une pièce d'archive datée de
1965 remontant sous la période 1959-1980 et une pièce de 2005 n'y remontant
pas ; la période voisine d'avant et d'après ; le fil d'Ariane et le
`BreadcrumbList` ; l'aperçu de partage tiré de la première image ; une période
en brouillon et un slug inconnu rendus en 404 ; création, modification et
suppression d'une fiche, images comprises, la suppression emportant ses
liaisons. Données d'essai effacées, compte d'essai supprimé.

**Une migration**, `sql/015_periode.sql` : les deux tables, l'amorce des cinq
chapitres, et le `DROP COLUMN` sur `repere`. Elle ne se rejoue pas — la
suppression de colonne lèverait une erreur, ce qui vaut mieux qu'une
modification silencieuse.

### Lot G11 — livré, et l'anglais ouvert

**Le lot G1 avait posé la structure ; celui-ci rend le site réellement
traduisible.** Le plan du §9 annonçait « aucune reprise de code » et la section
G1 disait le contraire — que les textes des gabarits sortiraient avec G11.
C'est G1 qui avait raison, et quatre chantiers l'ont montré.

**Deux choses se traduisent sur ce site, et elles n'ont pas le même régime.**
C'est la décision du lot, et tout en découle :

| | Où ça vit | Qui le change |
|---|---|---|
| **Les contenus** — une notice, un récit de période, une actualité | table `traduction`, en base | l'éditeur, depuis le back-office |
| **Les textes du site** — « Lire la suite », « Retour aux archives », « Ouvrir le menu » | `src/lang/fr.php` et `en.php`, dans le dépôt | le développeur, avec le code qui les affiche |

Les mélanger aurait été une faute des deux côtés : demander à un éditeur de
traduire « Fermer » depuis un écran d'administration, ou livrer une mise à jour
de code pour corriger une notice. **533 clés**, français et anglais au complet
des deux côtés — l'écart entre les deux catalogues est nul, et se vérifie d'une
ligne.

**Le repli est le même des deux côtés** : une clé absente de l'anglais retombe
sur le français, donc une page anglaise incomplète reste lisible. C'est ce qui
permettra d'ouvrir rubrique par rubrique au lieu d'attendre que tout soit
traduit.

**`t()` échappe, et c'est délibéré.** Ces chaînes finissent presque toutes dans
du HTML, et un helper qui n'échappe pas oblige à écrire `View::e(t(...))`
plusieurs centaines de fois — ce qu'on oublie une fois. Deux variantes existent
parce que deux contextes l'exigent : `t_brut()` pour les phrases portant une
mise en exergue, qu'un découpage en trois morceaux rendrait intraduisibles, et
`t_nu()` pour `$titre` et `$description`, que la mise en page échappe
elle-même. Sans cette troisième forme, « l'État » ressortait en
`l&amp;#039;État`.

**`DateFr` devient `DateLisible` et écrit dans les deux langues.** Le nom était
juste tant qu'elle n'écrivait qu'en français ; « 12 septembre 2026 » servi sous
`/en/` était le genre de détail qui trahit une traduction faite à moitié. Le
français ne bouge pas — « 1er mars », « 18 h 30 », « du 28 février au 3 mars
2026 » — et l'anglais suit ses propres règles : pas d'ordinal dans une date
complète, douze heures avec un point, et le tiret demi-cadratin d'intervalle,
spacé dès qu'une borne contient une espace.

**L'écran de traduction est un écran à part, et non un panneau sur chaque
fiche.** Traduire n'est pas éditer — deux gestes, souvent deux personnes,
parfois deux moments séparés de plusieurs semaines ; et le français doit se
lire **en regard**, sans quoi on traduit à côté. Rien ne s'y publie : il se
confie sans risque. Il **ne dépend pas de l'ouverture** : il s'appuie sur les
langues *déclarées* et non sur les langues ouvertes, de sorte que la matière
pouvait se verser pendant que `/en/` répondait encore 404 — et qu'elle
continuerait de se verser si l'anglais était refermé.

**Ce qui se traduit y est déclaré, pas déduit des colonnes.** Une entité porte
des champs qui ne se traduisent pas — un slug, une année, un crédit photo, une
URL de vidéo — et proposer de traduire un slug est une invitation à casser une
adresse.

Trois défauts que le lot a mis au jour, et qui n'avaient rien à voir avec
l'anglais en apparence :

- **Trente-et-un liens internes et deux actions de formulaire étaient écrits en
  dur** — `href="/le-livre"`, `action="/contact"`. Sous `/en/`, chacun ramenait
  au français sans le dire, et les formulaires postaient vers la route
  française depuis une page anglaise. C'est précisément ce contre quoi le
  commentaire de `nav.php` mettait en garde depuis G1.
- **Trois redirections après envoi perdaient le préfixe de langue.** Le
  visiteur repartait sur la page française avec son accusé de réception anglais
  posé en session, qu'il lisait donc au mauvais endroit.
- **`parametre` n'était pas traduisible du tout.** Sa clé primaire est `cle`,
  une chaîne ; `Traduction` s'indexe sur un entier. Or il porte
  `preface_texte`, `preface_extrait` et `auteur_bio` : de la prose, pas des
  réglages. Comblé **sans migration** — la table accepte `ligne_id = 0`, aucune
  ligne de `parametre` n'ayant d'identifiant qui puisse entrer en collision.

**L'anglais a été ouvert le 8 septembre 2026, sur décision du commanditaire.**
Le lot avait été livré fermé — le raisonnement de G1 étant que servir des pages
à moitié françaises sous `/en/` apprend aux moteurs que le site ment sur son
contenu. L'ouverture a été décidée ensuite, et le moment s'y prêtait : la base
ne portait alors que **sept repères publiés** et aucun autre contenu, de sorte
que `/en/` sert une interface entièrement anglaise et sept notices encore
françaises — pas une page à trous.

**Ce que l'ouverture engage.** Le `hreflang`, le `x-default` et le sitemap
dédoublé s'écrivent désormais, et annoncent une version anglaise ; le plan du
site passe de douze à vingt-quatre adresses. Une page annoncée anglaise dont le
corps reste français perd de la confiance auprès des moteurs, et c'est long à
regagner. **Le remède n'est pas de refermer mais de traduire** : chaque champ
posé depuis l'écran de traduction retire une phrase française de la page.

**Refermer se fait du même mot** — `'active' => false` dans
`App\Core\Langue::LANGUES` — et sans casse : `/en/` repasse en 404, le
`hreflang` et le sélecteur de langue disparaissent, et les traductions déjà
saisies restent en base à attendre.

**Le sélecteur de langue a reçu son style à cette occasion.** Il n'en avait pas :
tant que l'anglais était fermé, il ne paraissait sur aucune page et personne ne
l'avait vu. Deux sigles séparés d'un filet, à la façon de la loupe voisine —
c'est un commutateur, pas une huitième rubrique, et lui donner le poids d'une
entrée de menu ferait croire à une section du site. Il pointe **la même page**
dans l'autre langue, jamais la racine : changer de langue ne doit pas faire
perdre sa place.

**Vérifié** : les dix-sept adresses publiques répondent dans les deux langues, `hreflang`, `x-default` et
le sitemap dédoublé s'écrivent, un POST réel sur le formulaire de contact
refuse en anglais, l'écran de traduction enregistre une période et deux
paramètres, et la page anglaise rend « Childhood and education » là où la
française rend « Enfance et formation ». Aucun double échappement sur les
quatorze pages. Compte et données d'essai effacés.

**Aucune migration.** La table `traduction` du lot G1 suffisait, `ligne_id = 0`
compris.

**Ce qui reste, et qui n'est pas du code : la matière.** Traduire la biographie,
les notices d'archives, les sujets d'Héritage et la préface est un travail
éditorial sur une figure historique réelle — le CDC §6 exige qu'on ne publie
rien sur Yacé sans source, et cela vaut dans les deux langues. Les **textes
d'interface**, eux, sont traduits : ce sont des libellés d'outil, pas des faits.

**Deux réserves à consigner.** Les textes éditoriaux d'attente de l'accueil et
de la page du livre — « Résumé long à fournir », « Texte à rédiger par
l'éditeur » — sont passés au lexique parce qu'ils y étaient déjà en dur. Leur
vraie place est la base, sous la main de l'éditeur ; le lexique est un progrès,
pas la destination. Et **la version anglaise des mentions légales est une
traduction, pas un avis juridique** : le texte décrit fidèlement le
comportement du site, mais qui engage la structure éditrice devra le relire.

### Lot G3 — livré, et la boutique reste fermée

**Le dernier lot du périmètre, et le seul qui touche à l'argent.** Il ouvre la
commande en **paiement à la livraison**, et rien d'autre : aucune passerelle
n'est appelée. `App\Core\Paiement` décrit carte.abidjan.net depuis le lot E2
et continue de ne pas être appelée.

**La boutique est livrée fermée.** Aucune date de parution n'est annoncée —
le commanditaire l'a confirmé le 7 septembre — et prendre une commande payable
à la remise pour un ouvrage qui n'existe pas encore, c'est promettre une
livraison qu'on ne peut pas tenir. Elle s'ouvre à **deux conditions et il les
faut toutes** : la case cochée *et* un prix saisi. Ouvrir sans prix afficherait
« 0 F CFA » et enregistrerait des commandes gratuites ; l'écran des paramètres
refuse la case sans le prix, et le dit.

**La page `/commander` reste servie quand la boutique est fermée**, et annonce
que les commandes ouvriront à la parution. Un bouton menant à une 404 fait
croire à une panne ; un bouton qui ne fait rien ment au clavier comme à la
souris.

#### Les zones de livraison, et pourquoi les frais s'héritent

Une table qui se référence elle-même, à trois niveaux — pays, ville, commune.
Ils ont la même forme : un nom, un parent, un tarif. Trois tables auraient
triplé les écrans et les jointures, et un quatrième niveau — un quartier
d'Abidjan, un arrondissement de Paris — en aurait demandé une quatrième.

**`frais` est nullable, et null veut dire « ceux du parent ».** C'est ce qui
rend le système tenable à la main :

```
Côte d'Ivoire ......... 2 000 F      posé
  Abidjan ............. 1 500 F      posé
    Cocody ............ 1 000 F      posé
    Yopougon .......... 1 500 F      hérité d'Abidjan
    Treichville ....... 1 500 F      hérité d'Abidjan
```

On pose le pays, puis seulement les exceptions. Sans héritage, il faudrait un
tarif pour chacune des treize communes du district, et pour chaque commune
ajoutée ensuite. L'autre forme — un tableau plat de triplets avec un tarif
chacun — explose en combinaisons et oblige à retrouver le tarif de la ville
pour le recopier ; elle a été écartée pour cela.

**Une zone dont personne, sur toute la remontée, ne porte de tarif n'est pas
proposée.** Mieux vaut ne pas offrir la livraison quelque part que de la
facturer zéro franc. Et **fermer un pays ferme ses villes et ses communes**,
sans avoir à les décocher une à une : c'est le geste qu'on veut quand un
transporteur cesse de desservir un secteur.

L'amorce pose la Côte d'Ivoire, Abidjan et ses treize communes, plus une racine
« Reste du monde » — **toutes sans tarif**. C'est du découpage administratif
public, pas une liste validée, et tant qu'aucun montant n'est posé la livraison
n'est proposée nulle part.

#### Ce qu'une commande fige, et pourquoi

Le prix de l'ouvrage et les tarifs de livraison changeront. Une commande de
janvier doit garder ce qu'elle a coûté en janvier : recalculer un total ancien
à partir des tarifs du jour ferait mentir la fiche de suivi, et une
contestation se tranche sur ce qui a été facturé.

`commande` gagne donc `prix_unitaire`, `frais_livraison`, `zone_id` et
`zone_libelle` — cette dernière portant « Côte d'Ivoire · Abidjan · Cocody »
**en texte**, parce qu'une zone peut être renommée ou supprimée. `montant`
reste le total, ce qui laisse l'écran de suivi et le calcul de recette du lot
E2 intacts.

#### Les statuts, et la phase 2

« initiée → payée → remise » décrivait un paiement **en ligne, avant**
l'expédition. En paiement à la livraison, l'argent arrive **à la remise** :

```
initiée ──→ confirmée ──→ remise          (appel, puis remise encaissée)
   └────────────┴───────→ annulée
```

`payee` et `echouee` sont **conservées et inatteignables** : rien ne les écrit
tant qu'aucune passerelle n'encaisse, et un bouton « Constater le paiement »
sur une commande payable à la livraison ferait enregistrer un encaissement qui
n'a pas eu lieu. **La phase 2 rouvrira une ligne de `SUITES` — et aucune
migration.** Réutiliser `payee` pour dire « confirmée » aurait tenu sans
migration, mais aurait fait mentir le mot sur toutes les commandes et faussé le
calcul de recette, qui compte `payee` et `remise`.

#### Trois règles tenues sans discussion

**Le total est recalculé côté serveur à l'envoi.** Ce que la page affiche est
indicatif ; ce qui s'enregistre vient de `Boutique::total()`, relu en base à
partir de la quantité et de la zone reçues. Un total posté se ramène à zéro
franc en modifiant un champ. Éprouvé : zone fermée, zone inexistante, zone
vide, quantité à 999 et livraison sans adresse sont refusées, et aucune n'a
créé de commande.

**Le formulaire de commande reçoit les protections des formulaires publics** —
plafond de débit, piège à robots, délai minimal — et le plafond y est plus bas
qu'ailleurs : **trois par heure**. C'est le seul formulaire du site sans
barrière de paiement. Une commande payable à la livraison ne coûte rien à
passer, et une rafale de commandes fantaisistes ne se voit qu'au moment où
quelqu'un décroche son téléphone pour les confirmer une à une.

**Le téléphone est obligatoire**, alors qu'il ne l'est nulle part ailleurs sur
le site : en paiement à la livraison, la commande se confirme par un appel.
Sans numéro, elle ne peut ni être confirmée ni être livrée.

#### Ce que le lot a décidé au passage

**Une page, un formulaire.** Le site vend un seul ouvrage : il n'y a pas de
panier, seulement une quantité. Un tunnel en trois étapes pour un produit
unique est une cérémonie — trois pages à charger, trois occasions
d'abandonner, et un état à porter entre elles.

**Un seul menu déroulant pour la zone**, portant le chemin complet et son
tarif — « Abidjan · Cocody — 1 000 F CFA ». Trois menus en cascade exigeraient
du JavaScript pour se remplir l'un l'autre, et sans lui on pourrait composer un
triplet incohérent. Le récapitulatif se met à jour par script s'il y en a un,
et la page reste entièrement utilisable s'il n'y en a pas — le serveur
recalcule de toute façon.

**La référence est dictable au téléphone** : `PGY-4F2K9A`, six signes pris dans
un alphabet sans O ni I ni 0 ni 1. Le paiement à la livraison impose l'appel :
la référence doit survivre à la voix. Aléatoire et non séquentielle — une
référence qui s'incrémente annonce à chaque client combien d'exemplaires ont
été vendus avant lui.

**La confirmation se lit en session, pas dans l'adresse.** Une page
`/commander/PGY-4F2K9A` serait partageable, et montrerait le nom, le téléphone
et l'adresse d'un client à qui aurait le lien.

**Pas de courriel de confirmation** : le lot F4 a tranché que `mail()` échoue
en silence sur un mutualisé, et c'est pourquoi les messages sont stockés. La
confirmation est à l'écran, avec la référence, et la commande attend dans le
back-office — où quelqu'un appelle, ce que le paiement à la livraison impose
de toute façon.

**Le prix de l'ouvrage devient un nombre.** Il était en texte libre — « 25 000
F CFA » — et n'était affiché nulle part. Un tunnel qui calcule `prix × quantité
+ frais` ne peut pas partir de là, et garder les deux formes ferait diverger
l'affiché et le facturé.

**Les cases à cocher du back-office sortaient de leur bloc par la gauche.**
Le thème attend le balisage de Bootstrap 4 — la case *dans* le libellé — et
annule le `padding-left` de `.form-check` ; `champ_case()` écrit celui de
Bootstrap 5, où Bootstrap pose une marge négative que ce `padding-left`
compense. Les deux se cumulaient. **Quatre écrans en souffraient** depuis les
lots C et G2 — les paramètres, la fiche d'un repère, celle d'un compte — sans
que personne le remarque : un champ au-dessus masquait le décalage. La case
« Ouvrir les commandes », seule en tête de son bloc, l'a donné à voir.
Rétabli dans `pgy-admin.css`, qui charge après le thème, plutôt qu'en
réécrivant un balisage standard déjà en place à cinq endroits.

**Le routeur accepte le tiret bas dans un segment nommé.** Découvert ici :
l'écran de traduction adresse ses rubriques par nom de table, et
`zone_livraison` en porte un — la route ne correspondait pas et l'écran
répondait 404. Élargir ne relâche aucune garde : un segment fantaisiste atteint
le contrôleur au lieu du repli du routeur, et le contrôleur rend la même 404.

**Une migration**, `sql/016_boutique.sql`. Éprouvée sur une reconstruction de
la production avant d'être jouée en développement.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/016_boutique.sql` | `zone_livraison` et son amorce, quatre colonnes sur `commande`, deux statuts de plus, le prix numérique |

### Lot G12 — livré

**Un aplat gris occupait la moitié de « Où se procurer l'ouvrage », et personne
ne savait ce qu'il attendait.** On y voyait un emplacement d'image, ou un
cadre de carte à venir. C'était le fond du conteneur.

**Le défaut.** Le bloc portait `class="pos row g-0"` avec trois `col-md-4` :
la répartition en tiers venait du `display: flex` de Bootstrap. Or `.pos`
déclare `display: grid` dans `assets/css/components.css`, chargé **après**
Bootstrap — et sans `grid-template-columns`. La grille tombait à une colonne,
les trois cartes s'empilaient sur un tiers de la largeur, et les deux tiers
restants laissaient voir le fond du conteneur, peint en `--rule` (`#DED7C9`)
pour dessiner les filets de 1 px entre les cellules. Le défaut datait du
premier commit du dépôt.

**Ni carte, ni dynamique.** Aucune Google Map n'a jamais été prévue : le CDC
§4.2 demande un « où acheter », pas une cartographie, et une carte imposerait
une clé d'API facturée et un bandeau de consentement pour un service tiers.

**Les trois villes vivaient dans deux gabarits.** « Abidjan »,
« Yamoussoukro » et « Paris » étaient écrites en dur dans
`templates/pages/accueil.php` **et** dans `templates/pages/livre.php`, chacune
sous une ligne « Enseigne et adresse à renseigner » qu'aucun écran ne
permettait de renseigner. Trois villes qu'on ne pouvait ni corriger, ni
compléter, ni augmenter d'une quatrième sans toucher au code.

**Elles sont en base** (`point_de_vente`), avec leur écran dans « Contenus » —
ville, enseigne, adresse, téléphone, site, rang, statut. Un seul champ
obligatoire, la ville : c'est le grand caractère de la carte, et souvent la
seule chose qu'on sache au moment où l'on crée la fiche. Tout le reste ne
s'affiche que s'il est rempli.

**L'amorce les verse en `publie`**, contrairement aux cinq chapitres de
biographie du lot G10, versés en brouillon. La différence tient à ce qu'elles
affirment : « texte à rédiger » sur la vie d'une figure historique n'a rien à
faire en ligne, une ville où l'on vend le livre ne prétend rien de tel — et
elle était déjà publiée, de fait. Les verser en brouillon aurait vidé la
section sur les deux pages.

**Aucune enseigne, aucune adresse n'a été inventée.** Les fiches affichent la
même phrase qu'avant le lot ; l'écran d'administration compte celles qui en
sont là et le signale en tête de liste. La matière est due par l'éditeur — elle
est entrée dans la table des livrables non techniques ci-dessous.

**Un seul gabarit pour les deux pages.** `templates/partials/points-de-vente.php`
sert l'accueil et « Le livre ». Les deux copies avaient déjà divergé : elles
appelaient `accueil.commander.adresse` et `livre.acheter.adresse` pour la même
phrase. Une seule clé, `points_de_vente.a_renseigner`, les remplace.

**Le nombre de colonnes n'est plus écrit nulle part.**
`repeat(auto-fit, minmax(min(100%, 15rem), 1fr))` : le nombre de points de
vente vient de la base, et trois colonnes en dur auraient rendu au gabarit ce
que ce lot venait de lui retirer. La grille se referme d'elle-même sur mobile,
et la section disparaît entièrement si rien n'est publié — un cadre vide vaut
moins que pas de cadre.

**`CrudController` a gagné une clé, `libelle`.** Une fiche de point de vente
n'a pas de titre, elle a une ville. Sans cette clé, supprimer une fiche aurait
annoncé « Le point de vente « — » a été supprimé » : le déroulé commun et le
gabarit d'actions de liste lisaient tous deux `titre` en dur. `titre` reste le
défaut, et aucun autre écran ne change.

**Traduisible dès l'ouverture** : la ville et l'adresse sont déclarées à
l'écran des traductions — « Londres » contre « London », et une adresse
ivoirienne qui porte un repère plutôt qu'un numéro. Ni l'enseigne, ni le
téléphone, ni le site : un nom propre ne se traduit pas.

**Une migration**, `sql/017_point_de_vente.sql`.

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/017_point_de_vente.sql` | la table `point_de_vente` et l'amorce des trois villes, publiées, sans enseigne ni adresse |

### Ce que le brief ajoute à la liste des livrables attendus

À la liste du §5 s'ajoutent, tous non techniques :

| Manquant | Qui le fournit |
|---|---|
| **Texte de la préface**, ou l'extrait à mettre en exergue, plus le **nom et la qualité du préfacier** dans la forme exacte à citer. Le mécanisme est livré (G2) : il ne manque que la matière, et une case à cocher | commanditaire |
| **Notice de l'auteur** — nom, qualité, biographie. Sa page reste en 404 tant que le nom n'est pas saisi | commanditaire / éditeur |
| **Matière d'Héritage** — les dix sujets : pont, boulevard, buste de Marcory, Jacqueville, hommages, décorations, publications, musique | commanditaire |
| **Découpage de la biographie** en douze périodes : leurs bornes, leur texte validé et sourcé. Le mécanisme est livré (G10) — l'écran attend les dates et le récit, et cinq chapitres d'amorce y sont en brouillon | commanditaire / auteur |
| **Fonds d'archives** et leurs crédits — chaque pièce publiée doit porter son fonds, son photographe ou son détenteur de droits ; le back-office refuse déjà la publication sans crédit | commanditaire / familles |
| **Compte de la chaîne vidéo**, si la décision 2 est retenue | commanditaire |
| **Politique de sauvegarde** — qui garde une copie des originaux, où, à quelle fréquence | hébergeur / commanditaire |
| **Enseignes et adresses des points de vente** — les trois villes sont en base et publiées (G12), mais aucune ne dit encore où aller : l'accueil et la page du livre affichent « Enseigne et adresse à renseigner » sous Abidjan, Yamoussoukro et Paris | commanditaire / éditeur |
| **Prix de l'ouvrage en francs CFA**, et le **point de retrait** avec ses horaires. Ce sont les deux valeurs qui ouvrent la boutique : sans prix elle reste fermée, sans point de retrait le retrait n'est pas proposé. Les **tarifs de livraison** se posent ensuite, zone par zone | commanditaire / éditeur |
| **Traduction anglaise des contenus** — biographie, notices d'archives, sujets d'Héritage, préface. **L'anglais est ouvert** : chaque champ traduit depuis le back-office retire une phrase française des pages `/en/`, et un champ non traduit y affiche le français | commanditaire / traducteur |
