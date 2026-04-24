<?php
/**
 * inc/customizer/panel-orden.php
 *
 * Apariencia → Personalizar → Orden y visibilidad de secciones.
 * - Sortable con las 9 secciones del home.
 * - Dashboard con los 9 eye toggles para mostrar/ocultar rápidamente.
 *
 * Los settings per-sección (colegio_ae_section_{slug}_enabled) se registran
 * acá porque son la fuente de verdad y los paneles per-sección (Sprint 2.1+)
 * van a bindar controles adicionales al mismo setting.
 */

defined('ABSPATH') || exit;

add_action('customize_register', 'colegio_ae_customizer_register_orden');

function colegio_ae_customizer_register_orden(WP_Customize_Manager $wp_customize) {

    $sections = colegio_ae_get_sections();
    $default_order = implode(',', array_keys($sections));

    /* -------- Sección del Customizer -------- */
    $wp_customize->add_section('colegio_ae_orden', [
        'title'       => __('Orden y visibilidad de secciones', 'colegio-ae'),
        'description' => __('Arrastra las secciones para cambiar el orden en que aparecen en el home. Usa los ojos para mostrar u ocultar cada sección del sitio público.', 'colegio-ae'),
        'priority'    => 25,
    ]);

    /* -------- Setting: orden de secciones (string coma-separado) -------- */
    $wp_customize->add_setting('colegio_ae_sections_order', [
        'default'           => $default_order,
        'type'              => 'theme_mod',
        'sanitize_callback' => 'colegio_ae_sanitize_sections_order',
        'capability'        => 'edit_theme_options',
    ]);

    /* -------- Control: sortable -------- */
    $items = [];
    foreach ($sections as $slug => $data) {
        $items[$slug] = $data['label'];
    }

    $wp_customize->add_control(new Colegio_AE_Sortable_Control($wp_customize, 'colegio_ae_sections_order', [
        'label'       => __('Orden en el home', 'colegio-ae'),
        'description' => __('Arrastra para cambiar el orden.', 'colegio-ae'),
        'section'     => 'colegio_ae_orden',
        'items'       => $items,
        'priority'    => 10,
    ]));

    /* -------- 9 settings + 9 controles eye toggle (dashboard) -------- */
    $priority = 20;
    foreach ($sections as $slug => $data) {
        $setting_id = 'colegio_ae_section_' . $slug . '_enabled';
        $default    = isset($data['default_enabled']) ? (bool) $data['default_enabled'] : true;

        $wp_customize->add_setting($setting_id, [
            'default'           => $default ? 1 : 0,
            'type'              => 'theme_mod',
            'sanitize_callback' => 'colegio_ae_sanitize_checkbox',
            'capability'        => 'edit_theme_options',
        ]);

        $wp_customize->add_control(new Colegio_AE_Eye_Toggle_Control($wp_customize, $setting_id, [
            'label'    => $data['label'],
            'section'  => 'colegio_ae_orden',
            'priority' => $priority++,
        ]));
    }
}
