<?php
/**
 * Opciones Generales del Theme Customizer
 *
 * @package NombreTheme
 */

function nombretheme_customize_register( $wp_customize ) {
	// SECCIÓN PARA EL LOGOTIPO
	$wp_customize->add_setting( 'site_logo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo', array(
		'label'    => __( 'Logotipo del Sitio', 'nombretheme' ),
		'section'  => 'title_tagline',
		'settings' => 'site_logo',
	)));

	// SECCIÓN DE FUENTES
	$wp_customize->add_section( 'general_fonts', array(
		'title'    => __( 'Tipografía', 'nombretheme' ),
		'priority' => 30,
	));

	// Fuente para el cuerpo
	$wp_customize->add_setting( 'body_font', array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'body_font', array(
		'label'   => __( 'Fuente del Cuerpo', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'     => 'select',
		'choices'  => array(
			'Roboto'            => 'Roboto',
			'Raleway'           => 'Raleway',
			'Montserrat'        => 'Montserrat',
			'Poppins'           => 'Poppins',
			'Lato'              => 'Lato',
			'Inter'             => 'Inter',
			'Open Sans'         => 'Open Sans',
			'Lora'              => 'Lora',
			'DM Sans'           => 'DM Sans',
			'Libre Baskerville' => 'Libre Baskerville'
		),
	));

	// Fuente para títulos
	$wp_customize->add_setting( 'title_font', array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'title_font', array(
		'label'   => __( 'Fuente de Títulos', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'select',
		'choices'  => array(
			'Roboto'            => 'Roboto',
			'Raleway'           => 'Raleway',
			'Montserrat'        => 'Montserrat',
			'Poppins'           => 'Poppins',
			'Lato'              => 'Lato',
			'Inter'             => 'Inter',
			'Open Sans'         => 'Open Sans',
			'Lora'              => 'Lora',
			'DM Sans'           => 'DM Sans',
			'Libre Baskerville' => 'Libre Baskerville'
		),
	));

	// Fuente para taglines
	$wp_customize->add_setting( 'tagline_font', array(
		'default'           => 'Roboto',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'title_font', array(
		'label'   => __( 'Fuente de Títulos', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'select',
		'choices'  => array(
			'Roboto'            => 'Roboto',
			'Raleway'           => 'Raleway',
			'Montserrat'        => 'Montserrat',
			'Poppins'           => 'Poppins',
			'Lato'              => 'Lato',
			'Inter'             => 'Inter',
			'Open Sans'         => 'Open Sans',
			'Lora'              => 'Lora',
			'DM Sans'           => 'DM Sans',
			'Libre Baskerville' => 'Libre Baskerville'
		),
	));

	// SECCIÓN DE TAMAÑOS DE FUENTES
	$wp_customize->add_setting( 'body_font_size', array(
		'default'           => '1rem',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'body_font_size', array(
		'label'   => __( 'Tamaño de Fuente del Cuerpo', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'text',
	));

	$wp_customize->add_setting( 'h1_font_size', array(
		'default'           => '3.5rem',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'h1_font_size', array(
		'label'   => __( 'Tamaño de Fuente del H1', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'text',
	));

	$wp_customize->add_setting( 'h2_font_size', array(
		'default'           => '2rem',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'h2_font_size', array(
		'label'   => __( 'Tamaño de Fuente del H2', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'text',
	));

	$wp_customize->add_setting( 'h3_font_size', array(
		'default'           => '1.5rem',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'h3_font_size', array(
		'label'   => __( 'Tamaño de Fuente del H3', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'text',
	));

	$wp_customize->add_setting( 'h4_font_size', array(
		'default'           => '1.125rem',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'h4_font_size', array(
		'label'   => __( 'Tamaño de Fuente del H4', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'text',
	));

	$wp_customize->add_setting( 'h5_font_size', array(
		'default'           => '1.025rem',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control( 'h5_font_size', array(
		'label'   => __( 'Tamaño de Fuente del H5', 'nombretheme' ),
		'section' => 'general_fonts',
		'type'    => 'text',
	));
	
	// SECCIÓN DE COLORES
	$wp_customize->add_section( 'general_colors', array(
		'title'    => __( 'Colores Generales', 'nombretheme' ),
		'priority' => 35,
	));

	// Color Primario
	$wp_customize->add_setting( 'primary_color', array(
		'default'           => '#3978a7',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
		'label'    => __( 'Color Primario', 'nombretheme' ),
		'section'  => 'general_colors',
		'settings' => 'primary_color',
	)));

	// Color Primario
	$wp_customize->add_setting( 'secondary_color', array(
		'default'           => '#5da7e9',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'secondary_color', array(
		'label'    => __( 'Color Secundario', 'nombretheme' ),
		'section'  => 'general_colors',
		'settings' => 'secondary_color',
	)));

	// Color de fondo
	$wp_customize->add_setting( 'background_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
		'label'    => __( 'Color de Fondo', 'nombretheme' ),
		'section'  => 'general_colors',
		'settings' => 'background_color',
	)));

	// Color de texto
	$wp_customize->add_setting( 'body_color', array(
		'default'           => '#4a4a4a',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_color', array(
		'label'    => __( 'Color del Texto', 'nombretheme' ),
		'section'  => 'general_colors',
		'settings' => 'body_color',
	)));

	// Color de los Encabezados
	$wp_customize->add_setting( 'heading_color', array(
		'default'           => '#0a192f',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
		'label'    => __( 'Color de los Encabezados', 'nombretheme' ),
		'section'  => 'general_colors',
		'settings' => 'heading_color',
	)));
}
add_action( 'customize_register', 'nombretheme_customize_register' );
