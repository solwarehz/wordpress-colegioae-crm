<?php
/**
 * footer.php
 */

defined('ABSPATH') || exit;
?>

<footer class="site-footer" role="contentinfo">
    <div class="container site-footer__columns">

        <div class="site-footer__col site-footer__col--brand">
            <?php if (has_custom_logo()) : ?>
                <div class="site-footer__logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>
            <p class="site-footer__tagline">
                <?php esc_html_e('Formamos estudiantes líderes con pensamiento crítico, valores sólidos y visión global. Huaraz, Perú.', 'colegio-ae'); ?>
            </p>
        </div>

        <nav class="site-footer__col site-footer__col--nav" aria-label="<?php esc_attr_e('Menú secundario', 'colegio-ae'); ?>">
            <h3 class="site-footer__col-title"><?php esc_html_e('Links de interés', 'colegio-ae'); ?></h3>
            <?php
            wp_nav_menu([
                'theme_location' => 'menu-secundario',
                'container'      => false,
                'menu_class'     => 'site-footer__menu',
                'fallback_cb'    => '__return_false',
            ]);
            ?>
        </nav>

        <nav class="site-footer__col site-footer__col--social" aria-label="<?php esc_attr_e('Redes sociales', 'colegio-ae'); ?>">
            <h3 class="site-footer__col-title"><?php esc_html_e('Síguenos en:', 'colegio-ae'); ?></h3>
            <?php
            wp_nav_menu([
                'theme_location' => 'menu-redes-sociales',
                'container'      => false,
                'menu_class'     => 'site-footer__social',
                'walker'         => class_exists('Colegio_AE_Social_Walker') ? new Colegio_AE_Social_Walker() : '',
                'fallback_cb'    => '__return_false',
            ]);
            ?>
        </nav>

    </div>

    <div class="site-footer__bottom">
        <div class="container">
            <p class="site-footer__copyright">
                &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>.
                <?php esc_html_e('Todos los derechos reservados.', 'colegio-ae'); ?>
            </p>
        </div>
    </div>
</footer>

<?php get_template_part('template-parts/global/whatsapp-float'); ?>

<?php wp_footer(); ?>
</body>
</html>
