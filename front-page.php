<?php
/**
 * front-page.php — Home one-page del Colegio Albert Einstein.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main site-main--home">
    <?php
    get_template_part('template-parts/home/hero');
    get_template_part('template-parts/home/nosotros');
    get_template_part('template-parts/home/valores');
    get_template_part('template-parts/home/servicios');
    get_template_part('template-parts/home/sedes');
    get_template_part('template-parts/home/profesores');
    get_template_part('template-parts/home/mentalidad-ganadora');
    get_template_part('template-parts/home/opiniones');
    get_template_part('template-parts/home/contacto');
    ?>
</main>

<?php get_footer(); ?>
