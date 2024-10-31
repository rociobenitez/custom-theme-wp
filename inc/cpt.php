<?php

// Registrar todos los Custom Post Types
add_action( 'init', 'register_custom_post_types' );
function register_custom_post_types() {

   // CPT Blog
   register_post_type( 'post', array(
      'label'       => __('Blog', 'theme'),
      'description' => __('Publicaciones del blog', 'theme'),
      'labels'      => array(
         'menu_name'          => __('Blog', 'theme'),
         'add_new'            => __('Nuevo', 'theme'),
         'add_new_item'       => __('Agregar Nueva Entrada', 'theme'),
         'edit_item'          => __('Editar Entrada', 'theme'),
         'update_item'        => __('Actualizar Entrada', 'theme'),
         'search_items'       => __('Buscar Entradas', 'theme'),
         'view_item'          => __('Ver Entrada', 'theme'),
         'all_items'          => __('Ver Todos', 'theme'),
         'not_found'          => __('No encontrado', 'theme'),
         'not_found_in_trash' => __('No encontrado en la papelera', 'theme'),
         'parent_item_colon'  => __('Entrada Padre:', 'theme'),
      ),
      'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
      'public'             => true,
      'publicly_queryable' => true,
      '_builtin'           => true, // Esto define el tipo de publicación como integrado
      '_edit_link'         => 'post.php?post=%d', // URL de edición en admin
      'capability_type'    => 'post', // Derechos de usuario similares a publicaciones estándar
      'map_meta_cap'       => true,
      'query_var'          => false, // Define la variable de consulta en URLs
      'hierarchical'       => true, // Determina si es jerárquico (con hijos)
      'has_archive'        => false,
      'rewrite'            => array('slug' => 'blog', 'with_front' => false),
   ));

   /*
   // CPT Servicios (Descomentarlo para habilitarlo)
   // Este bloque de código permite registrar el CPT de Servicios. 
   // Descomenta y personaliza según sea necesario.
   register_post_type('servicios', array(
      'label'       => __('Servicios', 'theme'),
      'description' => __('Custom post type para servicios', 'theme'),
      'labels'      => array(
         'name'               => __('Servicios', 'theme'),
         'singular_name'      => __('Servicio', 'theme'),
         'menu_name'          => __('Servicios', 'theme'),
         'parent_item_colon'  => __('Servicio padre:', 'theme'),
         'all_items'          => __('Ver todos', 'theme'),
         'view_item'          => __('Ver Servicio', 'theme'),
         'add_new_item'       => __('Añadir Nuevo Servicio', 'theme'),
         'add_new'            => __('Nuevo Servicio', 'theme'),
         'edit_item'          => __('Editar Servicio', 'theme'),
         'update_item'        => __('Actualizar Servicio', 'theme'),
         'search_items'       => __('Buscar Servicio', 'theme'),
         'not_found'          => __('No se encontraron servicios', 'theme'),
         'not_found_in_trash' => __('No se encontraron servicios en la papelera', 'theme')
      ),
      'public'             => true,
      'has_archive'        => true,
      'publicly_queryable' => true,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'servicios', 'with_front' => false),
      'supports'           => array('title', 'editor', 'thumbnail', 'revisions', 'excerpt'),
      'menu_icon'          => 'dashicons-universal-access',
      'hierarchical'       => false,
   ));
   */

   /*
   // Taxonomía Tipo de Servicio para CPT Servicios (Descomentarlo para habilitarlo)
   // Descomenta este bloque para añadir la taxonomía "Tipo de Servicio" al CPT Servicios.

   register_taxonomy('tipo_servicio', 'servicios', array(
      'hierarchical'      => true,
      'labels'            => array(
         'name'              => __('Tipos de Servicio', 'theme'),
         'singular_name'     => __('Tipo de Servicio', 'theme'),
         'search_items'      => __('Buscar Tipo de Servicio', 'theme'),
         'all_items'         => __('Todos los Tipos de Servicio', 'theme'),
         'parent_item'       => __('Tipo de Servicio Padre', 'theme'),
         'parent_item_colon' => __('Tipo de Servicio Padre:', 'theme'),
         'edit_item'         => __('Editar Tipo de Servicio', 'theme'),
         'update_item'       => __('Actualizar Tipo de Servicio', 'theme'),
         'add_new_item'      => __('Añadir Nuevo Tipo de Servicio', 'theme'),
         'new_item_name'     => __('Nuevo Nombre de Tipo de Servicio', 'theme'),
         'menu_name'         => __('Tipos de Servicio', 'theme')
      ),
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array('slug' => 'tipo-servicio'),
   ));
   */
}

// Habilitar soporte para miniaturas en los tipos de publicación personalizados
add_theme_support('post-thumbnails', array('post', 'servicios'));

// Función para ajustar la paginación del blog
add_action( 'init', 'wpa_fix_blog_pagination' );
function wpa_fix_blog_pagination() {
   add_rewrite_rule(
      'blog/page/([0-9]+)/?$',
      'index.php?pagename=blog&paged=$matches[1]',
      'top'
   );
   add_rewrite_rule(
      'blog/([^/]*)$',
      'index.php?name=$matches[1]',
      'top'
   );
   add_rewrite_tag('%blog%', '([^/]*)');
}

// Añadir soporte para archivos en formularios de edición
add_action('post_edit_form_tag', 'post_edit_form_tag');
function post_edit_form_tag() {
    echo ' enctype="multipart/form-data"';
}

// Ajuste de URL para enlaces personalizados en el CPT Blog
add_filter( 'post_link', 'append_query_string', 10, 3 );
function append_query_string( $url, $post, $leavename ) {
    if ($post->post_type == 'post') {     
        $url = home_url(user_trailingslashit("blog/$post->post_name"));
    }
    return $url;
}
