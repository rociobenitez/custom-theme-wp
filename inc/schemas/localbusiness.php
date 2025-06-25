<?php
defined('ABSPATH') || exit;

if ( ! function_exists('generate_schema_localbusiness') ) {
    function generate_schema_localbusiness() {
        if ( ! function_exists('get_field') ) {
            return;
        }

        // Obtener campos de opciones
        $opts = get_fields('option');
        $lb   = $opts['localbusiness'] ?? [];
        $sites = $opts['additional_address'] ?? [];

        // Campos comunes
        $home_url = esc_url( home_url() );
        $page_url = esc_url( get_permalink() );
        $email    = sanitize_email( $opts['email'] ?? '' );
        $logo     = $opts['site_logo']['url'] ?? get_template_directory_uri() . '/img/logotipo.png';
        $image    = $opts['default_image']['url'] ?? get_template_directory_uri() . '/img/default_image.jpg';
        $socials  = array_filter( array_map('esc_url', array_column($opts['social_links'] ?? [], 'social_url')) );

        // Iniciar esquema
        if ( ! empty($sites) ) {
            // Multi-sede => Organization con departments
            $schema = [
                '@context'      => 'https://schema.org',
                '@type'         => 'Organization',
                '@id'           => '#Organization',
                'name'          => sanitize_text_field( $lb['name'] ?? get_bloginfo('name') ),
                'alternateName' => sanitize_text_field( $lb['alternate_name'] ?? '' ),
                'description'   => sanitize_textarea_field( $lb['description'] ?? '' ),
                'url'           => $home_url,
                'email'         => $email,
                'logo'          => $logo,
                'image'         => $image,
            ];
            if ( ! empty($lb['area_served']) ) {
                $schema['areaServed'] = sanitize_text_field( $lb['area_served'] );
            }
            if ( ! empty($lb['price_range']) ) {
                $schema['priceRange'] = sanitize_text_field( $lb['price_range'] );
            }
            if ( $socials ) {
                $schema['sameAs'] = array_values( array_unique($socials) );
            }
            // mainEntityOfPage solo en home
            if ( is_front_page() ) {
                $schema['mainEntityOfPage'] = ['@id' => $page_url];
            }
            // Departments
            $depts = [];
            foreach ( $sites as $site ) {
                // Validar subcampos
                if ( empty($site['address']) ) {
                    continue;
                }
                $addr = [
                    '@type'         => 'PostalAddress',
                    'streetAddress' => sanitize_text_field($site['address']),
                ];
                if ( ! empty($site['address_locality']) ) { 
                    $addr['addressLocality'] = sanitize_text_field($site['address_locality']);
                }
                if ( ! empty($site['address_region']) ) { 
                    $addr['addressRegion'] = sanitize_text_field($site['address_region']);
                }
                if ( ! empty($site['postal_code']) ) { 
                    $addr['postalCode']      = sanitize_text_field($site['postal_code']);
                }
                $addr['addressCountry'] = 'ES';

                $dept = [
                    '@type'   => sanitize_text_field($site['type'] ?? 'LocalBusiness'),
                    'name'    => sanitize_text_field($site['name'] ?? $schema['name']),
                    'address' => $addr,
                ];
                if ( ! empty($site['phone']) )    { $dept['telephone'] = sanitize_text_field($site['phone']); }
                if ( ! empty($site['url']) )      { $dept['url']       = esc_url($site['url']); }
                if ( ! empty($site['latitude']) && ! empty($site['longitude']) ) {
                    $dept['geo'] = [
                        '@type'     => 'GeoCoordinates',
                        'latitude'  => floatval($site['latitude']),
                        'longitude' => floatval($site['longitude']),
                    ];
                }
                if ( ! empty($site['google_maps_link']) ) {
                    $dept['hasMap'] = esc_url($site['google_maps_link']);
                }
                // Horario sede
                if ( ! empty($site['opening_hours_specification']) ) {
                    $hours = [];
                    foreach ( $site['opening_hours_specification'] as $h ) {
                        if ( empty($h['dayofweek']) || empty($h['opens']) || empty($h['closes']) ) {
                            continue;
                        }
                        $hours[] = [
                            '@type'     => 'OpeningHoursSpecification',
                            'dayOfWeek' => array_map('sanitize_text_field',(array)$h['dayofweek']),
                            'opens'     => sanitize_text_field($h['opens']),
                            'closes'    => sanitize_text_field($h['closes']),
                        ];
                    }
                    if ( $hours ) {
                        $dept['openingHoursSpecification'] = $hours;
                    }
                }
                $depts[] = $dept;
            }
            if ( $depts ) {
                $schema['department'] = $depts;
            }
        } else {
            // Única ubicación => LocalBusiness
            $type = sanitize_text_field($lb['type'] ?? 'LocalBusiness');
            $id   = sanitize_text_field($lb['id']   ?? 'Local');
            $schema = [
                '@context' => 'https://schema.org',
                '@type'    => $type,
                '@id'      => $home_url . '#' . $id,
                'url'      => $home_url,
                'mainEntityOfPage' => ['@id' => $page_url],
                'name'     => sanitize_text_field($lb['name'] ?? get_bloginfo('name')),
                'description' => sanitize_textarea_field($lb['description'] ?? ''),
                'email'    => $email,
                'telephone'=> sanitize_text_field($opts['phone'] ?? ''),
                'logo'     => esc_url($logo),
                'image'    => esc_url($image),
            ];
            // Geo y address
            if ( isset($lb['latitude'],$lb['longitude']) ) {
                $schema['geo'] = [
                    '@type'     => 'GeoCoordinates',
                    'latitude'  => floatval($lb['latitude']),
                    'longitude' => floatval($lb['longitude']),
                ];
            }
            if ( ! empty($opts['address']) ) {
                $addr = [
                    '@type'         => 'PostalAddress',
                    'addressCountry' => 'ES',
                    'streetAddress' => sanitize_text_field($opts['address']),
                ];
                if ( ! empty($opts['city']) ) {
                    $addr['addressLocality'] = sanitize_text_field($opts['city']);
                }
                if ( ! empty($opts['province'])) {
                    $addr['addressRegion'] = sanitize_text_field($opts['province']);
                }
                if ( ! empty($opts['postal_code']) ) {
                    $addr['postalCode'] = sanitize_text_field($opts['postal_code']);
                }
                $schema['address'] = $addr;
            }
            // Horario
            if ( ! empty($lb['opening_hours_specification']) ) {
                $oh = [];
                foreach ($lb['opening_hours_specification'] as $day) {
                    if ( empty($day['dayofweek']) || empty($day['opens']) || empty($day['closes']) ) {
                        continue;
                    }
                    $oh[] = [
                        '@type'     => 'OpeningHoursSpecification',
                        'dayOfWeek' => array_map('sanitize_text_field',(array)$day['dayofweek']),
                        'opens'     => sanitize_text_field($day['opens']),
                        'closes'    => sanitize_text_field($day['closes']),
                    ];
                }
                if ( $oh ) {
                    $schema['openingHoursSpecification'] = $oh;
                }
            }
            // Redes sociales
            if ( $socials ) {
                $schema['sameAs'] = array_values(array_unique($socials));
            }
        }

        // Imprimir JSON-LD
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE );
        echo "\n</script>\n";
    }
    add_action('wp_head', 'generate_schema_localbusiness', 1);
}
