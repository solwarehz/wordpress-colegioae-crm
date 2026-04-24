<?php
/**
 * footer.php
 */

defined('ABSPATH') || exit;

/* Valores del Customizer (Footer) */
$ae_footer_logo    = (string) get_theme_mod('colegio_ae_footer_logo', '');
$ae_footer_tagline = (string) get_theme_mod('colegio_ae_footer_tagline', __('Formamos estudiantes líderes con pensamiento crítico, valores sólidos y visión global. Huaraz, Perú.', 'colegio-ae'));
$ae_col2_title     = (string) get_theme_mod('colegio_ae_footer_col2_title', __('Links de interés', 'colegio-ae'));
$ae_col3_title     = (string) get_theme_mod('colegio_ae_footer_col3_title', __('Síguenos en:', 'colegio-ae'));
$ae_copyright      = (string) get_theme_mod('colegio_ae_footer_copyright', __('Colegio Albert Einstein. Todos los derechos reservados.', 'colegio-ae'));
?>

<footer class="site-footer" role="contentinfo">
    <div class="container site-footer__columns">

        <div class="site-footer__col site-footer__col--brand">
            <?php if (!empty($ae_footer_logo)) : ?>
                <div class="site-footer__logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url($ae_footer_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" loading="lazy">
                    </a>
                </div>
            <?php elseif (has_custom_logo()) : ?>
                <div class="site-footer__logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>
            <?php if (!empty($ae_footer_tagline)) : ?>
                <p class="site-footer__tagline"><?php echo esc_html($ae_footer_tagline); ?></p>
            <?php endif; ?>
        </div>

        <nav class="site-footer__col site-footer__col--nav" aria-label="<?php echo esc_attr($ae_col2_title); ?>">
            <h3 class="site-footer__col-title"><?php echo esc_html($ae_col2_title); ?></h3>
            <?php
            wp_nav_menu([
                'theme_location' => 'menu-secundario',
                'container'      => false,
                'menu_class'     => 'site-footer__menu',
                'fallback_cb'    => '__return_false',
            ]);
            ?>
        </nav>

        <nav class="site-footer__col site-footer__col--social" aria-label="<?php echo esc_attr($ae_col3_title); ?>">
            <h3 class="site-footer__col-title"><?php echo esc_html($ae_col3_title); ?></h3>
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
                &copy; <?php echo esc_html(date('Y')); ?> <?php echo esc_html($ae_copyright); ?>
            </p>
        </div>
    </div>
</footer>

<?php get_template_part('template-parts/global/whatsapp-float'); ?>

<?php wp_footer(); ?>
</body>
</html>
