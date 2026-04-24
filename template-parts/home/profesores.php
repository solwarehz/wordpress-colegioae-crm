<?php
/**
 * template-parts/home/profesores.php — Carrusel de profesores destacados.
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('profesores');
$d        = colegio_ae_defaults();
$title    = (string) get_theme_mod('colegio_ae_profesores_title',    $d['profesores_title']);
$subtitle = (string) get_theme_mod('colegio_ae_profesores_subtitle', $d['profesores_subtitle']);
$count    = max(3, min(10, (int) get_theme_mod('colegio_ae_profesores_count', 5)));
$autoplay = max(3000, (int) get_theme_mod('colegio_ae_profesores_autoplay', 4500));
$btn_text = (string) get_theme_mod('colegio_ae_profesores_btn_text', $d['profesores_btn_text']);
$btn_url  = (string) get_theme_mod('colegio_ae_profesores_btn_url',  $d['profesores_btn_url']);

$teachers = new WP_Query([
    'post_type'      => 'page',
    'posts_per_page' => $count,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [
        [
            'key'   => '_wp_page_template',
            'value' => 'page-templates/page-profesor.php',
        ],
    ],
]);
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section profesores">
    <div class="container">
        <header class="profesores__header">
            <h2 class="profesores__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="profesores__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </header>

        <?php if ($teachers->have_posts()) : ?>
            <div class="profesores__carousel" data-profesor-carousel data-autoplay="<?php echo esc_attr($autoplay); ?>" aria-roledescription="carousel">
                <button class="profesores__arrow profesores__arrow--prev" data-profesor-prev aria-label="<?php esc_attr_e('Profesor anterior', 'colegio-ae'); ?>" type="button">‹</button>

                <div class="profesores__track">
                    <?php while ($teachers->have_posts()) : $teachers->the_post(); ?>
                        <article class="profesor-card" aria-roledescription="slide">
                            <a href="<?php the_permalink(); ?>" class="profesor-card__link">
                                <div class="profesor-card__image card-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('ae-card-square', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
                                    <?php else : ?>
                                        <div class="profesor-card__placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </div>
                                <h3 class="profesor-card__name"><?php the_title(); ?></h3>
                                <p class="profesor-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 18)); ?></p>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <button class="profesores__arrow profesores__arrow--next" data-profesor-next aria-label="<?php esc_attr_e('Siguiente profesor', 'colegio-ae'); ?>" type="button">›</button>
            </div>

            <?php if (!empty($btn_text) && !empty($btn_url)) : ?>
                <div class="profesores__footer">
                    <a href="<?php echo esc_url($btn_url); ?>" class="btn btn--secondary"><?php echo esc_html($btn_text); ?></a>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <p class="profesores__empty"><?php esc_html_e('Los perfiles de nuestros profesores estarán disponibles próximamente.', 'colegio-ae'); ?></p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>
