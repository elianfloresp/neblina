<?php
/**
 * Ficha do livro — bookPage() do protótipo.
 */
$slug  = get_query_var('neblina_slug');
$livro = $slug ? get_page_by_path($slug, OBJECT, 'livro') : null;

if (!$livro):
    status_header(404);
    get_header();
    get_template_part('template-parts/not-found');
    get_footer();
    return;
endif;

get_header();

$titulo      = neblina_field('titulo', $livro->ID) ?: get_the_title($livro);
$sinopse     = neblina_field('sinopse', $livro->ID);
$excerto     = neblina_field('excerto', $livro->ID);
$autor       = neblina_book_author($livro->ID);
$produto     = neblina_book_product($livro->ID);
$a_venda     = $produto && $produto->get_status() === 'publish' && $produto->is_purchasable();

// [rótulo ES, rótulo PT, valor] — campos vazios não aparecem na ficha
$metadados = array_filter([
    ['Traducción', 'Tradução', neblina_field('metadados_tradutor', $livro->ID)],
    ['ISBN',       'ISBN',     get_field('metadados_isbn', $livro->ID)],
    ['Páginas',    'Páginas',  get_field('metadados_paginas', $livro->ID)],
    ['Formato',    'Formato',  neblina_field('metadados_formato', $livro->ID)],
    ['Año',        'Ano',      get_field('metadados_ano', $livro->ID)],
], fn($m) => trim((string) $m[2]) !== '');

// Anterior / próximo na ordem do catálogo
$ids  = get_posts([
    'post_type'      => 'livro',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
    'fields'         => 'ids',
]);
$pos  = array_search($livro->ID, $ids, true);
$prev = $pos > 0 ? $ids[$pos - 1] : null;
$next = $pos !== false && $pos < count($ids) - 1 ? $ids[$pos + 1] : null;
$book_title = fn($id) => neblina_field('titulo', $id) ?: get_the_title($id);
?>

<div class="shell">
    <div class="book-back">
        <a class="text-link" href="<?php echo esc_url(neblina_url('livros')); ?>"><?php echo neblina_i('back_to_catalog'); ?></a>
    </div>

    <section class="book-detail">
        <div class="detail-art"><?php neblina_book_cover($livro->ID, $titulo); ?></div>

        <div class="book-info">
            <span class="eyebrow"><?php echo neblina_i('catalog_kicker'); ?></span>
            <h1><?php echo esc_html($titulo); ?></h1>
            <?php if ($autor): ?>
                <p><?php if ($autor['url']): ?><a href="<?php echo esc_url($autor['url']); ?>"><?php echo esc_html($autor['name']); ?></a><?php else: echo esc_html($autor['name']); endif; ?></p>
            <?php endif; ?>

            <?php if ($sinopse): ?>
                <h2><?php echo neblina_i('about_book'); ?></h2>
                <div class="book-synopsis rich-text"><?php echo wp_kses_post($sinopse); ?></div>
            <?php endif; ?>

            <div class="book-buy">
                <?php neblina_print_shop_notices(); ?>
                <?php if ($a_venda): ?>
                    <p class="book-price"><?php echo wp_kses_post(wc_price(wc_get_price_to_display($produto))); ?></p>
                    <?php if ($produto->is_in_stock()):
                        $estoque     = $produto->get_stock_quantity();
                        $poucos      = $estoque !== null && $estoque <= (int) get_option('woocommerce_notify_low_stock_amount', 3);
                        $no_carrinho = WC()->cart ? (WC()->cart->get_cart_item_quantities()[$produto->get_id()] ?? 0) : 0;
                        $disponivel  = $estoque === null ? -1 : max(0, $estoque - $no_carrinho); ?>
                        <?php if ($disponivel === 0): ?>
                            <a class="button" href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php echo neblina_i('see_cart'); ?></a>
                            <p class="book-stock"><?php echo neblina_i('all_in_cart'); ?></p>
                        <?php else: ?>
                            <form class="book-buy-form" method="post" action="<?php echo esc_url(neblina_url('livro', $livro->post_name)); ?>">
                                <?php woocommerce_quantity_input([
                                    'min_value'    => 1,
                                    'max_value'    => $disponivel,
                                    'input_value'  => 1,
                                    'product_name' => $titulo,
                                ], $produto); ?>
                                <button class="button" type="submit" name="add-to-cart" value="<?php echo esc_attr($produto->get_id()); ?>"><?php echo neblina_i('add_to_cart'); ?></button>
                            </form>
                        <?php endif; ?>
                        <?php if ($poucos && $disponivel !== 0): ?>
                            <p class="book-stock"><?php printf(esc_html(neblina_i($estoque === 1 ? 'last_copy' : 'last_copies')), (int) $estoque); ?></p>
                        <?php endif; ?>
                        <p class="note"><?php echo neblina_i('vat_included'); ?></p>
                    <?php else: ?>
                        <button class="button" type="button" disabled><?php echo neblina_i('sold_out'); ?></button>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="button" type="button" disabled aria-describedby="buy-note"><?php echo neblina_i('buy_disabled'); ?></button>
                    <p class="note" id="buy-note"><?php echo neblina_i('buy_soon'); ?></p>
                <?php endif; ?>
            </div>

            <?php if ($metadados): ?>
                <h2><?php echo neblina_i('editorial_info'); ?></h2>
                <dl class="editorial-data">
                    <?php foreach ($metadados as [$es, $pt, $valor]): ?>
                        <div>
                            <dt><?php echo esc_html(neblina_lang() === 'pt' ? $pt : $es); ?></dt>
                            <dd><?php echo esc_html($valor); ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>

            <?php if ($excerto): ?>
                <details>
                    <summary><?php echo neblina_i('read_excerpt'); ?></summary>
                    <div class="book-excerpt rich-text"><?php echo wp_kses_post($excerto); ?></div>
                </details>
            <?php endif; ?>
        </div>
    </section>

    <nav class="book-sequence" aria-label="<?php echo esc_attr(neblina_i('browse_books')); ?>">
        <?php if ($prev): ?>
            <a class="text-link" href="<?php echo esc_url(neblina_url('livro', get_post_field('post_name', $prev))); ?>"><?php echo neblina_i('prev_book'); ?><span><?php echo esc_html($book_title($prev)); ?></span></a>
        <?php else: ?><span></span><?php endif; ?>
        <?php if ($next): ?>
            <a class="text-link" href="<?php echo esc_url(neblina_url('livro', get_post_field('post_name', $next))); ?>"><?php echo neblina_i('next_book'); ?><span><?php echo esc_html($book_title($next)); ?></span></a>
        <?php else: ?><span></span><?php endif; ?>
    </nav>

    <section class="section">
        <div class="section-heading"><h2><?php echo neblina_i('other_books'); ?></h2></div>
        <?php get_template_part('template-parts/book-grid', null, [
            'per_page' => 3,
            'exclude'  => $livro->ID,
            'base'     => neblina_url('livro', $livro->post_name),
        ]); ?>
    </section>
</div>

<?php get_footer();
