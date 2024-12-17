<?php
/**
 * Carga bloques flexibles dinámicamente desde ACF
 *
 * Se espera que el campo flexible_content contenga la estructura de bloques.
 *
 * @param array $fields Array de campos de ACF que contiene 'flexible_content'.
 * @param string $folder Carpeta donde se encuentran los archivos de los bloques.
 */
function load_flexible_blocks($flexible_content) {
   if (is_array($flexible_content)) {
      foreach ($flexible_content as $block) {
         if (isset($block['acf_fc_layout']) && !empty($block['acf_fc_layout'])) {
            $block_file = 'template-parts/blocks/' . $block['acf_fc_layout'] . '.php';
   
            if (file_exists(get_stylesheet_directory() . '/' . $block_file)) {
               include locate_template($block_file);
            }
         }
      }
   }
}
?>
