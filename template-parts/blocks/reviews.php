<?php
/*
 * Flexible block: Reviews
 */
$shortcode = get_field('shortcode', 'option');
$link = get_field('link-reviews', 'option');

if(!empty($shortcode)):
?>
<section class="reviews c-bg-light py-5">
    <div class="container">
        <div class="row">
            <?= tagTitle($block['htag_tagline'], $block['tagline'], 'tagline text-center'); ?>
            <?= tagTitle($block['htag_title'], $block['title'], 'heading-3 text-center'); ?>
            <div class="my-4">
                <?= do_shortcode($shortcode); ?>
            </div>
            <?php if (!empty($link)) : ?>
                <div class="d-flex justify-content-center">
                    <a href="<?= esc_url($link['url']); ?>" class="btn btn-md btn-outline" target="_blank">
                        <?= esc_html($link['title']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>