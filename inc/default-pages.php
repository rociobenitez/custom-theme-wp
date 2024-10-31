<?php
/**
 * Función para crear páginas por defecto al activar el theme
 */
function custom_theme_create_default_pages() {
    $default_pages = [
        'aviso-legal' => [
            'title' => 'Aviso legal',
            'content' => 'Contenido de ejemplo para la página de Aviso Legal.',
        ],
        'politica-privacidad' => [
            'title' => 'Política de privacidad',
            'content' => 'Contenido de ejemplo para la página de Política de Privacidad.',
        ],
        'politica-cookies' => [
            'title' => 'Política de cookies',
            'content' => 'Contenido de ejemplo para la página de Política de Cookies.',
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
        ]
    ];

    foreach ($default_pages as $slug => $page) {
        if (!get_page_by_path($slug)) {  // Verifica si la página ya existe
            $page_id = wp_insert_post([
                'post_title'   => $page['title'],
                'post_name'    => $slug,
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);

            if (!empty($page['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page['template']);
            }
        }
    }
}
add_action('after_switch_theme', 'custom_theme_create_default_pages');
