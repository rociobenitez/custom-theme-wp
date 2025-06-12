<?php
/**
 * The template for displaying the footer.
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;
$opts = Template_Helpers::generate_footer_options();
?>

<footer id="site-footer" role="contentinfo" class="site-footer c-bg-white">
    <?php
    // Footer Top Section: logo, menús y contacto
    get_template_part( 'template-parts/footer/footer-top' );
    // Footer Bottom Section: copyright, social links, etc.
    get_template_part( 'template-parts/footer/footer-bottom' );
    ?>
</footer>

<?php
// Módulo Kit Digital si está habilitado
if ( !empty( $opts['kit_digital'] ) ) :
    get_template_part( 'template-parts/footer/footer-kitdigital' );
endif;

// Contactos flotantes (WhatsApp, teléfono, email)
get_template_part('template-parts/footer/footer-contacts', null, [
    'whatsapp' => $opts['whatsapp'] ?? '',
    'phone'    => $opts['phone'] ?? '',
    'email'    => $opts['email'] ?? ''
]);
?>

<!-- Scroll to Top Button -->
<a href="#" class="scroll-to-top" aria-label="<?php esc_attr_e( 'Ir arriba', CTM_TEXTDOMAIN ); ?>"><i class="bi bi-arrow-up"></i></a>

<?php wp_footer(); ?>
</body>
</html>