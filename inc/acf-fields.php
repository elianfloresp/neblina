<?php
/**
 * Grupos ACF registrados via PHP (compatível com ACF free).
 *
 * - Usa Tab field pra separar PT, ES e Comum no admin.
 * - Nomes dos campos seguem o padrão {campo}_pt / {campo}_es
 *   para serem lidos por neblina_field('campo') via inc/i18n.php.
 * - Capa do livro, retrato do autor e imagem do banner usam a "Imagem destacada"
 *   nativa do WP (já declarado em cpts.php via supports => ['thumbnail']).
 */

if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    // =============================================================
    // LIVRO
    // =============================================================
    acf_add_local_field_group([
        'key'      => 'group_neblina_livro',
        'title'    => 'Dados do livro (bilíngue)',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'livro']]],
        'position' => 'normal',
        'style'    => 'default',
        'fields'   => [
            // ---------- Aba Português ----------
            ['key' => 'field_livro_tab_pt', 'label' => 'Português', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_livro_titulo_pt',       'label' => 'Título (PT)',    'name' => 'titulo_pt',   'type' => 'text',    'required' => 1],
            ['key' => 'field_livro_sinopse_pt',      'label' => 'Sinopse (PT)',   'name' => 'sinopse_pt',  'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0, 'instructions' => 'Texto que aparece na ficha do livro.'],
            ['key' => 'field_livro_excerto_pt',      'label' => 'Trecho de leitura (PT)', 'name' => 'excerto_pt', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0, 'instructions' => 'Opcional. Aparece no "Fragmento de leitura" expansível.'],
            ['key' => 'field_livro_meta_trad_pt',    'label' => 'Tradutor (PT)',  'name' => 'metadados_tradutor_pt', 'type' => 'text'],
            ['key' => 'field_livro_meta_form_pt',    'label' => 'Formato (PT)',   'name' => 'metadados_formato_pt',  'type' => 'text', 'placeholder' => 'Ex: Capa dura'],
            ['key' => 'field_livro_link_compra_pt',  'label' => 'Link de compra (PT)', 'name' => 'link_compra_pt', 'type' => 'url',
                'instructions' => 'URL HTTPS da loja no mercado brasileiro. Deixa vazio pra desativar o botão em PT.'],

            // ---------- Aba Español ----------
            ['key' => 'field_livro_tab_es', 'label' => 'Español', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_livro_titulo_es',       'label' => 'Título (ES)',    'name' => 'titulo_es',   'type' => 'text',    'required' => 1],
            ['key' => 'field_livro_sinopse_es',      'label' => 'Sinopsis (ES)',  'name' => 'sinopse_es',  'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0],
            ['key' => 'field_livro_excerto_es',      'label' => 'Fragmento (ES)', 'name' => 'excerto_es',  'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0],
            ['key' => 'field_livro_meta_trad_es',    'label' => 'Traducción (ES)','name' => 'metadados_tradutor_es', 'type' => 'text'],
            ['key' => 'field_livro_meta_form_es',    'label' => 'Formato (ES)',   'name' => 'metadados_formato_es',  'type' => 'text', 'placeholder' => 'Ej: Tapa dura'],
            ['key' => 'field_livro_link_compra_es',  'label' => 'Link de compra (ES)', 'name' => 'link_compra_es', 'type' => 'url',
                'instructions' => 'URL HTTPS de la tienda en el mercado español. Dejar vacío para desactivar el botón en ES.'],

            // ---------- Aba Comum ----------
            ['key' => 'field_livro_tab_comum', 'label' => 'Comum (nos 2 idiomas)', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_livro_autor', 'label' => 'Autor relacionado', 'name' => 'autor_relacionado', 'type' => 'post_object',
                'post_type' => ['autor'], 'return_format' => 'object', 'ui' => 1, 'allow_null' => 1,
                'instructions' => 'Autor cadastrado no admin > Autores.'],
            ['key' => 'field_livro_meta_isbn',    'label' => 'ISBN',    'name' => 'metadados_isbn',    'type' => 'text',
                'instructions' => 'Mesmo nos 2 idiomas.'],
            ['key' => 'field_livro_meta_paginas', 'label' => 'Páginas', 'name' => 'metadados_paginas', 'type' => 'number'],
            ['key' => 'field_livro_meta_ano',     'label' => 'Ano',     'name' => 'metadados_ano',     'type' => 'number'],
            ['key' => 'field_livro_dica_capa', 'label' => '', 'type' => 'message',
                'message' => '<strong>Capa:</strong> use o campo nativo <em>Imagem destacada</em> do WordPress (coluna da direita).'],
        ],
    ]);

    // =============================================================
    // AUTOR
    // =============================================================
    acf_add_local_field_group([
        'key'      => 'group_neblina_autor',
        'title'    => 'Dados do autor (bilíngue)',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'autor']]],
        'position' => 'normal',
        'style'    => 'default',
        'fields'   => [
            // ---------- Aba Português ----------
            ['key' => 'field_autor_tab_pt', 'label' => 'Português', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_autor_genero_pt', 'label' => 'Gênero (PT)', 'name' => 'genero_pt', 'type' => 'text',
                'placeholder' => 'Ex: Ficção, Filosofia, Antropologia'],
            ['key' => 'field_autor_micro_pt', 'label' => 'Micro-descrição (PT)', 'name' => 'micro_pt', 'type' => 'textarea', 'rows' => 2,
                'instructions' => 'Frase curta que aparece no card da listagem.'],
            ['key' => 'field_autor_bio_pt', 'label' => 'Biografia (PT)', 'name' => 'bio_pt', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0,
                'instructions' => 'Bio longa que aparece no perfil do autor.'],

            // ---------- Aba Español ----------
            ['key' => 'field_autor_tab_es', 'label' => 'Español', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_autor_genero_es', 'label' => 'Género (ES)', 'name' => 'genero_es', 'type' => 'text',
                'placeholder' => 'Ej: Ficción, Filosofía, Antropología'],
            ['key' => 'field_autor_micro_es', 'label' => 'Micro-descripción (ES)', 'name' => 'micro_es', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_autor_bio_es', 'label' => 'Biografía (ES)', 'name' => 'bio_es', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0],

            // ---------- Dica ----------
            ['key' => 'field_autor_dica_retrato', 'label' => '', 'type' => 'message',
                'message' => '<strong>Retrato:</strong> use o campo nativo <em>Imagem destacada</em> do WordPress (coluna da direita).'],
        ],
    ]);

    // =============================================================
    // BANNER
    // =============================================================
    acf_add_local_field_group([
        'key'      => 'group_neblina_banner',
        'title'    => 'Dados do banner (bilíngue)',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'banner']]],
        'position' => 'normal',
        'style'    => 'default',
        'fields'   => [
            // ---------- Aba Português ----------
            ['key' => 'field_banner_tab_pt', 'label' => 'Português', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_banner_eyebrow_pt', 'label' => 'Chamada superior (PT)', 'name' => 'eyebrow_pt', 'type' => 'text',
                'placeholder' => 'Ex: MANIFESTO NEBLINA'],
            ['key' => 'field_banner_titulo_pt',  'label' => 'Título principal (PT)', 'name' => 'titulo_pt',  'type' => 'textarea', 'rows' => 3,
                'instructions' => 'Texto grande que aparece sobre a imagem.'],

            // ---------- Aba Español ----------
            ['key' => 'field_banner_tab_es', 'label' => 'Español', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_banner_eyebrow_es', 'label' => 'Antetítulo (ES)',       'name' => 'eyebrow_es', 'type' => 'text',
                'placeholder' => 'Ej: MANIFIESTO NEBLINA'],
            ['key' => 'field_banner_titulo_es',  'label' => 'Título principal (ES)', 'name' => 'titulo_es',  'type' => 'textarea', 'rows' => 3],

            // ---------- Aba Comum ----------
            ['key' => 'field_banner_tab_comum', 'label' => 'Comum', 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_banner_link', 'label' => 'Link de destino', 'name' => 'link_destino', 'type' => 'url',
                'instructions' => 'URL HTTPS ou caminho tipo /pt/livro/dom-casmurro/. Opcional.'],
            ['key' => 'field_banner_dica_img', 'label' => '', 'type' => 'message',
                'message' => '<strong>Imagem de fundo:</strong> use o campo nativo <em>Imagem destacada</em> (coluna da direita). Recomendado: 1920×900.<br><strong>Ordem dos banners:</strong> use o campo <em>Atributos</em> → <em>Ordem</em>.'],
        ],
    ]);
});
