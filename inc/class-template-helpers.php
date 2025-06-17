<?php
namespace Custom_Theme\Helpers;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Contiene funciones de ayuda para plantillas: Open Graph, encabezados dinámicos,
 * paginación, enlaces de contacto, footer copy, get_component, etc.
 */
class Template_Helpers {

    /**
     * Añade etiquetas Open Graph dinámicas si no hay plugin SEO activo.
     * Enganchado a 'wp_head', prioridad 5.
     */
    public static function add_open_graph_tags() {
        if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
            return;
        }

        $og_title = get_bloginfo( 'name' );
        $og_url   = home_url();
        $og_desc  = get_bloginfo( 'description' );
        $og_image = get_template_directory_uri() . '/assets/favicons/apple-touch-icon.png';

        if ( is_singular() ) {
            global $post;
            $og_title = get_the_title( $post->ID );
            $og_url   = get_permalink( $post->ID );

            if ( has_excerpt( $post->ID ) ) {
                $og_desc = get_the_excerpt( $post->ID );
            } else {
                $og_desc = wp_trim_words( strip_tags( $post->post_content ), 30, '...' );
            }

            if ( has_post_thumbnail( $post->ID ) ) {
                $og_image = get_the_post_thumbnail_url( $post->ID, 'full' );
            }
        }

        printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $og_title ) );
        printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $og_desc ) );
        printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $og_image ) );
        printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $og_url ) );
        printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
        printf( '<meta property="og:type" content="%s" />' . "\n", is_singular() ? 'article' : 'website' );
    }

    /**
     * Genera un encabezado HTML con clases y atributos.
     *
     * @param int    $level 0=h1, 1=h2, 2=h3, 3=p
     * @param string $text
     * @param string $class
     * @param string $class2
     * @return string
     */
    public static function tag_title( $level, $text, $class, $class2 = '' ) {
        $tags = [ 'h1', 'h2', 'h3', 'p' ];
        $level = ( is_numeric( $level ) && $level >= 0 && $level <= 3 ) ? intval( $level ) : 2;
        $tag   = $tags[ $level ];
        return sprintf(
            '<%1$s class="%2$s %3$s">%4$s</%1$s>',
            $tag,
            esc_attr( $class ),
            esc_attr( $class2 ),
            esc_html( $text )
        );
    }

    /**
     * Genera un encabezado HTML para FAQs con id, clases y contenido.
     */
    public static function tag_title_faq( $level, $text, $class, $id = '', $class2 = '', $content = '' ) {
        $tags = [ 'h1', 'h2', 'h3', 'p' ];
        $level = ( is_numeric( $level ) && $level >= 0 && $level <= 3 ) ? intval( $level ) : 2;
        $tag   = $tags[ $level ];
        $id_attr = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        return sprintf(
            '<%1$s%2$s class="%3$s %4$s">%5$s %6$s</%1$s>',
            $tag,
            $id_attr,
            esc_attr( $class ),
            esc_attr( $class2 ),
            esc_html( $text ),
            $content ? ' ' . esc_html( $content ) : ''
        );
    }

    /**
     * Genera un paginador HTML con clases y atributos personalizados.
     */
    public static function custom_pagination( $total_pages = null, $current_page = null ) {
        global $wp_query;
        if ( ! $total_pages ) {
            $total_pages = $wp_query->max_num_pages;
        }
        if ( $total_pages <= 1 ) {
            return;
        }
        if ( ! $current_page ) {
            $current_page = max( 1, get_query_var( 'paged' ) );
        }

        $base = untrailingslashit( strtok( get_pagenum_link( 1 ), '?' ) ) . '/%_%';

        echo paginate_links( [
            'base'      => $base,
            'format'    => 'page/%#%/',
            'current'   => $current_page,
            'total'     => $total_pages,
            'prev_text' => __( '« Anterior', CTM_TEXTDOMAIN ),
            'next_text' => __( 'Siguiente »',  CTM_TEXTDOMAIN ),
            'end_size'  => 1,
            'mid_size'  => 2,
        ] );
    }

    /**
     * Genera un enlace de contacto con icono y texto.
     *
     * @param string $type  'phone', 'whatsapp', 'email'
     * @param string $value El valor correspondiente
     */
    public static function generate_contact_link( $type, $value ) {
        if ( empty( $value ) ) {
            return;
        }

        switch ( $type ) {
            case 'phone':
                $href     = 'tel:' . esc_attr( preg_replace( '/\D/', '', $value ) );
                $icon_url = CTM_THEME_URI . '/assets/img/icons/phone.svg';
                $alt      = __( 'Teléfono', CTM_TEXTDOMAIN );
                $class    = 'contact-link phone';
                break;
            case 'whatsapp':
                $href     = 'https://wa.me/' . esc_attr( preg_replace( '/\D/', '', $value ) );
                $icon_url = CTM_THEME_URI . '/assets/img/icons/whatsapp.svg';
                $alt      = __( 'WhatsApp', CTM_TEXTDOMAIN );
                $class    = 'contact-link whatsapp';
                break;
            case 'email':
                $href     = 'mailto:' . sanitize_email( $value );
                $icon_url = CTM_THEME_URI . '/assets/img/icons/mail.svg';
                $alt      = __( 'Correo electrónico', CTM_TEXTDOMAIN );
                $class    = 'contact-link email';
                break;
            default:
                return;
        }

        printf(
            '<a href="%1$s" class="%2$s d-flex align-items-center me-3" target="_blank" rel="noopener noreferrer">
                <img src="%3$s" alt="%4$s" width="20" height="20" class="me-1">
                <span>%5$s</span>
            </a>',
            esc_url( $href ),
            esc_attr( $class ),
            esc_url( $icon_url ),
            esc_attr( $alt ),
            esc_html( $value )
        );
    }

    /**
     * Obtiene la información de contacto formateada para el footer.
     *
     * @param array $opts Array de opciones generado por generate_footer_options().
     * @return array      Array con claves 'address', 'phone', 'whatsapp', 'email', 'opening_hours'.
     */
    public static function get_contact_info( $opts ) {
        // Dirección completa: unir address, city y postal_code
        $full_address = trim( 
            ( $opts['address'] ?? '' ) .
            ( ! empty( $opts['city'] ) ? ', ' . $opts['city'] : '' ) .
            ( ! empty( $opts['postal_code'] ) ? ', ' . $opts['postal_code'] : '' )
        );

        return array(
            'address' => array(
                'icon' => CTM_THEME_URI . '/assets/img/icons/location.svg',
                'alt'  => __( 'Icono de ubicación', CTM_TEXTDOMAIN ),
                'link' => $opts['google_maps_link'] ?? '',
                'text' => $full_address,
            ),
            'phone' => array(
                'icon' => CTM_THEME_URI . '/assets/img/icons/phone.svg',
                'alt'  => __( 'Icono de teléfono', CTM_TEXTDOMAIN ),
                'link' => ! empty( $opts['phone'] ) ? 'tel:' . preg_replace( '/\D/', '', $opts['phone'] ) : '',
                'text' => $opts['phone'] ?? '',
            ),
            'whatsapp' => array(
                'icon' => CTM_THEME_URI . '/assets/img/icons/whatsapp.svg',
                'alt'  => __( 'Icono de WhatsApp', CTM_TEXTDOMAIN ),
                'link' => ! empty( $opts['whatsapp'] ) ? 'https://wa.me/' . preg_replace( '/\D/', '', $opts['whatsapp'] ) : '',
                'text' => $opts['whatsapp'] ?? '',
            ),
            'email' => array(
                'icon' => CTM_THEME_URI . '/assets/img/icons/mail.svg',
                'alt'  => __( 'Icono de correo electrónico', CTM_TEXTDOMAIN ),
                'link' => ! empty( $opts['email'] ) ? 'mailto:' . sanitize_email( $opts['email'] ) : '',
                'text' => $opts['email'] ?? '',
            ),
            'opening_hours' => $opts['opening_hours'] ?? '',
        );
    }

    /**
     * Genera las opciones del footer desde ACF.
     *
     * @return array
     */
    public static function generate_footer_options() {
        if ( ! function_exists( 'get_fields' ) ) {
            return array();
        }
        $opts = get_fields( 'option' );
        return wp_parse_args( $opts, array(
            'footer_layout'           => '4columns',
            'footer_logo'             => array(),
            'footer_text_below_logo'  => '',
            'footer_contact_title'    => '',
            'footer_column1_title'    => '',
            'footer_column2_title'    => '',
            'whatsapp'                => '',
            'phone'                   => '',
            'email'                   => '',
            'address'                 => '',
            'city'                    => '',
            'postal_code'             => '',
            'google_maps_link'        => '',
            'opening_hours'           => '',
            'kit_digital'             => false,
        ) );
    }


    /**
     * Obtiene las columnas del footer configuradas en ACF.
     *
     * @param array $opts Opciones generadas por generate_footer_options().
     * @return array Array de columnas con tipo, título y contenido.
     */
    public static function get_footer_columns( array $opts ): array {
        $columns    = [];
        $menu_locs  = [ 'footer1', 'footer2' ];

        // 1) Logo 
        $columns[] = [
            'type'    => 'logo',
            'logo_url'=> ! empty( $opts['footer_logo']['url'] )
                        ? esc_url( $opts['footer_logo']['url'] )
                        : get_template_directory_uri() . '/assets/img/logo.svg',
            'text'    => wp_kses_post( $opts['footer_text_below_logo'] ?? '' ),
        ];

        // 2) Columnas de menú
        foreach ( $menu_locs as $loc ) {
            if ( has_nav_menu( $loc ) ) {
                // extraer el índice numérico del loc: "footer1" → 1
                $idx   = (int) filter_var( $loc, FILTER_SANITIZE_NUMBER_INT );
                $title = sanitize_text_field(
                    $opts[ "footer_column{$idx}_title" ] ?? "Menu {$idx}"
                );
                $columns[] = [
                    'type'          => 'menu',
                    'title'         => $title,
                    'menu_location' => $loc,
                ];
            }
        }

        // 3) Columna de contacto
        $contact = self::get_contact_info( $opts );
        $has_contact = array_reduce( $contact, fn( $carry, $c ) =>
            $carry || ( ! empty( $c['text'] ) && ! empty( $c['link'] ) ),
            false
        );

        if ( $has_contact ) {
            $columns[] = [
                'type'    => 'contact',
                'title'   => sanitize_text_field( $opts['footer_contact_title'] ?? '' ),
                'contact' => $contact,
            ];
        }

        return $columns;
    }


    /**
     * Imprime el texto de derechos de autor en el footer.
     */
    public static function footer_copy() {
        $year      = date_i18n( 'Y' );
        $site_name = get_bloginfo( 'name' );
        $link      = 'https://github.com/rociobenitez';
        printf(
            /* translators: %1$s año, %2$s nombre de sitio, %3$s URL */
            '&copy; %1$s %2$s | Desarrollado por <a href="%3$s" target="_blank">Rocío Benítez</a>',
            esc_html( $year ),
            esc_html( $site_name ),
            esc_url( $link )
        );
    }

    /**
     * Incluye un componente de plantilla y pasa variables.
     *
     * @param string $path Ruta relativa al archivo (sin .php)
     * @param array  $args Datos a extraer como variables
     */
    public static function get_component( $path, $args = [] ) {
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args, EXTR_SKIP );
        }
        $template = locate_template( $path . '.php', false, false );
        if ( $template ) {
            include $template;
        }
    }

    /**
     * Recoge y normaliza todo lo necesario para el Page Header.
     *
     * @param array $args Argumentos pasados desde get_template_part().
     * @return array Datos:
     *   - show (bool)
     *   - style (string) 'cols'|'bg_image'|'bg_color'
     *   - text_align (string) clase de alineación
     *   - tagline, htag_tagline
     *   - title, htag_title
     *   - description
     *   - button_text, button_url
     *   - bg_image (url) o bg_color (hex o clase)
     */
    public static function get_page_header_data( array $args = [] ): array {
        $fields          = function_exists('get_fields') ? get_fields() : [];
        $default_img     = get_template_directory_uri() . '/assets/img/default-background.jpg';

        // Valores por defecto
        $data = [
            'htag_tagline' => intval( ! empty( $fields['pageheader_htag_tagline'] ) ?? 3 ),
            'htag_title'   => intval( ! empty( $fields['pageheader_htag_title'] ) ?? 2 ),
            'style'        => ! empty( $args['pageheader_style'] )
                                ?? ! empty( $fields['pageheader_style'] )
                                ?? 'bg_image',
            'description'  => ! empty( $args['pageheader_text'] )
                                ?? ! empty( $fields['pageheader_text'] )
                                ?? '',
            'bg_color'     => ! empty( $args['pageheader_bg_color'] ) 
                                ?? ! empty( $fields['pageheader_bg_color'] )
                                ?? '',
        ];

        // Tagline
        if ( ! empty( $args['tagline'] ) ) {
            $data['tagline'] = $args['tagline'];
        } elseif ( ! empty( $fields['pageheader_tagline'] ) ) {
            $data['tagline'] = $fields['pageheader_tagline'];
        } else {
            $data['tagline'] = '';
        }

        // Título
        if ( ! empty( $args['title'] ) ) {
            $data['title'] = $args['title'];
        } elseif ( ! empty( $fields['pageheader_title'] ) ) {
            $data['title'] = $fields['pageheader_title'];
        } else {
            $data['title'] = get_the_title();
        }

        // Botón
        if ( ! empty( $fields['pageheader_button'] ) 
            && is_array( $fields['pageheader_button'] ) ) {
            $btn = $fields['pageheader_button'];
            $data['button_text'] = $btn['title'] ?? '';
            $data['button_url']  = $btn['url']   ?? '';
        }

        // Imagen destacada = prioridad ACF → thumbnail → default
        if ( ! empty( $fields['pageheader_image']['url'] ) ) {
            $data['bg_image'] = esc_url( $fields['pageheader_image']['url'] );
        } elseif ( has_post_thumbnail() ) {
            $data['bg_image'] = get_the_post_thumbnail_url( null, 'full' );
        } else {
            $data['bg_image'] = $default_img;
        }

        return $data;
    }
}
