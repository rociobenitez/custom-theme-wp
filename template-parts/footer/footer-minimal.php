<?php
/**
 * Template Part: Footer Minimalista
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$opts = $args['opts'] ?? [];
$cols = $args['cols'] ?? [];

$logo_url = !empty( $opts['footer_logo']['url'] ) 
    ? $opts['footer_logo']['url']
    : get_template_directory_uri() . '/assets/img/logo.svg';
?>

<div class="row justify-content-between">
  <!-- Logo -->
  <div class="col-lg-2 footer-brand d-flex flex-column gap-2 align-items-center align-items-lg-start mb-5 mb-xl-0">
    <?php get_template_part( 'template-parts/footer/footer-brand' ); ?>  
  </div>

  <!-- Footer Menu -->
  <div class="col-lg-9 justify-content-end ms-lg-auto text-end">
      <div class="footer-menu text-center text-lg-end mb-4 mb-lg-0">
        <?php if ( !empty( $cols['footer1']['title'] ) ) : ?>
            <p class="footer-menu-title mb-2">
              <?= esc_html($cols['footer1']['title']); ?>
            </p>
        <?php endif; ?>
        <?php wp_nav_menu([
            'theme_location' => $cols['footer1']['menu'],
            'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column flex-lg-row justify-content-lg-end gap-1 gap-lg-4',
        ]); ?>
      </div>

      <div class="d-flex footer-social justify-content-center justify-content-lg-end">
        <?php Social_Links::render(); ?>
      </div>  
  </div>
</div>

