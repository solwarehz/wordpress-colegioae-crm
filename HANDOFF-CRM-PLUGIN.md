# HANDOFF — Theme Colegio AE → Equipo CRM + Equipo Plugin

> **Audiencia:** equipo de construcción del CRM Solware (backend + frontend) y equipo del Plugin de tracking.
> **Propósito:** comunicar el estado actual del theme WordPress (cliente piloto: Colegio Albert Einstein de Huaraz) y especificar el contrato esperado entre el theme y el plugin/CRM que se construirán a continuación.

---

## 1. RESUMEN EJECUTIVO

| Pieza | Estado | Responsable |
|---|---|---|
| Theme WordPress (`wordpress-colegioae-crm`) | ✅ **Producción** — versión `0.9.3` | Este equipo |
| Plugin de tracking + integración con CRM | ⏳ **Pendiente de construir** | Equipo Plugin |
| Backend CRM (NestJS + endpoints) | ⏳ **Pendiente de construir** | Equipo CRM |
| Frontend CRM (Next.js panel administrador) | ⏳ **Pendiente de construir** | Equipo CRM |
| Activaciones del cliente (contenido, imágenes, menús) | ⏳ **Pendiente** | Cliente |

Sitio piloto en producción: **https://colegioae.edu.pe**
Repositorio del theme: **https://github.com/solwarehz/wordpress-colegioae-crm**

---

## 2. THEME — LO QUE YA ESTÁ LISTO

### 2.1 Estructura del sitio (Fase 1)

- Home one-page con 9 secciones reordenables: hero, nosotros, valores, servicios (niveles educativos), sedes, profesores, mentalidad ganadora, reseñas, contáctanos.
- Plantillas adicionales: blog (archive + single), profesores (listado + perfil individual), documentos institucionales, libro de reclamaciones (Ley 29571), políticas de privacidad (Ley 29733), búsqueda interna (`search.php`), **landing de admisión** (`/admision/`).
- 3 menús administrables: principal (header), secundario (footer col 2), redes sociales (footer col 3 con Custom Walker que detecta labels y los reemplaza por SVG).
- WhatsApp flotante configurable, año dinámico en footer, soporte light/dark con toggle persistido en `localStorage`, sin auto-detección de `prefers-color-scheme`.

### 2.2 Customizer 100% editable (Fase 2)

- 14 paneles: Global, Header, Footer, Hero, Nosotros, Valores, Servicios, Sedes, Profesores, Mentalidad, Reseñas, Contacto, Documentos, Admisión.
- Ordenamiento drag-and-drop de secciones del home con jQuery UI Sortable.
- Visibility toggle (👁) por sección.
- Repeaters como campos numerados fijos (5 slides hero, 6 valores, 3 niveles, 3 sedes, 3 reseñas, 10 documentos).
- Custom controls propios: `Eye_Toggle_Control`, `Sortable_Control`, `Multicheck_Control`.
- `selective_refresh` + `postMessage` para preview en vivo.
- Defaults centralizados en `inc/customizer/defaults.php` (fuente única de verdad).

### 2.3 Sprints de calidad

| Sprint | Implementado |
|---|---|
| **S1 — Seguridad** | Sanitizer Tally con allowlist de hosts; bloqueo total de comentarios (POST `wp-comments-post.php` 403, REST 404, feeds 403, menú admin oculto, redirect de `edit-comments.php`); escape HTML en todo el output. |
| **S2 — Performance** | Variable fonts self-hosted (Open Sans + Roboto, 91 KB total subset latin); transient cache 1 h en queries de profesores y mentalidad; defer JS (7/7 scripts); CSS condicional por contexto (`pages.css` no carga en home, `home.css` no carga en archives, `cards.css` compartido); `wp_get_attachment_image()` con `srcset` automático; helper `colegio_ae_render_image()` con resolución URL→ID cacheada en transient 24 h. |
| **S3 — SEO** | Pipeline propio en `inc/seo.php` enganchado a `wp_head` con prioridad 5 (sin Rank Math/Yoast); metabox por página/post con Title override + Description; Open Graph + Twitter Cards completos; canonical en archives; H1 único por página; `aria-labelledby` en 8 secciones; alt configurable por slide del hero; `add_image_size('ae-og', 1200, 630, true)` para imagen social con crop centrado. |
| **S4 — Accesibilidad** | Heading hierarchy correcta, alt text en todas las imágenes con fallback a `get_the_title()`, `aria-labelledby` en sections, `rel="noopener noreferrer"` en target=_blank, sentence case automático con helper PHP. |
| **S5 — Hardening servidor** | Headers HTTP de seguridad (X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS en HTTPS); `Cache-Control: public, max-age=300, s-maxage=300` para HTML público (LiteSpeed Cache de Hostinger lo aprovecha automáticamente). |
| **Landing Admisión** | Template standalone (sin `get_header`/`get_footer`) con logo sin link (anti-fuga del lead), banner full-width con altura controlada por `vh`, formulario Tally embebido, mini-footer con copyright, WhatsApp float persistente. |

### 2.4 Convenciones técnicas

- Constantes globales: `COLEGIO_AE_VERSION`, `COLEGIO_AE_DIR`, `COLEGIO_AE_URI` (todas resuelven dinámicamente; el nombre real de la carpeta del tema en producción es `solware`, no `colegio-ae`, y el código no depende de eso).
- PHP 8.3 mínimo (probado en Hostinger LiteSpeed).
- `wp_kses` con allowlists para todo embed pegado por usuario.
- Stack frontend: CSS puro con custom properties, vanilla JS, sin build tool.

---

## 3. CAMBIO IMPORTANTE RESPECTO AL SPEC ORIGINAL

> **Tally reemplaza a Fluent Forms.**

El spec MVP del CRM Solware (v1.1) menciona Fluent Forms como el motor de formularios. El cliente decidió usar **Tally** (https://tally.so) en su lugar, por:
- Cero costo en plan gratuito (Fluent Forms gratis tiene limitaciones).
- Editor más amigable para el cliente no técnico.
- Mejor UX en mobile out-of-the-box.

### Implicaciones para el plugin

El form NO vive en el sitio WordPress. Vive en `tally.so` y se embebe vía `<iframe data-tally-src="...">` + `<script src="https://tally.so/widgets/embed.js">`.

Esto cambia la estrategia de tracking del plugin:

| Spec original (Fluent Forms) | Realidad actual (Tally) |
|---|---|
| El plugin intercepta el submit JS del form interno | Imposible: el form vive en otro origen (CORS) |
| El plugin agrega el `visitor_id` al payload | El plugin debe pasar `sw_vid` como **query param al iframe** (`data-tally-src=...&visitor_id=XXX`) |
| El plugin POSTea a `/api/leads/intake` desde el sitio | Tally hace **webhook** directo al CRM cuando el form se envía |
| `form_submit`, `form_start`, `form_abandon` se detectan vía JS | Solo se puede detectar `form_start` (carga del iframe) y `form_submit` vía `postMessage` cross-origin del iframe Tally al parent |

### Recomendación de arquitectura para el plugin

1. **Inyectar `visitor_id` al iframe Tally** — antes de que se monte el iframe, el plugin reescribe `data-tally-src` para incluir `?visitor_id=<sw_vid>` como hidden field. Tally lo recibe como un campo más y lo incluye en el webhook.
2. **Webhook Tally → CRM directo**: configurar en cada form de Tally un webhook hacia `https://api.solware-pyme.com/api/leads/intake`. El payload incluirá `visitor_id` automáticamente.
3. **Tracking de eventos**: el plugin escucha eventos `postMessage` del iframe Tally (Tally emite `Tally.FormLoaded` y `Tally.FormSubmitted` vía postMessage). Con eso enviamos `form_start` y `form_submit` a `/api/tracking/events`.
4. **Form abandon**: usar `beforeunload` en el parent + IntersectionObserver para detectar que el iframe estuvo en pantalla pero el usuario salió sin enviar. Manda `form_abandon` con el `visitor_id`.

---

## 4. SPEC DEL PLUGIN A CONSTRUIR

Vigente la spec del MVP CRM Solware (sección 8) con los ajustes del punto 3:

### 4.1 Identificación del visitante (`visitor_id`)

- Cookie first-party `sw_vid` con UUID v4.
- Vida útil: 365 días.
- Generada **solo si el usuario consintió** las cookies (banner Ley 29733).
- Renovar `Max-Age` en cada visita.

### 4.2 Banner de consentimiento (Ley 29733 Perú)

- 3 opciones: **Aceptar**, **Rechazar**, **Configurar**.
- Si rechaza: NO genera `visitor_id`, NO envía eventos, NO carga Facebook Pixel u otros pixels.
- "Configurar" abre un modal con checks por categoría (esencial, analítica, marketing).
- Estado del consentimiento persistido en cookie separada (`sw_consent`).

### 4.3 Eventos a trackear

| Evento | Cuándo | Payload mínimo |
|---|---|---|
| `pageview` | Al cargar cada página | url, referrer, ts |
| `time_on_page` | Cada 30 s mientras la pestaña esté activa | url, seconds, ts |
| `scroll_depth` | Al alcanzar 25 / 50 / 75 / 100 % | url, depth, ts |
| `click` | Click en cualquier elemento con `data-track="<label>"` | label, url, ts |
| `form_start` | Iframe Tally cargado y visible (IntersectionObserver) | form_id, url, ts |
| `form_abandon` | beforeunload con form visto pero no enviado | form_id, url, ts |
| `form_submit` | postMessage `Tally.FormSubmitted` recibido | form_id, url, ts |

### 4.4 Envío de eventos al CRM

- Endpoint: **`POST https://api.solware-pyme.com/api/tracking/events`**
- Batch: cada 30–60 s, o al cerrar/cambiar pestaña (`navigator.sendBeacon`).
- Payload:
  ```json
  {
    "visitor_id": "uuid",
    "site": "colegioae.edu.pe",
    "events": [
      { "type": "pageview", "url": "...", "ts": 1714000000, ... },
      ...
    ]
  }
  ```
- Headers: `Content-Type: application/json`, sin auth (público; rate-limit del lado CRM).
- Reintentos: 3 con backoff exponencial; si falla, descartar (no bloquear al usuario).

### 4.5 Inyección de `visitor_id` en formularios Tally

El plugin debe:
1. Buscar todos los `iframe[data-tally-src]` en la página.
2. Antes de que `widgets/embed.js` los monte, agregar al `data-tally-src` el query param:
   ```
   &visitor_id=<sw_vid>&site=colegioae.edu.pe&ref=<document.referrer>
   ```
3. Tally pasará esos values como hidden fields en el webhook al CRM.

### 4.6 Panel de configuración WP Admin

- Opciones:
  - URL del CRM endpoint (default: `https://api.solware-pyme.com`).
  - Sitio identifier (default: `colegioae.edu.pe`).
  - Toggle: enviar a Facebook Pixel (off por default).
  - Pixel ID si está activo.
  - Texto del banner de consentimiento (editable, multilenguaje opcional).
- Capability: `manage_options`.
- Página dedicada bajo menú "CRM Solware".

### 4.7 Restricciones técnicas

- **Tamaño del JS de tracking ≤ 20 KB minified + gzipped.**
- Vanilla JS (sin React, sin jQuery).
- Sin dependencia del theme — el plugin debe funcionar con cualquier theme WP.
- Compatible con LiteSpeed Cache (no romper page cache).

---

## 5. ENDPOINTS QUE EL CRM DEBE EXPONER

| Endpoint | Método | Origen | Payload | Propósito |
|---|---|---|---|---|
| `https://api.solware-pyme.com/api/tracking/events` | POST | Plugin (browser, sin auth) | `{ visitor_id, site, events: [...] }` | Recibir batch de eventos del visitante |
| `https://api.solware-pyme.com/api/leads/intake` | POST | Webhook Tally (con secret) | Payload de Tally con `visitor_id` como custom field | Crear lead cuando se envía un form |

### CORS
Ambos endpoints deben permitir CORS desde:
- `https://colegioae.edu.pe`
- (futuros dominios de clientes del CRM)

### Esquema de eventos (sugerencia)
```ts
type Event = {
  type: 'pageview' | 'time_on_page' | 'scroll_depth' | 'click' | 'form_start' | 'form_abandon' | 'form_submit';
  url: string;
  ts: number;             // unix epoch ms
  meta?: Record<string, any>;
};
```

### Esquema de lead (sugerencia)
```ts
type Lead = {
  visitor_id?: string;     // viene del custom field inyectado por el plugin
  site: string;
  form_id: string;
  fields: Record<string, any>;  // todos los campos del form Tally
  source: string;          // url donde se llenó el form
  utm: Record<string, string>;  // utm_source, utm_medium, etc. (si aplica)
  ts: number;
};
```

---

## 6. HOOKS DE INTEGRACIÓN DISPONIBLES EN EL THEME

El theme expone los siguientes filters/actions útiles para el plugin:

| Hook | Tipo | Uso |
|---|---|---|
| `colegio_ae_sections` | filter | Modificar el catálogo de secciones del home (extender, ocultar, reordenar). |
| `wp_head` (prio 5) | action | El theme inyecta meta SEO en prio 5; el plugin puede engancharse después para añadir más tags. |
| `wp_body_open` | action | Punto recomendado para el banner de consentimiento de cookies. |
| `wp_footer` | action | Punto recomendado para el script de tracking minified. |
| `wp_enqueue_scripts` | action | Si el plugin necesita encolar JS, hacerlo aquí (no inline en el footer). |

### Identificadores DOM disponibles
- Cualquier elemento puede recibir `data-track="<label>"` para que el plugin trackee click.
- Iframes Tally: `iframe[data-tally-src]` (selector seguro).
- WhatsApp float: `.whatsapp-float` (selector si quieren trackear este canal aparte).

---

## 7. ACTIVACIONES PENDIENTES DEL CLIENTE (NO TÉCNICAS)

Antes de cerrar el handoff completo, el cliente debe completar en WP Admin:

- [ ] **Ajustes → Generales → Idioma del sitio** → "Español de Perú" (`es_PE`)
- [ ] **Ajustes → Generales → Descripción corta** → reemplazar el placeholder actual con el copy real (sin typos)
- [ ] **Apariencia → Personalizar → Identidad del sitio** → subir Site Icon ≥ 512×512
- [ ] **Apariencia → Personalizar → Global** → subir "Imagen para redes sociales (OG)" ≥ 1200×630
- [ ] **Apariencia → Personalizar → Global** → confirmar/editar "Descripción del sitio para Google"
- [ ] **Apariencia → Personalizar → Página: Admisión** → subir banner + pegar embed Tally específico de admisión
- [ ] **Apariencia → Personalizar → Sección: Contáctanos** → pegar embed Tally del formulario de contacto
- [ ] **Apariencia → Personalizar → Sección: Hero** → reemplazar slides placeholder por fotos reales del colegio
- [ ] **Apariencia → Personalizar → Sección: Sedes / Profesores / Reseñas** → subir fotos reales
- [ ] **Apariencia → Menús** → crear los 3 menús (principal, secundario, redes sociales) y asignar locations
- [ ] **Páginas → Crear**: Documentos, Profesores (listado), Libro de Reclamaciones, Política de Privacidad, Admisión (con su template)
- [ ] **Hostinger → LiteSpeed Cache** → activar (si plan lo incluye)
- [ ] **Facebook Sharing Debugger** → forzar re-scrape después de subir OG image
- [ ] **Google Search Console** → registrar el sitio y enviar `wp-sitemap.xml`

---

## 8. STACK Y DATOS DEL PROYECTO

### Theme
- **Repo:** https://github.com/solwarehz/wordpress-colegioae-crm
- **Branch:** `main`
- **Versión actual:** `0.9.3`
- **Hosting:** Hostinger (LiteSpeed Web Server)
- **Auto-deploy:** push a `main` → pull automático en Hostinger
- **Notas:** la carpeta del tema en producción se llama `solware`, no `colegio-ae`. El código resuelve esto dinámicamente.

### Documentos del proyecto en este repo
- `CLAUDE.md` — especificaciones completas y arquitectura del theme.
- `MANUAL-TEMAS-WP.md` — guía operativa de mejores prácticas (lecciones aprendidas reusables para futuros temas).
- `contenido-fase1.md` — copy y assets de la Fase 1 (referencia histórica).
- `plugin.md` — borrador inicial de la spec del plugin (referencia).
- `HANDOFF-CRM-PLUGIN.md` — este documento.

### Backend CRM (a construir por equipo CRM)
- Stack: NestJS
- Dominio API: `api.solware-pyme.com`
- Base de datos: a definir por equipo CRM
- Autenticación: a definir (sin auth en endpoints de tracking; con secret en webhook de Tally)

### Frontend CRM (a construir por equipo CRM)
- Stack: Next.js
- Dominio: a definir (probable: `app.solware-pyme.com` o `crm.solware-pyme.com`)

### Cliente piloto
- **Nombre:** Colegio Albert Einstein
- **Ubicación:** Huaraz, Perú
- **Buyer persona:** "Madre comprometida" 35 años, busca formación integral para sus hijos
- **WhatsApp del colegio:** `+51 981398282`
- **Lema:** "Un einstino, un triunfador"
- **Cumplimiento legal:** Ley 29733 (datos personales) + Ley 29571 (reclamaciones SUNAT/Indecopi)

---

## 9. CONTACTO Y SIGUIENTES PASOS

1. **Equipo CRM:** revisar §5 (endpoints) + §3 (cambio Tally vs Fluent Forms). Definir esquemas exactos de payload + autenticación.
2. **Equipo Plugin:** revisar §3 + §4 + §6. Decidir si se construye 1 plugin único o se separa en (a) banner de consentimiento, (b) tracker de eventos, (c) integración Tally→CRM.
3. **Cliente:** completar §7 (activaciones).
4. **Sincronizar** entre los 3 equipos sobre el formato exacto del payload del webhook de Tally.

Cualquier ajuste a este handoff debe registrarse al final del documento con fecha y autor.

---

*Generado: estado al cierre de la fase Theme MVP del proyecto CRM Solware.*
