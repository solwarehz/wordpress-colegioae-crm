<?php
/**
 * Template Name: Documentos institucionales
 * Template Post Type: page
 *
 * Lista de documentos institucionales editable desde Apariencia → Personalizar
 * → Página: Documentos. Hasta 10 slots con título, descripción y archivo
 * (PDF u otro tipo) elegido desde la biblioteca de Medios.
 */

defined('ABSPATH') || exit;

get_header();

/* Construir lista efectiva de documentos */
$defaults = colegio_ae_defaults()['documentos'];
$docs = [];
for ($i = 1; $i <= 10; $i++) {
    $d = $defaults[$i] ?? ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0];

    if (!(int) get_theme_mod("colegio_ae_doc_{$i}_enabled", $d['enabled'])) continue;

    $title = (string) get_theme_mod("colegio_ae_doc_{$i}_title", $d['title']);
    if ($title === '') continue;

    $file_id = (int) get_theme_mod("colegio_ae_doc_{$i}_file", $d['file']);
    $file_url = $file_id > 0 ? wp_get_attachment_url($file_id) : '';
    $file_name = $file_id > 0 ? get_the_title($file_id) : '';
    $mime = $file_id > 0 ? get_post_mime_type($file_id) : '';

    $docs[] = [
        'title'     => $title,
        'desc'      => (string) get_theme_mod("colegio_ae_doc_{$i}_desc", $d['desc']),
        'url'       => $file_url,
        'file_name' => $file_name,
        'mime'      => $mime,
    ];
}

/* Ícono según mime type */
function colegio_ae_doc_icon($mime) {
    if (strpos($mime, 'pdf') !== false) return '📄';
    if (strpos($mime, 'word') !== false || strpos($mime, 'msword') !== false) return '📝';
    if (strpos($mime, 'image') !== false) return '🖼️';
    if (strpos($mime, 'excel') !== false || strpos($mime, 'spreadsheet') !== false) return '📊';
    return '📎';
}
?>

<main id="main" class="site-main">
    <div class="container section">
        <?php while (have_posts()) : the_post(); ?>
            <header class="documentos-page__header">
                <h1 class="documentos-page__title"><?php the_title(); ?></h1>
                <?php if (get_the_content()) : ?>
                    <div class="documentos-page__intro"><?php the_content(); ?></div>
                <?php else : ?>
                    <p class="documentos-page__intro"><?php esc_html_e('Accede a los documentos oficiales del colegio: reglamento interno, calendario académico, cronograma de matrículas y más.', 'colegio-ae'); ?></p>
                <?php endif; ?>
            </header>

            <?php if (!empty($docs)) : ?>
                <ul class="documentos-page__list">
                    <?php foreach ($docs as $doc) :
                        $has_file = !empty($doc['url']);
                        $icon = $has_file ? colegio_ae_doc_icon($doc['mime']) : '📎';
                    ?>
                        <li class="documento-item">
                            <span class="documento-item__icon" aria-hidden="true"><?php echo $icon; ?></span>
                            <div class="documento-item__body">
                                <h2 class="documento-item__title"><?php echo esc_html($doc['title']); ?></h2>
                                <?php if (!empty($doc['desc'])) : ?>
                                    <p class="documento-item__desc"><?php echo esc_html($doc['desc']); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($has_file) : ?>
                                <a href="<?php echo esc_url($doc['url']); ?>" class="btn btn--secondary documento-item__cta" target="_blank" rel="noopener noreferrer" download>
                                    <?php esc_html_e('Descargar', 'colegio-ae'); ?>
                                </a>
                            <?php else : ?>
                                <span class="documento-item__cta documento-item__cta--disabled" aria-disabled="true">
                                    <?php esc_html_e('Próximamente', 'colegio-ae'); ?>
                                </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p class="documentos-page__empty">
                    <?php if (current_user_can('edit_theme_options')) : ?>
                        <strong><?php esc_html_e('No hay documentos configurados.', 'colegio-ae'); ?></strong><br>
                        <?php esc_html_e('Agrega documentos en Apariencia → Personalizar → Página: Documentos.', 'colegio-ae'); ?>
                    <?php else : ?>
                        <?php esc_html_e('Pronto encontrarás aquí los documentos oficiales del colegio.', 'colegio-ae'); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
