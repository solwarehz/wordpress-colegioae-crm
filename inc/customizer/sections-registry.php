<?php
/**
 * inc/customizer/sections-registry.php
 *
 * Registro central de las secciones del home.
 * Cada sección tiene: label, template part, estado default.
 * Extender con: add_filter('colegio_ae_sections', ...).
 */

defined('ABSPATH') || exit;

function colegio_ae_get_sections() {
    return apply_filters('colegio_ae_sections', [
        'hero' => [
            'label'           => __('Hero', 'colegio-ae'),
            'template'        => 'home/hero',
            'default_enabled' => true,
            'anchor_default'  => 'inicio',
        ],
        'nosotros' => [
            'label'           => __('Nosotros / Conócenos', 'colegio-ae'),
            'template'        => 'home/nosotros',
            'default_enabled' => true,
            'anchor_default'  => 'nosotros',
        ],
        'valores' => [
            'label'           => __('Valores', 'colegio-ae'),
            'template'        => 'home/valores',
            'default_enabled' => true,
            'anchor_default'  => 'valores',
        ],
        'servicios' => [
            'label'           => __('Niveles educativos', 'colegio-ae'),
            'template'        => 'home/servicios',
            'default_enabled' => true,
            'anchor_default'  => 'servicios',
        ],
        'sedes' => [
            'label'           => __('Sedes', 'colegio-ae'),
            'template'        => 'home/sedes',
            'default_enabled' => true,
            'anchor_default'  => 'sedes',
        ],
        'profesores' => [
            'label'           => __('Profesores', 'colegio-ae'),
            'template'        => 'home/profesores',
            'default_enabled' => true,
            'anchor_default'  => 'profesores',
        ],
        'mentalidad' => [
            'label'           => __('Mentalidad ganadora', 'colegio-ae'),
            'template'        => 'home/mentalidad-ganadora',
            'default_enabled' => true,
            'anchor_default'  => 'mentalidad-ganadora',
        ],
        'resenas' => [
            'label'           => __('Reseñas', 'colegio-ae'),
            'template'        => 'home/opiniones',
            'default_enabled' => true,
            'anchor_default'  => 'resenas',
        ],
        'contacto' => [
            'label'           => __('Contáctanos', 'colegio-ae'),
            'template'        => 'home/contacto',
            'default_enabled' => true,
            'anchor_default'  => 'contacto',
        ],
    ]);
}
