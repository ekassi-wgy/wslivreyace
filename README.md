# Philippe Grégoire Yacé — *Une destinée* (1920-1998)

Site éditorial de l'ouvrage. Livraison en cours : **socle de design + page d'accueil**
(phase 1 du cahier des charges).

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

---

## 2. Fichiers

Le site occupe la racine : MAMP y pointe directement
(`DocumentRoot "…/projetsmamp/livreyace"`). PHP 8.3, MySQL 8, base `livreyace_sbd`.

```
livreyace/                  ← racine web
├── .htaccess               réécriture vers le contrôleur frontal
├── index.php               contrôleur frontal : autoload, routes
├── assets/                 css, js, visuels du thème        [public]
├── medias/                 fichiers téléversés               [public]
├── config/                 configuration                     [interdit]
├── src/
│   ├── Core/               Config, Database, Router, View    [interdit]
│   ├── Controller/                                           [interdit]
│   └── Model/                                                [interdit]
├── templates/
│   ├── layout.php          mise en page unique               [interdit]
│   ├── partials/           navigation, pied                  [interdit]
│   └── pages/              corps des pages                   [interdit]
├── sql/                    migrations                        [interdit]
└── reference/              ancien site Abidjan.net           [interdit]
```

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

### La duplication du chrome est soldée

Navigation et pied de page vivaient en trois exemplaires dans les pages statiques.
Ils sont désormais dans `templates/partials/`, inclus une seule fois par
`templates/layout.php`. Vérifié sur les trois pages : un seul `<header>`, un seul
`<main>`, un seul `<footer>` par rendu.

### Base de données

Huit tables, `utf8mb4`, InnoDB — voir `sql/001_schema.sql` :
`utilisateur`, `actualite`, `evenement`, `temoignage`, `media`, `repere`,
`commande`, `parametre`.

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

- **Logotype** — le verrou de `index.html` est une reconstruction HTML approchée.
  Remplacer par le SVG officiel (`.logo`, présent dans l'en-tête et le pied).
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

La navigation et le pied de page sont **dupliqués à l'identique** dans les trois pages
(vérifié par empreinte). C'est tenable à trois pages, pas à douze : ils doivent devenir
des gabarits partagés dès que le socle applicatif est choisi. C'est la première tâche
de la phase 2.

## 7. Couverture du cahier des charges

Accueil (§4.1) — **complet** : hero slider, accroche, aperçu du livre, teaser
biographie, frise de repères, témoignages, actualités, CTA commande.

Le livre (§4.2) — **complet** : résumé long, mot de l'éditeur, fiche technique,
sommaire, extrait, feuilletage, où acheter. L'auteur (§4.3) y est traité en section
plutôt qu'en page dédiée, faute de matière ; à détacher dès que le contenu existe.

Biographie (§4.4) — **complet** : contexte historique, biographie structurée en cinq
chapitres avec sommaire latéral collé, frise chronologique filtrable par période et
dépliable, citations, galerie de portraits.

Transverses (§5) — **partiel** : responsive, accessibilité, structure sémantique,
métadonnées, schema.org `Book`/`Person`, lazy loading sont en place. Newsletter,
recherche interne, multilinguisme, partage social et back-office **ne sont pas
réalisables en HTML statique** — voir ci-dessous.

### Point d'architecture à trancher

Le CDC demande formulaires de témoignage avec modération, gestion d'actualités et
d'événements sans intervention technique, commande en ligne avec confirmation par
email, et recherche interne. **Aucun de ces besoins ne tient en pages statiques.**

Le site de référence (Abidjan.net) répondait à ce problème avec des gabarits PHP et un
backend de paiement séparé sur `carte.abidjan.net`. La décision de socle applicatif
(CMS, framework PHP, ou statique + services externes) conditionne les phases 2 et 3 et
doit être prise avant de dupliquer les gabarits.
