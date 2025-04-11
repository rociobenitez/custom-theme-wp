<?php
/**
 * Bloque de Categorías Relacionadas - Scroll Horizontal
 * Muestra las categorías seleccionadas en ACF con scroll manual, ordenadas de la Z a la A.
 *
 * @package customtheme
 */

$htag = (isset($block['htag']) && $block['htag'] !== '') ? $block['htag'] : 2;

// Verificar si el campo "categories" de ACF tiene contenido
if (!empty($block['categories']) && is_array($block['categories'])) :
    // Ordenar las categorías de forma descendente (Z-A) por el nombre del término
    usort($block['categories'], function($a, $b) {
        // Obtener el nombre del primer término (si es objeto o ID)
        $nameA = is_object($a) ? $a->name : (get_term($a, 'product_cat')->name ?? '');
        $nameB = is_object($b) ? $b->name : (get_term($b, 'product_cat')->name ?? '');
        return strcmp($nameB, $nameA); // Orden descendente: se compara B con A
    });
    ?>
    <section class="categories-scroll-manual">
        <div class="categories-wrapper">
            <?php foreach ($block['categories'] as $category) : ?>
                <?php
                // Datos de la categoría seleccionada en ACF
                $category_id = is_object($category) ? $category->term_id : $category;
                $term_obj = get_term($category_id, 'product_cat');
                // Si ocurre algún error al obtener el término, se asigna un valor por defecto
                if (is_wp_error($term_obj)) {
                    continue;
                }
                $category_link = get_term_link($category_id, 'product_cat'); // Enlace a la categoría
                $category_title = get_term($category_id)->name ?? 'Sin título'; // Nombre de la categoría

                // Imagen destacada de la categoría: se usa la miniatura asignada, si no, una imagen por defecto
                $image_id = get_term_meta($category_id, 'thumbnail_id', true);
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : wc_placeholder_img_src();
                ?>
                <div class="category cover" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%), url(<?= esc_url($image_url); ?>);">
                    <a class="d-flex w-100 h-100 align-items-end justify-content-center p-3" href="<?= esc_url($category_link); ?>" aria-label="<?= esc_attr($category_title); ?>">
                        <?= tagTitle($htag, $category_title, 'category-title', ''); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
