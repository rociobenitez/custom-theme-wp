<?php
/**
 * Parte del encabezado: barra de navegación principal.
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
$show_social_links = ! empty( $options['show_social_links'] ) ? $options['show_social_links'] : false;

// Logos (ACF permite override, si no existe, usamos default del theme)
$default_logo = ! empty( $options['site_logo']['url'] ) ? esc_url( $options['site_logo']['url'] ) : CTM_THEME_URI . '/assets/img/logo.svg';
$white_logo = ! empty( $options['site_logo_white']['url'] ) ? esc_url( $options['site_logo_white']['url'] ) : CTM_THEME_URI . '/assets/img/logo-white.webp';
$site_name = esc_attr(get_bloginfo('name'));

// Dimensiones del logo
$logo_width = 170;
$logo_height = 48;

// Clases dinámicas ACF
$header_bg_class = is_page_template('page-home.php')
    ? (!empty($options['bg_color_header_home']) ? $options['bg_color_header_home'] : 'bg-transparent')
    : 'bg-white';
$header_border_class = !empty($options['border_header']) ? $options['border_header'] : 'border-none';
?>
<!-- Navbar principal -->
<nav class="navbar navbar-expand-xl navbar-light">
    <div class="container <?= $show_social_links === 'navbar' ? 'justify-content-between' : ''; ?>">

        <!-- Logo -->
        <a class="navbar-brand" href="<?= esc_url( home_url('/') ); ?>">
            <img src="<?= esc_url($default_logo); ?>"
                class="img-brand logo-default" id="logo-default"
                width="<?= $logo_width;?>" height="<?= $logo_height;?>"
                alt="<?= $site_name; ?>">

            <img src="<?= esc_url($white_logo); ?>"
                class="img-brand logo-white" id="logo-white"
                width="<?= $logo_width;?>" height="<?= $logo_height;?>"
                alt="<?= $site_name; ?>">
        </a>

        <!-- Botón hamburguesa para móviles -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" 
            aria-expanded="false" aria-label="<?= esc_attr_e( 'Toggle navigation', CTM_TEXTDOMAIN ); ?>">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú principal -->
        <?php
        $adittional_class = $show_social_links === 'navbar' ? ' mx-xl-auto' : '';
        wp_nav_menu( array(
            'theme_location'  => 'main',
            'depth'           => 3,
            'container'       => 'div',
            'container_class' => 'collapse navbar-collapse',
            'container_id'    => 'navbarNav',
            'menu_class'      => 'navbar-nav ms-auto align-items-xl-center gap-2' . $adittional_class,
            'menu_id'         => 'main-menu',
            'fallback_cb'     => '__return_false',
            // 'walker'          => new Bootstrap_Navwalker(),
        ) );
        ?>

        <?php if ($show_social_links === 'navbar') :?>
            <!-- Iconos de redes sociales -->
            <div class="social-media d-none d-xl-flex align-items-center ms-3">
                <?= social_media();?>
            </div>
        <?php endif;?>

    </div>
</nav>