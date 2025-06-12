<?php
/**
 * Footer Top Section
 *
 * Displays the top section of the footer with logo, menus, and contact information.
 *
 * @package custom_theme
 */

if ( ! defined('ABSPATH') ) exit;

use Custom_Theme\Helpers\Template_Helpers;

// Datos de contacto y columnas
$opts      = Template_Helpers::generate_footer_options();
$cols      = Template_Helpers::get_footer_columns( $opts );
$layout    = $opts['footer_layout'] ?? 'columns';
$col_count = count( $cols );
$col_cls   = 'row-cols-md-' . min( $col_count, 6 );
?>
<div class="footer-top pt-5">
  <div class="container py-4">
    <?php 
    if ( $layout === 'minimal' ) :
        get_template_part( 'template-parts/footer/footer-minimal', null, [ 
            'opts' => $opts,
            'cols' => $cols
        ] );
    else : ?>

        <div class="row justify-content-center row-cols-1 <?= esc_attr( $col_cls ); ?> g-4">
            <?php foreach ( $cols as $col ) : ?>

                <?php
                switch ( $col['type'] ) {
                    // Brand column
                    case 'logo':
                    ?>
                        <div class="footer-brand text-center text-md-start">
                            <?php get_template_part( 'template-parts/footer/footer-brand' ); ?>  
                        </div>

                    <?php
                    break;

                    // Menus columns
                    case 'menu': ?>
                        <?php if ( $col['title'] ) : ?>
                            <p class="footer-menu-title mb-2">
                                <?= esc_html( $col['title'] ); ?>
                            </p>
                        <?php endif;
                        wp_nav_menu([
                            'theme_location' => $col['menu_location'],
                            'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column gap-1',
                            'depth'          => 1,
                        ]);
                    break;

                    // Contact column
                    case 'contact':
                        if ( $col['title'] ) : ?>
                            <p class="footer-contact-title mb-2">
                                <?php echo esc_html( $col['title'] ); ?>
                            </p>
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
                    <?php 
                    
                    break; 
                }
                ?>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>
  </div>
</div>