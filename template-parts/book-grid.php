<?php
/**
 * Grade de livros com paginação — equivalente ao bookGrid() do protótipo.
 * Usada na home (3 por página), no catálogo (6) e em "Outras fichas" (3).
 *
 * @param int    $args['per_page'] Livros por página.
 * @param int    $args['exclude']  ID de livro a omitir (ficha atual).
 * @param string $args['base']     URL da página atual, para os links ?page=.
 */
$per     = $args['per_page'] ?? 3;
$exclude = $args['exclude'] ?? 0;
$base    = $args['base'] ?? neblina_url('livros');

// Ordem global do catálogo: define o número "01, 02…" de cada card.
$all_ids = get_posts([
    'post_type'      => 'livro',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
    'fields'         => 'ids',
]);
$ids   = array_values(array_filter($all_ids, fn($id) => $id !== $exclude));
$total = count($ids);
$pages = max(1, (int) ceil($total / $per));
$page  = min($pages, max(1, (int) ($_GET['page'] ?? 1)));
$slice = array_slice($ids, ($page - 1) * $per, $per);
$link  = fn($n) => esc_url($base . '?page=' . $n);
?>
<div id="book-list" tabindex="-1">
    <div class="books">
        <?php foreach ($slice as $id): ?>
            <?php get_template_part('template-parts/book-card', null, [
                'post'  => get_post($id),
                'index' => array_search($id, $all_ids, true),
            ]); ?>
        <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
        <div class="pagination-wrap">
            <p class="pagination-count">
                <?php printf('%s %d–%d %s %d %s', neblina_i('showing'), ($page - 1) * $per + 1, min($page * $per, $total), neblina_i('of'), $total, neblina_i('books_lower')); ?>
            </p>
            <nav class="pagination" aria-label="<?php echo esc_attr(neblina_i('book_pages')); ?>">
                <?php if ($page > 1): ?>
                    <a class="page-link" href="<?php echo $link($page - 1); ?>" rel="prev"><?php echo neblina_i('prev'); ?></a>
                <?php else: ?>
                    <span class="page-link" aria-disabled="true"><?php echo neblina_i('prev'); ?></span>
                <?php endif; ?>

                <div class="page-numbers">
                    <?php
                    $last_shown = null;
                    foreach (range(1, $pages) as $n):
                        if ($n !== 1 && $n !== $pages && abs($n - $page) > 1) continue;
                        if ($last_shown !== null && $n - $last_shown > 1) echo '<span aria-hidden="true">…</span>';
                        $last_shown = $n;
                        ?>
                        <a class="page-link" href="<?php echo $link($n); ?>"
                           aria-label="<?php echo esc_attr(neblina_i('page') . ' ' . $n); ?>"<?php echo $n === $page ? ' aria-current="page"' : ''; ?>><?php echo $n; ?></a>
                    <?php endforeach; ?>
                </div>

                <?php if ($page < $pages): ?>
                    <a class="page-link" href="<?php echo $link($page + 1); ?>" rel="next"><?php echo neblina_i('next'); ?></a>
                <?php else: ?>
                    <span class="page-link" aria-disabled="true"><?php echo neblina_i('next'); ?></span>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>
