<?php
/**
 * Autores — authorListing() do protótipo.
 */
get_header();

$autores = get_posts([
    'post_type'      => 'autor',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
?>

<div class="shell authors-page">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_i('authors_kicker'); ?></div>
        <h1><?php echo neblina_i('nav_authors'); ?></h1>
        <p><?php echo neblina_i('authors_lead'); ?></p>
    </div>

    <section class="authors-section" aria-label="<?php echo esc_attr(neblina_i('authors_region')); ?>">
        <div class="authors-intro">
            <span class="eyebrow"><?php echo neblina_i('authors_fronts'); ?></span>
            <span class="demo-label"><?php echo neblina_i('curatorship'); ?></span>
        </div>
        <div class="authors-grid">
            <?php foreach ($autores as $i => $autor): ?>
                <?php get_template_part('template-parts/author-card', null, ['post' => $autor, 'index' => $i]); ?>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php get_footer();
