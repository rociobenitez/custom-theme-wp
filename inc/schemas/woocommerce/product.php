<?php
/**
 * Genera e inserta los esquemas JSON-LD para Product.
 */

if (!defined('ABSPATH')) {
    exit; // Evita el acceso directo al archivo
}

function generate_schema_product() {
    // Verificar si es una página de producto
    if ( !is_product() ) {
        return;
    }

    // Obtener el objeto del producto de WooCommerce
    $product = wc_get_product(get_the_ID());

    if (!$product) {
        return;
    }

    // Obtener la URL actual de la página
    $current_url = get_permalink();

    // Obtener los campos de ACF
    $term = get_queried_object();
    $fields = get_fields($term);

    // Obtener el título de la página (nombre del producto)
    $h1 = $fields['cabecera']['title'];
    $title_product = !empty($h1) ? $h1 : get_the_title();

    // Obtener la URL de la imagen del producto
    $image_product = wp_get_attachment_url(get_post_thumbnail_id());

    // Obtener la descripción del producto (solo si existe)
    $description = get_the_excerpt();
    $description = !empty($description) ? wp_strip_all_tags($description) : null;

    // Obtener el precio del producto
    $price = get_post_meta(get_the_ID(), '_price', true);
    $price = !empty($price) ? $price : null;

    // Obtener la disponibilidad del producto en WooCommerce
    $stock_status = $product->get_stock_status();
    $availability = ($stock_status === 'instock') ? "https://schema.org/InStock" : "https://schema.org/OutOfStock";

    // Construir el array del esquema Product
    $schema_product = [
        "@context" => "https://schema.org",
        "@type" => "Product",
        "@id" => "#product",
        "name" => esc_html($title_product),
        "image" => esc_url($image_product),
        "offers" => [
            "@type" => "Offer",
            "priceCurrency" => "EUR",
            "price" => $price,
            "availability" => esc_url($availability),
            "seller" => [ 
                "@type" => "HealthAndBeautyBusiness",
                "@id" => "#Local",
            ]
        ],
        "mainEntityOfPage" => [ 
            "@id" => $current_url
        ]  
    ];

    // Agregar la descripción solo si existe
    if (!empty($description)) {
        $schema_product["description"] = esc_html($description);
    }

    // Convertir el array a JSON con formato legible
    $schema_product_json = json_encode($schema_product, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprimir el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL . $schema_product_json . PHP_EOL . '</script>';
}

// Hook para agregar el schema de Product en categorías de WooCommerce
add_action('wp_head', 'generate_schema_product');