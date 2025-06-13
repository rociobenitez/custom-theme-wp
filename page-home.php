<?php
/**
 * Template Name: Home
 */

if ( ! defined('ABSPATH') ) exit;

$bodyclass='page-home';

get_header();

// Obtener campos ACF
$fields = function_exists('get_fields') ? get_fields() : [];

// Hero (si existe fondo imagen o video)
if ( ! empty( $fields['hero_background_image'] ) 
  || ! empty( $fields['hero_background_video'] ) ) {
  get_template_part( 'template-parts/blocks/hero', null, $fields );
}

// Slider
if ( ! empty( $fields['slides'] ) 
  && is_array( $fields['slides'] ) ) {
  get_template_part( 'template-parts/blocks/slider', null, [ 'slides' => $fields['slides'] ] );
}

// Bloques flexibles ACF
if ( ! empty( $fields['flexible_content'] )
  && is_array( $fields['flexible_content'] ) ) {
  BlockLoader::load( $fields['flexible_content'] );
}

get_footer();
