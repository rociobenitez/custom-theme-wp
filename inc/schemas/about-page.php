<?php
/**
 * Genera e inserta los esquemas JSON-LD para AboutPage y HealthAndBeautyBusiness.
 */

if (!defined('ABSPATH')) {
    exit; // Evitar el acceso directo al archivo
}

/**
 * Genera el esquema JSON-LD para AboutPage.
 */
function generate_schema_about_page() {
    // Verificar si estamos en una página
    if ( !is_page() ) {
        return;
    }

    // Obtener el template de la página actual
    $template = get_page_template_slug();

    // Verificar si el template es 'page-about.php'
    if ('page-about.php' !== $template) {
        return;
    }

    // Obtener la URL actual de la página
    $current_url = get_permalink();

    // Campos de Opciones
    $options = get_fields('option');
    $localbusiness = $options['localbusiness'];

    // ID Schema
    $id_schema = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';

    // Construir el arreglo del esquema AboutPage
    $about_page_schema = [
        "@context" => "https://schema.org",
        "@id" => $current_url,
        "@type" => "AboutPage",
        "mainEntity" => [
            "@id" => "#" . $id_schema, 
        ]
    ];

    // Convertir el arreglo a JSON con formato legible
    $about_page_json = json_encode($about_page_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprimir el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL . $about_page_json . '</script>' . PHP_EOL;
}

// Hook para agregar el schema de AboutPage
add_action('wp_head', 'generate_schema_about_page');