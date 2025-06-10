<?php
/**
 * Footer Bottom
 *
 * Displays the bottom section of the footer with copyright and legal links.
 *
 * @package CustomTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="footer-bottom container d-flex flex-column-reverse flex-lg-row justify-content-between align-items-center mt-5 py-3 border-top border-secondary">
    <div class="footer-copy my-auto text-center text-lg-start">
        <p class="text-secondary fs13 lh140 mb-0 px-4 px-sm-0">
            <?php Theme_Helpers::footer_copy(); ?>
        </p>
    </div>
    <?php if ( has_nav_menu( 'legal' ) ) : ?>
        <div class="legal-links d-flex mb-3 mb-lg-0 gap-1">
            <?php wp_nav_menu( array(
                'theme_location' => 'legal',
                'menu_class'     => 'navbar-nav d-flex flex-column flex-sm-row gap-0 gap-sm-3 fs14 text-center',
                'depth'          => 1,
            ) ); ?>
        </div>
    <?php endif; ?>
</div>