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
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        // 2. Eliminar pestaña "Valoraciones"
        add_filter( 'woocommerce_product_tabs', [ __CLASS__, 'remove_reviews_tab' ], 98 );

        // 3. Agregar icono de carrito al menú principal
        add_filter( 'wp_nav_menu_items', [ __CLASS__, 'add_woocommerce_cart_icon_to_menu' ], 10, 2 );

        // 4. Actualizar contador del carrito con AJAX
        add_filter( 'woocommerce_add_to_cart_fragments', [ __CLASS__, 'update_cart_count_fragment' ] );

        // 5. Encolar filters.js si es WooCommerce 
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_ajax_filters' ] );

        // 6. AJAX handlers
        add_action( 'wp_ajax_ctm_filter_products', [ __CLASS__, 'filter_products' ] );
        add_action( 'wp_ajax_nopriv_ctm_filter_products', [ __CLASS__, 'filter_products' ] );
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

    /**
     * Encola Ajax filters si es WooCommerce
     */
    public static function enqueue_ajax_filters() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        if ( ! ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
            return;
        }
        wp_enqueue_script(
            'ctm-ajax-filters',
            CTM_THEME_URI . '/assets/js/filters.js',
            [], // sin jQuery
            CTM_THEME_VERSION,
            true
        );
        wp_localize_script('ctm-ajax-filters','ctm_ajax',[
            'ajax_url'=> admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('ctm_filter_products'),
        ]);
    }

    /**
     * Ajax Handlers
     */
    public static function filter_products() {
        // Seguridad
        check_ajax_referer( 'ctm_filter_products', 'nonce' );

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 12,
        ];

        // Construir tax_query
        $tax_query = [];
        foreach ( $_POST as $key => $value ) {
            if ( in_array( $key, ['action','nonce'], true ) ) {
                continue;
            }
            // nombres vienen como brand[], color[], etc.
            if ( is_array( $value ) ) {
                $term_ids = array_map( 'absint', $value );
                if ( empty( $term_ids ) ) {
                    continue;
                }
                $tax_query[] = [
                    'taxonomy' => sanitize_key( $key ),
                    'field'    => 'term_id',
                    'terms'    => $term_ids,
                    'operator' => 'IN',
                ];
            }
        }
        if ( ! empty( $tax_query ) ) {
            $tax_query['relation'] = 'AND';
            $args['tax_query'] = $tax_query;
        }

        // Ejecutar WP_Query
        $q = new \WP_Query( $args );
        ob_start();
        if ( $q->have_posts() ) {
            while ( $q->have_posts() ) {
                $q->the_post();
                wc_get_template_part( 'content', 'product' );
            }
        } else {
            echo '<p class="text-center">' . esc_html__( 'No products found', 'woocommerce' ) . '</p>';
        }
        wp_reset_postdata();

        wp_send_json_success( [ 'html' => ob_get_clean() ] );
    }
}
