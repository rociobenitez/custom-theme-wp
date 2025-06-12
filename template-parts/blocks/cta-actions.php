<?php
/**
 * Bloque de CTA's de contacto
 * 
 * @package custom_theme
 */

if ( ! defined('ABSPATH') ) exit;
use Custom_Theme\Helpers\Theme_Options;

$actions = Theme_Options::get_cta_actions();
if ( empty($actions) ) {
    return;
}
?>
<section class="cta-actions py-5 c-bg-light">
  <div class="container">
    <div class="row justify-content-center g-4">
      <?php foreach ( $actions as $act ) : ?>
        <div class="col-md-6 col-lg-3">
            <div class="cta-actions-content c-bg-white p-4">
                <p class="cta-actions-title mb-3"><?= esc_html( $act['title'] ); ?></p>
                <p class="cta-actions-text"><?= esc_html( $act['text'] ); ?></p>
                <a href="<?= esc_url( $act['button']['url'] ); ?>"
                   class="btn btn-md btn-outline-secondary mt-4 w-100"
                   target="<?= esc_attr( $act['button']['target'] ?? '_blank' ); ?>">
                  <?= esc_html( $act['button']['title'] ); ?>
                </a>
            </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
