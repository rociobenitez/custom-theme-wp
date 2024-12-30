<?php 
/**
 * Sección: Texto Centrado sin Imagen
 */

$tagline       = $block['tagline'] ?? '';
$htag_tagline  = $block['htag_tagline'] ?? 3;
$title         = $block['title'] ?? '';
$htag_title    = $block['htag_title'] ?? 2;
$text          = $block['text'] ?? '';
$bg_color      = !empty($block['background_color']) ? $block['background_color'] : '#FFFFFF';
$cta_button    = $block['button'] ?? null;
$col_main_text = !empty($block['width_content']) ? $block['width_content'] : 'col-lg-8';
$text_align    = !empty($block['text_align']) ? $block['text_align'] : 'text-center';
$block_align   = $block['text_align'] == 'text-center'
   ? 'justify-content-center align-items-center'
   : ($block['text_align'] == 'text-left'
      ? 'justify-content-start align-items-start'
      : ($block['text_align'] == 'text-right'
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
         'container_class'  => 'col-11 ' . $col_main_text,
         'text_align_class' => $text_align
      ]);
      ?> 
   </div>
</section>