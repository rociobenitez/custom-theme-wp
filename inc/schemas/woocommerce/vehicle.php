<?php
/**
 * Genera y añade el esquema JSON-LD para Vehicle en las páginas de productos de WooCommerce.
 *
 * Esta función recopila datos dinámicos desde la página de producto actual de WooCommerce
 * y construye un esquema estructurado siguiendo las directrices de Schema.org para un vehículo.
 * El esquema JSON-LD resultante mejora el SEO del sitio al proporcionar información detallada y
 * estructurada sobre el vehículo a los motores de búsqueda.
 *
 * @hook wp_head Se engancha a la acción 'wp_head' para insertar el script en el encabezado de la página.
 */

if (!defined('ABSPATH')) {
    exit; // Evita el acceso directo al archivo
}

function generate_vehicle_schema() {
    // Verificar si es una página de producto
    if ( !is_product() ) {
        return;
    }

    // Obtener el objeto del producto de WooCommerce
    $product = wc_get_product(get_the_ID());

    if ( ! $product ) {
        return;
    }

    // Obtener la URL del producto
    $product_link = get_permalink($post->ID);

    // Obtener la imagen destacada del producto
    $image_id  = get_post_thumbnail_id($post->ID);
    $image_url = wp_get_attachment_url($image_id);

    // Obtener el precio del producto
    $price = $product->get_price(); 

    // Obtener la categoría del producto
    $terms = get_the_terms($post->ID, 'product_cat');
    $brand = '';
    $model = '';

    if ($terms && !is_wp_error($terms)) {
        // El producto tiene una categoría padre (marca) y una subcategoría (modelo)
        foreach ($terms as $term) {
            if ($term->parent == 0) {
                $brand = $term->name;
            } else {
                $model = $term->name;
            }
        }
    }

    // Obtener los campos de ACF
    $vehicle_model_date = get_field('ano_matriculacion', $post->ID);
    $mileage_odometer   = get_field('kilometros', $post->ID);

    // Obtener términos de taxonomías personalizadas
    $fuel_types  = get_the_terms($post->ID, 'combustible'); // Tipo de combustible
    $body_types  = get_the_terms($post->ID, 'carroceria');  // Tipo de carrocería

    // Obtener la configuración de LocalBusiness desde ACF (opciones)
    $localbusiness = get_field('localbusiness', 'option');
    if (!$localbusiness) {
        return;
    }
    $type_local      = isset($localbusiness['type']) ? sanitize_text_field($localbusiness['type']) : 'LocalBusiness';
    $id_schema_local = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';

    // Obtener el vendedor desde LocalBusiness (suponiendo que el nombre del vendedor está en 'nombre_vendedor')
    $seller_name = isset($localbusiness['nombre_vendedor']) ? sanitize_text_field($localbusiness['nombre_vendedor']) : '';

    // Construir el esquema como un array
    $schema = [
        "@context"    => "https://schema.org",
        "@type"       => "Vehicle",
        "name"        => esc_html(get_the_title()),
        "description" => esc_html(get_the_excerpt()),
        "url"         => esc_url($product_link),
    ];

    // Añadir la imagen si está disponible
    if (!empty($image_url)) {
        $schema['image'] = esc_url($image_url);
    }

    // Añadir la marca si está disponible
    if (!empty($brand)) {
        $schema['brand'] = [
            "@type" => "Brand",
            "name"  => esc_html($brand),
        ];
    }

    // Añadir el modelo si está disponible
    if (!empty($model)) {
        $schema['model'] = esc_html($model);
    }

    // Añadir el año (vehicleModelDate) si está disponible
    if (!empty($vehicle_model_date)) {
        $schema['vehicleModelDate'] = esc_html($vehicle_model_date);
    }

    // Añadir los kilómetros (mileageFromOdometer) si están disponibles
    if (!empty($mileage_odometer)) {
        $schema['mileageFromOdometer'] = [
            "@type"       => "QuantitativeValue",
            "value"       => intval($mileage_odometer),
            "unitCode"    => "KM"
        ];
    }

    // Añadir el tipo de combustible (fuelType) si está disponible
    if ($fuel_types && !is_wp_error($fuel_types)) {
        $fuel_type_names = wp_list_pluck($fuel_types, 'name');
        $schema['fuelType'] = implode(", ", array_map('esc_html', $fuel_type_names));
    }

    // Añadir el tipo de carrocería (bodyType) si está disponible
    if ($body_types && !is_wp_error($body_types)) {
        $body_type_names = wp_list_pluck($body_types, 'name');
        $schema['bodyType'] = implode(", ", array_map('esc_html', $body_type_names));
    }

    // Añadir las ofertas agregadas si hay precios válidos
    if ($price > 0) {
        $schema['offers'] = [
            "@type"           => "Offer",
            "price"           => number_format($price, 2, '.', ''),
            "priceCurrency"   => "EUR",
            "availability"    => "https://schema.org/InStock",
            "itemCondition"   => "https://schema.org/UsedCondition",
            "seller"          => [
                "@type" => $type_local,
                "@id"   => "#$id_schema_local"
            ]
        ];

        // Añadir el nombre del vendedor si está disponible
        if (!empty($seller_name)) {
            $schema['offers']['seller']['name'] = esc_html($seller_name);
        }
    }

    // Añadir mainEntityOfPage
    $schema['mainEntityOfPage'] = [
        "@id"   => esc_url($product_link)
    ];

    // Convertir el array a JSON con opciones para una mejor legibilidad
    $json_ld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprimir el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL . $json_ld . '</script>' . PHP_EOL;
}

add_action('wp_head', 'generate_vehicle_schema');
