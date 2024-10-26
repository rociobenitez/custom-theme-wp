<?php
/**
 * Tamaños de imagen personalizados y soporte de formatos para el theme
 *
 * @package NombreTheme
 */

/**
 * Registra tamaños de imagen personalizados necesarios para el diseño del theme.
 */
function register_custom_image_sizes() {
   // Tamaño principal para hero/slider o banner grande en desktop.
   add_image_size('banner_large', 1600, 700, true);
   
   // Tamaño opcional de banner intermedio (solo si se requiere menor altura).
   add_image_size('banner_small', 1600, 350, true);

   // Tamaño para miniaturas en galerías o grids.
   add_image_size('gallery_thumbnail', 400, 400, true);
}
add_action('after_setup_theme', 'register_custom_image_sizes');

/**
 * Elimina tamaños de imagen de WordPress predeterminados que no se utilizan en el theme.
 */
function disable_unused_image_sizes() {
   remove_image_size('1536x1536');
   remove_image_size('2048x2048');
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
       'banner_large'      => __('Banner grande / Slider', THEME_TEXTDOMAIN),
       'banner_small'      => __('Banner pequeño', THEME_TEXTDOMAIN),
       'gallery_thumbnail' => __('Galería o Grids', THEME_TEXTDOMAIN),
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
   $mimes['svg'] = 'image/svg+xml';
   $mimes['webp'] = 'image/webp';
   return $mimes;
}
add_filter('upload_mimes', 'custom_mime_types');
