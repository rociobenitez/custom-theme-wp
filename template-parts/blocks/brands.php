<?php
/**
 * Block: Brands (Product Brands)
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;

// Solo si WooCommerce está activo
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// Campos ACF
$fields = [
    'title'        => $args['title'] ?? '',
    'htag_title'   => $args['htag_title'] ?? 2,
    'tagline'      => $args['tagline'] ?? '',
    'htag_tagline' => $args['htag_tagline'] ?? 3,
    'text'         => $args['text'] ?? '',
    'button'       => $args['button'] ?? '',
    'align_cls'    => 'heading-2 text-center',
    'wrapper_cls'  => 'row col-lg-8 mx-auto',
];

// Obtener términos product_brand ordenados por meta 'brand_order'
$brands = get_terms( [
    'taxonomy'   => 'product_brand',
    'hide_empty' => false, // cambiar a true para mostrar solo las marcas con productos
    // 'meta_key'   => 'brand_order',
    // 'orderby'    => 'meta_value_num',
    // 'order'      => 'ASC',
] );

if ( empty( $brands ) || is_wp_error( $brands ) ) {
    return;
}
?>

<section class="brands-section">
    <div class="container">
        <?php // Encabezado de sección (opcional)
        get_template_part( 'template-parts/components/section-heading', null, $fields );
        ?>
        <div class="row justify-content-center align-items-center g-4">
          <?php foreach ( $brands as $brand ) :
      
            // miniatura
            $thumb_id = get_term_meta( $brand->term_id, 'thumbnail_id', true );
            $logo_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';
      
            if ( ! $logo_url ) {
              continue;
            }
            ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center">
              <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>"
                 class="d-block py-2">
                <img src="<?php echo esc_url( $logo_url ); ?>"
                     alt="<?php echo esc_attr( $brand->name ); ?>"
                     class="img-fluid mx-auto"
                     width="150" height="150" />
              </a>
            </div>
          <?php endforeach; ?>
        </div>
    </div>
</section>
