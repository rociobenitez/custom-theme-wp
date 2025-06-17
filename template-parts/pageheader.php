<?php
/**
 * Page Header
 * 
 * @package custom_theme
 */

if ( ! defined('ABSPATH') ) exit;
use Custom_Theme\Helpers\Template_Helpers;

// Obtener datos del encabezado
$ph = Template_Helpers::get_page_header_data( $args );
?>

<?php if ( $ph['style'] === 'cols' ) : ?>
  <section class="page-header-split d-flex position-relative" style="<?= $ph['bg_color'] ? "background-color:{$ph['bg_color']}" : '' ?>">
    <div class="content-wrapper d-flex align-items-center py-5 w-100">
      <div class="container">
        <div class="row">
          <div class="col-12 col-lg-5">
            <?php if ( $ph['tagline'] ) echo Template_Helpers::tag_title( $ph['htag_tagline'], $ph['tagline'], 'tagline' ); ?>
            <?php echo Template_Helpers::tag_title( $ph['htag_title'], $ph['title'], 'title heading-2 mb-0' ); ?>
            <?php if ( $ph['description'] ): ?>
               <div class="page-header-text mb-4">
                  <?= wp_kses_post( wpautop( $ph['description'] ) ) ?>
               </div>
            <?php endif; ?>
            <?php if ( $ph['button_text'] && $ph['button_url'] ): ?>
              <a href="<?= esc_url( $ph['button_url'] ) ?>" class="btn btn-lg btn-primary">
                <?= esc_html( $ph['button_text'] ) ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="image-wrapper position-absolute top-0 end-0 h-100 w-50 cover"
         style="background-image: url('<?= esc_url( $ph['bg_image'] ) ?>');">
    </div>
  </section>

<?php elseif ( $ph['style'] === 'bg_color' ) : ?>
  <section class="page-header py-5 text-center" 
           style="background-color:<?= esc_attr( $ph['bg_color'] ) ?>;">
    <div class="container">
      <?php echo Template_Helpers::tag_title( $ph['htag_title'], $ph['title'], 'title mb-0' ); ?>
      <?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
    </div>
  </section>

<?php else: /* bg_image */ ?>
  <section class="page-header cover py-5 text-center"
           style="background:
             linear-gradient(0deg, rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
             url('<?= esc_url( $ph['bg_image'] ) ?>');">
    <div class="container">
      <?php echo Template_Helpers::tag_title( $ph['htag_title'], $ph['title'], 'title mb-0 text-white' ); ?>
      <?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
    </div>
  </section>
<?php endif; ?>
