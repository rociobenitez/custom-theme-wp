<?php
/**
 * Bloque CTA Banner
 */

$title = $bloque['title'] ?? '';
$htag = $bloque['htag'] ?? 3;
$description = $bloque['description'] ?? '';
$cta_boton = $bloque['cta_button'] ?? null;
$image_id = $bloque['background_image']['id'] ?? null;

// Cargar imágenes de diferentes tamaños para diferentes dispositivos
$bg_image_large = $image_id ? wp_get_attachment_image_url($image_id, 'large') : get_template_directory_uri() . '/assets/img/default-banner-background.jpg';
$bg_image_medium = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_template_directory_uri() . '/assets/img/default-banner-background.jpg';
$bg_image_small = $image_id ? wp_get_attachment_image_url($image_id, 'small') : get_template_directory_uri() . '/assets/img/default-banner-background.jpg';

$linear_gradient = "linear-gradient(0deg, rgba(10, 25, 47, 0.5), rgba(10, 25, 47, 0.6)), linear-gradient(260deg, rgba(16, 42, 67, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%)";

if($title):
?>
<style>
   .cta-banner { background: <?php echo $linear_gradient; ?>, url('<?php echo esc_url($bg_image_large); ?>'); }

   @media (max-width: 1023px) {
      .cta-banner { background: <?php echo $linear_gradient; ?>, url('<?php echo esc_url($bg_image_medium); ?>'); }
   }

   @media (max-width: 767px) {
      .cta-banner { background: <?php echo $linear_gradient; ?>, url('<?php echo esc_url($bg_image_small); ?>'); }
   }
</style>

<section class="cta-banner d-flex flex-column align-items-center justify-content-center text-center cover c-white p-5">
   <div class="container py-4">
      <div class="col-md-9 mx-md-auto">
         <?php if ($title):
            echo tagTitle($htag, $title, 'heading-3 c-white cta-title', '');
         endif;
         if ($description): ?>
            <div class="cta-description"><?php echo $description; ?></div>
         <?php endif; ?>

         <?php if ($cta_boton): ?>
            <a href="<?php echo esc_url($cta_boton['url']); ?>" class="btn btn-lg btn-transparent mt-3" target="<?php echo esc_attr($cta_boton['target']); ?>">
                  <?php echo esc_html($cta_boton['title']); ?>
            </a>
         <?php endif; ?>
      </div>
   </div>
</section>
<?php endif; ?>