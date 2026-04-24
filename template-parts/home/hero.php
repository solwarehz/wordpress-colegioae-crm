<?php
/**
 * Hero slider
 */

defined('ABSPATH') || exit;

$slides = [
    [
        'image'    => 'https://picsum.photos/seed/ae-hero-1/1920/1080',
        'title'    => 'Formamos líderes del mañana',
        'subtitle' => 'Con pensamiento crítico, valores sólidos y visión global',
        'cta_text' => 'Agenda una visita',
        'cta_url'  => '#contacto',
    ],
    [
        'image'    => 'https://picsum.photos/seed/ae-hero-2/1920/1080',
        'title'    => 'Educación que transforma',
        'subtitle' => 'Donde cada niño descubre su potencial y aprende a confiar en él',
        'cta_text' => 'Conoce nuestra propuesta',
        'cta_url'  => '#nosotros',
    ],
    [
        'image'    => 'https://picsum.photos/seed/ae-hero-3/1920/1080',
        'title'    => 'Más que un colegio, una familia',
        'subtitle' => 'Compromiso, respeto y excelencia que acompañan a tu hijo cada día',
        'cta_text' => 'Escríbenos',
        'cta_url'  => '#contacto',
    ],
];
?>

<section id="inicio" class="hero" aria-label="<?php esc_attr_e('Presentación', 'colegio-ae'); ?>">
    <div class="hero__slider" data-slider data-autoplay="6000">
        <div class="hero__track">
            <?php foreach ($slides as $i => $slide) : ?>
                <article class="hero__slide<?php echo $i === 0 ? ' hero__slide--active' : ''; ?>" aria-hidden="<?php echo $i === 0 ? 'false' : 'true'; ?>">
                    <div class="hero__image card-image">
                        <img src="<?php echo esc_url($slide['image']); ?>" alt="" loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>">
                    </div>
                    <div class="hero__overlay"></div>
                    <div class="hero__content">
                        <div class="container">
                            <h1 class="hero__title"><?php echo esc_html($slide['title']); ?></h1>
                            <p class="hero__subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
                            <?php if (!empty($slide['cta_text'])) : ?>
                                <a href="<?php echo esc_url($slide['cta_url']); ?>" class="btn btn--primary hero__cta">
                                    <?php echo esc_html($slide['cta_text']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <button class="hero__arrow hero__arrow--prev" data-slider-prev aria-label="<?php esc_attr_e('Slide anterior', 'colegio-ae'); ?>" type="button">‹</button>
        <button class="hero__arrow hero__arrow--next" data-slider-next aria-label="<?php esc_attr_e('Siguiente slide', 'colegio-ae'); ?>" type="button">›</button>

        <div class="hero__dots" role="tablist" aria-label="<?php esc_attr_e('Navegación de slides', 'colegio-ae'); ?>">
            <?php foreach ($slides as $i => $_slide) : ?>
                <button class="hero__dot<?php echo $i === 0 ? ' hero__dot--active' : ''; ?>" data-slider-go="<?php echo $i; ?>" role="tab" aria-label="<?php echo esc_attr(sprintf(__('Ir al slide %d', 'colegio-ae'), $i + 1)); ?>" type="button"></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
