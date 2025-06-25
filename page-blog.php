<?php
/**
 * Template Name: Blog
 */
namespace Custom_Theme;

if ( ! defined('ABSPATH') ) exit;

get_header();

// Obtener campos ACF para bloques flexibles
$fields = function_exists('get_fields') ? get_fields() : [];

// Imagen por defecto si el post no tiene destacada
$default_img_post = get_template_directory_uri() . '/assets/img/default-background.jpg';

// Page Header
get_template_part( 'template-parts/pageheader' );
?>

<?php if ( apply_filters( 'the_content', get_the_content() ) ) : ?>
<section class="the-content py-5 bg-light">
    <div class="container">
        <?php the_content(); ?>
    </div>
</section>
<?php endif; ?>

<div class="container my-5">
   <div class="row justify-content-between">
      <div class="col-lg-8">
         <?php 
         // Preparar consulta de entradas
         $paged = get_query_var( 'paged', 1 );
         $args = array(
            "post_type"      => "post",
            "posts_per_page" => get_option( 'posts_per_page', 10 ),
            "paged"          => $paged
         );
         $query = new \WP_Query($args);

         if ( $query->have_posts() ) :
            echo '<div class="row">';
            while ( $query->have_posts() ) : $query->the_post();
               // Imagen destacada o imagen por defecto
               $image_url = has_post_thumbnail() 
                  ? get_the_post_thumbnail_url( null, 'large' )
                  : $default_img_post;

               get_template_part( 'template-parts/components/card', 'post', [
                  'image_url'  => esc_url( $image_url ),
                  'title'      => get_the_title(),
                  'author'     => ucwords( strtolower( get_the_author() ) ),
                  'date'       => get_the_date(),
                  'excerpt'    => wp_trim_words( get_the_excerpt(), 20, '...' ),
                  'permalink'  => get_permalink(),
                  'style_card' => 'blog',
               ] );
            endwhile;
            echo '</div>';

            // Paginación
            get_template_part( 'template-parts/components/pagination', null, [
               'max_num_pages' => $query->max_num_pages,
               'current_page'  => max(1, $paged),
            ]);
         else : ?>
            <article class="p-4 bg-light rounded">
               <p><?php esc_html_e( 'No hay entradas disponibles.', CTM_TEXTDOMAIN ); ?></p>
            </article>
         <?php endif;

         wp_reset_postdata();
         ?>
      </div>

      <?php get_template_part( 'template-parts/sidebar-blog', null, [ 'id_form' => 2 ] ); ?>  

   </div>
</div>

<?php
// Bloques flexibles ACF
if ( ! empty( $fields['flexible_content'] )
&& is_array( $fields['flexible_content'] ) ) {
   BlockLoader::load( $fields['flexible_content'] );
}

get_footer();
