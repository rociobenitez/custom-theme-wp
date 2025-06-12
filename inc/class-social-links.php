<?php
namespace Custom_Theme\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Social_Links {

    private static $icons = [
        'facebook'  => 'facebook.svg',
        'instagram' => 'instagram.svg',
        'linkedin'  => 'linkedin.svg',
        'youtube'   => 'youtube.svg',
        'x'         => 'x.svg',
        'tiktok'    => 'tiktok.svg',
        'pinterest' => 'pinterest.svg',
        'twitch'    => 'twitch.svg',
        'dribbble'  => 'dribbble.svg',
        'behance'   => 'behance.svg',
    ];

    /**
     * Recupera array name=>url desde ACF Options (campo repeater 'social_links').
     * @return array<string,string>
     */
    public static function get_all():array {
        if ( ! function_exists( 'get_field' ) ) {
            return [];
        }
        $raw = get_field( 'social_links', 'option' );
        if ( ! is_array( $raw ) ) {
            return [];
        }
        $result = [];
        foreach ( $raw as $item ) {
            if ( empty( $item['social_network'] ) || empty( $item['social_url'] ) ) {
                continue;
            }
            $network = sanitize_text_field( strtolower( $item['social_network'] ) );
            $url     = esc_url( $item['social_url'] );
            if ( isset( self::$icons[ $network ] ) ) {
                $result[ $network ] = $url;
            }
        }
        return $result;
    }

    /**
     * Imprime los enlaces con sus iconos.
     */
    public static function render(): void {
        $links = self::get_all();
        if ( empty( $links ) ) {
            return;
        }
        echo '<div class="social-links d-flex">';
        foreach ( $links as $network => $url ) {
            $icon = self::$icons[ $network ];
            $src  = CTM_THEME_URI . "/assets/img/icons/boxicons/{$icon}";
            $alt  = sprintf( esc_attr__( 'Icono de %s', CTM_TEXTDOMAIN ), ucfirst( $network ) );
            printf(
                '<a href="%1$s" class="social-item %2$s me-2" target="_blank" rel="noopener noreferrer">
                    <img src="%3$s" alt="%4$s" width="24" height="24">
                </a>',
                $url,
                esc_attr( $network ),
                esc_url( $src ),
                $alt
            );
        }
        echo '</div>';
    }
}
