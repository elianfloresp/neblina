<?php
/**
 * Contato — email, instagram, whatsapp. Fixo no código.
 */
get_header();

$whatsapp = '555499981726';
$email    = 'hola@editorialneblina.com';
?>

<div class="shell">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'CONTATO' : 'CONTACTO'; ?></div>
        <h1><?php echo neblina_lang() === 'pt' ? 'Toda travessia começa<br>com uma conversa.' : 'Toda travesía empieza<br>con una conversación.'; ?></h1>
    </div>

    <section class="contact-layout contact-channels">
        <article class="contact-item">
            <span class="eyebrow"><?php echo neblina_lang() === 'pt' ? 'E-MAIL EDITORIAL' : 'CORREO EDITORIAL'; ?></span>
            <h2>
                <a class="text-link" href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
            </h2>
            <p><?php echo neblina_lang() === 'pt' ? 'Para conversas editoriais e institucionais.' : 'Para conversaciones editoriales e institucionales.'; ?></p>
        </article>

        <article class="contact-item">
            <span class="eyebrow">INSTAGRAM</span>
            <h2>
                <a class="text-link" href="https://www.instagram.com/neblinaeditorial/" target="_blank" rel="noopener noreferrer">@neblinaeditorial</a>
            </h2>
            <p><?php echo neblina_lang() === 'pt' ? 'Acompanhe o projeto editorial.' : 'Acompaña el proyecto editorial.'; ?></p>
        </article>

        <article class="contact-item">
            <span class="eyebrow">WHATSAPP</span>
            <h2>
                <a class="text-link" href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" rel="noopener noreferrer">+55 54 9998-1726</a>
            </h2>
            <p><?php echo neblina_lang() === 'pt' ? 'Vamos conversar pelo WhatsApp.' : 'Hablemos por WhatsApp.'; ?></p>
            <a class="button" href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" rel="noopener noreferrer">
                <?php echo neblina_lang() === 'pt' ? 'Abrir WhatsApp' : 'Abrir WhatsApp'; ?>
            </a>
        </article>
    </section>
</div>

<?php get_footer();
