<?php

/**
 * Fichero de favicons y app icons.
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon.ico" sizes="any" />
<link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon.svg" />
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/android-chrome-192x192.png" sizes="192x192" />
<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/android-chrome-512x512.png" sizes="512x512" />

<!-- Apple Touch Icon (iOS) -->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">

<!-- Microsoft Tiles (Windows 8 / IE10+) -->
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/assets/favicons/mstile-144x144.png">

<!-- Web App Manifest (PWA) -->
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/site.webmanifest">
