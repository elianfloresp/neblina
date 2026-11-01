<?php
/**
 * Rewrite rules para URLs bilíngues sem plugin.
 *
 * Padrão: /{lang}/{slug}/[opcional subslug]/
 *   /es/libros/              → página catálogo (ES)
 *   /pt/livros/              → página catálogo (PT)
 *   /es/libro/dom-casmurro/  → ficha de livro (ES)
 *   /pt/livro/dom-casmurro/  → ficha de livro (PT)
 *
 * Depois de instalar o tema, vai em Ajustes → Links permanentes e salva
 * (não precisa mexer, só salvar) pra o WordPress reconhecer essas regras.
 */

if (!defined('ABSPATH')) exit;

/**
 * Registra as query vars que vamos usar.
 */
function neblina_query_vars($vars) {
    $vars[] = 'neblina_lang';
    $vars[] = 'neblina_route';
    $vars[] = 'neblina_slug';
    return $vars;
}
add_filter('query_vars', 'neblina_query_vars');

/**
 * Adiciona as rewrite rules.
 */
function neblina_rewrite_rules() {
    // Home de cada idioma: /es/ e /pt/
    add_rewrite_rule('^(es|pt)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=home',
        'top');

    // Catálogo: /es/libros/ e /pt/livros/
    add_rewrite_rule('^(es|pt)/(libros|livros)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=catalog',
        'top');

    // Ficha de livro: /es/libro/{slug}/ e /pt/livro/{slug}/
    add_rewrite_rule('^(es|pt)/(libro|livro)/([^/]+)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=book&neblina_slug=$matches[3]',
        'top');

    // Autores — lista
    add_rewrite_rule('^(es|pt)/autores/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=authors',
        'top');

    // Autor — perfil individual
    add_rewrite_rule('^(es|pt)/autor/([^/]+)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=author&neblina_slug=$matches[2]',
        'top');

    // Sobre: /es/nosotros/ e /pt/sobre/
    add_rewrite_rule('^(es|pt)/(nosotros|sobre)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=about',
        'top');

    // Equipe
    add_rewrite_rule('^(es|pt)/(equipo|equipe)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=team',
        'top');

    // Contato
    add_rewrite_rule('^(es|pt)/(contacto|contato)/?$',
        'index.php?neblina_lang=$matches[1]&neblina_route=contact',
        'top');
}
add_action('init', 'neblina_rewrite_rules');

/**
 * Intercepta o template usado conforme a rota.
 */
function neblina_template_include($template) {
    $route = get_query_var('neblina_route');
    if (!$route) return $template;

    $map = [
        'home'    => 'front-page.php',
        'catalog' => 'page-catalogo.php',
        'book'    => 'single-livro.php',
        'authors' => 'page-autores.php',
        'author'  => 'single-autor.php',
        'about'   => 'page-sobre.php',
        'team'    => 'page-equipe.php',
        'contact' => 'page-contato.php',
        'cart'    => 'page-carrinho.php',     // rotas da loja: inc/woocommerce.php
        'checkout'=> 'page-checkout.php',
    ];

    if (isset($map[$route])) {
        $file = locate_template($map[$route]);
        if ($file) return $file;
    }
    return $template;
}
add_filter('template_include', 'neblina_template_include');

/**
 * Raiz do site (/) — detecta idioma por Accept-Language e redireciona.
 * Cookie (escolha manual) > Accept-Language > default.
 *
 * Considera subdiretório (ex: /neblina/ no XAMPP) subtraindo o path do home_url.
 */
function neblina_root_redirect() {
    if (is_admin()) return;

    // Path do site considerando subdiretório
    $home_path    = parse_url(home_url('/'), PHP_URL_PATH) ?: '/';
    $current_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    // Remove o prefixo do subdiretório pra pegar só o path "lógico" do WP
    if ($home_path !== '/' && strpos($current_path, $home_path) === 0) {
        $current_path = substr($current_path, strlen($home_path) - 1);
    }
    $trimmed = trim($current_path, '/');

    // Já tem prefixo de idioma? Deixa seguir.
    if ($trimmed !== '' && preg_match('#^(es|pt)(/|$)#', $trimmed)) {
        return;
    }

    // Qualquer outra URL sem idioma que não seja a raiz: deixa WP tratar
    // (feeds, admin, etc já foram descartados acima ou vão pro 404).
    if ($trimmed !== '') return;

    // 1. Cookie (escolha manual anterior)
    if (isset($_COOKIE['neblina_lang']) && in_array($_COOKIE['neblina_lang'], NEBLINA_LANGS, true)) {
        wp_safe_redirect(home_url('/' . $_COOKIE['neblina_lang'] . '/'));
        exit;
    }

    // 2. Accept-Language
    $accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $target = NEBLINA_DEFAULT_LANG;
    if (stripos($accept, 'pt') === 0) {
        $target = 'pt';
    }

    wp_safe_redirect(home_url('/' . $target . '/'));
    exit;
}
add_action('template_redirect', 'neblina_root_redirect', 1);

/**
 * Quando o usuário clica no botão ES/PT, salva a escolha em cookie
 * para a próxima visita respeitar a preferência manual.
 */
function neblina_set_lang_cookie() {
    $lang = get_query_var('neblina_lang');
    if (!$lang || !in_array($lang, NEBLINA_LANGS, true)) return;

    // Só grava se mudou (evita header duplicado)
    if (!isset($_COOKIE['neblina_lang']) || $_COOKIE['neblina_lang'] !== $lang) {
        setcookie('neblina_lang', $lang, [
            'expires'  => time() + YEAR_IN_SECONDS,
            'path'     => '/',
            'secure'   => is_ssl(),
            'httponly' => false,  // JS pode ler se precisar
            'samesite' => 'Lax',
        ]);
    }
}
add_action('template_redirect', 'neblina_set_lang_cookie', 2);

/**
 * Flush das rewrite rules na ativação do tema.
 */
function neblina_flush_rewrites() {
    neblina_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'neblina_flush_rewrites');
