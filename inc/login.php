<?php
/**
 * Personalización de la página de inicio de sesión de WordPress.
 *
 * @package NombreTheme
 */

function custom_login_background() {
   echo '<style type="text/css">
      body.login {
         background-image: linear-gradient(to bottom, rgba(0,0,0,0.55), rgba(0,0,0,0.35)), url("' . esc_url( LOGIN_BG ) . '");
         background-repeat: no-repeat;
         background-size: cover;
      }
      .login h1 a {
         background-image: url("' . esc_url( SITE_LOGO_WHITE ) . '");
         background-size: 200px;
         width: 100%;
         height: 40px;
      }
   </style>';
}
add_action( 'login_head', 'custom_login_background' );
