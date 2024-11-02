<?php 
/**
 * Bloque de Características
 */

// Verifica si el bloque 'features' tiene contenido
if (!empty($bloque['featured_items'])): 
    $background_color = $bloque['background_color'] ?? '#F9F9F9';
    $features = $bloque['featured_items'];
    $feature_count = count($features);
    $col_class = ($feature_count === 4) ? 'col-lg-3 col-md-6' : (($feature_count === 3) ? 'col-lg-4 col-md-6' : 'col-lg-6 col-md-12'); 
?>
<section class="features-block py-5" style="background-color: <?php echo esc_attr($background_color); ?>">
    <div class="container">
        <div class="row text-center text-lg-start">
            <?php foreach ($features as $feature): 
                $title = $feature['title'] ?? '';
                $htag_title = $feature['htag_title'] ?? 'h4';
                $description = $feature['description'] ?? '';
                if (!empty($title)):
                ?>
                <div class="<?php echo esc_attr($col_class); ?> mb-4">
                    <div class="feature-item">
                        <?php echo tagTitle($htag_title, $title, 'heading-3 feature-item-title fs600 c-dark', ''); ?>
                        <div class="feature-description">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    </div>
                </div>
            <?php endif; endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
