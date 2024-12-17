<?php
/**
 * Page Header Component
 *
 * Muestra una cabecera de página con dos estilos diferentes:
 * - Estilo "cols" (2 columnas: texto + imagen).
 * - Estilo "bg_image" (imagen de fondo con texto superpuesto).
 */

// --- Configuración por defecto ---
$fields           = get_fields();
$page_title       = get_the_title();
$default_img      = get_template_directory_uri() . '/img/img-default.png';
$pageheader_style = $args['pageheader_style'] ?? 'bg_image'; // Estilo predeterminado

// --- Variables por defecto ---
$tagline      = '';
$htag_tagline = 3;
$title        = $page_title;
$htag_title   = 3;
$description  = '';
$button       = null;
$bg_image     = $default_img;

// --- Verificar si estamos en el CPT "cursos" ---
if (is_singular('cursos')) {
   // Datos desde el propio CPT
   $tagline       = 'Detalles del Curso'; // Puedes personalizar esta línea
   $title         = $page_title;
   $htag_title    = 2;
   $description   = get_the_excerpt(); // El extracto como descripción
   $bg_image      = has_post_thumbnail() ? esc_url(get_the_post_thumbnail_url(null, 'full')) : $default_img;
   $button_text   = 'Ver Cursos';
   $button_url    = get_post_type_archive_link('cursos'); // Enlace a la página de archivo de cursos
} elseif (is_shop()) {
   $shop_page_id = wc_get_page_id('shop'); // ID de la página asignada como tienda
   $title        = !empty($fields['pageheader_title'])
      ? $fields['pageheader_title']
      : woocommerce_page_title(false); // Obtiene el título de la tienda
   $htag_title = 2;

   // Obtener la imagen destacada de la página de la tienda
   $bg_image = !empty($fields['pageheader_image'])
      ? esc_url($fields['pageheader_image']['url'])
      : (has_post_thumbnail($shop_page_id) // Verificar la imagen destacada de la página de la tienda
         ? esc_url(get_the_post_thumbnail_url($shop_page_id, 'full'))
         : $default_img); // Imagen por defecto si no hay ninguna configurada
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
   $button_text = $button['title'] ?? 'Contacto';
   $button_url  = $button['url'] ?? '/contacto/';
}

// --- Configuración específica para el estilo "cols" ---
if ($pageheader_style === 'cols') {
   $bg_color      = $fields['pageheader_bg_color'] ?? '#f8f9fa';
   $class_tagline = 'tagline';
   $class_title   = 'page-title fs40 fw400 mb-2 c-black text-uppercase lh120';
} else {
   // Configuración específica para el estilo "bg_image"
   $class_tagline = 'tagline';
   $class_title   = 'page-title fs56 fw300 mb-0 c-white text-uppercase';
   $bg_style      = $bg_image 
       ? "background: linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%), url('$bg_image');" 
       : "background: linear-gradient(0deg, rgba(0, 0, 0, 0.30) 0%, rgba(0, 0, 0, 0.30) 100%);";
}

// --- Renderizado de la cabecera ---
if ($title) :
   if ($pageheader_style === 'cols') : ?>
      <?php // Page Header Estilo "cols" (Texto + Imagen a 2 columnas) ?>
      <section class="header-split position-relative" style="background-color: <?php echo esc_attr($bg_color); ?>;">
         <?php // Columna de Contenido ?>
         <div class="content-wrapper d-flex align-items-center py-5">
            <div class="container">
               <div class="content col-12 col-lg-5 p-4">
                  <?php if ($tagline) : ?>
                     <?php echo tagTitle($htag_tagline, $tagline, $class_tagline, ''); ?>
                  <?php endif; ?>
                  <?php echo tagTitle($htag_title, $title, $class_title, ''); ?>
                  <?php if ($description) : ?>
                     <div class="fs18 mb-4">
                        <?php echo $description; ?>
                     </div>
                  <?php endif; ?>
                  <?php if ($button) : ?>
                     <a href="<?php echo esc_url($button_url); ?>" class="btn btn-lg btn-primary">
                        <?php echo esc_html($button_text); ?>
                     </a>
                  <?php endif; ?>
               </div>
            </div>
         </div>
         <?php // Columna de Imagen ?>
         <div class="image-wrapper col-12 col-lg-6 position-absolute top-0 end-0 h-100 cover" 
            style="background-image: url('<?php echo esc_url($bg_image); ?>');">
         </div>
      </section>

   <?php else: ?>
      <?php // Page Header Estilo "bg_image" (Imagen de fondo con texto superpuesto)  ?>
      <section class="page-header py-5 cover text-center" style="<?php echo esc_attr($bg_style); ?>">
         <div class="container">
            <?php echo tagTitle($htag_title, $title, $class_title, ''); ?>
            <?php get_template_part('template-parts/components/breadcrumbs'); ?>   
         </div>
      </section>
   <?php endif; ?>
<?php endif; ?>
