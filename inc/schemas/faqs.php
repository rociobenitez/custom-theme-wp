<?php
/**
 * Genera y añade el esquema JSON-LD para FAQPage en los bloques reutilizables de FAQs.
 *
 * Esta función recopila datos dinámicos desde los campos de FAQs configurados con Advanced Custom Fields (ACF)
 * y construye un esquema estructurado siguiendo las directrices de Schema.org para una página de preguntas frecuentes.
 * El esquema JSON-LD resultante mejora el SEO del sitio al proporcionar información detallada y
 * estructurada sobre las preguntas frecuentes a los motores de búsqueda.
 *
 * @hook wp_head Se engancha a la acción 'wp_head' para insertar el script en el encabezado de la página.
 */

 if (!defined('ABSPATH')) {
   exit; // Evita el acceso directo al archivo
}

function generate_schema_faqs() {

   // Obtener la URL actual
   $current_url = get_permalink();

   $faqs = [];

   // Obtener todos los campos de ACF (Bloques flexibles)
   $fields = get_fields();

   if ($fields && isset($fields['flexible_content']) && is_array($fields['flexible_content'])) {
      foreach ($fields['flexible_content'] as $block) {
         // Verificar si el bloque es del tipo 'faqs'
         if (isset($block['acf_fc_layout']) && $block['acf_fc_layout'] === 'faqs') {
            if (isset($block['faq']) && is_array($block['faq'])) {
               $faqs = $block['faq'];
               break; // Asumiendo que solo hay un bloque de FAQs
            }
         }
      }
   }
   
   // Si no hay FAQs, retorna
   if (empty($faqs) || !is_array($faqs)) {
      return;
   }

   $faqItems = [];

   foreach ($faqs as $faq) {
      // AComprobar que cada $faq es un array y tiene 'pregunta' y 'respuesta'
      if (is_array($faq) && !empty($faq['pregunta']) && !empty($faq['respuesta'])) {
         $faqItems[] = [
            "@type" => "Question",
            "name" => wp_kses_post($faq['pregunta']), // Permitir HTML seguro
            "acceptedAnswer" => [
               "@type" => "Answer",
               "text" => wp_strip_all_tags($faq['respuesta']) // Eliminar todas las etiquetas HTML en la respuesta
            ]
         ];
      } else {
         error_log('generate_faqs_schema: FAQ incompleto o no es un array.');
      }
   }

   if (empty($faqItems)) {
      error_log('generate_faqs_schema: No hay items de FAQ válidos.');
      return;
   }

   // Primer script: WebPage
   $webpage_schema = [
      "@context" => "https://schema.org",
      "@type" => "WebPage",
      "@id" => esc_url($current_url),
      "about" => [
         "@id" => "#FAQs"
      ]
   ];

   $json_webpage = json_encode($webpage_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

   // Imprimir el primer script
   if ($json_webpage !== false) {
      echo '<script type="application/ld+json">' . $json_webpage . '</script>';
   }

   // Segundo script: FAQPage
   $faqpage_schema = [
      "@context" => "https://schema.org",
      "@type" => "FAQPage",
      "@id" => "#FAQs",
      "mainEntity" => $faqItems
   ];

   $json_faqpage = json_encode($faqpage_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

   // Imprimir el segundo script
   if ($json_faqpage !== false) {
      echo '<script type="application/ld+json">' . $json_faqpage . '</script>';
   }
}

// Hook para agregar el schema de FAQs
add_action('wp_head', 'generate_schema_faqs');