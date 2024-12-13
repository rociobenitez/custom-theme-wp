<?php
/**
 * Secciones de Enlaces Destacados
 */

// Obtener los bloques desde ACF
$blocks       = $block['section_blocks'];
$bg_color     = $block['bg_color'] == 'bg-white' ? 'c-bg-white my-5' : 'c-bg-light py-5';
$show_heading = $block['show_heading'];

if ($blocks && is_array($blocks)) :
    $block_count = count($blocks);
?>
   <section class="section-blocks <?php echo esc_attr($bg_color);  ?>">
      <div class="container">

         <?php if ($show_heading) : ?>
            <div class="row">
               <div class="col-lg-8 mx-auto">
                  <div class="text-center mb-4">
                     <?php if (!empty($tagline)) :
                        echo tagTitle($htag_tagline, $tagline, 'tagline', '');
                     endif; 
                     if (!empty($title)) :
                        echo tagTitle($htag_title, $title, 'heading-2', '');
                     endif;
                     if (!empty($text)) : ?>
                        <div class="section-text">
                           <?php echo $text; ?>
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         <?php endif; ?>

         <div class="row gx-3 gy-4">
            <?php foreach ($blocks as $index => $block) :
               // Extraer datos del bloque
               $title    = $block['title'] ?? '';
               $subtitle = $block['description'] ?? '';
               $image    = $block['bg_img'] ?? '';
               $link     = $block['link_page']['url'] ?? '#';

               // Obtener las imágenes en diferentes tamaños
               $image_small  = wp_get_attachment_image_url($image['ID'], 'block_small');
               $image_medium = wp_get_attachment_image_url($image['ID'], 'block_medium');
               $image_large  = wp_get_attachment_image_url($image['ID'], 'block_large');

               // Calcular clases de tamaño según el número de bloques
               $block_class = 'col-lg-6'; // Por defecto, ocupan 50%
               if ($block_count === 3) {
                  if ($index === 0) {
                     $block_class = 'col-lg-12'; // Primero ocupa 100%
                  } elseif ($index === 1) {
                     $block_class = 'col-lg-7'; // Segundo ocupa más
                  } elseif ($index === 2) {
                     $block_class = 'col-lg-5'; // Tercero ocupa menos
                  }
               } elseif ($block_count === 4) {
                  if ($index === 0 || $index === 3) {
                     $block_class = 'col-lg-5'; // Primero y cuarto
                  } elseif ($index === 1 || $index === 2) {
                     $block_class = 'col-lg-7'; // Segundo y tercero
                  }
               } elseif ($block_count === 5) {
                  if ($index < 2) {
                     $block_class = 'col-lg-6'; // Primera fila
                  } else {
                     $block_class = 'col-lg-4'; // Segunda fila
                  }
               }
               ?>
               <div class="<?php echo esc_attr($block_class); ?> block-item">
                  <a href="<?php echo esc_url($link); ?>" class="block-link d-block text-center position-relative">
                     <div class="block-image cover rounded"
                        data-bg-small="<?php echo esc_url($image_small); ?>"
                        data-bg-medium="<?php echo esc_url($image_medium); ?>"
                        data-bg-large="<?php echo esc_url($image_large); ?>"
                        style="background-image: url('<?php echo esc_url($image_medium); ?>');"></div>
                     <div class="block-overlay d-flex flex-column rounded">
                        <?php if(!empty($title)) : ?>
                           <h3 class="block-title"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>
                        <?php if(!empty($subtitle)) : ?>
                           <div class="block-subtitle"><?php echo $subtitle; ?></div>
                        <?php endif; ?>
                     </div>
                  </a>
               </div>
            <?php endforeach; ?>
         </div>
      </div><!-- .container -->
   </section>
<?php endif; ?>

<script>
function updateBackgroundImages() {
   const blocks = document.querySelectorAll('.block-image');
   blocks.forEach(block => {
      const small = block.getAttribute('data-bg-small');
      const medium = block.getAttribute('data-bg-medium');
      const large = block.getAttribute('data-bg-large');

      let bgImage = medium; // Default (tablet size)
      if (window.innerWidth <= 576) {
         bgImage = small; // Mobile size
      } else if (window.innerWidth > 1200) {
         bgImage = large; // Desktop size
      }

      block.style.backgroundImage = `url('${bgImage}')`;
   });
}

// Llamar a la función al cargar y redimensionar la ventana
window.addEventListener('load', updateBackgroundImages);
window.addEventListener('resize', updateBackgroundImages);
</script>
