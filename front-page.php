<?php
/**
 * front-page.php — Home one-page del Colegio Albert Einstein.
 *
 * El orden y la visibilidad de las secciones se controlan desde
 * Apariencia → Personalizar → Orden y visibilidad de secciones.
 *
 * La lógica de registro, orden efectivo y visibilidad está en:
 *  - inc/customizer/sections-registry.php
 *  - inc/customizer/helpers.php
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main site-main--home">
    <?php
    $order = colegio_ae_get_sections_order();

    foreach ($order as $section_slug) {
        if (!colegio_ae_section_is_enabled($section_slug)) {
            continue;
        }
        $template = colegio_ae_get_section_template($section_slug);
        if (!empty($template)) {
            get_template_part('template-parts/' . $template);
        }
    }
    ?>
</main>

<?php get_footer(); ?>
