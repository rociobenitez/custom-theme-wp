<?php 
/**
 * Sección: Texto Centrado sin Imagen
 */

$tagline        = $block['tagline'] ?? '';
$htag_tagline   = $block['htag_tagline'] ?? 3;
$title          = $block['title'] ?? '';
$htag_title     = $block['htag_title'] ?? 2;
$description    = $block['description'] ?? '';
$bg_color       = !empty($block['background_color']) ? $block['background_color'] : '#FFFFFF';
$cta_button     = $block['button'] ?? null;
$col_main_text  = !empty($block['width_content']) ? $block['width_content'] : 'col-8';
$text_align     = !empty($block['text_align']) ? $block['text_align'] : 'text-center';
$block_align    = $block['text_align'] == 'text-center'
    ? 'justify-content-center align-items-center'
    : ($block['text_align'] == 'text-left'
        ? 'justify-content-start align-items-start'
        : ($block['text_align'] == 'text-right'
            ? 'justify-content-end align-items-end'
            : ''));
?>
<section class="alternating-block py-5 my-5 bg-custom" style="background-color: <?php echo esc_attr($bg_color); ?>">
   <div class="container">
      <div class="row <?php echo $block_align; ?> gap-5 gap-lg-0">
           <!-- Texto -->
           <div class="<?php echo $col_main_text; ?> <?php echo esc_attr($text_align); ?>">
            <?php if (!empty($tagline)):
               echo tagTitle($htag_tagline, $tagline, 'tagline', '');
            endif; 
            if (!empty($title)):
               echo tagTitle($htag_title, $title, 'heading-2', '');
            endif; ?>
            <div class="block-description"><?php echo $description; ?></div>

            <?php if ($cta_button): ?>
               <a href="<?php echo esc_url($cta_button['url']); ?>" 
                  class="btn btn-lg btn-primary mt-3" 
                  target="<?php echo esc_attr($cta_button['target']); ?>">
                  <?php echo esc_html($cta_button['title']); ?>
               </a>
            <?php endif; ?>
         </div>
      </div>
   </div>
</section>