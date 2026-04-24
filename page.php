<?php
/**
 * page.php — Template genérico para páginas estáticas (ej. Políticas de privacidad).
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main">
    <div class="container container--narrow section">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('generic-page'); ?>>
                <header class="generic-page__header">
                    <h1 class="generic-page__title"><?php the_title(); ?></h1>
                </header>
                <div class="generic-page__content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
