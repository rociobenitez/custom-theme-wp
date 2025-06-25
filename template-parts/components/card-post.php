<?php
/**
 * Componente: Card Post
 *
 * Variables esperadas:
 * - image_url (string): URL de la imagen destacada del post.
 * - title (string): Título del post.
 * - author (string): Nombre del autor del post.
 * - date (string): Fecha del post.
 * - excerpt (string): Resumen del post.
 * - permalink (string): URL del post.
 * - class_col (string): Clase CSS para la columna (por defecto: 'col-lg-4').
 * - style_card (string): Estilo de la card, puede ser 'border-none' o 'default' (por defecto: 'border-none').
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! $args['title'] && ! $args['image_url'] ) {
    return;
}

// Configurar los valores predeterminados usando el operador de fusión nula
$image_url  = $args['image_url'] ?? '';
$title      = $args['title'] ?? '';
$author     = $args['author'] ?? '';
$date       = $args['date'] ?? '';
$excerpt    = $args['excerpt'] ?? '';
$permalink  = $args['permalink'] ?? '';
$class_col  = $args['class_col'] ?? 'col-lg-4';
$style_card = $args['style_card'] ?? 'grid';

if ($style_card === 'default') {
   // Formato tipo card (con border y padding en el contenido)
   $card_class = 'border border-light-subtle shadow-sm' . ' ' . $style_card;
   $card_img_top = 'rounded-top';
   $card_body_class = '';
} else {
   // Formato tipo card (sin border y sin padding en el contenido)
   $card_class = 'border-0' . ' ' . $style_card;
   $card_img_top = 'rounded';
   $card_body_class = 'p-0 pt-2';
}

?>

<?php if ( is_page_template('page-blog.php') || is_author() ): ?>
<?php // Formato tipo blog (imagen izquierda, texto derecha) ?>
<article class="post-blog mb-4">
    <div class="row">
        <!-- Imagen del post -->
        <?php if (!empty($image_url)): ?>
        <div class="col-lg-4 d-flex align-items-stretch">
            <a href="<?php echo esc_url($permalink); ?>" class="text-decoration-none w-100">
               <img src="<?php echo esc_url($image_url); ?>" 
                  alt="<?php echo esc_attr($title); ?>" 
                  class="fit post-img rounded shadow-sm" height="240">
            </a>
        </div>
        <?php endif; ?>

        <!-- Contenido del post -->
        <div class="col-lg-8 ps-lg-4 d-flex flex-column justify-content-between">
            <div>
               <!-- Título del post -->
               <?php if (!empty($title)): ?>
               <a href="<?php echo esc_url($permalink); ?>" class="text-decoration-none">
                  <p class="heading-4 title mt-3 mb-2 fw600 c-black">
                     <?php echo esc_html($title); ?>
                  </p>
               </a>
               <?php endif; ?>
               
               <!-- Autor y Fecha -->
               <?php if (!empty($author)): ?>
               <div class="d-flex text-muted">
                  <small class="author">
                     <?php esc_html_e( 'Escrito por ', CTM_TEXTDOMAIN ); ?>
                     <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo $author; ?></a>
                  </small>
                  <span class="mx-2 line-sep">|</span>
                  <small class="date">
                     <?php echo esc_html($date); ?>
                  </small>
               </div>
               <?php endif; ?>
               
               <!-- Resumen del post -->
               <?php if (!empty($excerpt)): ?>
                  <p class="excerpt fs15 mt-2">
                     <?php echo esc_html($excerpt); ?>
                  </p>
               <?php endif; ?>
            </div>
            
            <!-- Botón Leer Más -->
            <?php if (!empty($permalink)): ?>
               <div class="d-flex justify-content-end">
                  <a href="<?php echo esc_url($permalink); ?>" class="btn btn-sm btn-outline-secondary fs14"><?php esc_html_e( 'Leer más', CTM_TEXTDOMAIN ); ?></a>
               </div>
            <?php endif; ?>
        </div>
    </div>
</article>
<?php else: ?>
<?php // Formato tipo card (imagen arriba, contenido debajo) ?>
   <div class="col-lg-4 mb-4">
      <a href="<?php echo esc_url($permalink); ?>" class="card h-100 text-decoration-none overflow-hidden relative <?php echo esc_attr($card_class); ?>">
         <!-- Imagen del post -->
         <?php if (!empty($image_url)): ?>
         <figure class="overflow-hidden <?php echo esc_attr($card_img_top); ?>">
            <img src="<?php echo esc_url($image_url); ?>" 
               alt="<?php echo esc_attr($title); ?>" 
               class="card-img-top fit <?php echo esc_attr($card_img_top); ?>">
         </figure>
         <?php endif; ?>
         <!-- Contenido del post -->
         <div class="card-body <?php echo esc_attr($card_body_class); ?>">
            <?php if (!empty($title)): ?>
               <p class="heading-4 title mt-3 mb-2 fw600 c-black">
                  <?php echo esc_html($title); ?>
               </p>
            <?php endif; ?>
            <?php if (!empty($author)): ?>
               <div class="d-flex mb-1 text-muted">
                  <small class="author">
                     <?php esc_html_e( 'Escrito por ', CTM_TEXTDOMAIN ); ?> 
                     <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo $author; ?></a>
                  </small>
                  <?php if (!empty($date)): ?>
                  <span class="mx-2 line-sep">|</span>
                  <small class="date">
                     <?php echo esc_html($date); ?>
                  </small>
                  <?php endif; ?>
               </div>
            <?php endif; ?>
            <?php if (!empty($excerpt)): ?>
               <p class="card-text text-muted">
                  <?php echo esc_html($excerpt); ?>
               </p>
            <?php endif; ?>
         </div>
      </a>
   </div>
<?php endif; ?>
