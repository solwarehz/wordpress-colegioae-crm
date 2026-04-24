<?php
/**
 * Template Name: Perfil de Profesor
 * Template Post Type: page
 *
 * Página individual de un profesor: foto arriba-izquierda con texto fluyendo
 * alrededor (CSS float + wrap).
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="main" class="site-main">
    <div class="container container--narrow section">
        <nav class="profesor-page__breadcrumb" aria-label="breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Inicio', 'colegio-ae'); ?></a>
            <span aria-hidden="true"> / </span>
            <a href="<?php echo esc_url(home_url('/profesores/')); ?>"><?php esc_html_e('Profesores', 'colegio-ae'); ?></a>
        </nav>

        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('profesor-page'); ?>>
                <header class="profesor-page__header">
                    <h1 class="profesor-page__name"><?php the_title(); ?></h1>
                </header>

                <div class="profesor-page__body">
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="profesor-page__photo card-image">
                            <?php the_post_thumbnail('ae-card-portrait', ['alt' => get_the_title(), 'loading' => 'eager']); ?>
                        </figure>
                    <?php else : ?>
                        <div class="profesor-page__photo profesor-page__photo--placeholder" aria-hidden="true"></div>
                    <?php endif; ?>

                    <div class="profesor-page__content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <footer class="profesor-page__footer">
                    <a href="<?php echo esc_url(home_url('/profesores/')); ?>" class="btn btn--secondary">
                        ‹ <?php esc_html_e('Volver a todos los profesores', 'colegio-ae'); ?>
                    </a>
                </footer>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
