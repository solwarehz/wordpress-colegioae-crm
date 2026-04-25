<?php
/**
 * inc/social-nav-walker.php
 *
 * Walker personalizado para menu-redes-sociales.
 * Detecta el título del ítem (facebook, instagram, tiktok, youtube)
 * y lo reemplaza por el SVG correspondiente + estilos.
 */

defined('ABSPATH') || exit;

class Colegio_AE_Social_Walker extends Walker_Nav_Menu {

    /**
     * Redes soportadas. El nombre debe coincidir (case-insensitive) con el
     * label del ítem del menú en WP Admin.
     */
    private static $supported = ['facebook', 'instagram', 'tiktok', 'youtube'];

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $label = strtolower(trim($item->title));
        $classes = ['site-footer__social-item'];

        $item_output  = '<li class="' . esc_attr(implode(' ', $classes)) . '">';
        $item_output .= sprintf(
            '<a class="site-footer__social-link site-footer__social-link--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">',
            esc_attr($label),
            esc_url($item->url),
            esc_attr($item->title)
        );

        if (in_array($label, self::$supported, true)) {
            $item_output .= self::get_svg_icon($label);
        } else {
            $item_output .= '<span>' . esc_html($item->title) . '</span>';
        }

        $item_output .= '</a>';

        $output .= $item_output;
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }

    /**
     * Lee el SVG de assets/icons/<red>.svg y lo devuelve inline.
     *
     * Usa get_theme_file_path() (WP 4.7+) que resuelve dinámicamente:
     * busca primero en el child theme activo, y cae al template padre
     * si el archivo no existe en el child. Esto permite que un child
     * theme futuro override iconos individuales sin tocar este walker.
     *
     * Fallback: texto simple si el archivo no existe en ninguno de los dos.
     */
    private static function get_svg_icon($name) {
        $path = get_theme_file_path('assets/icons/' . $name . '.svg');
        if (file_exists($path)) {
            $svg = file_get_contents($path);
            return $svg !== false ? $svg : '<span>' . esc_html($name) . '</span>';
        }
        return '<span>' . esc_html($name) . '</span>';
    }
}
