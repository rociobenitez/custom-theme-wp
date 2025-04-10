<?php
/**
 * Template Name: Home
 */

$bodyclass='page-home';

get_header();

/**
 * Cargar los datos de la página actual
 * Se usa ACF para obtener los valores y pasarlos a la plantilla
 */
$fields = get_fields();
set_query_var('fields', $fields);

if ( !is_array($fields) ) {
    $fields = [];
    error_log('No se encontraron campos personalizados.');
}

/**
 * Cargar el bloque Hero
 * Verificar si existe y si tiene contenido
 */
if ( !empty( $fields['hero_background_image'] ) || !empty( $fields['hero_background_video'] ) ) {
   $hero_template = 'template-parts/blocks/hero.php';

   if ( file_exists( get_stylesheet_directory() . '/' . $hero_template ) ) {
       include locate_template( $hero_template );
   } else {
       error_log('El archivo hero.php no se encontró en template-parts/blocks/');
   }
}

/**
 * Cargar el bloque Slider
 * Verificar si 'slides' es un array válido con contenido
 */
if ( !empty( $fields['slides'] ) && is_array( $fields['slides'] ) ) {
   $slider_template = 'template-parts/blocks/slider.php';
   if ( file_exists( get_stylesheet_directory() . '/' . $slider_template ) ) {
       include locate_template( $slider_template );
   } else {
       error_log('El archivo slider.php no se encontró en template-parts/blocks/');
   }
}

/**
 * Cargar bloques flexibles dinámicamente
 * Verificar si existen y si tienen contenido
 */
if (!empty($fields['flexible_content']) && is_array($fields['flexible_content'])) {
    require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
    load_flexible_blocks($fields['flexible_content']);
} else {
    error_log('No hay contenido flexible definido en ACF para esta página.');
}

get_footer();
