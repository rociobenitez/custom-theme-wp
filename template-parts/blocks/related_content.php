<?php 
/**
 * Sección: Contenido Relacionado (con campo ACF de tipo "relación")
 */

$view_all_button = $bloque['view_all_button'] ?? null;
$card_style = $bloque['card_style'] ?? 'image'; // opciones válidas: 'image' o 'text'
$related_items = $block['related'] ?? [];
$related_count = is_array($related_items) ? count($related_items) : 0;

if ($related_count > 0) :
?>
<section class="related-content py-5">
   <div class="container">
      <div class="row">
         <?php if (!empty($related_items)) :
            foreach ($related_items as $post_object) :
               // Preparar el post para funciones como get_the_title(), etc.
               setup_postdata($post_object);

               $title       = get_the_title($post_object);
               $htag        = 3;
               $description = wp_trim_words(get_the_excerpt($post_object), 10, '…');
               $link        = get_permalink($post_object);

               // Imagen destacada o fallback
               $image_url = '';
               if ($card_style === 'image') {
                  $image_url = has_post_thumbnail($post_object)
                     ? get_the_post_thumbnail_url($post_object, 'card_background')
                     : get_template_directory_uri() . '/assets/img/default-card-background.jpg';
               }

               // Renderizar card
               get_template_part('template-parts/components/card-related', null, [
                  'title'       => $title,
                  'htag'        => $htag,
                  'description' => $description,
                  'image_url'   => $image_url,
                  'link'        => $link,
                  'cols'        => $related_count === 4 ? 'col-lg-3 col-md-6' : 'col-lg-4 col-md-6',
                  'class_card'  => 'related-card',
                  'card_style'  => $card_style
               ]);
            endforeach;
            wp_reset_postdata();
         endif; ?>
      </div>
                  
      <?php if ($view_all_button) : ?>
         <div class="text-center mt-4">
            <a href="<?php echo esc_url($view_all_button['url']); ?>" class="btn btn-md btn-secondary" target="<?php echo esc_attr($view_all_button['target']); ?>">
               <?php echo esc_html($view_all_button['title']); ?>
            </a>
         </div>
      <?php endif; ?>
   </div>
</section>
<?php endif; ?>
