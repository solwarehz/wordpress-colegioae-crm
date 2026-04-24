/**
 * main.js — Inicialización global del tema.
 * Smooth scroll por anclajes con compensación del header sticky.
 */

(function () {
    'use strict';

    document.addEventListener('click', function (e) {
        var link = e.target.closest('a[href*="#"]');
        if (!link) return;

        var href = link.getAttribute('href');
        if (!href) return;

        var hashIndex = href.indexOf('#');
        if (hashIndex === -1) return;

        var hash = href.slice(hashIndex);
        if (hash === '#' || hash === '') return;

        // Si el link apunta a otra página con un ancla, dejar que el navegador gestione
        var pathBeforeHash = href.slice(0, hashIndex);
        if (pathBeforeHash && pathBeforeHash !== window.location.pathname && pathBeforeHash !== window.location.href) {
            return;
        }

        var target = document.querySelector(hash);
        if (!target) return;

        e.preventDefault();

        var header = document.querySelector('.site-header');
        var adminBar = document.getElementById('wpadminbar');
        var headerH = header ? header.offsetHeight : 0;
        var adminBarH = (adminBar && getComputedStyle(adminBar).position === 'fixed') ? adminBar.offsetHeight : 0;
        var offset = headerH + adminBarH;
        var top = target.getBoundingClientRect().top + window.pageYOffset - offset - 8;

        window.scrollTo({ top: top, behavior: 'smooth' });

        if (history.pushState) {
            history.pushState(null, '', hash);
        }
    });
})();
