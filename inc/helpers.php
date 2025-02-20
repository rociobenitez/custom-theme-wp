<?php
/**
 * Funciones de ayuda y utilidades generales
 *
 * @package custom_theme
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

/**
 * Genera un paginador HTML con clases y atributos personalizados.
 */
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

/**
 * Obtiene las opciones de contacto desde ACF.
 * @return array Un array con las opciones de contacto.
 */
function get_contact_options() {
   $options = get_fields('option');
   return [
      'phone' => $options['phone'] ?? '',
      'whatsapp' => $options['whatsapp'] ?? '',
      'email' => $options['email'] ?? '',
   ];
}

/**
 * Genera un enlace de contacto con icono y texto.
 * @param string $type Tipo de enlace ('phone', 'whatsapp', 'email').
 * @param string $value Valor del enlace.
 */
function generate_contact_link($type, $value) {
   if (!empty($value)) {
      $href = '';
      $icon_src = '';
      $alt = '';
      $class = '';
      switch ($type) {
         case 'phone':
            $href = 'tel:' . esc_attr(preg_replace('/\D/', '', $value));
            $icon_src = get_template_directory_uri() . '/assets/img/icons/phone.svg';
            $alt = 'Icono Teléfono';
            $class = 'topbar-link phone text-decoration-none';
            break;
         case 'whatsapp':
            $href = 'https://wa.me/' . esc_attr(preg_replace('/\D/', '', $value));
            $icon_src = get_template_directory_uri(). '/assets/img/icons/whatsapp.svg';
            $alt = 'Icono WhatsApp';
            $class = 'topbar-link whatsapp text-decoration-none';
            break;
         case 'email':
            $email = filter_var($value, FILTER_SANITIZE_EMAIL); // Elimina caracteres peligrosos
            $href = 'mailto:'. esc_attr($value);
            $icon_src = get_template_directory_uri(). '/assets/img/icons/mail.svg';
            $alt = 'Icono Correo electrónico';
            $class = 'topbar-link email text-decoration-none';
            break;
      }

      echo '<a href="'. esc_url($href) .'" class="'. esc_attr($class) .'">
         <div class="topbar-link-icon">
            <img src="'. esc_url($icon_src).'" alt="'. esc_attr($alt).'" class="fs20 me-2">
         </div>
         <div class="topbar-link-text">'. esc_html($value).'</div>
      </a>';
   }
}

/**
 * Genera una lista de redes sociales con iconos.
 */
function sociales(){
   $redes_sociales = array(
       'facebook'  => 'fa-facebook-f',
       'twitter'   => 'fa-x-twitter',
       'instagram' => 'fa-instagram',
       'linkedin'  => 'fa-linkedin-in',
       'youtube'   => 'fa-youtube',
   );
   $lista = '';
   $lista .= '<div class="d-flex">';
   $links = get_field('social_links', 'options');
   foreach($links as $social) :
       if (!empty($social['social_url'])) {
           $lista .= sprintf(
               '<a href="%s" class="m-2 text-black text-decoration-none d-flex" target="_blank" title="%s"><i class="but-round round-black p-2 fa-brands %s fs24"></i></a>',
               $social['social_url'],
               ucfirst($social['social_network']),
               esc_attr($redes_sociales[$social['social_network']])
           );
       }
   endforeach;
   $lista .= '</div>';
   return $lista;
}

/**
 * Genera el texto de derechos de autor en el pie de página.
 */
function footer_copy() {
   $year = date("Y");
   $site_name = get_bloginfo('name'); // Obtener el nombre del sitio
   $footer_text = sprintf(
       '&copy; %s %s, S.L. Todos los derechos reservados | Desarrollado por <a href="%s" target="_blank">Rocío Benítez</a>',
       esc_html($year),
       esc_html($site_name), // Escapar el nombre del sitio
       esc_url('https://github.com/rociobenitez')
   );
   echo $footer_text;
}