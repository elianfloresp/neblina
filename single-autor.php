<?php
/**
 * Perfil individual de autor.
 */
get_header();

$slug  = get_query_var('neblina_slug');
$autor = $slug ? get_page_by_path($slug, OBJECT, 'autor') : null;

if (!$autor):
    get_template_part('404');
    get_footer();
    return;
endif;

$genero = neblina_field('genero', $autor->ID);
$micro  = neblina_field('micro',  $autor->ID);
$bio    = neblina_field('bio',    $autor->ID);
$retrato = get_the_post_thumbnail_url($autor->ID, 'neblina-author-portrait');

$outros = get_posts([
    'post_type'      => 'autor',
    'posts_per_page' => 20,
    'post__not_in'   => [$autor->ID],
]);
?>

<div class="shell author-profile">
    <a class="text-link author-back" href="<?php echo esc_url(neblina_url('autores')); ?>">
        <?php echo neblina_i('all_authors'); ?>
    </a>

    <section class="author-profile-top">
        <div class="author-portrait">
            <?php if ($retrato): ?>
                <img src="<?php echo esc_url($retrato); ?>" alt="<?php echo esc_attr(get_the_title($autor)); ?>">
            <?php else: ?>
                <span class="author-initials" aria-hidden="true">
                    <?php echo esc_html(mb_strtoupper(mb_substr(get_the_title($autor), 0, 2))); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="author-profile-copy">
            <?php if ($genero): ?><span class="eyebrow"><?php echo esc_html($genero); ?></span><?php endif; ?>
            <h1><?php echo esc_html(get_the_title($autor)); ?></h1>
            <?php if ($micro): ?><p class="author-lead"><?php echo esc_html($micro); ?></p><?php endif; ?>
            <?php if ($bio): ?>
                <div class="author-bio">
                    <h2><?php echo neblina_i('about_writing'); ?></h2>
                    <div><?php echo wp_kses_post($bio); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($outros): ?>
        <nav class="author-other" aria-label="<?php echo esc_attr(neblina_i('other_voices')); ?>">
            <h2><?php echo neblina_i('other_voices'); ?></h2>
            <ul>
                <?php foreach ($outros as $o): ?>
                    <li>
                        <a class="text-link" href="<?php echo esc_url(neblina_url('autor', $o->post_name)); ?>">
                            <?php echo esc_html(get_the_title($o)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php get_footer();
