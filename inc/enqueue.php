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
/**
 * Versão do asset = data de modificação do arquivo, pra o navegador
 * baixar o CSS/JS novo a cada build sem precisar de Ctrl+F5.
 */
function neblina_asset_version($relative_path) {
    $path = NEBLINA_DIR . $relative_path;
    return file_exists($path) ? (string) filemtime($path) : NEBLINA_VERSION;
}

function neblina_enqueue_assets() {
    // Só os pesos usados pelo sistema tipográfico (styles/scss/_typography.scss):
    // Manrope 500 (títulos) · DM Sans 400/500/600 + itálico 400 (texto e interface).
    wp_enqueue_style(
        'neblina-google-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@500&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap',
        [],
        null
    );

    // CSS global (reset, tokens, header, footer, componentes)
    wp_enqueue_style(
        'neblina-global',
        NEBLINA_URI . '/assets/css/global.css',
        ['neblina-google-fonts'],
        neblina_asset_version('/assets/css/global.css')
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
        'cart'    => 'loja.css',
        'checkout'=> 'loja.css',
    ];
    if ($route && isset($route_css[$route])) {
        $file = '/assets/css/' . $route_css[$route];
        if (file_exists(NEBLINA_DIR . $file)) {
            wp_enqueue_style(
                'neblina-page-' . $route,
                NEBLINA_URI . $file,
                ['neblina-global'],
                neblina_asset_version($file)
            );
        }
    }

    // JS principal
    wp_enqueue_script(
        'neblina-main',
        NEBLINA_URI . '/assets/js/main.js',
        [],
        neblina_asset_version('/assets/js/main.js'),
        true
    );

    // JS específicos
    if (in_array($route, ['book', 'cart'], true)) {
        wp_enqueue_script(
            'neblina-loja',
            NEBLINA_URI . '/assets/js/loja.js',
            $route === 'cart' ? ['jquery'] : [],   // no carrinho escuta os eventos do Woo

            neblina_asset_version('/assets/js/loja.js'),
            true
        );
    }

    if ($route === 'home') {
        wp_enqueue_script(
            'neblina-carousel',
            NEBLINA_URI . '/assets/js/carousel.js',
            ['neblina-main'],
            neblina_asset_version('/assets/js/carousel.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'neblina_enqueue_assets');
