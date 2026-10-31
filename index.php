<?php
/**
 * Fallback geral — usado quando nenhum template específico casa.
 * Para a Neblina, deve raramente ser renderizado (todas as rotas têm page/single específico).
 */
get_header();
?>

<section class="shell">
    <div class="page-intro">
        <h1>Neblina Editorial</h1>
        <p><?php echo neblina_i('fallback_text'); ?></p>
    </div>
</section>

<?php get_footer();
