<?php
/**
 * Retrato de pessoa da equipe. Foto fixa no tema (assets/images/team/{id}.jpg);
 * sem foto, mostra as iniciais.
 *
 * @param array $args['pessoa'] ['id' => ..., 'name' => ...]
 */
$pessoa = $args['pessoa'];
$foto   = '/assets/images/team/' . $pessoa['id'] . '.jpg';
?>
<div class="portrait <?php echo esc_attr($pessoa['id']); ?>">
    <?php if (file_exists(NEBLINA_DIR . $foto)): ?>
        <img src="<?php echo esc_url(NEBLINA_URI . $foto); ?>"
             alt="<?php echo esc_attr('Retrato de ' . $pessoa['name']); ?>"
             loading="lazy">
    <?php else: ?>
        <div class="photo-placeholder" aria-hidden="true">
            <?php echo esc_html(neblina_initials($pessoa['name'])); ?>
        </div>
    <?php endif; ?>
</div>
