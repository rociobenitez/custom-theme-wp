<?php
/**
 * Genera e inserta el esquema JSON-LD para AboutPage.
 */
function generate_schema_about_page() {
    // Verificar si estamos en una página.
    if ( ! is_page() ) {
        return;
    }

    // Obtener el template de la página actual.
    $template = get_page_template_slug();

    // Verificar si el template es 'page-about.php'.
    if ( 'page-about.php' !== $template ) {
        return;
    }

    // Obtener la URL actual de la página.
    $current_url = get_permalink();

    $options       = get_fields('option');
    $localbusiness = $options['localbusiness'];
    if (!$localbusiness) {
        return;
    }

    $id_schema = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';

    // Construir el esquema AboutPage.
    $about_page_schema = [
        "@context" => "https://schema.org",
        "@id"      => esc_url( $current_url ),
        "@type"    => "AboutPage",
        "mainEntity" => [
            "@id" => "#" . $id_schema,
        ],
    ];

    // Convertir el array a JSON.
    $json_ld = json_encode( $about_page_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

    // Verificar si la conversión fue exitosa.
    if ( ! $json_ld ) {
        return;
    }

    // Imprimir el script JSON-LD en el head.
    echo '<script type="application/ld+json">' . $json_ld . '</script>' . "\n";
}

// Hook para insertar el esquema en el head de la página.
// add_action( 'wp_head', 'generate_schema_about_page' ); // Descomentar esta línea si se desea incluir el schema 'aboutpage'
?>
