<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CustomTheme
 */

$bodyclass='archive';
get_header();

?>
<main class="main-content">

	<?php get_template_part('template-parts/pageheader', null, ['htag' => 0]); ?>

	<div class="container my-5">
		<div class="row">
			<?php 
			if (have_posts()) : 
				while (have_posts()) : the_post();
					get_template_part('template-parts/components/card-cpt', null, [
						'title' => get_the_title(),
						'excerpt' => wp_trim_words(get_the_excerpt(), 20),
						'image_url' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
						'permalink' => get_permalink(),
						'class-col' => 'col-md-6 col-lg-4 col-xl-3',
				]);
				endwhile;
			else :
				echo '<p class="text-center">No se han encontrado publicaciones.</p>';
			endif;
			?>
		</div>
	</div>

</main>

<?php get_footer(); ?>
