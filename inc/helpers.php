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

/**
 * Obtiene un componente de la plantilla y lo muestra.
 * @param string $template_path Ruta relativa al archivo del componente.
 * @param array $args Argumentos para el componente.
 */
function get_component($template_path, $args = []) {
   if (!empty($args) && is_array($args)) {
      extract($args); // Extrae las claves del array como variables
   }
   include locate_template($template_path . '.php', false, false);
}

/**
 * Muestra los iconos de redes sociales con sus enlaces correspondientes basados en los datos del campo repetidor de ACF.
 * Los datos de redes sociales provienen de un campo repetidor ACF en la página de opciones.
 * Cada elemento del repetidor contiene un campo de selección para la red social y un campo para la URL.
 * 
 * @return void
 */
function social_media() {
   // Obtener el campo repetidor de redes sociales desde las opciones de ACF
   $social_networks = get_field('social_links', 'option');

   // Verificar si hay redes sociales configuradas
   if (!$social_networks || !is_array($social_networks)) {
       return;
   }

   // Definir la correspondencia de iconos de redes sociales
   $social_icons = [
       'facebook'  => 'facebook.svg',
       'instagram' => 'instagram.svg',
       'linkedin'  => 'linkedin.svg',
       'youtube'   => 'youtube.svg',
       'x'         => 'x.svg',
       'tiktok'    => 'tiktok.svg',
       'pinterest' => 'pinterest.svg',
       'twitch'    => 'twitch.svg',
       'dribbble'  => 'dribbble.svg',
       'behance'   => 'behance.svg',
   ];

   // Iniciar el contenedor de redes sociales
   echo '<div class="social-media-container">';

   // Recorrer cada red social
   foreach ($social_networks as $network) {
       // Omitir si faltan campos requeridos
       if (empty($network['social_network']) || empty($network['social_url'])) {
           continue;
       }

       $network_type = strtolower($network['social_network']);
       $network_url = esc_url($network['social_url']);

       // Omitir si el tipo de red no está en nuestra lista soportada
       if (!isset($social_icons[$network_type])) {
           continue;
       }

       // Obtener la ruta del icono
       $icon_path = get_template_directory_uri() . '/assets/img/icons/boxicons/' . $social_icons[$network_type];

       // Generar el enlace de la red social con su icono
       printf(
           '<a href="%1$s" class="social-media-link %2$s" target="_blank" rel="noopener noreferrer">
               <img src="%3$s" alt="%4$s" class="social-media-icon">
           </a>',
           $network_url,
           esc_attr($network_type),
           esc_url($icon_path),
           sprintf(esc_attr__('Icono de %s', 'custom-theme'), ucfirst($network_type))
       );
   }

   echo '</div>';
}