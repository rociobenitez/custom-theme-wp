<?php
namespace Custom_Theme\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Clase que engloba todas las personalizaciones de WooCommerce
 * para nuestro theme base.
 * Solo se inicializa si WooCommerce está activo.
 */
class WC_Support {

    /**
     * Registra todos los hooks de WooCommerce.
     * Llamar desde Custom_Theme::init() solo si class_exists('WooCommerce').
     */
    public static function init() {
        // 1. Soporte básico
        add_action( 'after_setup_theme', [ __CLASS__, 'add_woocommerce_support' ] );

        // 2. Eliminar pestaña "Valoraciones"
        add_filter( 'woocommerce_product_tabs', [ __CLASS__, 'remove_reviews_tab' ], 98 );

        // 3. Agregar icono de carrito al menú principal
        add_filter( 'wp_nav_menu_items', [ __CLASS__, 'add_woocommerce_cart_icon_to_menu' ], 10, 2 );

        // 4. Actualizar contador del carrito con AJAX
        add_filter( 'woocommerce_add_to_cart_fragments', [ __CLASS__, 'update_cart_count_fragment' ] );
    }

    /**
     * Añadir soporte para WooCommerce y características de galería.
     */
    public static function add_woocommerce_support() {
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }

    /**
     * Elimina la pestaña "Valoraciones" de la ficha de producto.
     *
     * @param array $tabs Las pestañas del producto.
     * @return array
     */
    public static function remove_reviews_tab( $tabs ) {
        if ( isset( $tabs['reviews'] ) ) {
            unset( $tabs['reviews'] );
        }
        return $tabs;
    }

    /**
     * Añade el icono de carrito al final del menú principal (theme_location = 'primary').
     * Muestra cantidad de ítems y se actualiza con AJAX.
     *
     * @param string $items HTML generado por wp_nav_menu.
     * @param object $args  Argumentos pasados a wp_nav_menu.
     * @return string
     */
    public static function add_woocommerce_cart_icon_to_menu( $items, $args ) {
        if ( 'primary' === $args->theme_location ) {
            ob_start();
            ?>
            <li class="menu-item menu-item-cart mx-2">
                <a class="cart-contents d-flex align-items-center" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Ver tu carrito', 'woocommerce' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 6A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.49-.598L4.89 6H2.311l-.805 3.21a.5.5 0 0 1-.97-.22l1.5-6A.5.5 0 0 1 2.5 2H.5a.5.5 0 0 1-.5-.5ZM5.5 12a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm7 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/>
                    </svg>
                    <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                </a>
            </li>
            <?php
            $cart_icon = ob_get_clean();

            // Insertar antes del cierre </ul> del menú (penúltimo <li>)
            // Dividimos por </li> para reinsertar el icono
            $items_array = explode( '</li>', $items );
            array_splice( $items_array, -2, 0, $cart_icon );
            $items = implode( '</li>', $items_array );
        }
        return $items;
    }

    /**
     * Actualiza el fragmento del contador del carrito por AJAX.
     *
     * @param array $fragments Array de fragmentos a devolver.
     * @return array
     */
    public static function update_cart_count_fragment( $fragments ) {
        ob_start();
        ?>
        <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        return $fragments;
    }
}
