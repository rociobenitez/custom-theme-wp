<?php
/**
 * Encola scripts y estilos
 *
 * @package custom_theme
 */

 /**
 * Encola estilos y scripts principales
 */
function custom_theme_enqueue_scripts() {
   // Encolar CSS externos
   wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), null );
   wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), null );
   wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), null );

   // CSS Principal del theme, panel de administración y Woocommerce
   wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array('bootstrap-css'), _THEME_VERSION );
   wp_enqueue_style( 'my-admin-style', get_template_directory_uri() . '/css/admin-styles.css', array('bootstrap-css'), _THEME_VERSION );
   wp_enqueue_style( 'my-admin-style', get_template_directory_uri() . '/css/woocommerce.css', array('bootstrap-css'), _THEME_VERSION );

   // JS Principal del theme
   wp_enqueue_script( 'theme-scripts', get_template_directory_uri() . '/assets/js/main.js', array(), _THEME_VERSION, true );

   // Encolar JS externos
   wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true );

   // (Opcional) AOS
   if ( defined( 'ENABLE_GSAP' ) && ENABLE_GSAP ) {
      wp_enqueue_style( 'aos-css', 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css', array(), null ); 
      wp_enqueue_script( 'aos-js', 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js', array(), null, true );
      wp_add_inline_script( 'aos-js', 'AOS.init();' );
   }
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_scripts' );

/**
 * Cargar Google Fonts de Forma Dinámica
 */
function custom_theme_enqueue_google_fonts() {
   $body_font  = get_theme_mod( 'body_font', 'Roboto' );
   $title_font = get_theme_mod( 'title_font', 'Roboto' );

   // Define las fuentes y sus URL en Google Fonts
   $google_fonts = array(
      'Roboto'            => 'Roboto:wght@400;500;700&display=swap',
      'Open Sans'         => 'Open+Sans:wght@400;600;700&display=swap',
      'Raleway'           => 'Raleway:ital,wght@0,100..900;1,100..900&display=swap',
      'Montserrat'        => 'Montserrat:ital,wght@0,100..900;1,100..900&display=swap',
      'Poppins'           => 'Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
      'Lato'              => 'Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap',
      'Inter'             => 'Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap',
      'Lora'              => 'Lora:ital,wght@0,400..700;1,400..700&display=swap',
      'DM Sans'           => 'DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap',
      'Libre Baskerville' => 'Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap'
   );

   // Encolar las fuentes seleccionadas
   if ( array_key_exists( $body_font, $google_fonts ) ) {
       wp_enqueue_style( 'google-font-body', 'https://fonts.googleapis.com/css2?family=' . $google_fonts[ $body_font ], array(), null );
   }

   if ( array_key_exists( $title_font, $google_fonts ) && $title_font !== $body_font ) {
       wp_enqueue_style( 'google-font-title', 'https://fonts.googleapis.com/css2?family=' . $google_fonts[ $title_font ], array(), null );
   }
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_google_fonts' );


/**
 * Encola el script customizer.js para previsualización en tiempo real
 */
// function theme_enqueue_customizer_preview_js() {
//    wp_enqueue_script( 'theme-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), _THEME_VERSION, true );
// }
// add_action( 'customize_preview_init', 'theme_enqueue_customizer_preview_js' );

/**
 * Encola GSAP y ScrollTrigger (opcional)
 */
function theme_gsap_script() {
   if ( defined( 'ENABLE_GSAP' ) && ENABLE_GSAP ) {
      wp_enqueue_script( 'gsap-js', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js', array(), null, true );
      wp_enqueue_script( 'gsap-st', 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js', array( 'gsap-js' ), null, true );
      wp_enqueue_script( 'animations-js', get_template_directory_uri() . '/assets/js/animations.js', array( 'gsap-js' ), null, true );
   }  
}
add_action( 'wp_enqueue_scripts', 'theme_gsap_script' );