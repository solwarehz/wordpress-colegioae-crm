<?php
/**
 * inc/customizer/panel-sedes.php
 * Panel "Sedes" — título, intro, 3 sedes (nombre, dirección, descripción, niveles, fotos).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_sedes');

function colegio_ae_customizer_register_sedes(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_sedes', [
        'title'    => __('Sección: Sedes', 'colegio-ae'),
        'priority' => 44,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_sedes_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_sedes', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_sedes_anchor', [
        'default' => 'sedes', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_sedes_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'section' => 'colegio_ae_section_sedes', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_sedes_title', [
        'default' => 'Nuestras sedes', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_sedes_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_sedes', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_sedes_intro', [
        'default' => 'Contamos con 3 sedes en Huaraz, pensadas para que cada familia encuentre la opción más cercana. Todas comparten nuestra filosofía y estándares académicos.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_sedes_intro', [
        'label' => __('Texto introductorio', 'colegio-ae'),
        'section' => 'colegio_ae_section_sedes', 'type' => 'textarea', 'priority' => 20,
    ]);

    /* 3 sedes */
    $defaults = [
        1 => [
            'name' => 'Sede Principal', 'address' => 'Jr. Principal 123, Huaraz',
            'desc' => 'Nuestra sede principal alberga los tres niveles educativos. Aulas cómodas, espacios para el juego y el deporte, biblioteca y laboratorio, pensados para que cada estudiante tenga las condiciones adecuadas para aprender y desarrollarse.',
            'inicial' => 1, 'primaria' => 1, 'secundaria' => 1,
            'foto_inicial' => 'https://picsum.photos/seed/ae-sede1-inicial/900/600',
            'foto_primaria' => 'https://picsum.photos/seed/ae-sede1-primaria/900/600',
            'foto_secundaria' => 'https://picsum.photos/seed/ae-sede1-secundaria/900/600',
        ],
        2 => [
            'name' => 'Sede 2', 'address' => 'Av. Secundaria 456, Huaraz',
            'desc' => 'En nuestra segunda sede acompañamos a los estudiantes de Inicial y Primaria en un espacio diseñado especialmente para ellos, con ambientes de escala humana y la cercanía que los niños necesitan en sus primeros años escolares.',
            'inicial' => 1, 'primaria' => 1, 'secundaria' => 0,
            'foto_inicial' => 'https://picsum.photos/seed/ae-sede2-inicial/900/600',
            'foto_primaria' => 'https://picsum.photos/seed/ae-sede2-primaria/900/600',
            'foto_secundaria' => '',
        ],
        3 => [
            'name' => 'Sede 3', 'address' => 'Jr. Tercera 789, Huaraz',
            'desc' => 'Nuestra tercera sede ofrece un espacio dedicado con aulas pensadas para el trabajo en equipo, la investigación y la preparación pre-universitaria.',
            'inicial' => 0, 'primaria' => 0, 'secundaria' => 1,
            'foto_inicial' => '',
            'foto_primaria' => '',
            'foto_secundaria' => 'https://picsum.photos/seed/ae-sede3-secundaria/900/600',
        ],
    ];

    $p = 25;
    foreach ($defaults as $i => $d) {
        $prefix = "colegio_ae_sede_{$i}_";

        $wp_customize->add_setting($prefix . 'name', [
            'default' => $d['name'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($prefix . 'name', [
            'label' => sprintf(__('Sede %d — Nombre', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_sedes', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting($prefix . 'address', [
            'default' => $d['address'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($prefix . 'address', [
            'label' => sprintf(__('Sede %d — Dirección', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_sedes', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting($prefix . 'desc', [
            'default' => $d['desc'], 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control($prefix . 'desc', [
            'label' => sprintf(__('Sede %d — Descripción', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_sedes', 'type' => 'textarea', 'priority' => $p++,
        ]);

        /* Niveles disponibles (3 checkboxes) */
        foreach (['inicial' => 'Inicial', 'primaria' => 'Primaria', 'secundaria' => 'Secundaria'] as $key => $label) {
            $wp_customize->add_setting($prefix . $key, [
                'default' => $d[$key], 'type' => 'theme_mod', 'sanitize_callback' => 'colegio_ae_sanitize_checkbox',
            ]);
            $wp_customize->add_control($prefix . $key, [
                'label' => sprintf(__('Sede %d — Ofrece %s', 'colegio-ae'), $i, $label),
                'section' => 'colegio_ae_section_sedes', 'type' => 'checkbox', 'priority' => $p++,
            ]);

            $img_setting = $prefix . 'foto_' . $key;
            $wp_customize->add_setting($img_setting, [
                'default' => $d['foto_' . $key], 'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
            ]);
            $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $img_setting, [
                'label' => sprintf(__('Sede %d — Foto %s', 'colegio-ae'), $i, $label),
                'section' => 'colegio_ae_section_sedes', 'priority' => $p++,
            ]));
        }
    }
}
