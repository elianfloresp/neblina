<?php
/**
 * Pequenos helpers usados pelos templates.
 */

if (!defined('ABSPATH')) exit;

/**
 * Iniciais para retratos provisórios: primeira letra do nome + do sobrenome.
 * "Eduardo Viveiros de Castro" → "EC".
 */
function neblina_initials($name) {
    $words = preg_split('/\s+/u', trim($name));
    $first = mb_substr($words[0], 0, 1);
    $last  = count($words) > 1 ? mb_substr(end($words), 0, 1) : '';
    return mb_strtoupper($first . $last);
}

/**
 * Autor de um livro: o cadastrado em Autores (com link) ou, se não houver,
 * o nome digitado no campo "Nome do autor" (sem link).
 *
 * @return array{name: string, url: ?string}|null
 */
function neblina_book_author($book_id) {
    $rel = get_field('autor_relacionado', $book_id);
    if ($rel) {
        $autor = is_object($rel) ? $rel : get_post($rel);
        if ($autor) {
            return ['name' => get_the_title($autor), 'url' => neblina_url('autor', $autor->post_name)];
        }
    }
    $nome = trim((string) get_field('autor_nome', $book_id));
    return $nome !== '' ? ['name' => $nome, 'url' => null] : null;
}

/**
 * Capa do livro: imagem destacada ou capa provisória tipográfica (protótipo).
 */
function neblina_book_cover($book_id, $title, $size = 'neblina-book-cover') {
    $capa = get_the_post_thumbnail_url($book_id, $size);
    if ($capa) {
        printf('<img src="%s" alt="%s">', esc_url($capa), esc_attr(neblina_i('cover_of') . ' ' . $title));
        return;
    }
    ?>
    <div class="cover" aria-hidden="true">
        <small><?php echo neblina_i('cover_kicker'); ?></small>
        <strong><?php echo neblina_i('cover_title'); ?></strong>
        <span class="wave brand-symbol"></span>
        <small><?php echo neblina_i('cover_provisional'); ?></small>
    </div>
    <?php
}

/**
 * Retrato provisório de autor (iniciais sobre cor) — authorPlaceholder() do protótipo.
 * Se o autor tiver imagem destacada, mostra a foto no mesmo quadro.
 */
function neblina_author_portrait($autor, $index) {
    $name = get_the_title($autor);
    $foto = get_the_post_thumbnail_url($autor->ID, 'neblina-author-portrait');
    ?>
    <div class="author-portrait tone-<?php echo (int) $index % 3; ?>" role="img"
         aria-label="<?php echo esc_attr(($foto ? neblina_i('portrait_of') : neblina_i('portrait_slot')) . ' ' . $name); ?>">
        <?php if ($foto): ?>
            <img src="<?php echo esc_url($foto); ?>" alt="">
        <?php else: ?>
            <span class="author-initials" aria-hidden="true"><?php echo esc_html(neblina_initials($name)); ?></span>
            <span class="portrait-caption" aria-hidden="true"><?php echo neblina_i('portrait_pending'); ?></span>
        <?php endif; ?>
    </div>
    <?php
}
