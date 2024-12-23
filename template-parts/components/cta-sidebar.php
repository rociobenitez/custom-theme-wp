<?php
/**
 * Bloque de CTA lateral con formulario
 */

// Obtener campos de ACF o definir valores predeterminados
$options             = get_fields('option');
$class_title_desktop = 'cta-title heading-4 mb-3 pb-2 border-bottom border-opacity-25';
$class_title_mobile  = 'cta-title heading-4 mb-3';
$class_btn           = 'btn btn-md btn-primary w-100 mt-3 d-flex justify-content-center align-items-center';
$cta_title           = $options['sidebar_form_title_cta'] ?? 'Contacta con nosotros';
$cta_title_mobile    = $options['sidebar_cta_title'] ?? '¿Quieres que te llamemos?';
$cta_description     = $options['sidebar_cta_text'] ?? '';
$cta_desc_form       = $options['sidebar_form_text_cta'] ?? '';
$phone               = $options['phone'] ?? '';
$id_form             = $arg['id_form'] ?? 2;
$shortcode_gform     = $id_form ? '[gravityform id="' . esc_attr($id_form) . '" title="false" description="false" ajax="true"]' : '';

if (!empty($phone) || !empty($id_form)): ?>

<div id="cta" class="cta-sticky rounded overflow-hidden">

   <div class="contact p-4 c-bg-light">

      <?php // Botón de contacto telefónico (móvil) ?>
      <div class="d-md-none text-center pt-2">
         <p class="<?php echo esc_attr($class_title_mobile); ?>">
            <?php echo esc_html($cta_title_mobile); ?>
         </p>
         <?php if (!empty($cta_description)): ?>
            <div class="cta-description fs15">
               <?php echo $cta_description; ?>
            </div>
         <?php endif; ?>
         <a href="tel:<?php echo esc_attr($phone); ?>" class="<?php echo esc_attr($class_btn); ?>">
            <?php // SVG del ícono ?>
            <svg height="20px" viewBox="0 -960 960 960" width="24px" fill="#fff">
               <path d="M798-120q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z"/>
            </svg> Contactar</a> 
      </div>

      <?php
      // Formulario de contacto (escritorio)
      if (!empty($id_form)): ?>
         <div class="d-none d-md-block pt-2">
            <p class="<?php echo esc_attr($class_title_desktop); ?>">
               <?php echo esc_html($cta_title); ?>
            </p>
            <?php if (!empty($cta_desc_form)): ?>
               <div class="cta-description fs15">
                  <?php echo $cta_desc_form; ?>
               </div>
            <?php endif; ?>
            <div class="contact-form">
               <?php echo do_shortcode($shortcode_gform); ?>
            </div>
         </div>
      <?php endif; ?>

   </div>

</div>

<?php endif; ?>
