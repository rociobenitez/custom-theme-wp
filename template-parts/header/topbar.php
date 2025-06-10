<?php

/**
 * Parte superior del encabezado: barra superior.
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Custom_Theme\Helpers\Theme_Options;

// Obtener opciones de tema
$options = Theme_Options::get_all();

// Obtener opciones de contacto
$contact_options = Theme_Options::get_contact_options();

// Clases dinámicas ACF
$topbar_bg_class = ! empty( $options['bg_color_topbar'] ) ? $options['bg_color_topbar'] : 'bg-black';
$topbar_border_class = ! empty( $options['border_topbar'] ) ? $options['border_topbar'] : 'border-none';
$show_topbar = ! empty( $options['show_topbar'] ) ? true : false;
$show_social_links = ! empty( $options['show_social_links'] ) ? $options['show_social_links'] : false;
?>

<?php if ( $show_topbar ) :?>
    <!-- Topbar -->
    <div class="topbar d-none d-md-block <?= esc_attr( $topbar_bg_class . ' ' . $topbar_border_class); ?>">
        <div class="container d-flex py-2 <?= $show_social_links === 'topbar' ? 'justify-content-between' : 'justify-content-end'; ?> <?= esc_attr($topbar_border_class); ?>">
            <?php if ( !empty( array_filter( $contact_options ) ) ) : ?>
                <div class="icons-contact d-flex align-items-center gap-3">
                    <?php foreach ($contact_options as $type => $value) : ?>
                        <?= generate_contact_link($type, $value); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($show_social_links === 'topbar') : ?>
                <!-- Iconos de redes sociales -->
                <div class="social-media d-flex align-items-center">
                    <?= social_media(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif;?>