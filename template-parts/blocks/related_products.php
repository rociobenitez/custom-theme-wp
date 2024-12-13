<?php
/**
 * Bloque de productos de Woocommerce
 */

$options = get_fields('option');
$page_shop = $options['page_shop'] ?? home_url('/tienda');

// Obtener los valores de ACF
$tagline          = $block['tagline'] ?? '';
$htag_tagline     = $block['htag_tagline'] ?? 3;
$title            = $block['title'] ?? '';
$htag_title       = $block['htag_title'] ?? 1;
$text             = $block['text'] ?? '';
$related_products = $block['related_products'] ?? [];
$htag_products    = $block['htag_products'] ?? 3;
$max_products     = $block['max_products'] ?? 8;
$view_all_url     = $block['view_all_button']['url'] ?? $page_shop;
$view_all_title   = $block['view_all_button']['title'] ?? 'Ver todos los productos';
$view_all_target  = $block['view_all_button']['target'] ?? '_self';
$show_price       = $block['show_price']; // Checkbox para mostrar precios

$default_product_img = get_template_directory_uri() . '/assets/img/default-product.jpg';

// Productos finales que se mostrarán
$final_products = [];

// Añadir productos manuales
if (!empty($related_products)) {
   $final_products = $related_products;
}

// Si los productos manuales no alcanzan el máximo, completar con productos automáticos
if (count($final_products) < $max_products) {
   $args = [
      'post_type'      => 'product',
      'posts_per_page' => $max_products - count($final_products),
      'orderby'        => 'date',
      'order'          => 'DESC',
      'post__not_in'   => wp_list_pluck($final_products, 'ID'), // Excluir productos ya seleccionados
   ];

   $query = new WP_Query($args);

   if ($query->have_posts()) {
      while ($query->have_posts()) {
         $query->the_post();
         $final_products[] = get_post(); // Añadir el producto al array final
      }
   }
   wp_reset_postdata();
}

// Mostrar productos si hay al menos uno
if (!empty($final_products)) :
?>
   <section class="product-listing container my-5">
      <div class="row">
         <div class="col-lg-8 mx-auto">
            <div class="text-center mb-4">
               <?php if (!empty($tagline)) :
                  echo tagTitle($htag_tagline, $tagline, 'tagline', '');
               endif; 
               if (!empty($title)) :
                  echo tagTitle($htag_title, $title, 'heading-2', '');
               endif;
               if (!empty($text)) : ?>
                  <div class="section-text">
                     <?php echo $text; ?>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      </div>

      <!-- Listado de productos -->
      <div class="row gx-3 gy-4">
         <?php foreach ($final_products as $product) :
            $product_id = $product->ID;
            $product_obj = wc_get_product($product_id);

            // Pasar variables necesarias al template
            set_query_var('product_id', $product_id);
            set_query_var('product_obj', $product_obj);
            set_query_var('htag_products', $htag_products);
            set_query_var('default_product_img', $default_product_img);
            set_query_var('show_price', $show_price);
            ?>
            <div class="col-6 col-md-4 col-lg-3">
               <?php get_template_part('template-parts/components/card-product'); ?>
            </div>
         <?php endforeach; ?>
      </div>

      <!-- Botón "Ver todos los productos" -->
      <div class="text-center mt-4">
         <a href="<?php echo esc_url($view_all_url); ?>" class="btn btn-md btn-primary" target="<?php echo $view_all_target; ?>">
            <?php echo esc_html($view_all_title); ?>
         </a>
      </div>
   </section>
<?php endif; ?>
