<?php
/**
 * inc/customizer/panel-admision.php
 *
 * Panel del Customizer para la landing de Admisión.
 * Configurable: imagen banner, alt, título, párrafo introductorio
 * y formulario Tally embebido.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_admision');

function colegio_ae_customizer_register_admision(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_page_admision', [
        'title'       => __('Página: Admisión', 'colegio-ae'),
        'description' => __('Landing exclusiva para captar leads que solicitan vacante. Diseñada sin distractores: solo logo, banner, copy invitacional y formulario.', 'colegio-ae'),
        'priority'    => 55,
    ]);

    /* ---------- Imagen banner ---------- */
    $wp_customize->add_setting('colegio_ae_admision_banner_image', [
        'default'           => 0,
        'type'              => 'theme_mod',
        'sanitize_callback' => 'absint',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'colegio_ae_admision_banner_image', [
        'label'       => __('Imagen del banner', 'colegio-ae'),
        'description' => __('Foto horizontal que abre el landing. Recomendado mínimo 2400×1000 px. WordPress recortará a proporción 21:9 en desktop y 5:3 en mobile.', 'colegio-ae'),
        'section'     => 'colegio_ae_page_admision',
        'mime_type'   => 'image',
        'priority'    => 10,
    ]));

    $wp_customize->add_setting('colegio_ae_admision_banner_alt', [
        'default'           => 'Estudiantes del Colegio Albert Einstein',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_admision_banner_alt', [
        'label'       => __('Texto alternativo del banner (alt)', 'colegio-ae'),
        'description' => __('Describe la imagen para personas con discapacidad visual y para Google.', 'colegio-ae'),
        'section'     => 'colegio_ae_page_admision',
        'type'        => 'text',
        'priority'    => 15,
    ]);

    /* ---------- Título ---------- */
    $wp_customize->add_setting('colegio_ae_admision_title', [
        'default'           => 'Admisión',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_admision_title', [
        'label'    => __('Título principal (H1)', 'colegio-ae'),
        'section'  => 'colegio_ae_page_admision',
        'type'     => 'text',
        'priority' => 20,
    ]);

    /* ---------- Párrafo introductorio ---------- */
    $wp_customize->add_setting('colegio_ae_admision_intro', [
        'default'           => 'Solicita una vacante para tu hijo en el Colegio Albert Einstein. Déjanos tus datos y un asesor te contactará.',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_textarea_field',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_admision_intro', [
        'label'       => __('Párrafo introductorio', 'colegio-ae'),
        'description' => __('Texto invitacional debajo del título. Mantenlo corto: 1–2 oraciones.', 'colegio-ae'),
        'section'     => 'colegio_ae_page_admision',
        'type'        => 'textarea',
        'priority'    => 25,
    ]);

    /* ---------- Formulario Tally ---------- */
    $wp_customize->add_setting('colegio_ae_tally_admision', [
        'default'           => '',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_embed',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_tally_admision', [
        'label'       => __('Formulario de admisión (Tally embed)', 'colegio-ae'),
        'description' => __('Crea un formulario en tally.so con campos específicos de admisión: nombre del padre/madre, email, celular, nivel solicitado, edad del estudiante. Pega aquí el código embed completo.', 'colegio-ae'),
        'section'     => 'colegio_ae_page_admision',
        'type'        => 'textarea',
        'priority'    => 30,
    ]);
}
