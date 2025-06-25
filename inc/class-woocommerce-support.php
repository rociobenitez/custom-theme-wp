<?php
namespace Custom_Theme\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Clase que engloba todas las personalizaciones de WooCommerce
 * para nuestro theme base. 
 */
class WC_Support {

    /**
     * Registra todos los hooks de WooCommerce.
     * Llamar desde Custom_Theme::init() solo si WooCommerce está activo.
     */
    public static function init() {
        // Soporte base de WooCommerce + Galería
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        // Habilitar el nuevo editor de widgets en bloque
        add_theme_support( 'widgets-block-editor' );

        // Eliminar reviews tab en la ficha de producto
        add_filter( 'woocommerce_product_tabs', [ __CLASS__, 'remove_reviews_tab' ], 98 );

        // Icono de carrito + AJAX cart count
        add_filter( 'wp_nav_menu_items', [ __CLASS__, 'add_woocommerce_cart_icon_to_menu' ], 10, 2 );
        add_filter( 'woocommerce_add_to_cart_fragments', [ __CLASS__, 'update_cart_count_fragment' ] );

        // Incluir todas las taxonomías de producto en layered nav
        // add_filter( 'woocommerce_layered_nav_taxonomies', [ __CLASS__, 'add_all_product_taxonomies' ] );

        // Filtros AJAX
        // add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_ajax_filters' ] );
        // add_action( 'pre_get_posts', [ __CLASS__, 'apply_query_filters' ], 20 );
        // add_action( 'wp_ajax_ctm_filter_products', [ __CLASS__, 'ajax_filter_products' ] );
        // add_action( 'wp_ajax_nopriv_ctm_filter_products', [ __CLASS__, 'ajax_filter_products' ] );

        // add_filter( 'woocommerce_layered_nav_taxonomies',
        //     function( $taxonomies ) {
        //         // incluimos marcas y categorías siempre
        //         $taxonomies[] = 'product_brand';
        //         $taxonomies[] = 'product_cat';
        //         return array_unique( $taxonomies );
        //     }
        // );

    }

    /**
     * Elimina la pestaña "Valoraciones" de la ficha de producto.
     *
     * @param array $tabs Las pestañas del producto.
     * @return array
     */
    public static function remove_reviews_tab( $tabs ) {
        unset( $tabs['reviews'] );
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
        if ( 'primary' !== $args->theme_location ) {
            return $items;
        }

        ob_start();
        ?>
        <li class="menu-item menu-item-cart mx-2">
            <a class="cart-contents d-flex align-items-center" 
                href="<?php echo esc_url( wc_get_cart_url() ); ?>" 
                title="<?php esc_attr_e( 'Ver tu carrito', 'woocommerce' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                    fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 6A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.49-.598L4.89 6H2.311l-.805 3.21a.5.5 0 0 1-.97-.22l1.5-6A.5.5 0 0 1 2.5 2H.5a.5.5 0 0 1-.5-.5ZM5.5 12a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm7 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/>
                </svg>
                <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            </a>
        </li>
        <?php
        $icon = ob_get_clean();

        // Insertar antes de </ul> principal
        return str_replace( '</ul>', $icon . '</ul>', $items );
    }

    /**
     * Actualiza el fragmento del contador del carrito por AJAX.
     *
     * @param array $fragments Array de fragmentos a devolver.
     * @return array
     */
    public static function update_cart_count_fragment( $fragments ) {
        ob_start(); ?>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        return $fragments;
    }

    /**
     * Añade todas las taxonomías de producto al widget layered nav.
     */
    public static function add_all_product_taxonomies( $taxonomies ) {
        return get_object_taxonomies( 'product', 'names' );
    }

    /**
     * Encola JS de filtros solo si es WooCommerce
     */
    // public static function enqueue_ajax_filters() {
    //     if ( ! class_exists('WooCommerce') ) {
    //         return;
    //     }
    //     if ( ! ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) ) {
    //         return;
    //     }
    //     wp_enqueue_script(
    //         'ctm-ajax-filters',
    //         CTM_THEME_URI . '/assets/js/ajax-filters.js',
    //         [], CTM_THEME_VERSION, true
    //     );
    //     wp_localize_script('ctm-ajax-filters','ctm_ajax',[
    //         'ajax_url'=> admin_url('admin-ajax.php'),
    //         'nonce'   => wp_create_nonce('ctm_filter'),
    //     ]);
    // }

     /**
     * Antes de la consulta principal de WooCommerce, aplica tax_query, price y stock desde $_GET.
     */
    // public static function apply_query_filters( $query ) {
    //     if ( is_admin() || ! $query->is_main_query() ) {
    //         return;
    //     }
    //     if ( ! ( is_shop() || is_product_taxonomy() ) ) {
    //         return;
    //     }

    //     // 1) Taxonomías (cualquier taxonomy de producto)
    //     $tax_query = [];
    //     foreach ( $_GET as $key => $value ) {
    //         if ( taxonomy_exists( $key ) && is_string( $value ) ) {
    //             $terms = array_filter( explode( ',', $value ), 'absint' );
    //             if ( $terms ) {
    //                 $tax_query[] = [
    //                     'taxonomy' => sanitize_key( $key ),
    //                     'field'    => 'term_id',
    //                     'terms'    => $terms,
    //                     'operator' => 'IN',
    //                 ];
    //             }
    //         }
    //     }
    //     if ( $tax_query ) {
    //         $tax_query['relation'] = 'AND';
    //         $query->set( 'tax_query', $tax_query );
    //     }

    //     // 2) Precio (WooCommerce usa min_price / max_price en query vars)
    //     if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
    //         // Dejamos que WooCommerce lo procese: ellos añaden su propio meta_query
    //         $query->set( 'min_price', wc_clean( $_GET['min_price'] ?? '' ) );
    //         $query->set( 'max_price', wc_clean( $_GET['max_price'] ?? '' ) );
    //     }

    //     // 3) Stock
    //     if ( isset( $_GET['stock'] ) && $_GET['stock'] === 'instock' ) {
    //         $meta = [
    //             [
    //                 'key'   => '_stock_status',
    //                 'value' => 'instock',
    //             ]
    //         ];
    //         $query->set( 'meta_query', $meta );
    //     }
    // }

    /**
     * Handler AJAX de recarga completa (si necesitas esta vía en vez de pre_get_posts).
     */
    // public static function ajax_filter_products() {
    //     check_ajax_referer( 'ctm_filter', 'nonce' );

    //     // Montar args idénticos a apply_query_filters()
    //     $args = [
    //         'post_type'      => 'product',
    //         'post_status'    => 'publish',
    //         'posts_per_page' => apply_filters( 'woocommerce_loop_shop_per_page', wc_get_default_products_per_row() * 2 ),
    //     ];

    //     // Taxonomies
    //     $tax_query = [];
    //     $filters   = isset( $_POST['filters'] ) ? (array) $_POST['filters'] : [];
    //     foreach ( $filters as $tax => $terms ) {
    //         $tax_query[] = [
    //             'taxonomy' => sanitize_key( $tax ),
    //             'field'    => 'term_id',
    //             'terms'    => array_map( 'intval', (array) $terms ),
    //             'operator' => 'IN',
    //         ];
    //     }
    //     if ( $tax_query ) {
    //         $tax_query['relation'] = 'AND';
    //         $args['tax_query']     = $tax_query;
    //     }

    //     // Price
    //     if ( isset( $_POST['min_price'] ) || isset( $_POST['max_price'] ) ) {
    //         $args['min_price'] = wc_clean( $_POST['min_price'] ?? '' );
    //         $args['max_price'] = wc_clean( $_POST['max_price'] ?? '' );
    //     }

    //     // Stock
    //     if ( ! empty( $_POST['stock'] ) && $_POST['stock'] === 'instock' ) {
    //         $args['meta_query'] = [
    //             [
    //                 'key'   => '_stock_status',
    //                 'value' => 'instock',
    //             ]
    //         ];
    //     }

    //     $q = new \WP_Query( $args );
    //     ob_start();
    //     if ( $q->have_posts() ) {
    //         while ( $q->have_posts() ) {
    //             $q->the_post();
    //             wc_get_template_part( 'content', 'product' );
    //         }
    //     } else {
    //         echo '<p class="text-center">' . esc_html__( 'No se encontraron productos.', 'custom_theme' ) . '</p>';
    //     }
    //     wp_reset_postdata();

    //     wp_send_json_success( [ 'html' => ob_get_clean() ] );
    // }
}
