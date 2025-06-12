<?php
/**
 * Card Product Component
 *
 * Variables esperadas:
 *  - product (WC_Product)
 *  - show_price (bool)
 */
if ( ! defined('ABSPATH') ) exit;

use Custom_Theme\Helpers\Template_Helpers;

$product    = $args['product'];
$show_price = ! empty( $args['show_price'] );
?>

<div class="col-6 col-md-4 col-lg-3">
  <div class="card h-100 border-0">
    <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
      <?php echo $product->get_image( 'medium', ['class'=>'card-img-top rounded-0'] ); ?>
    </a>
    <div class="card-body text-center">
      <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="text-decoration-none">
         <?php echo Template_Helpers::tag_title( $args['htag'], esc_html( $product->get_name() ), 'card-title fs-6' ); ?>
      </a>
      <?php if ( $show_price ) : ?>
        <div class="card-price">
          <?php echo wp_kses_post( $product->get_price_html() ); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
