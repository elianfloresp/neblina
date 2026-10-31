<?php
/**
 * Perfil do autor — authorProfile() do protótipo.
 */
$slug  = get_query_var('neblina_slug');
$autor = $slug ? get_page_by_path($slug, OBJECT, 'autor') : null;

if (!$autor):
    status_header(404);
    get_header();
    get_template_part('template-parts/not-found');
    get_footer();
    return;
endif;

get_header();

$todos = get_posts([
    'post_type'      => 'autor',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
]);
$index = (int) array_search($autor->ID, wp_list_pluck($todos, 'ID'), true);
$outros = array_filter($todos, fn($a) => $a->ID !== $autor->ID);

$genero = neblina_field('genero', $autor->ID);
$micro  = neblina_field('micro', $autor->ID);
$bio    = neblina_field('bio', $autor->ID);

// Livros desta voz: ligados em "Autor com página" ou com o mesmo nome em "Nome do autor".
$livros = get_posts([
    'post_type'      => 'livro',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
    'meta_query'     => [
        'relation' => 'OR',
        ['key' => 'autor_relacionado', 'value' => $autor->ID],
        ['key' => 'autor_nome',        'value' => get_the_title($autor)],
    ],
]);
$catalogo = get_posts([
    'post_type'      => 'livro',
    'posts_per_page' => -1,
    'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
    'fields'         => 'ids',
]);
?>

<div class="shell author-profile">
    <a class="text-link author-back" href="<?php echo esc_url(neblina_url('autores')); ?>"><?php echo neblina_i('all_authors'); ?></a>

    <section class="author-profile-top">
        <?php neblina_author_portrait($autor, $index); ?>
        <div class="author-profile-copy">
            <?php if ($genero): ?><span class="eyebrow"><?php echo esc_html($genero); ?></span><?php endif; ?>
            <h1><?php echo esc_html(get_the_title($autor)); ?></h1>
            <?php if ($micro): ?><p class="author-lead"><?php echo esc_html($micro); ?></p><?php endif; ?>
            <?php if ($bio): ?>
                <div class="author-bio">
                    <h2><?php echo neblina_i('about_writing'); ?></h2>
                    <div class="rich-text"><?php echo wp_kses_post($bio); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="author-books">
        <div class="section-heading">
            <h2><?php echo neblina_i($livros ? 'author_books' : 'editions_heading'); ?></h2>
        </div>
        <?php if ($livros): ?>
            <div class="books">
                <?php foreach ($livros as $livro): ?>
                    <?php get_template_part('template-parts/book-card', null, [
                        'post'  => $livro,
                        'index' => array_search($livro->ID, $catalogo, true),
                    ]); ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="edition-pending"><?php echo neblina_i('editions_soon'); ?></p>
        <?php endif; ?>
    </section>

    <?php if ($outros): ?>
        <nav class="author-other" aria-label="<?php echo esc_attr(neblina_i('other_voices')); ?>">
            <h2><?php echo neblina_i('other_voices'); ?></h2>
            <?php foreach ($outros as $o): ?>
                <a class="text-link" href="<?php echo esc_url(neblina_url('autor', $o->post_name)); ?>"><?php echo esc_html(get_the_title($o)); ?></a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>
</div>

<?php get_footer();
