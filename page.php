<?php
/**
 * Default template
 *
 * @package custom_theme
 */

$bodyclass  = 'page-default';

get_header();

// Obtener campos ACF
$fields = function_exists('get_fields') ? get_fields() : [];

// Page Header
get_template_part( 'template-parts/pageheader' );

// Bloques flexibles ACF
if ( ! empty( $fields['flexible_content'] )
&& is_array( $fields['flexible_content'] ) ) {
	BlockLoader::load( $fields['flexible_content'] );
}

get_footer();
