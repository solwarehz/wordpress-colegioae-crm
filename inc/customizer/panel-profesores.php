<?php
/**
 * inc/customizer/panel-profesores.php
 * Panel "Profesores" — sección home con carrusel de profesores destacados.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_profesores');

function colegio_ae_customizer_register_profesores(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_profesores', [
        'title'    => __('Sección: Profesores', 'colegio-ae'),
        'priority' => 45,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_profesores_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_profesores_anchor', [
        'default' => 'profesores', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_profesores_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_profesores_title', [
        'default' => 'Nuestros profesores', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_profesores_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_profesores_subtitle', [
        'default' => 'Profesionales apasionados por enseñar, en formación constante, que creen que enseñar es acompañar a descubrir.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_profesores_subtitle', [
        'label' => __('Subtítulo', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'textarea', 'priority' => 20,
    ]);

    $wp_customize->add_setting('colegio_ae_profesores_count', [
        'default' => 5, 'type' => 'theme_mod', 'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('colegio_ae_profesores_count', [
        'label' => __('Cuántos profesores mostrar', 'colegio-ae'),
        'description' => __('Entre 3 y 10 profesores. Se muestran los más recientes.', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'number',
        'input_attrs' => ['min' => 3, 'max' => 10, 'step' => 1], 'priority' => 25,
    ]);

    $wp_customize->add_setting('colegio_ae_profesores_autoplay', [
        'default' => 4500, 'type' => 'theme_mod', 'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('colegio_ae_profesores_autoplay', [
        'label' => __('Velocidad de auto-rotate (ms)', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'number',
        'input_attrs' => ['min' => 3000, 'max' => 10000, 'step' => 500], 'priority' => 30,
    ]);

    $wp_customize->add_setting('colegio_ae_profesores_btn_text', [
        'default' => 'Ver todos los profesores', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_profesores_btn_text', [
        'label' => __('Texto del botón "Ver todos"', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'text', 'priority' => 35,
    ]);

    $wp_customize->add_setting('colegio_ae_profesores_btn_url', [
        'default' => '/profesores/', 'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('colegio_ae_profesores_btn_url', [
        'label' => __('URL del listado completo', 'colegio-ae'),
        'description' => __('Default: /profesores/', 'colegio-ae'),
        'section' => 'colegio_ae_section_profesores', 'type' => 'text', 'priority' => 40,
    ]);
}
