<?php

/**
 * Opciones del theme.
 *
 * @package CustomTheme
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

/* // Opcional - Clave API de Google Maps
function custom_acf_init() {
   $google_api_key = get_field('api_google', 'option');
   if ($google_api_key) {
       acf_update_setting('google_api_key', $google_api_key);
   }
}
add_action('acf/init', 'custom_acf_init'); */
