<?php
/**
 * Bloque Hero
 */

// Obtener los valores de los campos ACF
$fields           = get_fields();
$title            = !empty($fields['hero_main_title']) ? $fields['hero_main_title'] : '';
$htag             = (isset($fields['hero_heading_htag']) && $fields['hero_heading_htag'] !== '') ? $fields['hero_heading_htag'] : 0;
$subtitle         = !empty($fields['hero_subtitle']) ? $fields['hero_subtitle'] : '';
$htag_subtitle    = (isset($fields['hero_subtitle_htag']) && $fields['hero_subtitle_htag'] !== '') ? $fields['hero_subtitle_htag'] : 3;
$tagline_position = !empty($fields['hero_tagline_position']) ? $fields['hero_tagline_position'] : 'below'; // 'above' o 'below'
$text_alignment   = !empty($fields['hero_text_alignment']) ? $fields['hero_text_alignment'] : 'center';  // 'center', 'left' o 'right'
$description      = !empty($fields['hero_description']) ? $fields['hero_description'] : '';
$bg_type          = !empty($fields['hero_background_type']) ? $fields['hero_background_type'] : 'image';
$img_bg           = !empty($fields['hero_background_image']) ? $fields['hero_background_image'] : [];
$video_bg         = !empty($fields['hero_background_video']) ? $fields['hero_background_video'] : [];

// Botones
$link_1 = !empty($fields['hero_cta_button']) ? $fields['hero_cta_button'] : [];
$link_2 = !empty($fields['hero_secondary_cta_button']) ? $fields['hero_secondary_cta_button'] : [];

// Si no hay un video o imagen de fondo, usar la imagen por defecto
if ( empty( $img_bg['url'] ) && empty( $video_bg['url'] ) ) {
    $bg_type = 'image';
    $img_bg  = ['url' => get_template_directory_uri() . '/assets/img/hero.jpg'];
}

// Mapear la clase de alineación del texto según el valor seleccionado
switch ( $text_alignment ) {
    case 'left':
        $text_align_class = 'text-start';
        $margin_text = 'me-auto';
        $align_container = 'justify-content-start';
        break;
    case 'right':
        $text_align_class = 'text-end';
        $margin_text = 'ms-auto';
        $align_container = 'justify-content-end';
        break;
    default:
        $text_align_class = 'text-center';
        $margin_text = 'mx-auto';
        $align_container = 'justify-content-center';
        break;
}
?>

<div id="hero">
    <?php if ( 'image' === $bg_type && ! empty( $img_bg['url'] ) ) : ?>
        <div class="hero-bg cover d-flex <?= esc_attr( $align_container ); ?> align-items-center"
            style="background:
                linear-gradient(0deg, rgba(20, 20, 20, 0.6), rgba(20, 20, 20, 0.8)),
                linear-gradient(260deg, rgba(50, 50, 50, 0.5) 0%, rgba(20, 20, 20, 0.3) 100%),
                url('<?= esc_url( $img_bg['url'] ); ?>');">
    <?php elseif ( 'video' === $bg_type && ! empty( $video_bg['url'] ) ) : ?>
        <div class="hero-bg cover d-flex <?= esc_attr( $align_container ); ?> relative overflow-hidden">
            <video autoplay muted loop class="background-video absolute w-100 h-100 object-fit-cover">
                <source src="<?= esc_url( $video_bg['url'] ); ?>" type="video/mp4">
                <?php esc_html_e( 'Tu navegador no soporta este vídeo.', CTM_TEXTDOMAIN ); ?>
            </video>
            <div class="video-overlay absolute"></div>
    <?php endif; ?>
    
        <div class="row h-100 container mx-auto py-5 <?= esc_attr( $text_align_class ); ?>">
            <div class="col-md-10 col-lg-8 d-flex flex-column justify-content-center h-100 py-5 my-5 <?= esc_attr( $margin_text ); ?>">
                <?php 
                // Si el subtítulo debe aparecer arriba, muéstralo antes del título
                if ( 'above' === $tagline_position && ! empty( $subtitle ) ) : ?>
                    <div class="hero-subtitle-container">
                        <?= tagTitle( $htag_subtitle, esc_html( $subtitle ), 'hero-subtitle fs20 c-white lh160', ''); ?>
                    </div>
                <?php endif; ?>
                
                <?php
                // Mostrar el título si existe
                if ( ! empty( $title ) ) :
                    echo tagTitle( $htag, esc_html( $title ), 'heading-1 hero-title c-white', '');
                endif;
                ?>

                <?php
                // Si el subtítulo debe aparecer debajo, muéstralo después del título
                if ( 'below' === $tagline_position && ! empty( $subtitle ) ) : ?>
                    <div class="hero-subtitle-container">
                        <?= tagTitle( $htag_subtitle, esc_html( $subtitle ), 'hero-subtitle fs20 c-white lh160', ''); ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $description ) ) : ?>
                    <div class="hero-description text-white">
                        <?= wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="hero-buttons d-flex flex-column flex-md-row gap-2 mt-5 <?= esc_attr( $align_container ); ?>">
                    <?php if ( !empty($link_1['url'] ) && !empty( $link_1['title'] ) ) : ?>
                        <a href="<?= esc_url( $link_1['url'] ); ?>" 
                           class="btn btn-xl btn-primary hero-button" 
                           target="<?= esc_attr( $link_1['target']) ; ?>">
                            <?= esc_html( $link_1['title'] ); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( !empty($link_2['url'] ) && !empty( $link_2['title'] ) ) : ?>
                        <a href="<?= esc_url( $link_2['url'] ); ?>" 
                           class="btn btn-xl btn-transparent hero-button" 
                           target="<?= esc_attr( $link_2['target'] ); ?>">
                            <?= esc_html( $link_2['title'] ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
