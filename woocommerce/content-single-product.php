<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

  <div class="container mb-5 mt-4">

    <div class="row">
      <!-- 1) Imagen / gallery -->
      <div class="col-12 col-lg-6">
        <?php
          /**
           * @hooked woocommerce_show_product_sale_flash - 10
           * @hooked woocommerce_show_product_images - 20
           */
          do_action( 'woocommerce_before_single_product_summary' );
        ?>
      </div>

      <!-- 2) Resumen: título / precio / excerpt / botón / meta… -->
      <div class="col-12 col-lg-6 summary entry-summary">
        <?php
          /**
           * @hooked woocommerce_template_single_title        - 5
           * @hooked woocommerce_template_single_rating       - 10
           * @hooked woocommerce_template_single_price        - 10
           * @hooked woocommerce_template_single_excerpt      - 20
           * @hooked woocommerce_template_single_add_to_cart  - 30
           * @hooked woocommerce_template_single_meta         - 40
           * @hooked woocommerce_template_single_sharing      - 50
           * @hooked WC_Structured_Data::generate_product_data() - 60
           */
          do_action( 'woocommerce_single_product_summary' );
        ?>
      </div>
    </div>

    <!-- 3) Upsells / Related en fila aparte -->
    <div class="row mt-5">
      <div class="col-12">
        <?php
          /**
           * @hooked woocommerce_output_product_data_tabs - 10
           * @hooked woocommerce_upsell_display            - 15
           * @hooked woocommerce_output_related_products   - 20
           */
          do_action( 'woocommerce_after_single_product_summary' );
        ?>
      </div>
    </div>

  </div><!-- .container -->

</div><!-- #product-## -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
