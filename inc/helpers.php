<?php
/**
 * Funciones de ayuda y utilidades generales
 *
 * @package NombreTheme
 */

/**
 * Genera un encabezado HTML con clases y atributos personalizados.
 *
 * @param int $h Nivel del encabezado. Debe estar entre 0 y 3, donde 0 es h1 y 3 es p.
 * @param string $titulo Texto del encabezado.
 * @param string $class Clase CSS principal para el encabezado.
 * @param string $class2 Clase CSS secundaria, opcional.
 * @return string Código HTML del encabezado.
 */
function tagTitle($h, $titulo, $class, $class2 = '') {
    $tags = array('h1', 'h2', 'h3', 'p');
    $h = is_numeric($h) && $h >= 0 && $h <= 3 ? (int) $h : 2;
    return sprintf('<%1$s class="%3$s %4$s">%2$s</%1$s>', $tags[$h], esc_html($titulo), esc_attr($class), esc_attr($class2));
}

/**
 * Genera un encabezado HTML para FAQs con id, clases y contenido personalizados.
 *
 * @param int $h Nivel del encabezado, limitado de 1 a 3.
 * @param string $titulo Texto del encabezado.
 * @param string $class Clase CSS para el encabezado.
 * @param string $id ID opcional para el encabezado.
 * @param string $class2 Clase CSS secundaria, opcional.
 * @param string $content Contenido adicional para incluir dentro del encabezado.
 * @return string Código HTML del encabezado.
 */
function tagTitleFaq($h, $titulo, $class, $id = '', $class2 = '', $content = '') {
    $tags = array('h1', 'h2', 'h3', 'p');
    $h = is_numeric($h) && $h >= 0 && $h <= 3 ? (int) $h : 2;
    $titulo_escapado = esc_html($titulo);
    $idAttribute = $id ? ' id="' . esc_attr($id) . '"' : '';
    return sprintf('<%1$s%2$s class="%4$s %5$s">%3$s %6$s</%1$s>', 
        $tags[$h],
        $idAttribute,
        $titulo_escapado,
        esc_attr($class),
        esc_attr($class2),
        $content ? ' ' . esc_html($content) : ''
    );
}

// Función personalizada de paginación
function custom_pagination($total_pages = null, $current_page = null) {
   // Si no se define el total de páginas, intenta obtenerlo de la consulta global
   if (!$total_pages) {
      global $wp_query;
      $total_pages = $wp_query->max_num_pages;
   }

   // Si no hay múltiples páginas, no se muestra la paginación
   if ($total_pages <= 1) {
      return;
   }

   // Determina la página actual si no se pasa como argumento
   if (!$current_page) {
      $current_page = max(1, get_query_var('paged'));
   }

   // Genera la URL base de la primera página y elimina posibles argumentos adicionales
   $base = strtok(get_pagenum_link(1), '?');

   // Argumentos para la función de paginación
   $args = array(
      'base'      => $base . '%_%',
      'format'    => 'page/%#%/', 
      'current'   => $current_page,
      'total'     => $total_pages,
      'prev_text' => __('« Anterior'),
      'next_text' => __('Siguiente »'),
      'end_size'  => 1,
      'mid_size'  => 2,
   );

   // Mostrar la paginación
   echo paginate_links($args);
}
