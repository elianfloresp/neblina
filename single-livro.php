<?php
/**
 * Ficha individual de um livro.
 */
get_header();

$slug  = get_query_var('neblina_slug');
$livro = $slug ? get_page_by_path($slug, OBJECT, 'livro') : null;

if (!$livro):
    get_template_part('404');
    get_footer();
    return;
endif;

$titulo      = neblina_field('titulo', $livro->ID) ?: get_the_title($livro);
$sinopse     = neblina_field('sinopse', $livro->ID);
$excerto     = neblina_field('excerto', $livro->ID);
$capa        = get_the_post_thumbnail_url($livro->ID, 'neblina-book-cover');
$autor_rel   = get_field('autor_relacionado', $livro->ID);
$link_compra = neblina_field('link_compra', $livro->ID);
$buy_safe    = $link_compra && preg_match('#^https://#', $link_compra);

$metadados = [
    ['key' => 'tradutor', 'es' => 'Traducción', 'pt' => 'Tradução', 'lang' => true],
    ['key' => 'isbn',     'es' => 'ISBN',       'pt' => 'ISBN',     'lang' => false],
    ['key' => 'paginas',  'es' => 'Páginas',    'pt' => 'Páginas',  'lang' => false],
    ['key' => 'formato',  'es' => 'Formato',    'pt' => 'Formato',  'lang' => true],
    ['key' => 'ano',      'es' => 'Año',        'pt' => 'Ano',      'lang' => false],
];

// Prev / next pela ordem (menu_order)
$all_livros = get_posts([
    'post_type'      => 'livro',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'fields'         => 'ids',
]);
$ids   = $all_livros;
$pos   = array_search($livro->ID, $ids, true);
$prev  = $pos > 0 ? get_post($ids[$pos - 1]) : null;
$next  = ($pos !== false && $pos < count($ids) - 1) ? get_post($ids[$pos + 1]) : null;
?>

<div class="shell book-detail-wrapper">
    <div class="book-back">
        <a class="text-link" href="<?php echo esc_url(neblina_url('livros')); ?>">
            <?php echo neblina_i('back_to_catalog'); ?>
        </a>
    </div>

    <section class="book-detail">
        <div class="detail-art">
            <?php if ($capa): ?>
                <img src="<?php echo esc_url($capa); ?>" alt="<?php echo esc_attr($titulo); ?>">
            <?php else: ?>
                <div class="cover">
                    <small>NEBLINA EDITORIAL</small>
                    <strong><?php echo neblina_lang() === 'pt' ? 'Um livro<br>por vir.' : 'Un libro<br>por venir.'; ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <div class="book-info">
            <span class="eyebrow book-eyebrow"><?php echo neblina_lang() === 'pt' ? 'REFERÊNCIA VISUAL' : 'REFERENCIA VISUAL'; ?></span>

            <h1><?php echo esc_html($titulo); ?></h1>

            <?php if ($autor_rel): ?>
                <?php $autor_post = is_object($autor_rel) ? $autor_rel : get_post($autor_rel); ?>
                <?php if ($autor_post): ?>
                    <p class="book-author">
                        <a href="<?php echo esc_url(neblina_url('autor', $autor_post->post_name)); ?>">
                            <?php echo esc_html(get_the_title($autor_post)); ?>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <h2 class="book-section-title"><?php echo neblina_lang() === 'pt' ? 'Sobre o livro' : 'Sobre el libro'; ?></h2>
            <div class="book-synopsis">
                <?php echo $sinopse ? wp_kses_post($sinopse) : '<p>' . esc_html(neblina_i('to_confirm')) . '</p>'; ?>
            </div>

            <?php if ($buy_safe): ?>
                <a class="button" href="<?php echo esc_url($link_compra); ?>" target="_blank" rel="noopener noreferrer">
                    <?php echo neblina_i('buy'); ?>
                </a>
            <?php else: ?>
                <button class="button" disabled><?php echo neblina_lang() === 'pt' ? 'Comprar livro' : 'Comprar libro'; ?></button>
                <p class="note"><?php echo neblina_i('buy_pending'); ?></p>
            <?php endif; ?>

            <h2 class="book-section-title"><?php echo neblina_lang() === 'pt' ? 'Informações editoriais' : 'Información editorial'; ?></h2>
            <dl class="editorial-data">
                <?php foreach ($metadados as $m):
                    $field_name = $m['lang']
                        ? 'metadados_' . $m['key']
                        : 'metadados_' . $m['key'];
                    $value = $m['lang']
                        ? neblina_field($field_name, $livro->ID)
                        : get_field($field_name, $livro->ID);
                ?>
                    <div>
                        <dt><?php echo esc_html($m[neblina_lang()] ?? $m['es']); ?></dt>
                        <dd><?php echo $value ? esc_html($value) : esc_html(neblina_i('to_confirm')); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>

            <?php if ($excerto): ?>
                <details>
                    <summary><?php echo neblina_i('read_excerpt'); ?></summary>
                    <div class="book-excerpt"><?php echo wp_kses_post($excerto); ?></div>
                </details>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($prev || $next): ?>
        <nav class="book-sequence" aria-label="<?php esc_attr_e('Navegar entre livros', 'neblina'); ?>">
            <?php if ($prev): ?>
                <a class="text-link" href="<?php echo esc_url(neblina_url('livro', $prev->post_name)); ?>">
                    <?php echo neblina_i('prev_book'); ?>
                    <span><?php echo esc_html(neblina_field('titulo', $prev->ID) ?: get_the_title($prev)); ?></span>
                </a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>

            <?php if ($next): ?>
                <a class="text-link" href="<?php echo esc_url(neblina_url('livro', $next->post_name)); ?>">
                    <?php echo neblina_i('next_book'); ?>
                    <span><?php echo esc_html(neblina_field('titulo', $next->ID) ?: get_the_title($next)); ?></span>
                </a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</div>

<?php get_footer();
