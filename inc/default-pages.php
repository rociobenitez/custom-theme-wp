<?php
/**
 * Función para crear páginas por defecto al activar el theme
 */
function custom_theme_create_default_pages() {
    $default_pages = [
        'aviso-legal' => [
            'title' => 'Aviso legal',
            'content' => 'Contenido de ejemplo para la página de Aviso Legal.',
            'template' => 'page.php',
        ],
        'politica-privacidad' => [
            'title' => 'Política de privacidad',
            'content' => 'Contenido de ejemplo para la página de Política de Privacidad.',
            'template' => 'page.php',
        ],
        'politica-cookies' => [
            'title' => 'Política de cookies',
            'content' => 'Contenido de ejemplo para la página de Política de Cookies.',
            'template' => 'page.php',
        ],
        'home' => [
            'title' => 'Home',
            'content' => 'Contenido de ejemplo para la página de inicio.',
            'template' => 'page-home.php',
        ],
        'contacto' => [
            'title' => 'Contacto',
            'content' => 'Contenido de ejemplo para la página de Contacto.',
            'template' => 'page-contact.php',
        ],
        'quienes-somos' => [
            'title' => 'Quiénes somos',
            'content' => 'Contenido de ejemplo para la página de Quiénes Somos.',
            'template' => 'page-about.php',
        ],
        'blog' => [
            'title' => 'Blog',
            'content' => 'Contenido de ejemplo para la página del blog.',
            'template' => 'page-blog.php',
        ]
    ];

    foreach ($default_pages as $slug => $page) {
        if ( ! get_page_by_path($slug) ) {  // Verificar si la página ya existe
            $page_id = wp_insert_post([
                'post_title'   => $page['title'],
                'post_name'    => $slug,
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
    
            if ( ! empty($page['template']) ) {
                update_post_meta($page_id, '_wp_page_template', $page['template']);
            }
        }
    }
    
    // Asignar la página 'home' como Front Page
    $home_page = get_page_by_path( 'home' );
    if ( $home_page ) {
        update_option( 'page_on_front', $home_page->ID );
        update_option( 'show_on_front', 'page' );
    }    
}
add_action('after_switch_theme', 'custom_theme_create_default_pages');
