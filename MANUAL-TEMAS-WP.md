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

### SEO real en buscadores (lecciones aprendidas)
- **Google extrae description del CONTENIDO si la query no aparece en la meta.** Si la búsqueda es "colegio particular huaraz" pero el home no menciona "particular", Google ignora la meta description y saca un fragmento del contenido. Fix: incluir las keywords objetivo de forma natural en el copy visible del home.
- **Google es más estricto con `og:image` que Bing.** Si la imagen real es más pequeña que las dimensiones declaradas (`og:image:width`/`height`), Google NO muestra imagen en el resultado. Bing scraea las imágenes del DOM como fallback aunque no haya OG.
- **Dominios similares dividen la autoridad.** Si existe `cae.edu.pe` y `colegioae.edu.pe` ambos para Albert Einstein, Google los trata como sitios distintos y reparte autoridad. Resolver con redirect 301 si son del mismo dueño; aceptar la competencia si son diferentes.
- **`og:image` cascada**: featured image → setting global Customizer → logo header → vacío. **El logo del header rara vez es buena imagen OG** (suele ser pequeño, transparente, sin contenido visual). Si el cliente no sube setting global, el preview en redes sale roto. Requerir explícitamente al cliente subir imagen OG ≥ 1200×630 px.
- **WP NO genera variantes mayores que la imagen original.** Si subes un logo de 350×94 px y registras `add_image_size('og', 1200, 630, true)`, WP NO crea la variante 1200×630. Sirve la original y queda mal en OG.
- **Plantear lo crítico:** verificar dimensiones reales del attachment antes de declarar `og:image:width`/`height`. Mejorar el helper para detectar discrepancias y NO emitir dimensiones falsas.

---

## 4. PERFORMANCE / VELOCIDAD

### CSS
- CSS por contexto. `home.css` solo en `is_front_page()`, `pages.css` solo en `!is_front_page()`. Lo realmente compartido en `cards.css` (o similar) que carga siempre.
- CSS por template específico: `if (is_page_template('page-templates/page-X.php'))` → encolar X.css solo cuando ese template está activo (ej. landing de admisión).
- Bumpear versión del tema invalidando cache automáticamente.
- Inline CSS dinámico (overrides de Customizer) con `wp_add_inline_style` → mínimo HTTP overhead.

### Truncado de texto seguro (line-clamp)
- Para títulos del hero, subtítulos largos, descriptions de cards: usar `display: -webkit-box; -webkit-line-clamp: N; -webkit-box-orient: vertical; overflow: hidden;` para limitar a N líneas con ellipsis.
- Combinar con `max-width: Nch` para controlar ancho de líneas. Una frase de 48 chars con `max-width: 18ch` y `line-clamp: 3` se trunca; con `24ch` y `line-clamp: 4` cabe holgada.
- Calcular capacidad: `max-width-ch × line-clamp ≈ chars máximos antes de truncar`.

### Sombras de texto para legibilidad sobre imágenes
- Doble capa para sensación de "elevation" Material Design:
  ```css
  text-shadow:
      0 2px 4px rgba(0, 0, 0, 0.4),    /* cercana, blur bajo, opacity alta */
      0 6px 18px rgba(0, 0, 0, 0.35);   /* lejana, blur alto, opacity baja */
  ```
- En texto pequeño (subtítulos): UNA capa, blur 8px, opacity 0.45. Doble sombra lo hace pesado.
- NO aplicar `filter: blur()` al texto en sí — lo vuelve ilegible. El blur deseable está en el shadow, no en el texto.

### Tipografía consistente con sentence case desde PHP
- CSS `text-transform: capitalize` capitaliza cada palabra, NO solo después de punto.
- CSS `text-transform: lowercase` + `::first-letter { uppercase }` cubre el caso simple pero no cubre "mayúscula tras punto".
- **Solución mejor**: helper PHP que normaliza el texto antes de imprimir. Ventaja: el HTML servido también queda semánticamente correcto (no solo el render visual). SEO + screen readers reciben texto limpio.
- Patrón con `mb_strtolower` + `mb_strtoupper` + `preg_replace_callback` con regex `/(\.\s+)(\p{L})/u` para capitalizar tras punto.
- Aplicar al render del título/subtítulo del hero, no al sanitize_callback (no destruir el dato del cliente, normalizar al mostrar).

### Override de reglas globales en componentes específicos
- Si declaras `h2 { text-transform: uppercase }` global, recordar que afecta a TODOS los h2, incluidos los que están dentro de componentes específicos.
- Caso real: el hero usa `h1` para slide #1 y `h2` para slides 2+. Slides 2+ heredaban uppercase de la regla global y rompían la consistencia visual con el slide 1.
- Fix: en el componente, agregar override explícito (`text-transform: none; letter-spacing: normal`) si la regla global no aplica al contexto.

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

### Cache de previews en redes sociales
- **WhatsApp y Facebook cachean previews por días/semanas.** Una vez que ven un URL sin imagen decente, lo guardan así. Aunque después subas la imagen OG correcta, el preview viejo persiste.
- **Para forzar re-scrape:** Facebook Sharing Debugger https://developers.facebook.com/tools/debug/ → pegar URL → "Scrape Again". WhatsApp comparte el sistema de Meta, así que purgar Facebook purga ambos.
- **Truco rápido:** compartir URL con query string (`?v=2`) hace que el bot lo trate como URL nuevo, sin cache.
- **Validar siempre** con el Debugger después de subir imagen OG, antes de difundir el link.

---

## 8. DEPLOY / DEVOPS

- **Hostinger auto-deploy puede demorar o fallar silenciosamente.** Verificar después de cada push: comparar `?ver=X.Y.Z` en HTML servido contra version del repo.
- **El nombre de la carpeta del tema en producción puede diferir del local.** No depender del nombre.
- `.gitignore` cuidado: si una regla excluye archivos que necesitas en producción (woff2, imágenes), el deploy no los sube. Validar listo cada vez que toques `.gitignore`.
- `git ls-files` para confirmar que los archivos están trackeados antes de pushear features que dependan de ellos.
- Bumps de versión: patch para fixes (0.X.Y), minor para features (0.X+1.0), major rara vez.

### Tres niveles de cache que purgar tras cada deploy

Después de un push, hay TRES capas que pueden estar sirviendo código viejo. Purgarlas en orden:

1. **Archivos físicos en el servidor** — el `git pull` debe haber bajado los cambios. Verificar con `?ver=X.Y.Z` en el HTML del servido. Si el HTML pide `?ver=` viejo aunque GitHub tenga el último, el deploy NO bajó los archivos.

2. **PHP OPcache** — si los archivos físicos están actualizados pero el HTML sigue pidiendo `?ver=` viejo, es OPcache cacheando `functions.php` en bytecode compilado. Síntomas:
   - Descargas el CSS directo y tiene los cambios nuevos ✓
   - Pero el HTML sigue declarando `?ver=` viejo
   - LiteSpeed Cache devuelve `miss` (no es ese)
   
   Soluciones:
   - WP Admin → LiteSpeed Cache → Toolbox → Purge OPcache (si la opción existe)
   - hPanel → Avanzado → PHP Configuration → "Restart PHP" o "Reset OPcache"
   - File Manager → editar `functions.php` y guardar (cambia el timestamp y OPcache lo recarga si `validate_timestamps=1`)
   - Soporte de Hostinger por chat ("purgar OPcache de PHP")

3. **LiteSpeed Cache (HTML cacheado)** — purgar siempre tras cualquier cambio. WP Admin → LiteSpeed Cache → Toolbox → Purge → Purge All.

4. **(Opcional) Browser** — Ctrl+Shift+R o pestaña incógnita para verificar.

**Patrón de diagnóstico:**
- ¿GitHub tiene el commit? Sí.
- ¿`?ver=` en HTML coincide con la versión esperada? Si no → archivo físico viejo o OPcache.
- ¿`fetch(css, {cache: 'no-store'})` devuelve los cambios? Si sí → archivo OK, problema es OPcache. Si no → deploy no bajó los archivos.

### Headers HTTP en producción Hostinger
- LiteSpeed Cache respeta los `Cache-Control` que emites desde PHP.
- `X-LiteSpeed-Cache: hit` en respuesta = la página se sirvió desde cache (rápido).
- `X-LiteSpeed-Cache: miss` = se generó fresco desde PHP (más lento, primer visitante después de purga).

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

### Landing pages de conversión (sin distractores)

Para una página cuyo único objetivo es captar el lead (admisión, evento, descuento, etc.):

- **Template standalone**: NO usar `get_header()` ni `get_footer()`. Construir DOCTYPE + html + head con `wp_head()` + body + content + `wp_footer()` manualmente. Da control total del chrome.
- **Sin menú de navegación**: el menú es la principal vía de fuga. Si no hay menú, no hay cómo distraerse.
- **Logo SIN link envolvente**: mantiene identidad visual y credibilidad, pero el visitante no puede navegar al sitio principal y "escapar" de la conversión. Solo el logo como decoración.
- **Banner con altura en `vh` (no aspect-ratio fijo)**: garantiza que el form quede visible arriba del fold sin importar viewport. `height: 35vh; max-height: 360px; min-height: 220px` funciona bien para banners de hero. En mobile bajar a 28vh / 240px / 160px.
- **WhatsApp float visible**: única alternativa al form. Suma conversión sin distraer porque está en esquina, no en flujo de lectura.
- **Mini-footer con copyright**: cero links extra, solo cumplir con la marca y el año.
- **Skip-link** al formulario para accesibilidad.
- **Banner con `loading="eager"` + `fetchpriority="high"`** porque es el LCP de la página.
- **Razones concretas en el copy invitacional**, no solo "déjanos tus datos". Justificar por qué cada campo del form es útil para el lead (ej: "para confirmar disponibilidad en el grado de tu hijo").
- **Encolar CSS específico solo para esa landing** con `is_page_template()`. Evita cargar estilos del sitio normal en una landing.

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
| Logo con link a home en una landing | Lead se escapa al sitio principal | Logo sin link (solo elemento visual) |
| Banner con `aspect-ratio` fijo en landing | Ocupa todo el viewport, oculta el form | `height: Nvh` con max-height + min-height |
| `og:image` que apunta al logo del header (350×94) | WhatsApp/Facebook rechazan preview por dimensiones | Setting dedicado en Customizer + imagen ≥ 1200×630 |
| `text-transform: capitalize` para sentence case | Capitaliza CADA palabra, no después de punto | Helper PHP que normaliza al renderizar |
| Reglas globales de h2/h3 sin pensar en componentes | Override invisible rompe estilos en hero u otros | Override explícito en componente cuando aplica |
| OPcache no purgado tras deploy | HTML sigue pidiendo `?ver=` viejo aunque el deploy bajó archivos | Restart PHP / Reset OPcache / tocar timestamp de functions.php |
| WhatsApp/Facebook cache de preview | Preview viejo persiste por días tras corregir OG | Facebook Sharing Debugger → "Scrape Again" |
| Banner sin `fetchpriority="high"` siendo el LCP | Métricas Core Web Vitals peor de lo necesario | Marcar el LCP image como eager + high priority |

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
- [ ] OPcache purgado tras último deploy (verificar `?ver=` en HTML)
- [ ] Pruebas en mobile real, no solo DevTools
- [ ] Imagen OG ≥ 1200×630 subida en Customizer (no usar el logo)
- [ ] Compartir el dominio en WhatsApp y verificar preview con imagen
- [ ] Facebook Sharing Debugger ejecutado tras subir OG image
- [ ] Buscar el sitio en Google y Bing con queries reales del cliente
- [ ] Verificar dominios similares competidores (cae.edu.pe, etc.)
- [ ] Idioma del sitio en formato país (es-PE, no solo es)

---

## 13. PROCESO POR FASES — STATUS

Cada cliente requiere fases discretas. Lo aprendido en el proyecto colegio-ae:

| Fase | Estado típico | Bloqueos comunes |
|---|---|---|
| Fase 1 — Tema completo | ✅ Mockup funcional listo | Falta contenido real del cliente |
| Fase 2 — Customizer 100% editable | ✅ Todo desde WP Admin | Cliente no termina de cargar contenidos |
| Sprint Seguridad | ✅ Tally + comentarios + headers | — |
| Sprint Performance | ✅ fonts + cache + defer | OPcache de Hostinger |
| Sprint SEO técnico | ✅ meta + OG + canonical | Cliente no sube imagen OG real |
| Sprint Accesibilidad | ✅ H1 + aria + alt | — |
| Sprint Hardening servidor | ✅ headers seguridad + cache HTML | Hostinger LiteSpeed config |
| Landings de conversión | ✅ admisión modelo | Cliente debe armar Tally específico |
| **Pendiente típico al cierre** | Compartir en redes (OG bien) + posicionamiento Google + activación de páginas opcionales | Acciones del cliente, no de código |

### Activaciones que el cliente debe completar después del cierre técnico
- Subir imagen OG ≥ 1200×630 en Customizer global
- Subir imágenes reales para cada slot (slides, sedes, profesores, reseñas)
- Pegar embed Tally en cada formulario (contacto, reclamaciones, admisión)
- Crear las páginas y asignar templates (Documentos, Profesores, Libro, Admisión, Privacidad)
- Configurar menús (principal, secundario, redes sociales)
- Cambiar idioma del sitio a país-específico
- Validar Google Search Console + Bing Webmaster Tools
- Forzar re-scrape en Facebook Sharing Debugger

---

*Documento vivo. Cuando aparezca una nueva lección aprendida, agregar al final de la sección correspondiente con fecha si es importante.*
