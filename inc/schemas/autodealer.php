<?php
/**
 * Genera y añade el esquema JSON-LD para LocalBusiness en el <head> de las páginas.
 *
 * Esta función recopila datos desde los campos de opciones configurados con Advanced Custom Fields (ACF)
 * y construye un esquema estructurado siguiendo las directrices de Schema.org para un negocio local.
 * También puede utilizarse para el schema 'AutoDealer'.
 *
 * @hook wp_head Se engancha a la acción 'wp_head' para insertar el script en el encabezado de la página.
 */
function generate_localbusiness_schema() {
   // Obtener el valor de los campos de opciones
   $options     = get_fields('option');

   // Schema localbusiness 
   $localbusiness = $options['localbusiness'];
   if (!$localbusiness) {
      return;
   }

   // Datos de contacto (Opciones)
   $email       = $options['email'];
   $maplink     = $options['google_maps_link'];
   $phone       = $options['phone'];
   $direccion   = $options['address'];
   $city        = $options['city'];
   $provincia   = $options['province'];
   $codpos      = $options['postal_code'];
   $facebook    = $options['facebook'];
   $x_rrss      = $options['x'];
   $instagram   = $options['instagram'];
   $linkedin    = $options['linkedin'];
   $youtube     = $options['youtube'];


   // Logotipo e Imagen por defecto
   $site_logo   = $options['site_logo'];
   $logo_url    = $site_logo
                     ? esc_url($site_logo['url'])
                     : get_template_directory_uri() . 'assets/img/logo.svg';
   
   $img_default     = $options['default_image'];
   $img_default_url = $img_default
                        ? esc_url($img_default['url'])
                        : get_template_directory_uri() . 'assets/img/default-image.webp';

   // Inicializar arrays para "sameAs"
   $sameAsUrls = [];

   // Extraer subcampos con fallback
   $type           = isset($localbusiness['type']) ? $localbusiness['type'] : '';
   $id_schema      = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';
   $additionaltype = isset($localbusiness['additionaltype']) ? $localbusiness['additionaltype'] : [];
   $sameAs         = isset($localbusiness['same_as']) ? $localbusiness['same_as'] : [];
   $knowsabout     = isset($localbusiness['knowsabout']) ? $localbusiness['knowsabout'] : [];
   $name           = isset($localbusiness['name']) ? $localbusiness['name'] : '';
   $alternate_name = isset($localbusiness['alternate_name']) ? $localbusiness['alternate_name'] : '';
   $description    = isset($localbusiness['description']) ? $localbusiness['description'] : '';
   $areaServed     = isset($localbusiness['area_served']) ? $localbusiness['area_served'] : '';
   $priceRange     = isset($localbusiness['price_range']) ? $localbusiness['price_range'] : '$$$';
   $longitude      = isset($localbusiness['longitude']) ? $localbusiness['longitude'] : '';
   $latitude       = isset($localbusiness['latitude']) ? $localbusiness['latitude'] : '';
   $openHoursSpec  = isset($localbusiness['openinghoursspecification']) ? $localbusiness['openinghoursspecification'] : [];
   
   // URLs
   $current_page_url = get_permalink();
   $main_url         = home_url();
   
   // Recopilar enlaces de redes sociales
   $socialLinks = array_filter([
      $facebook,
      $x_rrss,
      $instagram,
      $linkedin,
      $youtube
   ], function($link) {
      return !empty($link);
   });
   
   // Construir el esquema como un array
   $schema = [
      "@context" => "https://schema.org",
      "@type"    => $type,
      "@id"      => "#" . esc_attr($id_schema),
   ];

   if (!empty($additionaltype)) {
      $additionalTypeUrls = array_map(function($item) {
         return esc_url($item['url']);
      }, $additionaltype);
      if (!empty($additionalTypeUrls)) {
         $schema['additionalType'] = $additionalTypeUrls;
      }
   }

   if (!empty($knowsabout)) {
      $knowsAboutUrls = array_map(function($item) {
         return esc_url($item['url']);
      }, $knowsabout);
      if (!empty($knowsAboutUrls)) {
         $schema['knowsAbout'] = $knowsAboutUrls;
      }
   }

   if (!empty($name)) {
      $schema['name'] = esc_html($name);
   }

   if (!empty($alternate_name)) {
      $schema['alternateName'] = esc_html($alternate_name);
   }

   if (!empty($areaServed)) {
      $schema['areaServed'] = esc_html($areaServed);
   }

   if (!empty($description)) {
      $schema['description'] = esc_html($description);
   }

   if (!empty($main_url)) {
      $schema['url'] = esc_url($main_url);
   }

   if (!empty($current_page_url)) {
      $schema['mainEntityOfPage'] = [
         "@id" => esc_url($current_page_url)
      ];
   }

   if (!empty($email)) {
      $schema['email'] = sanitize_email($email);
   }

   if (!empty($maplink)) {
      $schema['hasMap'] = esc_url($maplink);
   }

   if (!empty($img_default_url)) {
      $schema['image'] = esc_url($img_default_url);
   }

   if (!empty($latitude) && !empty($longitude)) {
      $schema['geo'] = [
         "@type" => "GeoCoordinates",
         "latitude" => floatval($latitude),
         "longitude" => floatval($longitude)
      ];
   }

   if (!empty($logo_url)) {
      $schema['logo'] = esc_url($logo_url);
   }

   if (!empty($priceRange)) {
      $schema['priceRange'] = esc_html($priceRange);
   }

   if ( ! empty( $socialLinks ) || ! empty( $sameAs ) ) {
      // Procesar los enlaces de "sameAs"
      if ( ! empty( $sameAs ) ) {
         $processed_sameAs = array_map( function( $item ) {
            return esc_url( $item['url'] );
         }, $sameAs );
         $sameAsUrls = array_merge( $sameAsUrls, $processed_sameAs );
      }

      // Procesar los enlaces de "socialLinks"
      if ( ! empty( $socialLinks ) ) {
         $processed_socialLinks = array_map( 'esc_url', $socialLinks );
         $sameAsUrls = array_merge( $sameAsUrls, $processed_socialLinks );
      }

      // Eliminar duplicados
      $sameAsUrls = array_unique( $sameAsUrls );

      // Asignar al esquema si hay URLs válidas
      if ( ! empty( $sameAsUrls ) ) {
          $schema['sameAs'] = $sameAsUrls;
      }
   }

   if (!empty($phone)) {
      $schema['telephone'] = sanitize_text_field($phone);
   }

   if (!empty($direccion) || !empty($city) || !empty($provincia) || !empty($codpos)) {
      $address = [
         "@type" => "PostalAddress",
         "addressCountry" => "ES"
      ];

      if (!empty($city)) {
         $address['addressLocality'] = esc_html($city);
      }

      if (!empty($provincia)) {
         $address['addressRegion'] = esc_html($provincia);
      }

      if (!empty($codpos)) {
         $address['postalCode'] = esc_html($codpos);
      }

      if (!empty($direccion)) {
         $address['streetAddress'] = esc_html($direccion);
      }

      $schema['address'] = $address;
   }

   if (!empty($openHoursSpec)) {
      $openingHours = array_map(function($day) {
         $opening_hours = [
            "@type" => "OpeningHoursSpecification",
         ];
         
         if (!empty($day['dayofweek'])) {
            if (is_array($day['dayofweek'])) {
                  $opening_hours['dayOfWeek'] = array_map('esc_html', $day['dayofweek']);
            } else {
                  $opening_hours['dayOfWeek'] = esc_html($day['dayofweek']);
            }
         }
         
         if (!empty($day['opens'])) {
            $opening_hours['opens'] = esc_html($day['opens']);
         }
         
         if (!empty($day['closes'])) {
            $opening_hours['closes'] = esc_html($day['closes']);
         }
         
         return $opening_hours;
      }, $openHoursSpec);
      
      // Filtrar cualquier entrada vacía
      $openingHours = array_filter($openingHours, function($day) {
         return isset($day['dayOfWeek']) && isset($day['opens']) && isset($day['closes']);
      });
      
      if (!empty($openingHours)) {
         $schema['openingHoursSpecification'] = array_values($openingHours);
      }
   }
   
   // Convertir el array a JSON con opciones para una mejor legibilidad
   $json_ld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
   
   // Imprimir el script solo si JSON es válido
   if ($json_ld !== false) {
      echo '<script type="application/ld+json">' . $json_ld . '</script>';
   }
}

// Hook para imprimir el schema en el head de la página
add_action('wp_head', 'generate_localbusiness_schema'); 
