<?php
/**
 * Autores — listagem.
 */
get_header();

$autores = get_posts([
    'post_type'      => 'autor',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>

<div class="shell authors-page">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'VOZES DO CATÁLOGO' : 'VOCES DEL CATÁLOGO'; ?></div>
        <h1>Autores</h1>
        <p><?php echo neblina_lang() === 'pt' ? 'Cada voz, uma forma de atravessar a neblina.' : 'Cada voz, una forma de atravesar la neblina.'; ?></p>
    </div>

    <section class="authors-section">
        <?php if (empty($autores)): ?>
            <p class="note">
                <?php echo neblina_lang() === 'pt'
                    ? 'Nenhum autor cadastrado ainda. Vai no admin WP > Autores > Adicionar.'
                    : 'Ningún autor registrado todavía.'; ?>
            </p>
        <?php else: ?>
            <div class="authors-grid">
                <?php foreach ($autores as $i => $autor): ?>
                    <?php get_template_part('template-parts/author-card', null, ['post' => $autor, 'index' => $i]); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php get_footer();
