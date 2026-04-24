<?php
/**
 * single.php — Template para artículos individuales del blog.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('single-post'); ?>>
            <header class="single-post__header">
                <div class="container container--narrow">
                    <?php $cats = get_the_category(); if (!empty($cats)) : ?>
                        <div class="single-post__categories">
                            <?php foreach ($cats as $c) : ?>
                                <a href="<?php echo esc_url(get_category_link($c)); ?>" class="single-post__category">
                                    <?php echo esc_html($c->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="single-post__title"><?php the_title(); ?></h1>

                    <div class="single-post__meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </div>
                </div>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <div class="single-post__featured">
                    <div class="single-post__featured-image card-image">
                        <?php the_post_thumbnail('ae-hero', ['loading' => 'eager']); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="single-post__content container container--narrow">
                <?php the_content(); ?>

                <?php $tags = get_the_tags(); if (!empty($tags)) : ?>
                    <footer class="single-post__tags">
                        <span class="single-post__tags-label"><?php esc_html_e('Etiquetas:', 'colegio-ae'); ?></span>
                        <ul class="single-post__tags-list">
                            <?php foreach ($tags as $t) : ?>
                                <li><a href="<?php echo esc_url(get_tag_link($t)); ?>">#<?php echo esc_html($t->name); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </footer>
                <?php endif; ?>
            </div>

            <nav class="single-post__nav container container--narrow" aria-label="<?php esc_attr_e('Navegación entre artículos', 'colegio-ae'); ?>">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                <?php if ($prev_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="single-post__nav-link single-post__nav-link--prev">
                        <span class="single-post__nav-label">‹ <?php esc_html_e('Anterior', 'colegio-ae'); ?></span>
                        <span class="single-post__nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                    </a>
                <?php endif; ?>
                <?php if ($next_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="single-post__nav-link single-post__nav-link--next">
                        <span class="single-post__nav-label"><?php esc_html_e('Siguiente', 'colegio-ae'); ?> ›</span>
                        <span class="single-post__nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                    </a>
                <?php endif; ?>
            </nav>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
