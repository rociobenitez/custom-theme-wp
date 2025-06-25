<?php
/**
 * Genera e inserta los esquemas JSON-LD para Product.
 */

if (!defined('ABSPATH')) {
    exit;
}

function generate_schema_product() {
    if ( !is_product() ) {
        return;
    }

    $product = wc_get_product(get_the_ID());
    if (!$product) {
        return;
    }

    // Obtener los campos de ACF
    $term = get_queried_object();
    $fields = get_fields( $term );

    // URL de la imagen del producto
    $image_product = wp_get_attachment_url( get_post_thumbnail_id() );

    // Descripción del producto (solo si existe)
    $description = get_the_excerpt();
    $description = !empty( $description ) ? wp_strip_all_tags( $description ) : null;

    // Disponibilidad del producto en WooCommerce
    $stock_status = $product->get_stock_status();

    $schema_product = [
        "@context" => "https://schema.org",
        "@type" => "Product",
        "@id" => "#product",
        "name" => get_the_title(),
        "image" => esc_url( $image_product ),
        "offers" => [
            "seller" => [ 
                "@type" => "Organization",
                "@id" => "#Organization",
            ]
        ],
        "mainEntityOfPage" => [ 
            "@id" => get_permalink()
        ]  
    ];

    if (!empty($description)) {
        $schema_product["description"] = esc_html( $description );
    }

    $schema_product_json = json_encode($schema_product, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    echo '<script type="application/ld+json">' . PHP_EOL . $schema_product_json . PHP_EOL . '</script>';
}

add_action('wp_head', 'generate_schema_product');