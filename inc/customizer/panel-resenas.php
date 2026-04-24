<?php
/**
 * inc/customizer/panel-resenas.php
 * Panel "Reseñas" — título, subtítulo, 3 testimonios (foto, nombre, relación, texto, rating).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_resenas');

function colegio_ae_customizer_register_resenas(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_resenas', [
        'title'    => __('Sección: Reseñas', 'colegio-ae'),
        'priority' => 47,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_resenas_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_resenas', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_resenas_anchor', [
        'default' => 'resenas', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_resenas_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'section' => 'colegio_ae_section_resenas', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_resenas_title', [
        'default' => 'Reseñas de nuestras familias', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_resenas_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_resenas', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_resenas_subtitle', [
        'default' => 'Lo que cuentan los padres, madres y alumnos que forman parte del Colegio Albert Einstein.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_resenas_subtitle', [
        'label' => __('Subtítulo', 'colegio-ae'),
        'section' => 'colegio_ae_section_resenas', 'type' => 'textarea', 'priority' => 20,
    ]);

    /* 3 reseñas */
    $defaults = [
        1 => [
            'photo' => 'https://picsum.photos/seed/ae-testimonio-1/300/300',
            'name' => 'María Luisa Campos',
            'relation' => 'Madre de familia · 2 hijos en el colegio',
            'text' => 'Desde que mis hijos están en Albert Einstein, no solo mejoraron académicamente — se volvieron más seguros de sí mismos. Lo que más valoro es que los profesores conocen a cada uno por su nombre, por su historia. Eso no tiene precio.',
            'rating' => 5,
        ],
        2 => [
            'photo' => 'https://picsum.photos/seed/ae-testimonio-2/300/300',
            'name' => 'Javier Sánchez',
            'relation' => 'Padre de familia · hija en 4° de secundaria',
            'text' => 'Mi hija entró en 1° de secundaria con dificultades en matemáticas. Hoy está por postular a la universidad y es una de las mejores de su salón. Más allá de la mejora académica, veo a una joven líder que sabe lo que quiere.',
            'rating' => 5,
        ],
        3 => [
            'photo' => 'https://picsum.photos/seed/ae-testimonio-3/300/300',
            'name' => 'Lucía Ramírez',
            'relation' => 'Madre de familia · hijo en primaria',
            'text' => 'Elegí este colegio por la infraestructura, me quedé por los docentes. La comunicación con la institución es constante, se nota el compromiso real con cada estudiante. Mi hijo llega feliz todos los días.',
            'rating' => 5,
        ],
    ];

    $p = 25;
    foreach ($defaults as $i => $d) {
        $prefix = "colegio_ae_resena_{$i}_";

        $wp_customize->add_setting($prefix . 'photo', [
            'default' => $d['photo'], 'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $prefix . 'photo', [
            'label' => sprintf(__('Reseña %d — Foto', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_resenas', 'priority' => $p++,
        ]));

        $wp_customize->add_setting($prefix . 'name', [
            'default' => $d['name'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($prefix . 'name', [
            'label' => sprintf(__('Reseña %d — Nombre', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_resenas', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting($prefix . 'relation', [
            'default' => $d['relation'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($prefix . 'relation', [
            'label' => sprintf(__('Reseña %d — Relación con el colegio', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_resenas', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting($prefix . 'text', [
            'default' => $d['text'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control($prefix . 'text', [
            'label' => sprintf(__('Reseña %d — Texto', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_resenas', 'type' => 'textarea', 'priority' => $p++,
        ]);

        $wp_customize->add_setting($prefix . 'rating', [
            'default' => $d['rating'], 'type' => 'theme_mod',
            'sanitize_callback' => function ($v) { return max(1, min(5, (int) $v)); },
        ]);
        $wp_customize->add_control($prefix . 'rating', [
            'label' => sprintf(__('Reseña %d — Estrellas (1–5)', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_resenas', 'type' => 'select',
            'choices' => [1 => '1 ★', 2 => '2 ★★', 3 => '3 ★★★', 4 => '4 ★★★★', 5 => '5 ★★★★★'],
            'priority' => $p++,
        ]);
    }
}
