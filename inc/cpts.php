<?php
/**
 * Custom Post Types da Neblina.
 *
 * Livro:    público, com template custom (single-livro.php via rewrite).
 * Autor:    idem.
 * Banner:   admin-only, usado só na home (sem single).
 *
 * Grupos ACF devem ser criados no admin WP depois (Custom Fields > Add New).
 * Padrão de naming dos campos: {nome}_pt e {nome}_es.
 * Ex: titulo_pt, titulo_es, sinopse_pt, sinopse_es, autor_relacionado, capa, link_compra.
 */

if (!defined('ABSPATH')) exit;

function neblina_register_cpts() {
    // ========== LIVRO ==========
    register_post_type('livro', [
        'labels' => [
            'name'               => 'Livros',
            'singular_name'      => 'Livro',
            'add_new'            => 'Adicionar novo',
            'add_new_item'       => 'Adicionar novo livro',
            'edit_item'          => 'Editar livro',
            'new_item'           => 'Novo livro',
            'view_item'          => 'Ver livro',
            'search_items'       => 'Buscar livros',
            'not_found'          => 'Nenhum livro encontrado',
            'not_found_in_trash' => 'Nenhum livro na lixeira',
            'menu_name'          => 'Livros',
        ],
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-book-alt',
        'has_archive'         => false,       // controlamos via rewrite custom
        'rewrite'             => false,       // idem
        'supports'            => ['title', 'thumbnail', 'page-attributes'],
        'hierarchical'        => false,
    ]);

    // ========== AUTOR ==========
    register_post_type('autor', [
        'labels' => [
            'name'               => 'Autores',
            'singular_name'      => 'Autor',
            'add_new'            => 'Adicionar novo',
            'add_new_item'       => 'Adicionar novo autor',
            'edit_item'          => 'Editar autor',
            'new_item'           => 'Novo autor',
            'view_item'          => 'Ver autor',
            'search_items'       => 'Buscar autores',
            'not_found'          => 'Nenhum autor encontrado',
            'menu_name'          => 'Autores',
        ],
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'show_in_menu'        => true,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-id-alt',
        'has_archive'         => false,
        'rewrite'             => false,
        'supports'            => ['title', 'thumbnail', 'page-attributes'],
        'hierarchical'        => false,
    ]);

    // ========== BANNER ==========
    // Usado só pra popular o carrossel da home. Sem single público.
    register_post_type('banner', [
        'labels' => [
            'name'          => 'Banners',
            'singular_name' => 'Banner',
            'add_new_item'  => 'Adicionar novo banner',
            'edit_item'     => 'Editar banner',
            'menu_name'     => 'Banners',
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_rest'        => false,
        'menu_position'       => 7,
        'menu_icon'           => 'dashicons-images-alt2',
        'supports'            => ['title', 'thumbnail', 'page-attributes'],
    ]);
}
add_action('init', 'neblina_register_cpts');
