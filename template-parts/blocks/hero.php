<?php
/**
 * Hero
 */

// Obtener los valores de los campos ACF
$fields   = get_fields();
$title    = $fields['main_title'] ?: '';
$htag     = $fields['heading_tag'] ?: 0;
$subtitle = $fields['subtitle'] ?: '';
$bg_type  = $fields['background_type'] ?: 'image';
$img_bg   = $fields['background_image'];
$video_bg = $fields['background_video'];

// Configuración de los botones
$link_1 = $fields['cta_button'] ?: '';
$link_2 = $fields['secondary_cta_button'] ?: '';

// Verificar si no hay imagen ni video, y usar la imagen por defecto en ese caso
if (empty($img_bg['url']) && empty($video_bg['url'])) {
    $bg_type = 'image';
    $img_bg  = ['url' => get_template_directory_uri() . '/assets/img/hero.jpg'];
}
?>

<div id="hero">
    <?php if ($bg_type == 'image' && !empty($img_bg['url'])) : ?>
        <div class="hero-bg cover d-flex align-items-end"
            style="background:
                linear-gradient(0deg, rgba(10, 25, 47, 0.5), rgba(10, 25, 47, 0.6)),
                linear-gradient(260deg, rgba(16, 42, 67, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%),
                url('<?php echo esc_url($img_bg['url']); ?>');">
    <?php elseif ($bg_type == 'video' && !empty($video_bg['url'])) : ?>
        <div class="hero-bg cover d-flex align-items-end relative overflow-hidden">
            <video autoplay muted loop class="background-video absolute">
                <source src="<?php echo esc_url($video_bg['url']); ?>" type="video/mp4">
                Tu navegador no soporta este vídeo.
            </video>
            <div class="video-overlay absolute"></div>
    <?php endif; ?>
    
        <div class="row h-100 container mx-auto py-5">
            <div class="col-md-10 col-lg-8 col-xl-6 d-flex flex-column justify-content-center h-100 py-5 my-5">
                <?php echo tagTitle($htag, esc_html($title), 'heading-1 hero-title c-white', ''); ?>
                <div class="hero-subtitle-container">
                    <p class="hero-subtitle fs20 c-white lh160"><?php echo esc_html($subtitle); ?></p>
                </div>
                <div class="hero-buttons d-flex flex-column flex-md-row gap-2 mt-3">
                    <?php if (!empty($link_1['url']) && !empty($link_1['title'])) : ?>
                        <a href="<?php echo esc_url($link_1['url']); ?>" 
                           class="btn btn-xl btn-primary hero-button" 
                           target="<?php echo esc_attr($link_1['target']); ?>">
                            <?php echo esc_html($link_1['title']); ?>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($link_2['url']) && !empty($link_2['title'])) : ?>
                        <a href="<?php echo esc_url($link_2['url']); ?>" 
                           class="btn btn-xl btn-transparent hero-button" 
                           target="<?php echo esc_attr($link_2['target']); ?>">
                            <?php echo esc_html($link_2['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
