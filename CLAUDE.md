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

---

## Estructura del sitio

### Páginas

| Página | Tipo | Notas |
|--------|------|-------|
| Home | One-page | Navegación por anclajes a secciones |
| Documentos | Página estática | PDFs importantes del colegio |
| Blog | Archivo WordPress nativo | Artículos / noticias |
| Libro de reclamaciones | Página con formulario | Fluent Forms → envío al backend (email) |
| Políticas de privacidad | Página estática | Requerido por Ley 29733 Perú |

### Secciones del Home (one-page)

1. **Header** — logo + menú principal + botón CTA
2. **Hero** — moderno con slides de fotos
3. **Nosotros** — sobre el colegio
4. **Servicios** — niveles educativos u oferta educativa
5. **Videos**
6. **Profesores** — presentación de algunos docentes
7. **Opiniones** — testimonios
8. **Contáctanos**

---

## Tres menús WordPress

### 1. `menu-principal` → Header
- Anclajes a secciones del home (`#nosotros`, `#servicios`, etc.)
- Enlace a página Documentos
- Enlace al Blog

### 2. `menu-footer` → Footer
- Libro de reclamaciones
- Políticas de privacidad
- Otros enlaces institucionales importantes

### 3. `menu-redes-sociales` → Footer
- Ítems: `facebook`, `instagram`, `tiktok`, `youtube`
- El tema detecta el `name` del ítem de menú y asigna automáticamente el ícono SVG + estilos correspondientes
- Implementado con Custom Nav Walker en `inc/social-nav-walker.php`

---

## Estructura de archivos del tema

```
colegio-ae/
├── style.css                      ← metadatos del tema (no estilos)
├── functions.php                  ← setup, enqueue, register_nav_menus, theme support
├── index.php                      ← fallback requerido por WordPress
├── front-page.php                 ← home (one-page)
├── page.php                       ← template genérico de páginas estáticas
├── single.php                     ← post individual del blog
├── archive.php                    ← listado del blog
├── header.php                     ← header global
├── footer.php                     ← footer global
│
├── page-templates/
│   └── page-documentos.php        ← template de página Documentos
│
├── template-parts/
│   ├── home/
│   │   ├── hero.php
│   │   ├── nosotros.php
│   │   ├── servicios.php
│   │   ├── videos.php
│   │   ├── profesores.php
│   │   ├── opiniones.php
│   │   └── contacto.php
│   └── global/
│       ├── nav.php
│       └── social-menu.php
│
├── inc/
│   └── social-nav-walker.php      ← Custom Walker para menu-redes-sociales
│
├── assets/
│   ├── css/
│   │   ├── tokens.css             ← variables: colores, tipografía, espaciado, sombras
│   │   ├── reset.css              ← reset moderno
│   │   ├── base.css               ← tipografía base, utilitarios globales
│   │   ├── components/
│   │   │   ├── header.css
│   │   │   ├── hero.css
│   │   │   ├── nosotros.css
│   │   │   ├── servicios.css
│   │   │   ├── videos.css
│   │   │   ├── profesores.css
│   │   │   ├── opiniones.css
│   │   │   ├── contacto.css
│   │   │   ├── footer.css
│   │   │   ├── documentos.css
│   │   │   └── blog.css
│   │   └── main.css               ← @import de todos los anteriores
│   │
│   ├── js/
│   │   ├── slider.js              ← hero slides
│   │   ├── nav.js                 ← menú móvil + smooth scroll por anclajes
│   │   └── main.js                ← inicialización global
│   │
│   └── icons/
│       ├── facebook.svg
│       ├── instagram.svg
│       ├── tiktok.svg
│       └── youtube.svg
│
├── screenshot.png
├── CLAUDE.md                      ← este archivo
└── README.md
```

---

## Sistema de diseño (tokens.css)

Variables CSS para:
- **Colores:** primario, secundario, acento, neutros, blanco, negro, estados (error, éxito, advertencia)
- **Tipografía:** familias, escala de tamaños, pesos, line-height
- **Espaciado:** escala de 4px (4, 8, 16, 24, 32, 48, 64, 96...)
- **Sombras, radios de borde, transiciones**

### ⚠️ BLOQUEANTE — Pendiente del cliente

| Asset | Estado |
|-------|--------|
| Color primario institucional | ⏳ Pendiente |
| Color secundario / acento | ⏳ Pendiente |
| Tipografía institucional | ⏳ Pendiente |
| Logo (SVG o PNG alta calidad) | ⏳ Pendiente |

**No se escribe código hasta tener estos assets del Colegio Albert Einstein.**

Blanco (`#ffffff`) y negro (`#000000`) son base fija en todos los casos.

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
- Página de Libro de reclamaciones con formulario
- Banner de consentimiento de cookies (gestionado por el plugin CRM)
- Sin datos de menores en Fase 1 (solo padres/madres)
