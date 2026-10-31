<?php
/**
 * 404 — página não encontrada.
 */
get_header();
?>

<section class="shell">
    <div class="page-intro">
        <div class="eyebrow">404</div>
        <h1><?php echo neblina_i('error_404_title'); ?></h1>
        <p>
            <a class="text-link" href="<?php echo esc_url(neblina_home_url()); ?>">
                <?php echo neblina_i('back_to_home'); ?>
            </a>
        </p>
    </div>
</section>

<?php get_footer();
