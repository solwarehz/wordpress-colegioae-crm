<?php
/**
 * Template Name: Libro de Reclamaciones
 * Template Post Type: page
 *
 * Página informativa con H1 + párrafo explicativo + formulario Fluent Forms.
 * Contenedor angosto para que el form luzca atractivo.
 * Requerido por Ley 29733 del Perú.
 */

defined('ABSPATH') || exit;

get_header();

$reclamaciones_form_id = absint(get_theme_mod('reclamaciones_fluentform_id', 2));
?>

<main id="main" class="site-main reclamaciones-page">
    <div class="container container--narrow section">
        <?php while (have_posts()) : the_post(); ?>
            <header class="reclamaciones-page__header">
                <img
                    class="reclamaciones-page__badge"
                    src="<?php echo esc_url(COLEGIO_AE_URI . '/assets/icons/libro-reclamaciones.svg'); ?>"
                    alt="<?php esc_attr_e('Libro de Reclamaciones SUNAT', 'colegio-ae'); ?>"
                    width="96"
                    height="120"
                >
                <h1 class="reclamaciones-page__title"><?php the_title(); ?></h1>
            </header>

            <div class="reclamaciones-page__intro">
                <?php if (get_the_content()) : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                    <p>
                        Conforme al Código de Protección y Defensa del Consumidor (Ley N° 29571) y la Ley 29733 del Perú, el Colegio Albert Einstein pone a tu disposición este libro de reclamaciones virtual. Si tienes un reclamo o queja sobre nuestros servicios educativos o administrativos, completa el siguiente formulario.
                    </p>
                    <p>
                        Nos comprometemos a responder en un plazo máximo de <strong>30 días calendario</strong>.
                    </p>
                <?php endif; ?>
            </div>

            <div class="reclamaciones-page__form">
                <?php
                $fallback = '<div class="reclamaciones-page__notice">
                    <p><strong>Configura el formulario en Apariencia → Personalizar → Formularios (Tally).</strong></p>
                    <p>Crea en <a href="https://tally.so" target="_blank" rel="noopener noreferrer">tally.so</a> el formulario con los campos exigidos por Indecopi (tipo de documento, número, nombres y apellidos, domicilio, teléfono, email, tipo reclamo/queja, detalle del pedido, detalle del reclamo). Pega el código embed en el Customizer.</p>
                </div>';
                colegio_ae_render_tally_embed('reclamaciones', $fallback);
                ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
