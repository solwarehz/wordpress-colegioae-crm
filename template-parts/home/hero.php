<?php
/**
 * template-parts/home/hero.php — Hero slider, controlado desde Customizer.
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('hero');
$autoplay = (int) get_theme_mod('colegio_ae_hero_autoplay', 6000);
$defaults = colegio_ae_defaults()['hero_slides'];

$slides = [];
for ($i = 1; $i <= 5; $i++) {
    $d = $defaults[$i] ?? ['image' => '', 'title' => '', 'subtitle' => '', 'cta_text' => '', 'cta_url' => ''];
    $title    = (string) get_theme_mod("colegio_ae_hero_slide_{$i}_title",    $d['title']);
    $subtitle = (string) get_theme_mod("colegio_ae_hero_slide_{$i}_subtitle", $d['subtitle']);
    if ($title === '' && $subtitle === '') continue;

    $slides[] = [
        'image'    => (string) get_theme_mod("colegio_ae_hero_slide_{$i}_image",    $d['image']),
        'title'    => $title,
        'subtitle' => $subtitle,
        'cta_text' => (string) get_theme_mod("colegio_ae_hero_slide_{$i}_cta_text", $d['cta_text']),
        'cta_url'  => (string) get_theme_mod("colegio_ae_hero_slide_{$i}_cta_url",  $d['cta_url']),
    ];
}
if (empty($slides)) return;
?>

<section id="<?php echo esc_attr($anchor); ?>" class="hero" aria-label="<?php esc_attr_e('Presentación', 'colegio-ae'); ?>">
    <div class="hero__slider" data-slider data-autoplay="<?php echo esc_attr($autoplay); ?>">
        <div class="hero__track">
            <?php foreach ($slides as $i => $slide) :
                $cta_url = $slide['cta_url'];
                if ($cta_url !== '' && strpos($cta_url, '#') === 0) {
                    $cta_url = home_url('/' . $cta_url);
                }
            ?>
                <article class="hero__slide<?php echo $i === 0 ? ' hero__slide--active' : ''; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
                    <?php if (!empty($slide['image'])) : ?>
                        <div class="hero__image card-image">
                            <?php echo colegio_ae_render_image($slide['image'], 'ae-hero', [
                                'alt'         => '',
                                'loading'     => $i === 0 ? 'eager' : 'lazy',
                                'decoding'    => 'async',
                                'fetchpriority' => $i === 0 ? 'high' : 'auto',
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="hero__overlay"></div>
                    <div class="hero__content">
                        <div class="container">
                            <h1 class="hero__title"><?php echo esc_html($slide['title']); ?></h1>
                            <?php if (!empty($slide['subtitle'])) : ?>
                                <p class="hero__subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($slide['cta_text']) && !empty($cta_url)) : ?>
                                <a href="<?php echo esc_url($cta_url); ?>" class="btn btn--primary hero__cta"><?php echo esc_html($slide['cta_text']); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (count($slides) > 1) : ?>
            <button class="hero__arrow hero__arrow--prev" data-slider-prev aria-label="<?php esc_attr_e('Slide anterior', 'colegio-ae'); ?>" type="button">‹</button>
            <button class="hero__arrow hero__arrow--next" data-slider-next aria-label="<?php esc_attr_e('Siguiente slide', 'colegio-ae'); ?>" type="button">›</button>

            <div class="hero__dots" role="tablist" aria-label="<?php esc_attr_e('Navegación de slides', 'colegio-ae'); ?>">
                <?php foreach ($slides as $i => $_) : ?>
                    <button class="hero__dot<?php echo $i === 0 ? ' hero__dot--active' : ''; ?>" data-slider-go="<?php echo $i; ?>" role="tab" aria-label="<?php echo esc_attr(sprintf(__('Ir al slide %d', 'colegio-ae'), $i + 1)); ?>" type="button"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
