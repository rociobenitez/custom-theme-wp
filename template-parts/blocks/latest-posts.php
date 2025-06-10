<?php
/**
 * Bloque de Últimos Posts
 * Muestra los 3 posts más recientes del blog.
 */

// Query para obtener los 3 últimos posts publicados
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
);
$latest_posts = new WP_Query($args);

// Definir el encabezado del título de cada post
$htag = $block['htag_title'] ?? 3;
$title = get_the_title();
?>

<?php if ($latest_posts->have_posts()) : ?>
<section class="latest-posts features">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-end flex-column">
                        <!-- Imagen destacada del post con enlace al artículo -->
                        <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                            <img class="post-img fit"
                                 src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>"
                                 alt="<?php esc_attr(the_title_attribute()); ?>">
                        </a>
                        <!-- Contenido del post -->
                        <div class="post-content feature-content text-start text-lg-end mt-4">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo tagTitle($htag, get_the_title(), 'post-title', ''); ?>
                            </a>
                            <div class="post-excerpt features-text">
                                <?php
                                    $excerpt = get_the_excerpt(); 
                                    $excerpt = wp_trim_words($excerpt, 15, '...'); // Máximo 15 palabras
                                    echo esc_html($excerpt);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
