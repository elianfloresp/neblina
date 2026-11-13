<?php
/**
 * Endurecimento básico pro site no ar.
 *
 * - A lista de usuários da API REST só responde pra quem está logado
 *   (sem isso, /wp-json/wp/v2/users mostra o login do admin pra qualquer um).
 * - XML-RPC desligado: o site não usa, e é porta comum de tentativa de senha.
 * - Sem a versão do WordPress/WooCommerce no HTML.
 */

if (!defined('ABSPATH')) exit;

add_filter('rest_endpoints', function ($endpoints) {
    if (is_user_logged_in()) return $endpoints;
    foreach (array_keys($endpoints) as $rota) {
        if (strpos($rota, '/wp/v2/users') === 0) unset($endpoints[$rota]);
    }
    return $endpoints;
});

add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

remove_action('wp_head', 'wp_generator');
