<?php
/**
 * header.php
 */

defined('ABSPATH') || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Saltar al contenido', 'colegio-ae'); ?></a>

<header class="site-header" role="banner">
    <div class="container site-header__container">
        <div class="site-header__brand">
            <?php if (has_custom_logo()) : ?>
                <div class="site-header__logo site-header__logo--desktop">
                    <?php the_custom_logo(); ?>
                </div>
                <a class="site-header__site-name site-header__site-name--mobile" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            <?php else : ?>
                <a class="site-header__site-name" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            <?php endif; ?>
        </div>

        <nav class="site-nav" aria-label="<?php esc_attr_e('Menú principal', 'colegio-ae'); ?>">
            <button class="site-nav__toggle" aria-expanded="false" aria-controls="primary-menu" type="button">
                <span class="site-nav__toggle-bar" aria-hidden="true"></span>
                <span class="site-nav__toggle-bar" aria-hidden="true"></span>
                <span class="site-nav__toggle-bar" aria-hidden="true"></span>
                <span class="screen-reader-text"><?php esc_html_e('Abrir menú', 'colegio-ae'); ?></span>
            </button>
            <?php
            wp_nav_menu([
                'theme_location' => 'menu-principal',
                'container'      => false,
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'site-nav__list',
                'fallback_cb'    => '__return_false',
            ]);
            ?>
        </nav>

        <div class="site-header__actions">
            <div class="site-header__tools" data-tools>
                <button class="site-header__tools-toggle" type="button" aria-expanded="false" aria-controls="header-tools-tray" aria-label="<?php esc_attr_e('Mostrar opciones', 'colegio-ae'); ?>">
                    <svg class="site-header__tools-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <div class="site-header__tools-tray" id="header-tools-tray" role="group" aria-label="<?php esc_attr_e('Preferencias', 'colegio-ae'); ?>">
                    <button class="theme-toggle" type="button" aria-label="<?php esc_attr_e('Cambiar tema claro/oscuro', 'colegio-ae'); ?>">
                        <span class="theme-toggle__icon theme-toggle__icon--sun" aria-hidden="true">☀</span>
                        <span class="theme-toggle__icon theme-toggle__icon--moon" aria-hidden="true">☾</span>
                    </button>
                </div>
            </div>

            <a class="btn btn--primary site-header__cta" href="<?php echo esc_url(home_url('/#contacto')); ?>">
                <?php esc_html_e('Contáctanos', 'colegio-ae'); ?>
            </a>
        </div>
    </div>
</header>
