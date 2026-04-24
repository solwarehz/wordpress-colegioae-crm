<?php
/**
 * inc/customizer/output-css.php
 *
 * Lee los theme_mods del Customizer y construye un CSS inline con overrides
 * de custom properties. Se attach a tokens.css vía wp_add_inline_style, así
 * el CSS del tema sigue siendo estático y cacheable, y los overrides son
 * mínimos (solo lo que cambió).
 */

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'colegio_ae_attach_customizer_inline_css', 50);

function colegio_ae_attach_customizer_inline_css() {
    $css = colegio_ae_build_customizer_css();
    if (!empty($css)) {
        wp_add_inline_style('colegio-ae-tokens', $css);
    }
}

/**
 * Construye el bloque :root con variables overridden.
 * Devuelve string CSS o '' si no hay overrides.
 */
function colegio_ae_build_customizer_css() {

    /* -------- Tipografías: ID → font-stack -------- */
    $font_stacks = [
        'open-sans'  => "'Open Sans', system-ui, -apple-system, 'Segoe UI', sans-serif",
        'roboto'     => "'Roboto', system-ui, -apple-system, 'Segoe UI', sans-serif",
        'montserrat' => "'Montserrat', system-ui, -apple-system, 'Segoe UI', sans-serif",
        'lato'       => "'Lato', system-ui, -apple-system, 'Segoe UI', sans-serif",
        'playfair'   => "'Playfair Display', Georgia, 'Times New Roman', serif",
    ];

    $font_heading = (string) get_theme_mod('colegio_ae_font_heading', 'open-sans');
    $font_body    = (string) get_theme_mod('colegio_ae_font_body', 'roboto');

    /* -------- Colores -------- */
    $primary   = (string) get_theme_mod('colegio_ae_color_primary', '#004aad');
    $secondary = (string) get_theme_mod('colegio_ae_color_secondary', '#01aded');
    $red       = (string) get_theme_mod('colegio_ae_color_accent_red', '#e30914');
    $gold      = (string) get_theme_mod('colegio_ae_color_accent_gold', '#c2975c');

    $overrides = [];

    if (isset($font_stacks[$font_heading])) {
        $overrides[] = '--font-heading: ' . $font_stacks[$font_heading] . ';';
    }
    if (isset($font_stacks[$font_body])) {
        $overrides[] = '--font-body: ' . $font_stacks[$font_body] . ';';
    }

    if (!empty($primary)) {
        $overrides[] = '--color-primary: ' . $primary . ';';
        $overrides[] = '--color-primary-hover: ' . colegio_ae_darken_hex($primary, 12) . ';';
    }
    if (!empty($secondary)) {
        $overrides[] = '--color-secondary: ' . $secondary . ';';
        $overrides[] = '--color-secondary-hover: ' . colegio_ae_darken_hex($secondary, 12) . ';';
    }
    if (!empty($red)) {
        $overrides[] = '--color-accent-red: ' . $red . ';';
        $overrides[] = '--color-error: ' . $red . ';';
    }
    if (!empty($gold)) {
        $overrides[] = '--color-accent-gold: ' . $gold . ';';
    }

    /* -------- Header / CTA -------- */
    $menu_size = (int) get_theme_mod('colegio_ae_menu_font_size', 16);
    $menu_size = max(14, min(22, $menu_size));
    $overrides[] = '--menu-font-size: ' . $menu_size . 'px;';

    $cta_bg    = (string) get_theme_mod('colegio_ae_cta_bg', '');
    $cta_color = (string) get_theme_mod('colegio_ae_cta_color', '#ffffff');
    if (!empty($cta_bg)) {
        $overrides[] = '--color-cta-bg: ' . $cta_bg . ';';
        $overrides[] = '--color-cta-bg-hover: ' . colegio_ae_darken_hex($cta_bg, 12) . ';';
    }
    if (!empty($cta_color)) {
        $overrides[] = '--color-cta-text: ' . $cta_color . ';';
    }

    /* -------- Footer / SUNAT -------- */
    $sunat_url = (string) get_theme_mod('colegio_ae_sunat_image', '');
    if (empty($sunat_url)) {
        $sunat_url = COLEGIO_AE_URI . '/assets/images/libro-reclamaciones-sunat.png';
    }
    $overrides[] = "--sunat-img: url('" . esc_url($sunat_url) . "');";

    if (empty($overrides)) {
        return '';
    }

    return ':root, [data-theme="light"] { ' . implode(' ', $overrides) . ' }';
}

/**
 * Oscurece un color hex en N%. Usado para los *--hover derivados.
 */
function colegio_ae_darken_hex($hex, $percent = 10) {
    $hex = ltrim((string) $hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0].$hex[0] . $hex[1].$hex[1] . $hex[2].$hex[2];
    }
    if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
        return '#' . $hex;
    }
    $factor = max(0, min(100, (float) $percent)) / 100;
    $r = (int) round(hexdec(substr($hex, 0, 2)) * (1 - $factor));
    $g = (int) round(hexdec(substr($hex, 2, 2)) * (1 - $factor));
    $b = (int) round(hexdec(substr($hex, 4, 2)) * (1 - $factor));
    return sprintf('#%02x%02x%02x', max(0, min(255, $r)), max(0, min(255, $g)), max(0, min(255, $b)));
}
