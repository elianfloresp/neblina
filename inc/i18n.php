<?php
/**
 * i18n — bilíngue PT/ES sem plugin.
 *
 * Como funciona:
 * - A URL começa com /es/ ou /pt/ (ver inc/rewrite.php).
 * - neblina_lang() lê o idioma da query var.
 * - neblina_field('titulo') puxa get_field('titulo_' . lang).
 * - neblina_i('nav_books') devolve string de UI no idioma ativo.
 * - neblina_url('livros') monta URL com prefixo e slug localizado.
 */

if (!defined('ABSPATH')) exit;

/**
 * Faz language_attributes() refletir o idioma ativo da URL.
 */
function neblina_filter_language_attributes($output) {
    $lang = neblina_lang();
    $attr = $lang === 'pt' ? 'pt-BR' : 'es-ES';
    return 'lang="' . esc_attr($attr) . '"';
}
add_filter('language_attributes', 'neblina_filter_language_attributes');

/**
 * Idioma ativo da requisição atual.
 */
function neblina_lang() {
    $lang = get_query_var('neblina_lang', '');
    if (in_array($lang, NEBLINA_LANGS, true)) {
        return $lang;
    }
    if (isset($_COOKIE['neblina_lang']) && in_array($_COOKIE['neblina_lang'], NEBLINA_LANGS, true)) {
        return sanitize_text_field($_COOKIE['neblina_lang']);
    }
    return NEBLINA_DEFAULT_LANG;
}

/**
 * ACF field com sufixo de idioma. Faz fallback pro default se vazio.
 * Uso nos templates:
 *   <?php echo esc_html(neblina_field('titulo')); ?>
 */
function neblina_field($field_name, $post_id = null) {
    $lang = neblina_lang();
    $value = get_field($field_name . '_' . $lang, $post_id);
    if (empty($value) && $lang !== NEBLINA_DEFAULT_LANG) {
        $value = get_field($field_name . '_' . NEBLINA_DEFAULT_LANG, $post_id);
    }
    return $value;
}

/**
 * Textos de interface fixos (header, menu, botões, labels).
 * Para adicionar/trocar frase: edita o array abaixo.
 */
function neblina_i($key) {
    static $strings = null;
    if ($strings === null) {
        // Base: frases do protótipo v14, sem os avisos de demonstração.
        $strings = [
            // Estrutura
            'skip_to_content'  => ['es' => 'Saltar al contenido',          'pt' => 'Pular para o conteúdo'],
            'primary_nav'      => ['es' => 'Navegación principal',         'pt' => 'Navegação principal'],
            'home'             => ['es' => 'Inicio',                       'pt' => 'Início'],
            'home_lower'       => ['es' => 'inicio',                       'pt' => 'início'],
            'menu_open'        => ['es' => 'Menú ☰',                       'pt' => 'Menu ☰'],
            'menu_close'       => ['es' => 'Cerrar ×',                     'pt' => 'Fechar ×'],
            'toggle_theme'     => ['es' => 'Alternar modo claro / oscuro', 'pt' => 'Alternar modo claro / escuro'],
            'nav_books'        => ['es' => 'Libros',                       'pt' => 'Livros'],
            'nav_authors'      => ['es' => 'Autores',                      'pt' => 'Autores'],
            'nav_about'        => ['es' => 'Nosotros',                     'pt' => 'Sobre'],
            'nav_team'         => ['es' => 'Equipo',                       'pt' => 'Equipe'],
            'nav_contact'      => ['es' => 'Contacto',                     'pt' => 'Contato'],
            'footer_rights'    => ['es' => 'Todos los derechos reservados', 'pt' => 'Todos os direitos reservados'],
            'error_404_title'  => ['es' => 'Página no encontrada.',        'pt' => 'Página não encontrada.'],
            'back_to_home'     => ['es' => 'Volver al inicio',             'pt' => 'Voltar ao início'],
            'fallback_text'    => ['es' => 'Contenido en construcción.',   'pt' => 'Conteúdo em construção.'],

            // Home
            'banner_region'    => ['es' => 'Destacados de Neblina',        'pt' => 'Destaques da Neblina'],
            'see_catalog'      => ['es' => 'Ver catálogo',                 'pt' => 'Ver catálogo'],
            'manifesto_kicker' => ['es' => 'MUCHO GUSTO, SOMOS NEBLINA',   'pt' => 'MUITO PRAZER, SOMOS NEBLINA'],
            'know_neblina'     => ['es' => 'Conocer Neblina',              'pt' => 'Conhecer a Neblina'],
            'team_heading'     => ['es' => 'Quienes hacen Neblina',        'pt' => 'Quem faz a Neblina'],
            'our_team'         => ['es' => 'Nuestro equipo',               'pt' => 'Nossa equipe'],

            // Livros
            'catalog_kicker'   => ['es' => 'NUESTROS LIBROS',              'pt' => 'NOSSOS LIVROS'],
            'catalog_title'    => ['es' => 'El catálogo.',                 'pt' => 'O catálogo.'],
            'catalog_lead'     => ['es' => 'Voces brasileñas, nuevas travesías.', 'pt' => 'Vozes brasileiras, novas travessias.'],
            'card_meta'        => ['es' => 'NEBLINA EDITORIAL',            'pt' => 'NEBLINA EDITORIAL'],
            'see_sheet'        => ['es' => 'Ver ficha:',                   'pt' => 'Ver ficha:'],
            'know_book'        => ['es' => 'Conocer el libro',             'pt' => 'Conhecer o livro'],
            'cover_of'         => ['es' => 'Portada de',                   'pt' => 'Capa de'],
            'cover_kicker'     => ['es' => 'NEBLINA EDITORIAL',            'pt' => 'NEBLINA EDITORIAL'],
            'cover_title'      => ['es' => 'Un libro<br>por venir.',       'pt' => 'Um livro<br>por vir.'],
            'cover_soon'       => ['es' => 'PRÓXIMAMENTE',                 'pt' => 'EM BREVE'],
            'showing'          => ['es' => 'Mostrando',                    'pt' => 'Mostrando'],
            'of'               => ['es' => 'de',                           'pt' => 'de'],
            'books_lower'      => ['es' => 'libros',                       'pt' => 'livros'],
            'page'             => ['es' => 'Página',                       'pt' => 'Página'],
            'book_pages'       => ['es' => 'Páginas de libros',            'pt' => 'Páginas de livros'],
            'prev'             => ['es' => '← Anterior',                   'pt' => '← Anterior'],
            'next'             => ['es' => 'Siguiente →',                  'pt' => 'Próxima →'],
            'back_to_catalog'  => ['es' => 'Volver al catálogo',           'pt' => 'Voltar ao catálogo'],
            'about_book'       => ['es' => 'Sobre el libro',               'pt' => 'Sobre o livro'],
            'buy_disabled'     => ['es' => 'Comprar libro',                'pt' => 'Comprar'],
            'buy_soon'         => ['es' => 'Disponible próximamente.',     'pt' => 'Disponível em breve.'],
            'add_to_cart'      => ['es' => 'Añadir al carrito',            'pt' => 'Adicionar ao carrinho'],
            'added_to_cart'    => ['es' => 'Libro añadido al carrito.',    'pt' => 'Livro adicionado ao carrinho.'],
            'see_cart'         => ['es' => 'Ver carrito',                  'pt' => 'Ver carrinho'],
            'sold_out'         => ['es' => 'Agotado',                      'pt' => 'Esgotado'],
            'vat_included'     => ['es' => 'IVA incluido. Envíos a España y la Unión Europea.', 'pt' => 'IVA incluído. Envios para Espanha e União Europeia.'],
            'editorial_info'   => ['es' => 'Información editorial',        'pt' => 'Informações editoriais'],
            'read_excerpt'     => ['es' => 'Fragmento de lectura',         'pt' => 'Trecho de leitura'],
            'browse_books'     => ['es' => 'Navegar entre libros',         'pt' => 'Navegar entre livros'],
            'prev_book'        => ['es' => '← Libro anterior',             'pt' => '← Livro anterior'],
            'next_book'        => ['es' => 'Siguiente libro →',            'pt' => 'Próximo livro →'],
            'other_books'      => ['es' => 'Otros libros',                 'pt' => 'Outros livros'],

            // Autores
            'authors_kicker'   => ['es' => 'VOCES DEL CATÁLOGO',           'pt' => 'VOZES DO CATÁLOGO'],
            'authors_lead'     => ['es' => 'Cada voz, una forma de atravesar la neblina.', 'pt' => 'Cada voz, uma forma de atravessar a neblina.'],
            'authors_region'   => ['es' => 'Primeras voces de Neblina',    'pt' => 'Primeiras vozes da Neblina'],
            'authors_fronts'   => ['es' => 'FICCIÓN Y PENSAMIENTO CRÍTICO','pt' => 'FICÇÃO E PENSAMENTO CRÍTICO'],
            'curatorship'      => ['es' => 'Curaduría de estreno',         'pt' => 'Curadoria de estreia'],
            'know_author'      => ['es' => 'Conocer la voz',               'pt' => 'Conhecer a voz'],
            'all_authors'      => ['es' => 'Todos los autores',            'pt' => 'Todos os autores'],
            'about_writing'    => ['es' => 'Sobre su escritura',           'pt' => 'Sobre a escrita'],
            'author_books'     => ['es' => 'Libros',                       'pt' => 'Livros'],
            'editions_heading' => ['es' => 'Ediciones en preparación',     'pt' => 'Edições em preparação'],
            'editions_soon'    => ['es' => 'Los títulos de esta voz llegarán pronto al catálogo de Neblina.', 'pt' => 'Os títulos desta voz chegam em breve ao catálogo da Neblina.'],
            'other_voices'     => ['es' => 'Otras voces',                  'pt' => 'Outras vozes'],

            // Equipe e contato
            'team_kicker'      => ['es' => 'EL EQUIPO',                    'pt' => 'A EQUIPE'],
            'team_title'       => ['es' => 'Tres miradas.<br>Una travesía.', 'pt' => 'Três olhares.<br>Uma travessia.'],
            'editorial_team'   => ['es' => 'Equipo editorial',             'pt' => 'Equipe editorial'],
            'portrait_of'      => ['es' => 'Retrato de',                   'pt' => 'Retrato de'],
            'contact_kicker'   => ['es' => 'CONTACTO',                     'pt' => 'CONTATO'],
            'contact_title'    => ['es' => 'Toda travesía empieza<br>con una conversación.', 'pt' => 'Toda travessia começa<br>com uma conversa.'],
        ];
    }
    $lang = neblina_lang();
    return $strings[$key][$lang] ?? $key;
}

/**
 * Mapa de slugs por idioma. Permite URLs localizadas tipo /es/libros/ e /pt/livros/.
 */
function neblina_route_slugs() {
    return [
        'es' => [
            'livros'   => 'libros',
            'autores'  => 'autores',
            'sobre'    => 'nosotros',
            'equipe'   => 'equipo',
            'contato'  => 'contacto',
            'livro'    => 'libro',
            'autor'    => 'autor',
        ],
        'pt' => [
            'livros'   => 'livros',
            'autores'  => 'autores',
            'sobre'    => 'sobre',
            'equipe'   => 'equipe',
            'contato'  => 'contato',
            'livro'    => 'livro',
            'autor'    => 'autor',
        ],
    ];
}

/**
 * Monta URL completa com prefixo de idioma e slug localizado.
 *   neblina_url('livros')                   → /es/libros/
 *   neblina_url('livro', 'dom-casmurro')    → /es/libro/dom-casmurro/
 */
function neblina_url($route_key, $slug = '') {
    $lang  = neblina_lang();
    $slugs = neblina_route_slugs();
    $route = $slugs[$lang][$route_key] ?? $route_key;
    $path  = '/' . $lang . '/' . $route . ($slug ? '/' . $slug : '') . '/';
    return home_url($path);
}

/**
 * URL atual trocada para o outro idioma — usada no botão ES/PT do header.
 * Mapeia slugs localizados: /pt/sobre/ → /es/nosotros/, /pt/equipe/ → /es/equipo/, etc.
 * Pra single-livro e single-autor, mantém o slug do post (ex: /pt/livro/x/ → /es/libro/x/).
 */
function neblina_switch_lang_url($new_lang) {
    if (!in_array($new_lang, NEBLINA_LANGS, true)) {
        return home_url('/' . NEBLINA_DEFAULT_LANG . '/');
    }

    $route = get_query_var('neblina_route', 'home');
    $slug  = get_query_var('neblina_slug', '');

    // Mapa de rota lógica -> route_key da tabela de slugs
    $route_to_key = [
        'home'    => null,       // só o prefixo /{lang}/
        'catalog' => 'livros',
        'book'    => 'livro',
        'authors' => 'autores',
        'author'  => 'autor',
        'about'   => 'sobre',
        'team'    => 'equipe',
        'contact' => 'contato',
    ];

    // Fallback: se rota desconhecida, manda pra home do novo idioma
    if (!array_key_exists($route, $route_to_key)) {
        return home_url('/' . $new_lang . '/');
    }

    $route_key = $route_to_key[$route];

    // Home só precisa do prefixo
    if ($route_key === null) {
        return home_url('/' . $new_lang . '/');
    }

    // Pega o slug localizado no NOVO idioma
    $slugs = neblina_route_slugs();
    $localized_slug = $slugs[$new_lang][$route_key] ?? $route_key;

    $path = '/' . $new_lang . '/' . $localized_slug;
    if ($slug) {
        $path .= '/' . $slug;
    }
    $path .= '/';

    return home_url($path);
}

/**
 * Home no idioma atual.
 */
function neblina_home_url() {
    return home_url('/' . neblina_lang() . '/');
}

/**
 * Renderiza o menu principal no idioma atual.
 * Marca o item ativo com aria-current="page" (necessário pro ~til aparecer no CSS).
 */
function neblina_render_menu() {
    // Mapa de rotas -> item do menu que deve destacar
    $route = get_query_var('neblina_route', 'home');
    $route_to_item = [
        'home'    => 'home',
        'catalog' => 'livros',
        'book'    => 'livros',
        'authors' => 'autores',
        'author'  => 'autores',
        'about'   => 'sobre',
        'team'    => 'equipe',
        'contact' => 'contato',
    ];
    $active = $route_to_item[$route] ?? '';

    $items = [
        ['route' => null,       'key' => 'home',     'label' => neblina_i('home')],
        ['route' => 'livros',   'key' => 'livros',   'label' => neblina_i('nav_books')],
        ['route' => 'autores',  'key' => 'autores',  'label' => neblina_i('nav_authors')],
        ['route' => 'sobre',    'key' => 'sobre',    'label' => neblina_i('nav_about')],
        ['route' => 'equipe',   'key' => 'equipe',   'label' => neblina_i('nav_team')],
        ['route' => 'contato',  'key' => 'contato',  'label' => neblina_i('nav_contact')],
    ];
    foreach ($items as $item) {
        $url  = $item['route'] ? neblina_url($item['route']) : neblina_home_url();
        $attr = $item['key'] === $active ? ' aria-current="page"' : '';
        printf('<a href="%s"%s>%s</a>', esc_url($url), $attr, esc_html($item['label']));
    }
}
