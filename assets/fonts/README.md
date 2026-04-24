# Fuentes self-hosted

Esta carpeta debe contener los archivos `.woff2` de **Open Sans** y **Roboto**.

## Cómo obtenerlos

1. Ir a https://gwfh.mranftl.com/fonts (google-webfonts-helper)
2. Buscar **Open Sans** → seleccionar pesos `400, 600, 700`, formato moderno (`.woff2`). Descargar.
3. Buscar **Roboto** → seleccionar pesos `400, 700`, formato moderno (`.woff2`). Descargar.

## Nombres exactos esperados

Renombrar los archivos descargados para que coincidan con los declarados en `assets/css/fonts.css`:

- `open-sans-400.woff2`
- `open-sans-600.woff2`
- `open-sans-700.woff2`
- `roboto-400.woff2`
- `roboto-700.woff2`

## Comportamiento mientras faltan

Si los archivos aún no están presentes, el navegador cae al stack de fallback (`system-ui, -apple-system, Segoe UI, sans-serif`) sin error. El sitio funciona, solo no se ve con la tipografía definitiva.
