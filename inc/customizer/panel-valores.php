<?php
/**
 * inc/customizer/panel-valores.php
 * Panel "Valores" — título, subtítulo, 6 valores (nombre, descripción, ícono).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_valores');

function colegio_ae_customizer_register_valores(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_valores', [
        'title'    => __('Sección: Valores', 'colegio-ae'),
        'priority' => 42,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_valores_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_valores', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_valores_anchor', [
        'default' => 'valores', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_valores_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'section' => 'colegio_ae_section_valores', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_valores_title', [
        'default' => 'Nuestros valores', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_valores_title', [
        'label' => __('Título de la sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_valores', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_valores_subtitle', [
        'default' => 'Los 6 pilares que sostienen nuestra forma de educar.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_valores_subtitle', [
        'label' => __('Subtítulo', 'colegio-ae'),
        'section' => 'colegio_ae_section_valores', 'type' => 'textarea', 'priority' => 20,
    ]);

    /* 6 valores */
    $defaults = [
        1 => ['Compromiso',    'handshake', 'Trabajamos cada día con la convicción de que cada estudiante merece nuestra mejor versión. El compromiso no es una promesa — es nuestra forma de enseñar.'],
        2 => ['Humanidad',     'heart',     'Detrás de cada estudiante hay una familia, una historia y sueños únicos. Educamos con empatía, escuchando y acompañando en cada etapa.'],
        3 => ['Liderazgo',     'star',      'Formamos estudiantes que no siguen, sino que proponen. Que no repiten, sino que crean. Que asumen la responsabilidad de transformar su entorno.'],
        4 => ['Excelencia',    'award',     'No nos conformamos con lo bueno cuando podemos alcanzar lo mejor. La excelencia académica y humana es el estándar al que aspiramos cada día.'],
        5 => ['Adaptabilidad', 'refresh',   'Vivimos en un mundo que cambia rápido. Enseñamos a nuestros estudiantes a aprender siempre, a desaprender cuando toque y a crecer en cualquier escenario.'],
        6 => ['Respeto',       'users',     'Respetamos la diversidad de pensamiento, la individualidad de cada alumno y la cultura de cada familia. El respeto mutuo es la base de nuestra comunidad.'],
    ];

    $icon_choices = [
        'handshake' => __('Apretón de manos', 'colegio-ae'),
        'heart'     => __('Corazón', 'colegio-ae'),
        'star'      => __('Estrella', 'colegio-ae'),
        'award'     => __('Galardón', 'colegio-ae'),
        'refresh'   => __('Flechas circulares', 'colegio-ae'),
        'users'     => __('Personas', 'colegio-ae'),
        'shield'    => __('Escudo', 'colegio-ae'),
        'book'      => __('Libro', 'colegio-ae'),
        'globe'     => __('Globo terráqueo', 'colegio-ae'),
        'lightbulb' => __('Bombilla (idea)', 'colegio-ae'),
    ];

    $p = 25;
    foreach ($defaults as $i => $d) {
        list($name, $icon, $desc) = $d;

        $wp_customize->add_setting("colegio_ae_valor_{$i}_name", [
            'default' => $name, 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("colegio_ae_valor_{$i}_name", [
            'label' => sprintf(__('Valor %d — Nombre', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_valores', 'type' => 'text', 'priority' => $p++,
        ]);

        $wp_customize->add_setting("colegio_ae_valor_{$i}_icon", [
            'default' => $icon, 'type' => 'theme_mod',
            'sanitize_callback' => function ($input) use ($icon_choices) {
                return array_key_exists($input, $icon_choices) ? $input : 'star';
            },
        ]);
        $wp_customize->add_control("colegio_ae_valor_{$i}_icon", [
            'label' => sprintf(__('Valor %d — Ícono', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_valores', 'type' => 'select',
            'choices' => $icon_choices, 'priority' => $p++,
        ]);

        $wp_customize->add_setting("colegio_ae_valor_{$i}_desc", [
            'default' => $desc, 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control("colegio_ae_valor_{$i}_desc", [
            'label' => sprintf(__('Valor %d — Descripción', 'colegio-ae'), $i),
            'section' => 'colegio_ae_section_valores', 'type' => 'textarea', 'priority' => $p++,
        ]);
    }
}
