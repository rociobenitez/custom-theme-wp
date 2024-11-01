<?php
/**
 * Configuración básica del theme
 *
 * @package NombreTheme
 */

/**
 * Configura los valores predeterminados del tema y registra
 * soporte para varias características de WordPress.
 */ 
function custom_theme_setup() {
	// Soporte para traducciones
	load_theme_textdomain( THEME_TEXTDOMAIN, get_template_directory() . '/languages' );

	// Añade enlaces RSS 
	add_theme_support( 'automatic-feed-links' );

	// Soporte para título del sitio
	add_theme_support( 'title-tag' );

	// Soporte para miniaturas
	add_theme_support( 'post-thumbnails' );

   // Soporte para formatos de publicación
   add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// Soporte para logotipo personalizado
	add_theme_support('custom-logo', array(
		'height'      => 100,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
	));

	// Soporte para fondos personalizados
	add_theme_support('custom-background');

	// Registro de menús
	register_nav_menus(
		array(
			'main'    => esc_html__( 'Menú Principal', THEME_TEXTDOMAIN ),
			'footer1' => esc_html__( 'Menú Footer 1', THEME_TEXTDOMAIN ),
			'footer2' => esc_html__( 'Menú Footer 2', THEME_TEXTDOMAIN ),
			'legales' => esc_html__( 'Menú Legales', THEME_TEXTDOMAIN )
		)
	);

	// HTML5 para formularios de búsqueda, comentarios, etc.
   add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery' ) );
	
	// Habilitar widgets selectivos (para mejor rendimiento)
	add_theme_support('customize-selective-refresh-widgets');
  
}

add_action( 'after_setup_theme', 'custom_theme_setup' );