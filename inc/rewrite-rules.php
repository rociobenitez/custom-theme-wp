<?php
/**
 * Reglas de Reescritura Personalizadas para la Estructura de URL
 * 
 * - Reescribe las URLs de "author" a "equipo"
 * - Redirige las URLs de "author" a la estructura personalizada "equipo"
 * - Actualiza los enlaces de autor para que usen "equipo" en lugar de "author"
 */

// Añadir regla de reescritura personalizada para "equipo"
function custom_rewrite_rules() {
    add_rewrite_rule(
        '^equipo/([^/]*)/?',  // Nueva estructura en URL
        'index.php?author_name=$matches[1]',  // Redirige a la página de autor
        'top'  // Coloca la regla en la parte superior de las reglas de reescritura
    );
}
add_action('init', 'custom_rewrite_rules');

// Añadir variable de consulta personalizada
function custom_query_vars($vars) {
    $vars[] = 'author_name';  // Añade "author_name" para que WordPress reconozca la variable en la URL
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');

// Redirigir URLs de "author" a "equipo"
function redirect_author_to_equipo() {
    if (is_author()) {  // Si es una página de autor
        global $wp;
        $author_id = get_queried_object_id();  // Obtiene el ID del autor
        $author_name = get_the_author_meta('user_nicename', $author_id);  // Obtiene el "slug" del autor
        $current_url = home_url($wp->request);

        // Verifica si ya está en "equipo" y si la página de autor está activa
        $disable_author_page = get_field('disable_author_page', 'user_' . $author_id);  // Campo ACF para desactivar la página de autor
        
        if (!$disable_author_page && strpos($current_url, '/equipo/') === false) {
            wp_redirect(home_url("/equipo/$author_name"), 301);  // Redirige a la URL de "equipo"
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_author_to_equipo');
