<?php
/**
 * Firma del autor en los pots del blog
 */

// Verificar si el autor tiene una descripción
if ( !empty($author_description) && !$disable_author_page ) : ?>
   <hr class="container">
   <div class="container author-description p-3 shadow-sm rounded c-bg-light d-flex flex-column flex-sm-row justify-content-between my-5">
      <!-- Foto del autor -->
      <div class="author-photo col-6 col-sm-4 col-md-3 col-lg-2">
         <img src="<?php echo esc_url($author_image['url']); ?>" class="fit">
      </div>

      <!-- Información del autor -->
      <div class="col-sm-8 col-md-9 col-lg-10 ps-sm-4 py-sm-3">
         <p class="heading-5 mb-2">Artículo redactado por <span class="c-primary"><?php echo $author_full_name; ?></span></p>
         <div class="author-info fs15">
            <?php echo wp_kses_post($author_description); ?>
         </div>

         <?php // Enlace a la página de autor
         // Obtener la URL de la página del autor
         $author_url = get_author_posts_url(get_the_author_meta('ID'));
         if (!empty($author_url)) : ?>
            <a href="<?php echo esc_url($author_url); ?>" class="btn btn-sm btn-secondary">
               Saber más
            </a>
         <?php endif; ?>
      </div>
   </div>
<?php endif; ?>