<?php
/**
 * Flexible Block: Localizaciones
 * Muestra un listado de centros, establecimientos, tiendas...
 * Cada uno con imagen, título, descripción y, opcionalmente, un enlace.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Custom_Theme\Helpers\Template_Helpers;

$options = \Custom_Theme\Helpers\Theme_Options::get_all();
$use_options = $args['use_options'] ?? false;

// Obtener origen de datos: opciones globales o repeater del bloque
if ( $use_options ) {
    $items = $options['additional_address'] ?? [];
} else {
    $items = $args['items'] ?? [];
}

if ( empty( $items ) || ! is_array( $items ) ) {
    return;
}
?>
<section class="locations-block mb-5">
    <div class="container">
        
        <?php foreach ( $items as $index => $item ) :
            // Extraer campos comunes
            $title = $use_options
                ? ( $item['name']  ?? '' )
                : ( $item['title'] ?? '' );
            $htag_title = $use_options
                ? 2
                : ( $item['htag_title'] ?? 2 );
            $text = $use_options
                ? ( $item['text'] ?? '' )
                : ( $item['text'] ?? '' );
            $image = $use_options
                ? ( $item['img'] ?? [] )
                : ( $item['img'] ?? [] );
            $link = $use_options
                ? []
                : ( $item['button'] ?? [] );
            $address = $use_options
                ? ( $item['address'] ?? '' )
                : '';
            $maps = $use_options
                ? ( $item['google_maps_link'] ?? '' )
                : '';
            $hours = $use_options
                ? ( $item['opening_hours'] ?? '' )
                : '';

            // Si no hay título ni texto ni imagen, saltar
            if ( ! $title && ! $text && empty( $image['url'] ) ) {
                continue;
            }

            $is_even = ( $index % 2 === 0 );
            $order_text = $is_even ? 'order-lg-2' : '';
            $order_img = $is_even ? 'order-lg-1' : '';
            $bg_color = $is_even ? 'c-bg-secondary' : 'c-bg-light';
            ?>
            <div class="row justify-content-between align-items-center <?php echo esc_attr( $bg_color ); ?>">
                <div class="col-12 col-lg-6 p-5 <?php echo esc_attr( $order_text ); ?>">
                    <?php echo Template_Helpers::tag_title( $htag_title, esc_html( $title ), 'section-title' ); ?>

                    <div class="section-text">
                        <?php if ( ! empty( $text ) ) : ?>
                            <?php echo $text; ?>
                        <?php endif; ?>

                        <?php if ( $use_options ) : ?>
                            <?php if ( ! empty( $opening_hours ) ) : ?>
                                <div class="mb-2"><?php echo wp_kses_post( nl2br( $item_hours ) ); ?></div>
                            <?php endif; ?>
                            
                            <?php if ( $address ) : ?>
                                <div class="address d-flex align-items-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 0C5.85813 0 2.5 3.35813 2.5 7.5C2.5 9.13094 3.03469 10.6275 3.92344 11.8531C3.93938 11.8825 3.94187 11.9153 3.96 11.9434L8.96 19.4434C9.19188 19.7913 9.5825 20 10 20C10.4175 20 10.8081 19.7913 11.04 19.4434L16.04 11.9434C16.0584 11.9153 16.0606 11.8825 16.0766 11.8531C16.9653 10.6275 17.5 9.13094 17.5 7.5C17.5 3.35813 14.1419 0 10 0ZM10 10C8.61937 10 7.5 8.88062 7.5 7.5C7.5 6.11937 8.61937 5 10 5C11.3806 5 12.5 6.11937 12.5 7.5C12.5 8.88062 11.3806 10 10 10Z" fill="#B09E60"/>
                                    </svg>
                                    <?php if ( $maps ) : ?>
                                        <a href="<?php echo esc_url( $maps ); ?>" class="link-address text-decoration-underline">
                                            <?php echo esc_html( $address ); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="text-address"><?php echo esc_html( $address ); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ( ! empty( $link ) ) : ?>
                        <a href="<?php echo esc_url( $link['url'] ); ?>"
                            target="<?php echo esc_attr( $link['target'] ?? '_self' ); ?>"
                            class="btn btn-lg btn-default mt-3">
                            <?php echo esc_html( $link['title'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-lg-6 px-0 <?php echo esc_attr( $order_img ); ?>">
                    <img src="<?php echo esc_url( $image['url'] ); ?>"
                        alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
                        class="img-fluid object-fit-cover fit">
                </div>
            </div><!-- .row -->
        
        <?php endforeach; ?>
    </div><!-- .container -->
</section>
