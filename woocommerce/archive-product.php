<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

$current_term = get_queried_object(); // Obtener el término actual (categoría de producto)
$fields = get_fields($current_term); // Obtener los campos de ACF para este término

// Mostrar la cabecera de la página
get_template_part('template-parts/pageheader', null, ['pageheader_style' => 'bg-image']);
?>

<!-- Contenido principal de la tienda -->
<div class="woocommerce-shop py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-12 col-lg-3">
                <?php get_template_part('template-parts/sidebar', 'shop'); ?>
            </aside>

            <!-- Productos -->
            <div class="col-12 col-lg-9">
                <?php if ( woocommerce_product_loop() ) : ?>
                    <!-- Ordenar y filtros -->
                    <div class="shop-filters d-flex justify-content-between align-items-center mb-4">
                        <?php do_action( 'woocommerce_before_shop_loop' ); ?>
                    </div>

                    <!-- Lista de productos -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <div class="col">
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <?php do_action( 'woocommerce_after_shop_loop' ); ?>
                <?php else : ?>
                    <p class="text-center"><?php esc_html_e( 'No se encontraron productos.', 'woocommerce' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php // Cargar bloques flexibles dinámicamente
    if ($fields && isset($fields['flexible_content']) && is_array($fields['flexible_content'])) {
        require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
        load_flexible_blocks($fields['flexible_content']);
    }
    ?>
</div>

<?php get_footer(); ?>
