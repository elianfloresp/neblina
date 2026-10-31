<?php
/**
 * Card de autor — usado na lista de autores.
 *
 * @param WP_Post $args['post']
 * @param int     $args['index']
 */
$post   = $args['post'];
$index  = $args['index'] ?? 0;
$genero = neblina_field('genero', $post->ID);
$micro  = neblina_field('micro',  $post->ID);
$retrato = get_the_post_thumbnail_url($post->ID, 'neblina-author-portrait');
$url    = neblina_url('autor', $post->post_name);
$name   = get_the_title($post);
$initials = mb_strtoupper(mb_substr($name, 0, 2));
?>
<article class="author-card">
    <a class="author-card-link" href="<?php echo esc_url($url); ?>">
        <div class="author-portrait tone-<?php echo $index % 3; ?>">
            <?php if ($retrato): ?>
                <img src="<?php echo esc_url($retrato); ?>" alt="<?php echo esc_attr($name); ?>">
            <?php else: ?>
                <span class="author-initials" aria-hidden="true"><?php echo esc_html($initials); ?></span>
            <?php endif; ?>
        </div>
        <div class="author-card-meta">
            <?php if ($genero): ?><span><?php echo esc_html($genero); ?></span><?php endif; ?>
            <span aria-hidden="true"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
        </div>
        <h2><?php echo esc_html($name); ?></h2>
        <?php if ($micro): ?><p><?php echo esc_html($micro); ?></p><?php endif; ?>
        <span class="text-link"><?php echo neblina_i('know_author'); ?></span>
    </a>
</article>
