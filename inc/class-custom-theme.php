<?php
namespace Custom_Theme;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Clase principal del tema Custom_Theme.
 * Aquí se inicializan todos los módulos: setup, assets, imágenes, ACF, etc.
 */
class Custom_Theme {

    /**
     * Inicializa la clase: engancha los métodos a los hooks de WP.
     * Esta función se invoca desde functions.php en after_setup_theme.
     */
    public static function init() {
        // Definir constantes
        self::define_constants();

        // Cargar archivos necesarios (helpers, clases adicionales, etc.).
        self::includes();

        // Configuración básica del theme
        self::setup_theme();

        // Encolar scripts y estilos
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );

        // Registrar sidebars/widgets
        add_action( 'widgets_init', [ __CLASS__, 'register_widgets' ] );

        // Inicializar ACF: Opciones, JSON, Google Maps API
        add_action( 'acf/init', [ __CLASS__, 'acf_init' ] );

        // Limpieza del <head> 
        add_action( 'init', [ __CLASS__, 'cleanup' ], 20 );

        // Registrar tamaños de imagen y filtros de image sizes
        add_action( 'after_setup_theme', [ __CLASS__, 'register_image_sizes' ] );
        add_filter( 'intermediate_image_sizes_advanced', [ __CLASS__, 'disable_unused_default_image_sizes' ] );
        add_filter( 'image_size_names_choose', [ __CLASS__, 'custom_image_size_names' ] );
        add_filter( 'upload_mimes', [ __CLASS__, 'custom_upload_mime_types' ] );

        // ADMIN: Widget de bienvenida, encolar estilos y cambiar pie de página
        add_action( 'wp_dashboard_setup', [ __CLASS__, 'add_custom_dashboard_widget' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
        add_filter( 'admin_footer_text', [ __CLASS__, 'change_footer_admin' ] );

        // Registrar Custom Post Types y taxonomías
        add_action( 'init', [ __CLASS__, 'modify_post_rewrite' ], 20 );
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
        // add_action( 'init', [ __CLASS__, 'fix_blog_pagination' ] );
        // add_filter( 'post_link', [ __CLASS__, 'append_query_string' ], 10, 3 );

        // Permitir file uploads en formulario de edición de post si hay campos <input type="file">
        add_action( 'post_edit_form_tag', [ __CLASS__, 'enable_file_uploads_in_post_forms' ] );

        // Personalización de la página de login
        add_action( 'login_head', [ __CLASS__, 'custom_login_background' ] );

        // Avisos de plugins requeridos
        add_action( 'admin_notices', [ __CLASS__, 'required_plugins_notice' ] );

        // Registrar reglas de reescritura para /equipo/{autor} y redirección 301 de /author/→/equipo/
        add_action( 'init', [ __CLASS__, 'rewrite_rules' ] );
        add_action( 'template_redirect', [ __CLASS__, 'redirect_author_to_equipo' ] );
        add_action( 'after_switch_theme', [ __CLASS__, 'flush_rewrite_rules_on_theme_activation' ] );

        // Hooks específicos de WooCommerce (si está activo)
        if ( class_exists( 'WooCommerce' ) ) {
            add_action( 'after_setup_theme', [ 'Custom_Theme\\WooCommerce\\WC_Support', 'init' ] );
        }

        // Helpers de plantillas
        add_action( 'wp_head', [ 'Custom_Theme\\Helpers\\Template_Helpers', 'add_open_graph_tags' ], 5 );

    }

    /**
     * Define constantes del tema
     */
    private static function define_constants() {
        if ( ! defined( 'CTM_THEME_NAME' ) ) {
            define( 'CTM_THEME_NAME', 'Custom Theme' );
        }
        if ( ! defined( 'CTM_TEXTDOMAIN' ) ) {
            define( 'CTM_TEXTDOMAIN', 'custom_theme' );
        }
        if ( ! defined( 'CTM_COMPANY_NAME' ) ) {
            define( 'CTM_COMPANY_NAME', 'Nombre de la Compañía' );
        }
        if ( ! defined( 'CTM_THEME_VERSION' ) ) {
            define( 'CTM_THEME_VERSION', '1.0.0' );
        }
        if ( ! defined( 'CTM_THEME_PATH' ) ) {
            define( 'CTM_THEME_PATH', get_template_directory() );
        }
        if ( ! defined( 'CTM_THEME_URI' ) ) {
            define( 'CTM_THEME_URI', get_template_directory_uri() );
        }
        if ( ! defined( 'CTM_SITE_LOGO' ) ) {
            define( 'CTM_SITE_LOGO', CTM_THEME_URI . '/assets/img/logo.svg' );
        }
        if ( ! defined( 'CTM_SITE_LOGO_WHITE' ) ) {
            define( 'CTM_SITE_LOGO_WHITE', CTM_THEME_URI . '/assets/img/logo-white.svg' );
        }
        if ( ! defined( 'CTM_LOGIN_BG' ) ) {
            define( 'CTM_LOGIN_BG', CTM_THEME_URI . '/assets/img/login-background.jpg' );
        }
        if ( ! defined( 'CTM_DISABLE_ADMIN_BAR' ) ) {
            define( 'CTM_DISABLE_ADMIN_BAR', false );
        }
        if ( ! defined( 'CTM_ENABLE_GSAP' ) ) {
            define( 'CTM_ENABLE_GSAP', false );
        }
        if ( ! defined( 'CTM_ENABLE_AOS' ) ) {
            define( 'CTM_ENABLE_AOS', false );
        }
    }

    /**
     * Configuración general del tema: add_theme_support, register_nav_menus, etc.
     * Enganchado a 'after_setup_theme'.
     */
    public static function setup_theme() {
        // Soporte para traducciones
        load_theme_textdomain( CTM_TEXTDOMAIN, CTM_THEME_PATH . '/languages' );

        // Añade enlaces RSS 
        add_theme_support( 'automatic-feed-links' );

        // Soporte para título del sitio
        add_theme_support( 'title-tag' );

        // Soporte para miniaturas
        add_theme_support( 'post-thumbnails' );

        // Soporte para formatos de publicación
        add_theme_support( 'post-formats', array( 'aside','gallery','audio','video','image','link','quote','status' ) );

        // Soporte para logotipo personalizado
        add_theme_support( 'custom-logo', array(
            'height'      => 100,
            'width'       => 300,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        // Soporte para fondos personalizados
        add_theme_support( 'custom-background' );

        // HTML5 para formularios, comentarios, galerías
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery' ) );

        // Habilitar widgets selectivos (Customizer)
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Soporte para estilos en editor
        add_theme_support( 'editor-styles' );
        add_editor_style( 'style.css' );

        // Estilos nativos de bloques y align-wide
        add_theme_support( 'wp-block-styles' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'responsive-embeds' );

        // Paleta de colores y tamaños de fuente en el editor
        add_theme_support( 'editor-color-palette', [
            [ 'name' => __( 'Primario', CTM_TEXTDOMAIN ), 'slug' => 'primary', 'color' => '#ff0000' ],
            [ 'name' => __( 'Secundario', CTM_TEXTDOMAIN ), 'slug' => 'secondary', 'color' => '#00ff00' ],
            [ 'name' => __( 'Éxito', CTM_TEXTDOMAIN ), 'slug' => 'success', 'color' => '#00ff00' ],
            [ 'name' => __( 'Peligro', CTM_TEXTDOMAIN ), 'slug' => 'danger', 'color' => '#ff0000' ],
            [ 'name' => __( 'Advertencia', CTM_TEXTDOMAIN ), 'slug' => 'warning', 'color' => '#ffff00' ],
            [ 'name' => __( 'Info', CTM_TEXTDOMAIN ), 'slug' => 'info', 'color' => '#0000ff' ],
            [ 'name' => __( 'Claro', CTM_TEXTDOMAIN ), 'slug' => 'light', 'color' => '#f8f9fa' ],
            [ 'name' => __( 'Oscuro', CTM_TEXTDOMAIN ), 'slug' => 'dark', 'color' => '#343a40' ],
            // … más colores …
        ] );
        add_theme_support( 'editor-font-sizes', [
            [ 'name' => __('Pequeño', CTM_TEXTDOMAIN), 'size' => 12, 'slug' => 'small' ],
            [ 'name' => __('Normal',  CTM_TEXTDOMAIN), 'size' => 16, 'slug' => 'normal' ],
            [ 'name' => __('Grande',  CTM_TEXTDOMAIN), 'size' => 36, 'slug' => 'large' ],
        ] );

        // Registro de menús
        register_nav_menus( array(
            'main'    => esc_html__( 'Menú Principal', CTM_TEXTDOMAIN ),
            'footer1' => esc_html__( 'Menú Footer 1', CTM_TEXTDOMAIN ),
            'footer2' => esc_html__( 'Menú Footer 2', CTM_TEXTDOMAIN ),
            'legal'   => esc_html__( 'Menú Legales', CTM_TEXTDOMAIN ),
        ) );

        if ( CTM_DISABLE_ADMIN_BAR ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }
    }

    /**
     * Encolar scripts y estilos en el frontend.
     * Enganchado a 'wp_enqueue_scripts'.
     */
    public static function enqueue_assets() {
        // CSS externos
        wp_enqueue_style(
            'bootstrap-css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
            [],
            null
        );
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            [],
            null
        );
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
            [],
            null
        );

        // CSS del theme
        wp_enqueue_style(
            'theme-style',
            get_stylesheet_uri(), // style.css principal
            [ 'bootstrap-css' ],  // dependencias
            CTM_THEME_VERSION
        );

        // CSS personalizado de Woocommerce (si está activo)
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style(
                'woocommerce-style',
                CTM_THEME_URI . '/assets/css/woocommerce.css',
                [ 'bootstrap-css' ],
                CTM_THEME_VERSION
            );
        }

        // Scripts externos (Bootstrap Bundle)
        wp_enqueue_script(
            'bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            [],
            null,
            true
        );

        // JS principal del theme
        wp_enqueue_script(
            'theme-scripts',
            CTM_THEME_URI . '/assets/js/main.js',
            [],
            CTM_THEME_VERSION,
            true
        );

        // Carga condicional de AOS
        if ( defined( 'CTM_ENABLE_AOS' ) && CTM_ENABLE_AOS ) {
            wp_enqueue_style(
                'aos-css',
                'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css',
                [],
                null
            );
            wp_enqueue_script(
                'aos-js',
                'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js',
                [],
                null,
                true
            );
            wp_add_inline_script( 'aos-js', 'AOS.init();' );
        }

        // Carga condicional de GSAP
        if ( defined( 'CTM_ENABLE_GSAP' ) && CTM_ENABLE_GSAP ) {
            wp_enqueue_script(
                'gsap-js',
                'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
                [],
                null,
                true
            );
            wp_enqueue_script(
                'gsap-st',
                'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
                [ 'gsap-js' ],
                null,
                true
            );
            wp_enqueue_script(
                'animations-js',
                CTM_THEME_URI . '/assets/js/animations.js',
                [ 'gsap-js' ],
                null,
                true
            );
        }
    }

    /**
     * Registrar sidebars o widgets.
     * Enganchado a 'widgets_init'.
     */
    public static function register_widgets() {
        // Ejemplo de registro de sidebar
        register_sidebar( [
            'name'          => __( 'Sidebar Principal', CTM_TEXTDOMAIN ),
            'id'            => 'sidebar-main',
            'description'   => __( 'Zona de widgets para la barra lateral principal.', CTM_TEXTDOMAIN ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ] );
    }

    /**
     * Inicializar ACF: registro de opciones, json save/load, API Key Google Maps, etc.
     * Enganchado a 'acf/init'.
     */
    public static function acf_init() {
        // Registrar Página de Opciones ACF
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page( [
                'page_title' => esc_html__( 'Opciones Generales', CTM_TEXTDOMAIN ),
                'menu_title' => esc_html__( 'Opciones', CTM_TEXTDOMAIN ),
                'menu_slug'  => 'theme-general-settings',
                'capability' => 'manage_options',
                'redirect'   => false,
            ] );
        }

        // Carga/Sincronización de acf-json
        add_filter( 'acf/settings/save_json', [ __CLASS__, 'acf_json_save_point' ] );
        add_filter( 'acf/settings/load_json', [ __CLASS__, 'acf_json_load_point' ] );
        add_action( 'admin_notices', [ __CLASS__, 'acf_options_update_message' ] );

        // API Key de Google Maps
        if ( function_exists( 'get_field' ) ) {
            $google_api_key = get_field( 'google_maps_api', 'option' );
            if ( $google_api_key ) {
                acf_update_setting( 'google_api_key', esc_attr( $google_api_key ) );
            }
        }
    }

    /**
     * Filtra el path donde ACF guarda archivos JSON.
     */
    public static function acf_json_save_point( $path ) {
        return CTM_THEME_PATH . '/acf-json';
    }

    /**
     * Filtra el path donde ACF carga archivos JSON.
     */
    public static function acf_json_load_point( $paths ) {
        unset( $paths[0] );
        $paths[] = CTM_THEME_PATH . '/acf-json';
        return $paths;
    }

    /**
     * Muestra mensaje de "Opciones actualizadas" tras guardar ACF Options.
     */
    public static function acf_options_update_message() {
        if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
            add_settings_error(
                'acf_options',
                'acf_options_updated',
                esc_html__( 'Opciones actualizadas', CTM_TEXTDOMAIN ),
                'updated'
            );
            settings_errors( 'acf_options' );
        }
    }

    /**
     * Función para limpieza: elimina acciones, emojis, WP-Ressource-Hints, etc.
     * Enganchado a 'init' con prioridad 20.
     */
    public static function cleanup() {
        // 1. Eliminación de metadatos básicos
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'wp_generator' );

        // 2. Emoji
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );

        // 3. Windows Live Writer
        remove_action( 'wp_head', 'wlwmanifest_link' );

        // 4. WP Resource Hints
        remove_action( 'wp_head', 'wp_resource_hints', 2 );

        // 5. REST API link tags (si no las utilizas en frontend)
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    }

    /**
     * Registra tamaños de imagen personalizados.
     * Enganchado a 'after_setup_theme'.
     */
    public static function register_image_sizes() {
        add_image_size( 'ctm_block_small', 480, 400, false );
        add_image_size( 'ctm_block_medium', 768, 400, false );
        add_image_size( 'ctm_block_large', 1200, 400, false );
        add_image_size( 'ctm_gallery_thumbnail', 400, 400, true );
        add_image_size( 'ctm_card_background', 520, 260, false );
    }

    /**
     * Elimina tamaños automáticos de WP que no se utilizan en el theme.
     * Enganchado a 'intermediate_image_sizes_advanced'.
     */
    public static function disable_unused_default_image_sizes( $sizes ) {
        if ( isset( $sizes['medium_large'] ) ) {
            unset( $sizes['medium_large'] );
        }
        if ( isset( $sizes['1536x1536'] ) ) {
            unset( $sizes['1536x1536'] );
        }
        if ( isset( $sizes['2048x2048'] ) ) {
            unset( $sizes['2048x2048'] );
        }
        return $sizes;
    }

    /**
     * Agrega nombres amigables al selector de tamaños de imagen en el editor.
     * Enganchado a 'image_size_names_choose'.
     */
    public static function custom_image_size_names( $sizes ) {
        return array_merge(
            $sizes,
            [
                'ctm_block_small'       => __( 'Bloque pequeño (480×400)', CTM_TEXTDOMAIN ),
                'ctm_block_medium'      => __( 'Bloque mediano (768×400)', CTM_TEXTDOMAIN ),
                'ctm_block_large'       => __( 'Bloque grande (1200×400)', CTM_TEXTDOMAIN ),
                'ctm_gallery_thumbnail' => __( 'Miniatura galería (400×400)', CTM_TEXTDOMAIN ),
                'ctm_card_background'   => __( 'Fondo de card (520×260)', CTM_TEXTDOMAIN ),
            ]
        );
    }

    /**
     * Permite formatos de imagen adicionales (SVG, WebP).
     * Enganchado a 'upload_mimes'.
     */
    public static function custom_upload_mime_types( $mimes ) {
        $mimes['svg']  = 'image/svg+xml';
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }

    /**
     * Agrega un widget personalizado al Dashboard con logo y nombre de compañía.
     */
    public static function add_custom_dashboard_widget() {
        wp_add_dashboard_widget(
            'ctm_custom_dashboard_widget',
            esc_html__( '¡Bienvenido!', CTM_TEXTDOMAIN ),
            [ __CLASS__, 'render_custom_dashboard_widget' ]
        );
    }

    /**
     * Renderiza el contenido del widget de bienvenida.
     */
    public static function render_custom_dashboard_widget() {
        // Mostrar logo si existe
        if ( defined( 'CTM_SITE_LOGO' ) ) {
            $logo_path = str_replace( CTM_THEME_URI, CTM_THEME_PATH, CTM_SITE_LOGO );
            if ( file_exists( $logo_path ) ) {
                echo '<img src="' . esc_url( CTM_SITE_LOGO ) . '" alt="' . esc_attr__( 'Logo', CTM_TEXTDOMAIN ) . '" style="max-width:150px;margin-bottom:0.5rem;" />';
            }
        }
        echo '<h3>' . esc_html( CTM_COMPANY_NAME ) . '</h3>';
        echo '<p>' . esc_html__( 'Sistema de Gestión de Contenidos', CTM_TEXTDOMAIN ) . '</p>';
    }

    /**
     * Encola estilos personalizados para el área de administración.
     */
    public static function enqueue_admin_assets() {
        $css_path = CTM_THEME_PATH . '/assets/css/admin-styles.css';
        if ( file_exists( $css_path ) ) {
            wp_enqueue_style(
                'ctm-admin-styles',
                CTM_THEME_URI . '/assets/css/admin-styles.css',
                [],
                CTM_THEME_VERSION
            );
        }
    }

    /**
     * Cambia el texto del pie de página en el área de administración.
     */
    public static function change_footer_admin() {
        $year = date_i18n( 'Y' );
        printf(
            /* translators: %1$s año, %2$s nombre de compañía */
            esc_html__( '&copy; %1$s %2$s. Todos los derechos reservados.', CTM_TEXTDOMAIN ),
            esc_html( $year ),
            esc_html( CTM_COMPANY_NAME )
        );
    }

    /**
     * Ajusta la propiedad "rewrite" del CPT nativo "post" para que use
     * "/blog/{post_name}" en lugar del slug por defecto.
     * Enganchado a 'init'.
     */
    public static function modify_post_rewrite() {
        global $wp_post_types;

        if ( isset( $wp_post_types['post'] ) ) {
            $wp_post_types['post']->rewrite = [
                'slug'       => 'blog',
                'with_front' => false,
            ];
            $wp_post_types['post']->has_archive = false;
        }
    }

    /**
     * Registrar Custom Post Types.
     * Enganchado a 'init'.
     */
    public static function register_cpt() {
        // CPT "trabajos"
        $labels_trabajos = [
            'name'                  => __( 'Trabajos', CTM_TEXTDOMAIN ),
            'singular_name'         => __( 'Trabajo', CTM_TEXTDOMAIN ),
            'menu_name'             => __( 'Trabajos', CTM_TEXTDOMAIN ),
            'parent_item_colon'     => __( 'Trabajo padre:', CTM_TEXTDOMAIN ),
            'view_item'             => __( 'Ver Trabajo', CTM_TEXTDOMAIN ),
            'all_items'             => __( 'Ver Todos', CTM_TEXTDOMAIN ),
            'add_new'               => __( 'Nuevo Trabajo', CTM_TEXTDOMAIN ),
            'add_new_item'          => __( 'Añadir Nuevo Trabajo', CTM_TEXTDOMAIN ),
            'edit_item'             => __( 'Editar Trabajo', CTM_TEXTDOMAIN ),
            'update_item'           => __( 'Actualizar Trabajo', CTM_TEXTDOMAIN ),
            'search_items'          => __( 'Buscar Trabajo', CTM_TEXTDOMAIN ),
            'not_found'             => __( 'No se encontraron trabajos', CTM_TEXTDOMAIN ),
            'not_found_in_trash'    => __( 'No se encontraron trabajos en la papelera', CTM_TEXTDOMAIN ),
            'featured_image'        => __( 'Imagen destacada', CTM_TEXTDOMAIN ),
            'set_featured_image'    => __( 'Seleccionar imagen destacada', CTM_TEXTDOMAIN ),
            'remove_featured_image' => __( 'Eliminar imagen destacada', CTM_TEXTDOMAIN ),
            'use_featured_image'    => __( 'Usar como imagen destacada', CTM_TEXTDOMAIN ),
            'insert_into_item'      => __( 'Insertar en trabajo', CTM_TEXTDOMAIN ),
            'uploaded_to_this_item' => __( 'Subido a este trabajo', CTM_TEXTDOMAIN ),
            'items_list'            => __( 'Lista de trabajos', CTM_TEXTDOMAIN ),
            'items_list_navigation' => __( 'Navegación en lista de trabajos', CTM_TEXTDOMAIN ),
            'filter_items_list'     => __( 'Filtrar lista de trabajos', CTM_TEXTDOMAIN ),
        ];

        register_post_type( 'trabajos', [
            'label'               => __('Trabajos', CTM_TEXTDOMAIN),
            'description'         => __('Custom Post Type para trabajos', CTM_TEXTDOMAIN),
            'labels'              => $labels_trabajos,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => [ 'slug' => 'trabajos', 'with_front' => false ],
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-universal-access',
            'supports'            => [ 'title', 'editor', 'thumbnail', 'revisions', 'excerpt' ],
            'show_in_rest'        => true,
        ] );

        // Taxonomía "tipo-trabajo" para "trabajos"
        $labels_tipo = [
            'name'              => __( 'Tipos de Trabajo', CTM_TEXTDOMAIN ),
            'singular_name'     => __( 'Tipo de Trabajo', CTM_TEXTDOMAIN ),
            'search_items'      => __( 'Buscar Tipo de Trabajo', CTM_TEXTDOMAIN ),
            'all_items'         => __( 'Todos los Tipos de Trabajo', CTM_TEXTDOMAIN ),
            'parent_item'       => __( 'Tipo de Trabajo Padre', CTM_TEXTDOMAIN ),
            'parent_item_colon' => __( 'Tipo de Trabajo Padre:', CTM_TEXTDOMAIN ),
            'edit_item'         => __( 'Editar Tipo de Trabajo', CTM_TEXTDOMAIN ),
            'update_item'       => __( 'Actualizar Tipo de Trabajo', CTM_TEXTDOMAIN ),
            'add_new_item'      => __( 'Añadir Nuevo Tipo de Trabajo', CTM_TEXTDOMAIN ),
            'new_item_name'     => __( 'Nuevo Nombre de Tipo de Trabajo', CTM_TEXTDOMAIN ),
            'menu_name'         => __( 'Tipos de Trabajo', CTM_TEXTDOMAIN ),
        ];

        register_taxonomy( 'tipo-trabajo', 'trabajos', [
            'hierarchical'      => true,
            'labels'            => $labels_tipo,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'tipo-trabajo' ],
        ] );

        // --- Opcional: registrar más CPTs o taxonomías ---
        // ejemplo: Servicios
        // register_post_type( 'servicios', [ … ] );
        // register_taxonomy( 'tipo-servicio', 'servicios', [ … ] );
    }

    /**
     * Ajuste de paginación en el blog (rewrite rules).
     */
    // public static function fix_blog_pagination() {
    //     add_rewrite_rule(
    //         'blog/page/([0-9]+)/?$',
    //         'index.php?pagename=blog&paged=$matches[1]',
    //         'top'
    //     );
    //     add_rewrite_rule(
    //         'blog/([^/]*)$',
    //         'index.php?name=$matches[1]',
    //         'top'
    //     );
    //     add_rewrite_tag( '%blog%', '([^/]*)' );
    // }

    /**
     * Permitir archivos adjuntos en formularios de edición de post.
     */
    public static function enable_file_uploads_in_post_forms() {
        echo ' enctype="multipart/form-data"';
    }

    /**
     * Ajustar el permalink de las entradas de blog para que vayan a /blog/…
     */
    // public static function append_query_string( $url, $post, $leavename ) {
    //     if ( 'post' === $post->post_type ) {
    //         return home_url( user_trailingslashit( "blog/{$post->post_name}" ) );
    //     }
    //     return $url;
    // }

    /**
     * Personalizaciones para la página de login.
     * Enganchado a 'login_head'.
     */
    public static function custom_login_background() {
        // Comprobar si la ruta a CTM_LOGIN_BG apunta a un fichero real
        $login_bg_url = '';
        if ( defined( 'CTM_LOGIN_BG' ) ) {
            $bg_path = str_replace( CTM_THEME_URI, CTM_THEME_PATH, CTM_LOGIN_BG );
            if ( file_exists( $bg_path ) ) {
                $login_bg_url = esc_url( CTM_LOGIN_BG );
            }
        }

        // Comprobar si existe el logo blanco
        $logo_url_white = '';
        if ( defined( 'CTM_SITE_LOGO_WHITE' ) ) {
            $logo_path = str_replace( CTM_THEME_URI, CTM_THEME_PATH, CTM_SITE_LOGO_WHITE );
            if ( file_exists( $logo_path ) ) {
                $logo_url_white = esc_url( CTM_SITE_LOGO_WHITE );
            }
        }
        ?>
        <style type="text/css">
            <?php if ( $login_bg_url ) : ?>
            body.login {
                background-image: linear-gradient( to bottom, rgba(0,0,0,0.55), rgba(0,0,0,0.35) ),
                                url("<?php echo $login_bg_url; ?>");
                background-repeat: no-repeat;
                background-size: cover;
            }
            <?php endif; ?>

            <?php if ( $logo_url_white ) : ?>
            .login h1 a {
                background-image: url("<?php echo $logo_url_white; ?>");
                background-size: 200px auto;
                width: 100%;
                height: 40px;
            }
            <?php endif; ?>
            /* Enlaces e iconos en blanco */
            #login a,
            .dashicons-translation:before {
                color: #fff !important;
            }
            #login a:hover {
                text-decoration: underline;
            }
            /* Botón primario personalizado */
            .wp-core-ui .button-primary {
                background-color: #0c0d19;
                border-color: #0c0d19;
                color: #fff;
            }
            .wp-core-ui .button-primary:hover {
                background-color: rgba(12,13,25,0.85);
                border-color: rgba(12,13,25,0.85);
                color: #fff;
            }
            /* Ocultar selector de idioma */
            .login .language-switcher {
                display: none;
            }
        </style>
        <?php
    }

    /**
     * Agrega regla para /equipo/{autor} → author_name={autor}.
     */
    public static function rewrite_rules() {
        add_rewrite_rule(
            '^equipo/([^/]+)/?$',
            'index.php?author_name=$matches[1]',
            'top'
        );
    }

    /**
     * Redirige /author/{autor} → /equipo/{autor} (301)
     * Evita bucles comprobando que la URL no contenga ya /equipo/.
     */
    public static function redirect_author_to_equipo() {
        if ( is_author() && ! is_admin() ) {
            $author_id   = get_queried_object_id();
            $author_slug = get_the_author_meta( 'user_nicename', $author_id );
            $request_uri = $_SERVER['REQUEST_URI'] ?? '';
            // Si la URL actual contiene "/author/" y no contiene "/equipo/"
            if ( false !== strpos( $request_uri, '/author/' ) && false === strpos( $request_uri, '/equipo/' ) ) {
                $new_url = home_url( '/equipo/' . $author_slug );
                wp_safe_redirect( $new_url, 301 );
                exit;
            }
        }
    }

    /**
     * Vacuum rules: registrar nuevamente las reglas y vaciar el cache de URLs.
     */
    public static function flush_rewrite_rules_on_theme_activation() {
        self::modify_post_rewrite();
        self::register_cpt();
        self::rewrite_rules();
        flush_rewrite_rules();
    }

    /**
     * Muestra avisos en el panel de administración si faltan plugins requeridos (ej. ACF Pro).
     * Enganchado a 'admin_notices'.
     */
    public static function required_plugins_notice() {
        if ( ! is_admin() ) {
            return;
        }
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        // Verificar ACF Pro
        if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p>' . esc_html__( 'Para que este tema funcione correctamente, instala y activa el plugin Advanced Custom Fields PRO.', CTM_TEXTDOMAIN ) . '</p>';
            echo '</div>';
        }
    }

    /**
     * Incluye (require) todos los archivos de /inc necesarios.
     */
    private static function includes() {
        $files = [
            '/inc/class-theme-options.php',       // Helper para obtener opciones ACF
            '/inc/class-template-helpers.php',    // Helper para plantillas
            '/inc/class-social-links.php',        // Helper para redes sociales
            '/inc/class-bs-navwalker.php',        // Walker para Bootstrap nav
            '/inc/class-woocommerce-support.php', // Soporte WooCommerce
            // ... más helpers aquí
        ];
        foreach ( $files as $file ) {
            $path = CTM_THEME_PATH . $file;
            if ( file_exists( $path ) ) {
                require_once $path;
            }
        }
    }
}
