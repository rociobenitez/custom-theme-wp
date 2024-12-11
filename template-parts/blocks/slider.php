<?php
/**
 * Slider
 *
 */

// Obtener los valores de los campos de ACF
$fields = get_fields();

// Verificar si el campo 'slide' existe y es un array
$slider = isset($fields['slides']) && is_array($fields['slides']) ? $fields['slides'] : [];

if (empty($slider)) {
   return;
}
?>
<section id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
   <div id="hero" class="carousel-inner h-auto">
      <?php foreach ($slider as $index => $slide) : 
         // Validar y obtener la URL de la imagen de fondo
         $img_bg = !empty($slide['img_bg']['url']) ? esc_url($slide['img_bg']['url']) : get_template_directory_uri() . '/img/img-default.jpg';
         $background = "linear-gradient(0deg, rgba(17, 24, 39, 0.3) 0%, rgba(17, 24, 39, 0.40) 100%), url('" . $img_bg . "')";
         
         // Sanitizar otros campos
         $title = !empty($slide['title']) ? sanitize_text_field($slide['title']) : '';
         $htag_title = !empty($slide['htag_title']) ? sanitize_text_field($slide['htag_title']) : 0;
         $text = !empty($slide['text']) ? wp_kses_post($slide['text']) : '';
         $link_url = !empty($slide['link']['url']) ? esc_url($slide['link']['url']) : '';
         $link_title = !empty($slide['link']['title']) ? esc_html($slide['link']['title']) : '';
         ?>
         <div class="carousel-item carousel-background cover <?php echo $index === 0 ? 'active' : ''; ?>" 
            style="background: <?php echo $background; ?>">
            <div class="container">
               <div id="slider" class="carousel-content col-11 col-md-10 col-lg-8 d-flex flex-column justify-content-center align-items-center mx-auto">
                  <?php if ($title) :
                     echo tagTitle($htag_title, $title, 'heading-1 slide-title c-white text-center', '');
                  endif; ?>

                  <?php if ($text) : ?>
                     <div class="slide-description">
                        <?php echo $text; ?>
                     </div>
                  <?php endif; ?>

                  <?php if ($link_url && $link_title) : ?>
                     <a href="<?php echo $link_url; ?>" class="btn btn-transparent">
                        <?php echo $link_title; ?>
                     </a>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      <?php endforeach; ?>

      <?php if (count($slider) > 1) : ?>
         <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
         </button>
      <?php endif; ?>
   </div>
</section>
