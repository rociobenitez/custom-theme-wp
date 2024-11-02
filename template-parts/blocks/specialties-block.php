<section class="specialties py-5">
    <div class="container">
        <div class="row">
            <?php
            // Definir los datos de cada especialidad
            $specialties = [
                [
                    'title' => 'Cirugía de Pie y Tobillo',
                    'image_url' => get_template_directory_uri() . '/img/hero.jpg',
                    'link' => '#',
                    'description' => 'Tratamiento avanzado de pie y tobillo con las últimas tecnologías.'
                ],
                [
                    'title' => 'Intervencionismo Ecoguiado',
                    'image_url' => get_template_directory_uri() . '/img/hero.jpg',
                    'link' => '#',
                    'description' => 'Tratamiento avanzado de pie y tobillo con las últimas tecnologías.'
                ],
                [
                    'title' => 'Biomecánica',
                    'image_url' => get_template_directory_uri() . '/img/hero.jpg',
                    'link' => '#',
                    'description' => 'Tratamiento avanzado de pie y tobillo con las últimas tecnologías.'
                ],
                [
                    'title' => 'Unidad del Dolor',
                    'image_url' => get_template_directory_uri() . '/img/hero.jpg',
                    'link' => '#',
                    'description' => 'Tratamiento avanzado de pie y tobillo con las últimas tecnologías.'
                ],
                [
                    'title' => 'Neurofisiología',
                    'image_url' => get_template_directory_uri() . '/img/hero.jpg',
                    'link' => '#',
                    'description' => 'Tratamiento avanzado de pie y tobillo con las últimas tecnologías.'
                ],
                [
                    'title' => 'Podología',
                    'image_url' => get_template_directory_uri() . '/img/hero.jpg',
                    'link' => '#',
                    'description' => 'Tratamiento avanzado de pie y tobillo con las últimas tecnologías.'
                ]
            ];

            //$htag = $bloque['htags'];
            $htag = 2;

            // Incluir el componente para cada especialidad
            foreach ( $specialties as $specialty ) {
                $title = $specialty['title'];
                $image_url = $specialty['image_url'];
                $link = $specialty['link'];
                $description = $specialty['description'];
                
                get_template_part( 'template-parts/components/card-specialties', null, [
                    'title' => $title,
                    'htag' => $htag,
                    'description' => $description,
                    'image_url' => $image_url,
                    'link' => $link,
                    'cols' => 'col-lg-4 col-md-6'
                ] );
            }
            ?>
        </div>
        <div class="text-center">
            <a href="#" class="btn btn-lg btn-secondary">Ver todos los servicios</a>
        </div>
    </div>
</section>
