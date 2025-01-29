<?php
/**
 * Genera e inserta el esquema JSON-LD para Service en páginas de servicios.
 */

if (!defined('ABSPATH')) {
    exit; // Evita el acceso directo al archivo
}

/**
 * Genera el esquema JSON-LD para Service.
 */
function generate_schema_service() {
    if ( !is_product_category() ) {
        return;
    }

    // Obtener la categoría actual de WooCommerce
    $term = get_queried_object();

    if (!$term || !isset($term->term_id)) {
        return;
    }

    // Obtener el ID de la categoría
    $category_id = $term->term_id;

    // Obtener los campos de la categoría de ACF
    $fields = get_fields($term);

    // Obtener el título de la categoría (nombre de la categoría)
    $h1 = $fields['cabecera']['title'];
    $title_service = !empty($h1) ? $h1 : esc_html($term->name);

    // Obtener la descripción de la categoría (si existe)
    $description = term_description($category_id);
    $description = !empty($description) ? wp_strip_all_tags($description) : null;

    // Obtener la miniatura de la categoría de WooCommerce
    $thumbnail_id = get_woocommerce_term_meta($category_id, 'thumbnail_id', true);
    $image_service = !empty($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : null;

    // Obtener los tipos adicionales desde un campo de ACF (repeater en la categoría)
    $additional_types = $fields['additional_type'];
    $additionalTypeUrls = [];
    if (!empty($additional_types)) {
        foreach ($additional_types as $type) {
            if (!empty($type['url'])) {
                $additionalTypeUrls[] = esc_url($type['url']);
            }
        }
    }

     // Obtener los productos de esta categoría
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 10, // Limitar a 10 productos para no sobrecargar el JSON
        'tax_query'      => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id
            ]
        ]
    ];

    $query = new WP_Query($args);
    $itemList = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            global $product;

            if (!$product || !is_a($product, 'WC_Product')) {
                continue;
            }

            // Obtener los datos del producto
            $product_name = esc_html(get_the_title());
            $product_url = esc_url(get_permalink());
            $product_image = wp_get_attachment_url(get_post_thumbnail_id());
            $product_description = wp_strip_all_tags(get_the_excerpt());
            $product_price = $product->get_price();
            $availability = ($product->is_in_stock()) ? "https://schema.org/InStock" : "https://schema.org/OutOfStock";

            // Construcción del esquema de cada producto
            $itemList = [
                "@type" => "Product",
                "name" => $product_name,
                "url" => $product_url,
                "image" => $product_image,
                "offers" => [
                    "@type" => "Offer",
                    "price" => $product_price,
                    "priceCurrency" => "EUR",
                    "availability" => $availability,
                    "url" => $product_url
                ]
            ];

            // Agregar la descripción si existe
            if (!empty($product_description)) {
                $itemList["description"] = $product_description;
            }
        }
        wp_reset_postdata(); // Restaurar datos originales de la consulta
    }

    // Construir el array del esquema Service
    $schema_service = [
        "@context" => "https://schema.org",
        "@type" => "Service",
        "@id" => "#Service",
        "name" => $title_service,
        "url" => esc_url(get_term_link($term)),
        "provider" => [
            "@id" => "#Local"
        ],
        "mainEntityOfPage" => [
            "@id" => esc_url(get_term_link($term))
        ]
    ];

    // Agregar la descripción si existe
    if (!empty($description)) {
        $schema_service["description"] = $description;
    }

    // Agregar la imagen si existe
    if (!empty($image_service)) {
        $schema_service["image"] = esc_url($image_service);
    }

    // Agregar los tipos adicionales si existen
    if (!empty($additionalTypeUrls)) {
        $schema_service["additionalType"] = $additionalTypeUrls;
    }

    // Agregar la lista de productos relacionados si existen
    if (!empty($itemList)) {
        $schema_service["hasOfferCatalog"] = [
            "@type" => "OfferCatalog",
            "name" => $title_service,
            "itemListElement" => $itemList
        ];
    }

    // Convertir el array a JSON con formato legible
    $schema_service_json = json_encode($schema_service, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprimir el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL . $schema_service_json . PHP_EOL . '</script>';
}

// Hook para agregar el schema de Service en categorías de WooCommerce
add_action('wp_head', 'generate_schema_service');