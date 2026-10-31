<?php
/**
 * Contato — contact() do protótipo. Fixo no código.
 */
get_header();

$pt       = neblina_lang() === 'pt';
$whatsapp = '555499981726';
$email    = 'hola@editorialneblina.com';
?>

<div class="shell">
    <div class="page-intro">
        <div class="eyebrow"><?php echo neblina_i('contact_kicker'); ?></div>
        <h1><?php echo neblina_i('contact_title'); ?></h1>
    </div>

    <div class="contact-layout contact-channels">
        <div class="contact-item">
            <span class="eyebrow"><?php echo $pt ? 'E-MAIL EDITORIAL' : 'CORREO EDITORIAL'; ?></span>
            <h2><a class="text-link" href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></h2>
            <p><?php echo $pt ? 'Para conversas editoriais e institucionais.' : 'Para conversaciones editoriales e institucionales.'; ?></p>
        </div>

        <div class="contact-item">
            <span class="eyebrow">INSTAGRAM</span>
            <h2><a class="text-link" href="https://www.instagram.com/neblinaeditorial/" target="_blank" rel="noopener noreferrer">@neblinaeditorial</a></h2>
            <p><?php echo $pt ? 'Acompanhe o projeto editorial.' : 'Acompaña el proyecto editorial.'; ?></p>
        </div>

        <div class="contact-item">
            <span class="eyebrow">WHATSAPP</span>
            <h2><a class="text-link" href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp: +55 54 9998-1726">+55 54 9998-1726</a></h2>
            <p><?php echo $pt ? 'Vamos conversar pelo WhatsApp.' : 'Hablemos por WhatsApp.'; ?></p>
            <a class="button" href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" rel="noopener noreferrer">Abrir WhatsApp</a>
        </div>
    </div>
</div>

<?php get_footer();
