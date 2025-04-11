<?php
/**
 * Page Header Component
 *
 * Este componente muestra el header de la página, con una imagen de fondo (ya sea definida o por defecto)
 * y, en la sección inferior, muestra el título y texto principal. Si se define un formulario (ID de formulario),
 * se divide la sección en dos columnas: la izquierda muestra el contenido y la derecha, el formulario.
 *
 * @package customtheme
 */

global $fields;

if (!is_shop() && !is_tax()) :

    // Imagen por defecto
    $default_image = get_template_directory_uri() . '/img/image-default.png';
    
    // Si el campo 'img' no está definido o no tiene URL, usar la imagen por defecto
    if (!isset($fields['img']) || !is_array($fields['img']) || empty($fields['img']['url'])) {
        $img_bg = $default_image;
    } else {
        $img_bg = $fields['img']['url'];
    }
    ?>
    <section class="pageheader">
        <!-- Sección superior con imagen de fondo -->
        <div class="topheader cover" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.40) 0%, rgba(0, 0, 0, 0.40) 100%), url('<?php echo esc_url($img_bg); ?>');">
            <?php if (!empty($fields['title'])) : ?>
                <?php echo tagTitle($fields['htag_title'], $fields['title'], 'header-title text-center', ''); ?>
            <?php endif; ?>
        </div>
    
        <?php if (!empty($fields['text'])) : ?>
            <div class="botheader">
                <div class="container">
                    <div class="row justify-content-between">
                        <?php if (!empty($fields['id_form'])) : ?>
                            <!-- Si hay formulario definido, se muestran dos columnas -->
                            <div class="col-12 col-md-6">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <?php echo tagTitle($fields['htag_title-content'], $fields['title-content'], 'content-title', ''); ?>
                                    <div>
                                        <?= $fields['text']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 col-xl-4 mt-5 mt-md-0">
                                <div class="header-form">
                                    <?php echo tagTitle(3, $fields['title-form'], 'form-title text-center', ''); ?>
                                    <?php echo do_shortcode('[gravityform id="' . $fields['id_form'] . '" title="false" description="false" ajax="true" tabindex="50"]'); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Si no hay formulario, la columna de contenido ocupa todo el ancho -->
                            <div class="col-12">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <?php echo tagTitle($fields['htag_title-content'], $fields['title-content'], 'content-title', ''); ?>
                                    <div>
                                        <?= $fields['text']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.botheader -->
        <?php endif; ?>
    </section>
<?php endif; ?>
