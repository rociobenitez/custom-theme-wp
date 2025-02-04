<?php
/**
 * Limpieza del head para mejorar seguridad y optimización.
 *
 * @package custom_theme
 */

// Elimina enlaces de feeds adicionales (categorías, etiquetas, etc.)
remove_action('wp_head', 'feed_links_extra', 3);
// Elimina los enlaces de feeds principales de entradas y comentarios
remove_action('wp_head', 'feed_links', 2);
// Elimina la etiqueta RSD link
remove_action('wp_head', 'rsd_link');
// Elimina el shortlink de la cabecera
remove_action('wp_head', 'wp_shortlink_wp_head');
// Elimina la etiqueta meta generator que indica la versión de WordPress, por razones de seguridad
remove_action('wp_head', 'wp_generator');