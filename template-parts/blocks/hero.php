<?php
/**
 * Bloque Hero
 */

// Obtener los valores de los campos ACF
$fields           = get_fields();
$title            = $fields['hero_main_title'] ?? '';
$htag             = $fields['hero_heading_htag'] ?? 0;
$subtitle         = $fields['hero_subtitle'] ?? '';
$htag_subtitle    = $fields['hero_subtitle_htag'] ?? 3;
$tagline_position = $fields['hero_tagline_position'] ?? 'below'; // 'above' o 'below'
$text_alignment   = $fields['hero_text_alignment'] ?? 'center';  // 'center', 'left' o 'right'
$description      = $fields['hero_description'] ?? '';
$bg_type          = $fields['hero_background_type'] ?? 'image';
$img_bg           = $fields['hero_background_image'] ?? [];
$video_bg         = $fields['hero_background_video'] ?? [];

// Botones
$link_1 = $fields['hero_cta_button'] ?? [];
$link_2 = $fields['hero_secondary_cta_button'] ?? [];

// Si no hay un video o imagen de fondo, usar la imagen por defecto
if ( empty( $img_bg['url'] ) && empty( $video_bg['url'] ) ) {
    $bg_type = 'image';
    $img_bg  = ['url' => get_template_directory_uri() . '/assets/img/hero.jpg'];
}

// Mapear la clase de alineación del texto según el valor seleccionado
switch ( $text_alignment ) {
    case 'left':
        $text_align_class = 'text-start';
        $margin_text = 'me-auto my-5';
        $align_container = 'justify-content-start';
        break;
    case 'right':
        $text_align_class = 'text-end';
        $margin_text = 'ms-auto my-5';
        $align_container = 'justify-content-end';
        break;
    default:
        $text_align_class = 'text-center';
        $margin_text = 'mx-auto my-5';
        $align_container = 'justify-content-center';
        break;
}
?>

<div id="hero" class="overflow-hidden">
    <?php if ( 'image' === $bg_type && ! empty( $img_bg['url'] ) ) : ?>
        <div class="hero-bg cover d-flex z-1 <?= esc_attr( $align_container ); ?>"
            style="background:
                linear-gradient(0deg, rgba(20, 20, 20, 0.4), rgba(20, 20, 20, 0.5)),
                linear-gradient(260deg, rgba(50, 50, 50, 0.4) 0%, rgba(20, 20, 20, 0.3) 100%),
                url('<?= esc_url( $img_bg['url'] ); ?>');">
    <?php elseif ( 'video' === $bg_type && ! empty( $video_bg['url'] ) ) : ?>
        <div class="hero-bg cover d-flex <?= esc_attr( $align_container ); ?> position-relative overflow-hidden z-1">
            <video autoplay muted loop class="hero-video">
                <source src="<?= esc_url( $video_bg['url'] ); ?>" type="video/mp4">
                <?php esc_html_e( 'Tu navegador no soporta este vídeo.', THEME_TEXTDOMAIN ); ?>
            </video>
            <div class="video-overlay position-absolute top-0 start-0 h-100 w-100 bg-black bg-opacity-25 z-2"></div>
    <?php endif; ?>
    
        <div class="container position-absolute h-100 py-5 z-3">
            <div class="col-md-10 col-lg-8 col-xl-6 d-flex flex-column justify-content-center h-100 py-5 <?= esc_attr( $text_align_class . ' ' . $margin_text ); ?>">
                <?php 
                // Si el subtítulo debe aparecer arriba, muéstralo antes del título
                if ( 'above' === $tagline_position && ! empty( $subtitle ) ) : ?>
                    <p class="hero-subtitle fs20 c-white lh160"><?= esc_html( $subtitle ); ?></p>
                <?php endif; ?>
                
                <?php if ( ! empty( $title ) ) : ?>
                    <?= tagTitle( $htag, esc_html( $title ), 'heading-1 hero-title c-white', ''); ?>
                <?php endif; ?>

                <?php
                // Si el subtítulo debe aparecer debajo, muéstralo después del título
                if ( 'below' === $tagline_position && ! empty( $subtitle ) ) : ?>
                    <p class="hero-subtitle fs20 c-white lh160"><?= esc_html( $subtitle ); ?></p>
                <?php endif; ?>

                <?php if ( ! empty( $description ) ) : ?>
                    <div class="hero-description text-white">
                        <?= wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <div class="hero-buttons d-flex flex-column flex-md-row gap-2 mt-3 <?= esc_attr( $align_container ); ?>">
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
