<?php
/**
 * Block: Latest or Manual Products (“Novedades”)
 *
 * Sub‐campos ACF en $args:
 *  - title, htag_title, tagline, htag_tagline, text, text_align, wrapper_cls (para heading)
 *  - source_products      ('manual'|'latest')
 *  - related_products     (array of IDs)
 *  - show_price        (bool)
 *  - lp_view_all          (array link: url,title,target)
 *
 * @package custom_theme
 */
if ( ! defined('ABSPATH') ) exit;

use Custom_Theme\Helpers\Template_Helpers;

// Lista de IDs
$source = $args['source_products'] ?? 'latest';
$products = [];

if ( $source === 'manual' && ! empty( $args['related_products'] ) ) {
    $products = array_slice( (array) $args['related_products'], 0 );
}

if ( $source === 'latest' ) {
    $q_args = [
      'post_type'      => 'product',
      'orderby'        => 'date',
      'order'          => 'DESC',
      'fields'         => 'ids',
    ];
    $products = get_posts( $q_args );
}

// Si no hay nada, salir
if ( empty( $products ) ) {
    return;
}

// Botón “Ver todos”
$view_all = $args['view_all'] ?? [];
?>

<section class="product-listing container">
   <?php
   // Heading
   get_template_part(
      'template-parts/components/section-heading',
      null,
      [
         'title'        => $args['title'] ?? '',
         'htag_title'   => intval( $args['htag_title']   ?? 2 ),
         'tagline'      => $args['tagline'] ?? '',
         'htag_tagline' => intval( $args['htag_tagline'] ?? 3 ),
         'align_cls'    => 'heading-2 c-secondary text-center mb-0',
         'wrapper_cls'  => 'row col-lg-8 mx-auto mb-4',
      ]
   );
   ?>
  <div class="row gx-3 gy-4 justify-content-center">
    <?php foreach ( $products as $prod_id ) :
      $product = wc_get_product( $prod_id );

      if ( ! $product instanceof WC_Product ) {
        continue;
      }

      get_template_part(
        'template-parts/components/card-product',
        null,
        [
          'product'    => $product,
          'htag'       => $args['htag_products'],
          'show_price' => ! empty( $args['show_price'] ),
        ]
      );
    endforeach; ?>
  </div>

  <?php if ( ! empty( $view_all ) ) : ?>
    <div class="text-center mt-5">
      <a href="<?php echo esc_url( $view_all['url'] ); ?>"
         class="btn btn-lg btn-primary"
         target="<?php echo esc_attr( $view_all['target'] ?? '_self' ); ?>">
        <?php echo esc_html( $view_all['title'] ?? 'Ver todos los productos' ); ?>
      </a>
    </div>
  <?php endif; ?>
</section>
