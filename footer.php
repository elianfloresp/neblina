<?php
/**
 * Footer global — fundo azul royal.
 * Fixo no código.
 */
?>
</main><!-- /#main -->

<footer class="footer">
    <div class="shell">
        <div class="footer-top">
            <div class="footer-brand">
                <a class="brand" href="<?php echo esc_url(neblina_home_url()); ?>">
                    <img class="brand-light" src="<?php echo esc_url(NEBLINA_URI . '/assets/images/brand/logo-light.png'); ?>" alt="Neblina Editorial">
                </a>
                <p><?php echo neblina_i('footer_tagline'); ?></p>
                <span class="til footer-til" aria-hidden="true"></span>
            </div>

            <div class="footer-links">
                <a href="<?php echo esc_url(neblina_url('livros')); ?>"><?php echo neblina_i('nav_books'); ?></a>
                <a href="<?php echo esc_url(neblina_url('sobre')); ?>"><?php echo neblina_i('nav_about'); ?></a>
                <a href="<?php echo esc_url(neblina_url('contato')); ?>"><?php echo neblina_i('nav_contact'); ?></a>
                <a href="https://www.instagram.com/neblinaeditorial/" target="_blank" rel="noopener noreferrer">Instagram</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>Neblina Editorial · Brasil / España</span>
            <span><?php echo neblina_i('footer_credits'); ?></span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
