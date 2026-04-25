<?php
/**
 * functions.php — Colegio Albert Einstein theme
 */

defined('ABSPATH') || exit;

define('COLEGIO_AE_VERSION', '0.7.0');
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

    // Imagen Open Graph: 1200x630 con crop centrado. La proporción 1.91:1
    // es la que recomiendan Facebook, WhatsApp, LinkedIn y Twitter Cards.
    // WordPress genera esta variante automáticamente al subir cualquier
    // imagen — el cliente no tiene que recortar a mano.
    add_image_size('ae-og', 1200, 630, true);
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

    // Cards base (.profesor-card) — compartido entre home (carrusel) y
    // archive (/profesores/). Se carga siempre porque pesa poco (~600 B).
    wp_enqueue_style(
        'colegio-ae-cards',
        COLEGIO_AE_URI . '/assets/css/components/cards.css',
        ['colegio-ae-base'],
        COLEGIO_AE_VERSION
    );

    // CSS de páginas secundarias — solo cuando NO estamos en home. Esto
    // ahorra ~18 KB de CSS bloqueante en la vista más vista del sitio.
    if (!is_front_page()) {
        wp_enqueue_style(
            'colegio-ae-pages',
            COLEGIO_AE_URI . '/assets/css/components/pages.css',
            ['colegio-ae-base'],
            COLEGIO_AE_VERSION
        );
    }

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

    // Aplicar defer a todos los JS del tema. Están en footer y son
    // independientes entre sí; defer permite que el navegador siga
    // parseando el HTML sin bloquear y ejecuta los scripts en orden
    // antes de DOMContentLoaded.
    foreach ([
        'colegio-ae-slider',
        'colegio-ae-blog-carousel',
        'colegio-ae-profesores-carousel',
        'colegio-ae-scroll-reveal',
        'colegio-ae-theme-toggle',
        'colegio-ae-nav',
        'colegio-ae-main',
    ] as $handle) {
        wp_script_add_data($handle, 'strategy', 'defer');
    }
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
 * Oculta el email del autor de posts en cualquier output del frontend.
 * Protege contra plugins o bloques Gutenberg que filtren el email del
 * usuario administrador.
 */
function colegio_ae_hide_author_email_on_frontend($value, $field) {
    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
        return $value;
    }
    if ($field === 'user_email' || $field === 'email') {
        return '';
    }
    return $value;
}
add_filter('get_the_author_user_email', function ($v) { return is_admin() ? $v : ''; });
add_filter('get_the_author_email',      function ($v) { return is_admin() ? $v : ''; });
add_filter('the_author_email',          function ($v) { return is_admin() ? $v : ''; });

/**
 * Normaliza URLs de ítems de menú llamados "Inicio" o "Home":
 * si contienen un ancla vacía/rara (#, #home, #HOme, etc.) o apuntan a
 * dominios localhost, se reemplazan por la home_url real del sitio.
 *
 * Evita que en producción un menú mal configurado deje al visitante en #.
 */
function colegio_ae_fix_home_menu_items($items, $args) {
    $home = home_url('/');
    foreach ($items as $item) {
        $label = strtolower(trim($item->title));
        if (in_array($label, ['inicio', 'home'], true)) {
            $url = trim((string) $item->url);
            $needs_fix = (
                $url === '' ||
                $url === '#' ||
                strpos($url, '#') === 0 ||
                strpos($url, 'localhost') !== false ||
                strpos($url, '.test/') !== false ||
                strpos($url, '.local/') !== false
            );
            if ($needs_fix) {
                $item->url = $home;
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'colegio_ae_fix_home_menu_items', 10, 2);

/**
 * Includes.
 */
require_once COLEGIO_AE_DIR . '/inc/social-nav-walker.php';
require_once COLEGIO_AE_DIR . '/inc/disable-comments.php';
require_once COLEGIO_AE_DIR . '/inc/cache.php';
require_once COLEGIO_AE_DIR . '/inc/seo.php';
require_once COLEGIO_AE_DIR . '/inc/customizer.php';
