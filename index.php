<?php
/**
 * Fallback principal de WordPress.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main">
    <div class="container section">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_pagination(['mid_size' => 2]); ?>
        <?php else : ?>
            <p><?php esc_html_e('No hay contenido disponible.', 'colegio-ae'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
