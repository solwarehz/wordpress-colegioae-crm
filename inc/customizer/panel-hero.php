<?php
/**
 * inc/customizer/panel-hero.php
 * Panel "Hero" — ancla, velocidad, 5 slides (imagen, título, subtítulo, CTA).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_hero');

function colegio_ae_customizer_register_hero(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_hero', [
        'title'       => __('Sección: Hero', 'colegio-ae'),
        'description' => __('Slider principal del home. Deja vacíos los slides que no quieras mostrar.', 'colegio-ae'),
        'priority'    => 40,
    ]);

    /* ---------- Eye toggle (mismo setting del panel Orden) ---------- */
    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_hero_enabled', [
        'label'    => __('Mostrar sección Hero', 'colegio-ae'),
        'section'  => 'colegio_ae_section_hero',
        'priority' => 5,
    ]));

    /* ---------- ID del ancla ---------- */
    $wp_customize->add_setting('colegio_ae_section_hero_anchor', [
        'default'           => 'inicio',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_hero_anchor', [
        'label'       => __('ID del ancla', 'colegio-ae'),
        'description' => __('Default: inicio. Si lo cambias, actualiza los enlaces del menú que apunten a #inicio.', 'colegio-ae'),
        'section'     => 'colegio_ae_section_hero',
        'type'        => 'text',
        'priority'    => 10,
    ]);

    /* ---------- Velocidad auto-rotate ---------- */
    $wp_customize->add_setting('colegio_ae_hero_autoplay', [
        'default'           => 6000,
        'type'              => 'theme_mod',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('colegio_ae_hero_autoplay', [
        'label'       => __('Velocidad de auto-rotate (ms)', 'colegio-ae'),
        'description' => __('3000 = rápido, 10000 = lento. Default 6000.', 'colegio-ae'),
        'section'     => 'colegio_ae_section_hero',
        'type'        => 'number',
        'input_attrs' => ['min' => 3000, 'max' => 10000, 'step' => 500],
        'priority'    => 15,
    ]);

    /* ---------- Slides 1–5 ---------- */
    $slide_defaults = [
        1 => [
            'image' => 'https://picsum.photos/seed/ae-hero-1/1920/1080',
            'title' => 'Formamos líderes del mañana',
            'subtitle' => 'Con pensamiento crítico, valores sólidos y visión global',
            'cta_text' => 'Agenda una visita',
            'cta_url'  => '#contacto',
        ],
        2 => [
            'image' => 'https://picsum.photos/seed/ae-hero-2/1920/1080',
            'title' => 'Educación que transforma',
            'subtitle' => 'Donde cada niño descubre su potencial y aprende a confiar en él',
            'cta_text' => 'Conoce nuestra propuesta',
            'cta_url'  => '#nosotros',
        ],
        3 => [
            'image' => 'https://picsum.photos/seed/ae-hero-3/1920/1080',
            'title' => 'Más que un colegio, una familia',
            'subtitle' => 'Compromiso, respeto y excelencia que acompañan a tu hijo cada día',
            'cta_text' => 'Escríbenos',
            'cta_url'  => '#contacto',
        ],
        4 => ['image' => '', 'title' => '', 'subtitle' => '', 'cta_text' => '', 'cta_url' => ''],
        5 => ['image' => '', 'title' => '', 'subtitle' => '', 'cta_text' => '', 'cta_url' => ''],
    ];

    $priority = 20;
    foreach ($slide_defaults as $i => $d) {

        // Imagen
        $id = "colegio_ae_hero_slide_{$i}_image";
        $wp_customize->add_setting($id, [
            'default' => $d['image'], 'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $id, [
            'label' => sprintf(__('Slide %d — Imagen', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_hero', 'priority' => $priority++,
        ]));

        // Título
        $id = "colegio_ae_hero_slide_{$i}_title";
        $wp_customize->add_setting($id, [
            'default' => $d['title'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($id, [
            'label' => sprintf(__('Slide %d — Título', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_hero', 'type' => 'text', 'priority' => $priority++,
        ]);

        // Subtítulo
        $id = "colegio_ae_hero_slide_{$i}_subtitle";
        $wp_customize->add_setting($id, [
            'default' => $d['subtitle'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control($id, [
            'label' => sprintf(__('Slide %d — Subtítulo', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_hero', 'type' => 'textarea', 'priority' => $priority++,
        ]);

        // CTA texto
        $id = "colegio_ae_hero_slide_{$i}_cta_text";
        $wp_customize->add_setting($id, [
            'default' => $d['cta_text'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($id, [
            'label' => sprintf(__('Slide %d — Texto del botón', 'colegio-ae'), $i),
            'description' => __('Deja vacío si no quieres botón.', 'colegio-ae'),
            'section' => 'colegio_ae_section_hero', 'type' => 'text', 'priority' => $priority++,
        ]);

        // CTA URL
        $id = "colegio_ae_hero_slide_{$i}_cta_url";
        $wp_customize->add_setting($id, [
            'default' => $d['cta_url'], 'type' => 'theme_mod', 'sanitize_callback' => 'colegio_ae_sanitize_cta_href',
        ]);
        $wp_customize->add_control($id, [
            'label' => sprintf(__('Slide %d — URL del botón', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_hero', 'type' => 'text', 'priority' => $priority++,
        ]);
    }
}
