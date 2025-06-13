<?php
namespace Custom_Theme\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use WP_Widget;

class AJAX_Tax_Filter extends WP_Widget {
  public function __construct() {
    parent::__construct(
      'ajax_tax_filter',
      __('AJAX Taxonomy Filter', CTM_TEXTDOMAIN),
      ['description'=>__('Checkbox filter by any product taxonomy',CTM_TEXTDOMAIN)]
    );
  }
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters('widget_title',$instance['title']) . $args['after_title'];
    }
    // Obtenemos todas las taxonomías para products
    $taxes = get_object_taxonomies('product','objects');
    foreach( $taxes as $tax => $obj ) {
      if ( ! $obj->public ) continue;
      $terms = get_terms([ 'taxonomy'=>$tax,'hide_empty'=>true ]);
      if ( is_wp_error($terms) || empty($terms) ) continue;
      echo '<div class="filter-group mb-3"><strong>'.esc_html($obj->labels->name).'</strong>';
      foreach( $terms as $term ) {
        printf(
          '<div class="form-check">
             <input class="form-check-input ajax-filter" 
                    type="checkbox" data-tax="%1$s" 
                    data-term="%2$d" id="%1$s_%2$d">
             <label class="form-check-label" for="%1$s_%2$d">%3$s (%4$d)</label>
           </div>',
          esc_attr($tax),
          $term->term_id,
          esc_html($term->name),
          absint($term->count)
        );
      }
      echo '</div>';
    }
    echo $args['after_widget'];
  }
  public function form( $instance ) {
    $title = $instance['title'] ?? __('Filter by',CTM_TEXTDOMAIN);
    ?>
    <p>
      <label><?php esc_html_e('Title:',CTM_TEXTDOMAIN);?></label>
      <input class="widefat" name="<?php echo $this->get_field_name('title');?>"
             value="<?php echo esc_attr($title);?>">
    </p>
    <?php
  }
}
