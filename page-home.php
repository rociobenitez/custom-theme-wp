<?php
/**
 * Template Name: Home
 */

$bodyclass='page-home';

get_header();

$fields = get_fields();
set_query_var('fields', $fields);

/**
 * Cargar el bloque Hero
 */
if (isset($fields['hero_main_title']) && $fields['hero_main_title']) {
   $hero_template = 'template-parts/blocks/hero.php';
   if (file_exists(get_stylesheet_directory() . '/' . $hero_template)) {
       include locate_template($hero_template);
   } else {
       error_log('El archivo hero.php no se encontró en template-parts/blocks/');
   }
}

/**
 * Cargar el bloque Slider
 */
if (isset($fields['slides']) && is_array($fields['slides'])) {
   $slider_template = 'template-parts/blocks/slider.php';
   if (file_exists(get_stylesheet_directory() . '/' . $slider_template)) {
       include locate_template($slider_template);
   } else {
       error_log('El archivo slider.php no se encontró en template-parts/blocks/');
   }
}

// Cargar bloques flexibles dinámicamente
require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
load_flexible_blocks($fields['flexible_content']);

// Incluir el pie de página del tema
get_footer();