<?php
/**
 * inc/cache.php
 *
 * Caché de queries del home con transients.
 * Las secciones "Profesores" y "Mentalidad ganadora" ejecutan WP_Query
 * en cada pageview. Con esto, la primera visita ejecuta el query y
 * guarda los IDs por 1 hora; las siguientes leen del transient.
 *
 * Invalidación:
 *  - save_post / deleted_post (cuando cambia un profesor o un post de blog)
 *  - customize_save_after (cuando el admin cambia count, categorías, etc.)
 */

defined('ABSPATH') || exit;

/**
 * Devuelve IDs de posts ejecutando una WP_Query liviana,
 * cacheando el resultado en un transient.
 */
function colegio_ae_cached_post_ids($key, array $args, $ttl = HOUR_IN_SECONDS) {
    $cached = get_transient($key);
    if (is_array($cached)) {
        return $cached;
    }
    $args['fields']                 = 'ids';
    $args['no_found_rows']          = true;
    $args['update_post_meta_cache'] = false;
    $args['update_post_term_cache'] = false;

    $q   = new WP_Query($args);
    $ids = $q->posts ?: [];
    set_transient($key, $ids, $ttl);
    return $ids;
}

/**
 * Invalida los caches del home cuando cambia un post relevante.
 */
function colegio_ae_invalidate_home_caches($post_id) {
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    $post = get_post($post_id);
    if (!$post) {
        return;
    }
    if ($post->post_type === 'post') {
        delete_transient('colegio_ae_home_mentalidad');
    }
    if ($post->post_type === 'page') {
        $template = get_page_template_slug($post_id);
        if ($template === 'page-templates/page-profesor.php') {
            delete_transient('colegio_ae_home_profesores');
        }
    }
}
add_action('save_post',    'colegio_ae_invalidate_home_caches');
add_action('deleted_post', 'colegio_ae_invalidate_home_caches');

/**
 * Invalida ambos caches cuando se guardan cambios en el Customizer.
 * Cubre el caso de cambiar count, categorías de mentalidad, etc.
 */
add_action('customize_save_after', function () {
    delete_transient('colegio_ae_home_profesores');
    delete_transient('colegio_ae_home_mentalidad');
});
