<?php
/**
 * Loja — cada Livro tem um produto "espelho" no WooCommerce.
 *
 * O cliente só cadastra o Livro (aba Venda: preço, estoque, peso). Ao salvar,
 * o produto é criado/atualizado sozinho e fica escondido no admin.
 *
 * - Livro → produto: título, capa, preço, estoque, peso, ISBN (vira o SKU) e status.
 * - Estoque: a fonte é o produto (baixa a cada venda); a aba Venda sempre mostra o valor atual.
 * - Nome no carrinho/checkout/e-mail: título do livro no idioma de quem compra.
 * - Link do produto aponta para a ficha do livro; /produto/... redireciona pra ela.
 */

if (!defined('ABSPATH')) exit;

const NEBLINA_META_PRODUTO = '_neblina_produto_id';
const NEBLINA_META_LIVRO   = '_neblina_livro_id';

/**
 * Produto ligado ao livro (ou null se ainda não existe).
 */
function neblina_book_product($livro_id) {
    if (!function_exists('wc_get_product')) return null;
    $product_id = (int) get_post_meta($livro_id, NEBLINA_META_PRODUTO, true);
    $product    = $product_id ? wc_get_product($product_id) : null;
    return $product ?: null;
}

/**
 * Livro ligado ao produto (ou 0).
 */
function neblina_product_book_id($product) {
    if (!$product instanceof WC_Product) return 0;
    $id = $product->get_parent_id() ?: $product->get_id();
    return (int) get_post_meta($id, NEBLINA_META_LIVRO, true);
}

/**
 * Cria ou atualiza o produto a partir do livro.
 */
function neblina_sync_book_product($livro_id) {
    $livro = get_post($livro_id);
    if (!$livro || $livro->post_type !== 'livro' || !function_exists('wc_get_product')) return;
    if (in_array($livro->post_status, ['auto-draft', 'inherit'], true)) return;

    $product = neblina_book_product($livro_id) ?: new WC_Product_Simple();

    $titulo = get_field('titulo_' . NEBLINA_DEFAULT_LANG, $livro_id) ?: $livro->post_title;
    $preco  = get_field('venda_preco', $livro_id);
    $peso   = get_field('venda_peso', $livro_id);

    $product->set_name($titulo);
    $product->set_slug($livro->post_name);
    $product->set_status($livro->post_status === 'publish' ? 'publish' : 'draft');
    $product->set_catalog_visibility('hidden');
    $product->set_regular_price($preco === '' || $preco === null ? '' : wc_format_decimal($preco));
    $product->set_manage_stock(true);
    // Valor gravado, não get_field(): o filtro abaixo devolveria o estoque antigo do produto.
    $product->set_stock_quantity((int) get_post_meta($livro_id, 'venda_estoque', true));
    $product->set_weight($peso === '' || $peso === null ? '' : wc_format_decimal($peso));
    $product->set_image_id(get_post_thumbnail_id($livro_id));
    $product->set_tax_status('taxable');
    $product->set_reviews_allowed(false);

    // ISBN como SKU. Se já existir em outro produto, o WooCommerce recusa: fica sem SKU.
    try {
        $product->set_sku((string) get_field('metadados_isbn', $livro_id));
    } catch (WC_Data_Exception $e) {
        $product->set_sku('');
    }

    $product_id = $product->save();
    update_post_meta($livro_id, NEBLINA_META_PRODUTO, $product_id);
    update_post_meta($product_id, NEBLINA_META_LIVRO, $livro_id);
}

// Roda depois do ACF gravar os campos do livro.
add_action('acf/save_post', function ($post_id) {
    if (is_numeric($post_id) && get_post_type($post_id) === 'livro') {
        neblina_sync_book_product((int) $post_id);
    }
}, 20);

// Publicar / despublicar / lixeira pela listagem também chega no produto.
add_action('transition_post_status', function ($new, $old, $post) {
    if ($post->post_type !== 'livro' || $new === $old) return;
    $product = neblina_book_product($post->ID);
    if (!$product) return;
    if ($new === 'trash') {
        $product->delete(false);
    } elseif ($old === 'trash') {
        wp_untrash_post($product->get_id());
        neblina_sync_book_product($post->ID);
    } else {
        $product->set_status($new === 'publish' ? 'publish' : 'draft');
        $product->save();
    }
}, 10, 3);

// Livro apagado de vez → produto apagado de vez.
add_action('before_delete_post', function ($post_id) {
    if (get_post_type($post_id) !== 'livro') return;
    $product = neblina_book_product($post_id);
    if ($product) $product->delete(true);
});

// A aba Venda mostra o estoque real do produto (que muda com as vendas).
add_filter('acf/load_value/name=venda_estoque', function ($value, $post_id) {
    $product = is_numeric($post_id) ? neblina_book_product((int) $post_id) : null;
    return $product ? $product->get_stock_quantity() : $value;
}, 10, 2);

// Mesmo preço final em toda a UE: sem isso o Woo tira o IVA espanhol de quem compra de fora da Espanha.
// FICTÍCIO até o contador definir o IVA das vendas pra outros países (OSS).
add_filter('woocommerce_adjust_non_base_location_prices', '__return_false');

// Nome do produto no idioma do site (carrinho, checkout e itens do pedido).
add_filter('woocommerce_product_get_name', function ($name, $product) {
    if (is_admin() && !wp_doing_ajax()) return $name;
    $livro_id = neblina_product_book_id($product);
    return $livro_id ? (neblina_field('titulo', $livro_id) ?: $name) : $name;
}, 10, 2);

// Link do produto = ficha do livro.
add_filter('post_type_link', function ($url, $post) {
    if ($post->post_type !== 'product') return $url;
    $livro_id = (int) get_post_meta($post->ID, NEBLINA_META_LIVRO, true);
    return $livro_id ? neblina_url('livro', get_post_field('post_name', $livro_id)) : $url;
}, 10, 2);

// Páginas da loja padrão do Woo (vitrine, produto, categorias) não existem no site.
add_action('template_redirect', function () {
    if (!function_exists('is_woocommerce')) return;
    if (is_singular('product')) {
        $livro_id = (int) get_post_meta(get_queried_object_id(), NEBLINA_META_LIVRO, true);
        wp_safe_redirect($livro_id ? neblina_url('livro', get_post_field('post_name', $livro_id)) : neblina_url('livros'), 301);
        exit;
    }
    if (is_shop() || is_product_taxonomy()) {
        wp_safe_redirect(neblina_url('livros'), 301);
        exit;
    }
});

// Depois de adicionar ao carrinho, volta pra ficha (evita reenviar o formulário no F5).
add_filter('woocommerce_add_to_cart_redirect', function ($url, $product) {
    $livro_id = neblina_product_book_id($product);
    return $livro_id ? neblina_url('livro', get_post_field('post_name', $livro_id)) : $url;
}, 10, 2);

// Mensagem "adicionado ao carrinho" com os textos do site.
add_filter('wc_add_to_cart_message_html', function () {
    return sprintf('%s <a class="text-link" href="%s">%s</a>',
        esc_html(neblina_i('added_to_cart')), esc_url(wc_get_cart_url()), esc_html(neblina_i('see_cart')));
});

/**
 * Avisos da loja (adicionado ao carrinho, sem estoque…) no visual do site.
 */
function neblina_print_shop_notices() {
    if (!function_exists('wc_get_notices')) return;
    foreach (wc_get_notices() as $type => $notices) {
        foreach ($notices as $notice) {
            printf('<div class="shop-notice is-%s" role="%s">%s</div>',
                esc_attr($type), $type === 'error' ? 'alert' : 'status', wp_kses_post($notice['notice']));
        }
    }
    wc_clear_notices();
}

// Colunas Preço e Estoque na listagem de Livros do admin.
add_filter('manage_livro_posts_columns', function ($cols) {
    return array_slice($cols, 0, 2, true) + ['neblina_preco' => 'Preço', 'neblina_estoque' => 'Estoque'] + $cols;
});
add_action('manage_livro_posts_custom_column', function ($col, $livro_id) {
    $product = neblina_book_product($livro_id);
    if ($col === 'neblina_preco') {
        echo $product && $product->get_price() !== '' ? wp_kses_post(wc_price($product->get_price())) : '—';
    } elseif ($col === 'neblina_estoque') {
        echo $product ? esc_html((string) $product->get_stock_quantity()) : '—';
    }
}, 10, 2);
