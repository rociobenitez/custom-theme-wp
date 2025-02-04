<?php
/**
 * Personalización del área de administración de WordPress
 *
 * @package custom_theme
 */

// Widget personalizado en el panel de administración
function custom_dashboard_widget() {
   echo '<div class="custom-dashboard-widget">';
   echo '<img src="' . esc_url( SITE_LOGO ) . '" alt="' . esc_attr__( 'Logo', THEME_TEXTDOMAIN ) . '" />';
   echo '<h3 class="custom-dashboard-title">' . esc_html__( 'Sistema de Gestión de Contenidos', THEME_TEXTDOMAIN ) . '</h3>';
   echo '<h3 class="custom-dashboard-title">' . esc_html( COMPANY_NAME ) . '</h3>';
   echo '</div>';
}
function add_custom_dashboard_widget() {
   wp_add_dashboard_widget( 'custom_dashboard_widget', esc_html__( '¡Bienvenido!', THEME_TEXTDOMAIN ), 'custom_dashboard_widget' );
}
add_action( 'wp_dashboard_setup', 'add_custom_dashboard_widget' );

// Estilos personalizados para el área de administración
function my_admin_styles() {
   wp_enqueue_style( 'my-admin-style', get_template_directory_uri() . '/css/admin-styles.css' );
}
add_action( 'admin_enqueue_scripts', 'my_admin_styles' );

// Personalización del pie de página en el área de administración
function change_footer_admin() {
   echo '&copy; ' . date( "Y" ) . ' ' . esc_html( COMPANY_NAME ) . esc_html__( '. Todos los derechos reservados', THEME_TEXTDOMAIN );
}
add_filter( 'admin_footer_text', 'change_footer_admin' );
