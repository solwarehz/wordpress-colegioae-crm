<?php
/**
 * inc/customizer/controls/class-sortable.php
 *
 * Control del Customizer: lista arrastrable (jQuery UI Sortable — core de WP).
 * Guarda los slugs en un string coma-separado. El JS sincroniza el orden al
 * reordenar, disparando change para que el Customizer detecte la modificación.
 */

defined('ABSPATH') || exit;

if (!class_exists('Colegio_AE_Sortable_Control')) {
    class Colegio_AE_Sortable_Control extends WP_Customize_Control {

        public $type = 'colegio_ae_sortable';

        /**
         * Items disponibles: ['slug' => 'Label']
         */
        public $items = [];

        public function enqueue() {
            wp_enqueue_script('jquery-ui-sortable');
        }

        public function render_content() {
            if (empty($this->items) || !is_array($this->items)) {
                return;
            }

            $value       = (string) $this->value();
            $saved_order = array_filter(array_map('trim', explode(',', $value)));

            // Construir lista en el orden guardado; apendar items faltantes.
            $ordered = [];
            foreach ($saved_order as $slug) {
                if (isset($this->items[$slug])) {
                    $ordered[$slug] = $this->items[$slug];
                }
            }
            foreach ($this->items as $slug => $label) {
                if (!isset($ordered[$slug])) {
                    $ordered[$slug] = $label;
                }
            }
            ?>
            <div class="colegio-ae-sortable">
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
                <?php endif; ?>

                <ul class="colegio-ae-sortable__list">
                    <?php foreach ($ordered as $slug => $label) : ?>
                        <li class="colegio-ae-sortable__item" data-slug="<?php echo esc_attr($slug); ?>">
                            <span class="colegio-ae-sortable__handle" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="5" r="1"/>
                                    <circle cx="9" cy="12" r="1"/>
                                    <circle cx="9" cy="19" r="1"/>
                                    <circle cx="15" cy="5" r="1"/>
                                    <circle cx="15" cy="12" r="1"/>
                                    <circle cx="15" cy="19" r="1"/>
                                </svg>
                            </span>
                            <span class="colegio-ae-sortable__label"><?php echo esc_html($label); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <input
                    type="hidden"
                    class="colegio-ae-sortable__input"
                    <?php $this->link(); ?>
                    value="<?php echo esc_attr(implode(',', array_keys($ordered))); ?>"
                >
            </div>
            <?php
        }
    }
}
