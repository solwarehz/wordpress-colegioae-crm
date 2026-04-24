<?php
/**
 * Sección Servicios — niveles educativos
 */

defined('ABSPATH') || exit;

$niveles = [
    [
        'name'     => 'Inicial',
        'subtitle' => '3 a 5 años',
        'desc'     => 'Los primeros años marcan el resto de la vida escolar. En nuestro nivel Inicial, los niños aprenden a través del juego, el movimiento y la exploración, en un ambiente seguro que estimula su curiosidad y desarrolla sus habilidades sociales, emocionales y cognitivas.',
        'image'    => 'https://picsum.photos/seed/ae-servicios-inicial/800/600',
    ],
    [
        'name'     => 'Primaria',
        'subtitle' => '1° a 6° grado',
        'desc'     => 'Construimos las bases del pensamiento crítico. Nuestros estudiantes no memorizan: comprenden, cuestionan y aplican. Desarrollamos hábitos de estudio, lectura comprensiva, razonamiento matemático y una sólida formación en valores.',
        'image'    => 'https://picsum.photos/seed/ae-servicios-primaria/800/600',
    ],
    [
        'name'     => 'Secundaria',
        'subtitle' => '1° a 5° año',
        'desc'     => 'Preparamos jóvenes listos para la universidad y para la vida. Formación académica rigurosa, orientación vocacional, proyectos de liderazgo y participación en concursos que los retan a dar lo mejor de sí.',
        'image'    => 'https://picsum.photos/seed/ae-servicios-secundaria/800/600',
    ],
];
?>

<section id="servicios" class="section servicios">
    <div class="container">
        <header class="servicios__header">
            <h2 class="servicios__title"><?php esc_html_e('Niveles educativos', 'colegio-ae'); ?></h2>
            <p class="servicios__subtitle">Acompañamos a tu hijo desde los primeros años hasta la universidad.</p>
        </header>

        <div class="servicios__grid">
            <?php foreach ($niveles as $nivel) : ?>
                <article class="nivel-card">
                    <div class="nivel-card__image card-image">
                        <img src="<?php echo esc_url($nivel['image']); ?>" alt="<?php echo esc_attr('Nivel ' . $nivel['name']); ?>" loading="lazy">
                    </div>
                    <div class="nivel-card__body">
                        <h3 class="nivel-card__name"><?php echo esc_html($nivel['name']); ?></h3>
                        <p class="nivel-card__subtitle"><?php echo esc_html($nivel['subtitle']); ?></p>
                        <p class="nivel-card__desc"><?php echo esc_html($nivel['desc']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
