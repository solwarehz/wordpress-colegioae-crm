<?php
/**
 * inc/customizer/helpers.php
 *
 * Funciones helper reutilizables para el Customizer y los templates:
 * - lectura de theme_mods con prefijo del tema
 * - orden efectivo de secciones
 * - estado de visibilidad por sección
 * - lectura del ID de ancla por sección
 */

defined('ABSPATH') || exit;

/**
 * Lee un theme_mod con el prefijo del tema.
 */
function colegio_ae_get_mod($key, $default = '') {
    return get_theme_mod('colegio_ae_' . $key, $default);
}

/**
 * Devuelve el orden efectivo de las secciones del home:
 *  - respeta el orden guardado en colegio_ae_sections_order
 *  - ignora slugs obsoletos
 *  - agrega al final secciones nuevas del registro que no estaban guardadas
 */
function colegio_ae_get_sections_order() {
    $registry      = colegio_ae_get_sections();
    $default_order = array_keys($registry);

    $saved = colegio_ae_get_mod('sections_order', '');
    if (empty($saved)) {
        return $default_order;
    }

    $order = array_filter(array_map('trim', explode(',', $saved)));
    $order = array_values(array_intersect($order, $default_order));

    foreach ($default_order as $slug) {
        if (!in_array($slug, $order, true)) {
            $order[] = $slug;
        }
    }

    return $order;
}

/**
 * ¿Está habilitada una sección para mostrarse en el home?
 */
function colegio_ae_section_is_enabled($slug) {
    $registry = colegio_ae_get_sections();
    if (!isset($registry[$slug])) {
        return false;
    }
    $default = isset($registry[$slug]['default_enabled']) ? (bool) $registry[$slug]['default_enabled'] : true;
    return (bool) colegio_ae_get_mod('section_' . $slug . '_enabled', $default);
}

/**
 * Devuelve la ruta del template part de una sección.
 */
function colegio_ae_get_section_template($slug) {
    $registry = colegio_ae_get_sections();
    return isset($registry[$slug]['template']) ? $registry[$slug]['template'] : '';
}

/**
 * Devuelve el ID de ancla efectivo de una sección (editable por el cliente).
 * Fallback: el default del registro. Se sanitiza a slug válido HTML.
 */
function colegio_ae_get_section_anchor($slug) {
    $registry = colegio_ae_get_sections();
    if (!isset($registry[$slug])) {
        return '';
    }
    $default = isset($registry[$slug]['anchor_default']) ? $registry[$slug]['anchor_default'] : $slug;
    $anchor  = (string) colegio_ae_get_mod('section_' . $slug . '_anchor', $default);
    $anchor  = sanitize_title($anchor);
    return $anchor !== '' ? $anchor : $default;
}

/* ==========================================================================
   Sanitizers reutilizables
   ========================================================================== */

/**
 * Sanitiza una lista coma-separada de slugs de sección contra el registro.
 */
function colegio_ae_sanitize_sections_order($input) {
    if (empty($input) || !is_string($input)) {
        return '';
    }
    $valid_slugs = array_keys(colegio_ae_get_sections());
    $parts = array_filter(array_map('trim', explode(',', $input)));
    $parts = array_filter($parts, function ($slug) use ($valid_slugs) {
        return in_array($slug, $valid_slugs, true);
    });
    return implode(',', array_values(array_unique($parts)));
}

/**
 * Sanitiza un array/string de slugs de categoría separados por coma.
 */
function colegio_ae_sanitize_category_slugs($input) {
    if (is_array($input)) {
        $input = implode(',', $input);
    }
    if (empty($input)) {
        return '';
    }
    $parts = array_filter(array_map('sanitize_title', array_map('trim', explode(',', $input))));
    return implode(',', array_values(array_unique($parts)));
}

/**
 * Sanitiza boolean (para los eye toggles).
 */
function colegio_ae_sanitize_checkbox($input) {
    return !empty($input) && $input !== '0' && $input !== 'false' ? 1 : 0;
}
