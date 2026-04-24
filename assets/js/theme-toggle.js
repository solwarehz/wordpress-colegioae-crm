/**
 * theme-toggle.js — Switch light/dark + persistencia en localStorage.
 * El tema inicial se aplica desde un script inline en <head> (ver functions.php)
 * para evitar FOUC.
 */

(function () {
    'use strict';

    var STORAGE_KEY = 'colegio-ae-theme';

    function getTheme() {
        try {
            return localStorage.getItem(STORAGE_KEY) || 'light';
        } catch (e) {
            return 'light';
        }
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {
            // localStorage deshabilitado — ignorar
        }
        updateToggleLabel(theme);
    }

    function updateToggleLabel(theme) {
        var btn = document.querySelector('.theme-toggle');
        if (!btn) return;
        btn.setAttribute(
            'aria-label',
            theme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'
        );
    }

    function initTray() {
        var tools = document.querySelector('[data-tools]');
        if (!tools) return;
        var toggle = tools.querySelector('.site-header__tools-toggle');
        if (!toggle) return;

        function close() {
            tools.setAttribute('data-tools', 'closed');
            toggle.setAttribute('aria-expanded', 'false');
        }
        function open() {
            tools.setAttribute('data-tools', 'open');
            toggle.setAttribute('aria-expanded', 'true');
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = tools.getAttribute('data-tools') === 'open';
            isOpen ? close() : open();
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!tools.contains(e.target)) close();
        });

        // Cerrar con Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && tools.getAttribute('data-tools') === 'open') {
                close();
                toggle.focus();
            }
        });
    }

    function init() {
        var btn = document.querySelector('.theme-toggle');
        if (btn) {
            updateToggleLabel(getTheme());
            btn.addEventListener('click', function () {
                var current = getTheme();
                setTheme(current === 'dark' ? 'light' : 'dark');
            });
        }
        initTray();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
