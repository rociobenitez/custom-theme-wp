<?php
/**
 * Opciones del theme
 *
 * @package CustomTheme
 */

 /**
  * Si ACF Pro está activo, añadir una página de Opciones Globales
  */
 if( function_exists('acf_add_options_page') ) {
   // Página principal de Opciones del Theme
   acf_add_options_page(array(
       'page_title'    => 'Opciones Generales',
       'menu_title'    => 'Opciones',
       'menu_slug'     => 'theme-general-settings',
       'capability'    => 'edit_posts',
       'redirect'      => false,
   ));
}

/**
 * Sincronización de campos ACF mediante acf-json
 */
add_filter( 'acf/settings/save_json', 'custom_acf_json_save_point' );
function custom_acf_json_save_point( $path ) {
    // Guarda los archivos JSON en la carpeta acf-json del theme
    return get_template_directory() . '/acf-json';
}

add_filter( 'acf/settings/load_json', 'custom_acf_json_load_point' );
function custom_acf_json_load_point( $paths ) {
    // Elimina la ruta por defecto
    unset( $paths[0] );
    // Agrega la ruta personalizada
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
}

/**
 * Mensaje de actualización de opciones
 */
function custom_acf_options_update_message() {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        add_settings_error('acf_options', 'acf_options_updated', __('Opciones actualizadas', 'custom_theme'), 'updated');
    }
}
add_action('admin_notices', 'custom_acf_options_update_message');

/**
 *  Clave API de Google Maps (Opcional)
 */
function custom_acf_init() {
   $google_api_key = get_field('google_maps_api', 'option');
   if ($google_api_key) {
       acf_update_setting('google_api_key', $google_api_key);
   }
}
add_action('acf/init', 'custom_acf_init');
