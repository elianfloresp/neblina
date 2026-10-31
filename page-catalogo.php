<?php
/**
 * Catálogo — listagem paginada de livros.
 */
get_header();

$per_page = 6;
$paged    = max(1, (int) ($_GET['page'] ?? 1));

$query = new WP_Query([
    'post_type'      => 'livro',
    'posts_per_page' => $per_page,
    'paged'          => $paged,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>

<div class="shell">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'NOSSOS LIVROS' : 'NUESTROS LIBROS'; ?></div>
        <h1><?php echo neblina_lang() === 'pt' ? 'O catálogo.' : 'El catálogo.'; ?></h1>
        <p><?php echo neblina_lang() === 'pt' ? 'Vozes brasileiras, novas travessias.' : 'Voces brasileñas, nuevas travesías.'; ?></p>
    </div>

    <section class="section">
        <?php if (!$query->have_posts()): ?>
            <p class="note">
                <?php echo neblina_lang() === 'pt'
                    ? 'Nenhum livro cadastrado ainda. Vai no admin WP > Livros > Adicionar.'
                    : 'Ningún libro registrado todavía. Ve al admin WP > Livros > Añadir.'; ?>
            </p>
        <?php else: ?>
            <div class="books" id="book-list" tabindex="-1">
                <?php $i = 0; while ($query->have_posts()): $query->the_post(); ?>
                    <?php get_template_part('template-parts/book-card', null, ['post' => get_post(), 'index' => $i++]); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <?php if ($query->max_num_pages > 1): ?>
                <nav class="pagination" aria-label="<?php esc_attr_e('Páginas de livros', 'neblina'); ?>">
                    <?php
                    $base = neblina_url('livros');
                    for ($p = 1; $p <= $query->max_num_pages; $p++):
                        $url = $base . ($p > 1 ? '?page=' . $p : '');
                        $cur = $p === $paged;
                        ?>
                        <a href="<?php echo esc_url($url); ?>" class="page-link" <?php echo $cur ? 'aria-current="page"' : ''; ?>>
                            <?php echo $p; ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<?php get_footer();
