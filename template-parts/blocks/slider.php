<?php
/**
 * Slider
 *
 * Renderiza un slider dinámico basado en los valores de ACF.
 */

// Obtener los valores de los campos de ACF
$fields = get_fields();
$slider = isset($fields['slides']) && is_array($fields['slides']) ? $fields['slides'] : [];

// Imagen por defecto
$default_image = get_template_directory_uri() . 'assets/img/default-background.jpg';

// Estilos comunes
$title_class     = "heading-1 slide-title c-white text-center";
$btn_class       = "btn btn-transparent";
$linear_gradient = "linear-gradient(0deg, rgba(17, 24, 39, 0.3) 0%, rgba(17, 24, 39, 0.40) 100%)";

// Validar si hay slides configurados
if (empty($slider)) {
   return;
}

/**
 * Función para obtener valores sanitizados
 *
 * @param array $field Campo del slide.
 * @param string $key Clave del campo.
 * @param string $default Valor predeterminado.
 * @return mixed Valor sanitizado o predeterminado.
 */
function get_slide_field($field, $key, $default = '') {
   if (!isset($field[$key]) || empty($field[$key])) {
       return $default;
   }
   switch ($key) {
      case 'img_bg':
         return esc_url($field[$key]['url']);
      case 'link':
         return [
            'url'   => esc_url($field[$key]['url']),
            'title' => esc_html($field[$key]['title']),
         ];
      case 'htag_title':
         return sanitize_text_field($field[$key]);
      case 'text':
         return wp_kses_post($field[$key]);
      default:
         return sanitize_text_field($field[$key]);
   }
}
?>
<section id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
   <div class="carousel-inner h-auto">
      <?php foreach ($slider as $index => $slide) : 
         // Obtener valores sanitizados de los campos
         $img_bg      = get_slide_field($slide, 'img_bg', $default_image );
         $background  = $linear_gradient . ", url('" . $img_bg . "')";
         $title       = get_slide_field($slide, 'title');
         $htag_title  = get_slide_field($slide, 'htag_title', 0);
         $text        = get_slide_field($slide, 'text');
         $link        = get_slide_field($slide, 'link', []);
         $link_url    = $link['url'] ?? '';
         $link_title  = $link['title'] ?? '';
         ?>

         <!-- Slide -->
         <div class="carousel-item carousel-background cover <?php echo $index === 0 ? 'active' : ''; ?>" 
            style="background: <?php echo $background; ?>">
            <div class="container">
               <div id="slider" class="carousel-content col-11 col-md-10 col-lg-8 d-flex flex-column justify-content-center align-items-center mx-auto">
                  <?php if ($title) :
                     echo tagTitle($htag_title, $title, $title_class, '');
                  endif; ?>

                  <?php if ($text) : ?>
                     <div class="slide-description">
                        <?php echo $text; ?>
                     </div>
                  <?php endif; ?>

                  <?php if ($link_url && $link_title) : ?>
                     <a href="<?php echo $link_url; ?>" class="<?php echo $btn_class; ?> ">
                        <?php echo $link_title; ?>
                     </a>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
      
      <!-- Controles del slider -->
      <?php if (count($slider) > 1) : ?>
         <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
         </button>
      <?php endif; ?>
   </div>
</section>
