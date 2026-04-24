<?php
/**
 * Sección Sedes — infraestructura
 */

defined('ABSPATH') || exit;

$sedes = [
    [
        'name'    => 'Sede Principal',
        'address' => 'Jr. Principal 123, Huaraz',
        'desc'    => 'Nuestra sede principal alberga los tres niveles educativos. Aulas cómodas, espacios para el juego y el deporte, biblioteca y laboratorio, pensados para que cada estudiante tenga las condiciones adecuadas para aprender y desarrollarse.',
        'niveles' => [
            ['name' => 'Inicial',     'image' => 'https://picsum.photos/seed/ae-sede1-inicial/900/600'],
            ['name' => 'Primaria',    'image' => 'https://picsum.photos/seed/ae-sede1-primaria/900/600'],
            ['name' => 'Secundaria',  'image' => 'https://picsum.photos/seed/ae-sede1-secundaria/900/600'],
        ],
    ],
    [
        'name'    => 'Sede 2',
        'address' => 'Av. Secundaria 456, Huaraz',
        'desc'    => 'En nuestra segunda sede acompañamos a los estudiantes de Inicial y Primaria en un espacio diseñado especialmente para ellos, con ambientes de escala humana y la cercanía que los niños necesitan en sus primeros años escolares.',
        'niveles' => [
            ['name' => 'Inicial',  'image' => 'https://picsum.photos/seed/ae-sede2-inicial/900/600'],
            ['name' => 'Primaria', 'image' => 'https://picsum.photos/seed/ae-sede2-primaria/900/600'],
        ],
    ],
    [
        'name'    => 'Sede 3',
        'address' => 'Jr. Tercera 789, Huaraz',
        'desc'    => 'Nuestra tercera sede ofrece un espacio dedicado con aulas pensadas para el trabajo en equipo, la investigación y la preparación pre-universitaria.',
        'niveles' => [
            ['name' => 'Secundaria', 'image' => 'https://picsum.photos/seed/ae-sede3-secundaria/900/600'],
        ],
    ],
];
?>

<section id="sedes" class="section sedes">
    <div class="container">
        <header class="sedes__header">
            <h2 class="sedes__title"><?php esc_html_e('Nuestras sedes', 'colegio-ae'); ?></h2>
            <p class="sedes__subtitle">Contamos con 3 sedes en Huaraz, pensadas para que cada familia encuentre la opción más cercana. Todas comparten nuestra filosofía y estándares académicos.</p>
        </header>

        <div class="sedes__list">
            <?php foreach ($sedes as $i => $sede) : ?>
                <article class="sede-block<?php echo $i % 2 === 0 ? ' sede-block--left' : ' sede-block--right'; ?>">
                    <div class="sede-block__info">
                        <h3 class="sede-block__name"><?php echo esc_html($sede['name']); ?></h3>
                        <p class="sede-block__address">📍 <?php echo esc_html($sede['address']); ?></p>
                        <p class="sede-block__desc"><?php echo esc_html($sede['desc']); ?></p>
                        <ul class="sede-block__niveles">
                            <?php foreach ($sede['niveles'] as $n) : ?>
                                <li><?php echo esc_html($n['name']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="sede-block__gallery" data-niveles="<?php echo count($sede['niveles']); ?>">
                        <?php foreach ($sede['niveles'] as $n) : ?>
                            <figure class="sede-block__photo">
                                <div class="card-image">
                                    <img src="<?php echo esc_url($n['image']); ?>" alt="<?php echo esc_attr($sede['name'] . ' – ' . $n['name']); ?>" loading="lazy">
                                </div>
                                <figcaption><?php echo esc_html($n['name']); ?></figcaption>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
