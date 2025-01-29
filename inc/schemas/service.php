<?php
/**
 * Genera e inserta el esquema JSON-LD para 'Service'
 */

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
   $options = get_fields('option');
   $fields  = get_fields();

   if ( ! $options ) {
      return ''; // No hay datos, salir
   }

   $localbusiness = isset( $options['localbusiness'] ) ? $options['localbusiness'] : [];

   if ( empty( $localbusiness ) ) {
      return ''; // No hay datos de localbusiness, salir
   }

   // Obtener el ID del esquema
   $id_schema      = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';
   $additionaltype = isset($localbusiness['additionaltype']) ? $localbusiness['additionaltype'] : [];

   // Obtener los campos de servicio
   // 1. Nombre
   if ( ! empty( $fields['service_name'] ) ) {
      $name = esc_html( $fields['service_name'] );
   } elseif ( ! empty( $fields['title_pageheader'] ) ) {
      $name = esc_html( $fields['title_pageheader'] );
   } else {
       $name = esc_html( get_the_title() );
   }

   // 2. Descripción
   if ( ! empty( $fields['service_description'] ) ) {
      $description = esc_html( $fields['service_description'] );
   } else {
      // Obtener la meta descripción de Yoast SEO
      $description = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
      $description = ! empty( $description ) ? esc_html( $description ) : '';
   }

   // 3. Imagen
   if ( has_post_thumbnail() ) {
      $image_id  = get_post_thumbnail_id();
      $image_url = wp_get_attachment_image_url( $image_id, 'full' );
      $image     = esc_url( $image_url );
   } elseif ( ! empty( $options['default_image']['url'] ) ) {
      $image = esc_url( $options['default_image']['url'] );
   } else {
      $image = get_template_directory_uri() . '/assets/img/default-image.webp';
   }

   // 4. AdditionalType
   $additionalTypeUrls = [];
   if ( ! empty( $fields['service_additionaltype'] ) && is_array( $fields['service_additionaltype'] ) ) {
      foreach ( $fields['service_additionaltype'] as $item ) {
         if ( isset( $item['url'] ) && ! empty( $item['url'] ) ) {
            $additionalTypeUrls[] = esc_url( $item['url'] );
         }
      }
   }

   // Obtener la URL actual de la página
   $page_url = get_permalink();

   // Construir el array del esquema Service
   $service_schema = [
      "@context"    => "https://schema.org",
      "@type"       => "Service",
      "@id"         => "#Service",
      "name"        => $name,
      "description" => $description,
      "image"       => $image,
      "url"         => esc_url( $page_url ),
      "provider"    => [
         "@id" => '#' . $id_schema,
      ],
      "mainEntityOfPage" => [
         "@id"   => esc_url( $page_url ),
      ],
   ];

   if (!empty($additionaltype)) {
      $service_schema['additionalType'] = $additionalTypeUrls;
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

    // Imprimir el script JSON-LD en el head
    echo '<script type="application/ld+json">' . PHP_EOL . $service_json_ld . '</script>' . PHP_EOL;
}

add_action( 'wp_head', 'insert_custom_schemas' ); 
