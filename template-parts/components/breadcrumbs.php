<?php
/**
 * Genera los breadcrumbs de forma dinámica y adaptable a WooCommerce y otras plantillas.
 */
function generate_breadcrumbs() {
    $home_text      = 'Inicio';
    $separator      = '&nbsp;&nbsp;|&nbsp;&nbsp;';
    $separator_icon = '<svg height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff"><path d="M400-280v-400l200 200-200 200Z"/></svg>';
    $class_nav      = 'breadcrumb d-flex mb-0';
    $class_link     = 'breadcrumb-link';

    echo '<div class="breadcrumbs pt-3">';
    echo '<nav aria-label="Breadcrumbs" class="' . esc_attr($class_nav) . '">';
    echo '<a href="' . esc_url(home_url()) . '" class="' . esc_attr($class_link) . '">' . esc_html($home_text) . '</a><span class="separator">' . $separator . '</span>';

    // Single Producto
    if (is_product()) {
        $terms = wc_get_product_terms(get_the_ID(), 'product_cat');
        if (!empty($terms)) {
            $term       = $terms[0];
            $ancestors  = get_ancestors($term->term_id, 'product_cat');
            $ancestors  = array_reverse($ancestors);

            foreach ($ancestors as $ancestor) {
                $parent_term = get_term($ancestor, 'product_cat');
                echo '<a href="' . esc_url(get_term_link($parent_term)) . '" class="' . esc_attr($class_link) . '">'
                     . esc_html($parent_term->name) . '</a><span class="separator">' . $separator . '</span>';
            }
            echo '<a href="' . esc_url(get_term_link($term)) . '" class="' . esc_attr($class_link) . '">'
                 . esc_html($term->name) . '</a><span class="separator">' . $separator . '</span>';
        }
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';

    // Categoría de Producto
    } elseif (is_product_category()) {
        $current_term = get_queried_object();
        $ancestors    = get_ancestors($current_term->term_id, 'product_cat');
        $ancestors    = array_reverse($ancestors);

        foreach ($ancestors as $ancestor) {
            $parent_term = get_term($ancestor, 'product_cat');
            echo '<a href="' . esc_url( get_term_link($parent_term) ) . '" class="' . esc_attr($class_link) . '">'
                 . esc_html($parent_term->name) . '</a><span class="separator">' . $separator . '</span>';
        }
        echo '<span class="current">' . esc_html( $current_term->name ) . '</span>';

    // Página de la Tienda
    } elseif (is_shop()) {
        echo '<span class="current">' . esc_html__('Tienda', 'woocommerce') . '</span>';

    // Post Simple
    } elseif (is_single()) {
        $post_type = get_post_type();
        // Si es un post del blog
        if ($post_type === 'post') {
            // Enlace al archivo del blog
            $blog_page_id  = get_fields('page_blog', 'option');
            $blog_page_url = $blog_page_id ? get_permalink( $blog_page_id ) : '/blog/';
            echo '<a href="' . esc_url($blog_page_url) . '" class="' . esc_attr($class_link) . '">Blog</a><span class="separator">' . $separator . '</span>';
        } elseif ($post_type !== 'post') {
            // Para otros tipos de post personalizados (CPT)
            $post_type_object = get_post_type_object($post_type);
            echo '<a href="' . esc_url(get_post_type_archive_link($post_type)) . '" class="' . esc_attr($class_link) . '">'
                . esc_html($post_type_object->labels->name) . '</a><span class="separator">' . $separator . '</span>';
        }

        // Mostrar el título del post actual
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';

    // Página Estática
    } elseif (is_page()) {
        global $post;
        if ($post && $post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);

            foreach ($ancestors as $ancestor) {
                echo '<a href="' . esc_url(get_permalink($ancestor)) . '" class="' . esc_attr($class_link) . '">'
                     . esc_html(get_the_title($ancestor)) . '</a><span class="separator">' . $separator . '</span>';
            }
        }
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';

    // Página de Categorías del blog
    } elseif (is_category()) {
        $current_category = get_queried_object();
        echo '<span class="current">' . esc_html( $current_category->name ) . '</span>';
        
    // Página de Archivo
    } elseif (is_archive()) {
        $current_term = get_queried_object();
        $title = esc_html($current_term->name);
        echo '<span class="current">' . esc_html__( $title, CTM_TEXTDOMAIN ) . '</span>';

    // Resultados de Búsqueda
    } elseif (is_search()) {
        echo esc_html__( 'Resultados de búsqueda para: ', CTM_TEXTDOMAIN ) . esc_html( get_search_query() );

    // 404 - Página no encontrada
    } elseif (is_404()) {
        echo '<span class="current">' . esc_html__( 'Página no encontrada', CTM_TEXTDOMAIN ) . '</span>';

    // Otras Páginas
    } else {
        echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';
    }

    echo '</nav>';
    echo '</div>';
}

// Ejecutar los breadcrumbs
generate_breadcrumbs();
