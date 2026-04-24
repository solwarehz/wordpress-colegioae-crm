<?php
/**
 * inc/customizer/panel-header.php
 *
 * Panel "Header" — logo alt, tamaño de menú, CTA (texto + colores + destino),
 * visibilidad del chevron/theme toggle.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_header');

function colegio_ae_customizer_register_header(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_header', [
        'title'       => __('Header', 'colegio-ae'),
        'description' => __('Logo, menú principal, botón CTA y chevron del toggle de tema.', 'colegio-ae'),
        'priority'    => 22,
    ]);

    /* ---------- Logo alt ---------- */
    $wp_customize->add_setting('colegio_ae_header_logo_alt', [
        'default'           => '',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_header_logo_alt', [
        'label'       => __('Texto alternativo del logo', 'colegio-ae'),
        'description' => __('Accesibilidad y SEO. Si queda vacío se usa el nombre del sitio.', 'colegio-ae'),
        'section'     => 'colegio_ae_header',
        'type'        => 'text',
        'priority'    => 10,
    ]);

    /* ---------- Tamaño del menú ---------- */
    $wp_customize->add_setting('colegio_ae_menu_font_size', [
        'default'           => 16,
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_menu_size',
    ]);
    $wp_customize->add_control('colegio_ae_menu_font_size', [
        'label'       => __('Tamaño del texto del menú (px)', 'colegio-ae'),
        'description' => __('Entre 14 y 22 píxeles.', 'colegio-ae'),
        'section'     => 'colegio_ae_header',
        'type'        => 'number',
        'input_attrs' => ['min' => 14, 'max' => 22, 'step' => 1],
        'priority'    => 20,
    ]);

    /* ---------- CTA texto ---------- */
    $wp_customize->add_setting('colegio_ae_cta_text', [
        'default'           => 'Contáctanos',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('colegio_ae_cta_text', [
        'label'       => __('Texto del botón CTA', 'colegio-ae'),
        'description' => __('Siempre se muestra en MAYÚSCULAS (lo aplica el CSS).', 'colegio-ae'),
        'section'     => 'colegio_ae_header',
        'type'        => 'text',
        'priority'    => 30,
    ]);

    /* ---------- CTA destino ---------- */
    $wp_customize->add_setting('colegio_ae_cta_href', [
        'default'           => '#contacto',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_cta_href',
    ]);
    $wp_customize->add_control('colegio_ae_cta_href', [
        'label'       => __('Destino del CTA', 'colegio-ae'),
        'description' => __('Usa "#contacto" para scroll al formulario del home, o pega una URL completa.', 'colegio-ae'),
        'section'     => 'colegio_ae_header',
        'type'        => 'text',
        'priority'    => 35,
    ]);

    /* ---------- CTA colores ---------- */
    $wp_customize->add_setting('colegio_ae_cta_bg', [
        'default'           => '',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'colegio_ae_cta_bg', [
        'label'       => __('Color de fondo del CTA', 'colegio-ae'),
        'description' => __('Vacío: usa el azul primario definido en Global.', 'colegio-ae'),
        'section'     => 'colegio_ae_header',
        'priority'    => 40,
    ]));

    $wp_customize->add_setting('colegio_ae_cta_color', [
        'default'           => '#ffffff',
        'type'              => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'colegio_ae_cta_color', [
        'label'    => __('Color del texto del CTA', 'colegio-ae'),
        'section'  => 'colegio_ae_header',
        'priority' => 45,
    ]));

    /* ---------- Mostrar chevron/theme toggle ---------- */
    $wp_customize->add_setting('colegio_ae_show_theme_toggle', [
        'default'           => 1,
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_checkbox',
    ]);
    $wp_customize->add_control('colegio_ae_show_theme_toggle', [
        'label'       => __('Mostrar chevron con toggle light/dark', 'colegio-ae'),
        'description' => __('Si lo desactivas, el sitio queda fijo en modo claro y el chevron no se renderiza.', 'colegio-ae'),
        'section'     => 'colegio_ae_header',
        'type'        => 'checkbox',
        'priority'    => 50,
    ]);
}

function colegio_ae_sanitize_menu_size($input) {
    $n = (int) $input;
    return max(14, min(22, $n));
}

function colegio_ae_sanitize_cta_href($input) {
    $input = trim((string) $input);
    if ($input === '') return '#contacto';
    if (strpos($input, '#') === 0) {
        return '#' . sanitize_title(substr($input, 1));
    }
    return esc_url_raw($input);
}
