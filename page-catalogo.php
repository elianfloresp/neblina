<?php
/**
 * Catálogo — catalog() do protótipo: 6 livros por página.
 */
get_header();
?>

<div class="shell">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_i('catalog_kicker'); ?></div>
        <h1><?php echo neblina_i('catalog_title'); ?></h1>
        <p><?php echo neblina_i('catalog_lead'); ?></p>
    </div>
    <section class="section">
        <?php get_template_part('template-parts/book-grid', null, ['per_page' => 6, 'base' => neblina_url('livros')]); ?>
        <p class="note"><?php echo neblina_i('catalog_note'); ?></p>
    </section>
</div>

<?php get_footer();
