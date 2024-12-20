<?php
/**
 * Functiones relacionadas con el plugin de Woocommerce
 *
 * @package CustomTheme
 */

/**
 * Eliminar la pestaña "Valoraciones" de la ficha de producto
 */
add_filter('woocommerce_product_tabs', 'remove_reviews_tab', 98);
function remove_reviews_tab($tabs) {
   unset($tabs['reviews']);
   return $tabs;
}
