<?php 
/**
 * Contenido relacionado
 */

$view_all_button = $bloque['view_all_button'] ?? null;
$card_style = $bloque['card_style'] ?? 'image';
?>
<section class="related-content py-5">
   <div class="container">
      <div class="row">
         <?php if (!empty($bloque['cards'])) :
            foreach ($bloque['cards'] as $card) :
               $title = $card['title'] ?? '';
               $htag = $card['htag_title'] ?? 2;
               $description = $card['description'] ?? '';
               $link = $card['link'] ?? '#';

               // Configuración para el estilo de la card
               $image_url = '';
               if ($card_style == 'image' && !empty($card['background_image']) && $card['background_image'] !== false) {
                  // Verifica si hay imagen de fondo para el estilo con imagen
                  $image_id = $card['background_image']['id'];
                  $image_url = wp_get_attachment_image_url($image_id, 'card_background') ?: get_template_directory_uri() . '/assets/img/default-card-background.jpg';
               }

               // Renderizar cada card usando el template part
               get_template_part('template-parts/components/card-related', null, [
                  'title'       => $title,
                  'htag'        => $htag,
                  'description' => $description,
                  'image_url'   => $image_url,
                  'link'        => $link,
                  'cols'        => count($bloque['cards']) === 4 ? 'col-lg-3 col-md-6' : 'col-lg-4 col-md-6',
                  'class_card'  => 'related-card',
                  'card_style'  => $card_style
               ]);
            endforeach;
         endif; ?>
      </div>
                  
      <?php if ($view_all_button) : ?>
         <div class="text-center">
               <a href="<?php echo esc_url($view_all_button['url']); ?>" class="btn btn-lg btn-secondary" target="<?php echo esc_attr($view_all_button['target']); ?>">
                  <?php echo esc_html($view_all_button['title']); ?>
               </a>
         </div>
      <?php endif; ?>
   </div>
</section>
