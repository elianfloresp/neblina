<?php
/**
 * Sobre — about() do protótipo: texto institucional completo (13 blocos).
 */
get_header();

$inst = neblina_institutional();
?>

<div class="shell institutional-page">
    <div class="page-intro">
        <div class="eyebrow">NEBLINA EDITORIAL</div>
        <h1><?php echo esc_html($inst[0]); ?></h1>
    </div>

    <section class="about-grid">
        <div class="about-symbol" aria-hidden="true"><span class="til"></span></div>
        <div class="about-copy"><p><?php echo esc_html($inst[1]); ?></p></div>
    </section>

    <?php foreach ([[2, 4], [4, 7], [7, 13]] as [$start, $end]): ?>
        <section class="identity-story">
            <div></div>
            <div>
                <h2><?php echo esc_html($inst[$start]); ?></h2>
                <?php for ($i = $start + 1; $i < $end; $i++): ?>
                    <p><?php echo esc_html($inst[$i]); ?></p>
                <?php endfor; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<?php get_footer();
