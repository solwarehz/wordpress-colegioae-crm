<?php
/**
 * template-parts/home/sedes.php — Sección Sedes.
 */

defined('ABSPATH') || exit;

$anchor = colegio_ae_get_section_anchor('sedes');
$d      = colegio_ae_defaults();
$title  = (string) get_theme_mod('colegio_ae_sedes_title', $d['sedes_title']);
$intro  = (string) get_theme_mod('colegio_ae_sedes_intro', $d['sedes_intro']);

$sd = $d['sedes'];
$sedes = [];
for ($i = 1; $i <= 3; $i++) {
    $ds = $sd[$i] ?? null;
    if (!$ds) continue;
    $name = (string) get_theme_mod("colegio_ae_sede_{$i}_name", $ds['name']);
    if ($name === '') continue;

    $niveles = [];
    foreach (['inicial' => 'Inicial', 'primaria' => 'Primaria', 'secundaria' => 'Secundaria'] as $key => $label) {
        if ((bool) get_theme_mod("colegio_ae_sede_{$i}_{$key}", $ds[$key])) {
            $img = (string) get_theme_mod("colegio_ae_sede_{$i}_foto_{$key}", $ds['foto_' . $key]);
            if ($img !== '') {
                $niveles[] = ['name' => $label, 'image' => $img];
            }
        }
    }

    $sedes[] = [
        'name'    => $name,
        'address' => (string) get_theme_mod("colegio_ae_sede_{$i}_address", $ds['address']),
        'desc'    => (string) get_theme_mod("colegio_ae_sede_{$i}_desc",    $ds['desc']),
        'niveles' => $niveles,
    ];
}
if (empty($sedes)) return;
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section sedes" aria-labelledby="<?php echo esc_attr($anchor); ?>-title">
    <div class="container">
        <header class="sedes__header">
            <h2 id="<?php echo esc_attr($anchor); ?>-title" class="sedes__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($intro)) : ?>
                <p class="sedes__subtitle"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>
        </header>

        <div class="sedes__list">
            <?php foreach ($sedes as $i => $sede) : ?>
                <article class="sede-block<?php echo $i % 2 === 0 ? ' sede-block--left' : ' sede-block--right'; ?>">
                    <div class="sede-block__info">
                        <h3 class="sede-block__name"><?php echo esc_html($sede['name']); ?></h3>
                        <?php if (!empty($sede['address'])) : ?>
                            <p class="sede-block__address">📍 <?php echo esc_html($sede['address']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($sede['desc'])) : ?>
                            <p class="sede-block__desc"><?php echo esc_html($sede['desc']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($sede['niveles'])) : ?>
                            <ul class="sede-block__niveles">
                                <?php foreach ($sede['niveles'] as $n) : ?>
                                    <li><?php echo esc_html($n['name']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($sede['niveles'])) : ?>
                        <div class="sede-block__gallery" data-niveles="<?php echo count($sede['niveles']); ?>">
                            <?php foreach ($sede['niveles'] as $n) : ?>
                                <figure class="sede-block__photo">
                                    <div class="card-image">
                                        <?php echo colegio_ae_render_image($n['image'], 'ae-card', [
                                            'alt'      => $sede['name'] . ' – ' . $n['name'],
                                            'loading'  => 'lazy',
                                            'decoding' => 'async',
                                        ]); ?>
                                    </div>
                                    <figcaption><?php echo esc_html($n['name']); ?></figcaption>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
