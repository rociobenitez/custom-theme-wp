<?php 
/**
 * Sección: Texto Centrado sin Imagen
 */

$tagline       = $bloque['tagline'] ?? '';
$htag_tagline  = $bloque['htag_tagline'] ?? 3;
$title         = $bloque['title'] ?? '';
$htag_title    = $bloque['htag_title'] ?? 2;
$text          = $bloque['text'] ?? '';
$bg_color      = !empty($bloque['background_color']) ? $bloque['background_color'] : '#FFFFFF';
$cta_button    = $bloque['button'] ?? null;
$col_main_text = !empty($bloque['width_content']) ? $bloque['width_content'] : 'col-lg-8';
$text_align    = !empty($bloque['text_align']) ? $bloque['text_align'] : 'text-center';
$block_align   = $bloque['text_align'] == 'text-center'
   ? 'justify-content-center align-items-center'
   : ($bloque['text_align'] == 'text-left'
      ? 'justify-content-start align-items-start'
      : ($bloque['text_align'] == 'text-right'
         ? 'justify-content-end align-items-end'
         : ''));
?>
<section class="simple-text-block py-5 my-5 bg-custom" style="background-color: <?php echo esc_attr($bg_color); ?>">
   <div class="container">
      <?php
      get_component('template-parts/components/section-heading', [
         'show_heading'     => true,
         'tagline'          => $tagline,
         'htag_tagline'     => $htag_tagline,
         'title'            => $title,
         'htag_title'       => $htag_title,
         'text'             => $text,
         'block_align'      => $block_align . 'gap-5 gap-lg-0',
         'container_class'  => 'col-11' . $col_main_text,
         'text_align_class' => $text_align
      ]);
      ?> 
   </div>
</section>