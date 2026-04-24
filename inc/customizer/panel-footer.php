<?php
/**
 * inc/customizer/panel-footer.php
 *
 * Panel "Footer" — logo col 1, tagline, títulos de columnas 2 y 3,
 * imagen SUNAT editable, texto de copyright (el año va automático).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_footer');

function colegio_ae_customizer_register_footer(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_footer', [
        'title'       => __('Footer', 'colegio-ae'),
        'description' => __('Logo, tagline, títulos de columnas, imagen del Libro de Reclamaciones y copyright.', 'colegio-ae'),
        'priority'    => 23,
    ]);

    /* ---------- Logo col 1 ---------- */
    $wp_customize->add_setting('colegio_ae_footer_logo', [
        'default'           => '',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'colegio_ae_footer_logo', [
        'label'       => __('Logo del footer', 'colegio-ae'),
        'description' => __('Si queda vacío se usa el logo del header. Puedes subir uno distinto para el pie de página.', 'colegio-ae'),
        'section'     => 'colegio_ae_footer',
        'priority'    => 10,
    ]));

    /* ---------- Tagline ---------- */
    $wp_customize->add_setting('colegio_ae_footer_tagline', [
        'default'           => 'Formamos estudiantes líderes con pensamiento crítico, valores sólidos y visión global. Huaraz, Perú.',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('colegio_ae_footer_tagline', [
        'label'       => __('Texto bajo el logo (columna 1)', 'colegio-ae'),
        'section'     => 'colegio_ae_footer',
        'type'        => 'textarea',
        'priority'    => 15,
    ]);

    /* ---------- Título col 2 ---------- */
    $wp_customize->add_setting('colegio_ae_footer_col2_title', [
        'default'           => 'Links de interés',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_footer_col2_title', [
        'label'       => __('Título de la columna 2 (menú secundario)', 'colegio-ae'),
        'description' => __('Se muestra siempre en MAYÚSCULAS.', 'colegio-ae'),
        'section'     => 'colegio_ae_footer',
        'type'        => 'text',
        'priority'    => 20,
    ]);

    /* ---------- Título col 3 ---------- */
    $wp_customize->add_setting('colegio_ae_footer_col3_title', [
        'default'           => 'Síguenos en:',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_footer_col3_title', [
        'label'       => __('Título de la columna 3 (redes sociales)', 'colegio-ae'),
        'description' => __('Se muestra siempre en MAYÚSCULAS.', 'colegio-ae'),
        'section'     => 'colegio_ae_footer',
        'type'        => 'text',
        'priority'    => 25,
    ]);

    /* ---------- Imagen SUNAT ---------- */
    $wp_customize->add_setting('colegio_ae_sunat_image', [
        'default'           => '',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'colegio_ae_sunat_image', [
        'label'       => __('Imagen del Libro de Reclamaciones', 'colegio-ae'),
        'description' => __('⚠️ La Ley 29571 exige la imagen oficial del Libro de Reclamaciones (Indecopi). Úsala tal cual; no la reemplaces por versiones personalizadas. El tamaño y aspecto los controla el tema — la imagen se mostrará siempre pequeña (28×36px) junto al link del menú.', 'colegio-ae'),
        'section'     => 'colegio_ae_footer',
        'priority'    => 30,
    ]));

    /* ---------- Copyright ---------- */
    $wp_customize->add_setting('colegio_ae_footer_copyright', [
        'default'           => 'Colegio Albert Einstein. Todos los derechos reservados.',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_footer_copyright', [
        'label'       => __('Texto del copyright', 'colegio-ae'),
        'description' => __('El año se agrega automáticamente al inicio (© 2026, © 2027...).', 'colegio-ae'),
        'section'     => 'colegio_ae_footer',
        'type'        => 'text',
        'priority'    => 40,
    ]);
}
