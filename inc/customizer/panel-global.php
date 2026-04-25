<?php
/**
 * inc/customizer/panel-global.php
 *
 * Panel "Global" — ajustes que afectan todo el sitio:
 *  - Tipografía (heading + body, 5 opciones cada una)
 *  - Colores de marca (4 color pickers)
 *  - WhatsApp (número + mensaje precargado)
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_global');

function colegio_ae_customizer_register_global(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_global', [
        'title'       => __('Global (colores, tipografía, WhatsApp)', 'colegio-ae'),
        'description' => __('Ajustes que afectan todo el sitio.', 'colegio-ae'),
        'priority'    => 20,
    ]);

    /* =====================================================================
       TIPOGRAFÍA
       ===================================================================== */
    $font_choices = [
        'open-sans'  => __('Open Sans (recomendada para títulos)', 'colegio-ae'),
        'roboto'     => __('Roboto (recomendada para cuerpo)', 'colegio-ae'),
        'montserrat' => __('Montserrat', 'colegio-ae'),
        'lato'       => __('Lato', 'colegio-ae'),
        'playfair'   => __('Playfair Display (serif elegante)', 'colegio-ae'),
    ];

    $wp_customize->add_setting('colegio_ae_font_heading', [
        'default'           => 'open-sans',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_font_choice',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_font_heading', [
        'label'       => __('Tipografía de títulos (H1–H6)', 'colegio-ae'),
        'description' => __('Se aplica a todos los títulos del sitio.', 'colegio-ae'),
        'section'     => 'colegio_ae_global',
        'type'        => 'select',
        'choices'     => $font_choices,
        'priority'    => 10,
    ]);

    $wp_customize->add_setting('colegio_ae_font_body', [
        'default'           => 'roboto',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_font_choice',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_font_body', [
        'label'       => __('Tipografía de párrafos y texto', 'colegio-ae'),
        'section'     => 'colegio_ae_global',
        'type'        => 'select',
        'choices'     => $font_choices,
        'priority'    => 15,
    ]);

    /* =====================================================================
       COLORES DE MARCA
       ===================================================================== */
    $colors = [
        'primary'     => ['label' => __('Azul (primario)',       'colegio-ae'), 'default' => '#004aad'],
        'secondary'   => ['label' => __('Celeste (secundario)',  'colegio-ae'), 'default' => '#01aded'],
        'accent_red'  => ['label' => __('Rojo (alerta/énfasis)', 'colegio-ae'), 'default' => '#e30914'],
        'accent_gold' => ['label' => __('Dorado (decorativo)',   'colegio-ae'), 'default' => '#c2975c'],
    ];

    $priority = 20;
    foreach ($colors as $key => $data) {
        $setting_id = 'colegio_ae_color_' . $key;
        $wp_customize->add_setting($setting_id, [
            'default'           => $data['default'],
            'type'              => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
            'capability'        => 'edit_theme_options',
        ]);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $setting_id, [
            'label'    => $data['label'],
            'section'  => 'colegio_ae_global',
            'priority' => $priority++,
        ]));
    }

    /* =====================================================================
       WHATSAPP
       ===================================================================== */
    $wp_customize->add_setting('colegio_ae_whatsapp_number', [
        'default'           => '981398282',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_phone',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_whatsapp_number', [
        'label'       => __('Número de WhatsApp', 'colegio-ae'),
        'description' => __('Solo 9 dígitos del celular (sin +51). Se antepone el código de Perú automáticamente.', 'colegio-ae'),
        'section'     => 'colegio_ae_global',
        'type'        => 'tel',
        'priority'    => 50,
    ]);

    $wp_customize->add_setting('colegio_ae_whatsapp_message', [
        'default'           => 'Hola, quisiera información del colegio',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_whatsapp_message', [
        'label'       => __('Mensaje pre-cargado del botón WhatsApp', 'colegio-ae'),
        'description' => __('Texto que verá el visitante en su WhatsApp al abrir el chat.', 'colegio-ae'),
        'section'     => 'colegio_ae_global',
        'type'        => 'textarea',
        'priority'    => 55,
    ]);

    /* =====================================================================
       SEO — Imagen y descripción para redes sociales (Open Graph)
       ===================================================================== */
    $wp_customize->add_setting('colegio_ae_seo_social_image', [
        'default'           => 0,
        'type'              => 'theme_mod',
        'sanitize_callback' => 'absint',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'colegio_ae_seo_social_image', [
        'label'       => __('Imagen para redes sociales (Open Graph)', 'colegio-ae'),
        'description' => __('Se muestra cuando alguien comparte el link en WhatsApp, Facebook, etc. Recomendado: 1200×630 px. WordPress recortará automáticamente la imagen al subirla. Si una página tiene imagen destacada propia, esa tiene prioridad sobre esta.', 'colegio-ae'),
        'section'     => 'colegio_ae_global',
        'mime_type'   => 'image',
        'priority'    => 60,
    ]));

    $wp_customize->add_setting('colegio_ae_seo_site_description', [
        'default'           => 'Formamos estudiantes líderes con pensamiento crítico, valores sólidos y excelencia académica. Tres sedes en Huaraz.',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_textarea_field',
        'capability'        => 'edit_theme_options',
    ]);
    $wp_customize->add_control('colegio_ae_seo_site_description', [
        'label'       => __('Descripción del sitio para Google y redes sociales', 'colegio-ae'),
        'description' => __('Texto que Google muestra debajo del título en sus resultados. Aparece en la home y en archivos del blog. Ideal: entre 120 y 160 caracteres.', 'colegio-ae'),
        'section'     => 'colegio_ae_global',
        'type'        => 'textarea',
        'priority'    => 65,
    ]);
}

/* ==========================================================================
   Sanitizers específicos de este panel
   ========================================================================== */

function colegio_ae_sanitize_font_choice($input) {
    $valid = ['open-sans', 'roboto', 'montserrat', 'lato', 'playfair'];
    $input = sanitize_key($input);
    return in_array($input, $valid, true) ? $input : 'open-sans';
}

function colegio_ae_sanitize_phone($input) {
    $digits = preg_replace('/[^0-9]/', '', (string) $input);
    return substr($digits, 0, 15);
}
