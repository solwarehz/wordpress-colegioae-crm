<?php
/**
 * inc/headers.php
 *
 * Sprint S5 — Hardening de servidor:
 *   1) Headers HTTP de seguridad (anti-clickjacking, anti-MIME-sniffing,
 *      Referrer-Policy, Permissions-Policy, HSTS en HTTPS).
 *   2) Cache-Control para HTML público anónimo (max-age 5 min). Activa
 *      el page cache cuando hay un cache plugin/servidor disponible
 *      (LiteSpeed Cache de Hostinger, CDN, etc.) y mejora el browser
 *      cache para visitantes recurrentes.
 *
 * Por qué NO hay Content-Security-Policy aquí:
 *   WordPress core + plugins inyectan scripts y estilos inline en
 *   wp_head/wp_footer (admin bar, gutenberg, formularios, etc). Un
 *   CSP estricto rompería el sitio sin aportar mucha defensa adicional.
 *   Si en el futuro se quiere implementar, empezar con un
 *   `Content-Security-Policy-Report-Only` permisivo y endurecer
 *   gradualmente.
 */

defined('ABSPATH') || exit;

/* ============================================================================
   1) HEADERS DE SEGURIDAD
   ============================================================================ */

add_action('send_headers', 'colegio_ae_security_headers');

function colegio_ae_security_headers() {
    if (is_admin()) {
        // No tocar headers en /wp-admin/. Algunos plugins de admin necesitan
        // iframes y políticas más laxas; dejarlos al control de WP/plugin.
        return;
    }

    // Anti-clickjacking: este sitio solo se puede embed desde su propio origen.
    header('X-Frame-Options: SAMEORIGIN');

    // Anti-MIME-sniffing: el navegador respeta el Content-Type declarado y no
    // intenta adivinar el tipo. Mitiga ataques de "polyglot" (un .jpg que
    // realmente es un .html con script, etc).
    header('X-Content-Type-Options: nosniff');

    // Política de referrer: envía el origen completo solo a destinos del
    // mismo origen; para destinos externos solo envía el origen (no el path).
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permisos del navegador: el sitio NO usa geolocation, microphone,
    // camera, ni payment APIs. Los desactivamos explícitamente para que
    // un script comprometido no pueda activarlas en este origen.
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), accelerometer=(), gyroscope=()');

    // HSTS: solo en HTTPS. Le dice al navegador que recuerde por 1 año
    // que este sitio debe accederse vía HTTPS y nunca por HTTP.
    if (is_ssl()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

/* ============================================================================
   2) CACHE-CONTROL PARA HTML PÚBLICO ANÓNIMO
   ============================================================================ */

add_action('send_headers', 'colegio_ae_cache_headers');

function colegio_ae_cache_headers() {
    // No cachear vistas de admin, usuarios logueados, ni páginas dinámicas
    // donde el contenido depende del request específico.
    if (is_admin() || is_user_logged_in()) {
        return;
    }
    if (is_404() || is_search() || is_preview() || is_customize_preview()) {
        return;
    }

    // 5 minutos. Trade-off: si el cliente edita el Customizer y guarda,
    // los visitantes con cache verán la versión vieja por hasta 5 min.
    // Aceptable para un sitio institucional que cambia poco.
    //
    // - max-age:    cache del browser
    // - s-maxage:   cache de proxies/CDN (LiteSpeed, Cloudflare, etc.)
    // - public:     puede cachearse aunque la respuesta tenga otros headers
    header('Cache-Control: public, max-age=300, s-maxage=300');

    // Vary: el cache debe diferenciar respuestas según codificación
    // (gzip vs br vs identity) y según cookie (logged-in usa otra cosa).
    header('Vary: Accept-Encoding, Cookie');
}
