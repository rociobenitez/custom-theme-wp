<?php
/**
 * Bloque de Características
 *
 * Campos ACF esperados en $args:
 * - show_heading (bool)
 * - title, htag_title, tagline, htag_tagline, text, text_align, wrapper_cls
 * - background_color
 * - featured_with_image (bool)
 * - position_image ('top'|'bg')
 * - featured_items_images (array)
 * - featured_items (array)
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;

// --- Tipo de tarjeta ---
$item_type = $args['item_type'] ?? 'default';
$with_img = ! empty( $args['featured_with_image'] );
$items = $args['featured_items'] ?? [];

// Early return si no hay nada
if ( empty( $items ) ) {
    return;
}

// --- Dinámica de columnas ---
$count = count( $items );
if ( $count === 1 ) {
    $col_class = 'col-lg-8 mx-auto';
} elseif ( in_array( $count, [2,4], true ) ) {
    $col_class = 'col-md-6 col-lg-6';
} else {
    // 3, 6 o cualquier otro
    $col_class = 'col-md-6 col-lg-4';
}
?>
<section class="features-section features-<?php echo esc_attr( $item_type ); ?> 
  <?php echo $with_img ? 'features-img' : 'features-text'; ?> py-5">
  <div class="container">

    <?php // --- Cabecera del bloque ---
    get_template_part(
        'template-parts/components/section-heading',
        null,
        [
            'tagline'      => $args['tagline'] ?? '',
            'htag_tagline' => intval( $args['htag_tagline'] ?? 3 ),
            'title'        => $args['title'] ?? '',
            'htag_title'   => intval( $args['htag_title'] ?? 2 ),
            'text'         => $args['text'] ?? '',
            'align_cls'    => $args['text_align'] ?? 'text-center mt-5 mb-4',
            'wrapper_cls'  => $args['wrapper_cls'] ?? 'col-md-8 mx-auto mb-5',
        ]
    );
    ?>

    <div class="row justify-content-center align-items-strech">

      <?php foreach ( $items as $i => $item ) :
        $number = $i + 1;

        // Campos comunes
        $feat_title       = $item['title']         ?? '';
        $feat_htag        = intval( $item['htag_title'] ?? 4 );
        $feat_description = $item['description']   ?? '';
        $description_html = wp_kses_post( wpautop( $feat_description ) );

        // Cuando usas imagen:
        if ( $with_img ) {
            $img_src  = $item['image']['url'] ?? '';
            $img_alt  = $item['image']['alt'] ?? '';
            $position = $args['position_image'] ?? 'top';
        }

        // Si no hay título, saltamos
        if ( ! $feat_title ) {
            continue;
        }
        ?>

        <div class="feature-col <?php echo esc_attr( $col_class ); ?> mb-4">

          <?php // Imagen arriba
          if ( $with_img && $position === 'top' && $img_src ) : ?>
            <div class="feature-item feature-item-img-top h-100 d-flex flex-column">
                <figure class="overflow-hidden mb-0">
                    <img src="<?php echo esc_url( $img_src ); ?>"
                        alt="<?php echo esc_attr( $img_alt ); ?>"
                        class="img-fluid h-100 w-100 object-fit-cover">
                </figure>
                <div class="feature-content d-flex flex-column">
                    <?php if ( $item_type === 'step' ) : ?>
                      <div class="feature-number mb-2"><?php echo esc_html( $number ); ?>.</div>
                    <?php endif; ?>
                    <?php echo Template_Helpers::tag_title( $feat_htag, $feat_title, 'feature-item-title mb-2', '' ); ?>
                    <div class="feature-description mx-auto">
                        <?php echo $description_html; ?>
                    </div>
                </div>
            </div>

          <?php // Imagen de fondo
          elseif ( $with_img && $position === 'bg' && $img_src ) : ?>
            <div class="feature-item feature-item-img-bg position-relative overflow-hidden cover"
                 style="background: url('<?php echo esc_url( $img_src ); ?>');">
              <div class="feature-overlay p-4 d-flex flex-column justify-content-center align-items-center text-center h-100">
                <?php echo Template_Helpers::tag_title( $feat_htag, $feat_title, 'feature-item-title text-white mb-2', '' ); ?>
                <div class="feature-description text-white">
                  <?php echo $description_html; ?>
                </div>
              </div>
            </div>

          <?php else : ?>
            <div class="feature-item feature-item-text h-100 d-flex flex-column justify-content-center">
              <?php echo Template_Helpers::tag_title( $feat_htag, $feat_title, 'feature-item-title mb-2', '' ); ?>
              <div class="feature-description mx-auto">
                <?php echo $description_html; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>

      <?php $count+=1;
      endforeach; ?>

    </div>
  </div>
</section>
