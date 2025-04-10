<?php
/**
 * Recibe en $args (entre otros):
 * - 'title'             => Título del CPT.
 * - 'permalink'         => Enlace a la single del CPT.
 * - 'video_url'         => URL (YouTube, Vimeo, etc.) del video asociado.
 * - 'show_videos_only'  => bool (true/false): si es true, se mostrará solo el video embebido.
 */

// Extraer variables del array de argumentos
$title            = $args['title']            ?? get_the_title();
$permalink        = $args['permalink']        ?? get_permalink();
$video_url        = $args['video_url']        ?? '';
$show_videos_only = $args['show_videos_only'] ?? false;

if (!$video_url) {
    return;
}

// Si se ha definido el campo para “solo videos”
if ($show_videos_only) : 
    // MODO VIDEO: solo mostrar el iframe/embebido
    ?>
    <div class="card-cpt-video">
        <?php echo wp_oembed_get($video_url); ?>
    </div>
<?php else : 
    // MODO CARD: tarjeta con enlace al CPT, fondo de video embebido y título en hover
    ?>
    <div class="card-cpt card-cpt-with-overlay position-relative overflow-hidden">
        <a href="<?php echo esc_url($permalink); ?>" class="card-cpt-link d-block w-100 h-100 text-decoration-none position-relative">
            
            <!-- Video “de fondo” -->
            <div class="card-cpt-video-embed">
                <?php echo wp_oembed_get($video_url); ?>
            </div>
            
            <!-- Overlay con el título que aparece al hover -->
            <div class="card-cpt-title-overlay position-absolute top-50 start-50 translate-middle text-center text-white opacity-0 hover-opacity-100">
                <h3 class="fs-6 m-0 px-2 py-1"><?php echo esc_html($title); ?></h3>
            </div>

        </a>
    </div>
<?php endif; ?>
