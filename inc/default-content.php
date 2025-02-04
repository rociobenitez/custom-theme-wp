<?php
/**
 * Creación de entradas de prueba al activar el tema
 */
function create_sample_blog_posts() {
   if (get_option('sample_posts_created')) {
      return;
   }

   // Cargar los archivos necesarios para media_sideload_image
   require_once ABSPATH . 'wp-admin/includes/media.php';
   require_once ABSPATH . 'wp-admin/includes/file.php';
   require_once ABSPATH . 'wp-admin/includes/image.php';

   $default_image_path = get_template_directory() . '/assets/img/default-blog-image.webp';
   $default_image_uri = get_template_directory_uri() . '/assets/img/default-blog-image.webp';

   // Array de títulos de publicaciones de prueba
   $sample_posts = [
      'Entrada de prueba 1',
      'Entrada de prueba 2',
      'Entrada de prueba 3',
   ];

   foreach ($sample_posts as $title) {
      // Verificar si ya existe una entrada con este título usando WP_Query
      $post_query = new WP_Query([
         'post_type'      => 'post',
         'title'          => $title,
         'posts_per_page' => 1,
      ]);

      if (!$post_query->have_posts()) {
         // Crear la entrada
         $post_id = wp_insert_post([
               'post_title'   => $title,
               'post_content' => 'Este es el contenido de ejemplo para ' . $title,
               'post_status'  => 'publish',
               'post_type'    => 'post',
         ]);

         // Si la entrada se creó con éxito y la imagen predeterminada existe, asignarla
         if ($post_id && file_exists($default_image_path)) {
            // Cargar la imagen en la biblioteca de medios
            $image_id = media_sideload_image($default_image_uri, $post_id, null, 'id');
            
            // Asignar la imagen destacada
            if (is_wp_error($image_id)) {
               error_log('Error al descargar la imagen: ' . $image_id->get_error_message());
            } else {
               if (!set_post_thumbnail($post_id, $image_id)) {
                     error_log('Error al asignar la imagen destacada para el post ID: ' . $post_id);
               }
            }

            $attachment = get_post($image_id);
            if ($attachment->post_parent !== $post_id) {
               error_log("La imagen $image_id no está correctamente asignada al post $post_id");
            }
         }
      }

      // Restablecer la consulta para la siguiente iteración
      wp_reset_postdata();
   }

   // Establecer la opción para evitar futuras ejecuciones
   add_option('sample_posts_created', true);
}

// Ejecutar la función al activar el tema
add_action('after_switch_theme', 'create_sample_blog_posts');
