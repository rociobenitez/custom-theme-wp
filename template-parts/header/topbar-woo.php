<?php
/**
 * Topbar para WooCommerce:
 * - Buscador de productos
 * - Enlace al carrito con contador
 * - (Opcional) Enlace a favoritos
 * - Enlace a Mi Cuenta / Login/Register
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>
<div class="topbar d-none d-lg-block bg-light py-2">
  <div class="container">
    <div class="d-flex justify-content-end align-items-center">

      <!-- 1) Buscador de productos -->
      <div class="topbar-search me-3">
        <?php get_product_search_form(); ?>
      </div>

      <div class="topbar-icons d-flex align-items-center gap-3">

        <!-- 2) Carrito -->
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="position-relative">
          <i class="bi bi-bag fs-5"></i>
          <?php if ( WC()->cart->get_cart_contents_count() ) : ?>
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
              <?php echo WC()->cart->get_cart_contents_count(); ?>
            </span>
          <?php endif; ?>
        </a>

        <!-- 3) Favoritos (si existe plugin YITH or similar) -->
        <?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
          <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>">
            <i class="bi bi-heart fs-5"></i>
          </a>
        <?php endif; ?>

        <!-- 4) Mi Cuenta / Login -->
        <?php if ( is_user_logged_in() ) : ?>
          <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>">
            <i class="bi bi-person-circle fs-5"></i>
          </a>
        <?php else : ?>
          <a href="<?php echo esc_url( wc_get_page_permalink('myaccount') ); ?>">
            <i class="bi bi-person fs-5"></i>
          </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
