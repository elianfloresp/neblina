<?php
/**
 * Equipe — teamPage() do protótipo.
 */
get_header();
?>

<div class="shell">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_i('team_kicker'); ?></div>
        <h1><?php echo neblina_i('team_title'); ?></h1>
    </div>
    <section class="section">
        <?php get_template_part('template-parts/team-grid', null, ['full' => true]); ?>
    </section>
</div>

<?php get_footer();
