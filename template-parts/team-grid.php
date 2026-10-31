<?php
/**
 * Grade da equipe — team() do protótipo. Fotos fixas em assets/images/team/{id}.jpg.
 *
 * @param bool $args['full'] Mostra a bio (página Equipe).
 */
$full = !empty($args['full']);
?>
<div class="team-grid">
    <?php foreach (neblina_team() as $pessoa):
        $foto = '/assets/images/team/' . $pessoa['id'] . '.jpg'; ?>
        <article class="person">
            <div class="portrait <?php echo esc_attr($pessoa['id']); ?>">
                <div class="photo-window">
                    <?php if (file_exists(NEBLINA_DIR . $foto)): ?>
                        <img src="<?php echo esc_url(NEBLINA_URI . $foto); ?>" alt="<?php echo esc_attr(neblina_i('portrait_of') . ' ' . $pessoa['name']); ?>" loading="lazy">
                    <?php else: ?>
                        <span class="photo-placeholder" aria-hidden="true"><?php echo esc_html(neblina_initials($pessoa['name'])); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <h3><?php echo esc_html($pessoa['name']); ?></h3>
            <p class="role"><?php echo neblina_i('editorial_team'); ?></p>
            <?php if ($full): ?><p><?php echo esc_html($pessoa['bio']); ?></p><?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>
