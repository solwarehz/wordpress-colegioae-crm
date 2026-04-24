<?php
/**
 * inc/customizer/controls/class-multicheck.php
 *
 * Control del Customizer: lista de checkboxes (multi-select).
 * Almacena los valores marcados como string coma-separado.
 * Las opciones se pasan vía $choices = ['slug' => 'Label'].
 */

defined('ABSPATH') || exit;

if (!class_exists('Colegio_AE_Multicheck_Control')) {
    class Colegio_AE_Multicheck_Control extends WP_Customize_Control {

        public $type = 'colegio_ae_multicheck';

        public function render_content() {
            if (empty($this->choices) || !is_array($this->choices)) {
                ?>
                <p class="customize-control-description">
                    <?php esc_html_e('No hay opciones disponibles.', 'colegio-ae'); ?>
                </p>
                <?php
                return;
            }

            $current = (string) $this->value();
            $selected = array_filter(array_map('trim', explode(',', $current)));
            $input_id = '_customize-input-' . $this->id;
            ?>
            <div class="colegio-ae-multicheck">
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
                <?php endif; ?>

                <ul class="colegio-ae-multicheck__list">
                    <?php foreach ($this->choices as $slug => $label) :
                        $checked = in_array($slug, $selected, true);
                    ?>
                        <li>
                            <label>
                                <input
                                    type="checkbox"
                                    class="colegio-ae-multicheck__checkbox"
                                    value="<?php echo esc_attr($slug); ?>"
                                    <?php checked($checked); ?>
                                >
                                <?php echo esc_html($label); ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <input
                    type="hidden"
                    class="colegio-ae-multicheck__input"
                    id="<?php echo esc_attr($input_id); ?>"
                    <?php $this->link(); ?>
                    value="<?php echo esc_attr($current); ?>"
                >
            </div>
            <?php
        }
    }
}
