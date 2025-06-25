<?php
/**
 * La plantilla para mostrar 404 páginas (no encontrada)
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 */

if ( ! defined('ABSPATH') ) exit;

get_header(); ?>

<section class="error-404 py-5 w-100">
	<div class="col-md-6 mx-auto text-center">
		<div class="page-404-header">
			<h1 class="c-primary display-1 mb-2 fw800"><?php esc_html_e( '404', CTM_TEXTDOMAIN ); ?></h1>
			<p class="heading-3 c-dark lh140 mb-3"><?php esc_html_e( 'Página no encontrada', CTM_TEXTDOMAIN ); ?></p>
		</div>
		<div class="page-content">
			<div class="lh160 fs18 mb-0">
				<p><?php esc_html_e( 'La página que buscas no está disponible.', CTM_TEXTDOMAIN ); ?></p>
			</div>
			<a href="/" class="btn btn-md btn-default mt-2 px-5"><?php esc_html_e( 'Ir al inicio', CTM_TEXTDOMAIN ); ?></a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
