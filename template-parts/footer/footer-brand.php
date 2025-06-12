<?php
/**
 * Componente Logo del Footer
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;

$opts = Template_Helpers::generate_footer_options();

$footer_logo = ! empty( $opts['footer_logo']['url'] )
    ? esc_url( $opts['footer_logo']['url'] )
    : get_template_directory_uri() . '/assets/img/logo.svg';
?>
<a href="<?= esc_url( home_url( '/' ) ); ?>" rel="home">
    <img src="<?= esc_url( $footer_logo ); ?>"
        alt="<?= esc_attr( get_bloginfo('name') ); ?>"
        class="footer-logo" width="160" height="60">
</a>

<?php if ( !empty( $opts['footer_text_below_logo'] ) ) : ?>
<div class="footer-text-below-logo mt-3">
    <?= $opts['footer_text_below_logo']; ?>
</div>
<?php endif; ?>  

<?php if ( !empty( $opts['footer_cta_button'] ) ) : ?>
<a href="<?php echo esc_url( $opts['footer_cta_button']['url'] ); ?>"
    class="btn btn-lg btn-outline-secondary mt-2"
    target="<?php echo esc_attr( $opts['footer_cta_button']['target'] ?? '_self' ); ?>">
    <?php echo esc_html( $opts['footer_cta_button']['title'] ); ?>
</a>
<?php endif; ?>  
