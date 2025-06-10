<?php
/**
 * Reglas de reescritura personalizadas para la URL de autor (“equipo” en lugar de “author”).
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Agrega una regla de reescritura para “equipo/{autor}” en lugar de “author/{autor}”.
 */
function custom_rewrite_rules() {
    add_rewrite_rule(
        '^equipo/([^/]*)/?',
        'index.php?author_name=$matches[1]', 
        'top' 
    );
}
add_action( 'init', 'custom_rewrite_rules' );

/**
 * Añade "author_name" a la lista de variables de consulta.
 *
 * @param array $vars Lista actual de variables de consulta.
 * @return array Lista actualizada de variables de consulta.
 */
function custom_query_vars( $vars ) {
    $vars[] = 'author_name';
    return $vars;
}
add_filter( 'query_vars', 'custom_query_vars' );

/**
 * Redirige internamente las visitas a /author/{autor} hacia /equipo/{autor}, 301 Moved Permanently.
 */
function redirect_author_to_equipo() {
    if ( is_author() ) {
        global $wp;
        $author_id   = get_queried_object_id(); 
        $author_slug = get_the_author_meta( 'user_nicename', $author_id) ;  
        $current_url = home_url( $wp->request );

        // Verificar si hay un campo ACF para desactivar la página de autor
        $disable_author_page = false;
        if ( function_exists( 'get_field' ) ) {
            $disable_author_page = get_field( 'disable_author_page', 'user_' . $author_id );
        }
        
        // Si no está desactivado y la URL todavía usa “author/”
        if ( ! $disable_author_page && false !== strpos( $current_url, '/author/' ) ) {
            $url_equipo = home_url( '/equipo/' . $author_slug );
            wp_safe_redirect( $url_equipo, 301 );
            exit;
        }
    }
}
add_action( 'template_redirect', 'redirect_author_to_equipo' );

/**
 * Vaciar reglas de reescritura cuando se active el tema.
 */
function rn_flush_rewrite_rules_al_activar() {
    rn_reglas_reescritura_equipo();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'rn_flush_rewrite_rules_al_activar' );
