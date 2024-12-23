<?php
/**
 * Componente Reutilizable: Banner / CTA
 * 
 * Muestra un banner dinámico con opciones configurables en ACF:
 * - Diseño: Imagen de fondo o Imagen al lado.
 * - Elementos opcionales: Tagline, Texto, Botón y Formulario.
 * 
 * Campos esperados:
 * - Diseño (`banner_layout`): bg_image | side_image
 * - Ancho (`banner_width`): full | container
 * - Tagline (`tagline`): Texto opcional
 * - Título (`title`): Título principal
 * - Texto (`text`): Texto descriptivo
 * - Botón (`button`): Enlace con título y destino
 * - Imagen (`image`): Imagen del banner
 * - Posición de la Imagen (`position_image`): right | left
 * - Color de fondo (`bg_color`): Para diseño "imagen al lado"
 * - Mostrar formulario (`show_form`): Booleano
 * - ID del formulario (`form_id`): ID del formulario opcional
 */

// Variables de la página de Opciones
$contact_page_id  = get_fields('page_contacto', 'option'); 
$contact_page_url = $contact_page_id ? get_permalink($contact_page_id) : '/contacto/';

// Variables principales
$layout         = $block['banner_layout'] ?? 'bg_image';
$width          = $block['banner_width'] ?? '';
$tagline        = $block['tagline'] ?? '';
$htag_tagline   = $block['htag_tagline'] ?? 3;
$title          = $block['title'] ?? '';
$htag_title     = $block['htag_title'] ?? 3;
$description    = $block['text'] ?? '';
$button         = !$block['show_form'] ? $block['button'] : null;
$banner_image   = $block['image'] ?? null;
$image_position = $block['position_image'] ?? 'right';
$bg_color       = $block['bg_color'] ?? 'c-bg-light';
$show_form      = $block['show_form'] ?? false;
$form_id        = $block['id_form'] ?? '';

// Datos del botón
$button_text    = $button['title'] ?? 'Contactar';
$button_url     = $button['url'] ?? $contact_page_url;
$button_target  = $button['target'] ?? '_self';
$button_class   = in_array($bg_color, ['c-bg-white', 'c-bg-light'])
                    ? 'btn btn-md btn-primary mt-2'
                    : 'btn btn-md btn-secondary mt-2';

// Imagen del banner (si no hay imagen, usar una por defecto)
$default_image  = get_template_directory_uri() . '/assets/img/default-background.jpg';

// Optimización de imágenes según el dispositivo
$image_id        = $banner_image['id'] ?? null;
$bg_image_large  = $image_id ? wp_get_attachment_image_url($image_id, 'block_large') : $default_image;
$bg_image_medium = $image_id ? wp_get_attachment_image_url($image_id, 'block_medium') : $default_image;
$bg_image_small  = $image_id ? wp_get_attachment_image_url($image_id, 'block_small') : $default_image;

// Shortcode del formulario
$shortcode_form = $show_form && !empty($form_id) ? '[gravityform id="' . esc_attr($form_id) . '" title="false" description="false"]' : '';

// Clases css dinámicas
$is_light_bg   = in_array( $bg_color, ['c-bg-white', 'c-bg-light'] );
$class_tagline = $is_light_bg || $layout === 'bg_image' 
                    ? 'tagline c-primary'
                    : 'tagline c-black text-uppercase';

$class_title = $layout === 'side_image' && $is_light_bg 
                    ? 'heading-2 c-black'
                    : 'heading-2 c-white';

$class_title .= $layout === 'bg_image' ? ' text-shadow' : '';
$text_color   = $is_light_bg && $layout === 'side_image' ? 'c-black' : 'c-white';

// Clases y estilos del contenedor del banner
$section_classes = [
    $layout === 'bg_image' ? $layout . ' cover' : $layout,
    $bg_color,
    $width === 'container' ? $width . ' rounded' : $width,
];

$section_class = implode(' ', $section_classes);
$linear_gradient = 'linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%)';
?>

<style>
    .banner-component.bg_image {
        background: <?php echo $linear_gradient; ?>, url('<?php echo esc_url($bg_image_large); ?>');
    }

    @media (max-width: 1024px) {
        .banner-component.bg_image {
            background: <?php echo $linear_gradient; ?>, url('<?php echo esc_url($bg_image_medium); ?>');
        }
    }

    @media (max-width: 768px) {
        .banner-component.bg_image {
            background: <?php echo $linear_gradient; ?>, url('<?php echo esc_url($bg_image_small); ?>');
        }
    }
</style>

<section class="banner-component my-5 <?php echo esc_attr($section_class); ?>">
    <?php if ($layout === 'side_image') : ?>
        <?php // Contenido ?>
        <div class="d-flex justify-content-between align-items-center position-relative">
            <div class="container">
                <div class="col-lg-5 col-content py-5 <?php echo esc_attr($text_color); ?> <?php echo $image_position === 'left' ? 'ms-auto' : ''; ?>">
                    <?php
                    // Encabezado del banner
                    get_component('template-parts/components/section-heading', [
                        'style'         => 'minimal',
                        'show_heading'  => true,
                        'tagline'       => $tagline,
                        'htag_tagline'  => $htag_tagline,
                        'class_tagline' => $class_tagline,
                        'title'         => $title,
                        'htag_title'    => $htag_title,
                        'class_title'   => $class_title,
                        'text'          => $description,
                        'cta_button'    => $button,
                        'class_button'  => $button_class
                    ]);
                    ?>
                    <?php if ($show_form && !empty($form_id)) : ?>
                        <div class="banner-form mt-4">
                            <?php echo do_shortcode($shortcode_form); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php // Imagen ?>
            <div class="col-lg-6 col-image position-absolute top-0 h-100 cover <?php echo $image_position === 'left' ? 'start-1' : 'end-0'; ?>"
                style="background-image: url('<?php echo esc_url($bg_image_medium); ?>');">
            </div>
        </div>
    <?php else : ?>
        <?php // Imagen de fondo con contenido centrado ?>
        <div class="container">
            <div class="row justify-content-between align-items-center position-relative">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-center py-5 <?php echo esc_attr($text_color); ?>">
                    <?php
                    // Encabezado del banner
                    get_component('template-parts/components/section-heading', [
                        'style'         => 'minimal',
                        'show_heading'  => true,
                        'tagline'       => $tagline,
                        'htag_tagline'  => $htag_tagline,
                        'class_tagline' => $class_tagline,
                        'title'         => $title,
                        'htag_title'    => $htag_title,
                        'class_title'   => $class_title,
                        'text'          => $description,
                        'cta_button'    => $button,
                        'class_button'  => $button_class
                    ]);
                    ?>
                    <?php if ($show_form && !empty($form_id)) : ?>
                        <div class="banner-form mt-4">
                            <?php echo do_shortcode($shortcode_form); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>
