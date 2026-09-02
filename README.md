# Philippe Grégoire Yacé — *Une destinée* (1920-1998)

Site éditorial de l'ouvrage. **Le back-office est complet** : ossature de
`/cmsadmin/`, authentification, actualités, événements, repères, modération des
témoignages, fiche technique, tableau de bord, médiathèque, commandes et
comptes, **et la boîte de réception du formulaire de contact**. Côté public,
onze pages : accueil, Le livre, Biographie, **Témoignages**, **Actualités**
(liste, fiche par slug, revue de presse), **Archives** avec sa visionneuse,
**Événements** (agenda et fiche), **Contact** et **Mentions légales**.
**Restent Héritage et le tunnel de commande.**

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
│   │                       Validator, Slug, Site, DateFr,
│   │                       Televersement, Paiement, Debit    [interdit]
│   ├── Controller/         Actualite, Archive, Contact,
│   │                       Evenement, Temoignage (public)    [interdit]
│   ├── Controller/Admin/   Auth, Crud (Actualite, Evenement,
│   │                       Repere), Temoignage, Message,
│   │                       Media, Commande, Compte,
│   │                       Parametre                         [interdit]
│   └── Model/              Modele, Actualite, Evenement,
│                           Repere, Temoignage, Message,
│                           Media, Commande, Parametre,
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

`config/config.php` porte les valeurs MAMP locales. Pour la production, créer
`config/config.local.php` (ignoré par git) retournant un tableau partiel : il est
fusionné par-dessus.

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

**Les dates sont écrites en français par une table de douze mois**
(`App\Core\DateFr`), et non par `IntlDateFormatter`, `setlocale` ou
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

**Les dates s'écrivent comme on les dit.** `App\Core\DateFr` a gagné les
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
table de suivi, le projet en est à six fichiers. `sql/003_media.sql` ajoute à
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
  Il reste à le poser en `'url'` dans la section `app` de
  `config/config.local.php`, sur le serveur. Il n'est volontairement pas écrit
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

  Les coordonnées, elles, sont posées et vérifiées : adresse à Cocody
  Ambassade, `contact@philippeyace.ci`, +225 05 64 00 00 80. Elles vivent dans
  `config/config.php`, en un seul endroit pour les trois pages qui les
  affichent.

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
- **Aucune pagination sur les listes publiques.** Actualités, revue de presse,
  galerie et agenda rendent tout ce qui est publié. C'est le bon choix pour des
  dizaines d'entrées, et la galerie s'en tire mieux qu'on ne pourrait le croire :
  ses tuiles sont en `loading="lazy"`, un navigateur ne télécharge que ce qui
  approche de l'écran. La question se reposera au-delà de la centaine de pièces,
  où c'est le poids du HTML et la longueur de la planche qui gêneront, pas les
  images.
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
ajoutées avec les lots publics : `App\Core\DateFr`, qui écrit les dates en
français sans dépendre de la machine, et `View::paragraphes`, qui rend échappé
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

**Non fait** — Héritage, le tunnel de commande et la phase 3 du CDC. Héritage
n'attend rien de technique, tout de la matière éditoriale ; **le tunnel de
commande est le seul chantier de code qui reste.**

**Ce qui manque n'est donc plus du travail de développement, à une exception
près.** Le site attend des contenus — textes validés, visuels d'archives, fiche
technique de l'ouvrage — et quatre informations légales que seule la structure
éditrice peut fournir. Voir §5, et « Ce qui bloque » plus bas.

### Ce qui est en ligne, et ce qu'il faut y porter

Le dépôt et le serveur ne sont pas au même point, et **l'écart s'est creusé** :
le site en ligne est toujours à l'état du 1er septembre — dernier commit
déployé, « Partage social : canonical, og:image ». Depuis, quatre lots publics
ont été écrits et testés en local sans être déployés :

| Lot | Ce qui manque en ligne |
|---|---|
| **F1** | Témoignages — page publique, formulaire, aperçu sur l'accueil |
| **F2** | Actualités — liste, fiche par slug, revue de presse |
| **F3** | Archives et sa visionneuse, agenda des événements, seconde taille d'image |
| **F4** | Contact, mentions légales, boîte de réception au back-office |

S'y ajoute le **portrait de la biographie**, premier visuel réel du site.

En clair : **le serveur montre aujourd'hui quatre pages, le dépôt en porte
onze.** Tout ce qui suit dans cette section est écrit, testé et commité, mais
pas encore en production.

**Quatre migrations à jouer, dans cet ordre**, et une seule fois :

| Fichier | Ce qu'il apporte |
|---|---|
| `sql/003_media.sql` | le poids du fichier et l'unicité du chemin sur `media` (lot D2) |
| `sql/004_commande.sql` | provenance du paiement, code de transaction, note et trace de remise (lot E2) |
| `sql/005_soumission.sql` | le journal des soumissions publiques (limitation de débit) |
| `sql/006_message.sql` | la table des messages du formulaire de contact (lot F4) |

**`001_schema.sql` ne se rejoue jamais sur une base installée.** Il a été mis à
jour pour qu'une installation neuve n'ait pas à rejouer l'historique, mais ses
`CREATE TABLE IF NOT EXISTS` ne toucheraient pas des tables existantes : les
nouvelles colonnes n'arriveraient pas, et la base paraîtrait à jour sans l'être.

`003` et `004` sont des `ALTER TABLE` : les rejouer lève `Duplicate column
name`. `005` et `006` créent des tables en `IF NOT EXISTS` : les rejouer ne
casse rien, mais ne rattrape rien non plus si la table existe sous une autre
forme. Cette requête dit où en est une base — quatre zéros avant, quatre `1`
après :

```sql
SELECT 'octets sur media' AS controle, COUNT(*) AS present
  FROM information_schema.columns
 WHERE table_schema = DATABASE() AND table_name = 'media' AND column_name = 'octets'
UNION ALL SELECT 'passerelle sur commande', COUNT(*)
  FROM information_schema.columns
 WHERE table_schema = DATABASE() AND table_name = 'commande' AND column_name = 'passerelle'
UNION ALL SELECT 'table soumission_publique', COUNT(*)
  FROM information_schema.tables
 WHERE table_schema = DATABASE() AND table_name = 'soumission_publique'
UNION ALL SELECT 'table message', COUNT(*)
  FROM information_schema.tables
 WHERE table_schema = DATABASE() AND table_name = 'message';
```

Depuis la bascule de collation (voir §2), les quatre fichiers se chargent aussi
bien sous MySQL que sous MariaDB — il n'y a plus de ligne à corriger avant
d'envoyer.

**Côté fichiers**, trois points qu'un dépôt ne règle pas tout seul :

- **`medias/` doit exister et être accessible en écriture** par le serveur.
  Son `.htaccess` doit partir avec, c'est lui qui empêche l'exécution de ce qui
  y sera déposé — attention aux clients FTP qui masquent les fichiers commençant
  par un point.
- **`config/config.local.php` est ignoré par git** : il se crée à la main sur le
  serveur, avec les identifiants de production, `'debug' => false` et surtout
  `'url' => 'https://www.philippeyace.ci'` — le domaine est arrêté depuis le lot
  F4. Sans cette dernière valeur, `canonical` et `og:image` retombent sur
  l'en-tête `Host` de la requête, que le client choisit.
- **Les coordonnées publiques sont dans `config/config.php`**, donc dans le
  dépôt : adresse, courriel, téléphone, domaine. Elles alimentent la page
  Contact, les mentions légales et le pied de page. Les corriger se fait à un
  seul endroit ; les surcharger sur le serveur reste possible par
  `config.local.php`, qui est fusionné par-dessus.
- **`reference/` n'a rien à faire en production** : 70 Mo verrouillés en 403,
  à exclure explicitement de la règle de déploiement.

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
`App\Core\DateFr` pour les dates en français, `View::paragraphes` pour les
corps de texte saisis en clair, l'image de partage choisie par page, et le
patron d'une page publique adossée à une entité — liste filtrée par lien, fiche
par slug, 404 pour un brouillon. Les événements ont suivi exactement la même
forme, et la prévision s'est vérifiée : F3 n'a eu à inventer que ce qui lui est
propre — la trame de la planche, la visionneuse, les heures et les intervalles
de dates, la seconde taille d'image.

**Ce que F3 laisse à son tour :** les tailles dérivées et le `srcset` de
`App\Model\Media`, les heures et intervalles de `DateFr`, et une visionneuse
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
de cette liste, sont faits. Ce qui reste :

1. **Héritage** (§4.5), la dernière page publique hors boutique. Rien n'y bloque
   techniquement : elle attend la matière éditoriale, et elle seule.
2. **La boutique et son tunnel de commande** — la passerelle est arrêtée
   (`carte.abidjan.net`, voir §2) et décrite en un seul endroit. C'est désormais
   le seul chantier de code qui reste : la page publique de vente, puis le
   tunnel, qui créera les commandes que l'écran de suivi attend depuis le lot E2,
   avec leur code de transaction. Indépendant du point 1.
3. **Phase 3 du CDC** — newsletter, recherche interne, multilinguisme.

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
sont vides — et l'adresse publique à poser en configuration sur le serveur : le
domaine est connu, `https://www.philippeyace.ci`, mais il ne peut pas vivre
dans le dépôt.

**Depuis le lot F4, une part de ce qui manque est légale et non éditoriale** :
la page des mentions est écrite, mais l'identité de la structure éditrice, son
immatriculation, le directeur de la publication et l'hébergeur y restent à
fournir. La loi impose de les publier, et ce sont les seules informations du
site qu'aucun travail technique ne peut produire. Voir §5 pour la liste
complète, dimensions comprises.

## 8. Couverture du cahier des charges

Accueil (§4.1) — **complet** : hero slider, accroche, aperçu du livre, teaser
biographie, frise de repères, témoignages, actualités, CTA commande.

Le livre (§4.2) — **complet** : résumé long, mot de l'éditeur, fiche technique,
sommaire, extrait, feuilletage, où acheter. L'auteur (§4.3) y est traité en section
plutôt qu'en page dédiée, faute de matière ; à détacher dès que le contenu existe.

Biographie (§4.4) — **complet** : contexte historique, biographie structurée en cinq
chapitres avec sommaire latéral collé, frise chronologique filtrable par période et
dépliable, citations, galerie de portraits.

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

### Le socle applicatif : décision prise

Le CDC exige un back-office utilisable sans intervention technique, des formulaires
avec modération, des commandes et une recherche interne — rien de tout cela ne
tenait en pages statiques.

**Choix retenu : PHP structuré à la main** (contrôleur frontal, routeur, PDO,
gabarits), plutôt qu'un framework ou un CMS. Motif : garder la racine web sur
`livreyace/` — Symfony et Laravel imposent une racine en `public/` — et rester dans
la continuité du site de référence. Contrepartie assumée : le back-office est à
écrire intégralement, et c'est l'essentiel de la charge restante.
