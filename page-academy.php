<?php
/**
 * Template Name: Academia
 * Description: Plantilla para listar los cursos del Custom Post Type (CPT) "cursos" separados por la taxonomía "modalidad" (presencial y online).
 *
 * Requisitos:
 * - Debe existir un Custom Post Type (CPT) con slug 'cursos'.
 * - La taxonomía asociada "modalidad" debe contener los términos 'presencial' y 'online'.
 * - Se requiere el uso del template part 'template-parts/components/card-cpt' para renderizar cada curso.
 * - Los campos flexibles ACF 'flexible_content' se cargarán al final de la página si están definidos.
 *
 * Estructura de la página:
 * 1. Cabecera dinámica mediante un pageheader reutilizable.
 * 2. Contenido principal de la página.
 * 3. Dos secciones separadas: Formación Presencial y Formación Online.
 * 4. Bloques dinámicos cargados desde ACF.
 *
 * Personalización:
 * - La función render_cursos_by_modalidad() maneja la lógica para listar cursos según la taxonomía.
 */

$bodyclass  = 'page-cursos';
$cpt_slug   = 'cursos'; // Custom Post Type: Cursos
$taxonomy   = 'modalidad'; // Taxonomía: modalidad (presencial u online)
$terms      = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => true]); // Obtener términos de taxonomía
$fields     = get_fields();
$content    = get_the_content();

get_header(); ?>

<main class="main-content">
   <?php
   // Mostrar cabecera de la página con estilo "bg-image"
   get_template_part('template-parts/pageheader', null, ['pageheader_style' => 'bg-image']);
   ?>

   <!-- Sección de Contenido Principal -->
   <?php if (!empty($content)) : ?>
   <div class="bg-light-subtle py-5">
      <div class="container">
         <?php echo $content; ?>
      </div>
   </div>
   <?php endif; ?>

   <!-- Listado de Cursos por Taxonomía -->
   <section class="cpt-courses container my-5">
      <?php
      // Función para renderizar cursos por modalidad
      function render_cursos_by_modalidad($taxonomy, $term, $cpt_slug, $section_title, $class_section) {
         $query = new WP_Query([
            'post_type'      => $cpt_slug,
            'posts_per_page' => -1,
            'tax_query'      => [[
               'taxonomy' => $taxonomy,
               'field'    => 'slug',
               'terms'    => $term,
            ]],
         ]);

         if ($query->have_posts()) :
            ?>
            <div class="<?php echo esc_attr($class_section); ?>">
               <h2 class="section-title heading-3 text-uppercase mb-4">
                  <?php echo esc_html($section_title); ?>
               </h2>
               <div class="row">
                  <?php
                  while ($query->have_posts()) : $query->the_post();
                     // Plantilla para cada curso
                     get_template_part('template-parts/components/card-cpt', null, [
                        'title'     => get_the_title(),
                        'excerpt'   => wp_trim_words(get_the_excerpt(), 20),
                        'image_url' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                        'permalink' => get_permalink(),
                        'class-col' => 'col-md-6 col-lg-4 col-xl-3',
                     ]);
                  endwhile;
                  ?>
               </div>
            </div>
            <?php
            wp_reset_postdata();
         else :
            echo '<p class="text-center">No hay cursos disponibles en esta modalidad.</p>';
         endif;
      }

      if (!empty($terms) && !is_wp_error($terms)) :
         $total_terms = count($terms); // Número total de términos
         $current_index = 0;           // Inicializa contador

         foreach ($terms as $term) :
            $current_index++;
            
            // Determinar la clase de sección
            $section_class = 'cpt-section mb-5';
            if ($current_index !== $total_terms) {
               $section_class .= ' pb-5 border-bottom'; // Añadir clase excepto en el último término
            }

            // Renderizar cursos del término actual
            render_cursos_by_modalidad($taxonomy, $term->slug, $cpt_slug, 'Formación ' . ucfirst($term->name), $section_class);
         endforeach;
      else :
         echo '<p class="text-center">No hay cursos disponibles.</p>';
      endif;
      ?>
   </section>

   <?php // Bloques flexibles dinámicos
   if ($fields && isset($fields['flexible_content']) && is_array($fields['flexible_content'])) {
      foreach ($fields['flexible_content'] as $block) {
         if (isset($block['acf_fc_layout']) && !empty($block['acf_fc_layout'])) {
            $block_file = 'blocks/' . $block['acf_fc_layout'] . '.php';

            if (file_exists(get_stylesheet_directory() . '/' . $block_file)) {
               include locate_template($block_file);
            }
         }
      }
   }
   ?>
</main>

<?php get_footer(); ?>
