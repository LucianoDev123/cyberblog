# CyberBlog - CSS segmentado

Esta versión divide el `style.css` monolítico del FrontOffice sin modificar los CSS específicos del panel administrativo.

## Archivos

- `style.css`: punto de entrada del FrontOffice; mantiene el orden de la cascada mediante `@import`.
- `base.css`: variables globales, reset y `.container`.
- `components.css`: header, marca, navegación, accesos y estructura global hasta antes del Hero.
- `home.css`: Inicio, Hero, terminal, topics y about.
- `footer.css`: footer y sus estados.
- `blog.css`: listado público de artículos y empty state.
- `article.css`: detalle público de artículo.
- `auth.css`: Login y estilos adicionales del nuevo flujo de autenticación.
- `search.css`: buscador público.
- `responsive.css`: responsive general existente.
- `series.css`: listado y detalle público de Series.
- `overrides.css`: ajustes compactos que originalmente estaban al final para prevalecer sobre reglas anteriores.
- `hacker-path.css`: Hacker Path.
- `account.css`: Mi cuenta.

## CSS administrativo

NO reemplazar ni modificar con este paquete:

- `admin.css`
- `dashboard.css`
- `users.css`
- `articles.css`
- `article-create.css`
- `categories.css`
- `security-analyzer.css`

## Instalación

1. Hacer backup del `public/assets/css/style.css`.
2. Copiar estos archivos a `public/assets/css/`.
3. Mantener los CSS administrativos existentes.
4. Reconstruir el contenedor porque los archivos se copian en la imagen:

```bash
docker compose build cyberblog
docker compose up -d cyberblog
```

## Nota

El orden de los imports sigue el orden del `style.css` original. El objetivo de esta primera etapa es reducir el tamaño lógico del archivo sin alterar la cascada. Más adelante se puede optimizar la carga por vista, pero no se hace automáticamente en esta etapa para evitar cambios visuales accidentales.
