<?php
/**
 * Template Part: Footer de 3 Columnas
 *
 * @package CustomTheme
 */

?>
<div class="row py-4 gap-5">
<!-- Logo -->
   <div class="col-md-3 mx-auto col-brand">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
         <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" class="img-brand w-100" 
            alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
      </a>          
   </div>

   <!-- Footer Menus -->
   <div class="row">
      <?php foreach ($active_cols as $col) : ?>
         <div class="<?php echo esc_attr($column_class); ?> footer-menu text-center text-md-start mb-4 mb-lg-0">
            <p class="footer-menu-title mb-2"><?php echo esc_html($col['title']); ?></p>
            <?php wp_nav_menu([
               'theme_location' => $col['menu'],
               'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column gap-1',
            ]); ?>
         </div>
      <?php endforeach; ?>

      <!-- Contact Information -->
      <?php if (!empty(array_filter($contact_info, fn($info) => !empty($info['text']) && !empty($info['link'])))) : ?>
         <div class="<?php echo esc_attr($column_class); ?> footer-menu footer-contact text-center text-lg-start mb-4 mb-lg-0">
            <p class="footer-menu-title footer-contact-title mb-2"><?php echo esc_html($footer_contact_column_title); ?></p>
            <ul class="footer-contact-list list-unstyled d-flex flex-column gap-1">
               <?php foreach ($contact_info as $contact) : ?>
                  <?php if (!empty($contact['text']) && !empty($contact['link'])) : ?>
                        <li class="footer-contact-item">
                           <a href="<?php echo esc_url($contact['link']); ?>" target="_self">
                              <?php echo esc_html($contact['text']); ?>
                           </a>
                        </li>
                  <?php endif; ?>
               <?php endforeach; ?>
               <?php if ($opening_hours) : ?>
                  <li class="footer-contact-item">
                        <img src="<?php echo esc_attr($icon_schedule_src); ?>" class="footer-contact-icon me-1" alt="">
                        <?php echo esc_html($opening_hours); ?>
                  </li>
               <?php endif; ?>
            </ul>
            <div class="d-flex footer-social justify-content-center justify-content-md-start"><?php echo sociales(); ?></div>  
         </div>
      <?php endif; ?>
   </div>
</div>