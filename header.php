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
$site_name = esc_attr(get_bloginfo('name'));
$is_home_template = is_page_template('page-home.php');

// Obtener opciones desde ACF
$options = get_fields( 'option' );
$default_logo = $options['site_logo'] ? $options['site_logo']['url'] : get_template_directory_uri() . '/assets/img/logo.svg';
$white_logo = $options['site_logo_white'] ? $options['site_logo_white']['url']: get_template_directory_uri() . '/assets/img/logo-white.webp';
$show_topbar = $options['show_topbar'] ?? false;
$show_social_links = $options['show_social_links'] ?? false;
$contact_options = get_contact_options();

// Clases dinámicas desde opciones ACF
$topbar_bg_class = $options['bg_color_topbar'] ?? 'bg-black';
$topbar_border_class = $options['border_topbar'] ?? 'border-none';
$header_bg_class = $is_home_template ? ($options['bg_color_header_home'] ?? 'bg-transparent') : 'bg-white';
$header_border_class = $options['border_header'] ?? 'border-none';

$logo_width = 170;
$logo_height = 48;

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

	<!-- FAVICONS & APP ICONS -->
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon.ico" sizes="any" />
    <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon.svg" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/android-chrome-192x192.png" sizes="192x192" />
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/android-chrome-512x512.png" sizes="512x512" />
    
    <!-- Apple Touch Icon (iOS) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">

    <!-- Microsoft Tiles (Windows 8 / IE10+) -->
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/assets/favicons/mstile-144x144.png">
    
    <!-- Web App Manifest (PWA) -->
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/site.webmanifest">
    
    <!-- Enlaces Open Graph para compartir en redes (opcional y recomendado) -->
    <meta property="og:title" content="Título de tu sitio o de la página">
    <meta property="og:description" content="Descripción de la página">
    <meta property="og:image" content="URL de tu imagen para compartir en redes">
    <meta property="og:url" content="URL de la página">

	<?php wp_head(); ?>
</head>
 
 <body <?php body_class( $bodyclass ); ?>>
	<header id="mainHeader" class="header fixed-top <?= esc_attr($header_bg_class); ?> shadow-sm">

		<?php if ( $show_topbar ) :?>
			<!-- Topbar -->
			<div class="topbar d-none d-md-block <?= esc_attr($topbar_bg_class . ' ' . $topbar_border_class); ?>">
				<div class="container d-flex py-2 <?= $show_social_links === 'topbar' ? 'justify-content-between' : 'justify-content-end'; ?> <?= esc_attr($topbar_border_class); ?>">
					<?php if ( !empty( array_filter( $contact_options ) ) ) : ?>
						<div class="icons-contact d-flex align-items-center gap-3">
							<?php foreach ($contact_options as $type => $value) : ?>
								<?= generate_contact_link($type, $value); ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ($show_social_links === 'topbar') : ?>
						<!-- Iconos de redes sociales -->
						<div class="social-media d-flex align-items-center">
							<?= social_media(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif;?>

		<!-- Navbar principal -->
		<nav class="navbar navbar-expand-xl navbar-light">
			<div class="container <?= $show_social_links === 'navbar' ? 'justify-content-between' : ''; ?>">

				<!-- Logo -->
				<a class="navbar-brand" href="<?= esc_url( home_url('/') ); ?>">
					<img src="<?= esc_url($default_logo); ?>"
						class="img-brand logo-default" id="logo-default"
						width="<?= $logo_width;?>" height="<?= $logo_height;?>"
						alt="<?= $site_name; ?>">

					<img src="<?= esc_url($white_logo); ?>"
						class="img-brand logo-white" id="logo-white"
						width="<?= $logo_width;?>" height="<?= $logo_height;?>"
						alt="<?= $site_name; ?>">
				</a>

				<!-- Botón hamburguesa para móviles -->
				<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarNav" aria-controls="navbarNav" 
					aria-expanded="false" aria-label="<?= esc_attr_e( 'Toggle navigation', THEME_TEXTDOMAIN ); ?>">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- Menú principal -->
				<?php
				$adittional_class = $show_social_links === 'navbar' ? ' mx-xl-auto' : '';
				wp_nav_menu( array(
					'theme_location'  => 'main',
					'depth'           => 3,
					'container'       => 'div',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'navbarNav',
					'menu_class'      => 'navbar-nav ms-auto align-items-xl-center gap-2' . $adittional_class,
					'menu_id'         => 'main-menu',
					'fallback_cb'     => '__return_false',
					'walker'          => new bootstrap_5_wp_nav_menu_walker(),
				) );
				?>

				<?php if ($show_social_links === 'navbar') :?>
					<!-- Iconos de redes sociales -->
					<div class="social-media d-none d-xl-flex align-items-center ms-3">
						<?= social_media();?>
					</div>
				<?php endif;?>

			</div>
		</nav>
	</header>

	<?php if (!empty($options['link_btn_movil'])) : ?>
		<div class="d-flex">
			<a href="<?= esc_url($options['link_btn_movil']['url']); ?>" class="btn btn-primary btn-contact-mobile d-block d-xl-none">
				<?= esc_html($options['link_btn_movil']['title']); ?>
			</a>
		</div>
	<?php endif; ?>
