<?php
/**
 * Template Part: Footer de 3 Columnas
 *
 * @package custom_theme
 */
?>
<div class="row py-4 gap-5">
   <!-- Logo -->
   <div class="col-md-3 mx-auto col-brand">
      <a href="<?= esc_url( home_url( '/' ) ); ?>" rel="home">
         <img src="<?= get_template_directory_uri(); ?>/img/logo.png" class="img-brand w-100" 
            alt="<?= esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
      </a>          
   </div>

   <!-- Footer Menus -->
   <div class="row">
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