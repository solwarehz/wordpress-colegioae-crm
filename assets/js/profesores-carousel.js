/**
 * profesores-carousel.js — Carrusel de profesores (home).
 * Auto-rotate scroll horizontal suave con pausa al hover.
 */

(function () {
    'use strict';

    function initCarousel(root) {
        var track = root.querySelector('.profesores__track');
        var cards = track ? Array.from(track.querySelectorAll('.profesor-card')) : [];
        var prev = root.querySelector('[data-profesor-prev]');
        var next = root.querySelector('[data-profesor-next]');
        var autoplay = parseInt(root.getAttribute('data-autoplay'), 10) || 0;

        if (!track || cards.length <= 1) return;

        var current = 0;
        var timer = null;

        function cardsPerView() {
            var w = window.innerWidth;
            if (w <= 480) return 1;
            if (w <= 700) return 2;
            if (w <= 1100) return 3;
            return 4;
        }

        function go(i) {
            var per = cardsPerView();
            if (cards.length <= per) {
                // Todos los cards caben en pantalla: no hay scroll disponible,
                // igual "saltamos" virtualmente para actualizar current y
                // mantener el loop (sin animación visible).
                current = 0;
                return;
            }
            var max = cards.length - per;
            if (i > max) i = 0;
            if (i < 0) i = max;
            current = i;

            var cardWidth = cards[0].getBoundingClientRect().width;
            var gap = parseFloat(getComputedStyle(track).columnGap || 16);
            track.scrollTo({ left: current * (cardWidth + gap), behavior: 'smooth' });
        }

        function startAutoplay() {
            if (!autoplay) return;
            stopAutoplay();
            timer = setInterval(function () { go(current + 1); }, autoplay);
        }

        function stopAutoplay() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        prev && prev.addEventListener('click', function () { go(current - 1); startAutoplay(); });
        next && next.addEventListener('click', function () { go(current + 1); startAutoplay(); });

        root.addEventListener('mouseenter', stopAutoplay);
        root.addEventListener('mouseleave', startAutoplay);

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAutoplay(); else startAutoplay();
        });

        window.addEventListener('resize', function () {
            // Reset scroll position si cambia el viewport
            go(current);
        });

        startAutoplay();
    }

    function init() {
        document.querySelectorAll('[data-profesor-carousel]').forEach(initCarousel);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
