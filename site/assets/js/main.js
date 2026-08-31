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
