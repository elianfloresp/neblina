<?php
/**
 * Painel enxuto: o cliente vê só Livros, Autores, Banners e os Pedidos da loja.
 *
 * Posts, Comentários e Páginas não são usados no site (call de 21/10).
 * Produtos do WooCommerce são gerados pelos Livros (inc/woocommerce.php).
 * Os itens só saem do menu: as telas continuam acessíveis pela URL.
 */

if (!defined('ABSPATH')) exit;

add_action('admin_menu', function () {
    remove_menu_page('edit.php');                     // Posts
    remove_menu_page('edit-comments.php');            // Comentários
    remove_menu_page('edit.php?post_type=page');      // Páginas
    remove_menu_page('edit.php?post_type=product');   // Produtos
    remove_menu_page('woocommerce-marketing');        // Marketing
}, 999);

// Mesmos atalhos no "+ Novo" da barra superior.
add_action('admin_bar_menu', function ($bar) {
    foreach (['new-post', 'new-page', 'new-product', 'comments'] as $node) {
        $bar->remove_node($node);
    }
}, 999);
