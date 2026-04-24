<?php
/**
 * inc/customizer/panel-servicios.php
 * Panel "Niveles educativos" — título, subtítulo, 3 niveles (nombre, subtítulo, descripción, imagen, link).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_servicios');

function colegio_ae_customizer_register_servicios(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_servicios', [
        'title'    => __('Sección: Niveles educativos', 'colegio-ae'),
        'priority' => 43,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_servicios_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_servicios', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_servicios_anchor', [
        'default' => 'servicios', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_servicios_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'section' => 'colegio_ae_section_servicios', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_servicios_title', [
        'default' => 'Niveles educativos', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_servicios_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_servicios', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_servicios_subtitle', [
        'default' => 'Acompañamos a tu hijo desde los primeros años hasta la universidad.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_servicios_subtitle', [
        'label' => __('Subtítulo', 'colegio-ae'),
        'section' => 'colegio_ae_section_servicios', 'type' => 'textarea', 'priority' => 20,
    ]);

    /* 3 niveles */
    $defaults = [
        1 => ['Inicial', '3 a 5 años', 'Los primeros años marcan el resto de la vida escolar. En nuestro nivel Inicial, los niños aprenden a través del juego, el movimiento y la exploración, en un ambiente seguro que estimula su curiosidad y desarrolla sus habilidades sociales, emocionales y cognitivas.', 'https://picsum.photos/seed/ae-servicios-inicial/800/600', ''],
        2 => ['Primaria', '1° a 6° grado', 'Construimos las bases del pensamiento crítico. Nuestros estudiantes no memorizan: comprenden, cuestionan y aplican. Desarrollamos hábitos de estudio, lectura comprensiva, razonamiento matemático y una sólida formación en valores.', 'https://picsum.photos/seed/ae-servicios-primaria/800/600', ''],
        3 => ['Secundaria', '1° a 5° año', 'Preparamos jóvenes listos para la universidad y para la vida. Formación académica rigurosa, orientación vocacional, proyectos de liderazgo y participación en concursos que los retan a dar lo mejor de sí.', 'https://picsum.photos/seed/ae-servicios-secundaria/800/600', ''],
    ];

    $p = 25;
    foreach ($defaults as $i => $d) {
        list($name, $sub, $desc, $img, $link) = $d;

        $wp_customize->add_setting("colegio_ae_nivel_{$i}_name", [
            'default' => $name, 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("colegio_ae_nivel_{$i}_name", [
            'label' => sprintf(__('Nivel %d — Nombre', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_servicios', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting("colegio_ae_nivel_{$i}_subtitle", [
            'default' => $sub, 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("colegio_ae_nivel_{$i}_subtitle", [
            'label' => sprintf(__('Nivel %d — Subtítulo (edad/grado)', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_servicios', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting("colegio_ae_nivel_{$i}_desc", [
            'default' => $desc, 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control("colegio_ae_nivel_{$i}_desc", [
            'label' => sprintf(__('Nivel %d — Descripción', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_servicios', 'type' => 'textarea', 'priority' => $p++,
        ]);

        $setting_img = "colegio_ae_nivel_{$i}_image";
        $wp_customize->add_setting($setting_img, [
            'default' => $img, 'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $setting_img, [
            'label' => sprintf(__('Nivel %d — Imagen', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_servicios', 'priority' => $p++,
        ]));

        $wp_customize->add_setting("colegio_ae_nivel_{$i}_link", [
            'default' => $link, 'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control("colegio_ae_nivel_{$i}_link", [
            'label' => sprintf(__('Nivel %d — Link opcional', 'colegio-ae'), $i),
            'description' => __('Si lo llenas, el card será clickable.', 'colegio-ae'),
            'section' => 'colegio_ae_section_servicios', 'type' => 'url', 'priority' => $p++,
        ]);
    }
}
