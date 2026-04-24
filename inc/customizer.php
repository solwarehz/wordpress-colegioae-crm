<?php
/**
 * inc/customizer.php
 *
 * Loader del Customizer del tema.
 * Cada panel vive en su propio archivo dentro de inc/customizer/.
 *
 * Orden de carga:
 *  1) Helpers y registro (sin hooks, funciones puras)
 *  2) Custom controls (deben existir antes de que corra customize_register)
 *  3) Paneles (cada uno engancha su propio customize_register)
 *  4) Enqueue de CSS/JS del Customizer (solo contexto admin)
 */

defined('ABSPATH') || exit;

$ae_customizer_dir = COLEGIO_AE_DIR . '/inc/customizer';

/* 1) Helpers + registro de secciones + defaults centralizados */
require_once $ae_customizer_dir . '/helpers.php';
require_once $ae_customizer_dir . '/sections-registry.php';
require_once $ae_customizer_dir . '/defaults.php';

/* 2) Custom controls (se requieren antes de customize_register) */
add_action('customize_register', function () use ($ae_customizer_dir) {
    require_once $ae_customizer_dir . '/controls/class-eye-toggle.php';
    require_once $ae_customizer_dir . '/controls/class-sortable.php';
    require_once $ae_customizer_dir . '/controls/class-multicheck.php';
}, 1);

/* 3) Paneles */
require_once $ae_customizer_dir . '/panel-orden.php';
require_once $ae_customizer_dir . '/panel-global.php';
require_once $ae_customizer_dir . '/panel-header.php';
require_once $ae_customizer_dir . '/panel-footer.php';
require_once $ae_customizer_dir . '/panel-hero.php';
require_once $ae_customizer_dir . '/panel-nosotros.php';
require_once $ae_customizer_dir . '/panel-valores.php';
require_once $ae_customizer_dir . '/panel-servicios.php';
require_once $ae_customizer_dir . '/panel-sedes.php';
require_once $ae_customizer_dir . '/panel-profesores.php';
require_once $ae_customizer_dir . '/panel-mentalidad.php';
require_once $ae_customizer_dir . '/panel-resenas.php';
require_once $ae_customizer_dir . '/panel-contacto.php';

/* Output del CSS dinámico basado en theme_mods */
require_once $ae_customizer_dir . '/output-css.php';

/* Formularios (Tally) — panel temporal de Fase 1.
 * En Sprint 2.4 se movera a panel-contacto.php. Por ahora mantenemos el
 * código original para no romper nada. */
add_action('customize_register', 'colegio_ae_customizer_register_formularios');

function colegio_ae_customizer_register_formularios(WP_Customize_Manager $wp_customize) {
    $wp_customize->add_section('colegio_ae_forms', [
        'title'       => __('Formularios (Tally)', 'colegio-ae'),
        'description' => __('Pega aquí el código HTML (iframe o script) que Tally te da al crear tus formularios en tally.so. Sugerido: usa el botón "Share" → "Embed on your website" → copia el código.', 'colegio-ae'),
        'priority'    => 30,
    ]);

    $wp_customize->add_setting('colegio_ae_tally_contacto', [
        'default'           => '',
        'sanitize_callback' => 'colegio_ae_sanitize_embed',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_tally_contacto', [
        'label'       => __('Embed de contacto', 'colegio-ae'),
        'description' => __('Formulario de la sección "Escríbenos" en el home. Si queda vacío se muestra un formulario de respaldo ilustrativo.', 'colegio-ae'),
        'section'     => 'colegio_ae_forms',
        'type'        => 'textarea',
    ]);

    $wp_customize->add_setting('colegio_ae_tally_reclamaciones', [
        'default'           => '',
        'sanitize_callback' => 'colegio_ae_sanitize_embed',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_tally_reclamaciones', [
        'label'       => __('Embed del libro de reclamaciones', 'colegio-ae'),
        'description' => __('Formulario legal del libro de reclamaciones. Los campos deben cumplir con lo exigido por la Ley 29571 e Indecopi.', 'colegio-ae'),
        'section'     => 'colegio_ae_forms',
        'type'        => 'textarea',
    ]);
}

/**
 * Sanitiza el código embed (permite iframe y script de tally.so).
 */
function colegio_ae_sanitize_embed($input) {
    $allowed = [
        'iframe' => [
            'src' => true, 'width' => true, 'height' => true, 'frameborder' => true,
            'allowfullscreen' => true, 'title' => true, 'loading' => true,
            'marginheight' => true, 'marginwidth' => true, 'scrolling' => true,
            'style' => true, 'data-tally-src' => true,
        ],
        'div'    => [
            'style' => true, 'class' => true, 'id' => true,
            'data-tally-src' => true, 'data-tally-open' => true,
            'data-tally-emoji-text' => true, 'data-tally-emoji-animation' => true,
        ],
        'script' => ['src' => true, 'async' => true, 'defer' => true, 'type' => true],
        'a'      => ['href' => true, 'target' => true, 'rel' => true, 'class' => true],
    ];
    return wp_kses($input, $allowed);
}

/**
 * Helper: imprime el embed guardado para una key dada, o un fallback.
 */
function colegio_ae_render_tally_embed($key, $fallback_html = '') {
    $code = get_theme_mod('colegio_ae_tally_' . $key, '');
    if (!empty($code)) {
        echo $code;
    } else {
        echo $fallback_html;
    }
}

/* 4) Enqueue de CSS/JS del Customizer — solo contexto admin */
add_action('customize_controls_enqueue_scripts', 'colegio_ae_enqueue_customizer_assets');

function colegio_ae_enqueue_customizer_assets() {
    wp_enqueue_style(
        'colegio-ae-customizer',
        COLEGIO_AE_URI . '/assets/css/admin/customizer.css',
        [],
        COLEGIO_AE_VERSION
    );
    wp_enqueue_script(
        'colegio-ae-customizer',
        COLEGIO_AE_URI . '/assets/js/admin/customizer.js',
        ['jquery', 'jquery-ui-sortable', 'customize-controls'],
        COLEGIO_AE_VERSION,
        true
    );
}
