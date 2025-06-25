<?php
/**
* Bloque de Preguntas Frecuentes (FAQs)
 *
 * Renderiza un acordeón de FAQs basado en ACF Flexible Content.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Custom_Theme\Helpers\Template_Helpers;

// Extraer y validar variables
$faqs         = ! empty( $args['faq'] ) && is_array( $args['faq'] ) ? $args['faq'] : [];
$htag_faq     = ! empty( $args['htag_faq'] )     ? $args['htag_faq'] : 2;
$tagline      = ! empty( $args['tagline'] )      ? $args['tagline'] : '';
$htag_tagline = ! empty( $args['htag_tagline'] ) ? $args['htag_tagline'] : 'h3';
$title        = ! empty( $args['title'] )        ? $args['title'] : '';
$htag_title   = ! empty( $args['htag_title'] )   ? $args['htag_title'] : 1;
$text         = ! empty( $args['text'] )         ? wp_kses_post( $args['text'] ) : '';
$image        = ! empty( $args['image']['url'] ) ? esc_url_raw( $args['image']['url'] ) : '';
$design       = in_array( $args['design'] ?? '', [ 'image_left', 'image_right', 'center', 'default' ], true )
                  ? $args['design']
                  : 'default';

// Si no hay FAQs, no renderizamos nada
if ( empty( $faqs ) ) {
    return;
}

// Generar clases según diseño
$wrapper_classes = [ 'row', 'gy-4', 'align-items-strech', 'justify-content-between' ];
$content_classes = [ 'col-12', 'col-lg-6' ];
$image_classes   = [ 'col-12', 'col-lg-5', 'cover', 'bg-cover' ];

switch ( $design ) {
    case 'image_left':
        $wrapper_classes[] = 'flex-lg-row-reverse';
        $image_classes[]   = 'bg-start';
        break;
    case 'image_right':
        $image_classes[]   = 'bg-end';
        break;
    case 'center':
        $content_classes = [ 'col-12', 'col-lg-6', 'mx-auto', 'text-center' ];
        break;
    default:
        // diseño por defecto: justificado
        $content_classes = [ 'col-12', 'mx-auto', 'text-center' ];
        break;
}

// ID único para el acordeón (permite varios bloques en la misma página)
$accordion_id = 'faqAccordion_' . wp_unique_id();
?>
<section class="faqs-section my-5">
   <div class="container">
      <div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">

         <!-- Columna de contenido (FAQs) -->
         <div class="<?php echo esc_attr( implode( ' ', $content_classes ) ); ?>">
            <?php if ( $tagline ) : ?>
               <?php echo Template_Helpers::tag_title( 
                  $htag_tagline,
                  esc_html( $tagline ),
                  'section-tagline tagline mb-0'
               ); ?>
            <?php endif; ?>

            <?php if ( $title ) : ?>
               <?php echo Template_Helpers::tag_title( 
                  $htag_title,
                  esc_html( $title ),
                  'section-title heading-2 mb-3'
               ); ?>
            <?php endif; ?>
            
            <?php if ( $text ) : ?>
               <div class="section-text">
                  <?php echo wp_kses_post( wpautop( $text ) ); ?>
               </div>
            <?php endif; ?>
         
            <div class="accordion mt-4" id="<?php echo esc_attr( $accordion_id ); ?>">
               <?php foreach ( $faqs as $index => $faq ): 

                  // Contenido de cada FAQ
                  $question = ! empty( $faq['question'] ) ? $faq['question'] : '';
                  $answer   = ! empty( $faq['answer'] ) ? $faq['answer'] : '';

                  // Si falta pregunta o respuesta, saltar
                  if ( ! $question || ! $answer ) {
                        continue;
                  }

                  // IDs únicos por elemento
                  $heading_id  = "{$accordion_id}_heading_{$index}";
                  $collapse_id = "{$accordion_id}_collapse_{$index}";
                  ?>
                  <div class="accordion-item">
                     <<?php echo esc_html( $htag_faq ); ?> class="accordion-header" id="<?php echo esc_attr( $heading_id ); ?>">
                        <button
                           class="accordion-button collapsed pb-2"
                           type="button"
                           data-bs-toggle="collapse"
                           data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>"
                           aria-expanded="false"
                           aria-controls="<?php echo esc_attr( $collapse_id ); ?>"
                        >
                           <?php echo esc_html( $question ); ?>
                        </button>
                     </<?php echo esc_html( $htag_faq ); ?>>

                     <div
                        id="<?php echo esc_attr( $collapse_id ); ?>"
                        class="accordion-collapse collapse"
                        aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
                        data-bs-parent="#<?php echo esc_attr( $accordion_id ); ?>"
                     >
                        <div class="accordion-body fs15 pt-0">
                           <?php echo $answer; ?>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>

         <?php if ( $image && in_array( $design, [ 'image_left', 'image_right' ], true ) ) : ?>
               <div class="<?php echo esc_attr( implode( ' ', $image_classes ) ); ?>"
               style="background-image: url('<?php echo esc_url( $image ); ?>');"></div>
         <?php endif; ?>

      </div>
   </div>
</section>
