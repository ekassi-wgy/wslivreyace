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

```
site/
├── index.html              page d'accueil
└── assets/
    ├── css/tokens.css      jetons : couleurs, typo, rythme, mouvement
    ├── css/base.css        reset, neutralisation Bootstrap, typographie
    ├── css/components.css  navigation, hero, boutons, frise, galerie, pied
    ├── js/main.js          navigation, peau du carrousel, apparitions
    └── img/*.svg           visuels d'attente — à remplacer
```

---

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
- **Visuels** — les huit `.svg` de `assets/img/` sont des cadres d'attente générés.
  Prévoir un traitement homogène des archives (voir CDC §6).
- **Contenus** — tout le texte éditorial est balisé provisoire. **Les dates de la frise
  et les citations doivent être validées avant publication** : Yacé est une figure
  historique réelle, aucun propos ne doit lui être attribué sans source.
- **URL absolue** — `og:image` et `canonical` pointent sur `example.org`.

---

## 6. Couverture du cahier des charges

Accueil (§4.1) — **complet** : hero slider, accroche, aperçu du livre, teaser
biographie, frise de repères, témoignages, actualités, CTA commande.

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
