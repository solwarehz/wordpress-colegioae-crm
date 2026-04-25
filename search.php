<?php
/**
 * search.php — Resultados de búsqueda del sitio.
 *
 * Reutiliza el patrón visual de archive.php (grid + post-card + paginación)
 * y los estilos `.blog-archive__*` / `.post-card*` que viven en pages.css.
 *
 * Maneja 3 estados:
 *   - Con query y con resultados → grid + paginación
 *   - Con query y sin resultados → mensaje + sugerencia
 *   - Sin query (raro: alguien aterrizó en /?s= vacío) → form solamente
 */

defined('ABSPATH') || exit;

get_header();

$query = (string) get_search_query();
$found = isset($GLOBALS['wp_query']->found_posts) ? (int) $GLOBALS['wp_query']->found_posts : 0;
?>

<main id="main" class="site-main">
    <div class="container section">
        <header class="blog-archive__header">
            <h1 class="blog-archive__title">
                <?php if ($query !== '') : ?>
                    <?php printf(
                        /* translators: %s = términos de búsqueda */
                        esc_html__('Resultados para «%s»', 'colegio-ae'),
                        esc_html($query)
                    ); ?>
                <?php else : ?>
                    <?php esc_html_e('Búsqueda', 'colegio-ae'); ?>
                <?php endif; ?>
            </h1>
            <p class="blog-archive__subtitle">
                <?php
                if ($query !== '') {
                    printf(
                        esc_html(_n(
                            '%d resultado encontrado',
                            '%d resultados encontrados',
                            $found,
                            'colegio-ae'
                        )),
                        $found
                    );
                } else {
                    esc_html_e('Escribe lo que buscas en el formulario de abajo.', 'colegio-ae');
                }
                ?>
            </p>
        </header>

        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <label for="search-input" class="screen-reader-text">
                <?php esc_html_e('Buscar en el sitio', 'colegio-ae'); ?>
            </label>
            <input type="search"
                   id="search-input"
                   name="s"
                   value="<?php echo esc_attr($query); ?>"
                   placeholder="<?php esc_attr_e('Escribe una palabra clave…', 'colegio-ae'); ?>"
                   required>
            <button type="submit" class="btn btn--primary">
                <?php esc_html_e('Buscar', 'colegio-ae'); ?>
            </button>
        </form>

        <?php if (have_posts()) : ?>
            <div class="blog-archive__grid">
                <?php while (have_posts()) : the_post();
                    $pt        = get_post_type();
                    $first_cat = get_the_category();
                    $pt_obj    = get_post_type_object($pt);
                    $pt_label  = $pt_obj && $pt_obj->labels ? $pt_obj->labels->singular_name : ucfirst($pt);
                ?>
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
                                <?php if ($pt === 'post' && !empty($first_cat)) : ?>
                                    <span class="post-card__category"><?php echo esc_html($first_cat[0]->name); ?></span>
                                <?php elseif ($pt !== 'post') : ?>
                                    <span class="post-card__category"><?php echo esc_html($pt_label); ?></span>
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

            <nav class="blog-archive__pagination" aria-label="<?php esc_attr_e('Paginación de resultados', 'colegio-ae'); ?>">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '‹ ' . __('Anterior', 'colegio-ae'),
                    'next_text' => __('Siguiente', 'colegio-ae') . ' ›',
                ]);
                ?>
            </nav>

        <?php elseif ($query !== '') : ?>
            <p class="blog-archive__empty">
                <?php
                printf(
                    esc_html__('No encontramos resultados para «%s». Prueba con otra palabra o explora el menú principal.', 'colegio-ae'),
                    esc_html($query)
                );
                ?>
            </p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
