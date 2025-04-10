<?php
/**
 * Template Part: Footer de 4 Columnas
 *
 * @package custom_theme
 */
?>
<div class="footer-top pt-5 pb-4">
   <div class="container py-4">
      <div class="row justify-content-center">
         <!-- Logo -->
         <div class="col-lg-2 footer-brand d-flex flex-column gap-2 align-items-center align-items-lg-start mb-5 mb-xl-0">
            <a href="<?= esc_url(home_url('/')); ?>" class="footer-brand-link" rel="home">
               <img src="<?= esc_url($logo_url); ?>" alt="<?= esc_attr(get_bloginfo('name')); ?>" class="footer-logo" width="<?= esc_attr($width_logo); ?>" height="<?= esc_attr($height_logo); ?>">
            </a>
            <?php if (!empty($footer_text_below_logo)) : ?>
               <div><?= $footer_text_below_logo; ?></div>
            <?php endif; ?>
         </div>

         <!-- Footer Menus -->
         <div class="row col-lg-10 justify-content-end">
            <?php foreach ($active_cols as $col) : ?>
               <div class="<?= esc_attr($column_class); ?> footer-menu text-center text-md-start mb-4 mb-lg-0">
                  <?php if ( !empty( $col['title'] ) ) : ?>
                     <p class="footer-menu-title mb-2"><?= esc_html($col['title']); ?></p>
                  <?php endif; ?>
                  <?php wp_nav_menu([
                     'theme_location' => $col['menu'],
                     'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column gap-1',
                  ]); ?>
               </div>
            <?php endforeach; ?>

            <!-- Información de contacto -->
            <?php if ( !empty( array_filter( $contact_info, fn( $info ) => !empty( $info['text'] ) && !empty( $info['link'] ) ) ) ) : ?>
               <div class="<?= esc_attr( $column_class ); ?> footer-contact text-center text-lg-start mb-4 mb-lg-0">
                  <?php if ( !empty( $footer_contact_column_title ) ) : ?>
                     <p class="footer-contact-title mb-2"><?= esc_html( $footer_contact_column_title ); ?></p>
                  <?php endif; ?>
                  <ul class="footer-contact-list list-unstyled d-flex flex-column gap-1">
                     <?php foreach ( $contact_info as $contact ) : ?>
                        <?php if ( !empty( $contact['text'] ) && !empty( $contact['link'] ) ) : ?>
                           <li class="footer-contact-item">
                              <a href="<?= esc_url( $contact['link'] ); ?>" target="_self">
                                 <img src="<?= esc_attr( $contact['icon'] ); ?>" class="footer-contact-icon me-1" alt="<?= esc_attr( $contact['alt'] ); ?>" >
                                 <?= esc_html( $contact['text'] ); ?>
                              </a>
                           </li>
                        <?php endif; ?>
                     <?php endforeach; ?>
                     <?php if ( $opening_hours ) : ?>
                        <li class="footer-contact-item">
                           <img src="<?= esc_attr( $icon_schedule_src ); ?>" class="footer-contact-icon me-1" alt=<?php _e("Icono horario de apertura", THEME_TEXTDOMAIN ); ?>>
                           <?= $opening_hours; ?>
                        </li>
                     <?php endif; ?>
                  </ul>
                  <div class="d-flex footer-social"><?= social_media(); ?></div>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>
