<?php
/**
 * inc/customizer/panel-nosotros.php
 * Panel "Nosotros / Conócenos" — título, párrafos, video YouTube.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_nosotros');

function colegio_ae_customizer_register_nosotros(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_nosotros', [
        'title'    => __('Sección: Nosotros / Conócenos', 'colegio-ae'),
        'priority' => 41,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_nosotros_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_nosotros_anchor', [
        'default' => 'nosotros', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_nosotros_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'description' => __('Default: nosotros.', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_nosotros_title', [
        'default' => 'Conoce el Colegio Albert Einstein',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_nosotros_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'type' => 'text', 'priority' => 20,
    ]);

    $wp_customize->add_setting('colegio_ae_nosotros_p1', [
        'default' => 'En Huaraz formamos estudiantes líderes con pensamiento crítico, valores sólidos y visión global, capaces de transformar su entorno con compromiso, innovación y excelencia académica.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_nosotros_p1', [
        'label' => __('Párrafo 1', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'type' => 'textarea', 'priority' => 25,
    ]);

    $wp_customize->add_setting('colegio_ae_nosotros_p2', [
        'default' => 'Creemos que la educación no es solo transmitir conocimiento: es acompañar a cada niño y joven en el descubrimiento de quién es, qué lo apasiona y cómo puede aportar al mundo. Por eso trabajamos con la convicción de que una educación de calidad empieza por la cercanía — conocer a cada familia, entender a cada estudiante y ofrecerle un espacio seguro donde atreverse a crecer.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_nosotros_p2', [
        'label' => __('Párrafo 2', 'colegio-ae'),
        'description' => __('Deja vacío si no necesitas un segundo párrafo.', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'type' => 'textarea', 'priority' => 30,
    ]);

    $wp_customize->add_setting('colegio_ae_nosotros_video_url', [
        'default' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
        'type' => 'theme_mod', 'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('colegio_ae_nosotros_video_url', [
        'label' => __('URL del video de YouTube', 'colegio-ae'),
        'description' => __('Pega la URL completa del video. Se convierte automáticamente a embed.', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'type' => 'url', 'priority' => 35,
    ]);

    $wp_customize->add_setting('colegio_ae_nosotros_video_title', [
        'default' => 'Video institucional Colegio Albert Einstein',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_nosotros_video_title', [
        'label' => __('Título del video (accesibilidad)', 'colegio-ae'),
        'section' => 'colegio_ae_section_nosotros', 'type' => 'text', 'priority' => 40,
    ]);
}
