<?php
/**
 * functions.php — Colegio Albert Einstein theme
 */

defined('ABSPATH') || exit;

define('COLEGIO_AE_VERSION', '0.4.3');
define('COLEGIO_AE_DIR', get_template_directory());
define('COLEGIO_AE_URI', get_template_directory_uri());

/**
 * Theme setup.
 */
function colegio_ae_setup() {
    load_theme_textdomain('colegio-ae', COLEGIO_AE_DIR . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');

    register_nav_menus([
        'menu-principal'      => __('Menú principal (header)', 'colegio-ae'),
        'menu-secundario'     => __('Menú secundario (footer columna 2)', 'colegio-ae'),
        'menu-redes-sociales' => __('Redes sociales (footer columna 3)', 'colegio-ae'),
    ]);

    add_image_size('ae-hero', 1920, 1080, true);
    add_image_size('ae-card', 800, 600, true);
    add_image_size('ae-card-square', 600, 600, true);
    add_image_size('ae-card-portrait', 600, 800, true);
    add_image_size('ae-blog-featured', 1200, 675, true);
}
add_action('after_setup_theme', 'colegio_ae_setup');

/**
 * Encolar CSS y JS.
 */
function colegio_ae_enqueue_assets() {
    $css_base = [
        'tokens' => 'assets/css/tokens.css',
        'fonts'  => 'assets/css/fonts.css',
        'reset'  => 'assets/css/reset.css',
        'base'   => 'assets/css/base.css',
    ];
    $prev = null;
    foreach ($css_base as $handle => $rel) {
        $full_handle = 'colegio-ae-' . $handle;
        wp_enqueue_style(
            $full_handle,
            COLEGIO_AE_URI . '/' . $rel,
            $prev ? [$prev] : [],
            COLEGIO_AE_VERSION
        );
        $prev = $full_handle;
    }

    $components = ['header', 'footer', 'theme-toggle', 'whatsapp-float'];
    foreach ($components as $component) {
        wp_enqueue_style(
            'colegio-ae-' . $component,
            COLEGIO_AE_URI . '/assets/css/components/' . $component . '.css',
            ['colegio-ae-base'],
            COLEGIO_AE_VERSION
        );
    }

    // CSS específico del home (one-page)
    if (is_front_page()) {
        wp_enqueue_style(
            'colegio-ae-home',
            COLEGIO_AE_URI . '/assets/css/components/home.css',
            ['colegio-ae-base'],
            COLEGIO_AE_VERSION
        );
        wp_enqueue_script(
            'colegio-ae-slider',
            COLEGIO_AE_URI . '/assets/js/slider.js',
            [],
            COLEGIO_AE_VERSION,
            true
        );
        wp_enqueue_script(
            'colegio-ae-blog-carousel',
            COLEGIO_AE_URI . '/assets/js/blog-carousel.js',
            [],
            COLEGIO_AE_VERSION,
            true
        );
        wp_enqueue_script(
            'colegio-ae-profesores-carousel',
            COLEGIO_AE_URI . '/assets/js/profesores-carousel.js',
            [],
            COLEGIO_AE_VERSION,
            true
        );
    }

    // Scroll reveal (todas las vistas)
    wp_enqueue_script(
        'colegio-ae-scroll-reveal',
        COLEGIO_AE_URI . '/assets/js/scroll-reveal.js',
        [],
        COLEGIO_AE_VERSION,
        true
    );

    // CSS común para páginas secundarias — cargado en TODAS las vistas porque
    // contiene estilos compartidos (profesor-card, post-card) que también usa el home.
    wp_enqueue_style(
        'colegio-ae-pages',
        COLEGIO_AE_URI . '/assets/css/components/pages.css',
        ['colegio-ae-base'],
        COLEGIO_AE_VERSION
    );

    wp_enqueue_script(
        'colegio-ae-theme-toggle',
        COLEGIO_AE_URI . '/assets/js/theme-toggle.js',
        [],
        COLEGIO_AE_VERSION,
        true
    );

    wp_enqueue_script(
        'colegio-ae-nav',
        COLEGIO_AE_URI . '/assets/js/nav.js',
        [],
        COLEGIO_AE_VERSION,
        true
    );

    wp_enqueue_script(
        'colegio-ae-main',
        COLEGIO_AE_URI . '/assets/js/main.js',
        [],
        COLEGIO_AE_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'colegio_ae_enqueue_assets');

/**
 * Script inline anti-FOUC para aplicar el tema guardado antes del primer paint.
 */
function colegio_ae_theme_init_inline() {
    ?>
    <script>
        (function() {
            try {
                var saved = localStorage.getItem('colegio-ae-theme');
                var theme = (saved === 'dark' || saved === 'light') ? saved : 'light';
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'colegio_ae_theme_init_inline', 1);

/**
 * Includes.
 */
require_once COLEGIO_AE_DIR . '/inc/social-nav-walker.php';
require_once COLEGIO_AE_DIR . '/inc/customizer.php';
