<?php
/**
 * CTA Lateral con Formulario de Contacto
 *
 * Muestra un llamado a la acción con teléfono (móvil) y un formulario (escritorio).
 * Si no se configura un formulario de Gravity Forms, muestra un formulario de contacto básico.
 *
 * @package custom_theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Obtener opciones globales
$options = \Custom_Theme\Helpers\Theme_Options::get_all();

// Clases de estilo
$btn_class = 'btn btn-md btn-primary w-100 mt-3 d-flex justify-content-center align-items-center';

// Contenido de CTA
$phone            = $options['phone']                  ?? '';
$cta_title_mobile = $options['sidebar_cta_title']      ?? esc_html__( '¿Quieres que te llamemos?', CTM_TEXTDOMAIN );
$cta_title_desktop= $options['sidebar_form_title_cta'] ?? esc_html__( 'Contacta con nosotros', CTM_TEXTDOMAIN );
$cta_text_mobile  = $options['sidebar_cta_text']       ?? '';
$cta_text_form    = $options['sidebar_form_text_cta']  ?? '';

// ID de Gravity Form
$form_id      = isset( $options['sidebar_form_id'] ) ? intval( $options['sidebar_form_id'] ) : 0;
$gf_available = ( $form_id && shortcode_exists( 'gravityform' ) );

// Condición de despliegue: al menos teléfono o formulario
if ( empty( $phone ) && ! $gf_available ) {
    return;
}
?>
<div id="cta-sidebar" class="cta-sticky overflow-hidden">
    <div class="contact p-4 c-bg-light">

        <!-- Versión móvil: botón teléfono -->
        <?php if ( $phone ) : ?>
            <div class="d-md-none text-center pt-2">
                <p class="cta-title heading-4 mb-3">
                    <?php echo esc_html( $cta_title_mobile ); ?>
                </p>
                <?php if ( $cta_text_mobile ) : ?>
                    <div class="cta-description fs15">
                        <?php echo wp_kses_post( wpautop( $cta_text_mobile ) ); ?>
                    </div>
                <?php endif; ?>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="<?php echo esc_attr( $btn_class ); ?>">
                    <span class="me-2" aria-hidden="true">
                        <!-- Icono teléfono -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27c1.2.48 2.5.75 3.85.75a1 1 0 011 1v3.5a1 1 0 01-1 1C10.07 21.5 2.5 13.93 2.5 4a1 1 0 011-1H7a1 1 0 011 1c0 1.35.25 2.65.73 3.85a1 1 0 01-.27 1.11l-2.2 2.2z"/>
                        </svg>
                    </span>
                    <?php esc_html_e( 'Llamar ahora', CTM_TEXTDOMAIN ); ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Versión escritorio: formulario -->
        <div class="d-none d-md-block pt-2">
            <p class="cta-title heading-4 mb-3">
                <?php echo esc_html( $cta_title_desktop ); ?>
            </p>
            <?php if ( $cta_text_form ) : ?>
                <div class="cta-description fs15">
                    <?php echo wp_kses_post( wpautop( $cta_text_form ) ); ?>
                </div>
            <?php endif; ?>

            <div class="contact-form">
                <?php if ( $gf_available && $form_id != 0 ) : ?>
                    <?php echo do_shortcode( sprintf( '[gravityform id="%d" title="false" description="false" ajax="true"]', $form_id ) ); ?>
                <?php else : ?>
                    <!-- Formulario de fallback: Correo electrónico simple -->
                    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                        <input type="hidden" name="action" value="sidebar_cta_contact">
                        <div class="mb-3">
                            <label for="cta-name" class="form-label"><?php esc_html_e( 'Nombre', CTM_TEXTDOMAIN ); ?></label>
                            <input type="text" id="cta-name" name="cta_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="cta-email" class="form-label"><?php esc_html_e( 'Email', CTM_TEXTDOMAIN ); ?></label>
                            <input type="email" id="cta-email" name="cta_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="cta-message" class="form-label"><?php esc_html_e( 'Mensaje', CTM_TEXTDOMAIN ); ?></label>
                            <textarea id="cta-message" name="cta_message" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="<?php echo esc_attr( $btn_class ); ?>">
                            <?php esc_html_e( 'Enviar', CTM_TEXTDOMAIN ); ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
