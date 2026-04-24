/**
 * customizer-preview.js — actualización instantánea de textos en el preview
 * sin recargar la página, para los settings con transport: postMessage.
 */

(function ($, api) {
    'use strict';

    function bindText(settingId, selector) {
        api(settingId, function (value) {
            value.bind(function (newval) {
                $(selector).text(newval);
            });
        });
    }

    api('colegio_ae_cta_text',                function (v) { v.bind(function (n) { $('.site-header__cta').text(n); }); });
    api('colegio_ae_footer_tagline',          function (v) { v.bind(function (n) { $('.site-footer__tagline').text(n); }); });
    api('colegio_ae_footer_col2_title',       function (v) { v.bind(function (n) { $('.site-footer__col--nav .site-footer__col-title').text(n); }); });
    api('colegio_ae_footer_col3_title',       function (v) { v.bind(function (n) { $('.site-footer__col--social .site-footer__col-title').text(n); }); });
    api('colegio_ae_footer_copyright',        function (v) { v.bind(function (n) {
        var year = new Date().getFullYear();
        $('.site-footer__copyright').html('© ' + year + ' ' + $('<div>').text(n).html());
    }); });

    bindText('colegio_ae_nosotros_title',     '.nosotros__title');
    bindText('colegio_ae_valores_title',      '.valores__title');
    bindText('colegio_ae_valores_subtitle',   '.valores__subtitle');
    bindText('colegio_ae_servicios_title',    '.servicios__title');
    bindText('colegio_ae_servicios_subtitle', '.servicios__subtitle');
    bindText('colegio_ae_sedes_title',        '.sedes__title');
    bindText('colegio_ae_sedes_intro',        '.sedes__subtitle');
    bindText('colegio_ae_profesores_title',   '.profesores__title');
    bindText('colegio_ae_profesores_subtitle','.profesores__subtitle');
    bindText('colegio_ae_mentalidad_title',   '.mentalidad__title');
    bindText('colegio_ae_mentalidad_subtitle','.mentalidad__subtitle');
    bindText('colegio_ae_mentalidad_intro',   '.mentalidad__intro');
    bindText('colegio_ae_resenas_title',      '.resenas__title');
    bindText('colegio_ae_resenas_subtitle',   '.resenas__subtitle');
    bindText('colegio_ae_contacto_title',     '.contacto__title');
    bindText('colegio_ae_contacto_subtitle',  '.contacto__subtitle');
    bindText('colegio_ae_contacto_intro',     '.contacto__intro');

    api('colegio_ae_nosotros_p1', function (v) { v.bind(function (n) {
        $('.nosotros__text p').eq(0).text(n);
    }); });
    api('colegio_ae_nosotros_p2', function (v) { v.bind(function (n) {
        $('.nosotros__text p').eq(1).text(n);
    }); });

})(jQuery, wp.customize);
