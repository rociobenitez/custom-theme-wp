<?php
/**
 * Personalización del área de administración de WordPress
 *
 * @package NombreTheme
 */

// Widget personalizado en el panel de administración
function custom_dashboard_widget() {
   echo '<div class="custom-dashboard-widget">';
   echo '<img src="' . esc_url( SITE_LOGO ) . '" alt="Logo" />';
   echo '<h3 class="custom-dashboard-title">Sistema de Gestión de Contenidos</h3>';
   echo '<h3 class="custom-dashboard-title">'. COMPANY_NAME . '</h3>';
   echo '</div>';
}
function add_custom_dashboard_widget() {
   wp_add_dashboard_widget( 'custom_dashboard_widget', '¡Bienvenido!', 'custom_dashboard_widget' );
}
add_action( 'wp_dashboard_setup', 'add_custom_dashboard_widget' );

// Estilos personalizados para el área de administración
function my_admin_styles() {
   wp_enqueue_style( 'my-admin-style', get_template_directory_uri() . '/css/admin-styles.css' );
}
add_action( 'admin_enqueue_scripts', 'my_admin_styles' );

// Personalización del pie de página en el área de administración
function change_footer_admin() {
   echo '&copy; ' . date( "Y" ) . ' ' . COMPANY_NAME . '. Todos los derechos reservados';
}
add_filter( 'admin_footer_text', 'change_footer_admin' );
