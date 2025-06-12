<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package custom_theme
 */

$bodyclass  = 'page-default';

get_header();
$fields = get_fields(); 

?>

<main id="primary" class="main-content">

	<?php 
	get_template_part('template-parts/pageheader', null, [
		'pageheader_style' => 'cols',
	]);

	// Cargar bloques flexibles dinámicamente
	require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
	load_flexible_blocks($fields['flexible_content']);
	?>

</main>

<?php get_footer(); ?>
