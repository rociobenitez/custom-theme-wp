<?php
/**
 * Footer Contacts
 *
 * Muestra botones de contacto en el footer (WhatsApp, Teléfono, Email).
 */

$whatsapp = get_fields('whatsapp', 'option'); // Campo ACF global para WhatsApp
$phone    = get_fields('phone', 'option');    // Campo ACF global para Teléfono
$email    = get_fields('email', 'option');    // Campo ACF global para Email
?>

<div class="footer-contacts">
    <a class="footer-contacts__btn footer-contacts__btn--main" role="button" aria-label="Botón Principal">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
    <?php if (!empty($whatsapp)) : ?>
        <a href="https://wa.me/+34<?php echo str_replace(' ', '', $whatsapp); ?>" class="footer-contacts__btn footer-contacts__btn--wp" role="button" aria-label="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    <?php endif; ?>
    <?php if (!empty($phone)) : ?>
        <a href="tel:<?php echo esc_attr($phone); ?>" class="footer-contacts__btn footer-contacts__btn--phone" role="button" aria-label="Teléfono">
            <i class="fa-solid fa-phone"></i>
        </a>
    <?php endif; ?>
    <?php if (!empty($email)) : ?>
        <a href="mailto:<?php echo esc_attr($email); ?>" class="footer-contacts__btn footer-contacts__btn--email" role="button" aria-label="Email">
            <i class="fa-regular fa-envelope"></i>
        </a>
    <?php endif; ?>
</div>
