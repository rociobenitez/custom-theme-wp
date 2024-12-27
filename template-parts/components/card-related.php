<?php
$title = $args['title'] ?: '';
$htag = $args['htag'] ?: 2;
$image_url = $args['image_url'] ?: get_template_directory_uri() . '/assets/img/default-card-background.jpg';
$link = $args['link'] ?: '#';
$cols = $args['cols'] ?: 'col-md-4';
$description = $args['description'] ?: '';
$class_card = $args['class_card'] ?? '';
$card_style = $args['card_style'] ?? 'image';
$background_css = 'linear-gradient(rgba(16, 42, 67, 0.8), rgba(16, 42, 67, 0.8)';

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

        <a href="<?php echo $link_url; ?>" class="card <?php echo esc_attr($class_card); ?> text-white cover relative overflow-hidden" target="<?php echo $link_target; ?>">
            <div class="card-background position-absolute top-0 start-0 w-100 h-100 cover" style="background: <?php echo $background_css; ?>, url('<?php echo esc_url($image_url); ?>');"></div>
            <div class="card-body relative d-flex flex-column justify-content-center align-items-center text-center p-4">
                <?php if(!empty($title)): ?>
                    <?php echo tagTitle($htag, esc_html($title), 'card-title fs20 fw500', ''); ?>
                <?php endif; ?>
                <span class="card-underline"></span>
                <?php if(!empty($description)): ?>
                    <div class="card-description back-content position-absolute w-100 p-4 c-white">
                        <?php echo $description; ?>
                    </div>
                <?php endif; ?>
            </div>
        </a>

    <?php endif; ?>

</div>
