<?php
/**
 * Footer Kit Digital
 *
 * Displays the footer section for Kit Digital, including EU funding information.
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="container-fluid bg-black py-2">
	<div class="container">
		<div class="ue-footer d-md-flex justify-content-md-between align-items-center">
			<p class="fs12 c-white mb-0"><?php esc_html_e( 'Esta web está financiada por la Unión Europea - Next Generation EU', CTM_TEXTDOMAIN ); ?></p>
			<img src="<?php echo get_template_directory_uri(); ?>/assets/img/ES_Europea.png" alt="<?php esc_attr_e( 'Logotipo web financiada por la Unión Europea', CTM_TEXTDOMAIN ); ?>" />
		</div>
	</div>
</section>