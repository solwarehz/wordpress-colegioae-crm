<?php
/**
 * template-parts/home/mentalidad-ganadora.php — Carrusel de últimos posts del blog
 * filtrados por las categorías seleccionadas en el Customizer.
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('mentalidad');
$d        = colegio_ae_defaults();
$title    = (string) get_theme_mod('colegio_ae_mentalidad_title',    $d['mentalidad_title']);
$subtitle = (string) get_theme_mod('colegio_ae_mentalidad_subtitle', $d['mentalidad_subtitle']);
$intro    = (string) get_theme_mod('colegio_ae_mentalidad_intro',    $d['mentalidad_intro']);
$cats_raw = (string) get_theme_mod('colegio_ae_mentalidad_categories', 'concursos');
$count    = max(3, min(10, (int) get_theme_mod('colegio_ae_mentalidad_count', 5)));
$autoplay = max(3000, (int) get_theme_mod('colegio_ae_mentalidad_autoplay', 5000));

$cats = array_filter(array_map('trim', explode(',', $cats_raw)));

$query_args = [
    'post_type'      => 'post',
    'posts_per_page' => $count,
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if (!empty($cats)) {
    $query_args['category_name'] = implode(',', $cats);
}

$posts_query = new WP_Query($query_args);
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section mentalidad">
    <div class="container">
        <header class="mentalidad__header">
            <h2 class="mentalidad__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="mentalidad__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            <?php if (!empty($intro)) : ?>
                <p class="mentalidad__intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </header>

        <?php if ($posts_query->have_posts()) : ?>
            <div class="mentalidad__carousel" data-carousel data-autoplay="<?php echo esc_attr($autoplay); ?>" aria-roledescription="carousel">
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

                <?php if ($posts_query->post_count > 1) : ?>
                    <button class="mentalidad__arrow mentalidad__arrow--prev" data-carousel-prev aria-label="<?php esc_attr_e('Anterior', 'colegio-ae'); ?>" type="button">‹</button>
                    <button class="mentalidad__arrow mentalidad__arrow--next" data-carousel-next aria-label="<?php esc_attr_e('Siguiente', 'colegio-ae'); ?>" type="button">›</button>

                    <div class="mentalidad__dots" role="tablist">
                        <?php for ($i = 0; $i < $posts_query->post_count; $i++) : ?>
                            <button class="mentalidad__dot<?php echo $i === 0 ? ' mentalidad__dot--active' : ''; ?>" data-carousel-go="<?php echo $i; ?>" aria-label="<?php echo esc_attr(sprintf(__('Ir a slide %d', 'colegio-ae'), $i + 1)); ?>" type="button"></button>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <p class="mentalidad__empty">
                <?php if (current_user_can('edit_posts')) : ?>
                    <strong><?php esc_html_e('Esta sección no muestra posts.', 'colegio-ae'); ?></strong><br>
                    <?php esc_html_e('Configura las categorías en Apariencia → Personalizar → Sección: Mentalidad ganadora, y publica al menos un post en alguna de esas categorías.', 'colegio-ae'); ?>
                <?php else : ?>
                    <?php esc_html_e('Pronto compartiremos nuestras historias y logros.', 'colegio-ae'); ?>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</section>
