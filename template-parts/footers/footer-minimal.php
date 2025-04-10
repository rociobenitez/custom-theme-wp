<?php
/**
 * Template Part: Footer Minimalista
 *
 * @package custom_theme
 */
?>
<div class="row justify-content-between">
   <!-- Logo -->
   <div class="col-lg-2 footer-brand d-flex flex-column gap-2 align-items-center align-items-lg-start mb-5 mb-xl-0">
      <a href="<?= esc_url( home_url( '/' ) ); ?>" rel="home">
        <img src="<?= esc_url( $logo_url ); ?>" alt="<?= esc_attr( get_bloginfo('name') ); ?>" class="footer-logo" width="<?= esc_attr( $width_logo ); ?>" height="<?= esc_attr( $height_logo ); ?>">
      </a>
      <?php if ( !empty( $footer_text_below_logo ) ) : ?>
        <div class="footer-text-below-logo">
            <?= $footer_text_below_logo; ?>
        </div>
      <?php endif; ?>        
   </div>

   <!-- Footer Menu -->
   <div class="col-lg-9 justify-content-end ms-lg-auto text-end">
      <div class="footer-menu text-center text-lg-end mb-4 mb-lg-0">
        <?php if ( !empty( $footer_columns['footer1']['title'] ) ) : ?>
            <p class="footer-menu-title mb-2"><?= esc_html($footer_columns['footer1']['title']); ?></p>
        <?php endif; ?>
        <?php wp_nav_menu([
            'theme_location' => $footer_columns['footer1']['menu'],
            'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column flex-lg-row justify-content-lg-end gap-1 gap-lg-4',
        ]); ?>
      </div>

      <div class="d-flex footer-social justify-content-center justify-content-lg-end"><?= social_media(); ?></div>  
   </div>
</div>