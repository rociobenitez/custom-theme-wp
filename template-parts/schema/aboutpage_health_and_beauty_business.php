<?php
/**
 * Genera e inserta los esquemas JSON-LD para AboutPage y HealthAndBeautyBusiness.
 */

if (!defined('ABSPATH')) {
    exit; // Evita el acceso directo al archivo
}

/**
 * Genera el esquema JSON-LD para AboutPage.
 */
function generate_schema_about_page() {
    // Verifica si estamos en una página
    if (!is_page()) {
        return;
    }

    // Obtiene el template de la página actual
    $template = get_page_template_slug();

    // Verifica si el template es 'page-about.php'
    if ('page-about.php' !== $template) {
        return;
    }

    // Obtiene la URL actual de la página
    $current_url = get_permalink();

    // Construye el arreglo del esquema AboutPage
    $about_page = [
        "@context" => "https://schema.org",
        "@id" => $current_url,
        "@type" => "AboutPage",
        "mainEntity" => [
            "@id" => "#Local"
        ]
    ];

    // Convierte el arreglo a JSON con formato legible
    $about_page_json = json_encode($about_page, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprime el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL;
    echo $about_page_json;
    echo '</script>' . PHP_EOL;
}

/**
 * Genera el esquema JSON-LD para HealthAndBeautyBusiness.
 */
function generate_schema_health_and_beauty_business() {
    // Obtiene todos los campos de las opciones de ACF
    $options = get_fields('option');

    // Verifica si existen campos de 'localbusiness' en las opciones
    if (empty($options['localbusiness'])) {
        return;
    }

    $localbusiness = $options['localbusiness'];

    // Obtiene la URL principal del sitio (Dominio)
    $site_url = home_url();

    // Obtiene la URL de la página actual
    $page_url = get_permalink();

    // Obtiene campos de ACF desde 'localbusiness' y 'options'
    $id_schema        = !empty($localbusiness['id']) ? sanitize_text_field($localbusiness['id']) : '';
    $name             = !empty($localbusiness['name']) ? sanitize_text_field($localbusiness['name']) : '';
    $alternate_name   = !empty($localbusiness['alternate_name']) ? sanitize_text_field($localbusiness['alternate_name']) : '';
    $area_served      = !empty($localbusiness['area_served']) ? sanitize_text_field($localbusiness['area_served']) : '';
    $description      = !empty($localbusiness['description']) ? sanitize_textarea_field($localbusiness['description']) : '';
    $email            = !empty($options['email']) ? sanitize_email($options['email']) : '';
    $has_map          = !empty($options['link_google_maps']) ? esc_url($options['link_google_maps']) : '';

    // Maneja la imagen destacada o la imagen por defecto
    if (has_post_thumbnail()) {
        $image_id  = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $image     = esc_url($image_url);
    } elseif (!empty($options['default_image']['url'])) {
        $image = esc_url($options['default_image']['url']);
    } else {
        $image = get_template_directory_uri() . '/assets/img/default-image.webp';
    }

    $latitude         = !empty($localbusiness['latitude']) ? floatval($localbusiness['latitude']) : 0.0000;
    $longitude        = !empty($localbusiness['longitude']) ? floatval($localbusiness['longitude']) : 0.0000;

    // Maneja el logo del sitio
    if (!empty($options['site_logo']['url'])) {
        $logo = esc_url($options['site_logo']['url']);
    } else {
        $logo = get_template_directory_uri() . '/assets/img/logo.svg';
    }

    $price_range      = !empty($localbusiness['price_range']) ? sanitize_text_field($localbusiness['price_range']) : '$$';
    $telephone        = !empty($options['phone']) ? sanitize_text_field($options['phone']) : '';
    $address_country  = 'ES'; // Valor fijo según requerimiento
    $address_locality = !empty($options['city']) ? sanitize_text_field($options['city']) : '';
    $address_region   = !empty($options['province']) ? sanitize_text_field($options['province']) : '';
    $postal_code      = !empty($options['postal_code']) ? sanitize_text_field($options['postal_code']) : '';
    $street_address   = !empty($options['address']) ? sanitize_text_field($options['address']) : '';

    $additional_type    = isset($localbusiness['additional_type']) ? $localbusiness['additional_type'] : [];
    $same_as            = isset($localbusiness['same_as']) ? $localbusiness['same_as'] : [];
    $opening_hours_spec = isset($localbusiness['opening_hours']) ? $localbusiness['opening_hours'] : [];
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
        "areaServed"    => $area_served,
        "description"   => $description,
        "url" => esc_url($site_url),
        "mainEntityOfPage" => [
            "@id" => esc_url($page_url)
        ],
        "email"  => sanitize_email($email),
        "hasMap" => $has_map,
        "image"  => $image,
        "geo" => [
            "@type"     => "GeoCoordinates",
            "latitude"  => $latitude,
            "longitude" => $longitude
        ],
        "logo"       => $logo,
        "priceRange" => $price_range,
        "sameAs"     => $sameAsUrls,
        "telephone"  => $telephone,
        "address" => [
            "@type"           => "PostalAddress",
            "addressCountry"  => $address_country,
            "addressLocality" => $address_locality,
            "addressRegion"   => $address_region,
            "postalCode"      => $postal_code,
            "streetAddress"   => $street_address
        ],
        "openingHoursSpecification" => $openingHours,
        "founders" => $founders_arr
    ];

    // Añade 'additionalType' si existen URLs adicionales
    if (!empty($additionalTypeUrls)) {
        $health_and_beauty_business['additionalType'] = $additionalTypeUrls;
    }

    // Convierte el arreglo a JSON con formato legible
    $health_and_beauty_business_json = json_encode($health_and_beauty_business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // Imprime el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL;
    echo $health_and_beauty_business_json;
    echo '</script>' . PHP_EOL;
}

/**
 * Inserta los esquemas JSON-LD en el head mediante hooks.
 */
function insert_schema_structured_data() {
    // Genera el esquema AboutPage si corresponde
    generate_schema_about_page();

    // Genera el esquema HealthAndBeautyBusiness
    generate_schema_health_and_beauty_business();
}

// Añade la función al hook 'wp_head' para insertar los esquemas en el <head>
add_action('wp_head', 'insert_schema_structured_data');
