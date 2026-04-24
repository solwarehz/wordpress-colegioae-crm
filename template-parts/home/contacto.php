<?php
/**
 * Sección Contáctanos — formulario Fluent Forms (4 campos).
 */

defined('ABSPATH') || exit;

$fluentform_id = absint(get_theme_mod('contacto_fluentform_id', 1));
?>

<section id="contacto" class="section contacto">
    <div class="container container--narrow">
        <header class="contacto__header">
            <h2 class="contacto__title"><?php esc_html_e('Escríbenos', 'colegio-ae'); ?></h2>
            <p class="contacto__subtitle">Estamos aquí para resolver tus dudas y acompañarte en la decisión educativa más importante.</p>
            <p class="contacto__intro">
                Déjanos tus datos y nos pondremos en contacto contigo en menos de 24 horas. También puedes escribirnos directamente por WhatsApp al número visible en la parte inferior derecha.
            </p>
        </header>

        <div class="contacto__form">
            <?php
            $fallback = '<div class="contacto__notice">
                <p><strong>Configura tu formulario de contacto en Apariencia → Personalizar → Formularios (Tally).</strong></p>
                <p>Crea un formulario en <a href="https://tally.so" target="_blank" rel="noopener">tally.so</a> (gratuito) con los campos <em>nombre</em>, <em>email</em>, <em>celular</em> y <em>mensaje</em>, copia el código embed y pégalo en el Customizer.</p>
            </div>';
            colegio_ae_render_tally_embed('contacto', $fallback);
            ?>
        </div>
    </div>
</section>
