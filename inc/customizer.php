<?php
/**
 * inc/customizer.php
 *
 * Apariencia → Personalizar → Formularios (Tally).
 * Fase 1: solo 2 campos para los embeds de Tally (contacto + reclamaciones).
 * Fase 2: panel completo con todos los ajustes del sitio.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register');

function colegio_ae_customizer_register(WP_Customize_Manager $wp_customize) {

    /* ================== Sección: Formularios (Tally) ================== */
    $wp_customize->add_section('colegio_ae_forms', [
        'title'       => __('Formularios (Tally)', 'colegio-ae'),
        'description' => __('Pega aquí el código HTML (iframe o script) que Tally te da al crear tus formularios en tally.so. Sugerido: usa el botón "Share" → "Embed on your website" → copia el código.', 'colegio-ae'),
        'priority'    => 30,
    ]);

    // Contacto
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

    // Libro de reclamaciones
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
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'frameborder'     => true,
            'allowfullscreen' => true,
            'title'           => true,
            'loading'         => true,
            'marginheight'    => true,
            'marginwidth'     => true,
            'scrolling'       => true,
            'style'           => true,
            'data-tally-src'  => true,
        ],
        'div'    => ['style' => true, 'class' => true, 'id' => true, 'data-tally-src' => true, 'data-tally-open' => true, 'data-tally-emoji-text' => true, 'data-tally-emoji-animation' => true],
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
        // Ya sanitizado al guardar; salida directa.
        echo $code;
    } else {
        echo $fallback_html;
    }
}
