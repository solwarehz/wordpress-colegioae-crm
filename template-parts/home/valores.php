<?php
/**
 * Sección Valores — 6 valores institucionales
 */

defined('ABSPATH') || exit;

$icons = [
    'handshake' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 17 9 19l-2-2-2 2-3-3 7-7 7 7 1 1-4 4-2-2Z"/><path d="M13 7 9 3l-1 1-2-2-3 3 4 4"/><path d="m14 14 3 3-2 2 3 3 3-3-7-7 3-3-1-1 4-4 3 3-4 4"/></svg>',
    'heart'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
    'star'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"/></svg>',
    'award'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>',
    'refresh'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',
    'users'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
];

$valores = [
    ['name' => 'Compromiso',    'icon' => 'handshake', 'desc' => 'Trabajamos cada día con la convicción de que cada estudiante merece nuestra mejor versión. El compromiso no es una promesa — es nuestra forma de enseñar.'],
    ['name' => 'Humanidad',     'icon' => 'heart',     'desc' => 'Detrás de cada estudiante hay una familia, una historia y sueños únicos. Educamos con empatía, escuchando y acompañando en cada etapa.'],
    ['name' => 'Liderazgo',     'icon' => 'star',      'desc' => 'Formamos estudiantes que no siguen, sino que proponen. Que no repiten, sino que crean. Que asumen la responsabilidad de transformar su entorno.'],
    ['name' => 'Excelencia',    'icon' => 'award',     'desc' => 'No nos conformamos con lo bueno cuando podemos alcanzar lo mejor. La excelencia académica y humana es el estándar al que aspiramos cada día.'],
    ['name' => 'Adaptabilidad', 'icon' => 'refresh',   'desc' => 'Vivimos en un mundo que cambia rápido. Enseñamos a nuestros estudiantes a aprender siempre, a desaprender cuando toque y a crecer en cualquier escenario.'],
    ['name' => 'Respeto',       'icon' => 'users',     'desc' => 'Respetamos la diversidad de pensamiento, la individualidad de cada alumno y la cultura de cada familia. El respeto mutuo es la base de nuestra comunidad.'],
];
?>

<section id="valores" class="section valores">
    <div class="container">
        <header class="valores__header">
            <h2 class="valores__title"><?php esc_html_e('Nuestros valores', 'colegio-ae'); ?></h2>
            <p class="valores__subtitle">Los 6 pilares que sostienen nuestra forma de educar.</p>
        </header>

        <div class="valores__grid">
            <?php foreach ($valores as $valor) : ?>
                <article class="valor-card">
                    <div class="valor-card__icon">
                        <?php echo $icons[$valor['icon']] ?? ''; // SVG confiable, escape ya seguro ?>
                    </div>
                    <h3 class="valor-card__name"><?php echo esc_html($valor['name']); ?></h3>
                    <p class="valor-card__desc"><?php echo esc_html($valor['desc']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
