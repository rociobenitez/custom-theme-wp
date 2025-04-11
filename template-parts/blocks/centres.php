<?php
/**
 * Flexible Block: Centros
 * Muestra un listado de centros, cada uno con imagen, título, descripción y, opcionalmente, un enlace.
 */

if ( !empty($block['centre'] ) && is_array( $block['centre']) ) : ?>
<section class="centres mb-5">
    <div class="container-fluid">
        <div class="row justify-content-center px-1 mb-5 mb-md-0">
            <?php foreach( $block['centre'] as $centre ) : ?>
                <?php if ( !empty($centre['img']['url']) ) : ?>
                    <div class="col-12 col-md-6 px-1 mb-5 mb-md-0">
                        <?php 
                        // Si se proporciona un enlace, envolver la imagen y el título en un <a>
                        $link_url = ! empty( $centre['link']['url'] ) ? $centre['link']['url'] : '';
                        if ( ! empty( $link_url ) ) : ?>
                            <a href="<?= esc_url( $link_url ); ?>" class="centre-link d-block overflow-hidden">
                        <?php endif; ?>

                        <img class="fit"
                            src="<?= esc_url( $centre['img']['url'] ); ?>"
                            alt="<?= esc_attr( $centre['img']['alt'] ?? '' ); ?>">

                        <?php 
                        // Mostrar el título si está definido. Se formatea el título con boldLastWord().
                        if ( ! empty( $link_url ) ) : ?>
                            </a>
                        <?php endif;
                            
                        if ( ! empty( $centre['title'] ) ) :
                            $titleFormatted = boldLastWord( $centre['title'] );
                            echo htmlspecialchars_decode( tagTitle( $centre['htag_title'] ?? '2', $titleFormatted, 'heading c-black fw-300 text-end mt-4', '' ) );
                        endif; ?>

                        <?php if ( ! empty( $centre['text'] ) ) : ?>
                            <div class="description d-flex justify-content-end text-end">
                                <?= $centre['text']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>