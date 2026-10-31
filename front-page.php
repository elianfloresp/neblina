<?php
/**
 * Home — home() do protótipo: banners + livros + manifesto + equipe.
 */
get_header();

$banners = get_posts([
    'post_type'      => 'banner',
    'posts_per_page' => 10,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
$inst = neblina_institutional();
?>

<div class="shell">
    <section class="banner-carousel" aria-label="<?php echo esc_attr(neblina_i('banner_region')); ?>">
        <?php if (empty($banners)): ?>
            <div class="banner-empty">
                <div class="banner-content">
                    <span class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'MANIFESTO NEBLINA' : 'MANIFIESTO NEBLINA'; ?></span>
                    <h1 class="banner-title"><?php echo esc_html($inst[0]); ?></h1>
                </div>
            </div>
        <?php else: ?>
            <div class="banner-slides">
                <?php foreach ($banners as $i => $banner):
                    $img     = get_the_post_thumbnail_url($banner->ID, 'neblina-banner');
                    $alt     = get_post_meta(get_post_thumbnail_id($banner->ID), '_wp_attachment_image_alt', true);
                    $titulo  = neblina_field('titulo', $banner->ID) ?: get_the_title($banner);
                    $eyebrow = neblina_field('eyebrow', $banner->ID);
                    $link    = get_field('link_destino', $banner->ID);
                    $tag     = $i === 0 ? 'h1' : 'h2';
                    ?>
                    <article class="home-banner banner-image" data-slide="<?php echo $i; ?>" role="group"
                             aria-roledescription="slide" aria-label="<?php echo ($i + 1) . ' ' . neblina_i('of') . ' ' . count($banners); ?>"
                             <?php echo $i !== 0 ? 'hidden' : ''; ?>>
                        <?php if ($img): ?>
                            <img class="banner-photo" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($alt); ?>" <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
                        <?php endif; ?>
                        <div class="banner-content">
                            <?php if ($eyebrow): ?><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
                            <<?php echo $tag; ?> class="banner-title"><?php echo esc_html($titulo); ?></<?php echo $tag; ?>>
                            <?php if ($link): ?><a class="text-link" href="<?php echo esc_url($link); ?>"><?php echo neblina_i('know_book'); ?></a><?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <?php if (count($banners) > 1): ?>
                <div class="banner-pagination">
                    <div class="banner-pages">
                        <?php foreach ($banners as $i => $banner): ?>
                            <button class="banner-page" type="button" data-banner-page="<?php echo $i; ?>" aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>">
                                <?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <span class="banner-position" aria-live="polite">1 / <?php echo count($banners); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <section class="section home-books">
        <div class="section-heading">
            <h2><?php echo esc_html($inst[11]); ?></h2>
            <a class="text-link" href="<?php echo esc_url(neblina_url('livros')); ?>"><?php echo neblina_i('see_catalog'); ?></a>
        </div>
        <?php get_template_part('template-parts/book-grid', null, ['per_page' => 3, 'base' => neblina_home_url()]); ?>
        <p class="note"><?php echo neblina_i('catalog_note'); ?></p>
    </section>
</div>

<section class="manifesto">
    <div class="shell">
        <div><span class="eyebrow"><?php echo neblina_i('manifesto_kicker'); ?></span></div>
        <div>
            <h2><?php echo esc_html($inst[0]); ?></h2>
            <p><?php echo esc_html($inst[1]); ?></p>
            <a href="<?php echo esc_url(neblina_url('sobre')); ?>" class="text-link"><?php echo neblina_i('know_neblina'); ?></a>
        </div>
    </div>
</section>

<section class="team-preview shell">
    <div class="section-heading">
        <h2><?php echo neblina_i('team_heading'); ?></h2>
        <a href="<?php echo esc_url(neblina_url('equipe')); ?>" class="text-link"><?php echo neblina_i('our_team'); ?></a>
    </div>
    <?php get_template_part('template-parts/team-grid', null, ['full' => false]); ?>
</section>

<?php get_footer();
