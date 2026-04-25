<?php
/**
 * inc/disable-comments.php
 *
 * Bloqueo total del sistema de comentarios de WordPress a nivel de tema.
 *
 * Motivo: el tema no muestra comment-form ni comment-list en ninguna vista,
 * pero WP sigue aceptando POST a /wp-comments-post.php y exponiendo el
 * endpoint REST /wp/v2/comments. Los bots conocen estas rutas y meten spam
 * a la cola de moderación aunque visualmente nunca aparezca.
 *
 * Este archivo aplica defensa en capas:
 *   1) Cierra comments_open / pings_open en cualquier post.
 *   2) Vacía comments_array para ocultar los ya guardados (no los borra).
 *   3) Quita el endpoint REST /wp/v2/comments.
 *   4) Bloquea wp-comments-post.php con 403.
 *   5) Quita el feed de comentarios y el header link.
 *   6) Quita el soporte de comentarios de los post types.
 *   7) Limpia el menú admin y el admin bar.
 *
 * Lo que NO hace (intencional):
 *   - No borra los comentarios existentes (reversible).
 *   - No toca XML-RPC pingbacks (decisión separada).
 */

defined('ABSPATH') || exit;

/* 1) Rechazar nuevos comentarios y pings en todos los posts. */
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open',    '__return_false', 20, 2);

/* 2) Ocultar comentarios ya guardados (sin borrarlos de la base). */
add_filter('comments_array', '__return_empty_array', 10, 2);

/* 3) Cerrar el endpoint REST de comentarios. */
add_filter('rest_endpoints', function ($endpoints) {
    foreach (array_keys($endpoints) as $route) {
        if (strpos($route, '/wp/v2/comments') === 0) {
            unset($endpoints[$route]);
        }
    }
    return $endpoints;
});

/* 4) Bloquear POST directos a wp-comments-post.php con 403.
 *    El endpoint vive fuera del routing de WP, así que enganchamos en
 *    'pre_comment_on_post' (se dispara antes de procesar el POST). */
add_action('pre_comment_on_post', function () {
    wp_die(
        esc_html__('Los comentarios están deshabilitados.', 'colegio-ae'),
        esc_html__('Comentarios deshabilitados', 'colegio-ae'),
        ['response' => 403]
    );
}, 1);

/* 5) Bloquear feeds de comentarios (?feed=comments-rss2 y similares). */
add_action('template_redirect', function () {
    if (is_comment_feed()) {
        wp_die(
            esc_html__('Los comentarios están deshabilitados.', 'colegio-ae'),
            esc_html__('Comentarios deshabilitados', 'colegio-ae'),
            ['response' => 403]
        );
    }
}, 1);

/* Quitar los <link rel="..."> de feeds de comentarios del <head>. */
remove_action('wp_head', 'feed_links_extra', 3);

/* 6) Quitar el soporte de comentarios de post types públicos. */
add_action('init', function () {
    foreach (get_post_types(['public' => true], 'names') as $pt) {
        if (post_type_supports($pt, 'comments')) {
            remove_post_type_support($pt, 'comments');
        }
        if (post_type_supports($pt, 'trackbacks')) {
            remove_post_type_support($pt, 'trackbacks');
        }
    }
}, 100);

/* 7a) Esconder el menú "Comentarios" en WP Admin. */
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
});

/* 7b) Si alguien navega a edit-comments.php directamente, redirigir al dashboard. */
add_action('admin_init', function () {
    global $pagenow;
    if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
});

/* 7c) Quitar el ícono de comentarios del admin bar (frontend y backend). */
add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    if ($wp_admin_bar) {
        $wp_admin_bar->remove_node('comments');
    }
});

/* 7d) Quitar el dashboard widget "Comentarios recientes". */
add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
});
