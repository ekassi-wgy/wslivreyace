# Philippe Grégoire Yacé — *Une destinée* (1920-1998)

Site éditorial de l'ouvrage. **Le back-office est complet** : ossature de
`/cmsadmin/`, authentification, actualités, événements, repères, modération des
témoignages, fiche technique, tableau de bord, médiathèque, commandes et
comptes. Côté public, quatre pages : accueil, Le livre, Biographie, et
**Témoignages — la première adossée aux données, et le premier formulaire
ouvert**. **Restent les autres pages publiques et le tunnel de commande.**

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

Le schéma emploie la collation `utf8mb4_0900_ai_ci`, propre à **MySQL 8**. Sur
MariaDB, la remplacer par `utf8mb4_unicode_ci` dans les deux fichiers de
`sql/` — c'est la seule syntaxe non portable du projet.

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
│   │                       Validator, Slug, Site,
│   │                       Televersement, Paiement, Debit    [interdit]
│   ├── Controller/         Temoignage (public)               [interdit]
│   ├── Controller/Admin/   Auth, Crud (Actualite, Evenement,
│   │                       Repere), Temoignage, Media,
│   │                       Commande, Compte, Parametre       [interdit]
│   └── Model/              Modele, Actualite, Evenement,
│                           Repere, Temoignage, Media,
│                           Commande, Parametre, Utilisateur,
│                           TentativeConnexion                [interdit]
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
que par un paramètre de suivi désignent la même page.

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

Huit tables, `utf8mb4`, InnoDB — voir `sql/001_schema.sql` :
`utilisateur`, `actualite`, `evenement`, `temoignage`, `media`, `repere`,
`commande`, `parametre`. Plus deux compteurs glissants :
`tentative_connexion` (`sql/002_auth.sql`) pour les essais de connexion, et
`soumission_publique` (`sql/005_soumission.sql`) pour les formulaires publics.

Les migrations s'appliquent dans l'ordre de leur numéro ; il n'y a pas encore de
table de suivi, le projet en est à cinq fichiers. `sql/003_media.sql` ajoute à
`media` le poids du fichier et l'unicité de son chemin ; `sql/004_commande.sql`
ajoute à `commande` la provenance du paiement, le code de transaction, la note de
suivi et la trace de remise ; `sql/005_soumission.sql` crée le journal des
soumissions publiques. Tout est reporté dans `001_schema.sql` pour qu'une
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
- **Adresse publique** — poser `'url' => 'https://…'` dans la section `app` de
  `config/config.local.php`. Sans elle, `canonical` et `og:image` retombent sur
  l'hôte de la requête : acceptable en développement, pas sur un serveur public
  (voir « URL absolues » au §2).

---

## 6. Dette connue

Deux lignes qui figuraient ici sont **soldées** : la duplication de la navigation
et du pied de page, depuis le passage aux gabarits, et la limitation de débit des
formulaires publics, posée avant qu'aucun formulaire ne soit exposé (voir §2 pour
les deux). Ce qu'il reste :

- **Aucun test.** Le socle a été vérifié à la main (codes HTTP, connexion PDO,
  absence de warning, mesure du rendu au navigateur). Ces vérifications ne sont
  pas rejouables automatiquement.
- **Pas d'éditeur enrichi.** Le corps d'une actualité se saisit en texte brut,
  une ligne vide séparant deux paragraphes. L'illustration, elle, se choisit
  désormais dans la médiathèque (lot D2).
- **La médiathèque ne recadre ni ne remplace.** Une image mal cadrée se retaille
  hors du site, et changer le fichier d'une fiche demande d'en déposer un autre
  puis de supprimer le premier — un remplacement en place changerait sans le dire
  ce que montrent les pages qui l'affichent. Une seule vignette est produite
  (600 px) : la galerie publique voudra sans doute un jeu de tailles et un
  `srcset`, à décider quand elle sera écrite.
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
mise en page unique ; base `livreyace_sbd`, dix tables ; étanchéité des dossiers
applicatifs vérifiée sur Apache.

**Le back-office** — **entier**, ses sept lots livrés : de l'ossature aux
comptes et commandes, en passant par les contenus, la modération, la médiathèque
et le pilotage. Un éditeur peut aujourd'hui tout saisir, tout modérer et tout
publier sans intervention technique.

**La couche formulaire** — **complète** : jeton CSRF et validation depuis le
lot B, limitation de débit des formulaires publics, session côté visiteur
ouverte route par route, et les pièges à robots posés avec le premier
formulaire.

**Le site public** — quatre pages sur onze : accueil, Le livre, Biographie, et
Témoignages, la première adossée aux données. Plus une 404 dessinée dans la
charte et servie par le routeur.

**Non fait** — les sept pages publiques restantes, le tunnel de commande, et la
phase 3 du CDC. Tout ce qui reste est du côté visiteur : il n'y a plus une seule
ligne de back-office à écrire.

### Le back-office, lot par lot

Le back-office est livré par lots, validables l'un après l'autre. **Les sept
sont posés.** Pendant la construction, les entrées non encore écrites restaient
visibles dans la barre latérale, verrouillées : la forme finale de l'outil se
voyait dès le premier lot. Il n'en reste aucune — le mécanisme, lui, reste en
place pour la suite.

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
l'autre, dans l'ordre où les dépendances tombent. Le découpage ci-dessous est une
proposition, pas un engagement — seul F1 est livré.

| Lot | Objet | État |
|---|---|---|
| **F1** | Témoignages — page publique, formulaire de dépôt, aperçu sur l'accueil | livré |
| **F2** | Actualités — liste, détail par slug, revue de presse | à venir |
| **F3** | Galerie/Archives avec visionneuse, et Événements | à venir |
| **F4** | Contact et mentions légales | à venir |

**F1 en premier, et ce n'était pas un hasard** : c'est la seule tranche
verticale complète qui restait — écriture, validation, modération, affichage —
et elle porte la plomberie que les suivantes réutiliseront. F2 et F3 ne font que
lire ce que le back-office remplit déjà ; F4 réutilisera le formulaire de F1,
son barème de débit étant même déjà déclaré.

Restent en dehors de ce découpage : **Héritage** (§4.5), qui n'attend rien de
technique mais tout de la matière éditoriale, et la **Boutique** (§4.9), qui est
le tunnel de commande — voir ci-dessous.

### Prochaines étapes, dans l'ordre

Le back-office et la couche formulaire, qui occupaient les deux premières places
de cette liste, sont faits. Ce qui reste :

1. **Les pages publiques restantes** — lots F2 à F4 ci-dessus, puis Héritage.
   Rapides : les données se saisissent déjà, le système de composants existe, il
   n'y a plus qu'à lire ce qui est en base.
2. **Le tunnel de commande** — la passerelle est arrêtée (`carte.abidjan.net`,
   voir §2) et décrite en un seul endroit. Il suppose la page boutique, donc le
   point 1. Il créera les commandes que l'écran de suivi attend, avec leur code
   de transaction.
3. **Phase 3 du CDC** — newsletter, recherche interne, multilinguisme.

Deux décisions à prendre en chemin, aucune bloquante aujourd'hui : quelles pages
font entrer la barre de navigation, qui porte quatre entrées et le bouton de
commande — Témoignages n'y figure pas, et Actualités voudra sans doute sa place
avant elle ; et ouvrir ou non la saisie manuelle d'une commande, pour celles qui
se prendraient au téléphone ou en dédicace (voir §6).

### Ce qui bloque, et sur qui

**Les contenus, et eux seuls désormais.** Le logotype, qui ouvrait la liste des
livrables attendus au §5, est arrivé et intégré ; il ne reste plus rien de
technique en attente d'un tiers.

Tout le texte éditorial du site est provisoire et balisé comme tel, et **aucune
ligne ne peut être publiée sans validation de l'éditeur** — Yacé est une figure
historique réelle. S'y ajoutent les visuels d'archives, la fiche technique de
l'ouvrage — six de ses huit valeurs sont vides — et l'adresse publique à poser
en configuration avant toute mise en ligne. Voir §5 pour la liste complète,
dimensions comprises.

## 8. Couverture du cahier des charges

Accueil (§4.1) — **complet** : hero slider, accroche, aperçu du livre, teaser
biographie, frise de repères, témoignages, actualités, CTA commande.

Le livre (§4.2) — **complet** : résumé long, mot de l'éditeur, fiche technique,
sommaire, extrait, feuilletage, où acheter. L'auteur (§4.3) y est traité en section
plutôt qu'en page dédiée, faute de matière ; à détacher dès que le contenu existe.

Biographie (§4.4) — **complet** : contexte historique, biographie structurée en cinq
chapitres avec sommaire latéral collé, frise chronologique filtrable par période et
dépliable, citations, galerie de portraits.

Témoignages (§4.8) — **complet** : page publique, formulaire de dépôt, file de
modération, et l'aperçu des trois derniers validés sur l'accueil. La chaîne
entière tourne, du visiteur qui écrit au modérateur qui décide.

Reste à construire, côté public — Héritage (§4.5), Galerie/Archives (§4.6),
Actualités/Presse (§4.7), Boutique (§4.9), Événements (§4.10), Contact (§4.11),
Mentions légales (§4.12). **Le back-office de tout cela existe** ; ce sont les
pages qui manquent.

Nuance sur trois d'entre elles : **actualités, événements et archives ont leur
back-office** — les données se saisissent, s'illustrent et se publient. Seules
manquent les pages qui les afficheront, et elles seront rapides : il n'y a plus
qu'à lire ce qui existe. Galerie/Archives comprise : la médiathèque est écrite,
ses images portent légende, crédit, catégorie et rang, et une image publiée est
une image créditée.

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
