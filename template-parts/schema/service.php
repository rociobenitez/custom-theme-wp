<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Páginas donde se mostrará el schema
// $array_pages_schema = ['servicios', 'servicios-2'];  // Editar esta parte según el proyecto

/**
 * Genera el esquema JSON-LD para Service.
 *
 * @return string JSON-LD para el esquema Service.
 */
function generate_service_schema() {

   // Obtener datos dinámicos desde ACF
   $options        = get_fields('option');
   $localbusiness  = $options['localbusiness'];
   $id_schema      = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';
   $additionaltype = isset($localbusiness['additionaltype']) ? $localbusiness['additionaltype'] : [];
   $sameAs         = isset($localbusiness['same_as']) ? $localbusiness['same_as'] : [];
   $description    = isset($localbusiness['description']) ? $localbusiness['description'] : '';
   $name           = isset($localbusiness['name']) ? $localbusiness['name'] : '';
   $alternate_name = isset($localbusiness['alternate_name']) ? $localbusiness['alternate_name'] : '';
   $areaServed     = isset($localbusiness['area_served']) ? $localbusiness['area_served'] : '';
   $priceRange     = isset($localbusiness['price_range']) ? $localbusiness['price_range'] : '$$$';
   $longitude      = isset($localbusiness['longitude']) ? $localbusiness['longitude'] : '';
   $latitude       = isset($localbusiness['latitude']) ? $localbusiness['latitude'] : '';
   $openHoursSpec  = isset($localbusiness['openinghoursspecification']) ? $localbusiness['openinghoursspecification'] : [];

   // Datos de contacto y dirección
   $email           = $options['email'];
   $maplink         = $options['link_google_maps'];
   $phone           = $options['telefono'];
   $direccion       = $options['direccion'];
   $ciudad          = $options['ciudad'];
   $provincia       = $options['provincia'];
   $codpos          = $options['codpos'];
   $address_country = 'ES';

   // Logotipo
   $site_logo   = $options['site_logo'];
   $logo_url    = $site_logo
                     ? esc_url($site_logo['url'])
                     : get_template_directory_uri() . '/assets/img/logotipo.svg';

   // Imagen
   $img_default = $options['default_image'];
   $image       = $img_default
                     ? esc_url($img_default['url'])
                     : get_template_directory_uri() . '/assets/img/default-background.jpg';

   $page_url    = get_permalink(); // URL de la página actual

   // Construir el array del esquema Service
   $service_schema = [
      "@context"    => "https://schema.org",
      "@type"       => "Service",
      "@id"         => "#Service",
      "description" => !empty($description) ? esc_html( $description ) : '',
      "image"       => !empty($image) ? esc_url( $image ) : '',
      "name"        => !empty($name) ? esc_html( $name ) : '',
      "url"         => esc_url( $page_url ),
      "provider"    => [
         "@id" => '#' . $id_schema,
      ],
      "mainEntityOfPage" => [
         "@id"   => esc_url( $page_url ),
      ],
   ];

   if (!empty($additionaltype)) {
      $additionalTypeUrls = array_map(function($item) {
         return esc_url($item['url']);
      }, $additionaltype);
      if (!empty($additionalTypeUrls)) {
         $service_schema['additionalType'] = $additionalTypeUrls;
      }
   }
   
   // Eliminar campos vacíos para mantener el esquema limpio
   $service_schema = array_filter($service_schema, function($value) {
      return !empty($value);
   });
   
   return json_encode( $service_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
}


/**
 * Inserta los esquemas JSON-LD en las páginas específicas.
 */
function insert_custom_schemas() {

   global $array_pages_schema;

    // Verificar si estamos en una de las dos páginas específicas
    if ( ! is_page( $array_pages_schema ) ) {
        return;
    }
    
    // Obtener el ID de la página para obtener los campos ACF específicos
    $page_id = get_queried_object_id();
    
    // Obtener el nombre de la página
    $page_slug = get_post_field( 'post_name', $page_id );
    
    // Asegurarse de que estamos en una de las páginas específicas
    if ( ! in_array( $page_slug, $array_pages_schema , true ) ) {
        return;
    }
    
    // Generar y mostrar el esquema Service
    $service_json_ld = generate_service_schema();
    if ( ! empty( $service_json_ld ) ) {
        echo '<script type="application/ld+json">' . $service_json_ld . '</script>' . "\n";
    }
}

// add_action( 'wp_head', 'insert_custom_schemas' );  // Descomentar esta línea si se desea incluir el schema 'service'
?>
