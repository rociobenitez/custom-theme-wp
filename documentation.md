# Documentación del Theme personalizado

<div class="notecard note">
  <p><strong>Nota:</strong> Teheme en desarrollo.</p>
  <p>Documentación en curso...</p>
</div>

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
