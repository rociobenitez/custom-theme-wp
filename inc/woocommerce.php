<?php
/**
 * Funciones relacionadas con el plugin WooCommerce
 *
 * Este archivo incluye funciones para personalizar y extender la funcionalidad de WooCommerce,
 * como la eliminación de pestañas, la adición de elementos al menú, y la actualización dinámica del carrito.
 *
 * @package CustomTheme
 */

/**
 * Añadir soporte para WooCommerce
 */
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );
function mytheme_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' ); // Habilita zoom
    add_theme_support( 'wc-product-gallery-lightbox' ); // Habilita lightbox
    add_theme_support( 'wc-product-gallery-slider' ); // Habilita slider
}

/**
 * Eliminar la pestaña "Valoraciones" de la ficha de producto
 *
 * Esta función elimina la pestaña de valoraciones (reviews) en las páginas de productos.
 *
 * @param array $tabs Las pestañas del producto.
 * @return array Las pestañas modificadas sin la pestaña de valoraciones.
 */
function remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'remove_reviews_tab', 98);

/**
 * Añadir el icono del carrito al menú principal
 *
 * Esta función agrega un ícono de carrito en el menú principal, mostrando también
 * la cantidad de productos en el carrito. Se posiciona como penúltimo elemento del menú.
 *
 * @param string $items Los elementos HTML del menú.
 * @param object $args  Los argumentos del menú.
 * @return string Los elementos del menú con el carrito añadido.
 */
function add_woocommerce_cart_icon_to_menu($items, $args) {
    if ($args->theme_location === 'primary') {
        ob_start();
        ?>
        <li class="menu-item menu-item-cart mx-2">
            <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('Ver tu carrito', 'woocommerce'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 6A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.49-.598L4.89 6H2.311l-.805 3.21a.5.5 0 0 1-.97-.22l1.5-6A.5.5 0 0 1 2.5 2H.5a.5.5 0 0 1-.5-.5ZM5.5 12a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm7 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/>
                </svg>
                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
            </a>
        </li>
        <?php
        $cart_icon = ob_get_clean();

        // Insertar el ícono como penúltimo elemento
        $items_array = explode('</li>', $items);
        array_splice($items_array, -2, 0, $cart_icon);
        $items = implode('</li>', $items_array);
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'add_woocommerce_cart_icon_to_menu', 10, 2);

/**
 * Actualizar el contador del carrito con AJAX
 *
 * Permite que el contador del carrito se actualice dinámicamente cuando un producto es añadido,
 * sin necesidad de recargar la página.
 *
 * @param array $fragments Los fragmentos de HTML que se actualizarán dinámicamente.
 * @return array Los fragmentos de HTML modificados con el contador del carrito actualizado.
 */
function update_cart_count_fragment($fragments) {
    ob_start();
    ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'update_cart_count_fragment');
