<?php
/**
 * Template Name: Home
 */

 $bodyclass='page-home';

 get_header();
 
 get_template_part( 'template-parts/blocks/hero' );
 get_template_part( 'template-parts/blocks/alternating-block' );
 get_template_part( 'template-parts/blocks/features-block' );
 get_template_part( 'template-parts/blocks/services-block' );
 get_template_part( 'template-parts/blocks/specialties-block' );
 get_template_part( 'template-parts/blocks/services-block' );
 
 // $fields = get_fields();
 // set_query_var('fields', $fields);
 
 // if ($fields) { 
     
 //     get_template_part( 'template-parts/blocks/hero' );
 
 //     if (isset($fields['bloques_flexibles']) && is_array($fields['bloques_flexibles'])) {
 //         foreach ($fields['bloques_flexibles'] as $bloque) {
 //             if (isset($bloque['acf_fc_layout']) && !empty($bloque['acf_fc_layout'])) {
 //                 $block_file = 'template-parts/blocks/' . $bloque['acf_fc_layout'] . '.php';
 
 //                 if (file_exists(get_stylesheet_directory() . '/' . $block_file)) {
 //                     include locate_template($block_file);
 //                 }
 //             }
 //         }
 //     }
 // }
 
 get_footer();