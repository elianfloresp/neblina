<?php
/**
 * Home — carrossel + livros em destaque + manifesto + equipe.
 */
get_header();

// Query de banners (CPT banner)
$banners = get_posts([
    'post_type'      => 'banner',
    'posts_per_page' => 10,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

// Query de livros em destaque (CPT livro)
$livros = get_posts([
    'post_type'      => 'livro',
    'posts_per_page' => 3,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

// Equipe editorial (hard-coded no array abaixo — fixo, não vem do admin)
$equipe = [
    [
        'id'   => 'rafael',
        'name' => 'Rafael Farina',
        'bio_es' => 'Publicista de formación y autor de «Falhas que só existem no Sul» (2018) y «Ossos açucarados» (2020), ambos de poesía. Integra el cuerpo editorial de Neblina.',
        'bio_pt' => 'Publicitário de formação e autor de "Falhas que só existem no Sul" (2018) e "Ossos açucarados" (2020), ambos de poesia. Integra o corpo editorial da Neblina.',
    ],
    [
        'id'   => 'joao',
        'name' => 'João Nunes Junior',
        'bio_es' => 'Escritor y abogado brasileño. En 2023 se incorporó al mercado editorial como socio de Diadorim Editora.',
        'bio_pt' => 'Escritor e advogado brasileiro. Em 2023 ingressou no mercado editorial como sócio da Diadorim Editora.',
    ],
    [
        'id'   => 'paulo',
        'name' => 'Paulo Silva',
        'bio_es' => 'Biografía pendiente de recibir y validar con el equipo editorial.',
        'bio_pt' => 'Biografia a receber e validar com a equipe editorial.',
    ],
];
?>

<section class="banner-carousel" aria-label="<?php echo esc_attr(neblina_i('primary_nav')); ?>">
    <?php if (empty($banners)): ?>
        <div class="shell banner-empty">
            <div class="banner-content">
                <span class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'MANIFESTO NEBLINA' : 'MANIFIESTO NEBLINA'; ?></span>
                <h1 class="banner-title"><?php echo neblina_lang() === 'pt'
                    ? 'Editorial Neblina: Uma Força Vivificante do Pensamento e da Literatura Brasileira na Espanha'
                    : 'Editorial Neblina: Una Fuerza Vivificante del Pensamiento y de la Literatura Brasileña en España'; ?></h1>
            </div>
        </div>
    <?php else: ?>
        <div class="banner-slides">
            <?php foreach ($banners as $i => $banner): ?>
                <?php
                $img       = get_the_post_thumbnail_url($banner->ID, 'neblina-banner');
                $titulo    = neblina_field('titulo', $banner->ID) ?: get_the_title($banner);
                $eyebrow   = neblina_field('eyebrow', $banner->ID);
                $link      = get_field('link_destino', $banner->ID);
                ?>
                <article class="home-banner banner-image" data-slide="<?php echo $i; ?>" <?php echo $i !== 0 ? 'hidden' : ''; ?>>
                    <?php if ($img): ?>
                        <img class="banner-photo" src="<?php echo esc_url($img); ?>" alt="" <?php echo $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
                    <?php endif; ?>
                    <div class="banner-content">
                        <?php if ($eyebrow): ?><span class="eyebrow"><?php echo esc_html($eyebrow); ?></span><?php endif; ?>
                        <<?php echo $i === 0 ? 'h1' : 'h2'; ?> class="banner-title"><?php echo esc_html($titulo); ?></<?php echo $i === 0 ? 'h1' : 'h2'; ?>>
                        <?php if ($link): ?><a class="text-link" href="<?php echo esc_url($link); ?>"><?php echo neblina_i('know_book'); ?></a><?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if (count($banners) > 1): ?>
            <div class="banner-pagination shell">
                <div class="banner-pages">
                    <?php foreach ($banners as $i => $banner): ?>
                        <button class="banner-page" data-banner-page="<?php echo $i; ?>" aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>">
                            <?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <span class="banner-position" aria-live="polite">1 / <?php echo count($banners); ?></span>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<section class="home-books shell">
    <div class="section-heading">
        <h2><?php echo neblina_lang() === 'pt'
            ? 'O Brasil transformou-se em artigo de desejo.'
            : 'Brasil se ha convertido en artículo de deseo.'; ?></h2>
        <a class="text-link" href="<?php echo esc_url(neblina_url('livros')); ?>"><?php echo neblina_i('see_catalog'); ?></a>
    </div>

    <?php if (empty($livros)): ?>
        <p class="note">
            <?php echo neblina_lang() === 'pt'
                ? 'Catálogo ainda não cadastrado no admin.'
                : 'Catálogo aún no registrado en el admin.'; ?>
        </p>
    <?php else: ?>
        <div class="books">
            <?php foreach ($livros as $i => $livro): ?>
                <?php get_template_part('template-parts/book-card', null, ['post' => $livro, 'index' => $i]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="manifesto">
    <div class="shell">
        <div>
            <span class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'MUITO PRAZER, SOMOS NEBLINA' : 'MUCHO GUSTO, SOMOS NEBLINA'; ?></span>
            <span class="til" aria-hidden="true"></span>
        </div>
        <div>
            <h2><?php echo neblina_lang() === 'pt'
                ? 'Editorial Neblina: Uma Força Vivificante do Pensamento e da Literatura Brasileira na Espanha'
                : 'Editorial Neblina: Una Fuerza Vivificante del Pensamiento y de la Literatura Brasileña en España'; ?></h2>
            <p><?php echo neblina_lang() === 'pt'
                ? 'O nome Neblina, mantido em português, não é uma escolha meramente estética; ele nasce de uma raiz profunda e ancestral. O termo é a tradução de um conceito central da cosmologia guarani: tataxĩna.'
                : 'El nombre Neblina, mantenido en portugués, no es una elección meramente estética; nace de una raíz profunda y ancestral. El término es la traducción de un concepto central de la cosmología guaraní: tataxĩna.'; ?></p>
            <a href="<?php echo esc_url(neblina_url('sobre')); ?>" class="text-link"><?php echo neblina_lang() === 'pt' ? 'Conhecer a Neblina' : 'Conocer Neblina'; ?></a>
        </div>
    </div>
</section>

<section class="team-preview shell">
    <div class="section-heading">
        <h2><?php echo neblina_lang() === 'pt' ? 'Quem faz a Neblina' : 'Quienes hacen Neblina'; ?></h2>
        <a class="text-link" href="<?php echo esc_url(neblina_url('equipe')); ?>"><?php echo neblina_lang() === 'pt' ? 'Nossa equipe' : 'Nuestro equipo'; ?></a>
    </div>
    <div class="team-grid">
        <?php foreach ($equipe as $pessoa): ?>
            <article class="person">
                <div class="portrait <?php echo esc_attr($pessoa['id']); ?>">
                    <div class="photo-placeholder" aria-hidden="true">
                        <?php echo esc_html(mb_strtoupper(mb_substr($pessoa['name'], 0, 2))); ?>
                    </div>
                </div>
                <h3><?php echo esc_html($pessoa['name']); ?></h3>
                <p class="role"><?php echo neblina_i('editorial_team'); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php get_footer();
