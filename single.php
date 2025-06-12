<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package custom_theme
 */

get_header();

$bodyclass   = 'page-single';
$fields      = get_fields();
$title_h1    = $fields['title_h1'] ?? get_the_title();
$tagline_h1  = $fields['tagline_h1'] ?? '';
$all_content = '';

// Inicia la consulta de los posts del autor
$author              = get_queried_object();
$author_id           = get_post_field('post_author', get_the_ID());
$author_first_name   = get_the_author_meta('first_name', $author_id);
$author_last_name    = get_the_author_meta('last_name', $author_id);
$author_full_name    = trim($author_first_name . ' ' . $author_last_name);
$author_image        = get_field('photo', 'user_' . $author_id);
$author_description  = get_field('description', 'user_' . $author_id);
$disable_author_page = get_field('disable_author_page', 'user_' . $author_id);

get_header();
?>
<main id="primary" class="main-content">
	<?php get_template_part('template-parts/pageheader', null, ['htag' => 0]); ?>

	<?php 
	// Muestra el H1 y el contenido principal de la página
	if (have_posts()) : 
		while (have_posts()) : the_post(); ?>
			<div class="bg-light-subtle py-5 shadow-sm">
				<div class="container">
					<div class="page-content">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		<?php
		endwhile;
	endif;
	?>
	<div class="container">
		<div class="row">
			<!-- Contenido Principal -->
			<div class="col-lg-9 lg-5 mt-3 mb-5 ps-lg-4 order-lg-2">
				<?php
				// Cargar bloques flexibles dinámicamente y capturar el contenido
				require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
				$flexible_content = load_flexible_blocks($fields['flexible_content'], true);

				// Generar el array de TOC items
				$toc_items = generate_table_of_contents_items($flexible_content);

				// Modificar el contenido para añadir IDs a los encabezados
				$content_with_ids = add_ids_to_headings($flexible_content);

				// Mostrar el contenido y pasar los TOC items al sidebar
				echo $content_with_ids;

				// Añadir la firma del autor al final del post
				$author_template = 'template-parts/blocks/author-post.php';
				if (file_exists(get_stylesheet_directory() . '/' . $author_template)) {
					include locate_template($author_template);
				}
				?>
			</div>

			<?php
			// Incluir el sidebar con los TOC items
			get_template_part('template-parts/sidebar', null, [
				'toc_items' => $toc_items,
				'id_form' => 2,
			]);
			?>

		</div>
	</div>
	
</main>
<?php get_footer(); ?>
