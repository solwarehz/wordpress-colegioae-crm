<?php
/**
 * Sección Nosotros / Conócenos
 */

defined('ABSPATH') || exit;

$video_id = 'ScMzIvxBSi4'; // Placeholder. Reemplazar por video institucional real.
?>

<section id="nosotros" class="section nosotros">
    <div class="container nosotros__container">
        <div class="nosotros__text">
            <h2 class="nosotros__title"><?php esc_html_e('Conoce el Colegio Albert Einstein', 'colegio-ae'); ?></h2>
            <p>
                En Huaraz formamos estudiantes líderes con pensamiento crítico, valores sólidos y visión global, capaces de transformar su entorno con compromiso, innovación y excelencia académica.
            </p>
            <p>
                Creemos que la educación no es solo transmitir conocimiento: es acompañar a cada niño y joven en el descubrimiento de quién es, qué lo apasiona y cómo puede aportar al mundo. Por eso trabajamos con la convicción de que una educación de calidad empieza por la cercanía — conocer a cada familia, entender a cada estudiante y ofrecerle un espacio seguro donde atreverse a crecer.
            </p>
        </div>

        <div class="nosotros__video">
            <div class="nosotros__video-wrapper">
                <iframe
                    src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr($video_id); ?>"
                    title="<?php esc_attr_e('Video institucional Colegio Albert Einstein', 'colegio-ae'); ?>"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                ></iframe>
            </div>
        </div>
    </div>
</section>
