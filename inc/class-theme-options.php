<?php
namespace Custom_Theme\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper para obtener valores de ACF Options Page.
 */
class Theme_Options {

    /**
     * Recupera todas las opciones definidas en ACF Options Page.
     * @return array Un array de pares clave => valor; [] si no existe ACF.
     */
    public static function get_all() {
        if ( ! function_exists( 'get_fields' ) ) {
            return [];
        }
        $fields = get_fields( 'option' );
        return is_array( $fields ) ? $fields : [];
    }

    /**
     * Obtiene un campo específico de ACF Options Page, con fallback.
     * @param string $key     Nombre del campo.
     * @param mixed  $default Valor por defecto.
     * @return mixed
     */
    public static function get( $key, $default = '' ) {
        $all = self::get_all();
        return isset( $all[ $key ] ) ? $all[ $key ] : $default;
    }

    /**
     * Obtiene opciones de contacto: phone, whatsapp, email.
     * @return array [ 'phone' => '', 'whatsapp' => '', 'email' => '' ]
     */
    public static function get_contact_options() {
        $opts = self::get_all();
        return [
            'phone'    => isset( $opts['phone'] )    ? sanitize_text_field( $opts['phone'] )    : '',
            'whatsapp' => isset( $opts['whatsapp'] ) ? sanitize_text_field( $opts['whatsapp'] ) : '',
            'email'    => isset( $opts['email'] )    ? sanitize_email( $opts['email'] )         : '',
        ];
    }
}
