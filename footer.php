<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package custom_theme
 */

// Opciones del tema
$options = get_fields( 'option' );

// Obtener el diseño del footer seleccionado (por defecto '4columns')
$footer_layout = $options['footer_layout'] ?? '4columns';
$footer_templates = [
    '4columns' => 'template-parts/footers/footer-4columns.php',
    '3columns' => 'template-parts/footers/footer-3columns.php',
    'minimal'  => 'template-parts/footers/footer-minimal.php'
];

// Definir clases del footer
$footer_bg_class = 'bg-light';
$border_color    = 'border-secondary';

// Logotipo
$logo_url = !empty( $options['footer_logo']['url'] ) 
    ? $options['footer_logo']['url']
    : get_template_directory_uri() . '/assets/img/logo.svg';
$footer_text_below_logo = $options['footer_text_below_logo'] ?? '';
$width_logo = 160;
$height_logo = 60;

$footer_contact_column_title = $options['footer_contact_column_title'] ?? 'Contacto';

// Configuración de columnas del footer
$footer_columns = [
    'footer1' => [
        'title' => $options['footer_column1_title'] ?? 'Menu 1',
        'menu' => 'footer1'
    ],
    'footer2' => [
        'title' => $options['footer_column2_title'] ?? 'Menu 2',
        'menu' => 'footer2'
    ]
];

// Filtra las columnas activas
$active_cols  = array_filter( $footer_columns, fn( $col ) => has_nav_menu( $col['menu']) );
$column_count = count($active_cols) + 1;
$column_class = 'col-md-' . ($column_count === 1 ? '5' : (12 / $column_count));

// Variables de contacto
$whatsapp = $options['whatsapp'] ?? '';
$phone    = $options['phone'] ?? '';
$email    = $options['email'] ?? '';

$contact_info = [
	'address'   => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/location.svg",
		 'link' => !empty($options['google_maps_link']) ? $options['google_maps_link'] : '',
         'alt'  => __('Icono de ubicación', THEME_TEXTDOMAIN),
		 'text' => trim(($options['address'] ?? '') . 
							(!empty($options['city']) ? ', ' . $options['city'] : '') . 
							(!empty($options['postal_code']) ? ', ' . $options['postal_code'] : ''))
	],
	'phone'     => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/phone.svg",
		 'link' => !empty($phone) ? 'tel:' . $phone : '',
         'alt'  => __('Icono de teléfono', THEME_TEXTDOMAIN),
		 'text' => $phone ?? ''
	],
	'whatsapp'  => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/whatsapp.svg",
		 'link' => !empty($whatsapp) ? 'https://wa.me/+34' . str_replace(' ', '', $whatsapp) : '',
         'alt'  => __('Icono de WhatsApp', THEME_TEXTDOMAIN),
		 'text' => $whatsapp ?? ''
	],
	'email'     => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/mail.svg",
		 'link' => !empty($email) ? 'mailto:' . $email : '',
         'alt'  => __('Icono de correo electrónico', THEME_TEXTDOMAIN),
		 'text' => $email ?? ''
	]
];

// Horario de apertura (opcional)
$opening_hours = $options['opening_hours'] ?? '';
$icon_schedule_src = get_template_directory_uri() . "/assets/img/icons/schedule.svg";
?>

<footer id="site-footer" class="site-footer d-flex flex-column <?= esc_attr( $footer_bg_class ); ?>">
    <div class="footer-top pt-5 <?= $footer_layout !== 'minimal' ? 'pb-4' : ''; ?>">
        <div class="container py-4">
            <?php // Incluir la plantilla de footer según el diseño seleccionado
            if ( array_key_exists($footer_layout, $footer_templates )) {
                $template_path = $footer_templates[$footer_layout];
                if (file_exists(get_template_directory() . '/' . $template_path)) {
                    // Pasar variables a la plantilla incluida
                    include locate_template($template_path);
                } else {
                    // Cargar una plantilla por defecto
                    include locate_template('template-parts/footers/footer-4columns.php');
                }
            } else {
                // Cargar una plantilla de footer por defecto si el diseño no está definido
                include locate_template('template-parts/footers/footer-4columns.php');
            }
            ?>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column-reverse flex-lg-row justify-content-between align-items-center mt-5 py-3 border-top <?= esc_attr( $border_color ); ?>">
            <div class="footer-copy my-auto text-center text-lg-start">
                <p class="text-secondary fs13 lh140 mb-0 px-4 px-sm-0"><?php change_footer_admin(); ?></p>
            </div>
            <?php if ( has_nav_menu( 'legal' ) ) : ?>
                <div class="legal-links d-flex mb-3 mb-lg-0 gap-1">
                    <?php wp_nav_menu( array(
                        'theme_location' => 'legal',
                        'menu_class'     => 'navbar-nav d-flex flex-column flex-sm-row gap-0 gap-sm-3 fs14 text-center',
                        'depth'          => 1,
                    ) ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php
if ($options['kit_digital']) :
    $kitdigital_template = 'template-parts/footers/footer-kitdigital.php';
    if (file_exists(get_stylesheet_directory() . '/' . $kitdigital_template)) :
        include locate_template($kitdigital_template);
    endif;
endif;
?>

<!-- Scroll to Top Button -->
<a href="#" class="scroll-to-top" aria-label="<?php esc_attr_e( 'Scroll to top', THEME_TEXTDOMAIN ); ?>"><i class="bi bi-arrow-up"></i></a>

<?php // Mostrar botones de contacto (WhatsApp, Teléfono, Email)
if( !empty( $whatsapp ) || !empty( $phone ) || !empty( $email ) ) {
    get_template_part('template-parts/components/footer-contacts');
}
?>

<?php wp_footer(); ?>
</body>
</html>