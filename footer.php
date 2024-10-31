<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package CustomTheme
 */

// Opciones del tema
$options = get_fields('option');
$footer_bg_class = 'bg-light';
$border_color = 'border-secondary';

// Logotipo
$logo_url = !empty($options['footer_logo']) ? $options['footer_logo'] : get_template_directory_uri() . '/assets/img/logo.svg';
$footer_text_below_logo = !empty($options['footer_text_below_logo']) ? $options['footer_text_below_logo'] : '';
$width_logo = 150;
$height_logo = 60;

$footer_contact_column_title = !empty($options['footer_contact_column_title']) ? $options['footer_contact_column_title'] : 'Contacto';
$icon_schedule_src = get_template_directory_uri() . "/assets/img/icons/schedule.svg";

// Configuración de columnas del footer
$footer_columns = [
    'footer1' => [
        'title' => !empty($options['footer_column1_title']) ? $options['footer_column1_title'] : 'Menu 1',
        'menu' => 'footer1'
    ],
    'footer2' => [
        'title' => !empty($options['footer_column2_title']) ? $options['footer_column2_title'] : 'Menu 2',
        'menu' => 'footer2'
    ]
];

// Filtra las columnas activas
$active_columns = array_filter($footer_columns, fn($col) => has_nav_menu($col['menu']));
$column_count = count($active_columns) + 1; // Agregamos la columna de Contacto como adicional
$column_class = 'col-lg-' . ($column_count === 1 ? '5' : (12 / $column_count));

// Variables de contacto
$contact_info = [
	'address'   => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/location.svg",
		 'link' => !empty($options['google_maps_link']) ? $options['google_maps_link'] : '',
		 'text' => trim(($options['address'] ?? '') . 
							(!empty($options['city']) ? ', ' . $options['city'] : '') . 
							(!empty($options['postal_code']) ? ', ' . $options['postal_code'] : ''))
	],
	'phone'     => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/phone.svg",
		 'link' => !empty($options['phone']) ? 'tel:' . $options['phone'] : '',
		 'text' => $options['telefono'] ?? ''
	],
	'whatsapp'  => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/whatsapp.svg",
		 'link' => !empty($options['whatsapp']) ? 'https://wa.me/+34' . str_replace(' ', '', $options['whatsapp']) : '',
		 'text' => $options['whatsapp'] ?? ''
	],
	'email'     => [
		 'icon' => get_template_directory_uri() . "/assets/img/icons/mail.svg",
		 'link' => !empty($options['email']) ? 'mailto:' . $options['email'] : '',
		 'text' => $options['email'] ?? ''
	]
];

// Horario de apertura (opcional)
$opening_hours = !empty($options['opening_hours']) ? $options['opening_hours'] : '';
?>

<footer id="site-footer" class="site-footer d-flex flex-column <?php echo esc_attr($footer_bg_class); ?>">
    <div class="footer-top pt-5 pb-4">
        <div class="container py-4">
            <div class="row justify-content-center">
                <!-- Logo -->
                <div class="col-lg-2 footer-brand d-flex flex-column gap-2 align-items-center align-items-lg-start mb-5 mb-xl-0">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-brand-link" rel="home">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="footer-logo" width="<?php echo esc_attr($width_logo); ?>" height="<?php echo esc_attr($height_logo); ?>">
                    </a>
                    <?php if (!empty($footer_text_below_logo)) : ?>
                        <div><?php echo $footer_text_below_logo; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Footer Menus -->
                <div class="row col-lg-10 justify-content-end">
                    <?php foreach ($active_columns as $col) : ?>
                        <div class="<?php echo esc_attr($column_class); ?> footer-menu text-center text-md-start mb-4 mb-lg-0">
                            <p class="footer-menu-title mb-2"><?php echo esc_html($col['title']); ?></p>
                            <?php wp_nav_menu([
                                'theme_location' => $col['menu'],
                                'menu_class'     => 'footer-menu-list list-unstyled d-flex flex-column gap-1',
                            ]); ?>
                        </div>
                    <?php endforeach; ?>

                    <!-- Contact Information -->
                    <?php if (!empty(array_filter($contact_info, fn($info) => !empty($info['text']) && !empty($info['link'])))) : ?>
                        <div class="<?php echo esc_attr($column_class); ?> footer-contact text-center text-lg-start mb-4 mb-lg-0">
                            <p class="footer-contact-title mb-2"><?php echo esc_html($footer_contact_column_title); ?></p>
                            <ul class="footer-contact-list list-unstyled d-flex flex-column gap-1">
                                <?php foreach ($contact_info as $contact) : ?>
                                    <?php if (!empty($contact['text']) && !empty($contact['link'])) : ?>
                                        <li class="footer-contact-item">
                                            <a href="<?php echo esc_url($contact['link']); ?>" target="_self">
                                                <img src="<?php echo esc_attr($contact['icon']); ?>" class="footer-contact-icon me-1" alt="">
                                                <?php echo esc_html($contact['text']); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($opening_hours) : ?>
                                    <li class="footer-contact-item">
                                        <img src="<?php echo esc_attr($icon_schedule_src); ?>" class="footer-contact-icon me-1" alt="">
                                        <?php echo esc_html($opening_hours); ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column-reverse flex-lg-row justify-content-between align-items-center py-4 border-top <?php echo esc_attr($border_color); ?>">
            <div class="footer-copy my-auto text-center text-lg-start">
                <p class="text-secondary fs13 lh140 mb-0 px-4 px-sm-0"><?php change_footer_admin(); ?></p>
            </div>
            <?php if (has_nav_menu('legal')) : ?>
                <div class="legal-links d-flex mb-3 mb-lg-0 gap-1">
                    <?php wp_nav_menu([
                        'theme_location' => 'legal',
                        'menu_class'     => 'navbar-nav d-flex flex-column flex-sm-row gap-0 gap-sm-3 fs14 text-center',
                        'depth'          => 1,
                    ]); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- Scroll to Top Button -->
<a href="#" class="scroll-to-top" aria-label="Scroll to top"><i class="bi bi-arrow-up"></i></a>

<?php wp_footer(); ?>

</body>
</html>
