<?php
/**
 * Componente Logo del Footer
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$opts = Template_Helpers::generate_footer_options();
$cols = Template_Helpers::get_footer_columns( $opts );
?>
<a href="<?= esc_url( home_url( '/' ) ); ?>" rel="home">
    <img src="<?= esc_url( $cols['logo_url'] ); ?>"
        alt="<?= esc_attr( get_bloginfo('name') ); ?>"
        class="footer-logo" width="160" height="60">
</a>

<?php if ( !empty( $cols['text'] ) ) : ?>
<div class="footer-text-below-logo">
    <?= $cols['text']; ?>
</div>
<?php endif; ?>  
