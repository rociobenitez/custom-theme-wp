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

## Archivos

### Archivo: `enqueue.php`

- Carga y organiza todos los scripts y estilos del theme.
- Carga las Google Fonts de forma dinámica en función de la selección de fuentes realizada en el Customizer.
- Genera el CSS dinámico en línea usando las opciones del Customizer para aplicar tipografía y colores globales.

### Archivo: `customizer.php`

- Define opciones generales en el Theme Customizer de WordPress.
- Añade opciones de fuentes (para títulos y cuerpo) y colores personalizables.
- Permite que los usuarios seleccionen y personalicen la apariencia general de la web (tipografía y colores) desde el backend.

### Archivo: `customizer.js`

- Actualiza en tiempo real los cambios realizados en el Customizer.
- Escucha las modificaciones en las opciones de fuentes y colores, aplicando los valores en la previsualización en vivo sin recargar la página.
