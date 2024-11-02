<?php
/**
 * Template Name: Home
 */

$bodyclass='page-home';

get_header();

$fields = get_fields();
set_query_var('fields', $fields);

// Cargar el bloque Hero
if (file_exists(get_stylesheet_directory() . '/template-parts/blocks/hero.php')) {
   include locate_template('template-parts/blocks/hero.php');
}

// Cargar bloques flexibles dinámicamente
if ($fields && isset($fields['flexible_content']) && is_array($fields['flexible_content'])) {
   foreach ($fields['flexible_content'] as $bloque) {
      if (isset($bloque['acf_fc_layout']) && !empty($bloque['acf_fc_layout'])) {
         $block_file = 'template-parts/blocks/' . $bloque['acf_fc_layout'] . '.php';

         if (file_exists(get_stylesheet_directory() . '/' . $block_file)) {
               include locate_template($block_file);
         }
      }
   }
}

get_footer();