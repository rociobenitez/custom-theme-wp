<?php
/**
 * Page Header Component
 *
 * Muestra una cabecera de página con tres estilos diferentes:
 * - Estilo "cols" (2 columnas: texto + imagen).
 * - Estilo "bg_image" (imagen de fondo con texto superpuesto).
 * - Estilo "bg_color" (sin imagen, solo color de fondo).
 */

// --- Configuración por defecto ---
$fields           = get_fields();
$page_title       = get_the_title();
$default_img      = get_template_directory_uri() . '/assets/img/default-background.jpg';
$pageheader_style = $args['pageheader_style'] ?? 'bg_image'; // Estilo predeterminado
$text_align       = $args['text_align'] ?? 'text-start'; // Estilo predeterminado

// --- Variables por defecto ---
$tagline      = '';
$htag_tagline = 3;
$title        = $page_title;
$htag_title   = 3;
$description  = '';
$button       = null;
$bg_image     = $default_img;

// --- Verificar si estamos en distintas secciones ---
if (is_singular('cursos')) {
   // Datos desde el propio CPT
   $tagline       = $fields['pageheader_tagline'] ?? 'Detalles del Curso';
   $title         = $fields['pageheader_title'] ?? $page_title;
   $htag_title    = $fields['pageheader_htag_tagline'] ?? 2;
   $description   = $fields['pageheader_text'] ?? get_the_excerpt(); // El extracto como descripción
   $bg_image      = !empty($fields['pageheader_image'])
                     ? esc_url($fields['pageheader_image']['url'])
                     : (has_post_thumbnail()
                        ? esc_url(get_the_post_thumbnail_url(null, 'full'))
                        : $default_img); // Imagen por defecto si no hay ninguna configurada
   $button        = $fields['pageheader_button'] ?? null;
   $button_text   = $button ? $button['title'] : 'Ver todos los Cursos';
   $button_url    = $button ? $button['url'] : get_post_type_archive_link('cursos'); // Enlace a la página de archivo de cursos
   $course_price  = $fields['course_price'] ?? '';

} elseif (is_singular('trabajos')) {
   // Datos desde el propio CPT
   $tagline       = $fields['pageheader_tagline'] ?? 'Detalles del Trabajo';
   $title         = $fields['pageheader_title'] ?? $page_title;
   $htag_title    = $fields['pageheader_htag_tagline'] ?? 2;
   $description   = $fields['pageheader_text'] ?? get_the_excerpt(); // El extracto como descripción
   $bg_image      = !empty($fields['pageheader_image'])
                     ? esc_url($fields['pageheader_image']['url'])
                     : (has_post_thumbnail()
                        ? esc_url(get_the_post_thumbnail_url(null, 'full'))
                        : $default_img); // Imagen por defecto si no hay ninguna configurada
   $button        = $fields['pageheader_button'] ?? null;
   $button_text   = $button ? $button['title'] : 'Ver todos los Trabajos';
   $button_url    = $button ? $button['url'] : get_post_type_archive_link('trabajos');
   $course_price  = $fields['course_price'] ?? '';
   
} elseif (function_exists('is_shop') && is_shop()) {
   $shop_page_id = function_exists('wc_get_page_id') ? wc_get_page_id('shop') : null; // ID de la página asignada como tienda
   // Título de la tienda
   $title = !empty($fields['pageheader_title']) 
      ? $fields['pageheader_title']
      : (function_exists('woocommerce_page_title') 
            ? woocommerce_page_title(false) 
            : get_the_title($shop_page_id)); 
   $htag_title = 2;

   // Obtener la imagen destacada de la página de la tienda
   $bg_image = !empty($fields['pageheader_image'])
      ? esc_url($fields['pageheader_image']['url'])
      : ($shop_page_id && has_post_thumbnail($shop_page_id)
         ? esc_url(get_the_post_thumbnail_url($shop_page_id, 'full'))
         : $default_img); // Imagen por defecto si no hay ninguna configurada

} elseif (function_exists('is_product_category') && is_product_category()) {
   $current_term = get_queried_object(); // Obtener el objeto de la categoría actual
   $parent_term  = get_term($current_term->parent, 'product_cat'); // Obtener la categoría padre si existe
   
   // Construir el título con la categoría padre + subcategoría
   if ($parent_term && !is_wp_error($parent_term)) {
      $title = esc_html($parent_term->name) . ' / ' . esc_html($current_term->name);
   } else {
      $title = esc_html($current_term->name); // Sin padre, solo el nombre actual
   }
   $htag_title   = 2;
   $description  = esc_html($current_term->description);
   $thumbnail_id = function_exists('get_term_meta') 
      ? get_term_meta($current_term->term_id, 'thumbnail_id', true)
      : null;

   $bg_image = $thumbnail_id
      ? wp_get_attachment_url($thumbnail_id)
      : $default_img;

} elseif (is_category()) {
   $current_category = get_queried_object(); // Categoría actual
   $title = esc_html($current_category->name); // Nombre de la categoría como título
   $description = esc_html($current_category->description); // Descripción de la categoría, si está configurada
   $htag_title = 2; 
   $bg_image = $default_img; 
   
} else {
   // --- Variables ACF ---
   $tagline       = $fields['pageheader_tagline'] ?? '';
   $htag_tagline  = $fields['pageheader_htag_tagline'] ?? 3;
   $title         = !empty($args['title'])
                     ? $args['title']
                     : ( !empty($fields['pageheader_title'])
                        ? $fields['pageheader_title']
                        : $page_title );
   $htag_title    = $fields['pageheader_htag_title'] ?? 3;
   $description   = $fields['pageheader_text'] ?? '';
   $button        = $fields['pageheader_button'] ?? null;
   $bg_image = (!empty($fields['pageheader_image']) && isset($fields['pageheader_image']['url']))
                  ? esc_url($fields['pageheader_image']['url'])
                  : (has_post_thumbnail() 
                     ? esc_url(get_the_post_thumbnail_url(null, 'full')) 
                     : $default_img);
}

// --- Configuración del botón ---
if (!empty($button)) {
   $contact_page_id = get_fields('page_contacto', 'option'); 
   $contact_page_url = $contact_page_id ? get_permalink($contact_page_id) : '/contacto/';
   
   $button_text = $button['title'] ?? 'Contacto';
   $button_url  = $button['url'] ?? $contact_page_url;
}

// --- Configuración específica para el estilo "cols" ---
if ($pageheader_style === 'bg_image') {
   // Configuración específica para el estilo "bg_image"
   $class_tagline = 'tagline';
   $class_title   = 'page-title fs56 fw300 mb-0 c-white text-uppercase';
   $bg_style      = $bg_image 
       ? "background: linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url('$bg_image');" 
       : "background: linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%);";
} else {
   $bg_color      = $fields['pageheader_bg_color'] ?? '#f8f9fa';
   $class_tagline = 'tagline';
   $class_title   = 'page-title fs40 fw400 mb-2 c-black text-uppercase lh120';
}

// --- Renderizado de la cabecera ---
if ($fields['show_pageheader']) :
   if ($pageheader_style === 'cols') : ?>
      <?php // Page Header Estilo "cols" (Texto + Imagen a 2 columnas) ?>
      <section class="header-split position-relative" style="background-color: <?= esc_attr($bg_color); ?>;">
         <?php // Columna de Contenido ?>
         <div class="content-wrapper d-flex align-items-center py-5">
            <div class="container">
               <div class="content col-12 col-lg-5 py-4 <?= esc_attr($text_align); ?>">
                  <?php if ($tagline) : ?>
                     <?= tagTitle($htag_tagline, $tagline, $class_tagline, ''); ?>
                  <?php endif; ?>
                  <?= tagTitle($htag_title, $title, $class_title, ''); ?>
                  <?php if ($description) : ?>
                     <div class="fs18 mb-4">
                        <?= $description; ?>
                     </div>
                  <?php endif; ?>
                  <?php if ($button) : ?>
                     <a href="<?= esc_url($button_url); ?>" class="btn btn-lg btn-primary">
                        <?= esc_html($button_text); ?>
                     </a>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <?php // Columna de Imagen ?>
         <div class="image-wrapper col-12 col-lg-6 position-absolute top-0 end-0 h-100 cover" 
            style="background-image: url('<?= esc_url($bg_image); ?>');">
         </div>
      </section>
   <?php elseif($pageheader_style === 'bg_color'): ?>
      <section class="page-header py-5 <?= esc_attr($text_align); ?>" style="<?= esc_attr($bg_style); ?>">
         <div class="container">
            <?= tagTitle($htag_title, $title, $class_title, ''); ?>
            <?php get_template_part('template-parts/components/breadcrumbs'); ?>   
         </div>
      </section>
   <?php else: ?>
      <?php // Page Header Estilo "bg_image" (Imagen de fondo con texto superpuesto)  ?>
      <section class="page-header py-5 cover <?= esc_attr($text_align); ?>" style="<?= esc_attr($bg_style); ?>">
         <div class="container">
            <?= tagTitle($htag_title, $title, $class_title, ''); ?>
            <?php get_template_part('template-parts/components/breadcrumbs'); ?>   
         </div>
      </section>
   <?php endif; ?>
<?php endif; ?>
