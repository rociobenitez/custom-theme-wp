<?php
/**
 * Genera el esquema JSON-LD para HealthAndBeautyBusiness.
 */
function generate_schema_health_and_beauty_business() {
    // Obtiene todos los campos de las opciones de ACF
    $options = get_fields('option');
    $localbusiness = $options['localbusiness'];

    // Verifica si existen campos de 'localbusiness' en las opciones
    if ( empty( $localbusiness ) ) {
        return;
    }

    // Obtiene la URL principal del sitio (Dominio)
    $site_url = home_url();

    // Obtiene la URL de la página actual
    $page_url = get_permalink();

    // Imagen por defecto y logotipo
    $default_image_option = $options['default_image']['url'];
    $default_image_path   = get_template_directory_uri() . '/assets/img/default-image.webp';
    $logo_option          = $options['site_logo']['url'];
    $logo_path            = get_template_directory_uri() . '/assets/img/logo.svg'

    // Obtiene campos de ACF desde 'localbusiness' y 'options'
    $id_schema       = !empty($localbusiness['id']) ? sanitize_text_field($localbusiness['id']) : '';
    $additional_type = isset($localbusiness['additionaltype']) ? $localbusiness['additionaltype'] : [];
    $name            = !empty($localbusiness['name']) ? sanitize_text_field($localbusiness['name']) : '';
    $alternate_name  = !empty($localbusiness['alternate_name']) ? sanitize_text_field($localbusiness['alternate_name']) : '';
    $area_served     = !empty($localbusiness['area_served']) ? sanitize_text_field($localbusiness['area_served']) : '';
    $description     = !empty($localbusiness['description']) ? sanitize_textarea_field($localbusiness['description']) : '';
    $email           = !empty($options['email']) ? sanitize_email($options['email']) : '';
    $has_map         = !empty($options['link_google_maps']) ? esc_url($options['link_google_maps']) : '';

    // Maneja la imagen destacada o la imagen por defecto
    if ( has_post_thumbnail() ) {
        $image_id  = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $image     = esc_url($image_url);
    } elseif ( !empty( $default_image_option ) ) {
        $image = esc_url($default_image_option);
    } else {
        $image = esc_url($default_image_path);
    }

    $latitude  = !empty($localbusiness['latitude']) ? floatval($localbusiness['latitude']) : 0.0000;
    $longitude = !empty($localbusiness['longitude']) ? floatval($localbusiness['longitude']) : 0.0000;

    // Maneja el logo del sitio
    if (!empty($logo_option)) {
        $logo = esc_url($logo_option);
    } else {
        $logo = $logo_path;
    }

    $price_range      = !empty($localbusiness['price_range']) ? sanitize_text_field($localbusiness['price_range']) : '$$';
    $same_as          = isset($localbusiness['same_as']) ? $localbusiness['same_as'] : [];
    $telephone        = !empty($options['phone']) ? sanitize_text_field($options['phone']) : '';
    $address_country  = 'ES'; // Valor fijo según requerimiento
    $address_locality = !empty($options['city']) ? sanitize_text_field($options['city']) : '';
    $address_region   = !empty($options['province']) ? sanitize_text_field($options['province']) : '';
    $postal_code      = !empty($options['postal_code']) ? sanitize_text_field($options['postal_code']) : '';
    $street_address   = !empty($options['address']) ? sanitize_text_field($options['address']) : '';

    $opening_hours_spec = isset($localbusiness['openinghoursspecification']) ? $localbusiness['openinghoursspecification'] : [];
    $founders           = isset($localbusiness['founders']) ? $localbusiness['founders'] : [];

    // Procesa el campo repetidor 'additional_type'
    $additionalTypeUrls = [];
    if (!empty($additional_type)) {
        foreach ($additional_type as $item) {
            if (!empty($item['url'])) {
                $additionalTypeUrls[] = esc_url($item['url']);
            }
        }
    }

    // Procesa el campo repetidor 'same_as'
    $sameAsUrls = [];
    if (!empty($same_as)) {
        foreach ($same_as as $item) {
            if (!empty($item['url'])) {
                $sameAsUrls[] = esc_url($item['url']);
            }
        }
    }

    // Procesa el campo repetidor 'opening_hours'
    $openingHours = [];
    if (!empty($opening_hours_spec)) {
        foreach ($opening_hours_spec as $day) {
            $opening_hours = [
                "@type" => "OpeningHoursSpecification",
            ];

            if (!empty($day['dayofweek'])) {
                if (is_array($day['dayofweek'])) {
                    $opening_hours['dayOfWeek'] = array_map('sanitize_text_field', $day['dayofweek']);
                } else {
                    $opening_hours['dayOfWeek'] = sanitize_text_field($day['dayofweek']);
                }
            }

            if (!empty($day['opens'])) {
                $opening_hours['opens'] = sanitize_text_field($day['opens']);
            }

            if (!empty($day['closes'])) {
                $opening_hours['closes'] = sanitize_text_field($day['closes']);
            }

            // Asegura que todos los campos necesarios están presentes
            if (isset($opening_hours['dayOfWeek'], $opening_hours['opens'], $opening_hours['closes'])) {
                $openingHours[] = $opening_hours;
            }
        }
    }

    // Procesa el campo repetidor 'founders'
    $founders_arr = [];
    if (!empty($founders)) {
        foreach ($founders as $person) {
            if (!empty($person['name']) && !empty($person['sameAs'])) {
                $founders_arr[] = [
                    "@type" => "Person",
                    "name" => sanitize_text_field($person['name']),
                    "sameAs" => esc_url($person['sameAs'])
                ];
            }
        }
    }

    // Construye el arreglo del esquema HealthAndBeautyBusiness
    $health_and_beauty_business = [
        "@context" => "https://schema.org",
        "@type" => "HealthAndBeautyBusiness",
        "@id" => "#Local",
        "name" => $name,
        "alternateName" => $alternate_name,
        "areaServed" => $area_served,
        "description" => $description,
        "url" => esc_url($site_url),
        "mainEntityOfPage" => [
            "@id" => esc_url($page_url)
        ],
        "email" => sanitize_email($email),
        "hasMap" => $has_map,
        "image" => $image,
        "geo" => [
            "@type" => "GeoCoordinates",
            "latitude" => $latitude,
            "longitude" => $longitude
        ],
        "logo" => $logo,
        "priceRange" => $price_range,
        "telephone" => $telephone,
        "address" => [
            "@type" => "PostalAddress",
            "addressCountry" => $address_country,
            "addressLocality" => $address_locality,
            "addressRegion" => $address_region,
            "postalCode" => $postal_code,
            "streetAddress" => $street_address
        ],
    ];

    // Añade 'additionalType' si existen URLs adicionales
    if (!empty($additionalTypeUrls)) {
        $health_and_beauty_business['additionalType'] = $additionalTypeUrls;
    }

    // Añade 'sameAs' si existen URLs adicionales
    if (!empty($sameAsUrls)) {
        $health_and_beauty_business['sameAs'] = $sameAsUrls;
    }

    // Añade 'openingHoursSpecification' si no está vacío
    if (!empty($openingHours)) {
        $health_and_beauty_business['openingHoursSpecification'] = $openingHours;
    }

    // Añade 'founders' si existen datos adicionales
    if (!empty($founders_arr)) {
        $health_and_beauty_business['founders'] = $founders_arr;
    }

    // Convierte el arreglo a JSON con formato legible
    $health_and_beauty_business_json = json_encode($health_and_beauty_business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprime el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL . $health_and_beauty_business_json . '</script>' . PHP_EOL;
}

// Hook para agregar el schema 'HealthAndBeautyBusiness'
add_action('wp_head', 'generate_schema_health_and_beauty_business');