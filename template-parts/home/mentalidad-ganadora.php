<?php
/**
 * Sección Mentalidad ganadora — últimos 5 posts del blog en carrusel.
 */

defined('ABSPATH') || exit;

/**
 * Solo trae posts de la categoría "Concursos".
 * El cliente debe crear esa categoría en WP Admin → Entradas → Categorías
 * y asignarla a los posts que quiera mostrar aquí. Los demás posts aparecen
 * únicamente en la página del blog.
 */
$posts_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'category_name'  => 'concursos',
]);
?>

<section id="mentalidad-ganadora" class="section mentalidad">
    <div class="container">
        <header class="mentalidad__header">
            <h2 class="mentalidad__title"><?php esc_html_e('Mentalidad ganadora', 'colegio-ae'); ?></h2>
            <p class="mentalidad__subtitle">Porque formar líderes es también formar carácter.</p>
            <p class="mentalidad__intro">
                En los concursos en los que participamos, queremos ganar. Si no ganamos, damos pelea. No nos rendimos y seguimos preparándonos. Porque más que los premios, lo que nos importa es que nuestros estudiantes desarrollen la disciplina, la confianza y la resiliencia que los acompañarán toda la vida.
            </p>
        </header>

        <?php if ($posts_query->have_posts()) : ?>
            <div class="mentalidad__carousel" data-carousel data-autoplay="5000" aria-roledescription="carousel">
                <div class="mentalidad__track">
                    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                        <article class="post-slide" aria-roledescription="slide">
                            <a href="<?php the_permalink(); ?>" class="post-slide__link">
                                <div class="post-slide__image card-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('ae-blog-featured', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
                                    <?php else : ?>
                                        <div class="post-slide__placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="post-slide__body">
                                    <h3 class="post-slide__title"><?php the_title(); ?></h3>
                                    <p class="post-slide__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 25)); ?></p>
                                    <span class="post-slide__readmore"><?php esc_html_e('Leer artículo →', 'colegio-ae'); ?></span>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <button class="mentalidad__arrow mentalidad__arrow--prev" data-carousel-prev aria-label="<?php esc_attr_e('Anterior', 'colegio-ae'); ?>" type="button">‹</button>
                <button class="mentalidad__arrow mentalidad__arrow--next" data-carousel-next aria-label="<?php esc_attr_e('Siguiente', 'colegio-ae'); ?>" type="button">›</button>

                <div class="mentalidad__dots" role="tablist">
                    <?php for ($i = 0; $i < $posts_query->post_count; $i++) : ?>
                        <button class="mentalidad__dot<?php echo $i === 0 ? ' mentalidad__dot--active' : ''; ?>" data-carousel-go="<?php echo $i; ?>" aria-label="<?php echo esc_attr(sprintf(__('Ir a slide %d', 'colegio-ae'), $i + 1)); ?>" type="button"></button>
                    <?php endfor; ?>
                </div>
            </div>
        <?php else : ?>
            <p class="mentalidad__empty">
                <?php if (current_user_can('edit_posts')) : ?>
                    <strong>Esta sección muestra artículos de la categoría «Concursos».</strong><br>
                    Crea la categoría <code>concursos</code> en <em>Entradas → Categorías</em> y asígnala a los artículos que quieras destacar aquí.
                <?php else : ?>
                    Pronto compartiremos nuestras historias y logros.
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>
