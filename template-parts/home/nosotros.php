<?php
/**
 * template-parts/home/nosotros.php — Sección Nosotros / Conócenos.
 */

defined('ABSPATH') || exit;

$anchor = colegio_ae_get_section_anchor('nosotros');
$d      = colegio_ae_defaults();
$title  = (string) get_theme_mod('colegio_ae_nosotros_title',       $d['nosotros_title']);
$p1     = (string) get_theme_mod('colegio_ae_nosotros_p1',          $d['nosotros_p1']);
$p2     = (string) get_theme_mod('colegio_ae_nosotros_p2',          $d['nosotros_p2']);
$video  = (string) get_theme_mod('colegio_ae_nosotros_video_url',   $d['nosotros_video_url']);
$v_alt  = (string) get_theme_mod('colegio_ae_nosotros_video_title', $d['nosotros_video_title']);

/* Extraer video ID de cualquier URL de YouTube */
$video_id = '';
if (!empty($video)) {
    if (preg_match('#(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([A-Za-z0-9_\-]{6,})#', $video, $m)) {
        $video_id = $m[1];
    }
}
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section nosotros">
    <div class="container nosotros__container">
        <div class="nosotros__text">
            <h2 class="nosotros__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($p1)) : ?>
                <p><?php echo esc_html($p1); ?></p>
            <?php endif; ?>
            <?php if (!empty($p2)) : ?>
                <p><?php echo esc_html($p2); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($video_id)) : ?>
            <div class="nosotros__video">
                <div class="nosotros__video-wrapper">
                    <iframe
                        src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr($video_id); ?>"
                        title="<?php echo esc_attr($v_alt); ?>"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"
                    ></iframe>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
