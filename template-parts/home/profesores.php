<?php
/**
 * Sección Profesores — carrusel auto-rotate de los últimos 5 profesores.
 */

defined('ABSPATH') || exit;

$teachers = new WP_Query([
    'post_type'      => 'page',
    'posts_per_page' => 5,
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

<section id="profesores" class="section profesores">
    <div class="container">
        <header class="profesores__header">
            <h2 class="profesores__title"><?php esc_html_e('Nuestros profesores', 'colegio-ae'); ?></h2>
            <p class="profesores__subtitle">Profesionales apasionados por enseñar, en formación constante, que creen que enseñar es acompañar a descubrir.</p>
        </header>

        <?php if ($teachers->have_posts()) : ?>
            <div class="profesores__carousel" data-profesor-carousel data-autoplay="4500" aria-roledescription="carousel">
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

            <div class="profesores__footer">
                <a href="<?php echo esc_url(home_url('/profesores/')); ?>" class="btn btn--secondary">
                    <?php esc_html_e('Ver todos los profesores', 'colegio-ae'); ?>
                </a>
            </div>
        <?php else : ?>
            <p class="profesores__empty">Los perfiles de nuestros profesores estarán disponibles próximamente.</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>
