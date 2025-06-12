<?php
/**
 * The header for our theme
 *
 * Muestra la sección <head> completa y abre <body> y <header>.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $bodyclass;
$bodyclass = $bodyclass ?? 'default-body';
$options = \Custom_Theme\Helpers\Theme_Options::get_all();
$mobile_button = isset( $options['link_btn_movil']['url'] ) ? $options['link_btn_movil'] : false;
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

	<!-- Favicons & App Icons -->
	<?php get_template_part( 'template-parts/header/favicons' ); ?>
    
    <!-- Enlaces Open Graph -->
    <?php \Custom_Theme\Helpers\Template_Helpers::add_open_graph_tags(); ?>

	<?php wp_head(); ?>
</head>
 
<body <?php body_class( $bodyclass ); ?>>

	<?php wp_body_open();  ?>

	<header id="mainHeader" class="header fixed-top
		<?php 
		echo esc_attr( $opts['bg_color_header_home'] ?? 'bg-white' );
		echo ' ';
		echo esc_attr( $opts['border_header'] ?? 'border-none' );
		?>">
		<?php 
		// Topbar
		\Custom_Theme\Custom_Theme::render_topbar();
		// Navbar
		get_template_part( 'template-parts/header/navbar' );
		?>
	</header>

	<!-- Botón móvil -->
	<?php if ( $mobile_button ) : ?>
		<div class="d-flex">
			<a href="<?php echo esc_url( $mobile_button['url'] ); ?>" class="btn btn-primary btn-contact-mobile d-block d-xl-none">
				<?php echo esc_html( $mobile_button['title'] ); ?>
			</a>
		</div>
	<?php endif; ?>
