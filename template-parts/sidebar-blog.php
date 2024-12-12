<?php
/**
 * Sidebar de la página del Blog
 * Incluye las categorías del blog
 *
 * @package CustomTheme
 */

$class_col  = $args['class_col'] ?? 'col-lg-3';
$id_form    = $args['id_form'] ?? 2;
?>
<aside class="<?php echo esc_attr($class_col); ?>">
   <div class="sidebar sticky-top mb-5">
      <?php get_template_part('template-parts/components/cta-sidebar', null, [ 'id_form' => $id_form ]); ?>
      <?php if ((is_single() && get_post_type() == 'post') || is_archive() || (is_page_template('page-blog.php'))) :
         $categories       = get_categories();
         $valid_categories = array();

         foreach ($categories as $category) {
            $category_name = $category->name;
            $valid_categories[] = '<li class="d-flex align-items-center li-cat ps-0 fs15"><a href="' . get_category_link($category->term_id) . '" class="link-cat text-decoration-none fw500 ps-3">' . $category_name . '</a></li>';
         }
         
         if (!empty($valid_categories)): ?>
            <div class="aside-cat p-3 mt-4 rounded border c-bg-light" data-aos="fade-up">
                  <p class="heading-4">Categorías</p>
                  <ul class="list-unstyled m-0 p-0">
                     <?php echo implode('', $valid_categories); ?>
                  </ul>
            </div>
         <?php endif;
      endif; ?>
   </div>
</aside>