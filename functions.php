<?php
/**
 * Funciones y definiciones del tema.
 *
 * @package custom_theme
 */

// Evitamos acceso directo.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Incluimos archivos de funciones.
 */
$theme_includes = [
    '/inc/config.php',             // Configuración del tema
    '/inc/acf-options.php',        // Configuración de ACF Pro
    '/inc/admin.php',              // Personalización del área de administración
    '/inc/class-bs-navwalker.php', // Clase para el NavWalker de Bootstrap
    '/inc/cleanup.php',            // Limpieza de código
    '/inc/cpt.php',                // Tipos de contenido personalizados
    '/inc/customizer.php',         // Personalización del tema en el Customizer
    '/inc/default-content.php',    // Contenido predeterminado
    '/inc/default-pages.php',       // Páginas predeterminadas
    '/inc/enqueue.php',            // Scripts y estilos
    '/inc/helpers.php',            // Funciones auxiliares
    '/inc/image-sizes.php',        // Tamaños de imágenes
    '/inc/login.php',              // Funciones de inicio de sesión
    '/inc/rewrite-rules.php',      // Reglas de enrutamiento personalizadas
    '/inc/woocommerce.php',        // Funciones específicas de WooCommerce
    '/inc/setup.php',              // Configuraciones básicas del tema
];

foreach ( $theme_includes as $file ) {
    $filepath = get_template_directory() . $file;
    if ( file_exists( $filepath ) ) {
        require_once $filepath;
    } else {
        // Puedes activar un aviso si falta algún archivo.
        error_log( 'Error al incluir ' . $filepath );
    }
}

/**
 * Añadir aviso si falta ACF PRO o Classic Editor.
 */
function custom_required_plugins_notice() {
    // Mostrar aviso solo para administradores
    if ( ! current_user_can( 'update_core' ) ) {
        return;
    }

    // Verificar si los plugins Classic Editor y ACF PRO están activos
    if ( ! function_exists( 'is_plugin_active' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>' . esc_html__( 'Para que el theme funcione correctamente, por favor instala y activa el plugin Classic Editor.', THEME_TEXTDOMAIN ) . '</p>';
        echo '</div>';
    }

    if (! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>'. esc_html__( 'Para que el theme funcione correctamente, por favor instala y activa el plugin Advanced Custom Fields PRO.', THEME_TEXTDOMAIN ). '</p>';
        echo '</div>'; 
    }
}
add_action( 'admin_notices', 'custom_required_plugins_notice' );
