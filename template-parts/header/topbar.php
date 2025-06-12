<?php
/**
 * Topbar general (contacto + social)
 *
 * Solo se muestra si:
 *  - ACF Options: show_topbar = true
 *  - Hay al menos un contacto o, show_social_links = 'topbar' y hay redes
 *
 * Variables ACF usadas:
 *  - show_topbar (true_false)
 *  - bg_color_topbar (css class)
 *  - border_topbar (css class)
 *  - show_social_links (radio: topbar | navbar | none)
 *
 * Contacto obtenido vía Theme_Options::get_contact_options()
 * Social via Social_Links::get_all()
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Custom_Theme\Helpers\Theme_Options;
use Custom_Theme\Helpers\Social_Links;

$show_topbar       = Theme_Options::get( 'show_topbar', false );
$bg_class          = Theme_Options::get( 'bg_color_topbar', 'bg-black' );
$border_class      = Theme_Options::get( 'border_topbar', 'border-none' );
$social_position   = Theme_Options::get( 'show_social_links', 'topbar' );

if ( ! $show_topbar ) {
    return;
}

$contact = Theme_Options::get_contact_options();
$social  = Social_Links::get_all();

// si no hay nada que mostrar, salimos
if ( empty( array_filter( $contact ) ) && ( $social_position !== 'topbar' || empty( $social ) ) ) {
    return;
}

?>
<div class="topbar <?php echo esc_attr( $bg_class . ' ' . $border_class ); ?> py-1">
  <div class="container d-flex justify-content-between align-items-center">

    <!-- Contacto -->
    <?php if ( ! empty( array_filter( $contact ) ) ) : ?>
      <div class="topbar-contact d-flex gap-3">
        <?php if ( $contact['phone'] ) : ?>
          <a href="tel:<?php echo esc_attr( preg_replace('/\D/','',$contact['phone']) ); ?>">
            <i class="bi bi-telephone me-1"></i>
            <?php echo esc_html( $contact['phone'] ); ?>
          </a>
        <?php endif; ?>

        <?php if ( $contact['whatsapp'] ) : ?>
          <a href="https://wa.me/<?php echo esc_attr( preg_replace('/\D/','',$contact['whatsapp']) ); ?>">
            <i class="bi bi-whatsapp me-1"></i>
            <?php echo esc_html( $contact['whatsapp'] ); ?>
          </a>
        <?php endif; ?>

        <?php if ( $contact['email'] ) : ?>
          <a href="mailto:<?php echo esc_attr( $contact['email'] ); ?>">
            <i class="bi bi-envelope me-1"></i>
            <?php echo esc_html( $contact['email'] ); ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Social links -->
    <?php if ( $social_position === 'topbar' && ! empty( $social ) ) : ?>
      <div class="topbar-social">
        <?php Social_Links::render(); ?>
      </div>
    <?php endif; ?>

  </div>
</div>
