<?php
/**
 * Single cursos
 * Plantilla para landings de cursos online o presenciales
 */
$bodyclass = 'page-course';
get_header();

// Obtener todos los campos de ACF de la página actual
$fields  = get_fields();
$price_course = $fields['course_price'];
?>

<main id="primary" class="main-content">

   <?php // Encabezado de la página
   get_template_part('template-parts/pageheader', null, [
      'pageheader_style' => 'cols'
   ]);

   // Cargar bloques flexibles dinámicamente
   if (isset($fields['flexible_content']) && is_array($fields['flexible_content'])) {
      require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
      load_flexible_blocks($fields['flexible_content']);
   }
   ?>
   
</main>

<?php get_footer(); ?>
