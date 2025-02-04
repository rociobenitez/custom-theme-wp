<?php
/**
 * Configuración del theme y definición de constantes
 *
 * @package custom_theme
 */

// Definir el nombre del theme.
if ( ! defined( 'THEME_NAME' ) ) {
    define( 'THEME_NAME', 'Custom Theme' );
}

// Definir el dominio de texto del theme.
if ( ! defined( 'THEME_TEXTDOMAIN' ) ) {
    define( 'THEME_TEXTDOMAIN', 'custom_theme' );
}

// Nombre de la compañía (útil para notificaciones, copyright, etc.)
if ( ! defined( 'COMPANY_NAME' ) ) {
    define( 'COMPANY_NAME', 'Nombre de la Compañía' );
}

// URL del logotipo del sitio.
if ( ! defined( 'SITE_LOGO' ) ) {
    define( 'SITE_LOGO', get_template_directory_uri() . '/assets/img/logo.svg' );
}

// URL del logotipo negativo del sitio.
if ( ! defined( 'SITE_LOGO_WHITE' ) ) {
    define( 'SITE_LOGO_WHITE', get_template_directory_uri() . '/assets/img/logo-white.svg' );
}

// Versión del theme (útil para cache busting de archivos).
if ( ! defined( '_THEME_VERSION' ) ) {
    define( '_THEME_VERSION', '1.0.0' );
}

// Ruta del directorio del tema.
if ( ! defined( 'THEME_DIR' ) ) {
    define( 'THEME_DIR', get_template_directory_uri() );
}

// URL del fondo de inicio de sesión.
if ( ! defined( 'LOGIN_BG' ) ) {
    define( 'LOGIN_BG', get_template_directory_uri() . '/assets/img/login-background.jpg' );
}

// Activar o desactivar la barra de administración.
if ( ! defined( 'DISABLE_ADMIN_BAR' ) ) {
    define( 'DISABLE_ADMIN_BAR', false );
}

// Habilitar o deshabilitar GSAP (opcional).
if ( ! defined( 'ENABLE_GSAP' ) ) {
    define( 'ENABLE_GSAP', false );
}
