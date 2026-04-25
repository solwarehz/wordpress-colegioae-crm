<?php
/**
 * inc/seo.php
 *
 * SEO técnico nativo del tema (sin plugin externo). Emite:
 *   1) <meta name="description"> editable por página.
 *   2) Open Graph + Twitter Cards (con imagen 1200x630 auto-cropeada).
 *   3) <link rel="canonical"> en archives, búsquedas y feeds (las páginas
 *      singulares ya lo reciben de WordPress core).
 *
 * Por decisión del cliente NO se emite JSON-LD con datos institucionales
 * (email, teléfono, dirección física). Si en el futuro se requiere
 * EducationalOrganization, se añade aquí.
 */

defined('ABSPATH') || exit;

/* ============================================================================
   1) METABOX EN EL EDITOR — Title override + Description por página/post
   ============================================================================ */

add_action('add_meta_boxes', function () {
    foreach (['post', 'page'] as $screen) {
        add_meta_box(
            'colegio_ae_seo_box',
            __('SEO — buscadores y redes sociales', 'colegio-ae'),
            'colegio_ae_render_seo_metabox',
            $screen,
            'normal',
            'default'
        );
    }
});

function colegio_ae_render_seo_metabox($post) {
    wp_nonce_field('colegio_ae_seo_save', 'colegio_ae_seo_nonce');
    $title = (string) get_post_meta($post->ID, '_ae_seo_title', true);
    $desc  = (string) get_post_meta($post->ID, '_ae_seo_description', true);
    ?>
    <p>
        <label for="ae_seo_title" style="display:block;font-weight:600;margin-bottom:4px;">
            <?php esc_html_e('Título SEO (opcional)', 'colegio-ae'); ?>
        </label>
        <input type="text" id="ae_seo_title" name="ae_seo_title"
               value="<?php echo esc_attr($title); ?>"
               style="width:100%;" maxlength="70"
               placeholder="<?php echo esc_attr(get_the_title($post)); ?>">
        <span class="description">
            <?php esc_html_e('Si lo dejas vacío, se usa el título de la página. Ideal: hasta 60 caracteres.', 'colegio-ae'); ?>
        </span>
    </p>
    <p style="margin-top:16px;">
        <label for="ae_seo_description" style="display:block;font-weight:600;margin-bottom:4px;">
            <?php esc_html_e('Descripción SEO (opcional)', 'colegio-ae'); ?>
        </label>
        <textarea id="ae_seo_description" name="ae_seo_description"
                  rows="3" style="width:100%;" maxlength="200"><?php echo esc_textarea($desc); ?></textarea>
        <span class="description">
            <?php esc_html_e('Texto que aparece debajo del título en Google y al compartir en WhatsApp/Facebook. Si lo dejas vacío, se usa el extracto del post o las primeras líneas del contenido. Ideal: entre 120 y 160 caracteres.', 'colegio-ae'); ?>
        </span>
    </p>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['colegio_ae_seo_nonce']) ||
        !wp_verify_nonce($_POST['colegio_ae_seo_nonce'], 'colegio_ae_seo_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id))    return;

    if (isset($_POST['ae_seo_title'])) {
        update_post_meta($post_id, '_ae_seo_title',
            sanitize_text_field(wp_unslash($_POST['ae_seo_title'])));
    }
    if (isset($_POST['ae_seo_description'])) {
        update_post_meta($post_id, '_ae_seo_description',
            sanitize_textarea_field(wp_unslash($_POST['ae_seo_description'])));
    }
});

/* ============================================================================
   2) HELPERS — resolver el title, description e imagen según contexto
   ============================================================================ */

/**
 * Title que se usa para <meta og:title> y <meta twitter:title>.
 * Prioridad: post_meta override → get_the_title() / archive title / site name.
 */
function colegio_ae_seo_title() {
    // is_front_page() debe evaluarse antes que is_singular(), porque cuando
    // la home es una página estática asignada en Ajustes → Lectura, ambas
    // condiciones son verdaderas y queremos el nombre del colegio, no
    // "Inicio".
    if (is_front_page()) {
        return get_bloginfo('name', 'display');
    }
    if (is_home()) {
        return __('Blog', 'colegio-ae') . ' – ' . get_bloginfo('name', 'display');
    }
    if (is_singular()) {
        $override = (string) get_post_meta(get_the_ID(), '_ae_seo_title', true);
        if ($override !== '') return $override;
        return get_the_title() . ' – ' . get_bloginfo('name', 'display');
    }
    if (is_category() || is_tag() || is_tax()) {
        return single_term_title('', false) . ' – ' . get_bloginfo('name', 'display');
    }
    if (is_author()) {
        return get_the_author() . ' – ' . get_bloginfo('name', 'display');
    }
    if (is_search()) {
        return sprintf(__('Resultados para "%s"', 'colegio-ae'), get_search_query()) . ' – ' . get_bloginfo('name', 'display');
    }
    return wp_get_document_title();
}

/**
 * Description que se usa para <meta description>, <meta og:description>
 * y <meta twitter:description>.
 * Prioridad: post_meta → excerpt → primeras palabras del content → setting global.
 */
function colegio_ae_seo_description() {
    $fallback = (string) get_theme_mod(
        'colegio_ae_seo_site_description',
        get_bloginfo('description', 'display')
    );

    // is_front_page() / is_home() (blog) usan siempre el fallback global,
    // aunque técnicamente sean páginas estáticas (is_singular() == true).
    if (is_singular() && !is_front_page() && !is_home()) {
        $override = (string) get_post_meta(get_the_ID(), '_ae_seo_description', true);
        if ($override !== '') return $override;

        $excerpt = (string) get_the_excerpt();
        if ($excerpt !== '') {
            return wp_trim_words($excerpt, 30, '…');
        }
        $content = (string) get_post_field('post_content', get_the_ID());
        $stripped = trim(wp_strip_all_tags(strip_shortcodes($content)));
        if ($stripped !== '') {
            return wp_trim_words($stripped, 30, '…');
        }
    }
    return $fallback;
}

/**
 * URL de la imagen Open Graph (tamaño cropeado 1200x630).
 * Prioridad:
 *   - Singular con featured image → ese attachment en tamaño 'ae-og'.
 *   - Setting global "Imagen social" → ese attachment en tamaño 'ae-og'.
 *   - Logo del header → ese attachment en tamaño 'ae-og'.
 *   - '' (vacío) → no se emite el tag og:image.
 */
function colegio_ae_seo_image_url() {
    // 1) Featured image del post/página actual
    if (is_singular() && has_post_thumbnail()) {
        $id = get_post_thumbnail_id();
        $src = wp_get_attachment_image_src($id, 'ae-og');
        if ($src) return $src[0];
    }

    // 2) Setting global del Customizer
    $social_id = (int) get_theme_mod('colegio_ae_seo_social_image', 0);
    if ($social_id > 0) {
        $src = wp_get_attachment_image_src($social_id, 'ae-og');
        if ($src) return $src[0];
    }

    // 3) Logo del header como último recurso
    $logo_id = (int) get_theme_mod('custom_logo', 0);
    if ($logo_id > 0) {
        $src = wp_get_attachment_image_src($logo_id, 'ae-og');
        if ($src) return $src[0];
    }

    return '';
}

/**
 * URL canónica del documento actual.
 */
function colegio_ae_seo_canonical_url() {
    if (is_front_page()) {
        return home_url('/');
    }
    if (is_home()) {
        return get_permalink((int) get_option('page_for_posts'));
    }
    if (is_singular()) {
        return get_permalink();
    }
    if (is_category()) return get_category_link(get_queried_object_id());
    if (is_tag())      return get_tag_link(get_queried_object_id());
    if (is_tax())      return get_term_link(get_queried_object());
    if (is_author())   return get_author_posts_url(get_queried_object_id());
    if (is_year())     return get_year_link(get_query_var('year'));
    if (is_month())    return get_month_link(get_query_var('year'), get_query_var('monthnum'));
    if (is_day())      return get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
    if (is_search())   return home_url('/?s=' . urlencode(get_search_query()));

    return '';
}

/* ============================================================================
   3) EMISIÓN — un solo hook a wp_head con todos los tags
   ============================================================================ */

add_action('wp_head', 'colegio_ae_seo_output_tags', 5);

function colegio_ae_seo_output_tags() {
    $title       = colegio_ae_seo_title();
    $description = colegio_ae_seo_description();
    $image       = colegio_ae_seo_image_url();
    $canonical   = colegio_ae_seo_canonical_url();
    $site_name   = get_bloginfo('name', 'display');
    $locale      = get_locale(); // ej. 'es_PE' tras configurar idioma

    // Type: article para singulares de blog, website para todo lo demás.
    $og_type = is_singular('post') ? 'article' : 'website';

    echo "\n<!-- SEO (colegio-ae theme) -->\n";

    // Meta description estándar
    if ($description !== '') {
        printf('<meta name="description" content="%s">' . "\n", esc_attr($description));
    }

    // Canonical para archives/búsquedas/home (los singulares lo reciben de
    // rel_canonical en WP core, no duplicar).
    if ($canonical !== '' && !is_singular()) {
        printf('<link rel="canonical" href="%s">' . "\n", esc_url($canonical));
    }

    // Open Graph
    printf('<meta property="og:title" content="%s">' . "\n",       esc_attr($title));
    if ($description !== '') {
        printf('<meta property="og:description" content="%s">' . "\n", esc_attr($description));
    }
    printf('<meta property="og:type" content="%s">' . "\n",        esc_attr($og_type));
    printf('<meta property="og:site_name" content="%s">' . "\n",   esc_attr($site_name));
    printf('<meta property="og:locale" content="%s">' . "\n",      esc_attr($locale));
    if ($canonical !== '') {
        printf('<meta property="og:url" content="%s">' . "\n", esc_url($canonical));
    }
    if ($image !== '') {
        printf('<meta property="og:image" content="%s">' . "\n", esc_url($image));
        echo  '<meta property="og:image:width" content="1200">' . "\n";
        echo  '<meta property="og:image:height" content="630">' . "\n";
    }

    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    printf('<meta name="twitter:title" content="%s">' . "\n", esc_attr($title));
    if ($description !== '') {
        printf('<meta name="twitter:description" content="%s">' . "\n", esc_attr($description));
    }
    if ($image !== '') {
        printf('<meta name="twitter:image" content="%s">' . "\n", esc_url($image));
    }

    echo "<!-- /SEO -->\n";
}
