<?php
// Obtener los valores de ACF
$tagline      = $block['tagline'] ?? '';
$htag_tagline = $block['htag_tagline'] ?? 3;
$title        = $block['title'] ?? '';
$htag_title   = $block['htag_title'] ?? 3;
$text         = $block['text'] ?? '';
$btn          = $block['button'] ?? '';
$images       = $block['gallery_images'] ?? [];
$image_count  = count($images);
?>

<section class="gallery-block container my-5">
   <?php if ($image_count === 3) : ?>
      <div class="row align-items-stretch">
         <!-- Bloque de texto -->
         <div class="col-md-6 col-lg-4 c-bg-light p-4 rounded d-flex flex-column <?php echo $image_count < 4 ? 'justify-content-center' : ''; ?>">
            <?php
            get_component('template-parts/components/section-heading', [
               'show_heading' => true,
               'tagline'      => $tagline,
               'htag_tagline' => $htag_tagline,
               'title'        => $title,
               'htag_title'   => $htag_title,
               'class_title'  => 'heading-4',
               'text'         => $text,
               'cta_button'   => $btn,
               'class_button' => 'btn btn-secondary mt-3',
               'style'        => 'minimal'
            ]);
            ?> 
         </div>

         <!-- Galería de imágenes -->
         <div class="col-lg-6 col-lg-8">
            <div class="row g-3 h-100">
               <?php foreach ($images as $index => $image) : ?>
                  <div class="<?php echo $index < 3 ? 'col-sm-6 col-md-4' : 'col-md-4'; ?>">
                     <a href="<?php echo esc_url($image['url']); ?>" 
                        data-bs-toggle="modal" 
                        data-bs-target="#galleryModal" 
                        data-bs-image="<?php echo esc_url($image['url']); ?>" 
                        class="gallery-item">
                        <div class="gallery-image-wrapper overflow-hidden h-100 rounded">
                           <img src="<?php echo esc_url($image['sizes']['block_small']); ?>" 
                              alt="<?php echo esc_attr($image['alt']); ?>" 
                              class="img-fluid shadow-sm fit">
                        </div>
                     </a>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
   <?php else: ?>
      <?php
      get_component('template-parts/components/section-heading', [
         'show_heading'     => true,
         'tagline'          => $tagline,
         'htag_tagline'     => $htag_tagline,
         'title'            => $title,
         'htag_title'       => $htag_title,
         'text'             => $text,
         'cta_button'       => '',
      ]);
      ?> 

      <!-- Galería de imágenes -->
      <div class="row g-3 h-100 justify-content-center align-items-center">
         <?php foreach ($images as $index => $image) : ?>
            <div class="col-sm-6 col-md-4">
               <a href="<?php echo esc_url($image['url']); ?>" 
                  data-bs-toggle="modal" 
                  data-bs-target="#galleryModal" 
                  data-bs-image="<?php echo esc_url($image['url']); ?>" 
                  class="gallery-item">
                  <div class="gallery-image-wrapper overflow-hidden h-100 rounded">
                     <img src="<?php echo esc_url($image['sizes']['block_small']); ?>" 
                        alt="<?php echo esc_attr($image['alt']); ?>" 
                        class="img-fluid shadow-sm fit">
                  </div>
               </a>
            </div>
         <?php endforeach; ?>
      </div>

      <?php if ($btn) :
         $btn_text   = $btn['title'] ?? '';
         $btn_url    = $btn['url'] ?? '';
         $btn_target = $btn['target'] ?? '_self';
         ?>
         <div class="container-btn d-flex justify-content-center align-items-center mt-4">
            <a href="<?php echo esc_url($btn_url); ?>" 
               class="btn btn-md btn-primary" 
               target="<?php echo esc_attr($btn_target); ?>">
               <?php echo esc_html($btn_text); ?>
            </a>
         </div>
      <?php endif; ?>

   <?php endif; ?>

   <!-- Modal para ampliar imágenes -->
   <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content">
               <div class="modal-header border-0">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body p-0">
                  <img src="" id="galleryModalImage" class="img-fluid w-100 rounded">
               </div>
         </div>
      </div>
   </div>
</section>


<style>
.gallery-block .gallery-image-wrapper img {
   transition: transform 0.3s ease;
}

/* Efecto hover para las imágenes */
.gallery-block .gallery-item:hover img {
   transform: scale(1.05); /* Pequeño zoom */
}
</style>