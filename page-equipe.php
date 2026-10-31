<?php
/**
 * Equipe editorial — 3 pessoas fixas (Rafa, João, Paulo).
 */
get_header();

$equipe = [
    [
        'id'   => 'rafael',
        'name' => 'Rafael Farina',
        'bio_es' => 'Publicista de formación y autor de «Falhas que só existem no Sul» (2018) y «Ossos açucarados» (2020), ambos de poesía. Ha participado en concursos, talleres y festivales literarios como alumno, asistente, moderador y ponente. Integra el cuerpo editorial de Neblina.',
        'bio_pt' => 'Publicitário de formação e autor de "Falhas que só existem no Sul" (2018) e "Ossos açucarados" (2020), ambos de poesia. Participou de concursos, oficinas e festivais literários como aluno, assistente, moderador e palestrante. Integra o corpo editorial da Neblina.',
    ],
    [
        'id'   => 'joao',
        'name' => 'João Nunes Junior',
        'bio_es' => 'Escritor y abogado brasileño. En 2023 se incorporó al mercado editorial como socio de Diadorim Editora, en Brasil. Trabaja también en producción audiovisual y escritura de argumentos y guiones cinematográficos. Autor de «A Parte Viva da Noite» (2019) y «Primeiro Museu da Solidão», todavía inédito.',
        'bio_pt' => 'Escritor e advogado brasileiro. Em 2023 ingressou no mercado editorial como sócio da Diadorim Editora no Brasil. Trabalha também com produção audiovisual e escrita de argumentos e roteiros cinematográficos. Autor de "A Parte Viva da Noite" (2019) e "Primeiro Museu da Solidão", ainda inédito.',
    ],
    [
        'id'   => 'paulo',
        'name' => 'Paulo Silva',
        'bio_es' => 'Biografía pendiente de recibir y validar con el equipo editorial.',
        'bio_pt' => 'Biografia a receber e validar com a equipe editorial.',
    ],
];
?>

<div class="shell">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'A EQUIPE' : 'EL EQUIPO'; ?></div>
        <h1><?php echo neblina_lang() === 'pt' ? 'Três olhares.<br>Uma travessia.' : 'Tres miradas.<br>Una travesía.'; ?></h1>
    </div>

    <section class="section">
        <div class="team-grid team-grid-full">
            <?php foreach ($equipe as $pessoa): ?>
                <article class="person">
                    <?php get_template_part('template-parts/team-portrait', null, ['pessoa' => $pessoa]); ?>
                    <h3><?php echo esc_html($pessoa['name']); ?></h3>
                    <p class="role"><?php echo neblina_i('editorial_team'); ?></p>
                    <p><?php echo esc_html($pessoa['bio_' . neblina_lang()] ?? $pessoa['bio_es']); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php get_footer();
