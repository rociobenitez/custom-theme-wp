<?php
/**
 * Bloque de Preguntas Frecuentes (FAQs)
 *
 * Este bloque renderiza una lista de preguntas y respuestas frecuentes en formato acordeón.
 *
 * Variables esperadas:
 * - $block['faq']: Array con las preguntas y respuestas (cada elemento tiene 'question' y 'answer').
 * - $block['title']: Título principal del bloque.
 * - $block['htag']: Etiqueta HTML para el título (h1, h2, h3, etc.).
 * - $block['tagline']: Subtítulo opcional (tagline).
 * - $block['htag_tagline']: Etiqueta HTML para el tagline.
 * - $block['title_class']: Clases CSS para el título principal.
 * - $block['tagline_class']: Clases CSS para el tagline.
 *
 * Componentes reutilizables:
 * - `section-heading`: Componente que gestiona la cabecera de la sección (título y tagline).
 */

$faqs          = $block['faq'] ?? []; // Array de FAQs
$tagline       = $block['tagline'] ?? 'Resolvemos tus dudas'; // Tagline por defecto
$htag_tagline  = $block['htag_tagline'] ?? 3;  // Etiqueta HTML del tagline (p por defecto)
$tagline_class = $block['tagline_class'] ?? 'tagline';
$title         = $block['title'] ?? 'Preguntas Frecuentes'; // Título por defecto
$htag          = $block['htag'] ?? 1;  // Etiqueta HTML del título (h2 por defecto)
$title_class   = $block['title_class'] ?? 'heading-3';

if (!empty($faqs)) : ?>
<section class="faqs-section my-5">
   <div class="container">
      <?php // Cabecera de la sección
      get_component('template-parts/components/section-heading', [
         'text_align_class' => 'text-center', // Alineación del texto
         'show_heading'     => true,
         'tagline'          => $tagline,
         'htag_tagline'     => $htag_tagline,
         'title'            => $title,
         'htag_title'       => $htag,
         'text'             => null,
         'cta_button'       => null,
      ]);
      ?>
      <div class="accordion mt-4" id="faqAccordion">
         <?php foreach ($faqs as $index => $faq): 
            // Generar IDs únicos para cada elemento del acordeón
            $heading_id = "heading" . $index;
            $collapse_id = "collapse" . $index;
            ?>
            <div class="accordion-item">
               <h3 class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                  <button class="accordion-button fw500 collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?php echo esc_attr($collapse_id); ?>"
                        aria-expanded="false"
                        aria-controls="<?php echo esc_attr($collapse_id); ?>">
                     <?php echo esc_html($faq['question']); ?>
                  </button>
               </h3>
               <div id="<?php echo esc_attr($collapse_id); ?>" 
                     class="accordion-collapse collapse"
                     aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                     data-bs-parent="#faqAccordion">
                  <div class="accordion-body">
                      <?php echo $faq['answer']; ?>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   </div>
</section>
<?php endif; ?>
