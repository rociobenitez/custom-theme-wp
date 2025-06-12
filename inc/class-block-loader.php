<?php
/**
 * BlockLoader: carga cada ACF Flexible Content como template-part
 */

if ( ! defined('ABSPATH') ) {
  exit;
}

class BlockLoader {

  /**
   * Carga los bloques flexibles pasados
   *
   * @param array $blocks Array de subfields de flexible_content
   */
  public static function load( array $blocks ): void {
    foreach ( $blocks as $block ) {
      $layout = $block['acf_fc_layout'] ?? '';
      if ( ! $layout ) {
        continue;
      }

      // normalizar nombre de template: 'hero' → 'template-parts/blocks/hero.php'
      $slug = sanitize_file_name( $layout );
      $path = "template-parts/blocks/{$slug}.php";

      if ( locate_template( $path ) ) {
        get_template_part( "template-parts/blocks/{$slug}", null, $block );
      }
    }
  }
}
