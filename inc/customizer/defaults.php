<?php
/**
 * inc/customizer/defaults.php
 *
 * Defaults centralizados — fuente única de verdad para tanto los paneles
 * del Customizer como los templates que los renderizan en frontend.
 *
 * Razón: get_theme_mod() en frontend no lee los defaults registrados por
 * WP_Customize_Setting (esos solo aplican dentro del Customizer). Por eso
 * tanto panel como template referencian este archivo.
 */

defined('ABSPATH') || exit;

function colegio_ae_defaults() {
    static $cache = null;
    if ($cache !== null) return $cache;

    $cache = [

        /* -------- Hero slides -------- */
        'hero_slides' => [
            1 => [
                'image'    => 'https://picsum.photos/seed/ae-hero-1/1920/1080',
                'title'    => 'Formamos líderes del mañana',
                'subtitle' => 'Con pensamiento crítico, valores sólidos y visión global',
                'cta_text' => 'Agenda una visita',
                'cta_url'  => '#contacto',
            ],
            2 => [
                'image'    => 'https://picsum.photos/seed/ae-hero-2/1920/1080',
                'title'    => 'Educación que transforma',
                'subtitle' => 'Donde cada niño descubre su potencial y aprende a confiar en él',
                'cta_text' => 'Conoce nuestra propuesta',
                'cta_url'  => '#nosotros',
            ],
            3 => [
                'image'    => 'https://picsum.photos/seed/ae-hero-3/1920/1080',
                'title'    => 'Más que un colegio, una familia',
                'subtitle' => 'Compromiso, respeto y excelencia que acompañan a tu hijo cada día',
                'cta_text' => 'Escríbenos',
                'cta_url'  => '#contacto',
            ],
            4 => ['image' => '', 'title' => '', 'subtitle' => '', 'cta_text' => '', 'cta_url' => ''],
            5 => ['image' => '', 'title' => '', 'subtitle' => '', 'cta_text' => '', 'cta_url' => ''],
        ],

        /* -------- Valores -------- */
        'valores' => [
            1 => ['name' => 'Compromiso',    'icon' => 'handshake', 'desc' => 'Trabajamos cada día con la convicción de que cada estudiante merece nuestra mejor versión. El compromiso no es una promesa — es nuestra forma de enseñar.'],
            2 => ['name' => 'Humanidad',     'icon' => 'heart',     'desc' => 'Detrás de cada estudiante hay una familia, una historia y sueños únicos. Educamos con empatía, escuchando y acompañando en cada etapa.'],
            3 => ['name' => 'Liderazgo',     'icon' => 'star',      'desc' => 'Formamos estudiantes que no siguen, sino que proponen. Que no repiten, sino que crean. Que asumen la responsabilidad de transformar su entorno.'],
            4 => ['name' => 'Excelencia',    'icon' => 'award',     'desc' => 'No nos conformamos con lo bueno cuando podemos alcanzar lo mejor. La excelencia académica y humana es el estándar al que aspiramos cada día.'],
            5 => ['name' => 'Adaptabilidad', 'icon' => 'refresh',   'desc' => 'Vivimos en un mundo que cambia rápido. Enseñamos a nuestros estudiantes a aprender siempre, a desaprender cuando toque y a crecer en cualquier escenario.'],
            6 => ['name' => 'Respeto',       'icon' => 'users',     'desc' => 'Respetamos la diversidad de pensamiento, la individualidad de cada alumno y la cultura de cada familia. El respeto mutuo es la base de nuestra comunidad.'],
        ],

        /* -------- Niveles educativos -------- */
        'niveles' => [
            1 => ['name' => 'Inicial', 'subtitle' => '3 a 5 años', 'desc' => 'Los primeros años marcan el resto de la vida escolar. En nuestro nivel Inicial, los niños aprenden a través del juego, el movimiento y la exploración, en un ambiente seguro que estimula su curiosidad y desarrolla sus habilidades sociales, emocionales y cognitivas.', 'image' => 'https://picsum.photos/seed/ae-servicios-inicial/800/600', 'link' => ''],
            2 => ['name' => 'Primaria', 'subtitle' => '1° a 6° grado', 'desc' => 'Construimos las bases del pensamiento crítico. Nuestros estudiantes no memorizan: comprenden, cuestionan y aplican. Desarrollamos hábitos de estudio, lectura comprensiva, razonamiento matemático y una sólida formación en valores.', 'image' => 'https://picsum.photos/seed/ae-servicios-primaria/800/600', 'link' => ''],
            3 => ['name' => 'Secundaria', 'subtitle' => '1° a 5° año', 'desc' => 'Preparamos jóvenes listos para la universidad y para la vida. Formación académica rigurosa, orientación vocacional, proyectos de liderazgo y participación en concursos que los retan a dar lo mejor de sí.', 'image' => 'https://picsum.photos/seed/ae-servicios-secundaria/800/600', 'link' => ''],
        ],

        /* -------- Sedes -------- */
        'sedes' => [
            1 => [
                'name' => 'Sede Principal', 'address' => 'Jr. Principal 123, Huaraz',
                'desc' => 'Nuestra sede principal alberga los tres niveles educativos. Aulas cómodas, espacios para el juego y el deporte, biblioteca y laboratorio, pensados para que cada estudiante tenga las condiciones adecuadas para aprender y desarrollarse.',
                'inicial' => 1, 'primaria' => 1, 'secundaria' => 1,
                'foto_inicial' => 'https://picsum.photos/seed/ae-sede1-inicial/900/600',
                'foto_primaria' => 'https://picsum.photos/seed/ae-sede1-primaria/900/600',
                'foto_secundaria' => 'https://picsum.photos/seed/ae-sede1-secundaria/900/600',
            ],
            2 => [
                'name' => 'Sede 2', 'address' => 'Av. Secundaria 456, Huaraz',
                'desc' => 'En nuestra segunda sede acompañamos a los estudiantes de Inicial y Primaria en un espacio diseñado especialmente para ellos, con ambientes de escala humana y la cercanía que los niños necesitan en sus primeros años escolares.',
                'inicial' => 1, 'primaria' => 1, 'secundaria' => 0,
                'foto_inicial' => 'https://picsum.photos/seed/ae-sede2-inicial/900/600',
                'foto_primaria' => 'https://picsum.photos/seed/ae-sede2-primaria/900/600',
                'foto_secundaria' => '',
            ],
            3 => [
                'name' => 'Sede 3', 'address' => 'Jr. Tercera 789, Huaraz',
                'desc' => 'Nuestra tercera sede ofrece un espacio dedicado con aulas pensadas para el trabajo en equipo, la investigación y la preparación pre-universitaria.',
                'inicial' => 0, 'primaria' => 0, 'secundaria' => 1,
                'foto_inicial' => '',
                'foto_primaria' => '',
                'foto_secundaria' => 'https://picsum.photos/seed/ae-sede3-secundaria/900/600',
            ],
        ],

        /* -------- Reseñas -------- */
        'resenas' => [
            1 => [
                'photo' => 'https://picsum.photos/seed/ae-testimonio-1/300/300',
                'name' => 'María Luisa Campos',
                'relation' => 'Madre de familia · 2 hijos en el colegio',
                'text' => 'Desde que mis hijos están en Albert Einstein, no solo mejoraron académicamente — se volvieron más seguros de sí mismos. Lo que más valoro es que los profesores conocen a cada uno por su nombre, por su historia. Eso no tiene precio.',
                'rating' => 5,
            ],
            2 => [
                'photo' => 'https://picsum.photos/seed/ae-testimonio-2/300/300',
                'name' => 'Javier Sánchez',
                'relation' => 'Padre de familia · hija en 4° de secundaria',
                'text' => 'Mi hija entró en 1° de secundaria con dificultades en matemáticas. Hoy está por postular a la universidad y es una de las mejores de su salón. Más allá de la mejora académica, veo a una joven líder que sabe lo que quiere.',
                'rating' => 5,
            ],
            3 => [
                'photo' => 'https://picsum.photos/seed/ae-testimonio-3/300/300',
                'name' => 'Lucía Ramírez',
                'relation' => 'Madre de familia · hijo en primaria',
                'text' => 'Elegí este colegio por la infraestructura, me quedé por los docentes. La comunicación con la institución es constante, se nota el compromiso real con cada estudiante. Mi hijo llega feliz todos los días.',
                'rating' => 5,
            ],
        ],

        /* -------- Documentos (página) -------- */
        'documentos' => [
            1  => ['enabled' => 1, 'title' => 'Reglamento Interno 2026',  'desc' => 'Normas de convivencia, derechos y deberes.',         'file' => 0],
            2  => ['enabled' => 1, 'title' => 'Calendario Académico',     'desc' => 'Fechas clave del año escolar.',                       'file' => 0],
            3  => ['enabled' => 1, 'title' => 'Cronograma de Matrículas', 'desc' => 'Fechas y requisitos para nueva inscripción.',        'file' => 0],
            4  => ['enabled' => 1, 'title' => 'Ideario Institucional',    'desc' => 'Misión, visión y principios del colegio.',           'file' => 0],
            5  => ['enabled' => 1, 'title' => 'Plan de Estudios',         'desc' => 'Contenidos curriculares por nivel.',                  'file' => 0],
            6  => ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0],
            7  => ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0],
            8  => ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0],
            9  => ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0],
            10 => ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0],
        ],

        /* -------- Textos simples -------- */
        'nosotros_title' => 'Conoce el Colegio Albert Einstein',
        'nosotros_p1'    => 'En Huaraz formamos estudiantes líderes con pensamiento crítico, valores sólidos y visión global, capaces de transformar su entorno con compromiso, innovación y excelencia académica.',
        'nosotros_p2'    => 'Creemos que la educación no es solo transmitir conocimiento: es acompañar a cada niño y joven en el descubrimiento de quién es, qué lo apasiona y cómo puede aportar al mundo. Por eso trabajamos con la convicción de que una educación de calidad empieza por la cercanía — conocer a cada familia, entender a cada estudiante y ofrecerle un espacio seguro donde atreverse a crecer.',
        'nosotros_video_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
        'nosotros_video_title' => 'Video institucional Colegio Albert Einstein',

        'valores_title'    => 'Nuestros valores',
        'valores_subtitle' => 'Los 6 pilares que sostienen nuestra forma de educar.',

        'servicios_title'    => 'Niveles educativos',
        'servicios_subtitle' => 'Acompañamos a tu hijo desde los primeros años hasta la universidad.',

        'sedes_title' => 'Nuestras sedes',
        'sedes_intro' => 'Contamos con 3 sedes en Huaraz, pensadas para que cada familia encuentre la opción más cercana. Todas comparten nuestra filosofía y estándares académicos.',

        'profesores_title'     => 'Nuestros profesores',
        'profesores_subtitle'  => 'Profesionales apasionados por enseñar, en formación constante, que creen que enseñar es acompañar a descubrir.',
        'profesores_btn_text'  => 'Ver todos los profesores',
        'profesores_btn_url'   => '/profesores/',

        'mentalidad_title'    => 'Mentalidad ganadora',
        'mentalidad_subtitle' => 'Porque formar líderes es también formar carácter.',
        'mentalidad_intro'    => 'En los concursos en los que participamos, queremos ganar. Si no ganamos, damos pelea. No nos rendimos y seguimos preparándonos. Porque más que los premios, lo que nos importa es que nuestros estudiantes desarrollen la disciplina, la confianza y la resiliencia que los acompañarán toda la vida.',

        'resenas_title'    => 'Reseñas de nuestras familias',
        'resenas_subtitle' => 'Lo que cuentan los padres, madres y alumnos que forman parte del Colegio Albert Einstein.',

        'contacto_title'    => 'Escríbenos',
        'contacto_subtitle' => 'Estamos aquí para resolver tus dudas y acompañarte en la decisión educativa más importante.',
        'contacto_intro'    => 'Déjanos tus datos y nos pondremos en contacto contigo en menos de 24 horas. También puedes escribirnos directamente por WhatsApp al número visible en la parte inferior derecha.',
    ];

    return $cache;
}

/**
 * Helper: lee un theme_mod con default desde el catálogo central.
 */
function colegio_ae_mod($key, $default = null) {
    if ($default === null) {
        $defaults = colegio_ae_defaults();
        $default = $defaults[$key] ?? '';
    }
    return get_theme_mod('colegio_ae_' . $key, $default);
}
