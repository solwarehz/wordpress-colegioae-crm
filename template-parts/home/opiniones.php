<?php
/**
 * template-parts/home/opiniones.php — Sección Reseñas (file legacy llamado opiniones).
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('resenas');
$d        = colegio_ae_defaults();
$title    = (string) get_theme_mod('colegio_ae_resenas_title',    $d['resenas_title']);
$subtitle = (string) get_theme_mod('colegio_ae_resenas_subtitle', $d['resenas_subtitle']);

$rd = $d['resenas'];
$resenas = [];
for ($i = 1; $i <= 3; $i++) {
    $dr = $rd[$i] ?? null;
    if (!$dr) continue;
    $name = (string) get_theme_mod("colegio_ae_resena_{$i}_name", $dr['name']);
    if ($name === '') continue;
    $resenas[] = [
        'photo'    => (string) get_theme_mod("colegio_ae_resena_{$i}_photo",    $dr['photo']),
        'name'     => $name,
        'relation' => (string) get_theme_mod("colegio_ae_resena_{$i}_relation", $dr['relation']),
        'text'     => (string) get_theme_mod("colegio_ae_resena_{$i}_text",     $dr['text']),
        'rating'   => (int) get_theme_mod("colegio_ae_resena_{$i}_rating",      $dr['rating']),
    ];
}
if (empty($resenas)) return;

if (!function_exists('ae_render_stars')) {
    function ae_render_stars($n) {
        $n = max(0, min(5, (int) $n));
        $out = '<div class="opinion-card__stars" aria-label="' . esc_attr(sprintf(__('%d de 5 estrellas', 'colegio-ae'), $n)) . '">';
        for ($i = 1; $i <= 5; $i++) {
            $filled = $i <= $n;
            $out .= '<svg class="opinion-card__star' . ($filled ? '' : ' opinion-card__star--empty') . '" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26" /></svg>';
        }
        $out .= '</div>';
        return $out;
    }
}
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section resenas" aria-labelledby="<?php echo esc_attr($anchor); ?>-title">
    <div class="container">
        <header class="resenas__header">
            <h2 id="<?php echo esc_attr($anchor); ?>-title" class="resenas__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="resenas__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </header>

        <div class="resenas__grid">
            <?php foreach ($resenas as $r) : ?>
                <article class="opinion-card">
                    <?php echo ae_render_stars($r['rating']); ?>
                    <div class="opinion-card__quote-mark" aria-hidden="true">“</div>
                    <blockquote class="opinion-card__quote"><?php echo esc_html($r['text']); ?></blockquote>
                    <footer class="opinion-card__footer">
                        <?php if (!empty($r['photo'])) : ?>
                            <div class="opinion-card__avatar card-image">
                                <?php echo colegio_ae_render_image($r['photo'], 'ae-card-square', [
                                    'alt'      => 'Foto de ' . $r['name'],
                                    'loading'  => 'lazy',
                                    'decoding' => 'async',
                                ]); ?>
                            </div>
                        <?php endif; ?>
                        <div class="opinion-card__person">
                            <p class="opinion-card__name"><?php echo esc_html($r['name']); ?></p>
                            <?php if (!empty($r['relation'])) : ?>
                                <p class="opinion-card__relation"><?php echo esc_html($r['relation']); ?></p>
                            <?php endif; ?>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
