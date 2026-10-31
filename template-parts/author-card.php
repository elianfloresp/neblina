<?php
/**
 * Card de autor — authorListing() do protótipo.
 *
 * @param WP_Post $args['post']
 * @param int     $args['index']
 */
$autor  = $args['post'];
$index  = (int) ($args['index'] ?? 0);
$genero = neblina_field('genero', $autor->ID);
$micro  = neblina_field('micro', $autor->ID);
$url    = neblina_url('autor', $autor->post_name);
?>
<article class="author-card">
    <a class="author-card-link" href="<?php echo esc_url($url); ?>"
       aria-labelledby="author-name-<?php echo $autor->ID; ?>" aria-describedby="author-micro-<?php echo $autor->ID; ?>">
        <?php neblina_author_portrait($autor, $index); ?>
        <div class="author-card-meta">
            <span><?php echo esc_html($genero); ?></span>
            <span aria-hidden="true"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
        </div>
        <h2 id="author-name-<?php echo $autor->ID; ?>"><?php echo esc_html(get_the_title($autor)); ?></h2>
        <p id="author-micro-<?php echo $autor->ID; ?>"><?php echo esc_html($micro); ?></p>
        <span class="text-link"><?php echo neblina_i('know_author'); ?></span>
    </a>
</article>
