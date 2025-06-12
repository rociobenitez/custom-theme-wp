<?php
/**
 * Bloque Hero
 */

defined( 'ABSPATH' ) or exit;

use Custom_Theme\Helpers\Template_Helpers;

// Obtener los argumentos del bloque Hero
$title          = $args['hero_main_title'] ?? '';
$htag           = $args['hero_heading_htag'] ?? 1;
$subtitle       = $args['hero_subtitle'] ?? '';
$htag_sub       = $args['hero_subtitle_htag'] ?? 3;
$tag_pos        = $args['hero_tagline_position'] ?? 'below';
$text_align     = $args['hero_text_alignment'] ?? 'center';
$description    = $args['hero_description'] ?? '';
$split_enabled  = $args['hero_split'] ?? false;
$split_side     = $args['hero_split_side'] === 'right' ? 'right' : 'left';
$bg_type        = $args['hero_background_type'] ?? 'image';
$img_bg         = $args['hero_background_image']['url'] ?? '';
$video_bg       = $args['hero_background_video']['url'] ?? '';
$btn_primary    = $args['hero_cta_button'] ?? [];
$btn_secondary  = $args['hero_secondary_cta_button'] ?? [];

// Botones
$link_1 = $args['hero_cta_button'] ?? [];
$link_2 = $args['hero_secondary_cta_button'] ?? [];

if ( $bg_type === 'image' && ! $img_bg ) {
    $img_bg = get_template_directory_uri() . '/assets/img/hero.jpg';
}

// mapa de clases de alineación
$map = [
    'left'   => ['text-start','me-auto','justify-content-start'],
    'right'  => ['text-end','ms-auto','justify-content-end'],
    'center' => ['text-center','mx-auto','justify-content-center'],
];
list( $text_class, $margin_class, $justify_class ) = $map[ $text_align ];

// ------------- SPLIT LAYOUT -------------
if ( $split_enabled && $bg_type === 'image' ) : ?>
  <section id="hero" class="hero-split hero-split--<?= esc_attr($split_side); ?>">
    <div class="container-fluid p-0 d-flex">
      <?php 
      // según side, texto primero o background primero
      if ( $split_side === 'left' ) {
        // text panel
        ?>
        <div class="hero-split__text col-lg-6 flex-fill d-flex <?= esc_attr($text_class); ?> align-items-center p-5">
          <div class="w-100">
            <?php if ( $subtitle && $tag_pos==='above' ) : ?>
              <?= Template_Helpers::tag_title($htag_sub, esc_html($subtitle), 'hero-subtitle'); ?>
            <?php endif; ?>
            <?php if ( $title ) : ?>
              <?= Template_Helpers::tag_title($htag, esc_html($title), 'hero-title'); ?>
            <?php endif; ?>
            <?php if ( $subtitle && $tag_pos==='below' ) : ?>
              <?= Template_Helpers::tag_title($htag_sub, esc_html($subtitle), 'hero-subtitle'); ?>
            <?php endif; ?>
            <?php if ( $description ) : ?>
              <div class="hero-description"><?php echo wp_kses_post($description); ?></div>
            <?php endif; ?>
            <div class="hero-buttons d-flex gap-2 mt-4 <?= esc_attr($justify_class); ?>">
              <?php if ( $btn_primary ): ?>
                <a href="<?= esc_url($btn_primary['url']); ?>" class="btn btn-xl btn-default">
                  <?= esc_html($btn_primary['title']); ?>
                </a>
              <?php endif; ?>
              <?php if ( $btn_secondary ): ?>
                <a href="<?= esc_url($btn_secondary['url']); ?>" class="btn btn-xl btn-outline">
                  <?= esc_html($btn_secondary['title']); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php
        // image panel
        ?>
        <div class="hero-split__bg flex-fill cover col-lg-6" style="background-image:url('<?= esc_url($img_bg); ?>');"></div>
      <?php
      } else {
        // side === right: primero bg, luego text
        ?>
        <div class="hero-split__bg flex-fill" style="background-image:url('<?= esc_url($img_bg); ?>');"></div>
        <div class="hero-split__text flex-fill d-flex <?= esc_attr($text_class); ?> align-items-center p-5">
          <div class="w-100">
            <?php if ( $subtitle && $tag_pos==='above' ) : ?>
              <?= Template_Helpers::tag_title($htag_sub, esc_html($subtitle), 'hero-subtitle'); ?>
            <?php endif; ?>
            <?php if ( $title ) : ?>
              <?= Template_Helpers::tag_title($htag, esc_html($title), 'hero-title'); ?>
            <?php endif; ?>
            <?php if ( $subtitle && $tag_pos==='below' ) : ?>
              <?= Template_Helpers::tag_title($htag_sub, esc_html($subtitle), 'hero-subtitle'); ?>
            <?php endif; ?>
            <?php if ( $description ) : ?>
              <div class="hero-description"><?php echo wp_kses_post($description); ?></div>
            <?php endif; ?>
            <div class="hero-buttons d-flex gap-2 mt-4 <?= esc_attr($justify_class); ?>">
              <?php if ( $btn_primary['url'] ): ?>
                <a href="<?= esc_url($btn_primary['url']); ?>" class="btn btn-primary">
                  <?= esc_html($btn_primary['title']); ?>
                </a>
              <?php endif; ?>
              <?php if ( $btn_secondary['url'] ): ?>
                <a href="<?= esc_url($btn_secondary['url']); ?>" class="btn btn-outline-light">
                  <?= esc_html($btn_secondary['title']); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>
<?php
// ------------- LAYOUT NORMAL -------------
else : ?>
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
                if ( 'above' === $tag_pos && ! empty( $subtitle ) ) : ?>
                    <div class="hero-subtitle-container">
                        <?= Template_Helpers::tag_title( $htag_sub, esc_html( $subtitle ), 'hero-subtitle fs20 c-white lh160', ''); ?>
                    </div>
                <?php endif; ?>
                
                <?php
                // Mostrar el título si existe
                if ( ! empty( $title ) ) :
                    echo Template_Helpers::tag_title( $htag, esc_html( $title ), 'heading-1 hero-title c-white', '');
                endif;
                ?>

                <?php
                // Si el subtítulo debe aparecer debajo, muéstralo después del título
                if ( 'below' === $tag_pos && ! empty( $subtitle ) ) : ?>
                    <div class="hero-subtitle-container">
                        <?= Template_Helpers::tag_title( $htag_sub, esc_html( $subtitle ), 'hero-subtitle fs20 c-white lh160', ''); ?>
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
<?php endif; ?>