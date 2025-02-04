<?php
/**
 * Configuración básica del theme
 *
 * @package custom_theme
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
   add_theme_support( 'post-formats', array( 'aside', 'gallery', 'audio', 'video', 'image', 'link', 'quote', 'status' ) );

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
			'legal' => esc_html__( 'Menú Páginas Legales', THEME_TEXTDOMAIN )
		)
	);

	// HTML5 para formularios de búsqueda, comentarios, etc.
   add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery' ) );
	
	// Habilitar widgets selectivos (para mejor rendimiento)
	add_theme_support('customize-selective-refresh-widgets');
  
}

add_action( 'after_setup_theme', 'custom_theme_setup' );

/**
 * Crear menús por defecto.
 */
function custom_create_default_menus() {
    // --- Menú Principal ---
    $main_menu = wp_get_nav_menu_object( 'main' );
    if ( ! $main_menu ) {
        // Crear menú principal
        $main_menu_id = wp_create_nav_menu( 'main' );

        // Array de páginas para el menú principal
        $main_pages = [
            'home'         => '/',
            'quienes-somos'=> 'quienes-somos',
            'blog'         => 'blog',
            'contacto'     => 'contacto',
        ];
        foreach ( $main_pages as $label => $slug ) {
            $page = get_page_by_path( $slug );
            if ( $page ) {
                wp_update_nav_menu_item( $main_menu_id, 0, [
                    'menu-item-title'     => $page->post_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ] );
            }
        }
        // Asignar el menú a la ubicación 'main'
        $locations = get_theme_mod( 'nav_menu_locations' );
        if ( ! is_array( $locations ) ) {
            $locations = [];
        }
        $locations['main'] = $main_menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    // --- Menú de Legales ---
    $legal_menu = wp_get_nav_menu_object( 'legal' );
    if ( ! $legal_menu ) {
        // Crear menú legal
        $legal_menu_id = wp_create_nav_menu( 'legal' );

        // Array de páginas para el menú de legales
        $legal_pages = [
            'aviso-legal'         => 'aviso-legal',
            'politica-privacidad' => 'politica-privacidad',
            'politica-cookies'    => 'politica-cookies',
        ];
        foreach ( $legal_pages as $label => $slug ) {
            $page = get_page_by_path( $slug );
            if ( $page ) {
                wp_update_nav_menu_item( $legal_menu_id, 0, [
                    'menu-item-title'     => $page->post_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ] );
            }
        }
        // Asignar el menú a la ubicación 'legal'
        $locations = get_theme_mod( 'nav_menu_locations' );
        if ( ! is_array( $locations ) ) {
            $locations = [];
        }
        $locations['legal'] = $legal_menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}
add_action( 'after_switch_theme', 'custom_create_default_menus' );
