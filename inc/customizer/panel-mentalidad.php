<?php
/**
 * inc/customizer/panel-mentalidad.php
 * Panel "Mentalidad ganadora" — título, subtítulo, intro, categorías a filtrar, número de posts, velocidad.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_mentalidad');

function colegio_ae_customizer_register_mentalidad(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_mentalidad', [
        'title'    => __('Sección: Mentalidad ganadora', 'colegio-ae'),
        'priority' => 46,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_mentalidad_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_mentalidad_anchor', [
        'default' => 'mentalidad-ganadora', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_mentalidad_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_mentalidad_title', [
        'default' => 'Mentalidad ganadora', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_mentalidad_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_mentalidad_subtitle', [
        'default' => 'Porque formar líderes es también formar carácter.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_mentalidad_subtitle', [
        'label' => __('Subtítulo', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'type' => 'text', 'priority' => 20,
    ]);

    $wp_customize->add_setting('colegio_ae_mentalidad_intro', [
        'default' => 'En los concursos en los que participamos, queremos ganar. Si no ganamos, damos pelea. No nos rendimos y seguimos preparándonos. Porque más que los premios, lo que nos importa es que nuestros estudiantes desarrollen la disciplina, la confianza y la resiliencia que los acompañarán toda la vida.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_mentalidad_intro', [
        'label' => __('Texto introductorio', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'type' => 'textarea', 'priority' => 25,
    ]);

    /* Multicheck dinámico de categorías */
    $cats = get_categories(['hide_empty' => false]);
    $choices = [];
    foreach ($cats as $cat) {
        $choices[$cat->slug] = $cat->name . ' (' . $cat->count . ')';
    }

    $wp_customize->add_setting('colegio_ae_mentalidad_categories', [
        'default' => 'concursos',
        'type' => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_category_slugs',
    ]);
    $wp_customize->add_control(new Colegio_AE_Multicheck_Control($wp_customize, 'colegio_ae_mentalidad_categories', [
        'label' => __('Categorías a mostrar', 'colegio-ae'),
        'description' => __('Marca las categorías cuyos posts aparecerán en este carrusel. Si no marcas ninguna, la sección queda vacía. Siempre se muestra el más reciente primero.', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad',
        'choices' => $choices,
        'priority' => 30,
    ]));

    $wp_customize->add_setting('colegio_ae_mentalidad_count', [
        'default' => 5, 'type' => 'theme_mod', 'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('colegio_ae_mentalidad_count', [
        'label' => __('Cuántos posts mostrar', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'type' => 'number',
        'input_attrs' => ['min' => 3, 'max' => 10, 'step' => 1], 'priority' => 35,
    ]);

    $wp_customize->add_setting('colegio_ae_mentalidad_autoplay', [
        'default' => 5000, 'type' => 'theme_mod', 'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('colegio_ae_mentalidad_autoplay', [
        'label' => __('Velocidad del carrusel (ms)', 'colegio-ae'),
        'section' => 'colegio_ae_section_mentalidad', 'type' => 'number',
        'input_attrs' => ['min' => 3000, 'max' => 10000, 'step' => 500], 'priority' => 40,
    ]);
}
