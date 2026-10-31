<?php
/**
 * Conteúdo de "página não encontrada" — missing() do protótipo.
 */
?>
<div class="shell">
    <div class="page-intro">
        <div class="eyebrow">404</div>
        <h1><?php echo neblina_i('error_404_title'); ?></h1>
    </div>
    <p><a class="text-link" href="<?php echo esc_url(neblina_home_url()); ?>"><?php echo neblina_i('back_to_home'); ?></a></p>
</div>
