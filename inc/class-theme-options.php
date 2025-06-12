<?php
namespace Custom_Theme\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper para obtener valores de ACF Options Page.
 */
class Theme_Options {

    // Caché interno para no volver a llamar a get_fields() múltiples veces
    private static $all = null;

    /**
     * Recupera todas las opciones definidas en ACF Options Page.
     * @return array Un array de pares clave => valor; [] si no existe ACF.
     */
    public static function get_all(): array {
        if ( function_exists( 'get_fields' ) ) {
            $fields = get_fields( 'option' );
            self::$all = is_array( $fields ) ? $fields : [];
        } else {
            self::$all = [];
        }
        return self::$all;
    }

    /**
     * Obtiene un campo específico de ACF Options Page, con fallback.
     * @param string $key     Nombre del campo.
     * @param mixed  $default Valor por defecto.
     * @return mixed
     */
    public static function get( string $key, $default = '' ) {
        $all = self::get_all();
        return array_key_exists( $key, $all ) ? $all[ $key ] : $default;
    }

    /**
     * Obtiene opciones de contacto: phone, whatsapp, email.
     * @return array{phone:string,whatsapp:string,email:string}
     */
    public static function get_contact_options() {
        $phone    = self::get( 'phone', '' );
        $whatsapp = self::get( 'whatsapp', '' );
        $email    = self::get( 'email', '' );

        return [
            'phone'    => $phone    ? sanitize_text_field( $phone ) : '',
            'whatsapp' => $whatsapp ? sanitize_text_field( $whatsapp ) : '',
            'email'    => $email    ? sanitize_email( $email )         : '',
        ];
    }
}
