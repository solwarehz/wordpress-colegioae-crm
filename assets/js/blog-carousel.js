/**
 * blog-carousel.js — Carrusel de "Mentalidad ganadora" (últimos posts).
 */

(function () {
    'use strict';

    function initCarousel(root) {
        var track = root.querySelector('.mentalidad__track');
        var slides = Array.from(root.querySelectorAll('.post-slide'));
        var dots = Array.from(root.querySelectorAll('[data-carousel-go]'));
        var prev = root.querySelector('[data-carousel-prev]');
        var next = root.querySelector('[data-carousel-next]');
        var autoplay = parseInt(root.getAttribute('data-autoplay'), 10) || 0;

        if (!track || slides.length <= 1) return;

        var current = 0;
        var timer = null;

        function go(i) {
            current = (i + slides.length) % slides.length;
            var slideWidth = slides[0].getBoundingClientRect().width;
            track.scrollTo({ left: current * slideWidth, behavior: 'smooth' });

            dots.forEach(function (d, idx) {
                d.classList.toggle('mentalidad__dot--active', idx === current);
            });
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

        dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () { go(i); startAutoplay(); });
        });

        root.addEventListener('mouseenter', stopAutoplay);
        root.addEventListener('mouseleave', startAutoplay);

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAutoplay(); else startAutoplay();
        });

        // Recalcular al redimensionar
        window.addEventListener('resize', function () {
            var slideWidth = slides[0].getBoundingClientRect().width;
            track.scrollLeft = current * slideWidth;
        });

        startAutoplay();
    }

    function init() {
        document.querySelectorAll('[data-carousel]').forEach(initCarousel);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
