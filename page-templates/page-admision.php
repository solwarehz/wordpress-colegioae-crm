<?php
/**
 * Template Name: Admisión
 * Template Post Type: page
 *
 * Landing exclusiva para captar leads de admisión. Sin distractores:
 *   - Mini-header solo con logo (enlaza a home)
 *   - Banner full-width
 *   - H1 + párrafo invitacional + formulario Tally
 *   - Mini-footer con copyright
 *   - WhatsApp float (canal alternativo de conversión)
 *
 * No usa get_header() / get_footer() porque queremos control total
 * sobre el chrome (sin menú de navegación, sin columnas, etc.).
 */

defined('ABSPATH') || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('page-admision-landing'); ?>>
    <?php wp_body_open(); ?>

    <a class="skip-link screen-reader-text" href="#admision-form">
        <?php esc_html_e('Saltar al formulario', 'colegio-ae'); ?>
    </a>

    <header class="admision-header">
        <div class="admision-logo">
            <?php
            // Logo sin link envolvente. En una landing de conversión, todo
            // link representa una vía de escape del lead. Mantenemos el logo
            // como elemento visual de marca/credibilidad, pero sin que el
            // visitante pueda navegar al resto del sitio.
            $logo_id = (int) get_theme_mod('custom_logo', 0);
            if ($logo_id > 0) {
                echo wp_get_attachment_image($logo_id, 'full', false, [
                    'class' => 'custom-logo',
                    'alt'   => get_bloginfo('name'),
                ]);
            } else {
                echo '<span class="admision-logo-text">' . esc_html(get_bloginfo('name')) . '</span>';
            }
            ?>
        </div>
    </header>

    <main class="admision-main" id="main">

        <?php
        $banner_id  = (int)    get_theme_mod('colegio_ae_admision_banner_image', 0);
        $banner_alt = (string) get_theme_mod('colegio_ae_admision_banner_alt', __('Estudiantes del Colegio Albert Einstein', 'colegio-ae'));
        $title      = (string) get_theme_mod('colegio_ae_admision_title', __('Admisión', 'colegio-ae'));
        $intro      = (string) get_theme_mod('colegio_ae_admision_intro', __('Solicita una vacante para tu hijo en el Colegio Albert Einstein. Déjanos tus datos y un asesor te contactará.', 'colegio-ae'));
        ?>

        <?php if ($banner_id > 0) : ?>
            <div class="admision-banner">
                <?php echo wp_get_attachment_image($banner_id, 'ae-hero', false, [
                    'alt'           => $banner_alt,
                    'class'         => 'admision-banner__image',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                    'decoding'      => 'async',
                ]); ?>
            </div>
        <?php endif; ?>

        <section class="admision-content container container--narrow">
            <header class="admision-hero">
                <h1 class="admision-title"><?php echo esc_html(colegio_ae_sentence_case($title)); ?></h1>
                <?php if (!empty($intro)) : ?>
                    <p class="admision-intro"><?php echo esc_html($intro); ?></p>
                <?php endif; ?>
            </header>

            <div class="admision-form" id="admision-form">
                <?php
                $fallback = '<div class="admision-form__notice">'
                    . '<p><strong>' . esc_html__('Configura el formulario de admisión en Apariencia → Personalizar → Página: Admisión.', 'colegio-ae') . '</strong></p>'
                    . '<p>' . esc_html__('Crea un formulario en tally.so con campos: nombre, email, celular, nivel solicitado, edad del estudiante. Pega el embed.', 'colegio-ae') . '</p>'
                    . '</div>';
                colegio_ae_render_tally_embed('admision', $fallback);
                ?>
            </div>
        </section>

    </main>

    <footer class="admision-footer">
        <p class="admision-copyright">
            &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Todos los derechos reservados.', 'colegio-ae'); ?>
        </p>
    </footer>

    <?php get_template_part('template-parts/global/whatsapp-float'); ?>

    <?php wp_footer(); ?>
</body>
</html>
