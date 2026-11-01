<?php
/**
 * Neblina Editorial — functions.php
 *
 * Setup geral do tema. Toda lógica real fica modularizada em /inc/.
 */

if (!defined('ABSPATH')) exit;

/**
 * Constantes do tema
 */
define('NEBLINA_VERSION', '1.0.0');
define('NEBLINA_DIR', get_template_directory());
define('NEBLINA_URI', get_template_directory_uri());

/**
 * Idiomas suportados — ordem importa: o primeiro é o DEFAULT
 */
define('NEBLINA_LANGS', ['es', 'pt']);
define('NEBLINA_DEFAULT_LANG', 'es');

/**
 * Theme support
 */
function neblina_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script'
    ]);
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Menu principal', 'neblina'),
        'footer'  => __('Menu rodapé', 'neblina'),
    ]);

    // Tamanhos de imagem usados no tema
    add_image_size('neblina-book-cover', 480, 720, false);
    add_image_size('neblina-author-portrait', 600, 600, true);
    add_image_size('neblina-banner', 1920, 900, true);
}
add_action('after_setup_theme', 'neblina_theme_support');

/**
 * Required files — ordem importa (i18n antes de rewrite/templates)
 */
require NEBLINA_DIR . '/inc/i18n.php';
require NEBLINA_DIR . '/inc/content.php';
require NEBLINA_DIR . '/inc/template-helpers.php';
require NEBLINA_DIR . '/inc/rewrite.php';
require NEBLINA_DIR . '/inc/enqueue.php';
require NEBLINA_DIR . '/inc/cpts.php';
require NEBLINA_DIR . '/inc/acf-fields.php';
require NEBLINA_DIR . '/inc/woocommerce.php';
require NEBLINA_DIR . '/inc/admin.php';
