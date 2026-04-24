<?php
/**
 * Template Name: Listado de Profesores
 * Template Post Type: page
 *
 * Grid paginado con TODOS los profesores (páginas con template page-profesor.php).
 */

defined('ABSPATH') || exit;

get_header();

$paged = max(1, (int) get_query_var('paged', get_query_var('page', 1)));

$teachers = new WP_Query([
    'post_type'      => 'page',
    'posts_per_page' => 12,
    'paged'          => $paged,
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

<main id="main" class="site-main">
    <div class="container section">
        <?php while (have_posts()) : the_post(); ?>
            <header class="profesores-archive__header">
                <h1 class="profesores-archive__title"><?php the_title(); ?></h1>
                <?php if (get_the_content()) : ?>
                    <div class="profesores-archive__intro"><?php the_content(); ?></div>
                <?php else : ?>
                    <p class="profesores-archive__intro">
                        Nuestros docentes son el corazón del proyecto: profesionales apasionados por enseñar, en formación constante, que creen que enseñar es acompañar a descubrir.
                    </p>
                <?php endif; ?>
            </header>
        <?php endwhile; ?>

        <?php if ($teachers->have_posts()) : ?>
            <div class="profesores-archive__grid">
                <?php while ($teachers->have_posts()) : $teachers->the_post(); ?>
                    <article class="profesor-card">
                        <a href="<?php the_permalink(); ?>" class="profesor-card__link">
                            <div class="profesor-card__image card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('ae-card-square', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
                                <?php else : ?>
                                    <div class="profesor-card__placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </div>
                            <h2 class="profesor-card__name"><?php the_title(); ?></h2>
                            <p class="profesor-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: wp_strip_all_tags(get_the_content()), 22)); ?></p>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            $big = 999999999;
            $links = paginate_links([
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => $teachers->max_num_pages,
                'mid_size'  => 2,
                'prev_text' => '‹ ' . __('Anterior', 'colegio-ae'),
                'next_text' => __('Siguiente', 'colegio-ae') . ' ›',
                'type'      => 'list',
            ]);
            if ($links) : ?>
                <nav class="profesores-archive__pagination" aria-label="<?php esc_attr_e('Paginación', 'colegio-ae'); ?>">
                    <?php echo $links; // output seguro de paginate_links ?>
                </nav>
            <?php endif; ?>
        <?php else : ?>
            <p class="profesores-archive__empty">Los perfiles de nuestros profesores estarán disponibles próximamente.</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</main>

<?php get_footer(); ?>
