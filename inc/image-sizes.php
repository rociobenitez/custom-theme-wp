<?php
/**
 * Tamaños de imagen personalizados y soporte de formatos para el theme
 *
 * @package CustomTheme
 */

/**
 * Registra tamaños de imagen personalizados necesarios para el diseño del theme.
 */
function register_custom_image_sizes() {
   // Tamaños optimizados para bloques pequeños, medianos y grandes.
   add_image_size('block_small', 480, 400, false);  // Para bloques pequeños (e.g., cards, grids).
   add_image_size('block_medium', 768, 400, false); // Para bloques medianos (e.g., banners pequeños).
   add_image_size('block_large', 1200, 400, false); // Para bloques grandes (e.g., sliders, hero).

   // Tamaño cuadrado para miniaturas de galerías.
   add_image_size('gallery_thumbnail', 400, 400, false);

   // Tamaño para fondos de cards, con relación 2:1.
   add_image_size('card_background', 520, 260, false);
}
add_action('after_setup_theme', 'register_custom_image_sizes');

/**
 * Elimina tamaños de imagen predeterminados de WordPress que no se utilizan en el theme.
 */
function disable_unused_image_sizes() {
   remove_image_size('1536x1536'); // Tamaños generados automáticamente por WordPress
   remove_image_size('2048x2048'); // Tamaños generados automáticamente por WordPress
}
add_action('init', 'disable_unused_image_sizes');

/**
* Añade nombres descriptivos para los tamaños de imagen personalizados en el selector de medios de WordPress.
*
* @param array $sizes Lista de tamaños de imagen disponibles.
* @return array Lista de tamaños de imagen con los personalizados añadidos.
*/
function custom_image_size_names($sizes) {
   return array_merge($sizes, array(
      'block_small'       => __('Bloque pequeño (480x400)', THEME_TEXTDOMAIN),
      'block_medium'      => __('Bloque mediano (768x400)', THEME_TEXTDOMAIN),
      'block_large'       => __('Bloque grande (1200x400)', THEME_TEXTDOMAIN),
      'gallery_thumbnail' => __('Miniatura de galería (400x400)', THEME_TEXTDOMAIN),
      'card_background'   => __('Fondo de card (520x260)', THEME_TEXTDOMAIN),
   ));
}
add_filter('image_size_names_choose', 'custom_image_size_names');

/**
* Permite la carga de formatos de imagen adicionales como SVG y WebP.
*
* @param array $mimes Lista de tipos de archivo permitidos.
* @return array Lista actualizada de tipos de archivo permitidos con SVG y WebP añadidos.
*/
function custom_mime_types($mimes) {
   $mimes['svg'] = 'image/svg+xml'; // Permite subir SVG
   $mimes['webp'] = 'image/webp'; // Permite subir WebP
   return $mimes;
}
add_filter('upload_mimes', 'custom_mime_types');

/**
 * Desactiva la generación de tamaños automáticos no deseados por WordPress.
 */
function disable_unused_default_sizes($sizes) {
   unset($sizes['medium_large']); // Tamaño 'medium_large'.
   unset($sizes['1536x1536']);    // Tamaño predeterminado de alta resolución.
   unset($sizes['2048x2048']);    // Tamaño predeterminado de alta resolución.
   return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'disable_unused_default_sizes');
