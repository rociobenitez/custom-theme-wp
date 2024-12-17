<?php
/**
 * Template Name: Formación Online
 * Description: Plantilla para listar solo los cursos de modalidad "online".
 * 
 * Requisitos:
 * - Debe existir un Custom Post Type (CPT) con slug 'cursos'.
 * - La taxonomía asociada "modalidad" debe contener los términos 'presencial' y 'online'.
 * - Se requiere el uso del template part 'template-parts/components/card-cpt' para renderizar cada curso.
 * - Los campos flexibles ACF 'flexible_content' se cargarán al final de la página si están definidos.
 */

$cpt_slug  = 'cursos';     // Custom Post Type: Cursos
$taxonomy  = 'modalidad';  // Taxonomía: modalidad
$term_slug = 'online';     // Término específico para cursos online

$fields        = get_fields();
$page_title    = get_the_title();
$section_title = !empty($fields['pageheader_title'])
    ? $fields['pageheader_title']
    : 'Formación Online';
$section_title_class = 'section-title heading-2 mb-4 text-center';
$htag_section_title = 1; # H2

get_header(); ?>

<main class="main-content">
    <?php
    // Mostrar cabecera de la página con estilo "bg-image"
    get_template_part('template-parts/pageheader', null, [
        'pageheader_style' => 'bg-image',
        'title' => $page_title
    ]);
    ?>

    <!-- Sección Cursos Online -->
    <section class="cpt-courses container my-5">
        <?php
        /**
         * Función para renderizar cursos por taxonomía específica.
         * 
         * @param string $taxonomy      Taxonomía (ej: modalidad).
         * @param string $term          Slug del término (ej: online).
         * @param string $cpt_slug      Slug del Custom Post Type (ej: cursos).
         * @param string $section_title Título de la sección.
         * @param string $section_class Clase adicional para la sección.
         */
        function render_courses($taxonomy, $term, $cpt_slug, $section_title, $section_class, $htag_section_title, $section_title_class) {
            $query = new WP_Query([
                'post_type'      => $cpt_slug,
                'posts_per_page' => -1,
                'tax_query'      => [[
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term,
                ]],
            ]);

            if ($query->have_posts()) : ?>
                <div class="<?php echo esc_attr($section_class); ?>">
                    <?php echo tagTitle($htag_section_title, $section_title, $section_title_class, ''); ?>
                    <div class="row">
                        <?php
                        while ($query->have_posts()) : $query->the_post();
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
            else :
                echo '<p class="text-center">No hay cursos disponibles para esta modalidad.</p>';
            endif;

            wp_reset_postdata();
        }

        // Renderizar cursos online
        render_courses($taxonomy, $term_slug, $cpt_slug, $section_title, 'cpt-section my-5', $htag_section_title, $section_title_class);
        ?>
    </section>

    <?php
    // Cargar bloques flexibles dinámicamente
    require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
    load_flexible_blocks($fields['flexible_content']);
    ?>

</main>

<?php get_footer(); ?>
