<?php
/**
 * Sidebar principal
 *
 * @package custom_theme
 */

$class_col = $args['class_col'] ?? 'col-lg-3';
$toc_items = $args['toc_items'] ?? [];
$id_form   = $args['id_form'] ?? 2;
?>
<aside class="<?php echo esc_attr($class_col); ?>">
	<div class="sidebar sticky-top mb-5">
		<?php get_template_part('template-parts/components/cta-sidebar', null, [ 'id_form' => $id_form ]); ?>
		<?php // TOC Sidebar
			get_template_part('template-parts/components/toc', null, [
				'toc_items' => $toc_items,
				'style'     => 'minimal'
			]);
		?>
	</div>
</aside>