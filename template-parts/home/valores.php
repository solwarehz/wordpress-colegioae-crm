<?php
/**
 * template-parts/home/valores.php — Sección Valores.
 */

defined('ABSPATH') || exit;

$anchor   = colegio_ae_get_section_anchor('valores');
$title    = (string) get_theme_mod('colegio_ae_valores_title', 'Nuestros valores');
$subtitle = (string) get_theme_mod('colegio_ae_valores_subtitle', '');

$icons = [
    'handshake' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 17 9 19l-2-2-2 2-3-3 7-7 7 7 1 1-4 4-2-2Z"/><path d="M13 7 9 3l-1 1-2-2-3 3 4 4"/><path d="m14 14 3 3-2 2 3 3 3-3-7-7 3-3-1-1 4-4 3 3-4 4"/></svg>',
    'heart'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
    'star'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"/></svg>',
    'award'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>',
    'refresh'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',
    'users'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'shield'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    'book'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
    'globe'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
    'lightbulb' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-4 12.7c.6.4 1 1.2 1 2V18h6v-1.3c0-.8.4-1.6 1-2A7 7 0 0 0 12 2z"/></svg>',
];

$valores = [];
for ($i = 1; $i <= 6; $i++) {
    $name = (string) get_theme_mod("colegio_ae_valor_{$i}_name", '');
    if ($name === '') continue;
    $valores[] = [
        'name' => $name,
        'icon' => (string) get_theme_mod("colegio_ae_valor_{$i}_icon", 'star'),
        'desc' => (string) get_theme_mod("colegio_ae_valor_{$i}_desc", ''),
    ];
}
if (empty($valores)) return;
?>

<section id="<?php echo esc_attr($anchor); ?>" class="section valores">
    <div class="container">
        <header class="valores__header">
            <h2 class="valores__title"><?php echo esc_html($title); ?></h2>
            <?php if (!empty($subtitle)) : ?>
                <p class="valores__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </header>

        <div class="valores__grid">
            <?php foreach ($valores as $v) : ?>
                <article class="valor-card">
                    <div class="valor-card__icon"><?php echo $icons[$v['icon']] ?? $icons['star']; ?></div>
                    <h3 class="valor-card__name"><?php echo esc_html($v['name']); ?></h3>
                    <?php if (!empty($v['desc'])) : ?>
                        <p class="valor-card__desc"><?php echo esc_html($v['desc']); ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
