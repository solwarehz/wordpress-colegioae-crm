<?php
/**
 * inc/customizer/controls/class-eye-toggle.php
 *
 * Control del Customizer: botón con ícono de ojo para mostrar/ocultar una sección.
 * Un solo clic alterna el estado. El valor se persiste como boolean en un
 * setting del tipo checkbox. El ojo es solo UI — el estado lo maneja WP.
 */

defined('ABSPATH') || exit;

if (!class_exists('Colegio_AE_Eye_Toggle_Control')) {
    class Colegio_AE_Eye_Toggle_Control extends WP_Customize_Control {

        public $type = 'colegio_ae_eye_toggle';

        public function render_content() {
            $value = (int) $this->value();
            $input_id = '_customize-input-' . $this->id;
            ?>
            <div class="colegio-ae-eye-toggle" data-state="<?php echo $value ? 'on' : 'off'; ?>">
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>

                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
                <?php endif; ?>

                <label class="colegio-ae-eye-toggle__wrap" for="<?php echo esc_attr($input_id); ?>">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr($input_id); ?>"
                        class="colegio-ae-eye-toggle__input"
                        <?php $this->link(); ?>
                        <?php checked($value, 1); ?>
                        value="1"
                    >
                    <span class="colegio-ae-eye-toggle__btn" aria-hidden="true">
                        <svg class="colegio-ae-eye-toggle__icon colegio-ae-eye-toggle__icon--on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="colegio-ae-eye-toggle__icon colegio-ae-eye-toggle__icon--off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </span>
                    <span class="colegio-ae-eye-toggle__label">
                        <span class="colegio-ae-eye-toggle__label-on"><?php esc_html_e('Visible', 'colegio-ae'); ?></span>
                        <span class="colegio-ae-eye-toggle__label-off"><?php esc_html_e('Oculta', 'colegio-ae'); ?></span>
                    </span>
                </label>
            </div>
            <?php
        }
    }
}
