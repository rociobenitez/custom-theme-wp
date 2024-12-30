<?php
/**
 * Bloque de Tarifas (Rates)
 * Generado dinámicamente desde los campos ACF
 */

// Obtener los campos del bloque de tarifas
$tagline       = $block['tagline'] ?? '';
$htag_tagline  = $block['htag_tagline'] ?? 3;
$title         = $block['title'] ?? '';
$htag_title    = $block['htag_title'] ?? 2;
$text          = $block['text'] ?? '';
$bg_color      = !empty($block['bg_color']) ? $block['bg_color'] : 'bg-white';
$show_heading  = $block['show_heading'] ?? false;
$rates_block   = $block['section_rates']; 
var_dump($block['show_heading']);

if ($rates_block) : ?>
   <section class="rates-block py-5 <?php echo esc_attr($block['bg_color']); ?>">
      <div class="container">
         <?php if ($show_heading) :
            get_component('template-parts/components/section-heading', [
               'show_heading'     => $show_heading,
               'tagline'          => $tagline,
               'htag_tagline'     => $htag_tagline,
               'title'            => $title,
               'htag_title'       => $htag_title,
               'text'             => $text,
               'block_align'      => 'justify-content-center align-items-center',
               'container_class'  => 'col-11 col-lg-8',
               'text_align_class' => 'text-center'
            ]);
         endif; ?>

         <div class="rates-list">
            <?php foreach ($rates_block as $rate_section) : ?>
               <div class="rate-section mb-5">
                  <?php echo tagTitle($rate_section['htag_title'], esc_html($rate_section['title']), 'rate-title mb-4 fs32 fw500 c-primary', ''); ?>

                  <?php if (!empty($rate_section['description'])) : ?>
                     <div class="rate-description mb-3">
                        <?php echo wp_kses_post($rate_section['description']); ?>
                     </div>
                  <?php endif; ?>

                  <div class="services-list row">
                     <?php foreach ($rate_section['service'] as $service) : ?>
                        <div class="service col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                           <div class="card h-100 border border-light-subtle shadow-sm">
                              <?php if (!empty($service['image'])) : ?>
                                 <img src="<?php echo esc_url($service['image']['url']); ?>"
                                    alt="<?php echo esc_attr($service['image']['alt']); ?>"
                                    class="card-img-top fit">
                              <?php endif; ?>
                              <div class="card-body">
                                 <p class="service-title fs18 fw500 c-black mb-0">
                                    <?php echo esc_html($service['name']); ?>
                                 </p>
                                 <?php if ($service['unique_price']) : ?>
                                    <?php if (!empty($service['price'])) : ?>
                                       <p class="service-price fs22 fw500 c-primary mb-2">
                                          <?php echo esc_html($service['price']); ?>€
                                       </p>
                                    <?php endif; ?>
                                 <?php else : ?>
                                    <ul class="service-prices list-unstyled mb-0">
                                       <?php foreach ($service['prices'] as $price) : ?>
                                          <li class="d-flex justify-content-between align-items-center">
                                             <span class="text-muted fs15"><?php echo esc_html($price['description']); ?></span>
                                             <?php if (!empty($price['price'])) : ?>
                                                <span class="fw500 fs22 c-primary"><?php echo esc_html($price['price']); ?>€</span>
                                             <?php endif; ?>
                                          </li>
                                       <?php endforeach; ?>
                                    </ul>
                                 <?php endif; ?>
                                 <?php if (!empty($service['description'])) : ?>
                                    <p class="service-description text-muted fs15 mt-2 mb-0">
                                       <?php echo esc_html($service['description']); ?>
                                    </p>
                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>
<?php endif; ?>
