<?php 
/**
 *  Template Name: Contacto
 *  @package customtheme
 */

get_header();
$fields = get_fields();
$bodyclass='page-contact';

// Coordenadas dinámicas para el centro del mapa
$contact = $fields['page_contact'] ?? [];
$google_api_key = get_field('google_maps_api', 'option');
$ubicaciones = $contact['ubicacion_mapa'] ?? [];
$map_center = [
    'lat' => $ubicaciones[0]['map']['lat'] ?? 0,
    'lng' => $ubicaciones[0]['map']['lng'] ?? 0,
];

get_template_part('template-parts/pageheader-form');
$contenido = get_the_content();

if ( trim( get_the_content() ) ) { ?>
    <section class="bg-grey py-5">
        <div class="container the-content">
            <?php echo apply_filters('the_content', $contenido); ?>
        </div>
    </section>
<?php
}

if (!empty($google_api_key) && strlen($google_api_key) > 10) : ?>
    <!-- Mapa de Google con ubicaciones desde ACF -->
    <div id="map" style="width: 100%; height: 380px;"></div>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&loading=async&callback=initMap"></script>

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: <?php echo $map_center['lat']; ?>, lng: <?php echo $map_center['lng']; ?>} // Posibilidad de cambiar estas coordenadas al centro deseado
            });

            // Obtener localizaciones desde ACF
            <?php if(!empty($contact['location_map'])): ?>
                <?php foreach ($contact["location_map"] as $location): ?>
                    var marker = new google.maps.Marker({
                        position: {lat: <?php echo $location['map']['lat']; ?>, lng: <?php echo $location['map']['lng']; ?>},
                        map: map,
                        title: '<?php echo $location['map']['address']; ?>'
                    });
                <?php endforeach; ?>
            <?php endif; ?>
        }
    </script>
<?php endif;

// Cargar bloques flexibles dinámicamente
if(!empty($fields['flexible_content'])) {
    require_once get_template_directory() . '/template-parts/load-flexible-blocks.php';
    load_flexible_blocks($fields['flexible_content']);
}

get_footer();