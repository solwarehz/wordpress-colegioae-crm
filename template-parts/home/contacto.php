<?php
/**
 * template-parts/home/contacto.php — Sección Contáctanos.
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('contacto');
$d        = colegio_ae_defaults();
$title    = (string) get_theme_mod('colegio_ae_contacto_title',    $d['contacto_title']);
$subtitle = (string) get_theme_mod('colegio_ae_contacto_subtitle', $d['contacto_subtitle']);
$intro    = (string) get_theme_mod('colegio_ae_contacto_intro',    $d['contacto_intro']);
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section contacto">
    <div class="container container--narrow">
        <header class="contacto__header">
            <h2 class="contacto__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="contacto__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            <?php if (!empty($intro)) : ?>
                <p class="contacto__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
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
