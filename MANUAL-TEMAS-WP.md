# MANUAL-TEMAS-WP — Lecciones aprendidas para temas WordPress

> Guía operativa derivada del trabajo real en el tema `colegio-ae` (CRM Solware).
> Aplica a cualquier tema WordPress custom futuro. Cada sección lista patrones a aplicar y anti-patrones a evitar.

---

## 1. ARQUITECTURA Y PORTABILIDAD

- **Nunca hardcodear el nombre de la carpeta del tema.** Hostinger renombra automáticamente carpetas (en este proyecto `colegio-ae` → `solware`). Usar `get_template_directory()`, `get_template_directory_uri()`, `get_theme_file_path()`, `get_theme_file_uri()`.
- Definir constantes globales `XXX_DIR` y `XXX_URI` en `functions.php`:
  ```php
  define('XXX_DIR', get_template_directory());
  define('XXX_URI', get_template_directory_uri());
  ```
  Usarlas para `require_once` de includes que SIEMPRE están en el padre.
- Para assets que un child theme podría override (CSS, JS, SVGs, imágenes), usar `get_theme_file_path()` / `get_theme_file_uri()` directamente. Estas funciones buscan en child primero y caen al padre.
- Defaults del Customizer en archivo central (`inc/customizer/defaults.php`) — fuente única de verdad. `get_theme_mod()` no lee defaults registrados en el panel cuando se llama desde frontend; por eso el centralizado.
- Bumpear `THEME_VERSION` en cada cambio de assets. WordPress lo agrega como `?ver=` y rompe cache de browser.

---

## 2. SEGURIDAD

### Sanitización
- TODO `add_setting` del Customizer DEBE tener `sanitize_callback` apropiado:
  - text → `sanitize_text_field`
  - textarea → `sanitize_textarea_field`
  - color → `sanitize_hex_color`
  - URL → `esc_url_raw`
  - número/ID → `absint`
  - bool/checkbox → `(int) (bool) $v`
  - select → allowlist propia con `in_array()`
- Escape en frontend SIEMPRE: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`. Nada de `echo $foo` crudo.
- Embed HTML pegado por usuario → `wp_kses` con allowlist específica de tags + atributos, después de pre-validar URLs por regex de host permitido.

### Nonces y capabilities
- Metaboxes custom: `wp_nonce_field()` al render, `wp_verify_nonce()` + `current_user_can('edit_post', $post_id)` + guard `DOING_AUTOSAVE` al guardar.
- Settings sensibles del Customizer: `'capability' => 'edit_theme_options'`.

### Headers HTTP
- En `send_headers` action, emitir:
  - `X-Frame-Options: SAMEORIGIN`
  - `X-Content-Type-Options: nosniff`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), accelerometer=(), gyroscope=()`
  - `Strict-Transport-Security: max-age=31536000; includeSubDomains` (solo `is_ssl()`)
- **No emitir CSP** sin tiempo de configurarla bien. WP core y plugins inyectan inline scripts/styles; CSP estricto rompe el sitio. Si se quiere, empezar con `Content-Security-Policy-Report-Only` permisivo.
- Skip headers en admin y para usuarios logueados.

### Anti-patrón: seguridad sobre-restrictiva
- **No bloquear embeds legítimos por sobre-restricción.** Caso Tally: el sanitizer del proyecto inicialmente bloqueaba `<script>` sin `src` que apuntara a tally.so, pero el cliente pegó solo el iframe (modo "iframe only" sin script) y nada se renderizaba. Solución: detectar `data-tally-src` en el embed y auto-inyectar el script de Tally desde el tema (controlado por nosotros, hardcoded a `tally.so`).
- Validar **host** del recurso externo, no bloquear por defecto. Una allowlist de hosts (regex) + `wp_kses` final es suficiente para iframes/scripts de servicios conocidos.

### Comentarios
Si el tema no los usa, bloquearlos en capas vía include `inc/disable-comments.php`:
- `comments_open`, `pings_open` → `__return_false`
- `comments_array` → `__return_empty_array` (oculta los existentes)
- `rest_endpoints` → unset `/wp/v2/comments`
- `pre_comment_on_post` → `wp_die(403)` para frenar POST directos
- `template_redirect` con `is_comment_feed()` → 403
- `remove_post_type_support($pt, 'comments')` para todos los públicos
- `admin_menu` → `remove_menu_page('edit-comments.php')`, redirigir directos
- Quitar `comments` del admin bar y dashboard widget

Defensa adicional: `.htaccess` en raíz con `<Files "wp-comments-post.php"> Require all denied </Files>` (rechaza antes de que arranque PHP, sobrevive a cambios de tema).

---

## 3. SEO

- Pipeline propio en `inc/seo.php` enganchado a `wp_head` con prioridad 5. Más liviano y predecible que Rank Math/Yoast para sitios institucionales.
- Metabox por página/post con campos: Title override + Description. Guardar como `post_meta` (`_xxx_seo_title`, `_xxx_seo_description`).
- Helpers que resuelven el title/description según contexto:
  - **`is_front_page()` antes de `is_singular()`.** Si la home es página estática asignada, ambas son true; queremos el nombre del sitio, no el título de la página "Inicio".
  - Description con cascada: meta override → excerpt → primeras N palabras del content → fallback global.
- Open Graph + Twitter Cards completos: `og:title`, `og:description`, `og:image` (con dimensiones), `og:type`, `og:url`, `og:site_name`, `og:locale`, `twitter:card`, `twitter:title/description/image`.
- `og:image` con `add_image_size('og-image', 1200, 630, true)` → WP genera el recorte 1.91:1 automáticamente al subir cualquier imagen. **No hace falta CSS** para forzar proporciones en redes sociales — los crawlers no aplican CSS.
- Cascada de imagen OG: featured image del post → setting global del Customizer → logo del header → vacío.
- Canonical en archives, búsquedas, autor, fechas (los singulares ya lo reciben de `rel_canonical` de WP core).
- JSON-LD `EducationalOrganization` / `LocalBusiness` etc. solo si el cliente confirma exponer email, teléfono y dirección públicamente.
- `lang="es-PE"` (o el código país-específico) en WP Admin → Ajustes → Generales → "Idioma del sitio" → "Español de Perú". No es código.
- `<html lang>` vía `language_attributes()` siempre.
- H1 único por página. Si hay un slider con varios slides, solo el primero (slide activo inicial) es H1; los demás H2.
- `aria-labelledby` en cada `<section>` apuntando a su H2 con `id`.
- Alt configurable por slide del hero (no solo `alt=""`); fallback a `get_the_title()` cuando se usa `the_post_thumbnail()`.
- `search.php` propio, no caer al `index.php` genérico. H1 dinámico ("Resultados para «query»"), formulario, paginación, mensajes para 3 estados (con resultados, sin resultados, sin query).
- `rel="noopener noreferrer"` en TODO `target="_blank"` externo.
- Evitar agregar contenido pesado en archives (categorías, etiquetas) si solo van a duplicar lo del singular.

---

## 4. PERFORMANCE / VELOCIDAD

### CSS
- CSS por contexto. `home.css` solo en `is_front_page()`, `pages.css` solo en `!is_front_page()`. Lo realmente compartido en `cards.css` (o similar) que carga siempre.
- Bumpear versión del tema invalidando cache automáticamente.
- Inline CSS dinámico (overrides de Customizer) con `wp_add_inline_style` → mínimo HTTP overhead.

### JS
- TODO el JS en footer (`true` como 5° arg de `wp_enqueue_script`).
- Defer en cada handle: `wp_script_add_data($handle, 'strategy', 'defer')`. WP 6.3+. Ningún JS bloquea render.
- Inline anti-FOUC para tema dark/light: 1 script en `wp_head` con prioridad 1, lee localStorage y aplica `data-theme` antes del paint.

### Imágenes
- `add_image_size('xxx', W, H, true)` por cada uso (hero, card, og-image, etc.). Crop centrado.
- `wp_get_attachment_image($id, 'xxx', false, $atts)` para markup → genera srcset automático.
- Para imágenes guardadas como URL en theme_mods (no ID), helper que resuelve URL→ID con `attachment_url_to_postid()` cacheado en transient 24h, e invalidación en `delete_attachment`.
- `loading="eager"` + `fetchpriority="high"` solo en imagen LCP (primer slide del hero).
- `loading="lazy"` en todas las demás.
- `decoding="async"` siempre.

### Fuentes
- Self-hosted (no Google Fonts directos: privacidad + performance + GDPR).
- **Variable fonts** cuando estén disponibles: 1 archivo cubre múltiples pesos. Open Sans cubre 300-800 en ~48 KB; Roboto cubre 100-900 en ~43 KB.
- `font-display: swap` siempre.
- Subset `latin` para sitios en español (no incluir cirílico, griego, hebreo). Reduce ~80% del peso.
- woff2 únicamente. Todos los browsers modernos lo soportan.
- **No olvidar agregar woff2 al .gitignore exception** — si están en .gitignore, el deploy auto no los sube.

### Queries
- `WP_Query` con `meta_query` lentos: cachear IDs en transient 1h.
- Helper `cached_post_ids($key, $args)` que ejecuta query con `'fields' => 'ids'`, `'no_found_rows' => true`, `'update_post_meta_cache' => false`, `'update_post_term_cache' => false`.
- Reconstruir luego con `WP_Query(['post__in' => $ids, 'orderby' => 'post__in'])` que va a object cache.
- Invalidación: `save_post`, `deleted_post`, `customize_save_after`.

### Cache HTTP
- Emitir `Cache-Control: public, max-age=300, s-maxage=300` + `Vary: Accept-Encoding, Cookie` para HTML anónimo.
- Skip en admin, logged-in, 404, search, preview, customize.
- Esto activa cache servidor (LiteSpeed/CDN) Y browser cache. No es código de "page cache" propio; es el "interruptor" para que cualquier capa de cache aproveche.
- Trade-off: 5 min de stale tras editar Customizer. Aceptable para sitios institucionales que cambian poco.

---

## 5. CUSTOMIZER (FASE 2 PATTERN)

- Customizer **nativo** de WordPress, sin Kirki ni ACF. Custom controls cuando se necesite (eye toggle, sortable, multicheck) — extender `WP_Customize_Control`.
- Repeaters: campos numerados fijos (Slide 1/2/3, Sede 1/2/3). El Customizer nativo no tiene repeaters bien.
- Defaults centralizados (`inc/customizer/defaults.php`) referenciados por panel y template — `get_theme_mod()` no aplica defaults registrados en frontend.
- `selective_refresh` + `postMessage` para preview en vivo (texto, color, imagen).
- Drag-and-drop con jQuery UI Sortable (incluido en WP core).
- Cada panel en archivo propio bajo `inc/customizer/panel-xxx.php`. Loader en `inc/customizer.php`.
- Encolado de assets del Customizer solo en contexto admin: `customize_controls_enqueue_scripts` y `customize_preview_init`.

---

## 6. MENÚS

- 3 menús típicos: principal (header), secundario (footer col 2), redes sociales (footer col 3).
- **Custom Walker para menú de redes sociales:** detecta el `title` del item (case-insensitive) contra una allowlist (`facebook`, `instagram`, `tiktok`, `youtube`, etc.) y reemplaza el texto por SVG inline con `aria-label`.
- SVGs en `assets/icons/<red>.svg`. Inline para evitar HTTP request adicional.
- Filtro `wp_nav_menu_objects` para normalizar URLs problemáticas (ej: ítems "Inicio"/"Home" con anclas vacías o dominios localhost en producción → `home_url('/')`).
- **Cliente debe configurar el menú en WP Admin:** crear menú, agregar enlaces personalizados con label EXACTO ("Facebook" no "Mi Facebook"), asignar a la location del tema, guardar. El cliente NO siempre lo hace bien la primera vez — documentar en una guía.

---

## 7. EMBEDS DE TERCEROS (TALLY, YOUTUBE, ETC.)

- Sanitizer dedicado por servicio si se permite HTML pegado por usuario (Tally embed code).
- Validar **host** del recurso (`tally.so`, `youtube-nocookie.com`, etc.) por regex antes de aplicar `wp_kses`.
- **Auto-inyectar dependencias del servicio** si se detecta el patrón típico:
  - Tally: si hay `data-tally-src`, agregar `<script async src="https://tally.so/widgets/embed.js"></script>` una sola vez por request (variable static).
- YouTube: usar `youtube-nocookie.com` (privacy-enhanced) en vez de `youtube.com`. Extraer ID con regex y construir URL controlada nosotros, no dejar que el cliente pegue HTML libre.
- `referrerpolicy="strict-origin-when-cross-origin"`, `loading="lazy"` en iframes.

---

## 8. DEPLOY / DEVOPS

- **Hostinger auto-deploy puede demorar o fallar silenciosamente.** Verificar después de cada push: comparar `?ver=X.Y.Z` en HTML servido contra version del repo.
- **LiteSpeed Cache** (cuando está activo) debe purgar manualmente tras cada deploy. WP Admin → LiteSpeed Cache → Toolbox → Purge All.
- **El nombre de la carpeta del tema en producción puede diferir del local.** No depender del nombre.
- `.gitignore` cuidado: si una regla excluye archivos que necesitas en producción (woff2, imágenes), el deploy no los sube. Validar listo cada vez que toques `.gitignore`.
- `git ls-files` para confirmar que los archivos están trackeados antes de pushear features que dependan de ellos.
- Bumps de versión: patch para fixes (0.X.Y), minor para features (0.X+1.0), major rara vez.

---

## 9. ACCIONES DEL CLIENTE (NO CÓDIGO)

Documentar y comunicar claramente:
- WP Admin → Ajustes → Generales → **Idioma del sitio**: específico al país (es_PE, es_MX, etc.).
- WP Admin → Ajustes → Generales → **Tagline**: copy real, sin lorem ipsum ni typos.
- WP Admin → Apariencia → Personalizar → **Imagen para redes sociales**: subir imagen 1200×630.
- WP Admin → Apariencia → Menús → crear y asignar a las 3 locations.
- WP Admin → Páginas → crear las páginas referenciadas (Documentos, Libro de reclamaciones, Profesores, Política de privacidad).
- Subir imágenes reales al Media Library para reemplazar placeholders externos (picsum, lorem, etc.).
- Activar LiteSpeed Cache si el plan lo incluye.

---

## 10. PATRONES UI/UX QUE FUNCIONAN

- **Botones de descarga de PDF:** `target="_blank"` + `download` en Chrome NO descarga (abre el visor). Solución: dos botones separados — "Ver" (target=_blank) y "Descargar" (sin target, con download). En mobile stack vertical.
- **CTA descriptivos:** "Conoce nuestras sedes" mejor que "Click aquí" o "Más".
- **Sin promesas de SLA en formularios:** "Cuéntanos qué necesitas saber" es mejor que "te respondemos en 24h" si no podemos garantizarlo.
- **Tono cercano para colegios/instituciones cuyo público son padres:** "Educamos con amor", "tu hijo", "elegir colegio es importante" — no "Brindamos servicios educativos integrales de excelencia".
- **Footer con dato complementario:** trayectoria/años, mirada larga, comunidad. NO repetir lo que ya dice el home.

---

## 11. ANTI-PATRONES PROBADOS QUE FALLAN

| Anti-patrón | Qué pasa | Solución |
|---|---|---|
| Hardcodear `wp-content/themes/<slug>/...` | 404 al renombrar carpeta | `get_template_directory_uri()` |
| `target="_blank"` + `download` en PDF | Chrome ignora download | Botón doble Ver/Descargar |
| `<script>` sin `src` bloqueado en sanitizer Tally | Embeds "iframe only" no cargan | Auto-inyectar el script desde el tema |
| Defaults solo en panel del Customizer | Frontend muestra vacío | Defaults centralizados |
| Cargar `pages.css` en home | +20 KB inútiles | Cargar condicional + extraer compartidos |
| `og:locale` por defecto `es_ES` | SEO local débil | `lang` específico al país en Ajustes |
| Comentarios con `comments_open=false` solo | Bots POSTean igual al endpoint | Bloqueo en capas + .htaccess |
| Variable fonts con todos los subsets | woff2 de 300+ KB | Subset latin único |
| Fuentes en .gitignore | No llegan a producción | Quitar la exclusión |
| Refactor con CSP estricto sin testear | Sitio roto | Empezar con report-only |
| Nombre de la carpeta como texto-domain ID | Confuso pero NO rompe nada | Texto-domain es semantic, ok mantenerlo |

---

## 12. CHECKLIST PRE-LANZAMIENTO

Antes de declarar un tema listo para producción:

- [ ] Sin warnings/notices/fatals en log de PHP
- [ ] Smoke test 200 en home, blog, archive de CPT, single, página, libro de reclamaciones, búsqueda, 404
- [ ] Headers de seguridad emitidos en frontend
- [ ] Cache-Control en HTML público anónimo
- [ ] OG/Twitter Cards completos en cada tipo de página
- [ ] Canonical en cada URL única
- [ ] H1 único por página
- [ ] Imágenes con `srcset` cuando son attachments locales
- [ ] Variable fonts cargando con `font-display: swap`
- [ ] JS con `defer`
- [ ] CSS por contexto (no `pages.css` en home, no `home.css` en archives)
- [ ] Comentarios bloqueados (si no se usan)
- [ ] Tally/embeds funcionan extremo a extremo
- [ ] Menús de redes sociales con íconos correctos
- [ ] sitemap.xml accesible en `/wp-sitemap.xml`
- [ ] robots.txt sin `Disallow: /` accidental
- [ ] Page cache de Hostinger (LiteSpeed) activado en producción
- [ ] Deploy real verificado contra GitHub (versión coincide)
- [ ] Pruebas en mobile real, no solo DevTools
- [ ] Compartir el dominio en WhatsApp y verificar preview

---

*Documento vivo. Cuando aparezca una nueva lección aprendida, agregar al final de la sección correspondiente con fecha si es importante.*
