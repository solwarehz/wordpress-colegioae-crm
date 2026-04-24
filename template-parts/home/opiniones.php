<?php
/**
 * Sección Reseñas — testimonios con rating de estrellas.
 */

defined('ABSPATH') || exit;

$resenas = [
    [
        'photo'    => 'https://picsum.photos/seed/ae-testimonio-1/300/300',
        'name'     => 'María Luisa Campos',
        'relation' => 'Madre de familia · 2 hijos en el colegio',
        'text'     => 'Desde que mis hijos están en Albert Einstein, no solo mejoraron académicamente — se volvieron más seguros de sí mismos. Lo que más valoro es que los profesores conocen a cada uno por su nombre, por su historia. Eso no tiene precio.',
        'rating'   => 5,
    ],
    [
        'photo'    => 'https://picsum.photos/seed/ae-testimonio-2/300/300',
        'name'     => 'Javier Sánchez',
        'relation' => 'Padre de familia · hija en 4° de secundaria',
        'text'     => 'Mi hija entró en 1° de secundaria con dificultades en matemáticas. Hoy está por postular a la universidad y es una de las mejores de su salón. Más allá de la mejora académica, veo a una joven líder que sabe lo que quiere.',
        'rating'   => 5,
    ],
    [
        'photo'    => 'https://picsum.photos/seed/ae-testimonio-3/300/300',
        'name'     => 'Lucía Ramírez',
        'relation' => 'Madre de familia · hijo en primaria',
        'text'     => 'Elegí este colegio por la infraestructura, me quedé por los docentes. La comunicación con la institución es constante, se nota el compromiso real con cada estudiante. Mi hijo llega feliz todos los días.',
        'rating'   => 5,
    ],
];

function ae_render_stars($n) {
    $out = '<div class="opinion-card__stars" aria-label="' . esc_attr(sprintf(__('%d de 5 estrellas', 'colegio-ae'), $n)) . '">';
    for ($i = 1; $i <= 5; $i++) {
        $filled = $i <= $n;
        $out .= '<svg class="opinion-card__star' . ($filled ? '' : ' opinion-card__star--empty') . '" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26" /></svg>';
    }
    $out .= '</div>';
    return $out;
}
?>

<section id="resenas" class="section resenas">
    <div class="container">
        <header class="resenas__header">
            <h2 class="resenas__title"><?php esc_html_e('Reseñas de nuestras familias', 'colegio-ae'); ?></h2>
            <p class="resenas__subtitle">Lo que cuentan los padres, madres y alumnos que forman parte del Colegio Albert Einstein.</p>
        </header>

        <div class="resenas__grid">
            <?php foreach ($resenas as $r) : ?>
                <article class="opinion-card">
                    <?php echo ae_render_stars($r['rating']); ?>
                    <div class="opinion-card__quote-mark" aria-hidden="true">“</div>
                    <blockquote class="opinion-card__quote">
                        <?php echo esc_html($r['text']); ?>
                    </blockquote>
                    <footer class="opinion-card__footer">
                        <div class="opinion-card__avatar card-image">
                            <img src="<?php echo esc_url($r['photo']); ?>" alt="<?php echo esc_attr($r['name']); ?>" loading="lazy">
                        </div>
                        <div class="opinion-card__person">
                            <p class="opinion-card__name"><?php echo esc_html($r['name']); ?></p>
                            <p class="opinion-card__relation"><?php echo esc_html($r['relation']); ?></p>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
