<?php
/**
 * Flexible Block: Alternado
 * Muestra contenido alternado con imagen y texto.
 */

// Comprobar si hay más de un bloque de contenido
$multiple_slides = count($block['alternado']) > 1;
?>

<section class="alternating-slider position-relative <?= $multiple_slides ? 'c-bg-light' : 'c-bg-white'; ?>">
    <div class="swiper-container <?= $multiple_slides ? 'swiper' : ''; ?>">
        <div class="swiper-wrapper">
            <?php foreach ($block['alternado'] as $item) : ?>
                <?php
                $col1 = $item['image_size'] == 'small' ? 'col-md-9' : 'col-md-6';
                $col2 = $item['image_size'] == 'small' ? 'col-md-3' : 'col-md-6';
                $img_size = $item['image_size'] == 'small' ? 'img-small' : 'img-large';
                $img_position = $item['image_position'] == 'left' ? 'img-left' : 'img-right';
                $position = $item['image_position'] == 'right' ? '' : 'flex-row-reverse';
                $p = $item['image_position'] == 'left' ? 'ps-5' : 'pe-5 ms-auto';
                ?>
                <div class="swiper-slide">
                    <div class="container">
                        <div class="row <?= $position; ?>">
                            <div class="col-12 <?= $col1; ?> justify-content-end">
                                <div class="content alternating-content-<?= $item['image_position']; ?> <?= $p; ?> ">
                                    <?php if (!empty($item['tagline'])) : ?>
                                        <?= tagTitle($item['htag_tagline'], $item['tagline'], 'tagline', ''); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($item['title'])) : ?>
                                        <?= tagTitle($item['htag_title'], $item['title'], 'heading-3', ''); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($item['text'])) : ?>
                                        <?= $item['text']; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 <?= $col2; ?>">
                                <img class="alternating-img <?= $img_size; ?> <?= $img_position; ?>" src="<?= esc_url($item['image']['url']); ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($multiple_slides) : ?>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</section>

<?php if ($multiple_slides) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new Swiper(".alternating-slider .swiper-container", {
                loop: true,
                autoplay: {
                    delay: 10000,
                    disableOnInteraction: false
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true
                },
                effect: "slide",
                speed: 1000 // Velocidad de transición
            });
        });
    </script>
<?php endif; ?>

<style>
.img-small {
  width: 30%;
}
.img-large {
  width: 50%;
}
.img-left {
  left: 0%;
}
.img-right {
  right: 0%;
}
.alternating-img {
  background: linear-gradient(
    0deg,
    rgba(0, 0, 0, 0.2) 0%,
    rgba(0, 0, 0, 0.2) 100%
  );
  object-fit: cover;
}
.alternating-content-right {
  padding-top: 3.75rem;
  padding-bottom: 3.75rem;
}
.swiper .alternating-content-right {
  text-align: end;
}
.alternating-content-left {
  padding-top: 3.75rem;
  padding-bottom: 3.75rem;
  text-align: start;
}

/* `lg` applies to medium devices (tablets, less than 992px) */
@media (max-width: 991.98px) {
  .alternating-slider .content {
    margin-bottom: 2rem;
  }
  .alternating-img {
    height: 37.5rem;
    position: relative;
  }
}

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) {
  .alternating-slider :has(.swiper),
  .alternating-slider .swiper .content,
  .alternating-slider .swiper img {
    height: 600px;
  }
  .alternating-img {
    position: absolute;
    height: 100%;
    object-fit: cover;
  }
  .swiper .alternating-img {
    min-height: 37.5rem;
  }
}
</style>