/**
 * nav.js — Menú móvil (hamburger).
 */

(function () {
    'use strict';

    function init() {
        var nav = document.querySelector('.site-nav');
        if (!nav) return;

        var toggle = nav.querySelector('.site-nav__toggle');
        var list = nav.querySelector('.site-nav__list');
        if (!toggle || !list) return;

        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('site-nav--open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        // Cerrar al hacer click en un link del menú
        list.addEventListener('click', function (e) {
            var link = e.target.closest('a');
            if (!link) return;
            nav.classList.remove('site-nav--open');
            toggle.setAttribute('aria-expanded', 'false');
        });

        // Cerrar al cambiar a viewport grande
        var mq = window.matchMedia('(min-width: 1024px)');
        var handleMq = function (e) {
            if (e.matches) {
                nav.classList.remove('site-nav--open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        };
        if (mq.addEventListener) {
            mq.addEventListener('change', handleMq);
        } else if (mq.addListener) {
            mq.addListener(handleMq);
        }

        // Cerrar con Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('site-nav--open')) {
                nav.classList.remove('site-nav--open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
