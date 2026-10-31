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
$link_compra = neblina_field('link_compra', $livro->ID);
$buy_safe    = $link_compra && preg_match('#^https://#', $link_compra);

// [rótulo ES, rótulo PT, valor]
$metadados = [
    ['Traducción', 'Tradução', neblina_field('metadados_tradutor', $livro->ID)],
    ['ISBN',       'ISBN',     get_field('metadados_isbn', $livro->ID)],
    ['Páginas',    'Páginas',  get_field('metadados_paginas', $livro->ID)],
    ['Formato',    'Formato',  neblina_field('metadados_formato', $livro->ID)],
    ['Año',        'Ano',      get_field('metadados_ano', $livro->ID)],
];

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
            <span class="eyebrow"><?php echo neblina_i('visual_reference'); ?></span>
            <h1><?php echo esc_html($titulo); ?></h1>
            <?php if ($autor): ?>
                <p><?php if ($autor['url']): ?><a href="<?php echo esc_url($autor['url']); ?>"><?php echo esc_html($autor['name']); ?></a><?php else: echo esc_html($autor['name']); endif; ?></p>
            <?php endif; ?>

            <h2><?php echo neblina_i('about_reference'); ?></h2>
            <div class="book-synopsis rich-text"><?php echo $sinopse ? wp_kses_post($sinopse) : '<p>' . esc_html(neblina_i('to_confirm')) . '</p>'; ?></div>

            <?php if ($buy_safe): ?>
                <a class="button" href="<?php echo esc_url($link_compra); ?>" target="_blank" rel="noopener noreferrer"><?php echo neblina_i('buy'); ?></a>
            <?php else: ?>
                <button class="button" type="button" disabled aria-describedby="buy-note"><?php echo neblina_i('buy_disabled'); ?></button>
                <p class="note" id="buy-note"><?php echo neblina_i('buy_pending'); ?></p>
            <?php endif; ?>

            <h2><?php echo neblina_i('editorial_info'); ?></h2>
            <dl class="editorial-data">
                <?php foreach ($metadados as [$es, $pt, $valor]): ?>
                    <div>
                        <dt><?php echo esc_html(neblina_lang() === 'pt' ? $pt : $es); ?></dt>
                        <dd><?php echo esc_html($valor ?: neblina_i('to_confirm')); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>

            <details>
                <summary><?php echo neblina_i('read_excerpt'); ?></summary>
                <div class="book-excerpt rich-text"><?php echo $excerto ? wp_kses_post($excerto) : '<p>' . esc_html(neblina_i('to_confirm')) . '</p>'; ?></div>
            </details>
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
        <div class="section-heading"><h2><?php echo neblina_i('other_sheets'); ?></h2></div>
        <?php get_template_part('template-parts/book-grid', null, [
            'per_page' => 3,
            'exclude'  => $livro->ID,
            'base'     => neblina_url('livro', $livro->post_name),
        ]); ?>
    </section>
</div>

<?php get_footer();
