<?php
/**
 * Card de livro — usado em home e catálogo.
 *
 * @param WP_Post $args['post']  Objeto post do livro.
 * @param int     $args['index'] Índice no grid (define a cor de fundo).
 */
$post   = $args['post'];
$index  = $args['index'] ?? 0;
$titulo = neblina_field('titulo', $post->ID) ?: get_the_title($post);
$autor_rel = get_field('autor_relacionado', $post->ID);
$capa   = get_the_post_thumbnail_url($post->ID, 'neblina-book-cover');
$url    = neblina_url('livro', $post->post_name);
?>
<article class="book">
    <a href="<?php echo esc_url($url); ?>" class="book-stage" aria-label="<?php echo esc_attr($titulo); ?>">
        <?php if ($capa): ?>
            <img src="<?php echo esc_url($capa); ?>" alt="<?php echo esc_attr($titulo); ?>">
        <?php else: ?>
            <div class="cover" aria-hidden="true">
                <small>NEBLINA EDITORIAL</small>
                <strong><?php echo neblina_lang() === 'pt' ? 'Um livro<br>por vir.' : 'Un libro<br>por venir.'; ?></strong>
            </div>
        <?php endif; ?>
    </a>
    <div class="meta">
        <span><?php echo neblina_lang() === 'pt' ? 'ESTUDO DO KEY VISUAL' : 'ESTUDIO DEL KEY VISUAL'; ?></span>
        <span><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
    </div>
    <h3><a href="<?php echo esc_url($url); ?>"><?php echo esc_html($titulo); ?></a></h3>
    <?php if ($autor_rel): ?>
        <?php $autor_post = is_object($autor_rel) ? $autor_rel : get_post($autor_rel); ?>
        <?php if ($autor_post): ?>
            <p><?php echo esc_html(get_the_title($autor_post)); ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <a class="text-link" href="<?php echo esc_url($url); ?>"><?php echo neblina_i('know_book'); ?></a>
</article>
