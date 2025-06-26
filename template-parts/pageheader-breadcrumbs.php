<?php
/**
 * Minimal Page Header
 * (Only Breadcrumbs)
 * 
 * @package custom_theme
 */

if ( ! defined('ABSPATH') ) exit;
use Custom_Theme\Helpers\Template_Helpers;

// Obtener datos del encabezado
$ph = Template_Helpers::get_page_header_data( $args );
?>
<section class="page-header-minimal">
    <div class="container">
        <?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
    </div>
</section>
