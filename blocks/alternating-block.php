<?php 
/**
 * Bloque de Texto Alternado con Imagen
 */

$tagline = $bloque['tagline'] ?? '';
$htag_tagline = $bloque['htag_tagline'] ?? 3;
$title = $bloque['title'] ?? '';
$htag_title = $bloque['htag_title'] ?? 2;
$description = $bloque['description'] ?? '';
$image = $bloque['image'] ?? '';
$image_position = $bloque['image_position'] ?? 'right';
$background_color = $bloque['background_color'] ?? '#FFFFFF';
$cta_button = $bloque['button'] ?? null;
?>
<section class="alternating-block py-5 bg-custom" style="background-color: <?php echo esc_attr($background_color); ?>">
   <div class="container">
      <div class="row align-items-center">

         <!-- Texto -->
         <div class="col-lg-6 <?php echo $image_position === 'left' ? 'order-lg-2' : ''; ?>">
            <?php if (!empty($tagline)):
               echo tagTitle($htag_tagline, $tagline, 'tagline', '');
            endif; 
            if (!empty($title)):
               echo tagTitle($htag_title, $title, 'heading-2', '');
            endif; ?>
            <div class="block-description"><?php echo $description; ?></div>

            <?php if ($cta_button): ?>
               <a href="<?php echo esc_url($cta_button['url']); ?>" 
                  class="btn btn-primary mt-3" 
                  target="<?php echo esc_attr($cta_button['target']); ?>">
                  <?php echo esc_html($cta_button['title']); ?>
               </a>
            <?php endif; ?>
         </div>
         
         <!-- Imagen -->
         <?php if ($image): ?>
            <div class="col-lg-6 text-center <?php echo $image_position === 'left' ? 'order-lg-1' : ''; ?>">
               <img src="<?php echo esc_url($image['url']); ?>"
                    alt="<?php echo esc_attr($image['alt']); ?>"
                    class="img-fluid alternating-image">
            </div>
         <?php endif; ?>

      </div>
   </div>
</section>