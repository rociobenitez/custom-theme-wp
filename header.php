<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CustomTheme
 */

global $bodyclass;
$bodyclass = isset($bodyclass) ? $bodyclass : 'default-body';

// Obtener el valor de los campos de opciones
$options = get_fields('option');
$phone = isset($options['phone']) ? $options['phone'] : '';
$email = isset($options['email']) ? $options['email'] : '';
$whatsapp = isset($options['whatsapp']) ? $options['whatsapp'] : '';

$contactOptions = get_contact_options();
?>
 <!doctype html>
 <html <?php language_attributes(); ?>>
 <head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<meta name="robots" content="<?php echo is_search() ? 'noindex, nofollow' : 'all'; ?>" />
	<meta name="copyright" content="<?php bloginfo('name'); ?>" />
	<meta name="rating" content="general" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" http-equiv="distribution" content="Global" />
	<meta name="language" content="Spanish" />
	<meta name="theme-color" content="#000000" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<?php wp_head(); ?>
 </head>

<body class="<?php echo esc_attr($bodyclass); ?>">
	<?php get_template_part('template-parts/schema/local-business'); ?>

	<header id="mainHeader" class="header fixed-top bg-white shadow-sm">
		<div class="topbar d-none d-md-block">
			<div class="container d-flex justify-content-end py-2 border-bottom">
				<?php if (!empty(array_filter($contactOptions))) : ?>
					<div class="icons-contact d-flex align-items-center gap-3">
						<?php foreach ($contactOptions as $type => $value) {
							generateContactLink($type, $value);
						} ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<nav class="navbar navbar-expand-xl navbar-light">
			<div class="container" id="navbar-container">
				<a class="navbar-brand" href="<?php echo esc_url( home_url('/') ); ?>">
					<?php
					if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
						the_custom_logo();
					} else {
						echo '<img src="' . esc_url( get_template_directory_uri() ) . '/assets/img/logo.svg" class="img-brand" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="170" height="48">';
					}
					?>
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarNav" aria-controls="navbarNav" 
					aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php
				wp_nav_menu(array(
					'theme_location'  => 'main',
					'depth'           => 2,  // Solo dos niveles de anidación
					'container'       => 'div',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'navbarNav',
					'menu_class'      => 'navbar-nav ms-auto',
					'fallback_cb'     => '__return_false',
					'walker'          => new Bootstrap_NavWalker()
				));
				?>
			</div>
		</nav>
	</header>
    
	<?php
	$linkMovile = isset($options['link_btn_movil']) ? $options['link_btn_movil'] : '';
	if (!empty($linkMovile)) : ?>
		<div class="d-flex">
			<a href="<?php echo esc_url($linkMovile['url']); ?>" class="btn btn-primary btn-contact-mobile d-block d-md-none">
					<?php echo esc_html($linkMovile['title']); ?>
			</a>
		</div>
	<?php endif; ?>
