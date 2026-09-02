/* =========================================================================
   PGY — Interactions
   Le carrousel repose sur l'instance Bootstrap (a11y, clavier, swipe,
   pause au survol). Ce fichier ne fait que brancher une peau sur ses
   evenements : indicateurs numerotes et jauge de progression.
   ========================================================================= */
(function () {
  "use strict";

  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* --- Navigation ------------------------------------------------------ */

  var nav = document.querySelector(".nav-bar");
  var menu = document.getElementById("navMenu");
  var toggle = document.querySelector(".nav-toggle");

  if (nav) {
    var onScroll = function () {
      nav.classList.toggle("is-stuck", window.scrollY > 40);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  if (toggle && menu) {
    toggle.addEventListener("click", function () {
      var open = menu.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", String(open));
    });
    // Refermer apres navigation vers une ancre.
    menu.addEventListener("click", function (e) {
      if (e.target.closest("a")) {
        menu.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
      }
    });
  }

  /* --- Hero ------------------------------------------------------------ */

  var hero = document.getElementById("heroCarousel");

  if (hero && window.bootstrap) {
    var carousel = bootstrap.Carousel.getOrCreateInstance(hero);
    var dots = Array.prototype.slice.call(document.querySelectorAll(".hero__dot"));
    var bar = document.querySelector(".hero__progress i");

    var setActive = function (index) {
      dots.forEach(function (dot, i) {
        var on = i === index;
        dot.classList.toggle("is-active", on);
        dot.setAttribute("aria-current", on ? "true" : "false");
      });

      // Relance de la jauge : on force un reflow pour rejouer l'animation.
      if (bar && !reduced) {
        bar.classList.remove("is-running");
        void bar.offsetWidth;
        bar.classList.add("is-running");
      }
    };

    dots.forEach(function (dot, i) {
      dot.addEventListener("click", function () { carousel.to(i); });
    });

    hero.addEventListener("slid.bs.carousel", function (e) { setActive(e.to); });
    setActive(0);
  }

  /* --- Frise : filtrage par periode -------------------------------------
     Le depliement des entrees est gere par le collapse de Bootstrap ; ce
     bloc ne fait que masquer les entrees hors periode. */

  var chips = document.querySelectorAll(".chip[data-period]");
  var entries = document.querySelectorAll(".chrono__item[data-period]");

  if (chips.length && entries.length) {
    chips.forEach(function (chip) {
      chip.addEventListener("click", function () {
        var wanted = chip.dataset.period;
        chips.forEach(function (c) {
          var on = c === chip;
          c.classList.toggle("is-active", on);
          c.setAttribute("aria-pressed", String(on));
        });
        entries.forEach(function (item) {
          item.hidden = wanted !== "tout" && item.dataset.period !== wanted;
        });
      });
    });
  }

  /* --- Sommaire lateral : reperage de la section courante --------------- */

  var subLinks = Array.prototype.slice.call(document.querySelectorAll(".subnav a[href^='#']"));

  if (subLinks.length && "IntersectionObserver" in window) {
    var sections = subLinks
      .map(function (a) { return document.querySelector(a.getAttribute("href")); })
      .filter(Boolean);

    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        subLinks.forEach(function (a) {
          a.classList.toggle("is-active", a.getAttribute("href") === "#" + entry.target.id);
        });
      });
    }, { rootMargin: "-30% 0px -60% 0px" });

    sections.forEach(function (el) { spy.observe(el); });
  }

  /* --- Visionneuse d'archives -------------------------------------------
     Supplement, jamais condition : chaque tuile est deja un lien vers
     l'image, et la galerie se parcourt entierement sans cette fonction. Le
     code ci-dessous intercepte ce lien quand le navigateur sait ouvrir un
     <dialog> modal ; sinon il ne fait rien et le lien joue son role.

     Le <dialog> natif porte le piege au clavier, la fermeture par Echap, le
     fond inerte et le retour du focus sur la tuile d'origine. Ce qui reste a
     ecrire tient au contenu : l'image, sa legende, et le passage d'une piece
     a la suivante. */

  var vis = document.getElementById("visionneuse");
  var tuiles = Array.prototype.slice.call(
    document.querySelectorAll("#galerie [data-visionneuse]")
  );

  if (vis && tuiles.length && typeof vis.showModal === "function") {
    var visImage = document.getElementById("visImage");
    var visLegende = document.getElementById("visLegende");
    var visCredit = document.getElementById("visCredit");
    var visCompte = document.getElementById("visCompte");
    var visPrec = document.getElementById("visPrec");
    var visSuiv = document.getElementById("visSuiv");
    var courant = 0;

    // Une seule piece : les fleches n'ont nulle part ou aller.
    if (tuiles.length < 2) {
      visPrec.hidden = true;
      visSuiv.hidden = true;
    }

    var afficher = function (index) {
      // Modulo cyclique : la derniere piece ramene a la premiere. Un agenda
      // se termine, une planche d'archives tourne.
      courant = (index + tuiles.length) % tuiles.length;

      var tuile = tuiles[courant];
      var legende = tuile.getAttribute("data-legende") || "";
      var credit = tuile.getAttribute("data-credit") || "";
      var prise = tuile.getAttribute("data-prise") || "";

      visImage.src = tuile.getAttribute("href");
      visImage.alt = tuile.getAttribute("data-alt") || "";

      visLegende.textContent = legende;
      visLegende.hidden = legende === "";

      // Le credit et la date de prise se lisent ensemble : « vers 1965 —
      // Fonds X ». Le tiret n'apparait que si les deux sont la.
      visCredit.textContent = [prise, credit].filter(Boolean).join(" — ");

      visCompte.textContent = (courant + 1) + " / " + tuiles.length;

      // Les voisines sont demandees en avance : le passage d'une piece a
      // l'autre ne doit pas montrer un cadre vide.
      [courant + 1, courant - 1].forEach(function (i) {
        var voisine = tuiles[(i + tuiles.length) % tuiles.length];
        if (voisine !== tuile) {
          new Image().src = voisine.getAttribute("href");
        }
      });
    };

    tuiles.forEach(function (tuile, index) {
      tuile.addEventListener("click", function (e) {
        // Les modificateurs sont laisses au navigateur : ouvrir l'image dans
        // un nouvel onglet reste possible.
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
        e.preventDefault();
        afficher(index);
        vis.showModal();
      });
    });

    visPrec.addEventListener("click", function () { afficher(courant - 1); });
    visSuiv.addEventListener("click", function () { afficher(courant + 1); });
    document.getElementById("visFermer").addEventListener("click", function () { vis.close(); });

    vis.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") { e.preventDefault(); afficher(courant - 1); }
      if (e.key === "ArrowRight") { e.preventDefault(); afficher(courant + 1); }
    });

    // Clic hors du cadre : le <dialog> occupe tout l'ecran, l'evenement ne
    // remonte jusqu'a lui que depuis le fond.
    vis.addEventListener("click", function (e) {
      if (e.target === vis) vis.close();
    });

    // L'image chargee est liberee a la fermeture : sans cela, la piece
    // precedente reste visible une fraction de seconde a la reouverture.
    vis.addEventListener("close", function () { visImage.removeAttribute("src"); });
  }

  /* --- Apparition au defilement ---------------------------------------
     Le decalage est calcule par groupe : les elements d'une meme section
     entrent en cascade, jamais tous ensemble. */

  var targets = document.querySelectorAll(".reveal");

  if (!targets.length) return;

  if (reduced || !("IntersectionObserver" in window)) {
    targets.forEach(function (el) { el.classList.add("is-in"); });
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      var el = entry.target;
      var group = el.parentElement
        ? Array.prototype.slice.call(el.parentElement.querySelectorAll(":scope > .reveal"))
        : [el];
      var i = group.indexOf(el);
      el.style.setProperty("--reveal-delay", (i > 0 ? i * 110 : 0) + "ms");
      el.classList.add("is-in");
      observer.unobserve(el);
    });
  }, { rootMargin: "0px 0px -12% 0px", threshold: 0.12 });

  targets.forEach(function (el) { observer.observe(el); });
})();
