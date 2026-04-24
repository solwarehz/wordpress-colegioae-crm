<?php
/**
 * Template Name: Documentos institucionales
 * Template Post Type: page
 *
 * Lista de PDFs/archivos institucionales.
 * Los documentos se pueden crear como páginas hijas o se renderizan desde el contenido.
 */

defined('ABSPATH') || exit;

get_header();

// Lista placeholder. En producción se puede conectar a una taxonomía custom
// o a un CPT "documento" en Fase 2. Por ahora, el contenido de la página
// renderiza la lista.
$docs_placeholder = [
    ['title' => 'Reglamento Interno 2026',      'desc' => 'Normas de convivencia, derechos y deberes.',     'url' => '#'],
    ['title' => 'Calendario Académico',         'desc' => 'Fechas clave del año escolar.',                   'url' => '#'],
    ['title' => 'Cronograma de Matrículas',     'desc' => 'Fechas y requisitos para nueva inscripción.',     'url' => '#'],
    ['title' => 'Ideario Institucional',        'desc' => 'Misión, visión y principios del colegio.',        'url' => '#'],
    ['title' => 'Plan de Estudios',             'desc' => 'Contenidos curriculares por nivel.',              'url' => '#'],
];
?>

<main id="main" class="site-main">
    <div class="container section">
        <?php while (have_posts()) : the_post(); ?>
            <header class="documentos-page__header">
                <h1 class="documentos-page__title"><?php the_title(); ?></h1>
                <?php if (get_the_content()) : ?>
                    <div class="documentos-page__intro"><?php the_content(); ?></div>
                <?php else : ?>
                    <p class="documentos-page__intro">Accede a los documentos oficiales del colegio: reglamento interno, calendario académico, cronograma de matrículas y más.</p>
                <?php endif; ?>
            </header>

            <ul class="documentos-page__list">
                <?php foreach ($docs_placeholder as $doc) : ?>
                    <li class="documento-item">
                        <span class="documento-item__icon" aria-hidden="true">📄</span>
                        <div class="documento-item__body">
                            <h2 class="documento-item__title"><?php echo esc_html($doc['title']); ?></h2>
                            <p class="documento-item__desc"><?php echo esc_html($doc['desc']); ?></p>
                        </div>
                        <a href="<?php echo esc_url($doc['url']); ?>" class="btn btn--secondary documento-item__cta" download>
                            <?php esc_html_e('Descargar', 'colegio-ae'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="documentos-page__note">
                <small><em>Los archivos reales serán subidos por el colegio desde WP Admin. Esta es una lista de referencia.</em></small>
            </p>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
