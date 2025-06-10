<?php
/**
 * Custom WordPress Theme Functions
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_template_directory() . '/inc/class-custom-theme.php';
add_action( 'after_setup_theme', [ 'Custom_Theme\\Custom_Theme', 'init' ] );
