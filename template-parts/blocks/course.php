<?php
/**
 * Bloque Flexible: Temario del Curso
 *
 * Descripción:
 * Este bloque muestra la información de un curso, incluyendo:
 * - Imagen destacada (posición izquierda o derecha).
 * - Encabezados opcionales: tagline y título.
 * - Descripción del temario (contenido del curso).
 * - Información adicional: duración, modalidad y ubicación si es presencial.
 *
 * Campos ACF utilizados:
 * - tagline            : Texto corto introductorio.
 * - htag_tagline       : Etiqueta HTML para el tagline (H1-H6, P).
 * - title              : Título principal del bloque.
 * - htag_title         : Etiqueta HTML para el título (H1-H6, P).
 * - text               : Descripción o lista de temas (puede incluir HTML/WYSIWYG).
 * - course_duration    : Duración del curso.
 * - course_modality    : Modalidad del curso (ej. online, presencial).
 * - course_location    : Ubicación del curso (solo si es presencial).
 * - image              : Imagen destacada.
 * - image_position     : Posición de la imagen (left o right).
 *
 * Funcionamiento especial:
 * Si el bloque se carga en una página "single-cursos.php", la modalidad se obtiene
 * automáticamente desde la primera categoría asignada al curso (CPT 'curso').
 */

// --- Variables ACF ---
$tagline        = $block['tagline'] ?? '';
$htag_tagline   = $block['htag_tagline'] ?? 3; // Etiqueta para tagline (p por defecto)
$title          = $block['title'] ?? '';
$htag_title     = $block['htag_title'] ?? 1; // Etiqueta para título (h2 por defecto)
$description    = $block['text'] ?? '';
$duration       = $block['course_duration'] ?? '';
$acf_modality   = $block['course_modality'] ?? '';
$location       = $block['course_location'] ?? '';
$img_position   = $block['image_position'] ?? 'right'; // Posición de la imagen
$image_url      = $block['image']['url'] ?? get_template_directory_uri() . '/assets/img/default-course.jpg';

// --- Obtener la modalidad dinámicamente ---
$modality = $acf_modality; // Valor por defecto desde ACF

// Si estamos en la single del CPT "curso", obtener la modalidad desde la categoría
if (is_singular('curso')) {
   $terms = get_the_terms(get_the_ID(), 'category'); // Obtener las categorías del CPT
   if ($terms && !is_wp_error($terms)) {
       $modality = esc_html($terms[0]->name); // Usa la primera categoría como modalidad
   }
}


// --- Modalidad dinámica (si estamos en la single de 'curso') ---
$modality = $acf_modality; // Por defecto, se utiliza el campo ACF
if (is_singular('curso')) {
   $terms = get_the_terms(get_the_ID(), 'category'); // Obtener las categorías del CPT
   if ($terms && !is_wp_error($terms)) {
       $modality = esc_html($terms[0]->name); // Usa la primera categoría como modalidad
   }
}

// --- Renderizado del bloque ---
if ($description || $title) : ?>
<section class="course-block container bg-white shadow rounded overflow-hidden my-5">
    <div class="row align-items-strech">
        <!-- Columna de Imagen -->
        <div class="col-12 col-md-4 p-0 <?php echo $img_position === 'left' ? 'order-md-2' : ''; ?>">
            <img src="<?php echo esc_url($image_url); ?>" 
               alt="<?php echo esc_attr($title ?: 'Curso'); ?>"
               class="img-course img-fluid fit">
        </div>

        <!-- Columna de Contenido -->
        <div class="col-12 col-md-8 p-5 <?php echo $img_position === 'left' ? 'order-md-1' : ''; ?>">
            <?php
            // Tagline
            if (!empty($tagline)):
               echo tagTitle($htag_tagline, $tagline, 'tagline', '');
            endif; 
            // Título principal
            if (!empty($title)):
               echo tagTitle($htag_title, $title, 'heading-2', '');
            endif;
            ?>

            <!-- Descripción del temario -->
            <?php if (!empty($description)): ?>
               <div class="description-container mb-4">
                  <?php echo $description; ?>
               </div>
            <?php endif; ?>

            <!-- Información adicional del curso -->
            <?php if (!empty($duration)): ?>
               <p class="mb-1">
                  <strong class="c-primary">Duración:</strong>
                  <span><?php echo esc_html($duration); ?></span>
               </p>
            <?php endif; ?>

            <?php if (!empty($modality)): ?>
               <p class="mb-1">
                  <strong class="c-primary">Modalidad:</strong>
                  <span><?php echo esc_html(ucfirst($modality)); ?></span>
               </p>
            <?php endif; ?>
            
            <?php if ($modality === 'presencial' && $location): ?>
                <p class="mb-0">
                    <strong class="c-primary">Lugar:</strong>
                    <span><?php echo esc_html($location); ?></span>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
