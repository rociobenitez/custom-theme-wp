<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package custom_theme
 */

global $bodyclass;
$bodyclass = $bodyclass ?? 'default-body';
$is_home_template = is_page_template('page-home.php');

// Definir la clase de fondo basada en la plantilla utilizada
$background_class = $is_home_template ? 'bg-transparent' : 'bg-white';
$headerclass = $background_class . ' shadow-sm';

// Definir las URLs de los logos
$logo_src = get_template_directory_uri() . '/assets/img/logo.svg';
$logo_white_src   = get_template_directory_uri() . '/assets/img/logo-white.svg';

// Ruta opcional para el logo en PNG o JPG de mayor resolución
$logo2x_src = ''; // get_template_directory_uri() . '/assets/img/logo@2x.png';
$srcset = ( ! empty( $logo2x_src ) ) 
    ? 'srcset="' . esc_url( $logo_src ) . ' 1x, ' . esc_url( $logo2x_src ) . ' 2x"' 
    : '';

$logo_width = 170;
$logo_height = 48;

// Obtener el valor de los campos de opciones
$options = get_fields( 'option' );
$contact_options = get_contact_options();

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<meta name="robots" content="<?php echo is_search() ? 'noindex, nofollow' : 'all'; ?>" />
	<meta name="copyright" content="<?php bloginfo('name'); ?>" />
	<meta name="rating" content="general" />
	<meta name="resource-type" content="document" />
	<meta name="distribution" content="Global" />
	<meta name="language" content="Spanish" />
	<meta name="theme-color" content="#000000" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
 
 <body <?php body_class( $bodyclass ); ?>>
	<header id="mainHeader" class="header fixed-top <?php echo esc_attr( $headerclass ); ?>">
		<div class="topbar d-none d-md-block">
			<div class="container d-flex justify-content-end py-2 border-bottom">
				<?php if ( !empty( array_filter( $contact_options ) ) ) : ?>
					<div class="icons-contact d-flex align-items-center gap-3">
						<?php
						foreach ( $contact_options as $type => $value ) {
							generate_contact_link( $type, $value );
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<nav class="navbar navbar-expand-xl navbar-light">
			<div class="container" id="navbar-container">
				<a class="navbar-brand" href="<?php echo esc_url( home_url('/') ); ?>">
					<?php if (function_exists('the_custom_logo') && has_custom_logo()) : 
						the_custom_logo(); 
					else : ?>
						<img src="<?php echo esc_url($logo_src); ?>"
							<?php echo $srcset; ?> 
							class="img-brand logo-default" id="logo-default"
							alt="<?php echo esc_attr( get_bloginfo('name') ); ?>" 
							width="<?php echo esc_attr( $logo_width ); ?>" 
							height="<?php echo esc_attr( $logo_height ); ?>" />
						<img src="<?php echo esc_url( $logo_white_src ); ?>"
							<?php echo $srcset; ?> 
							class="img-brand logo-white" id="logo-white"
							alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" 
							width="<?php echo esc_attr( $logo_width ); ?>" 
							height="<?php echo esc_attr( $logo_height ); ?>" />
					<?php endif; ?>
				</a>
				<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarNav" aria-controls="navbarNav" 
					aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', THEME_TEXTDOMAIN ); ?>">
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php
				wp_nav_menu( array(
					'theme_location'  => 'main',
					'depth'           => 3,
					'container'       => 'div',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'navbarNav',
					'menu_class'      => 'navbar-nav ms-auto align-items-xl-center gap-2',
					'menu_id'         => 'main-menu',
					'fallback_cb'     => '__return_false',
					'walker'          => new bootstrap_5_wp_nav_menu_walker(),
				) );
				?>
			</div>
		</nav>
	</header>
	
	<?php
	$link_movile = $options['link_btn_movil'] ?? '';
	if ( !empty( $link_movile ) ) : ?>
		<div class="d-flex">
			<a href="<?php echo esc_url($link_movile['url']); ?>" class="btn btn-primary btn-contact-mobile d-block d-xl-none">
					<?php echo esc_html($link_movile['title']); ?>
			</a>
		</div>
	<?php endif; ?>
