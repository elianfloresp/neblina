<?php
/**
 * Enfileira CSS, JS e Google Fonts.
 *
 * CSS: compilado via Gulp a partir de styles/scss/ → assets/css/
 * JS:  arquivos em assets/js/
 *
 * Padrão: 1 CSS compilado por página (home.css, catalogo.css, etc.) +
 * um global.css com reset, variáveis, header, footer, utilitários.
 */

if (!defined('ABSPATH')) exit;

/**
 * CSS/JS do site público.
 */
function neblina_enqueue_assets() {
    $v = NEBLINA_VERSION;

    // Google Fonts (fallbacks oficiais do protótipo)
    wp_enqueue_style(
        'neblina-google-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap',
        [],
        null
    );

    // CSS global (reset, variáveis, header, footer, utilitários)
    wp_enqueue_style(
        'neblina-global',
        NEBLINA_URI . '/assets/css/global.css',
        ['neblina-google-fonts'],
        $v
    );

    // CSS específico por rota
    $route = get_query_var('neblina_route');
    $route_css = [
        'home'    => 'home.css',
        'catalog' => 'catalogo.css',
        'book'    => 'livro.css',
        'authors' => 'autores.css',
        'author'  => 'autor.css',
        'about'   => 'sobre.css',
        'team'    => 'equipe.css',
        'contact' => 'contato.css',
    ];
    if ($route && isset($route_css[$route])) {
        $file = $route_css[$route];
        $path = NEBLINA_DIR . '/assets/css/' . $file;
        if (file_exists($path)) {
            wp_enqueue_style(
                'neblina-page-' . $route,
                NEBLINA_URI . '/assets/css/' . $file,
                ['neblina-global'],
                $v
            );
        }
    }

    // JS principal
    wp_enqueue_script(
        'neblina-main',
        NEBLINA_URI . '/assets/js/main.js',
        [],
        $v,
        true
    );

    // JS específicos
    if ($route === 'home') {
        wp_enqueue_script('neblina-carousel', NEBLINA_URI . '/assets/js/carousel.js', ['neblina-main'], $v, true);
    }
}
add_action('wp_enqueue_scripts', 'neblina_enqueue_assets');
