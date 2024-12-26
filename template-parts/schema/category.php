<?php
/**
 * Genera y añade el esquema JSON-LD para Categoría de Producto en las páginas de categorías de WooCommerce.
 *
 * Esta función recopila datos dinámicos desde la página de categoría actual de WooCommerce
 * y construye un esquema estructurado siguiendo las directrices de Schema.org para una categoría de producto.
 * El esquema JSON-LD resultante mejora el SEO del sitio al proporcionar información detallada y
 * estructurada sobre la categoría a los motores de búsqueda.
 *
 * @hook wp_head Se engancha a la acción 'wp_head' para insertar el script en el encabezado de la página.
 */
function generate_categoria_schema() {
   // Taxonomías personalizadas
   $taxonomies = ['cambio', 'carroceria', 'combustible'];

   // Verificar si estamos en una categoría de producto estándar o en una taxonomía
   if (!is_product_category() && !is_tax($taxonomies)) {
      return;
   }

   // Obtener la información de la categoría o taxonomía actual
   $current_term = get_queried_object();
   if (!$current_term || is_wp_error($current_term)) {
       return;
   }

   // Schema LocalBusiness
   $localbusiness   = get_field('localbusiness', 'option');
   $type_local      = isset($localbusiness['type']) ? sanitize_text_field($localbusiness['type']) : '';
   $id_schema_local = isset($localbusiness['id']) ? $localbusiness['id'] : 'Local';

   // Obtener la información de la categoría actual
   $cat_name        = $current_term->name;
   $cat_description = $current_term->description;
   $cat_link        = get_term_link($current_term);
   $cat_image_id    = get_term_meta($current_term->term_id, 'thumbnail_id', true);
   $cat_image_url   = wp_get_attachment_url($cat_image_id);

   // Obtener el h1 de la página
   $h1_cat_name  = get_field('main_section_title');

   // Determinar el tipo de página: categoría de producto o taxonomía personalizada
   $is_product_category_page = is_product_category();
   $is_custom_taxonomy_page = is_tax($taxonomies);

   // Construir los argumentos de la consulta según el tipo de página
   if ($is_product_category_page) {
      // Página de categoría de producto: usar 'product_cat' taxonomía
      $tax_query = array(
          array(
              'taxonomy' => 'product_cat',
              'field'    => 'term_id',
              'terms'    => $current_term->term_id,
          ),
      );
   } elseif ($is_custom_taxonomy_page) {
      // Página de taxonomía personalizada: usar la taxonomía actual
      $tax_query = array(
          array(
              'taxonomy' => $current_term->taxonomy,
              'field'    => 'term_id',
              'terms'    => $current_term->term_id,
          ),
      );
   } else {
      return;
   }

   // Obtener los productos en la categoría o taxonomía para extraer precios y contar vehículos
   $args = array(
      'post_type'      => 'product',
      'posts_per_page' => -1,
      'tax_query'      => $tax_query,
      'meta_query'     => array(
          array(
              'key'     => '_price',
              'value'   => '',
              'compare' => '!=',
          ),
      ),
   );

   $query         = new WP_Query($args);
   $price_low     = 0;
   $price_high    = 0;
   $vehicle_count = $query->found_posts;

   if ($query->have_posts()) {
      while ($query->have_posts()) {
         $query->the_post();
         $price = get_post_meta(get_the_ID(), '_price', true);
         if ($price) {
            $price = floatval($price);
            if ($price_low === 0 || $price < $price_low) {
               $price_low = $price;
            }
            if ($price > $price_high) {
               $price_high = $price;
            }
         }
      }
      wp_reset_postdata();
   }

   // Construir el esquema como un array
   $schema = [
      "@context" => "https://schema.org",
      "@type"    => "Vehicle",
      "@id"      => "#Categoria",
      "name"     => !empty($h1_cat_name) ? esc_html($h1_cat_name) : esc_html($cat_name),
   ];

   // Condicional para añadir 'brand' y 'model' según el tipo de página
   if (is_product_category() && !is_tax($taxonomies)) {
      if ($current_term->parent == 0) {
          // Página de marca (categoría padre): añadir solo 'brand'
          if (!empty($cat_name)) {
              $schema['brand'] = [
                  "@type" => "Brand",
                  "name"  => esc_html($cat_name),
              ];
          }
      } else {
          // Página de modelo (subcategoría): añadir 'brand' y 'model'
          // Obtener la categoría padre (marca)
          $parent_cat = get_term($current_term->parent, 'product_cat');
          if (!empty($parent_cat) && !is_wp_error($parent_cat)) {
              $schema['brand'] = [
                  "@type" => "Brand",
                  "name"  => esc_html($parent_cat->name),
              ];
          }

          // Añadir 'model' con el nombre de la subcategoría
          if (!empty($cat_name)) {
              $schema['model'] = esc_html($cat_name);
          }
      }
   }

   // Añadir campos específicos para taxonomías personalizadas
   if ($is_custom_taxonomy_page) {
      // Verificar el nombre de la taxonomía actual y añadir el campo correspondiente
      if ($current_term->taxonomy == 'combustible') {
          $schema['fuelType'] = esc_html($current_term->name);
      } elseif ($current_term->taxonomy == 'carroceria') {
          $schema['bodyType'] = esc_html($current_term->name);
      }
  }

   // Validar y añadir la descripción si no está vacía
   if (!empty(trim($cat_description))) {
      $schema['description'] = esc_html($cat_description);
   }

   if(!empty($cat_link)) {
      $schema['mainEntityOfPage'] = [
         "@id"   => esc_url($cat_link)
      ];
   }

   $schema["itemCondition"] = "https://schema.org/UsedCondition";

   // Añadir la imagen si está disponible
   if (!empty($cat_image_url)) {
      $schema['image'] = esc_url($cat_image_url);
   }

   // Añadir las ofertas agregadas si hay precios válidos
   if ($price_low > 0 && $price_high > 0) {
      $schema['offers'] = [
         "@type"         => "AggregateOffer",
         "lowPrice"      => $price_low,
         "highPrice"     => $price_high,
         "offerCount"    => $vehicle_count,
         "priceCurrency" => "EUR",
         "availability"  => "https://schema.org/InStock",
         "seller"        => [
               "@type" => $type_local,
               "@id"   => "#$id_schema_local"
         ]
      ];
   }

   // Convertir el array a JSON con opciones para una mejor legibilidad
   $json_ld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

   // Imprimir el script solo si JSON es válido
   if ($json_ld !== false) {
      echo '<script type="application/ld+json">' . $json_ld . '</script>';
   }
}

add_action('wp_head', 'generate_categoria_schema');
?>
