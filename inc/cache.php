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

/**
 * Resuelve una URL de attachment a su ID, cacheando en transient por 24h.
 * Los theme_mods del Customizer guardan URLs (no IDs); para poder usar
 * wp_get_attachment_image() con srcset automático, necesitamos el ID.
 */
function colegio_ae_attachment_id_from_url($url) {
    $url = trim((string) $url);
    if ($url === '') {
        return 0;
    }
    $cache_key = 'ae_url2id_' . md5($url);
    $cached    = get_transient($cache_key);
    if ($cached !== false) {
        return (int) $cached;
    }
    $id = (int) attachment_url_to_postid($url);
    set_transient($cache_key, $id, DAY_IN_SECONDS);
    return $id;
}

/**
 * Renderiza un <img> con srcset responsive si la URL corresponde a una
 * imagen de la biblioteca de Medios. Si no, cae a <img src> simple
 * (backwards-compat para URLs externas o huérfanas).
 *
 * @param string $url      URL del attachment (guardada en un theme_mod).
 * @param string $size     Tamaño registrado (ae-hero, ae-card, etc.).
 * @param array  $atts     Atributos extra (alt, loading, class, etc.).
 * @return string          HTML del <img> listo para echo.
 */
function colegio_ae_render_image($url, $size = 'large', $atts = []) {
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }
    $id = colegio_ae_attachment_id_from_url($url);
    if ($id > 0) {
        return wp_get_attachment_image($id, $size, false, $atts);
    }
    // Fallback: <img> simple con URL cruda.
    $attr_html = '';
    foreach ($atts as $k => $v) {
        $attr_html .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
    }
    return '<img src="' . esc_url($url) . '"' . $attr_html . '>';
}

/**
 * Invalida el cache URL→ID cuando se elimina o actualiza un attachment.
 */
add_action('delete_attachment', function ($post_id) {
    $url = wp_get_attachment_url($post_id);
    if ($url) {
        delete_transient('ae_url2id_' . md5($url));
    }
});
