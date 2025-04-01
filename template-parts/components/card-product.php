<?php
/**
 * Componente: Product Card
 * 
 * Variables esperadas:
 * - $product_obj: Objeto del producto (`WC_Product`).
 * - $product_id: ID del producto.
 * - $htag_products: Nivel de etiqueta HTML para el título del producto.
 * - $default_product_img: Imagen predeterminada si no hay miniatura.
 * - $show_price: Booleano para mostrar el precio.
 */

// Validar que se pase el objeto del producto
if (!isset($product_obj) || !isset($product_id)) {
    return; // Salir si faltan datos necesarios
}
?>
<div class="product-item card border-0">
   <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="product-link">
      <!-- Imagen del producto -->
      <div class="product-image">
         <?php if (has_post_thumbnail($product_id)) : ?>
            <?php echo get_the_post_thumbnail($product_id, 'medium', ['class' => 'card-img-top rounded']); ?>
         <?php elseif ($default_product_img) : ?>
            <img src="<?php echo esc_url($default_product_img); ?>" class="card-img-top rounded"
               alt="<?php echo esc_attr(get_the_title($product_id)); ?>" >
         <?php else : ?>
            <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" class="card-img-top rounded"
               alt="<?php echo esc_attr(get_the_title($product_id)); ?>" >
         <?php endif; ?>
      </div>

      <!-- Detalles del producto -->
      <div class="card-body px-0">
         <?php echo tagTitle($htag_products, get_the_title($product_id), 'heading-6 product-title', ''); ?>

         <?php if ($show_price && $product_obj->get_price_html()) : ?>
            <p class="product-price text-muted mb-0">
               <?php echo $product_obj->get_price_html(); ?>
            </p>
         <?php endif; ?>
      </div>
   </a>
</div>
