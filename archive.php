<?php
/**
 * archive.php — Archivo del blog (listado con grid + paginación + filtros).
 */

defined('ABSPATH') || exit;

get_header();

$cats = get_categories(['hide_empty' => true]);
$tags = get_tags(['hide_empty' => true]);
?>

<main id="main" class="site-main">
    <div class="container section">
        <header class="blog-archive__header">
            <h1 class="blog-archive__title">
                <?php
                if (is_category()) {
                    echo esc_html('Categoría: ' . single_cat_title('', false));
                } elseif (is_tag()) {
                    echo esc_html('Etiqueta: ' . single_tag_title('', false));
                } elseif (is_author()) {
                    the_post(); printf('Publicaciones de %s', esc_html(get_the_author())); rewind_posts();
                } elseif (is_day() || is_month() || is_year()) {
                    echo esc_html(get_the_archive_title());
                } else {
                    esc_html_e('Blog', 'colegio-ae');
                }
                ?>
            </h1>
            <p class="blog-archive__subtitle">Tips, historias y reflexiones para acompañarte en la educación de tus hijos.</p>
        </header>

        <?php if (!empty($cats) || !empty($tags)) : ?>
            <aside class="blog-archive__filters" aria-label="<?php esc_attr_e('Filtros del blog', 'colegio-ae'); ?>">
                <?php if (!empty($cats)) : ?>
                    <div class="blog-archive__filter-group">
                        <span class="blog-archive__filter-label"><?php esc_html_e('Categorías:', 'colegio-ae'); ?></span>
                        <ul class="blog-archive__filter-list">
                            <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"<?php echo (!is_category() && !is_tag()) ? ' class="is-active"' : ''; ?>><?php esc_html_e('Todas', 'colegio-ae'); ?></a></li>
                            <?php foreach ($cats as $c) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($c)); ?>"<?php echo (is_category($c->term_id) ? ' class="is-active"' : ''); ?>>
                                        <?php echo esc_html($c->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (!empty($tags)) : ?>
                    <div class="blog-archive__filter-group">
                        <span class="blog-archive__filter-label"><?php esc_html_e('Etiquetas:', 'colegio-ae'); ?></span>
                        <ul class="blog-archive__filter-list blog-archive__filter-list--tags">
                            <?php foreach ($tags as $t) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_tag_link($t)); ?>"<?php echo (is_tag($t->term_id) ? ' class="is-active"' : ''); ?>>
                                        #<?php echo esc_html($t->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </aside>
        <?php endif; ?>

        <?php if (have_posts()) : ?>
            <div class="blog-archive__grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="post-card__link">
                            <div class="post-card__image card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('ae-blog-featured', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
                                <?php else : ?>
                                    <div class="post-card__placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </div>
                            <div class="post-card__body">
                                <?php $first_cat = get_the_category(); if (!empty($first_cat)) : ?>
                                    <span class="post-card__category"><?php echo esc_html($first_cat[0]->name); ?></span>
                                <?php endif; ?>
                                <h2 class="post-card__title"><?php the_title(); ?></h2>
                                <p class="post-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                                <span class="post-card__meta">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <nav class="blog-archive__pagination" aria-label="<?php esc_attr_e('Paginación', 'colegio-ae'); ?>">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '‹ ' . __('Anterior', 'colegio-ae'),
                    'next_text' => __('Siguiente', 'colegio-ae') . ' ›',
                ]);
                ?>
            </nav>
        <?php else : ?>
            <p class="blog-archive__empty"><?php esc_html_e('Aún no hay publicaciones.', 'colegio-ae'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
