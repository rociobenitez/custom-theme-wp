<?php
/**
 * Component: Section Heading
 *
 * Variables esperadas en $args:
 *  - title         (string)        // obligatorio
 *  - tagline       (string)        // opcional
 *  - htag_title    (int|string)    // 0=h1,1=h2,2=h3,3=p
 *  - htag_tagline  (int|string)    // idem para tagline
 *  - text          (string)        // opcional (descripción)
 *  - button        (array)         // opcional ['title', 'url', 'target']
 *  - align_cls     (string)        // text-start, text-center, text-end
 *  - wrapper_cls   (string)        // clases extra para el wrapper
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;

// Valores por defecto
$title        = $args['title'] ?? '';
$tagline      = $args['tagline'] ?? '';
$htag_title   = $args['htag_title'] ?? 1;
$htag_tagline = $args['htag_tagline'] ?? 3;
$text         = $args['text'] ?? '';
$button       = $args['button'] ?? '';
$align_cls    = $args['align_cls'] ?? 'text-center mb-4';
$wrapper_cls  = $args['wrapper_cls'] ?? '';

if ( empty( $title ) ) {
    return;
}
?>
<div class="<?php echo esc_attr( $wrapper_cls ); ?>">
  <?php if ( $tagline ) : ?>
    <?php
      echo Template_Helpers::tag_title(
        $htag_tagline,
        esc_html( $tagline ),
        'section-tagline ' . esc_attr( $align_cls )
      );
    ?>
  <?php endif; ?>

  <?php
    echo Template_Helpers::tag_title(
      $htag_title,
      esc_html( $title ),
      'section-title ' . esc_attr( $align_cls )
    );
  ?>

   <?php if ( ! empty( $text ) ) : ?>
        <div class="section-text <?php echo esc_attr( $align_cls ); ?>">
            <?php echo wp_kses_post( wpautop( $text ) ); ?>
        </div>
    <?php endif; ?>

    <?php if ( is_array( $button ) && ! empty( $button['url'] ) && ! empty( $button['title'] ) ) : ?>
        <div class="section-button <?php echo esc_attr( $align_cls ); ?>">
            <a href="<?php echo esc_url( $button['url'] ); ?>"
               class="btn btn-primary"
               target="<?php echo esc_attr( $button['target'] ?? '_self' ); ?>">
                <?php echo esc_html( $button['title'] ); ?>
            </a>
        </div>
    <?php endif; ?>

</div>





<?php
/**
 * Componente: Section Heading
 *
 * Variables esperadas:
 * - $style_heading (string): Estilo del encabezado, puede ser 'default' o 'minimal' (por defecto: 'default').
 * - $block_align (string): Clase para alinear el bloque (por defecto: '').
 * - $container_class (string): Clase del contenedor (por defecto: 'col-lg-8 mx-auto').
 * - $text_align_class (string): Clase para la alineación del texto (por defecto: 'text-center mb-4').
 * - $show_heading (bool): Si se muestra el encabezado (por defecto: false).
 * - $tagline (string): Tagline opcional.
 * - $htag_tagline (int|string): Etiqueta HTML para el tagline .
 * - $class_tagline (string): Clase CSS para el tagline (por defecto: 'tagline').
 * - $title (string): Título principal.
 * - $htag_title (int|string): Etiqueta HTML para el título.
 * - $class_title (string): Clase CSS para el título (por defecto: 'heading-2').
 * - $text (string): Texto adicional debajo del título.
 * - $cta_button (array): Botón opcional con claves:
 *   - 'title' (string): Texto del botón.
 *   - 'url' (string): URL del botón.
 *   - 'target' (string): Target del enlace (por defecto: '_self').
 * - $class_button (string): Clase CSS para el botón (por defecto: 'btn btn-lg btn-primary mt-3').
 */

// Establecer valores predeterminados para las variables
$style_heading    = $style ?? 'default';
$block_align      = $block_align ?? '';
$container_class  = $container_class ?? 'col-lg-8 mx-auto';
$text_align_class = $text_align_class ?? 'text-center mb-4';
$show_heading     = $show_heading ?? false;
$tagline          = $tagline ?? '';
$htag_tagline     = $htag_tagline ?? 3;
$class_tagline    = $class_tagline ?? 'tagline';
$title            = $title ?? '';
$htag_title       = $htag_title ?? 1;
$class_title      = $class_title ?? 'heading-2';
$text             = $text ?? '';
$cta_button       = $cta_button ?? null;
$class_button     = $class_button ?? 'btn btn-lg btn-primary mt-3';

// Mostrar el encabezado solo si $show_heading es true
if ($show_heading) : 

   if ($style_heading === 'default') : ?>
   <div class="row <?php echo esc_attr($block_align); ?>">
      <div class="<?php echo esc_attr($container_class); ?>">
         <div class="<?php echo esc_attr($text_align_class); ?>">
   <?php endif; ?>

            <?php if (!empty($tagline)) :
               echo tagTitle($htag_tagline, $tagline, $class_tagline, '');
            endif; ?>

            <?php if (!empty($title)) :
               echo tagTitle($htag_title, $title, $class_title, '');
            endif; ?>

            <?php if (!empty($text)) : ?>
               <div class="section-text">
                  <?php echo $text; ?>
               </div>
            <?php endif; ?>
            <?php if ($cta_button) :
               $btn_text   = $cta_button['title'] ?? '';
               $btn_url    = $cta_button['url'] ?? '';
               $btn_target = $cta_button['target'] ?? '_self';
               ?>
               <a href="<?php echo esc_url($btn_url); ?>" 
                  class="<?php echo esc_attr($class_button); ?>" 
                  target="<?php echo esc_attr($btn_target); ?>">
                  <?php echo esc_html($btn_text); ?>
               </a>
            <?php endif; ?>

   <?php if ($style_heading === 'default') : ?>
         </div>
      </div>
   </div>
   <?php endif; ?>

<?php endif; ?>
