# plugin.md — Plugin CRM Solware para WordPress

> **Estado: borrador en elaboración.** Este documento seguirá refinándose hasta que la Fase 1 del tema esté terminada; ver el resultado final del sitio permitirá ajustar detalles de integración (selectores `data-track`, mapeo de formularios, convenciones compartidas) antes de arrancar la implementación del plugin.
>
> Este documento define todo lo que el equipo del plugin debe construir. Es la fuente de verdad del alcance; la especificación completa del proyecto vive en `CRM Solware — Especificaciones Técnicas del MVP Fase 1 v1.1`, sección 8.

---

## 1. Identidad del plugin

| Campo | Valor |
|-------|-------|
| Nombre comercial | **CRM Solware** |
| Slug del plugin | `solware-crm` |
| Text domain | `solware-crm` |
| Repositorio | `wordpress-solware-crm-plugin` — GitHub org `solwarehz` |
| Entregable | Archivo `.zip` instalable desde WP Admin → Plugins → Subir plugin |
| Licencia | GPL v2 o posterior (requerido por WP) |

## 2. Contexto

El plugin forma parte del sistema **CRM Solware**, una plataforma de gestión de leads para instituciones educativas. Este plugin es la pieza del lado WordPress que:

1. **Identifica** al visitante con una cookie first-party.
2. **Rastrea** su comportamiento en el sitio.
3. **Captura** su información cuando envía un formulario (Fluent Forms).
4. **Envía** todo al backend NestJS de Solware (`api.solware-pyme.com`).
5. **Cumple** con la Ley 29733 del Perú (consentimiento explícito de cookies).

El plugin NO depende del tema; debe funcionar con cualquier tema WordPress. El piloto es el **Colegio Albert Einstein** (Perú), cuyo tema vive en otro repositorio (`wordpress-colegioae-crm`).

## 3. Responsabilidades del equipo del plugin

**Dentro del alcance:**
- Todo el código PHP del plugin (hooks, admin, REST endpoints internos si son necesarios).
- El script de tracking JS (vanilla, ≤ 20 KB minificado).
- El banner de consentimiento de cookies.
- El panel de administración en WP Admin.
- La integración con Fluent Forms (hooks).
- La documentación del plugin (`README.md` del propio plugin).
- Pruebas de instalación/desinstalación limpias.

**Fuera del alcance:**
- El tema WordPress (otro equipo, otro repo).
- El backend NestJS (`api.solware-pyme.com`) — ya existe; solo se consume vía HTTP.
- El frontend Next.js del CRM.
- La infraestructura Docker / Hostinger.

## 4. Funcionalidades

### 4.1 Cookie `visitor_id`

- Al primer ingreso (si hay consentimiento), generar un UUID v4.
- Guardar en cookie first-party: `sw_vid`.
- TTL: **1 año**.
- Atributos: `Path=/; SameSite=Lax; Secure` (en HTTPS).
- Si ya existe, reusar el mismo valor en todas las requests.
- Si el usuario **rechaza** el consentimiento: **no** generar la cookie, **no** enviar eventos, **no** disparar Facebook Pixel.

### 4.2 Script de tracking (vanilla JS)

- **Presupuesto de tamaño: ≤ 20 KB minificado**, sin gzip.
- **Sin dependencias externas** (no jQuery, no axios, no nada).
- Encolado en el `<head>` del sitio solo si hay consentimiento.

**Eventos a capturar:**

| Evento | Disparador | Payload mínimo |
|--------|-----------|----------------|
| `pageview` | Al cargar la página | `url`, `referrer`, `user_agent`, `visitor_id` |
| `time_on_page` | Al salir de la página (`beforeunload` o `visibilitychange`) | `duration_seconds` |
| `scroll_depth` | Cuando el usuario alcanza 25 / 50 / 75 / 100 % | `percentage` |
| `click` | Clicks en elementos con atributo `data-track="nombre"` | `target`, `data-track`, `href` si existe |
| `form_start` | Primer `focus` dentro de un form Fluent Forms | `form_id` |
| `form_abandon` | El usuario empezó el form y se fue sin enviarlo | `form_id`, `last_field` |
| `form_submit` | Submit exitoso | `form_id` |

**Batching:**
- Buffer local en memoria.
- Flush cada **30–60 s** (configurable desde admin) o cuando el buffer alcance N eventos.
- También flush en `beforeunload` / `visibilitychange` (usar `navigator.sendBeacon` como fallback).
- Endpoint: `POST https://api.solware-pyme.com/api/tracking/events`
- Body: array de eventos en JSON. Incluir `visitor_id` en cada uno.
- Reintentos: si falla, reencolar en `localStorage` y reintentar en la siguiente página.

### 4.3 Integración con Fluent Forms

Al enviar un formulario Fluent Forms:
1. Inyectar `visitor_id` como campo oculto en el payload (hook `fluentform/submission_inserted` o similar).
2. Reenviar el payload al backend de Solware: `POST https://api.solware-pyme.com/api/leads/intake`
3. Incluir el `form_id` de Fluent Forms y los campos mapeados.

El envío a Solware debe hacerse **desde el servidor (PHP)**, no desde el cliente, para evitar bloqueos de ad-blockers y asegurar confiabilidad.

### 4.4 Banner de consentimiento (Ley 29733)

Primera visita al sitio → mostrar banner con **3 opciones**:

- **Aceptar todas las cookies** → genera `sw_vid`, activa tracking, activa Facebook Pixel si está configurado.
- **Rechazar** → **ninguna** cookie de tracking, **ningún** evento enviado, **ningún** pixel disparado.
- **Configurar** → modal con categorías granulares:
  - Necesarias (siempre activas)
  - Analíticas / tracking CRM (toggle)
  - Marketing / Facebook Pixel (toggle)

La preferencia debe guardarse en `localStorage` Y en una cookie técnica (`sw_consent`) para que funcione incluso con JS deshabilitado en cierta forma. Re-consultable desde un enlace en el footer ("Configurar cookies").

### 4.5 Panel de administración (WP Admin)

Menú propio en el sidebar: **CRM Solware**. Submenús:

| Sección | Contenido |
|---------|-----------|
| **General** | API endpoint base (default `https://api.solware-pyme.com`), API key/token, toggle global de tracking |
| **Tracking** | Intervalo de batch (30–60 s), eventos habilitados/deshabilitados, lista de selectores `data-track` de referencia |
| **Formularios** | Mapeo de formularios Fluent Forms → endpoint de intake, campos a enviar |
| **Consentimiento** | Textos del banner (ES), colores, posición, link a políticas |
| **Pixel** | ID de Facebook Pixel (opcional), solo se dispara con consentimiento |
| **Estado** | Últimos envíos al backend, contador de eventos, errores |

Todos los campos deben usar nonces + `current_user_can('manage_options')`.

## 5. Contrato con el backend

El backend lo provee otro equipo. Endpoints que el plugin consume:

```
POST https://api.solware-pyme.com/api/tracking/events
Headers:
  Content-Type: application/json
  X-Solware-Site: <site_id desde admin>
  Authorization: Bearer <api_token desde admin>
Body:
  [
    { "visitor_id": "uuid", "type": "pageview", "url": "...", "ts": 1712345678, ... },
    ...
  ]

POST https://api.solware-pyme.com/api/leads/intake
Headers: (iguales)
Body:
  {
    "visitor_id": "uuid",
    "form_id": "fluentform-id",
    "fields": { "nombre": "...", "email": "...", "telefono": "..." },
    "submitted_at": 1712345678
  }
```

Si algún detalle del contrato cambia, coordinar con el equipo backend **antes** de implementar.

## 6. Arquitectura y estructura de archivos

```
solware-crm/
├── solware-crm.php                ← archivo principal con el header del plugin
├── uninstall.php                  ← limpieza al desinstalar (borrar options, transients)
├── readme.txt                     ← formato WordPress.org (aunque no se publique)
├── README.md                      ← documentación dev
├── plugin.md                      ← este documento
│
├── includes/
│   ├── class-plugin.php           ← bootstrap / loader
│   ├── class-consent.php          ← lógica del banner
│   ├── class-tracking.php         ← endpoints REST internos si hacen falta
│   ├── class-fluentforms.php      ← hooks Fluent Forms → backend
│   ├── class-api-client.php       ← wrapper de wp_remote_post a api.solware-pyme.com
│   └── class-uuid.php             ← generador UUID v4
│
├── admin/
│   ├── class-admin.php            ← settings API + render
│   ├── views/
│   │   ├── general.php
│   │   ├── tracking.php
│   │   ├── forms.php
│   │   ├── consent.php
│   │   ├── pixel.php
│   │   └── status.php
│   └── assets/
│       ├── admin.css
│       └── admin.js
│
├── public/
│   ├── class-public.php           ← enqueue scripts/styles en frontend
│   └── assets/
│       ├── tracker.js             ← script de tracking (vanilla, <20KB)
│       ├── consent.js             ← lógica del banner
│       ├── consent.css
│       └── pixel.js               ← integración Facebook Pixel (condicional)
│
└── languages/
    └── solware-crm.pot            ← internacionalización (i18n)
```

## 7. Header del plugin (archivo principal)

```php
<?php
/**
 * Plugin Name:       CRM Solware
 * Plugin URI:        https://solware.com/crm
 * Description:       Tracking de visitantes, integración con Fluent Forms y banner de consentimiento de cookies para el CRM Solware.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Solware
 * Author URI:        https://solware.com
 * License:           GPL v2 or later
 * Text Domain:       solware-crm
 * Domain Path:       /languages
 */

defined('ABSPATH') || exit;
```

## 8. Cumplimiento legal (Ley 29733 — Perú)

- El banner debe aparecer **antes** de que se cree cualquier cookie de tracking.
- Los 3 botones (Aceptar / Rechazar / Configurar) deben tener **jerarquía visual equivalente** — no es válido hacer "Aceptar" gigante y "Rechazar" un link pequeño.
- Rechazar debe ser tan fácil como aceptar.
- La preferencia debe ser reconsultable y modificable desde un link visible en el footer.
- Registrar en DB (table propia o option) el timestamp del consentimiento y el ID de versión de texto legal aceptado (para auditoría).
- **Sin datos de menores en Fase 1.** Los formularios del tema solo capturan información de padres/madres/apoderados.

## 9. Requisitos no funcionales

### Performance
- `tracker.js` ≤ **20 KB** minificado.
- Enqueue diferido (`defer` o al final del `<body>`), **sin bloquear el render**.
- Batching de requests (no spammear al backend).
- Objetivo del sitio: PageSpeed mobile > 80 con el plugin activo.

### Seguridad
- **Nonces** en todas las acciones admin (`wp_nonce_field`, `check_admin_referer`).
- **Capability checks** (`current_user_can('manage_options')`).
- **Sanitización** de inputs (`sanitize_text_field`, `esc_url_raw`, etc.).
- **Escape** de outputs (`esc_html`, `esc_attr`).
- El API token del backend se guarda en `wp_options` y **nunca** se expone al frontend.
- Todas las requests al backend son **server-side** (`wp_remote_post`).

### Compatibilidad
- WP ≥ 6.0
- PHP ≥ 7.4
- No romper con Rank Math, Fluent Forms, WP Rocket, Wordfence (los 4 conviven con el plugin).
- Usar `wp_enqueue_script` correctamente; no imprimir `<script>` inline sin `wp_add_inline_script`.

## 10. Buenas prácticas WordPress

- Prefijar todo: `solware_crm_*`, `SWCRM_*` para constantes, namespace `Solware\CRM` si usan OOP.
- No tocar la tabla `wp_posts`; usar `wp_options` o crear tabla propia (`wp_solware_crm_*`) solo si es estrictamente necesario.
- **Uninstall limpio:** borrar todas las options, transients y tablas propias en `uninstall.php`.
- **Activación/Desactivación:** usar `register_activation_hook` y `register_deactivation_hook`.
- **i18n:** todos los strings visibles con `__()` / `_e()` y text domain `solware-crm`.
- **Changelog** en `readme.txt` formato WordPress.
- Seguir [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/).

## 11. Checklist de testing

Antes de cada release:

- [ ] Instalación limpia desde WP Admin (.zip)
- [ ] Activación sin errores PHP ni JS
- [ ] Banner de consentimiento aparece en primera visita
- [ ] "Aceptar" → cookie `sw_vid` creada, eventos fluyen
- [ ] "Rechazar" → ninguna cookie, ningún evento enviado, Pixel no dispara
- [ ] "Configurar" → modal granular funciona
- [ ] Fluent Forms submit incluye `visitor_id` en el payload a backend
- [ ] Panel admin: todos los campos guardan y se leen correctamente
- [ ] PageSpeed mobile > 80 con el plugin activo
- [ ] Tamaño de `tracker.js` minificado ≤ 20 KB
- [ ] Desinstalación borra todas las options (verificar con `wp option get`)
- [ ] Compatible con Rank Math + WP Rocket + Wordfence activos
- [ ] Funciona en Chrome, Safari, Firefox, Edge (últimas 2 versiones)
- [ ] Responsive: banner y modal se ven bien en móvil

## 12. Distribución

- Sin publicar en WordPress.org (es un producto privado).
- Entregar como **.zip** generado desde GitHub Releases o build script.
- El cliente lo instala vía WP Admin → Plugins → Subir plugin → Activar.
- Actualizaciones: manuales vía subida de nuevo `.zip` en Fase 1 (automáticas con WP Auto-Update URL posterior).

## 13. Coordinación con el equipo del tema

- El tema (`wordpress-colegioae-crm`) **no debe conocer al plugin**. El plugin funciona con cualquier tema.
- El tema marca elementos rastreables con `data-track="nombre-evento"` — **esto es convención acordada**, el plugin la lee.
- El tema usa Fluent Forms para los formularios; el plugin engancha los hooks de Fluent Forms, no toca los formularios directamente.
- Si el tema necesita algo del plugin (por ejemplo, leer si hay consentimiento), el plugin debe exponer una función global: `solware_crm_has_consent()` → bool.
- Cualquier cambio en convenciones compartidas (`data-track`, nombres de cookies, funciones públicas) se coordina entre ambos equipos.

---

## Fases del proyecto (alineadas con el tema)

### Fase 1 (actual)
- Todo lo descrito en este documento.

### Fase 2
- Más eventos configurables desde admin (custom event builder).
- Dashboard de estadísticas en WP Admin (leer desde el backend).
- Múltiples pixeles (Google Ads, TikTok, etc.).

### Fase 3
- A/B testing básico.
- Sincronización bidireccional con el CRM (estados de lead visibles en WP).

---

**Dudas o cambios de alcance → coordinar con el arquitecto antes de implementar.**
