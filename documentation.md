# Documentación del Theme personalizado

<div class="notecard note">
  <p><strong>Nota:</strong> Teheme en desarrollo.</p>
  <p>Documentación en curso...</p>
</div>

---

### Requisitos de Imágenes y Recursos Gráficos

#### Formatos Aceptados y Recomendaciones

- **Formatos**:
  - **Imágenes Generales**: JPG, PNG, **WebP** (recomendado para optimización).
  - **Logos e Iconos**: **SVG** (sanear el archivo antes de subirlo para mayor seguridad).
- **Ubicación de Recursos**:
  - Coloca las imágenes en la carpeta `assets/img/`.
  - **Logotipo principal**: `assets/img/logo.svg`
  - **Fondo de login**: `assets/img/login-background.jpg`

> **Nota**: Para un rendimiento óptimo, utiliza herramientas de compresión de imágenes.

#### Tamaños de Imagen Personalizados

Este theme define varios tamaños de imagen para optimizar la visualización en dispositivos y secciones del sitio:

- **banner_large**: 1600x700px, optimizado para sliders y secciones hero de ancho completo.
- **banner_small** (opcional): 1600x350px, para banners de menor altura.
- **gallery_thumbnail**: 400x400px, para miniaturas en galerías o grids.

#### Buenas Prácticas para Optimización de Imágenes

- **Usar Formato WebP**: Siempre que sea posible, utiliza el formato WebP en el front-end para mejorar la velocidad de carga.
- **Optimización de Imágenes**: Reduce el peso de las imágenes con herramientas de compresión como Imagify o similares para mejorar el rendimiento.
- **Lazy Loading**: Habilita el lazy loading en imágenes para cargar de forma diferida y mejorar la experiencia de usuario.

### Funcionalidades Adicionales del Theme para Imágenes

1. **Filtro para el Selector de Tamaños de Imagen en Medios**: Los nombres de los tamaños de imagen personalizados están disponibles en el selector de medios de WordPress, proporcionando a los editores una selección rápida y precisa del tamaño adecuado al insertar imágenes.
2. **Soporte para Formatos SVG y WebP**: Este theme permite la carga de formatos SVG y WebP para optimizar la representación de gráficos y mejorar la compresión de imágenes.

> **Registro de Tamaños de Imagen Personalizados para CPT**: Si se crean Custom Post Types (CPT), se pueden definir tamaños de imagen específicos para mejorar la carga y optimización de recursos.

---

## Menús

### Menú Principal

- **Profundidad de Anidación**: El menú está preparado para manejar hasta **2 niveles de anidación**. Esta configuración es ideal para mantener la navegación sencilla y accesible.
- **Menús de Más Niveles**: Si se requiere mayor profundidad en la navegación, se recomienda usar un **plugin de megamenú** que ofrezca funcionalidades avanzadas y opciones de personalización.

**Nota**: Para proyectos que incluyan una navegación compleja, un plugin de megamenú puede ofrecer una mejor experiencia de usuario al permitir una estructura de menú más visualmente organizada y modular.

### Manejo de Menús Desplegables

El theme incluye una clase personalizada `Bootstrap_NavWalker` para integrar menús de WordPress con Bootstrap 5. Esto permite usar submenús y menús desplegables automáticamente. El archivo `class-bootstrap-navwalker.php` se encuentra en `inc/` y debe ser incluido en `functions.php` para funcionar correctamente.

---

### Reglas de reescritura para URLs de autor

Este paso personaliza las URLs de los perfiles de autor para que utilicen la estructura `equipo/nombre-del-autor`. Si prefieres no implementar esta reescritura, puedes comentar o eliminar el código correspondiente en `rewrite-rules.php`.

**Pasos:**

1. Asegúrate de que el archivo `rewrite-rules.php` esté en la carpeta `inc` y correctamente incluido en `functions.php`.
2. Este archivo utiliza los campos de opciones de ACF para redirigir o desactivar la página de perfil del autor, en función de la configuración del campo `desactivar_pagina_de_autor` en los campos de usuario.
3. Para que funcione correctamente, verifica que los campos de opciones de autor estén activos y disponibles a través de su archivo JSON (`acf-json/group_author.json`).
4. Si decides implementar esta función, guarda nuevamente los enlaces permanentes en WordPress (Ajustes > Enlaces permanentes) para aplicar las reglas de reescritura.

---

## Archivos

### Archivo: `inc/helpers.php`

Contiene funciones de ayuda y utilidades generales, como la generación de encabezados HTML personalizados.

### Archivo: `inc/cleanup.php`

Optimiza y limpia el `wp_head`, eliminando etiquetas y enlaces innecesarios para mejorar la seguridad y el rendimiento del sitio.

### Archivo: `inc/enqueue.php`

- Carga y organiza todos los scripts y estilos del theme.
- Carga las Google Fonts de forma dinámica en función de la selección de fuentes realizada en el Customizer.
- Genera el CSS dinámico en línea usando las opciones del Customizer para aplicar tipografía y colores globales.

### Archivo: `inc/customizer.php`

- Define opciones generales en el Theme Customizer de WordPress.
- Añade opciones de fuentes (para títulos y cuerpo) y colores personalizables.
- Permite que los usuarios seleccionen y personalicen la apariencia general de la web (tipografía y colores) desde el backend.

### Archivo: `inc/customizer.js`

- Actualiza en tiempo real los cambios realizados en el Customizer.
- Escucha las modificaciones en las opciones de fuentes y colores, aplicando los valores en la previsualización en vivo sin recargar la página.

### Archivo: `inc/default-pages.php`

- Crea páginas predeterminadas como Aviso legal, Política de privacidad, Política de cookies, Contacto y Quiénes somos.
- **Personalización:**
  - Si deseas personalizar el contenido o revisar qué páginas se crean, es importante que verifiques este archivo antes de activarlo.
  - Comprueba también si estas páginas tienen plantillas asociadas, especialmente en page-contact.php y page-about.php.

### Archivo: `inc/default-content.php`

- Entradas de prueba por defecto para la etapa de desarrollo que serán eliminadas cuando se suba el contenido definitivo.
