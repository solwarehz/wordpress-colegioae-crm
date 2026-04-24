<?php
/**
 * inc/customizer/selective-refresh.php
 *
 * Habilita selective_refresh para los settings de texto más visibles.
 * Cambios sin recarga completa del preview = experiencia de edición fluida.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_register_selective_refresh');

function colegio_ae_register_selective_refresh(WP_Customize_Manager $wp_customize) {

    if (!isset($wp_customize->selective_refresh)) {
        return;
    }

    /* Cambiar transport a postMessage para los settings de texto */
    $postmessage_settings = [
        'colegio_ae_footer_tagline',
        'colegio_ae_footer_col2_title',
        'colegio_ae_footer_col3_title',
        'colegio_ae_footer_copyright',
        'colegio_ae_cta_text',
        'colegio_ae_nosotros_title',
        'colegio_ae_nosotros_p1',
        'colegio_ae_nosotros_p2',
        'colegio_ae_valores_title',
        'colegio_ae_valores_subtitle',
        'colegio_ae_servicios_title',
        'colegio_ae_servicios_subtitle',
        'colegio_ae_sedes_title',
        'colegio_ae_sedes_intro',
        'colegio_ae_profesores_title',
        'colegio_ae_profesores_subtitle',
        'colegio_ae_mentalidad_title',
        'colegio_ae_mentalidad_subtitle',
        'colegio_ae_mentalidad_intro',
        'colegio_ae_resenas_title',
        'colegio_ae_resenas_subtitle',
        'colegio_ae_contacto_title',
        'colegio_ae_contacto_subtitle',
        'colegio_ae_contacto_intro',
    ];

    foreach ($postmessage_settings as $setting_id) {
        $setting = $wp_customize->get_setting($setting_id);
        if ($setting) {
            $setting->transport = 'postMessage';
        }
    }

    /* Selective refresh por sección — re-render del bloque completo */
    $sections_to_refresh = [
        'nosotros'   => ['.nosotros',       'home/nosotros'],
        'valores'    => ['.valores',        'home/valores'],
        'servicios'  => ['.servicios',      'home/servicios'],
        'sedes'      => ['.sedes',          'home/sedes'],
        'profesores' => ['.profesores',     'home/profesores'],
        'mentalidad' => ['.mentalidad',     'home/mentalidad-ganadora'],
        'resenas'    => ['.resenas',        'home/opiniones'],
        'contacto'   => ['.contacto',       'home/contacto'],
    ];

    foreach ($sections_to_refresh as $slug => $config) {
        list($selector, $template) = $config;
        $wp_customize->selective_refresh->add_partial("colegio_ae_section_{$slug}", [
            'selector'        => $selector,
            'render_callback' => function () use ($template) {
                get_template_part('template-parts/' . $template);
            },
        ]);
    }
}
