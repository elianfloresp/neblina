<?php
/**
 * Card de livro — card() do protótipo.
 *
 * @param WP_Post $args['post']  Livro.
 * @param int     $args['index'] Posição no catálogo (numeração 01, 02…).
 */
$livro  = $args['post'];
$index  = (int) ($args['index'] ?? 0);
$titulo = neblina_field('titulo', $livro->ID) ?: get_the_title($livro);
$autor  = neblina_book_author($livro->ID);
$url    = neblina_url('livro', $livro->post_name);
?>
<article class="book">
    <a href="<?php echo esc_url($url); ?>" class="book-stage" aria-label="<?php echo esc_attr(neblina_i('see_sheet') . ' ' . $titulo); ?>">
        <?php neblina_book_cover($livro->ID, $titulo); ?>
    </a>
    <div class="meta">
        <span><?php echo neblina_i('card_meta'); ?></span>
        <span><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
    </div>
    <h3><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($titulo); ?></a></h3>
    <?php if ($autor): ?>
        <p><?php echo esc_html($autor['name']); ?></p>
    <?php endif; ?>
    <a class="text-link" href="<?php echo esc_url($url); ?>"><?php echo neblina_i('know_book'); ?></a>
</article>
