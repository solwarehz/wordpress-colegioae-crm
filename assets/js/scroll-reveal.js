/**
 * scroll-reveal.js — Revela elementos al hacer scroll usando IntersectionObserver.
 * Añade la clase `is-revealed` cuando el elemento entra en viewport.
 *
 * Selectores reveal por defecto: .section, .valor-card, .nivel-card, .sede-block,
 * .profesor-card, .post-slide, .opinion-card, .post-card.
 */

(function () {
    'use strict';

    // Respetar prefers-reduced-motion: revelar todo inmediatamente.
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    var selectors = [
        '.section',
        '.valor-card',
        '.nivel-card',
        '.sede-block',
        '.profesor-card',
        '.post-slide',
        '.opinion-card',
        '.post-card',
    ];

    function reveal(el) {
        el.classList.add('is-revealed');
    }

    function init() {
        var elements = document.querySelectorAll(selectors.join(','));
        if (!elements.length) return;

        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            elements.forEach(reveal);
            return;
        }

        // Mark elements for reveal animation
        elements.forEach(function (el) {
            el.classList.add('reveal');
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    reveal(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '0px 0px -80px 0px',
            threshold: 0.1,
        });

        elements.forEach(function (el) {
            observer.observe(el);
        });

        // Cards dentro de la misma sección: delay progresivo (stagger)
        document.querySelectorAll('.valores__grid, .servicios__grid, .resenas__grid, .blog-archive__grid, .profesores-archive__grid').forEach(function (container) {
            Array.from(container.children).forEach(function (child, i) {
                child.style.setProperty('--reveal-delay', (i * 60) + 'ms');
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
