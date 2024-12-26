<?php
/**
 * Schema LocalBusiness
 */

 ?>
<!--<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "MedicalClinic",
    "@id": "#Local",
    
    <?php //if (!empty($localbusiness['additionaltype'])): ?>
    "additionalType": [
    <?php /*
    $totalUrls = count($additionaltype);
    $contador = 1;
    foreach ($additionaltype as $url) {
        echo '"' . esc_url($url['url']) . '"';
        if ($contador !== $totalUrls) {
            echo ',';
        }
        $contador++;
    }
    ?>
    ],
    <?php endif; ?>
    
    <?php if (!empty($localbusiness['knowsabout'])): ?>
    "knowsAbout": [
    <?php
    $totalUrls = count($knowsabout);
    $contador = 1;
    foreach ($knowsabout as $url) {
        echo '"' . esc_url($url['url']) . '"';
        if ($contador !== $totalUrls) {
            echo ',';
        }
        $contador++;
    }
    ?>
    ],
    <?php endif; ?>
    
    "name": "<?php echo esc_js($name); ?>",
    "alternateName": "<?php echo esc_js($alternate_name); ?>",
    "areaServed": "<?php echo esc_js($areaServed); ?>",
    "description": "<?php echo esc_js($description); ?>",
    "url": "<?php echo esc_url($url_principal); ?>",
    
    <?php if (is_page('Inicio') || is_page('contacto')): ?>
    "mainEntityOfPage": {
        "@id": "<?php echo esc_url($url_pagina_actual); ?>"
    },
    <?php endif; ?>
    
    "email": "<?php echo $email; ?>",
    "hasMap": "<?php echo esc_url($maplink); ?>",
    "image": "<?php echo esc_url(get_template_directory_uri() . '/img/img-default.jpg'); ?>",
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "<?php echo esc_js($latitude); ?>",
        "longitude": "<?php echo esc_js($longitude); ?>"
    },
    "logo": "<?php echo esc_url(get_template_directory_uri() . '/img/logo-color.svg'); ?>",
    "priceRange": "<?php echo esc_js($priceRange); ?>",
    
    "sameAs": [
    <?php
    $socialLinks = array_filter([$linkedin, $youtube, $twitter, $instagram, $facebook]);
    $totalLinks = count($socialLinks);
    $index = 1;
    foreach ($socialLinks as $link) {
        echo '"' . esc_url($link) . '"';
        if ($index < $totalLinks) {
            echo ',';
        }
        $index++;
    }
    ?>
    ],
    
    "telephone": "<?php echo esc_js($phone); ?>",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "ES",
        "addressLocality": "<?php echo esc_js($ciudad); ?>",
        "addressRegion": "<?php echo esc_js($provincia); ?>",
        "postalCode": "<?php echo esc_js($codpos); ?>",
        "streetAddress": "<?php echo esc_js($direccion); ?>"
    },
    
    "openingHoursSpecification": [
    <?php
    $totalDays = count($openingHoursSpecification);
    $contador = 1;
    foreach ($openingHoursSpecification as $day) {
        echo '{
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "' . esc_js($day['dayofweek']) . '",
            "opens": "' . esc_js($day['opens']) . '",
            "closes": "' . esc_js($day['closes']) . '"
        }';
        if ($contador !== $totalDays) {
            echo ',';
        }
        $contador++;
    }*/
    ?>
    ]
}
</script>-->