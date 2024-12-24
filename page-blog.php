<?php
/**
 * Template Name: Blog
 */

$bodyclass='page-blog';

get_header();

$fields = get_fields();
$options = get_fields('option');
set_query_var('fields', $fields);
$contenido = get_the_content();
$default_img_post = $options['card_default_image'];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    "post_type"      => "post",
    "posts_per_page" => 10,
    "orderby"        => 'date',
    "order"          => 'DESC',
    "paged"          => $paged
);

$the_query = new WP_Query($args);

if ( $the_query->have_posts() ) : ?>
   <main class="main-content">
   <?php get_template_part('template-parts/pageheader', null, ['htag' => 0]); ?>

   <?php if(!empty($contenido)):?>
      <section class="section__content py-5 c-bg-light">
         <div class="container"><?php echo $contenido; ?></div>
      </section>
   <?php endif; ?>
   <section class="container my-5">
      <div class="row justify-content-between">
         <div class="col-lg-8">
               <?php while ( $the_query->have_posts() ) : $the_query->the_post();
                  $author = ucwords(strtolower(get_the_author()));
                  $excerpt = get_the_excerpt();
                  $custom_length = 25; // Número de palabras del excerpt
                  $trimmed_excerpt = wp_trim_words($excerpt, $custom_length, '...');

                  // Verifica si hay imagen destacada y asigna la URL o usa una por defecto
                  $img_src = has_post_thumbnail() 
                     ? wp_get_attachment_image_src(get_post_thumbnail_id(), 'large')[0]
                     : $default_img_post;

                  get_template_part('template-parts/components/card-post', null, [
                     'image_url' => $img_src,
                     'title'     => get_the_title(),
                     'author'    => $author,
                     'date'      => get_the_date(),
                     'excerpt'   => $trimmed_excerpt,
                     'permalink' => get_permalink(),
                     'class-col' => 'col-lg-4',
                  ]);

               endwhile; ?>
               <div class="pagination text-center">
                  <?php custom_pagination($the_query->max_num_pages, $paged); ?>
               </div>
         </div>
         <?php get_template_part('template-parts/sidebar-blog', null, [ 
            'id_form' => 2,
            'class_col' => 'col-lg-4 ps-lg-5 mt-5 mt-lg-0'
         ]); ?>      
      </div>
   </section>
   <?php
   else :
      echo '<div class="container my-5"><p>No hay entradas disponibles.</p></div>';
   endif;

   wp_reset_postdata(); // Restablecer la información global de postdata

   // Cargar bloques flexibles dinámicamente
   if(!empty($fields['flexible_content'])) {
      require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
      load_flexible_blocks($fields['flexible_content']);
   }
   ?>
   </main>
<?php get_footer(); ?>