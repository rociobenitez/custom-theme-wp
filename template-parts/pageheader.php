<?php
/**
 * Page Header Component
 *
 * Muestra una cabecera de página con dos estilos diferentes:
 * - Estilo "cols" (2 columnas: texto + imagen).
 * - Estilo "bg_image" (imagen de fondo con texto superpuesto).
 */

// --- Configuración por defecto ---
$page_title       = get_the_title();
$default_img      = get_template_directory_uri() . '/assets/img/default-background.jpg';
$pageheader_style = $pageheader_style ?? 'bg_image'; // Estilo predeterminado

// --- Variables ACF ---
$fields        = get_fields();
$tagline       = $fields['pageheader_tagline'] ?? '';
$htag_tagline  = $fields['pageheader_htag_tagline'] ?? 3;
$title         = $fields['pageheader_title'] ?? $page_title;
$htag_title    = $fields['pageheader_htag_title'] ?? 3;
$description   = $fields['pageheader_text'] ?? '';
$button        = $fields['pageheader_button'] ?? null;

// --- Imagen de fondo (ACF, destacada o por defecto) ---
$background_image = $fields['pageheader_image']
   ? esc_url($fields['pageheader_image']['url'])
   : (has_post_thumbnail() 
      ? esc_url(get_the_post_thumbnail_url(null, 'full')) 
      : $default_img);

// --- Configuración específica para el estilo "cols" ---
if ($fields['pageheader_style'] === 'cols') {
   $bg_color      = $fields['pageheader_bg_color'] ?? '#f8f9fa';
   $class_tagline = 'tagline';
   $class_title   = 'page-title fs40 fw400 mb-2 c-black text-uppercase lh120';
} else {
   // Configuración específica para el estilo "bg_image"
   $class_tagline = 'tagline';
   $class_title = 'page-title fs44 fw600 mb-0 c-white';
   $background_style = $background_image 
      ? "background: linear-gradient(0deg, rgba(10, 25, 47, 0.5), rgba(10, 25, 47, 0.6)),
         linear-gradient(260deg, rgba(16, 42, 67, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%), url('$background_image');" 
      : "background: linear-gradient(135deg,#3a739d,#456787 50%,#27587d);";
}

// --- Configuración del botón ---
if (!empty($button)) {
   $button_text = $button['title'] ?? 'Contacto';
   $button_url  = $button['url'] ?? '/contacto/';
}

// --- Renderizado de la cabecera ---
if ($title) :
   if ($fields['pageheader_style'] === 'cols' || $pageheader_style === 'cols') : ?>
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
            style="background-image: url('<?php echo esc_url($background_image); ?>');">
         </div>
      </section>

   <?php else: ?>
      <?php // Page Header Estilo "bg_image" (Imagen de fondo con texto superpuesto)  ?>
      <section class="page-header py-5 cover" style="<?php echo esc_attr($background_style); ?>">
         <div class="container">
            <?php echo tagTitle($htag_title, $title, $class_title, ''); ?>
            <?php get_template_part('template-parts/components/breadcrumbs'); ?>   
         </div>
      </section>
   <?php endif; ?>
<?php endif; ?>
