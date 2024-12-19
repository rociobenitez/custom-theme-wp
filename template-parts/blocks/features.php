<?php 
/**
 * Bloque de Características
 *
 * Este bloque permite mostrar destacados con opciones flexibles:
 * - Con o sin encabezado.
 * - Diseño con imagen (posición 'top' o 'bg') o sin imagen.
 * - Alineación automática y dinámica según el número de destacados.
 *
 * Campos ACF esperados:
 * - 'show_heading'            => Mostrar encabezado (boolean).
 * - 'tagline', 'htag_tagline' => Tagline y etiqueta HTML (h3, h4, etc.).
 * - 'title', 'htag_title'     => Título del bloque y etiqueta HTML.
 * - 'text'                    => Descripción del bloque.
 * - 'background_color'        => Color de fondo del bloque.
 * - 'featured_with_image'     => Determina si los destacados tienen imagen (boolean).
 * - 'position_image'          => Posición de la imagen: 'top' (encima) o 'bg' (fondo).
 * - 'featured_items_images'   => Repeater para destacados con imagen.
 * - 'featured_items'          => Repeater para destacados sin imagen.
 */

$tagline             = $block['tagline'] ?? '';
$htag_tagline        = $block['htag_tagline'] ?? 3;
$title               = $block['title'] ?? '';
$htag_title          = $block['htag_title'] ?? 2;
$description         = $block['text'] ?? '';
$show_heading        = $block['show_heading'] ?? false;
$bg_color            = $block['background_color'] ?? '#F9F9F9';
$featured_with_image = $block['featured_with_image'] ?? false;
$position_image      = $block['position_image'] ?? 'top';
$default_image       = get_template_directory_uri() . '/img/img-default.jpg';

// Verifica si 'features' tiene contenido
$features = $featured_with_image
    ? ($block['featured_items_images'] ?? [])
    : ($block['featured_items'] ?? []);

if (!is_array($features) || empty($features)) {
    return; // Si no hay destacados, no renderizar
}

// Calcular las columnas dinámicamente
$feature_count = count($features);
$col_class = ($feature_count === 4) ? 'col-md-6 col-lg-3' 
    : (($feature_count === 2) ? 'col-md-6' : 'col-md-6 col-lg-4'); 

// Clase CSS para los títulos de los detacados
$feature_title_class = 'heading-4 feature-item-title c-dark';
?>
<section class="features-block py-5" style="background-color: <?php echo esc_attr($bg_color); ?>">
    <div class="container">
        <?php if (!empty($title)) :
            get_component('template-parts/components/section-heading', [
                'container_class'  => 'col-md-8 mx-auto mb-4',
                'show_heading'     => $show_heading,
                'tagline'          => $tagline,
                'htag_tagline'     => $htag_tagline,
                'title'            => $title,
                'htag_title'       => $htag_title,
                'class_title'      => 'heading-2',
                'text'             => $description
            ]);
        endif; ?>
        <div class="row justify-content-center">
            <?php foreach ($features as $feature): 
                $title       = $feature['title'] ?? '';
                $htag_title  = $feature['htag_title'] ?? 'h4';
                $description = $feature['description'] ?? '';
                $image       = $feature['image']['url'] ?? $default_image;

                // Diseño con imagen tipo "top"
                if ($featured_with_image && $position_image === 'top'):
                    if(!empty($image) && !empty($title)): ?>
                        <div class="<?php echo esc_attr($col_class); ?> mb-4">
                            <div class="feature-item feature-item-img-top">
                                <figure class="overflow-hidden rounded">
                                    <img src="<?php echo esc_url($image); ?>" 
                                        alt="<?php echo esc_attr($title); ?>" 
                                        class="card-img-top fit rounded">
                                </figure>
                                <?php echo tagTitle($htag_title, $title, $feature_title_class, ''); ?>
                                <div class="feature-description">
                                    <?php echo wp_kses_post($description); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php 
                // Diseño con imagen tipo "bg"
                elseif ($featured_with_image && $position_image === 'bg'):
                    if(!empty($image) && !empty($title)): ?>
                        <div class="<?php echo esc_attr($col_class); ?> mb-4">
                            <div class="feature-item feature-item-img-bg position-relative overflow-hidden rounded cover p-4 d-flex justify-content-center align-items-center"
                                style="background-image: url('<?php echo esc_url($image); ?>');">
                                <div class="feature-content p-4 c-bg-white rounded position-relative">
                                    <div class="feature-description text-center fw600">
                                        <?php echo wp_kses_post($description); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php 
                // Diseño sin imagen
                else:
                    if(!empty($title)): ?>
                        <div class="<?php echo esc_attr($col_class); ?> mb-4">
                            <div class="feature-item feature-item-text text-center">
                                <?php echo tagTitle($htag_title, $title, $feature_title_class, ''); ?>
                                <div class="feature-description">
                                    <?php echo wp_kses_post($description); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; 
                endif;
            endforeach; ?>
        </div>
    </div>
</section>

