<?php
/**
 * template-parts/home/servicios.php — Sección Niveles educativos.
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('servicios');
$d        = colegio_ae_defaults();
$title    = (string) get_theme_mod('colegio_ae_servicios_title',    $d['servicios_title']);
$subtitle = (string) get_theme_mod('colegio_ae_servicios_subtitle', $d['servicios_subtitle']);

$nd = $d['niveles'];
$niveles = [];
for ($i = 1; $i <= 3; $i++) {
    $dn = $nd[$i] ?? ['name' => '', 'subtitle' => '', 'desc' => '', 'image' => '', 'link' => ''];
    $name = (string) get_theme_mod("colegio_ae_nivel_{$i}_name", $dn['name']);
    if ($name === '') continue;
    $niveles[] = [
        'name'     => $name,
        'subtitle' => (string) get_theme_mod("colegio_ae_nivel_{$i}_subtitle", $dn['subtitle']),
        'desc'     => (string) get_theme_mod("colegio_ae_nivel_{$i}_desc",     $dn['desc']),
        'image'    => (string) get_theme_mod("colegio_ae_nivel_{$i}_image",    $dn['image']),
        'link'     => (string) get_theme_mod("colegio_ae_nivel_{$i}_link",     $dn['link']),
    ];
}
if (empty($niveles)) return;
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section servicios">
    <div class="container">
        <header class="servicios__header">
            <h2 class="servicios__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="servicios__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </header>

        <div class="servicios__grid">
            <?php foreach ($niveles as $n) :
                $tag = !empty($n['link']) ? 'a' : 'article';
                $href_attr = !empty($n['link']) ? ' href="' . esc_url($n['link']) . '"' : '';
            ?>
                <<?php echo $tag; ?> class="nivel-card"<?php echo $href_attr; ?>>
                    <?php if (!empty($n['image'])) : ?>
                        <div class="nivel-card__image card-image">
                            <img src="<?php echo esc_url($n['image']); ?>" alt="<?php echo esc_attr('Nivel ' . $n['name']); ?>" loading="lazy">
                        </div>
                    <?php endif; ?>
                    <div class="nivel-card__body">
                        <h3 class="nivel-card__name"><?php echo esc_html($n['name']); ?></h3>
                        <?php if (!empty($n['subtitle'])) : ?>
                            <p class="nivel-card__subtitle"><?php echo esc_html($n['subtitle']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($n['desc'])) : ?>
                            <p class="nivel-card__desc"><?php echo esc_html($n['desc']); ?></p>
                        <?php endif; ?>
                    </div>
                </<?php echo $tag; ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>
