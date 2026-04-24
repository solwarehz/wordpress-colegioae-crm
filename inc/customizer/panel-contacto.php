<?php
/**
 * inc/customizer/panel-contacto.php
 * Panel "Contáctanos" — título, subtítulo, intro + embed de Tally.
 * El embed de reclamaciones se mantiene en el panel "Formularios (Tally)" legacy.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_contacto');

function colegio_ae_customizer_register_contacto(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_section_contacto', [
        'title'    => __('Sección: Contáctanos', 'colegio-ae'),
        'priority' => 48,
    ]);

    $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, 'colegio_ae_section_contacto_enabled', [
        'label' => __('Mostrar sección', 'colegio-ae'),
        'section' => 'colegio_ae_section_contacto', 'priority' => 5,
    ]));

    $wp_customize->add_setting('colegio_ae_section_contacto_anchor', [
        'default' => 'contacto', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_title',
    ]);
    $wp_customize->add_control('colegio_ae_section_contacto_anchor', [
        'label' => __('ID del ancla', 'colegio-ae'),
        'description' => __('Default: contacto. Es el destino del CTA del header.', 'colegio-ae'),
        'section' => 'colegio_ae_section_contacto', 'type' => 'text', 'priority' => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_contacto_title', [
        'default' => 'Escríbenos', 'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_contacto_title', [
        'label' => __('Título', 'colegio-ae'),
        'section' => 'colegio_ae_section_contacto', 'type' => 'text', 'priority' => 15,
    ]);

    $wp_customize->add_setting('colegio_ae_contacto_subtitle', [
        'default' => 'Estamos aquí para resolver tus dudas y acompañarte en la decisión educativa más importante.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_contacto_subtitle', [
        'label' => __('Subtítulo', 'colegio-ae'),
        'section' => 'colegio_ae_section_contacto', 'type' => 'textarea', 'priority' => 20,
    ]);

    $wp_customize->add_setting('colegio_ae_contacto_intro', [
        'default' => 'Déjanos tus datos y nos pondremos en contacto contigo en menos de 24 horas. También puedes escribirnos directamente por WhatsApp al número visible en la parte inferior derecha.',
        'type' => 'theme_mod', 'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_contacto_intro', [
        'label' => __('Texto introductorio', 'colegio-ae'),
        'section' => 'colegio_ae_section_contacto', 'type' => 'textarea', 'priority' => 25,
    ]);

    /* El embed de Tally vive en el panel "Formularios (Tally)" — referenciado desde aquí */
    $wp_customize->add_control('colegio_ae_contacto_tally_info', [
        'type' => 'hidden',
        'section' => 'colegio_ae_section_contacto',
        'priority' => 30,
        'description' => __('El formulario embebido se configura en Apariencia → Personalizar → Formularios (Tally) → "Embed de contacto".', 'colegio-ae'),
    ]);
}
