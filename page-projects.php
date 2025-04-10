<?php
/**
 * Template Name: Trabajos
 * Description: Plantilla para listar todos los trabajos del CPT 'trabajos' y filtrarlos por categoría.
 */

$bodyclass = 'page-trabajos';
$cpt_slug  = 'trabajos';
$taxonomy  = 'tipo-trabajo';
$fields    = get_fields();
$content   = get_the_content();
get_header();
?>

<main class="main-content bg-light-subtle">
    <?php
    // Cabecera (pageheader)
    get_template_part('template-parts/pageheader', null, ['pageheader_style' => 'bg-image']);
    ?>

    <?php if (!empty($content)) : ?>
    <div class="bg-light-subtle py-5">
        <div class="container">
            <?php echo $content; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php
    // Obtener todos los términos de la taxonomía 'tipo-trabajo'
    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => true,
    ]);
    ?>

    <!-- Filtro de categorías (botones) -->
    <section class="container py-4">
        <div class="filter-buttons d-flex flex-wrap gap-2 justify-content-center">
            <!-- Botón para mostrar todos -->
            <button class="btn btn-md btn-outline-secondary active" data-filter="all">
                <?= __('Todos', THEME_TEXTDOMAIN); ?>
            </button>
            
            <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
                <?php foreach ($terms as $term) : ?>
                    <button class="btn btn-md btn-secondary" data-filter="<?php echo esc_attr($term->slug); ?>">
                        <?php echo esc_html($term->name); ?>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Listado completo de trabajos -->
    <section class="container my-4">
        <div class="row gy-4" id="projects-container">
            <?php
            // Query para traer TODOS los trabajos
            $query_trabajos = new WP_Query([
                'post_type'      => $cpt_slug,
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ($query_trabajos->have_posts()) :
                while ($query_trabajos->have_posts()) : $query_trabajos->the_post();
                    
                    // Obtener slug(es) de la(s) categoría(s) asignada(s) a este CPT
                    $terms_cpt = get_the_terms(get_the_ID(), $taxonomy);
                    $term_slugs = [];
                    if (!empty($terms_cpt) && !is_wp_error($terms_cpt)) {
                        foreach ($terms_cpt as $t) {
                            $term_slugs[] = $t->slug;
                        }
                    }
                    
                    // Generar un atributo data-cat con las categorías separadas por espacio (para filtrar con JS)
                    $data_categories = implode(' ', $term_slugs);

                    // Variables para la tarjeta
                    $title     = get_the_title();
                    $excerpt   = wp_trim_words(get_the_excerpt(), 20);
                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    $permalink = get_permalink();
                    ?>
                    
                    <div class="col-12 col-md-6 col-lg-4" data-cat="<?php echo esc_attr($data_categories); ?>">
                        <?php
                        // Plantilla para cada item 
                        get_template_part('template-parts/components/card-cpt', null, [
                           'title'            => get_the_title(),
                           'permalink'        => get_permalink(),
                           'video_url'        => get_field('link_youtube'), // Campo ACF
                           'show_videos_only' => $fields['show_videos_only'], 
                       ]);
                        ?>
                    </div>
                    
                <?php endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-center">No hay trabajos disponibles.</p>';
            endif;
            ?>
        </div>
    </section>

    <?php
    // Cargar bloques flexibles (ACF)
    require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
    load_flexible_blocks($fields['flexible_content']);
    ?>
</main>

<!-- Filtrado -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const buttons = document.querySelectorAll('.filter-buttons button');
    const itemsContainer = document.querySelector('#projects-container');
    const items = itemsContainer.querySelectorAll('[data-cat]');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Quitar 'active' de todos y asignar al actual
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filterVal = btn.getAttribute('data-filter');
            
            items.forEach(item => {
                // Si el botón es 'all', mostrar todo
                if(filterVal === 'all') {
                    item.style.display = '';
                } else {
                    // item tiene un data-cat con slugs separados por espacio
                    const cats = item.getAttribute('data-cat').split(' ');
                    // Mostrar item si su array cats incluye el slug
                    if(cats.includes(filterVal)){
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?>
