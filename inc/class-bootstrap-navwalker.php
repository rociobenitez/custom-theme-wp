<?php
/**
 * Bootstrap NavWalker
 *
 * Maneja los menús de WordPress para integrarse con Bootstrap 5.
 * Funciona correctamente con submenús de cualquier nivel de anidación.
 *
 * @package CustomTheme
 */

 class Bootstrap_NavWalker extends Walker_Nav_Menu {
 
     /**
      * Start Level.
      *
      * Añade las clases necesarias para el submenú de Bootstrap.
      *
      * @param string $output Salida HTML generada.
      * @param int $depth Nivel de profundidad del menú.
      * @param array $args Argumentos del menú.
      */
     public function start_lvl( &$output, $depth = 0, $args = null ) {
         $indent = str_repeat("\t", $depth);
         $submenu_class = $depth > 0 ? ' dropdown-submenu' : ' dropdown-menu';
         $output .= "\n$indent<ul class=\"$submenu_class\">\n";
     }
 
     /**
      * Start Element.
      *
      * Configura los elementos principales y añade las clases de Bootstrap necesarias para menús desplegables.
      *
      * @param string $output Salida HTML generada.
      * @param WP_Post $item Elemento actual del menú.
      * @param int $depth Nivel de profundidad del menú.
      * @param array $args Argumentos del menú.
      * @param int $id ID del elemento.
      */
     public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
         $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
         $classes = empty( $item->classes ) ? array() : (array) $item->classes;
 
         // Añadir clases específicas de Bootstrap
         $classes[] = 'nav-item';
         if ($args->walker->has_children) {
             $classes[] = ($depth === 0) ? 'dropdown' : 'dropdown-submenu';
         }
 
         $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
         $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
         $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
         $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
         $output .= $indent . '<li' . $id . $class_names .'>';
 
         // Configurar atributos para el enlace
         $atts = array();
         $atts['title']  = ! empty( $item->title ) ? $item->title : '';
         $atts['target'] = ! empty( $item->target ) ? $item->target : '';
         $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
 
         if ($args->walker->has_children && $depth === 0) {
             $atts['href'] = '#';
             $atts['class'] = 'nav-link dropdown-toggle';
             $atts['data-bs-toggle'] = 'dropdown';
             $atts['aria-expanded'] = 'false';
         } elseif ($args->walker->has_children && $depth > 0) {
             $atts['href'] = ! empty( $item->url ) ? $item->url : '';
             $atts['class'] = 'dropdown-item dropdown-toggle d-flex align-items-center justify-content-between';
             $atts['data-bs-toggle'] = 'dropdown';
             $atts['aria-expanded'] = 'false';
         } else {
             $atts['href'] = ! empty( $item->url ) ? $item->url : '';
             $atts['class'] = ($depth === 0) ? 'nav-link' : 'dropdown-item';
         }
 
         $attributes = '';
         foreach ( $atts as $attr => $value ) {
             if ( ! empty( $value ) ) {
                 $attributes .= ' ' . $attr . '="' . $value . '"';
             }
         }
         
         $item_output = $args->before;
         $item_output .= '<a'. $attributes .'>';
         $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
         $item_output .= '</a>';
         $item_output .= $args->after;
         $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
     }
 }
 