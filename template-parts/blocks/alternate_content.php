<?php 
/**
 * Bloque de Texto Alternado con Imagen
 *
 * Recorre el repeater ACF 'alternado' y para cada ítem muestra:
 *  - Imagen a la izquierda o derecha (image_position)
 *  - Contenido con tagline, título, texto y botón
 *
 * Sub‐campos ACF esperados en $args:
 *  - alternado           (array): Repeater de ítems
 *     each item:
 *       - title
 *       - htag_title
 *       - tagline
 *       - htag_tagline
 *       - text
 *       - image (array con url, alt)
 *       - image_position   ('left'|'right')
 *       - button (array con url, title, target)
 *       - text_align       (p.ej. 'text-start','text-center','text-end')
 *  - bg_color (string de CSS color o clase de fondo)
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;

$alternados = $args['alternado'] ?? [];
if ( empty( $alternados ) || ! is_array( $alternados ) ) {
    return;
}

foreach ( $alternados as $item ) :

   // Sub‐campos con fallback
   $title        = $item['title'] ?? '';
   $htag_title   = intval( $item['htag_title'] ?? 2 );
   $tagline      = $item['tagline'] ?? '';
   $htag_tagline = intval( $item['htag_tagline'] ?? 3 );
   $text         = $item['text'] ?? '';
   $image        = $item['image'] ?? [];
   $img_pos      = $item['image_position'] ?? 'right';
   $bg_color     = $item['bg_color'] ?? 'c-bg-light';
   $button       = $item['button'] ?? [];
   $text_align   = $item['text_align'] ?? 'text-start';
   $full_width_img  = ! empty( $item['image_full_width'] ); 

   // Clases para alternar orden en desktop
   $order_text = $img_pos === 'left' ? 'order-lg-2 ps-lg-5' : 'pe-lg-5';
   $order_img  = $img_pos === 'left' ? 'order-lg-1' : '';

   // Si no hay título ni texto ni imagen, saltar
   if ( ! $title && ! $text && empty( $image['url'] ) ) {
      continue;
   }
   ?>
   <section class="alternate-section <?php echo esc_attr( $bg_color ); ?> <?php echo esc_attr( $full_width_img ? 'position-relative' : '' ); ?>">
      <div class="container">
         <div class="row justify-content-between align-items-center">

            <!-- Texto -->
            <div class="col-lg-6 <?php echo esc_attr( $order_text ); ?>">
               <?php if ( ! empty( $tagline ) ) : ?>
                  <?php echo Template_Helpers::tag_title( $htag_tagline, esc_html( $tagline ), 'section-tagline' ); ?>
               <?php endif; ?>

               <?php echo Template_Helpers::tag_title( $htag_title, esc_html( $title ), 'section-title' ); ?>

               <?php if ( ! empty( $text ) ) : ?>
                  <div class="section-text <?php echo esc_attr( $text_align ); ?>">
                     <?php echo wp_kses_post( wpautop( $text ) ); ?>
                  </div>
               <?php endif; ?>

               <?php if ( ! empty( $button ) ) : ?>
                  <a href="<?php echo esc_url( $button['url'] ); ?>"
                     class="btn btn-lg btn-default mt-3"
                     target="<?php echo esc_attr( $button['target'] ?? '_self' ); ?>">
                  <?php echo esc_html( $button['title'] ); ?>
                  </a>
               <?php endif; ?>
            </div>

            <?php
            // --- Design 1: imagen full‐width como background ---
            if ( $full_width_img ) : ?>

               <div class="col-lg-6 <?php echo esc_attr( $order_img ); ?>">
                  <div class="alternate-image cover h-100 <?php echo esc_attr( $img_pos === 'left' ? 'left' : 'right' ); ?>"
                     style="background: url('<?php echo esc_url( $image['url'] ); ?>');"></div>
               </div>

            <?php
            // --- Design 2: layout estándar con imagen + texto en container ---
            else : ?>

               <div class="col-lg-6 <?php echo esc_attr( $order_img ); ?>">
                  <img src="<?php echo esc_url( $image['url'] ); ?>"
                     alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
                     class="img-fluid object-fit-cover">
               </div>

            <?php endif; ?>
            
         </div><!-- .row -->
      </div><!-- .container -->
   </section>

<?php endforeach; ?>