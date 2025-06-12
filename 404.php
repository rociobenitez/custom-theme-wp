<?php
/**
 * La plantilla para mostrar 404 páginas (no encontrada)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package custom_theme
 */

// Variables globales
$bodyclass = 'page-404';
get_header();

// Opciones de ACF
$options        = get_fields('option');
$title_404      = $options['404_title'] ?: '404';
$subtitle_404   = $options['404_subtitle'] ?: 'Ups, parece que esta página no existe.';
$content_404    = $options['404_content'] ?
	: '<p>No encontramos la página que estás buscando. Puede que el enlace esté roto o que la página haya sido eliminada.</p><p>No te preocupes, puedes volver al <a href="/">Inicio</a> o explorar otras secciones de nuestro sitio.</p>';

$button_404     = $options['404_button'] ?: '';
$image_404      = $options['404_image'] ? $options['404_image']['url'] : get_template_directory_uri() . '/assets/img/default-404.svg';
$img_alt_404    = $button_404 ? $button_404['title'] : 'Error 404';
$img_target_404 = $button_404 ? $button_404['target'] : '_self';
?>

<main class="d-flex align-items-center justify-content-center">
	<section class="error-404 not-found container mx-auto pt-5 pb-md-5" data-aos="fade-up">
		<div class="row justify-content-between align-items-center">
			<!-- Columna de Texto -->
			<div class="col-md-6">
				<div class="page-404-header">
					<h1 class="c-primary display-1 mb-2 fw800"><?php echo esc_html($title_404); ?></h1>
					<p class="heading-4 lh140 mb-3"><?php echo esc_html($subtitle_404); ?></p>
				</div>
				<div class="page-content">
					<div class="lh160 fs18 mb-0">
						<?php echo $content_404; ?>
					</div>
					<?php if (!empty($img_alt_404) && !empty($image_404)): ?>
						<a href="<?php echo esc_url($image_404); ?>" class="btn btn-lg btn-default mt-2">
							<?php echo esc_html($img_alt_404); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<!-- Columna de Imagen -->
			<div class="col-md-6 ps-md-5 d-flex align-items-end">
				<img src="<?php echo esc_url($image_404); ?>" class="w-100" alt="<?php echo esc_attr($img_alt_404); ?>" target="<?php echo esc_attr($img_target_404); ?>">
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
