<?php
/**
 * inc/customizer/panel-documentos.php
 *
 * Panel "Página: Documentos".
 * 10 slots fijos, cada uno con: visible (eye toggle), título, descripción,
 * archivo (PDF u otro, desde la biblioteca de Medios).
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_documentos');

function colegio_ae_customizer_register_documentos(WP_Customize_Manager $wp_customize) {

    $wp_customize->add_section('colegio_ae_page_documentos', [
        'title'       => __('Página: Documentos', 'colegio-ae'),
        'description' => __('Lista de documentos institucionales (PDF, etc.) que se muestran en la página Documentos. Hasta 10 documentos. Sube los archivos a la biblioteca de Medios y selecciónalos en cada slot.', 'colegio-ae'),
        'priority'    => 50,
    ]);

    $defaults = colegio_ae_defaults()['documentos'];

    $p = 10;
    for ($i = 1; $i <= 10; $i++) {
        $d = $defaults[$i] ?? ['enabled' => 0, 'title' => '', 'desc' => '', 'file' => 0];

        // Visibilidad (eye toggle)
        $wp_customize->add_setting("colegio_ae_doc_{$i}_enabled", [
            'default'           => $d['enabled'],
            'type'              => 'theme_mod',
            'sanitize_callback' => 'colegio_ae_sanitize_checkbox',
        ]);
        $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, "colegio_ae_doc_{$i}_enabled", [
            'label'    => sprintf(__('Documento %d', 'colegio-ae'), $i),
            'section'  => 'colegio_ae_page_documentos',
            'priority' => $p++,
        ]));

        // Título
        $wp_customize->add_setting("colegio_ae_doc_{$i}_title", [
            'default'           => $d['title'],
            'type'              => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("colegio_ae_doc_{$i}_title", [
            'label'    => sprintf(__('Doc %d — Título', 'colegio-ae'), $i),
            'section'  => 'colegio_ae_page_documentos',
            'type'     => 'text',
            'priority' => $p++,
        ]);

        // Descripción
        $wp_customize->add_setting("colegio_ae_doc_{$i}_desc", [
            'default'           => $d['desc'],
            'type'              => 'theme_mod',
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control("colegio_ae_doc_{$i}_desc", [
            'label'    => sprintf(__('Doc %d — Descripción', 'colegio-ae'), $i),
            'section'  => 'colegio_ae_page_documentos',
            'type'     => 'textarea',
            'priority' => $p++,
        ]);

        // Archivo (Media — guarda el attachment ID)
        $wp_customize->add_setting("colegio_ae_doc_{$i}_file", [
            'default'           => $d['file'],
            'type'              => 'theme_mod',
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "colegio_ae_doc_{$i}_file", [
            'label'       => sprintf(__('Doc %d — Archivo', 'colegio-ae'), $i),
            'description' => __('Selecciona desde la biblioteca de Medios o sube uno nuevo. Recomendado: PDF.', 'colegio-ae'),
            'section'     => 'colegio_ae_page_documentos',
            'mime_type'   => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png',
            'priority'    => $p++,
            'button_labels' => [
                'select'       => __('Seleccionar archivo', 'colegio-ae'),
                'change'       => __('Cambiar archivo', 'colegio-ae'),
                'remove'       => __('Quitar', 'colegio-ae'),
                'placeholder'  => __('Sin archivo seleccionado', 'colegio-ae'),
            ],
        ]));
    }
}
