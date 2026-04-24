# CLAUDE.md — Tema WordPress: Colegio Albert Einstein

## Roles del equipo
- **Arquitecto (`@architect`):** entiende requisitos, define estructura, toma decisiones técnicas. No escribe código hasta tener aprobación completa.
- **Agente frontend:** desarrolla todo el código con buenas prácticas, una vez aprobada la arquitectura.

---

## Contexto del proyecto

Este tema es parte de un proyecto más grande: **CRM Solware**, plataforma de gestión de leads para instituciones educativas. El cliente piloto es el **Colegio Albert Einstein** (Perú).

El equipo solo es responsable del **tema WordPress** y el **plugin CRM** (repositorio separado). El resto del sistema (backend NestJS, frontend Next.js, infraestructura Docker) lo desarrolla otro equipo.

Documento de especificaciones completo: `CRM Solware — Especificaciones Técnicas del MVP Fase 1 v1.1`

---

## Repositorio

- **Tema:** https://github.com/solwarehz/wordpress-colegioae-crm (rama `main`)
- **Plugin CRM:** repositorio separado (pendiente de crear), entregable como `.zip` instalable desde WP Admin
- **Flujo de deploy:** Local → `git push` → GitHub → auto-deploy → Hostinger

---

## Decisiones técnicas confirmadas

| Decisión | Elección | Motivo |
|----------|----------|--------|
| Tipo de tema | Custom desde cero | Mayor control (se desvía del spec original que pedía base theme) |
| CSS | Sistema de diseño en CSS puro (custom properties) | Sin dependencias, moderno, mantenible |
| JavaScript | Vanilla JS, sin build tool | Simplicidad, archivos encolados directamente por WordPress |
| Build tool | Ninguno | Deploy directo desde Git |
| Page builder | Ninguno | Tema custom |
| Formularios | Fluent Forms (versión gratuita) | Recomendado en el spec, cubre todos los casos de uso |
| Menús | 3 menús administrables desde WP Admin | Ver detalle abajo |
| Plugin CRM | Repositorio y `.zip` independiente | Instalable sin tocar el tema |
| Tema visual | Light (default) + Dark con toggle manual | Preferencia en `localStorage`; sin auto-detección |
| Tipografía | Open Sans (títulos) + Roboto (cuerpo), self-hosted | Performance y privacidad (sin Google Fonts) |

---

## Estructura del sitio

### Páginas y templates

Todos los templates comparten `tokens.css` y componentes del sistema de diseño → **unidad visual** en colores, tipografía, espaciado y elementos interactivos.

| Página | Template | Diseño |
|--------|----------|--------|
| Home | `front-page.php` | One-page, navegación por anclajes a secciones |
| Blog (archivo) | `archive.php` | Diseño propio: grid de artículos, paginación WP nativa, filtro/búsqueda por etiquetas y categorías |
| Artículo (single) | `single.php` | Diseño propio de lectura: tipografía de artículo, metadatos, etiquetas |
| Documentos | `page-templates/page-documentos.php` | Listado de PDFs importantes del colegio |
| Libro de reclamaciones | `page-templates/page-libro-reclamaciones.php` | Template informativo: H1 destacado + párrafo explicativo + Fluent Forms. Contenedor angosto para que el formulario luzca atractivo. Requerido por Ley 29733 |
| Profesores (listado) | `page-templates/page-profesores.php` | Grid de **todos** los profesores con paginación WP nativa. `WP_Query` filtrando páginas con template `page-profesor.php`, orden por fecha DESC. Cada card: imagen destacada + nombre + extracto + link al perfil |
| Profesor (perfil individual) | `page-templates/page-profesor.php` | Muestra la información de **un solo profesor**. Foto arriba-izquierda con texto fluyendo alrededor (CSS `float` / wrap). Una página WP por cada profesor |
| Políticas de privacidad | `page.php` | Página genérica. Requerido por Ley 29733 |

### Secciones del Home (one-page)

1. **Header** — logo (desktop/tablet) / nombre del sitio desde `bloginfo('name')` (móvil) + menú principal + botón CTA "Contáctanos" + toggle light/dark
2. **Hero** — moderno con slides de fotos
3. **Nosotros / Conócenos** — texto sobre el colegio + video(s) embebidos desde YouTube (la sección "Videos" queda integrada aquí)
4. **Valores** — los 6 valores institucionales (Compromiso · Humanidad · Liderazgo · Excelencia · Adaptabilidad · Respeto) presentados como tarjetas/íconos
5. **Servicios** — niveles educativos (inicial, primaria, secundaria) u oferta educativa
6. **Sedes** — diferenciador de infraestructura. 3 sedes del colegio presentadas con fotos de sus niveles ofrecidos:
   - Sede 1: inicial, primaria, secundaria (3 fotos, una por nivel)
   - Sede 2: inicial, primaria (2 fotos)
   - Sede 3: 1 nivel (1 foto)
   Diseño elegante y moderno; tono del copy: *"entre las mejores de la ciudad de Huaraz"* (no se afirma "la más moderna")
7. **Profesores** — diferenciador. Grid de cards con los **últimos 5 profesores**, obtenidos vía `WP_Query` filtrando páginas con el template `page-profesor.php` (ordenadas por fecha DESC). Cada card usa la **imagen destacada** de la página del profesor + su título (nombre) + extracto corto, y enlaza al perfil completo
8. **Mentalidad ganadora** — diferenciador. Carrusel con movimiento (auto-rotate) mostrando las **últimas 5 publicaciones del blog**: **imagen destacada** del post + título + extracto corto. Refleja la filosofía: *"en los concursos en los que participamos, queremos ganar; si no ganamos damos pelea, no nos rendimos y seguimos preparándonos"*
9. **Opiniones** — testimonios de padres/alumnos
10. **Contáctanos** — formulario Fluent Forms con solo 4 campos: `nombre`, `email`, `celular`, `mensaje`

---

## Menús WordPress

Tres menús registrables desde WP Admin:

### 1. `menu-principal` → Header
- Anclajes a secciones del home (`#nosotros`, `#servicios`, `#contacto`, etc.)
- Enlace a página Documentos
- Enlace al Blog
- Incluye botón CTA "Contáctanos" que ancla a `#contacto`

### 2. `menu-secundario` → Footer (columna 2)
- Imagen característica del Libro de Reclamaciones SUNAT, enlaza a la página de libro de reclamaciones. La Ley 29733 exige que el "librito" se diferencie visualmente
- Otros enlaces institucionales (políticas de privacidad, etc.)

### 3. `menu-redes-sociales` → Footer (columna 3)
- El usuario crea ítems con los labels `facebook`, `instagram`, `tiktok`, `youtube` y apunta cada uno a su URL
- El Custom Nav Walker (`inc/social-nav-walker.php`) detecta el `title` del ítem (en minúsculas) y lo reemplaza por el SVG correspondiente + estilos

---

## Footer — estructura (Fase 1)

Tres columnas + barra inferior de copyright:

| Columna | Contenido |
|---------|-----------|
| 1 | Logo del cliente + texto corto |
| 2 | `menu-secundario` (imagen SUNAT + enlaces institucionales) |
| 3 | `menu-redes-sociales` (íconos por label) |

Debajo de las columnas, barra de copyright con año dinámico (se actualiza solo cada 1 de enero):

```php
© <?php echo date('Y'); ?> Colegio Albert Einstein. Todos los derechos reservados.
```

En Fase 1 el logo + texto corto van hardcodeados en `footer.php`. En Fase 2 se mueven al Customizer para edición desde WP Admin.

---

## Elementos globales

- **CTA "Contáctanos"** en el header → ancla `#contacto` de la home
- **Icono flotante de WhatsApp** → `https://wa.me/51981398282` (número oficial: Jose Pineda Sifuentes)
- **Año dinámico** en el copyright del footer vía `date('Y')` de PHP

---

## Copy inicial del sitio

Al construir cada sección se genera **texto de ejemplo** (placeholder realista, no Lorem Ipsum) basado en la información del brief del cliente. El cliente luego lo reemplazará con su copy definitivo desde WP Admin (Fase 2).

### Fuentes para generar el copy

- **Propósito / Círculo de Oro:** *"Formar estudiantes líderes con pensamiento crítico, valores sólidos y visión global, capaces de transformar su entorno con compromiso, innovación y excelencia académica."*
- **Buyer persona objetivo:** "Madre comprometida" de 35 años en Huaraz. Dolores: mala educación, influencias negativas, bajo rendimiento. Motivaciones: futuro exitoso para sus hijos, formación integral.
- **3 diferenciadores del colegio (mapeados a secciones del home):**
  - **Profesores** → sección "Profesores" (docentes capacitados, bloque de perfiles)
  - **Infraestructura** → sección "Sedes" (3 sedes, fotos de los niveles; tono honesto: "entre las mejores de Huaraz", no "la más moderna")
  - **Mentalidad ganadora** → sección con carrusel de blog (refleja cultura de no rendirse, seguir preparándose, participar en concursos)
- **6 valores institucionales:** Compromiso, Humanidad, Liderazgo, Excelencia, Adaptabilidad, Respeto.
- **Ubicación:** Huaraz, Perú.
- **Tono:** cercano, humano, confiable. No frío, no corporativo puro. Enfocado en resolver problemas del padre/madre (no en vender directamente).

### Categorías del blog

**No se crean categorías por adelantado.** Se irán creando orgánicamente conforme el cliente publique artículos.

---

## Estructura de archivos del tema

```
colegio-ae/
├── style.css                          ← metadatos del tema (no estilos)
├── functions.php                      ← setup, enqueue, register_nav_menus, theme support
├── index.php                          ← fallback requerido por WordPress
├── front-page.php                     ← home (one-page)
├── page.php                           ← template genérico de páginas estáticas
├── single.php                         ← post individual del blog (diseño propio)
├── archive.php                        ← listado del blog (paginación + filtros)
├── header.php                         ← header global
├── footer.php                         ← footer global (3 columnas + copyright)
│
├── page-templates/
│   ├── page-documentos.php            ← template de página Documentos
│   ├── page-libro-reclamaciones.php   ← template informativo (H1 + párrafo + form)
│   ├── page-profesores.php            ← listado grid de todos los profesores + paginación
│   └── page-profesor.php              ← template perfil profesor (foto + wrap text)
│
├── template-parts/
│   ├── home/
│   │   ├── hero.php
│   │   ├── nosotros.php             ← texto + videos embebidos (YouTube)
│   │   ├── valores.php
│   │   ├── servicios.php
│   │   ├── sedes.php                ← 3 sedes con fotos de niveles
│   │   ├── profesores.php
│   │   ├── mentalidad-ganadora.php  ← carrusel últimas 5 publicaciones del blog
│   │   ├── opiniones.php
│   │   └── contacto.php
│   └── global/
│       ├── nav.php
│       ├── social-menu.php
│       └── whatsapp-float.php         ← botón flotante de WhatsApp
│
├── inc/
│   └── social-nav-walker.php          ← Custom Walker para menu-redes-sociales
│
├── assets/
│   ├── css/
│   │   ├── tokens.css                 ← variables: colores, tipografía, espaciado, sombras
│   │   ├── reset.css                  ← reset moderno
│   │   ├── base.css                   ← tipografía base, utilitarios globales
│   │   ├── components/
│   │   │   ├── header.css
│   │   │   ├── hero.css
│   │   │   ├── nosotros.css
│   │   │   ├── valores.css
│   │   │   ├── servicios.css
│   │   │   ├── sedes.css
│   │   │   ├── profesores.css
│   │   │   ├── mentalidad-ganadora.css
│   │   │   ├── opiniones.css
│   │   │   ├── contacto.css
│   │   │   ├── footer.css
│   │   │   ├── documentos.css
│   │   │   ├── blog.css
│   │   │   ├── libro-reclamaciones.css
│   │   │   ├── profesor.css
│   │   │   ├── theme-toggle.css
│   │   │   └── whatsapp-float.css
│   │   └── main.css                   ← @import de todos los anteriores
│   │
│   ├── js/
│   │   ├── slider.js                  ← hero slides
│   │   ├── blog-carousel.js           ← carrusel de "Mentalidad ganadora" (auto-rotate)
│   │   ├── nav.js                     ← menú móvil + smooth scroll por anclajes
│   │   ├── theme-toggle.js            ← switch light/dark + persistencia en localStorage
│   │   └── main.js                    ← inicialización global
│   │
│   ├── fonts/                         ← Open Sans + Roboto self-hosted (woff2)
│   │
│   ├── images/
│   │   └── logo.png                   ← logo del colegio
│   │
│   └── icons/
│       ├── facebook.svg
│       ├── instagram.svg
│       ├── tiktok.svg
│       └── youtube.svg
│
├── screenshot.png
├── CLAUDE.md                          ← este archivo
└── README.md
```

---

## Sistema de diseño (tokens.css)

### Colores de marca (Colegio Albert Einstein)

| Rol | HEX | Uso |
|-----|-----|-----|
| Primario | `#004aad` (Azul) | CTAs, links, fondos destacados, header |
| Secundario | `#01aded` (Celeste) | Acentos, hovers, gradientes del hero |
| Alerta / énfasis | `#e30914` (Rojo) | Badges, estados de error, elementos puntuales de alta atención |
| Decorativo / premium | `#c2975c` (Dorado) | Ornamentos, bordes finos, iconos especiales |
| Base | `#ffffff` blanco / `#000000` negro | Fijas |

### Tipografía

| Uso | Familia |
|-----|---------|
| Títulos (h1–h6) | **Open Sans** |
| Cuerpo / párrafos | **Roboto** |

Fuentes **self-hosted** en `assets/fonts/` (no se cargan desde Google Fonts) para mejor performance y privacidad.

### Temas: Light y Dark

- **Light (por defecto):** fondo blanco + texto negro + colores de marca
- **Dark:** fondo gris muy oscuro + texto claro + colores de marca (mismas HEX; se ajustan tintes si hay problemas de contraste durante implementación)
- **Toggle manual** en el header; preferencia persistida en `localStorage`
- **Sin detección automática** de `prefers-color-scheme` — el default es light y el usuario decide cambiar

**Implementación:** atributo `data-theme="light|dark"` en `<html>`. `tokens.css` define variables para cada tema vía selector `[data-theme="dark"]`. Script inline al inicio de `<head>` lee `localStorage` antes del primer paint para evitar FOUC.

### Logo — estrategia responsive

- Logo en **PNG** (provisto por el cliente)
- **Desktop y tablet:** se muestra la imagen del logo
- **Móvil (< 768px):** se muestra solo el nombre del colegio, tomado dinámicamente de `bloginfo('name')` (Title configurado en WP Admin → Ajustes)

### Otros tokens

- **Espaciado:** escala de 4px (4, 8, 16, 24, 32, 48, 64, 96...)
- **Tipografía:** escala de tamaños, pesos (400, 600, 700), line-height
- **Sombras, radios de borde, transiciones**

### Manejo de imágenes

**Todas** las imágenes subidas por el cliente (hero slides, sedes, profesores, imágenes destacadas de blog, testimonios) se renderizan en contenedores con **aspect-ratio fijo** + **`object-fit: cover`**. Esto garantiza que cualquier imagen — sin importar su tamaño o proporción original — encaje perfectamente en su espacio sin deformarse ni romper el diseño.

**Patrón estándar:**

```css
.card-image {
  aspect-ratio: 4 / 3;           /* 16/9, 1/1, 3/4 según sección */
  overflow: hidden;
  border-radius: var(--radius-md);
  background: var(--color-neutral-100);
}
.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
}
```

**Ratios por sección (sugerencia inicial):**

| Sección | Aspect ratio |
|---------|--------------|
| Hero slider | 16 / 9 (desktop) · 4 / 5 (móvil) |
| Servicios (niveles) | 4 / 3 |
| Sedes (fotos por nivel) | 3 / 2 |
| Profesores (cards) | 1 / 1 (cuadrado) |
| Mentalidad ganadora (blog carousel) | 16 / 9 |
| Opiniones (avatar) | 1 / 1 |

**Fallback sin imagen destacada:** placeholder con gradiente de marca (Azul→Celeste) + ícono neutro. El fallback nunca rompe el layout ni deja el card vacío.

**Optimización:** usar `wp_get_attachment_image()` con tamaños registrados via `add_image_size()` para que WordPress genere variantes responsive (`srcset`) automáticamente. No servir siempre la imagen original.

### ✅ Assets del cliente

| Asset | Estado |
|-------|--------|
| Paleta de colores | ✅ Azul, Celeste, Rojo, Dorado |
| Tipografía | ✅ Open Sans (títulos) + Roboto (cuerpo) |
| Logo PNG | ✅ Estrategia responsive definida |

**No hay bloqueantes; la implementación del tema puede iniciar.**

---

## Plugin CRM (fuera de este repositorio)

Funcionalidades del plugin (spec v1.1 sección 8):
- Genera `visitor_id` (UUID) en cookie `sw_vid` (first-party, 1 año)
- Script de tracking vanilla JS ≤ 20KB: pageview, time_on_page, scroll_depth, click (data-track), form_start, form_abandon, form_submit
- Batch de eventos → `POST api.solware-pyme.com/api/tracking/events` cada 30-60s
- En form submit: agrega `visitor_id` al payload de Fluent Forms → `POST api.solware-pyme.com/api/leads/intake`
- Banner de consentimiento de cookies (Ley 29733 Perú): Aceptar / Rechazar / Configurar
- Si el usuario rechaza: NO genera `visitor_id`, NO envía eventos, NO dispara Facebook Pixel
- Panel de configuración en WP Admin

---

## Plugins WordPress del proyecto

| Plugin | Versión | Uso |
|--------|---------|-----|
| Fluent Forms | Gratuita | Formularios (contacto, reclamaciones, captación) |
| Rank Math | Gratuita | SEO |
| WP Rocket o similar | — | Caché y performance |
| Wordfence | Gratuita (ligera) | Seguridad |
| Plugin CRM Solware | Custom | Tracking + integración CRM |

---

## Objetivo de performance

- PageSpeed > 80 en mobile con el script de tracking instalado
- JS vanilla sin dependencias externas
- CSS sin framework externo

---

## Cumplimiento legal (Perú — Ley 29733)

- Página de Políticas de privacidad obligatoria
- Página de Libro de reclamaciones con template diferenciado + imagen SUNAT visible en footer
- Banner de consentimiento de cookies (gestionado por el plugin CRM)
- Sin datos de menores en Fase 1 (solo padres/madres)

---

## Fases del proyecto

### Fase 1 (actual)
- Tema WordPress completo con páginas, templates, menús y footer descritos
- Plugin CRM instalable como `.zip`
- WhatsApp flotante, CTA "Contáctanos", copyright con año dinámico

### Fase 2 — Personalización desde WP Admin

**Todo** el contenido de la página principal (home) + header + footer editable desde **Apariencia → Personalizar** (WordPress Customizer API), organizado en **paneles por sección** para que el cliente encuentre rápido lo que quiere editar sin tocar código ni entrar al Editor de páginas.

**Paneles del Customizer (uno por sección):**

| Panel | Controles |
|-------|-----------|
| **Header** | Logo (upload), alt del logo, label del CTA, URL/ancla del CTA, visibilidad del toggle light/dark |
| **Footer** | Logo columna 1 (puede diferir del header), texto corto de columna 1, texto de copyright (el año sigue siendo dinámico vía `date('Y')`) |
| **Hero** | Repeater de slides: imagen + título + subtítulo + botón opcional (texto + URL). Velocidad de auto-rotate |
| **Nosotros / Conócenos** | Título, párrafo(s), URL del video YouTube, título del video |
| **Valores** | Por cada valor (6): nombre, descripción corta, ícono. Título general de la sección |
| **Servicios** | Título de sección + repeater de niveles: nombre, descripción, imagen, link opcional |
| **Sedes** | Título de sección + repeater de sedes (hasta 3): nombre, dirección, niveles ofrecidos, grupo de fotos por nivel |
| **Profesores** | Título de sección, número de profesores a mostrar (default 5), texto y link al listado completo |
| **Mentalidad ganadora** | Título, descripción introductoria, número de posts a mostrar (default 5), velocidad del carrusel |
| **Opiniones** | Título de sección + repeater de testimonios: nombre, relación con el colegio, foto, texto |
| **Contáctanos** | Título, párrafo introductorio, Fluent Forms ID, email de notificación |
| **Global** | Número de WhatsApp, imagen SUNAT del libro de reclamaciones (columna 2 footer), override opcional de colores de marca |

**Notas de implementación:**
- **Customizer nativo** para campos simples (texto, imagen única, color, checkbox)
- Para **repeaters** (slides del hero, sedes, profesores destacados, testimonios, servicios): el Customizer nativo no tiene campos repetibles bien → evaluar **Kirki Customizer Framework** o **ACF (Advanced Custom Fields)** con integración Customizer. Decisión definitiva se toma al entrar a Fase 2.
- **Preview en vivo** con `selective refresh` o `postMessage` transport donde tenga sentido
- Los valores se guardan como **theme mods** (`get_theme_mod()`) o en `wp_options`
- **Sanitize + escape** en todos los callbacks (seguridad obligatoria)

### Fase 3 — SEO personalizable
- Meta title, meta description, meta keywords por página
- Open Graph tags
- Probablemente delegado a Rank Math + overrides custom donde haga falta
