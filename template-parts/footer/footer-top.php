<?php
/**
 * Footer Top Section
 *
 * Displays the top section of the footer with logo, menus, and contact information.
 *
 * @package custom_theme
 */

if ( ! defined('ABSPATH') ) exit;

// Datos de contacto y columnas
$opts         = Template_Helpers::generate_footer_options();
$contact_info = Theme_Helpers::get_contact_info( $opts );
$cols         = Template_Helpers::get_footer_columns( $opts );

// determinar layout: minimal, 3columns, 4columns
$layout = sanitize_key( $opts['footer_layout'] ?? '4columns' );
?>
<div class="footer-top pt-5">
  <div class="container py-4">
    <?php 
    if ( $layout === 'minimal' ) :
        get_template_part( 'template-parts/footer/footer-minimal', null, [ 'opts' => $opts, 'cols' => $cols ] );
    else :
        $count   = count( $cols );
        $col_cls = 'col-md-' . ( $count ? floor( 12 / max(1, $count) ) : 12 );
        ?>

        <div class="row justify-content-center">
            <?php foreach ( $cols as $col ) : ?>
                <div class="<?= esc_attr( $col_cls ); ?> footer-col mb-4 mb-lg-0">

                    <?php if ( $col['type'] === 'logo' ) : ?>
                        <div class="footer-brand text-center text-md-start">
                            <?php get_template_part( 'template-parts/footer/footer-brand' ); ?>  
                        </div>
                    <?php elseif ( $col['type'] === 'menu' ) : ?>
                        <?php if ( $col['title'] ) : ?>
                            <p class="footer-menu-title mb-2"><?= esc_html( $col['title'] ); ?></p>
                        <?php endif; ?>
                        <?php wp_nav_menu([
                            'theme_location' => $col['menu_location'],
                            'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column gap-1',
                            'depth'          => 1,
                        ]); ?>
                    <?php elseif ( $col['type'] === 'contact' ) : ?>
                        <?php if ( $col['title'] ) : ?>
                            <p class="footer-contact-title mb-2"><?php echo esc_html( $col['title'] ); ?></p>
                        <?php endif; ?>
                        <ul class="list-unstyled footer-contact-list">
                            <?php foreach ( $col['contact'] as $info ) :
                            if ( empty( $info['text'] ) || empty( $info['link'] ) ) {
                                continue;
                            }
                            ?>
                            <li class="d-flex align-items-center mb-2">
                                <img
                                src="<?php echo esc_url( $info['icon'] ); ?>"
                                alt="<?php echo esc_attr( $info['alt'] ); ?>"
                                width="20"
                                height="20"
                                class="me-2"
                                >
                                <a href="<?php echo esc_url( $info['link'] ); ?>">
                                <?php echo esc_html( $info['text'] ); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
  </div>
</div>