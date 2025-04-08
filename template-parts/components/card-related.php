<?php
$title = $args['title'] ?: '';
$htag = $args['htag'] ?: 2;
$image_url = $args['image_url'] ?: get_template_directory_uri() . '/assets/img/default-card-background.jpg';
$link = $args['link'] ?: '#';
$cols = $args['cols'] ?: 'col-md-4';
$description = $args['description'] ?: '';
$class_card = $args['class_card'] ?? '';
$card_style = $args['card_style'] ?? 'image';
$background_css = 'linear-gradient(rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.25))';

// Validación del enlace
$link_url = is_array($link) ? esc_url($link['url']) : esc_url($link);
$link_target = is_array($link) && isset($link['target']) ? esc_attr($link['target']) : '_self';
$link_title = is_array($link) && isset($link['title']) ? esc_html($link['title']) : '';
?>

<div class="<?php echo esc_attr( $cols ); ?> mb-4 px-md-2">

    <?php if ($card_style === 'text'): ?>

        <?php if(!empty($title)): ?>
        <div class="text-card">
            <?php echo tagTitle($htag, $title, 'heading-3 text-card-title', ''); ?>
            <?php if(!empty($description)): ?>
                <div class="description"><?php echo $description; ?></div>
            <?php endif; ?>
            <?php if ($link_url): ?>
                <a href="<?php echo $link_url; ?>" class="link-more" target="<?php echo $link_target; ?>">
                    <?php echo $link_title ?: 'Saber más'; ?>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php else: ?>

        <a href="<?php echo $link_url; ?>" class="card related-card text-white cover position-relative h-100 overflow-hidden rounded shadow-sm" target="<?php echo $link_target; ?>" aria-label="<?php echo esc_attr($title); ?>">
         <div class="card-background position-absolute top-0 start-0 w-100 h-100" style="background: <?php echo $background_css; ?>, url('<?php echo esc_url($image_url); ?>'); background-size: cover; background-position: center;" aria-hidden="true"></div>
         
         <div class="card-body position-relative d-flex flex-column justify-content-center align-items-center text-center p-4 h-100">
            <div class="card-content">
               <?php if ($title): ?>
                  <?php echo tagTitle($htag, $title, 'card-title fs20 fw500 mb-0 transition-all', ''); ?>
               <?php endif; ?>
               <?php if ($description): ?>
                  <div class="card-description hidden-description mt-2 small fw300">
                     <?php echo $description; ?>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      </a>

    <?php endif; ?>

</div>
