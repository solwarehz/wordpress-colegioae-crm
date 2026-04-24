/**
 * customizer.js — Inicialización de los controles custom del Customizer.
 * Se carga solo en Apariencia → Personalizar.
 */

(function ($, wp) {
    'use strict';

    /**
     * Sortable: jQuery UI Sortable sobre las listas .colegio-ae-sortable__list.
     * Al reordenar, actualiza el input hidden con slugs coma-separados y
     * dispara change() para que el Customizer detecte el cambio.
     */
    function initSortable($root) {
        $root.find('.colegio-ae-sortable__list').each(function () {
            var $list = $(this);
            if ($list.data('ae-sortable-ready')) return;

            $list.sortable({
                axis: 'y',
                placeholder: 'ui-sortable-placeholder',
                tolerance: 'pointer',
                forcePlaceholderSize: true,
                update: function () {
                    var $wrap = $list.closest('.colegio-ae-sortable');
                    var slugs = $list.find('.colegio-ae-sortable__item')
                        .map(function () { return $(this).data('slug'); })
                        .get();
                    $wrap.find('.colegio-ae-sortable__input')
                        .val(slugs.join(','))
                        .trigger('change');
                }
            }).disableSelection();

            $list.data('ae-sortable-ready', true);
        });
    }

    /**
     * Eye toggle: el checkbox hace toda la magia. Solo aseguramos que el
     * cambio de estado dispare change() (WP lo hace de forma nativa, pero
     * dejamos el hook por si queremos más lógica).
     */
    function initEyeToggle($root) {
        $root.find('.colegio-ae-eye-toggle__input').each(function () {
            var $input = $(this);
            if ($input.data('ae-eye-ready')) return;

            $input.on('change', function () {
                var $wrap = $input.closest('.colegio-ae-eye-toggle');
                $wrap.attr('data-state', $input.is(':checked') ? 'on' : 'off');
            });

            $input.data('ae-eye-ready', true);
        });
    }

    /**
     * Inicialización inicial + cuando se agregan controles dinámicamente.
     */
    $(function () {
        initSortable($(document));
        initEyeToggle($(document));
    });

    // Customizer re-renderiza controles cuando cambias de sección; engancharse
    // al evento nativo para inicializar cualquier control nuevo.
    if (typeof wp !== 'undefined' && wp.customize) {
        wp.customize.bind('ready', function () {
            wp.customize.control.bind('add', function (control) {
                control.deferred.embedded.done(function () {
                    initSortable(control.container);
                    initEyeToggle(control.container);
                });
            });
        });
    }

})(jQuery, window.wp);
